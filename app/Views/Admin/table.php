<link href="https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css" type="text/css" rel="stylesheet" media="screen,projection" />
<script src="http://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.7/js/materialize.min.js"></script>


<div class="row table-responsive">
    <table class="table datatable" id="example"></table>
</div>

<?php 
    $my_activities = $db->table('volunteer_activities')
        ->select('volunteer_activities.*, activities.city_id')
        ->join('activities', 'activities.id = volunteer_activities.activity_id')
        ->get()->getResult(); 
    
    if (empty($my_activities)) 
    {
        echo "No activities found.";
    }
    else
    {

    $dataset = '[';
    foreach ($my_activities as $row) {
        if (!isset($row->id)) {
            echo "Error: Missing 'ID' in the result set.";
            exit;
        }        

        $link1 = 'activity?id=' . $row->id;                    
        $link2 = 'certificate?id=' . $row->id;
        $statuses = $db->table('activities_status')->get()->getResult();

        $dataset .= '["<input class=\'selecting\' onchange=\'selectfunction(this.id)\' type=\'checkbox\' id=' . $row->id . '></input>",'
            . '"' . $row->id . '",'
            . '"' . $db->table('activities')->where('id', $row->activity_id)->get()->getRow()->name . '",'
            . '"' . $db->table('volunteers')->where('id', $row->volunteer_id)->get()->getRow()->name . '",'
            . '"' . $db->table('activities_status')->where('id', $row->status)->get()->getRow()->name . '",';

        if ($row->status == 2) {
            $checkboxes = '<div class="certificate-container" name="' . $row->id . '">' .
              '<input type="checkbox" id="enablePublic_' . $row->id . '" ' .
              ($row->public_certificate ? 'checked' : '') . 
              ' onclick="handleCertificateChange(' . $row->id . ', \'public_certificate\', this.checked)"> عامة <br>' .
              '<input type="checkbox" id="enablePrivate_' . $row->id . '" ' .
              ($row->private_certificate ? 'checked' : '') . 
              ' onclick="handleCertificateChange(' . $row->id . ', \'private_certificate\', this.checked)"> خاصة</div>';

        
            // Properly escape and wrap the string in the dataset
            $dataset .= '"' . addslashes($checkboxes) . '",';
        } else {
            $dataset .= '"",';
        }


        // **Dropdown for certificate type**
        if ($row->status == 2) {
            $dataset .= '"<select class=\'form-control\' id=\'certificateType_' . $row->id . '\'></select>",';
        } else {
            $dataset .= '"",';
        }

        // **Dropdown for status change**
        $dataset .= '"<select class=\'form-control\' onchange=\'handleStatusChange(' . $row->id . ', this.value)\'>' .
            '<option class=\'form-control\'>اختر</option>';

        foreach ($statuses as $status) {
            if ($status->id != $row->status) {
                $dataset .= '<option value=\'' . $status->id . '\'>' . $status->name . '</option>';
            }
        }

        $dataset .= '</select>",],'; // **Fix closing select and comma**
    }

    $dataset = rtrim($dataset, ',') . ']';
    }
?>


