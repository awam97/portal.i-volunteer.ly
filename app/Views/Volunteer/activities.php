<?php $volunteer_city = $db->table('volunteers')->where('id', $volunteer_id)->get()->getRow()->city_id;?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>

          
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">        
    <input style="display:none" type="text" name="selected" id="selected">   
    <!--<div class="row">  
        <div class="col-md-4 col-xs-8">                
            <select style="width:100%" class="form-control" id="action" name="action">
                <option value="0">اختر الإجراء</option>    
                <option value="1">طباعة كشف</option>
                <option value="2">حذف</option>
                <option value="3">تصدير البيانات لملف Excel</option>
            </select>                
        </div>
    <div class="col-md-8  col-xs-4">
        <button type="submit" class="btn btn-danger">تنفيذ الإجراء ></button>
    </div><br>-->        
    </div>
</div>



<div class="row table-responsive">
    <table class="table datatable" id="example">
            
    </table>
</div>

<?php 
    $activities = $db->table('activities')->select('activities.*')->join('volunteer_activities', 'volunteer_activities.activity_id = activities.id AND volunteer_activities.volunteer_id = ' . $volunteer_id, 'left')->where('activities.date_from>',date("Y/m/d"))->where('volunteer_activities.activity_id IS NULL')->get()->getResult();

    if (empty($activities)) {
        echo "No activities found.";
        exit;
    }
    $dataset = '[';
    foreach ($activities as $row) {
        if (!isset($row->id)) {
            echo "Error: Missing 'ID' in the result set.";
            exit;
        }        
        $link1 = 'activity?id=' . $row->id;            

        // Convert 0 and 1 values to tick or cross icons
        $transportation = $row->transportation ? '✓' : '✗';
        $residency = $row->residency ? '✓' : '✗';
        $expenses = $row->expenses ? '✓' : '✗';
        $training = $row->training ? '✓' : '✗';

        $dataset .= '["<input class=selecting onchange=\'selectfunction(this.id)\' type=\'checkbox\' id=' . $row->id . '></input>",'
                . '"<a href=' . $link1 . ' >' . $row->name . '</a>",'
                . '"' . $transportation . '",'
                . '"' . $residency . '",'
                . '"' . $expenses . '",'
                . '"' . $training . '",'
                . '"' . $row->organisation . '",'
                . '"' . $row->date_from . '",'
                . '"' . $row->date_to . '",'
                . '"' . $row->hours . '",'
                . '"' . $db->table('cities')->where('id',$row->city_id)->get()->getRow()->name . '",'
                . '],';
    }
    $dataset = rtrim($dataset, ',') . ']';
?>

        
        
<link href="https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css" type="text/css" rel="stylesheet" media="screen,projection" />
<script src="http://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.7/js/materialize.min.js"></script>
    
    <script>
        $(document).ready(function () {
            var dataSet = <?php echo  $dataset;?> ;
            $('#example').DataTable({
                data: dataSet,                
                "pageLength": -1,
                columns: [
            { title: "<input id='select_all' onchange='select_all()' class='select_all' type='checkbox'></input>" },                    
            { title: "النشاط" },
            { title: "المواصلات" },
            { title: "الإقامة" },
            { title: "الإعاشة" },
            { title: "التدريب" },
            { title: "المنظمة" },
            { title: "من" },
            { title: "إلى" },
            { title: "الساعات" },
            { title: "المدينة" }
                ],

                initComplete: function () {
                    configFilter(this, [1,2,3,4,5,6,7,8,9,10]);                    
                }
            });

            $('#example_length,#example_filter').hide();
        });

        function select_all()
        {
            var allcheckboxes = document.querySelectorAll('input[class="selecting"]');            
            var checkedall_id = document.getElementById('select_all').checked;
            if(checkedall_id == true)
            {
                
                allcheckboxes.forEach(checkbox => {checkbox.checked = true;selectfunction(checkbox.id)});
            }
            else
            {                                                
                allcheckboxes.forEach(checkbox => {checkbox.checked = false;}); 
                document.getElementById('selected').value = '';
            }
        }

        function checkboxes() 
        {
            
            var numberInput = document.getElementById('selected').value;
            var numbers = numberInput.split(',').map(num => num.trim());
            var checkboxes = document.querySelectorAll('input[class="selecting"]');
            //checkboxes.forEach(checkbox => {checkbox.checked = false;});        
            numbers.forEach(num => {
                    const checkbox = document.getElementById(num);
                    if (checkbox) {
                        checkbox.checked = true;
                    }
                });
        }

        setInterval(checkboxes, 500);

        function selectfunction(id)
        {
            checked_id = document.getElementById(id).checked;
            if(checked_id == true)
            {                
                document.getElementById('selected').value += ','+id;
            }
            else
            {
                let numberArray = document.getElementById('selected').value.split(',').map(Number);
                numberArray = numberArray.filter(num => num !== Number(id));
                numberArray = numberArray.filter(num => num !== Number(0));
                document.getElementById('selected').value = `${numberArray.join(',')}`;
            }
            
        }        

        function configFilter($this, colArray) {
            setTimeout(function () {
                var tableName = $this[0].id;
                var columns = $this.api().columns();
                $.each(colArray, function (i, arg) {
                    $('#' + tableName + ' th:eq(' + arg + ')').append('<a style="margin-right:10px" class="text-white fa fa-filter" class="filterIcon" onclick="showFilter(event,\'' + tableName + '_' + arg + '\')" />');
                });

                var template = '<div style="direction:rtl" class="modalFilter col-xs-12">' +
                                 '<div class="modal-content">' +
                                 '{0}</div>' +
                                 '<div class=" modal-footer">' +
                                     '<a onclick="hideFilter(this);" style="float:left" class="btn btn-info">اخفاء</a>' +
                                     '<a onclick="clearFilter(this, {1}, \'{2}\');"  class=" btn btn-info">تصفية</a>' +
                                     '<a onclick="performFilter(this, {1}, \'{2}\');"  class=" btn btn-danger">فرز</a>' +
                                 '</div>' +
                             '</div>';
                $.each(colArray, function (index, value) {
                    columns.every(function (i) {
                        if (value === i) {
                            var column = this, content = '<input type="text" class="form-control filterSearchText" onkeyup="filterValues(this)" /> <br/>';
                            var columnName = $(this.header()).text().replace(/\s+/g, "_");
                            var distinctArray = [];
                            column.data().each(function (d, j) {
                                if (distinctArray.indexOf(d) == -1) {
                                    var id = tableName + "_" + columnName + "_" + j; // onchange="formatValues(this,' + value + ');
                                    content += '<div><label for="' + id + '"> ' + d + '</label><input style="margin-left:10px;position:initial;opacity:100%;float:left" type="checkbox" value="' + d + '"  id="' + id + '"/></input></div>';
                                    distinctArray.push(d);
                                }
                            });
                            var newTemplate = $(template.replace('{0}', content).replace('{1}', value).replace('{1}', value).replace('{2}', tableName).replace('{2}', tableName));
                            $('body').append(newTemplate);
                            modalFilterArray[tableName + "_" + value] = newTemplate;
                            content = '';
                        }
                    });
                });
            }, 50);
        }
        var modalFilterArray = {};
        //User to show the filter modal
        function showFilter(e, index) {
            $('.modalFilter').hide();
            $(modalFilterArray[index]).css({ left: 0, top: 0 });
            var th = $(e.target).parent();
            var pos = th.offset();
            console.log(th);
            
            $(modalFilterArray[index]).css({ 'left': pos.left, 'top': pos.top });
            $(modalFilterArray[index]).show();
            $('#mask').show();
            e.stopPropagation();
        }

        //This function is to use the searchbox to filter the checkbox
        function filterValues(node) {
            var searchString = $(node).val().toUpperCase().trim();
            var rootNode = $(node).parent();
            if (searchString == '') {
                rootNode.find('div').show();
            } else {
                rootNode.find("div").hide();
                rootNode.find("div:contains('" + searchString + "')").show();
            }
        }

        //Execute the filter on the table for a given column
        function performFilter(node, i, tableId) {
            var rootNode = $(node).parent().parent();
            var searchString = '', counter = 0;

            rootNode.find('input:checkbox').each(function (index, checkbox) {
                if (checkbox.checked) {
                    searchString += (counter == 0) ? checkbox.value : '|' + checkbox.value;
                    counter++;
                }
            });
            $('#' + tableId).DataTable().column(i).search(
                searchString,
                true, false
            ).draw();
            rootNode.hide();
            $('#mask').hide();
        }

        //Removes the filter from the table for a given column
        function clearFilter(node, i, tableId) {
            var rootNode = $(node).parent().parent();
            rootNode.find(".filterSearchText").val('');
            rootNode.find('input:checkbox').each(function (index, checkbox) {
                checkbox.checked = false;
                $(checkbox).parent().show();
            });
            $('#' + tableId).DataTable().column(i).search(
                '',
                true, false
            ).draw();

        }
        
        function hideFilter(node, i, tableId) {
            var rootNode = $(node).parent().parent();
            rootNode.find(".filterSearchText").val('');

            $('#' + tableId).DataTable().column(i).search(
                '',
                true, false
            ).draw();
            rootNode.hide();
            $('#mask').hide();
        }
    </script>