<script>
    $(document).ready(function () {
    var dataSet = <?php if (isset($dataset)) echo $dataset; ?>;
    $('#example').DataTable({
        data: dataSet,
        "pageLength": -1,
        columns: [
            { title: "<input id='select_all' onchange='select_all()' class='select_all' type='checkbox'></input>" },
            { title: "م" },
            { title: "النشاط" },
            { title: "المتطوع" },
            { title: "الحالة" },
            { title: "الشهادات" },
            { title: "إصدار " },
            { title: "تغيير الحالة" }
            ],
            initComplete: function () {
                configFilter(this, [1, 2, 3, 4]);
            }
        });
        
        $('#example_length, #example_filter').hide();
    });
    
    function toggleCertificateOption(checkbox, optionValue) {
        if ($(checkbox).is(":checked")) {
            if ($("#certificateType option[value='" + optionValue + "']").length === 0) {
                $("#certificateType").append(
                    $("<option>", { value: optionValue, text: optionValue.replace(/^\w/, c => c.toUpperCase()) + " Certificate" })
                );
            }
        } else {
            $("#certificateType option[value='" + optionValue + "']").remove();
        }
    }
    
    function select_all() {
        var allcheckboxes = document.querySelectorAll('input[class="selecting"]');
        var checkedall_id = document.getElementById('select_all').checked;
        
        allcheckboxes.forEach(checkbox => {
            checkbox.checked = checkedall_id;
            if (checkedall_id) selectfunction(checkbox.id);
        });
        
        if (!checkedall_id) {
            document.getElementById('selected').value = '';
        }
    }
    
    function checkboxes() {
        var numberInput = document.getElementById('selected').value;
        var numbers = numberInput.split(',').map(num => num.trim());
        var checkboxes = document.querySelectorAll('input[class="selecting"]');
        
        numbers.forEach(num => {
            const checkbox = document.getElementById(num);
            if (checkbox) {
                checkbox.checked = true;
            }
        });
    }
    setInterval(checkboxes, 500);
    
    function selectfunction(id) {
        let checked_id = document.getElementById(id).checked;
        let selectedValues = document.getElementById('selected').value.split(',').map(Number);
        
        if (checked_id) {
            selectedValues.push(Number(id));
        } else {
            selectedValues = selectedValues.filter(num => num !== Number(id) && num !== 0);
        }
        
        document.getElementById('selected').value = selectedValues.join(',');
    }
    
    var modalFilterArray = {};
    
    function configFilter($this, colArray) {
        setTimeout(function () {
            var tableName = $this[0].id;
            var columns = $this.api().columns();
            
            $.each(colArray, function (i, arg) {
                $('#' + tableName + ' th:eq(' + arg + ')').append('<a style="margin-right:10px" class="text-white fa fa-filter filterIcon" onclick="showFilter(event,\'' + tableName + '_' + arg + '\')"></a>');
            });
            
            $.each(colArray, function (index, value) {
                columns.every(function (i) {
                    if (value === i) {
                        var column = this, content = '<input type="text" class="form-control filterSearchText" onkeyup="filterValues(this)" /> <br/>';
                        var distinctArray = [];
                        
                        column.data().each(function (d, j) {
                            if (!distinctArray.includes(d)) {
                                content += `<div><label>${d}</label><input type="checkbox" value="${d}" style="margin-left:10px; position:initial; opacity:100%; float:left"/></div>`;
                                distinctArray.push(d);
                            }
                        });
                        
                        var newTemplate = $('<div class="modalFilter col-xs-12"><div class="modal-content">' + content + '</div></div>');
                        $('body').append(newTemplate);
                        modalFilterArray[tableName + "_" + value] = newTemplate;
                    }
                });
            });
        }, 50);
    }
    
    function showFilter(e, index) {
        $('.modalFilter').hide();
        $(modalFilterArray[index]).css({ left: 0, top: 0 }).show();
        $('#mask').show();
        e.stopPropagation();
    }
    
    function filterValues(node) {
        var searchString = $(node).val().toUpperCase().trim();
        var rootNode = $(node).parent();
        
        rootNode.find('div').toggle(searchString === '' || rootNode.find("div:contains('" + searchString + "')").length > 0);
    }
    
    function performFilter(node, i, tableId) {
        var rootNode = $(node).parent().parent();
        var searchString = rootNode.find('input:checkbox:checked').map((_, checkbox) => checkbox.value).get().join('|');
        
        $('#' + tableId).DataTable().column(i).search(searchString, true, false).draw();
        rootNode.hide();
        $('#mask').hide();
    }
    
    function clearFilter(node, i, tableId) {
        var rootNode = $(node).parent().parent();
        rootNode.find(".filterSearchText").val('');
        rootNode.find('input:checkbox').prop('checked', false);
        
        $('#' + tableId).DataTable().column(i).search('', true, false).draw();
    }
    
    function handleCertificateChange(id, field, isChecked) {
        fetch('<?= base_url("Admin/updateCertificateStatus") ?>', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id: id, field: field, value: isChecked ? 1 : 0 })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateSelectOptions(id);
            } else {
                alert(response.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        });
    }

    function handleStatusChange(id, selectedStatus) {
        let actionText = ['تعليق هذا النشاط', 'الموافقة على النشاط', 'تعيينه كنشاط منجز'][selectedStatus];
    
        if (!confirm(`هل أنت متأكد من رغبتك في  ${actionText} ؟`)) return;
    
        fetch('<?= base_url("Admin/updateStatus") ?>', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id: id, status: Number(selectedStatus) })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(`${actionText} قم تم بنجاح.`);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        });
    }
    
    $(document).ready(function () {
        
        $(".certificate-container").each(function () {
            let id = $(this).attr("name");
            updateSelectOptions(id)
        });
    });

    
    // Function to update select options based on checkbox states
    function updateSelectOptions(id) {
        let enablePublic = $("#enablePublic_" + id).is(":checked");
        let enablePrivate = $("#enablePrivate_" + id).is(":checked");
        let selectElement = $("#certificateType_" + id);
    
        selectElement.empty(); // Clear previous options
        selectElement.append($("<option>", { value: "0", text: "اختر" }));
    
        if (enablePublic) {
            selectElement.append(
                $("<option>", { value: "public", text: "شهادة عامة" })
            );
        }
        if (enablePrivate) {
            selectElement.append(
                $("<option>", { value: "private", text: "شهادة خاصة" })
            );
        }
    
        // Attach change event for redirection
        selectElement.off("change").on("change", function () {
            let selectedValue = $(this).val();
            if (selectedValue === "public") {
                window.location.href = "public_certificate?id=" + id;
            } else if (selectedValue === "private") {
                window.location.href = "certificate?id=" + id;
            }
        });
    }


</script>


