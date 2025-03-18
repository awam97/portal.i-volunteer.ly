<?php if (!isset($hidden)|| (isset($hidden) && $hidden == 1) ){;?>
    <div class="row">
        <div class="col-md-4" style="padding:8px">
            <center>
                <a href="#tab1" class="btn btn-default" data-toggle="tab">عرض</a>
                <a href="#tab2" class="btn btn-default" data-toggle="tab">جديد</a>                    
                <a id="selectallBtn" class="btn btn-default">تحديد الكل</a>
                <a id="bulkDeleteBtn" class="btn btn-default">حذف</a>
            </center>
        </div>
        <div class="col-md-8">
            <div class="col-md-4 col-xs-4">
                <select id="sortBy" class="form-control">
                    <option value="name">ترتيب أبجدي</option>
                    <option value="id">ترتيب زمني</option>
                </select>
            </div>
            <div class="col-md-4 col-xs-4">
                <select id="sortOrder" class="form-control">
                    <option value="asc">تصاعدي</option>
                    <option value="desc">تنازلي</option>
                </select>
            </div>        
            <div class="col-md-4 col-xs-4">
                <input type="text" id="<?= $entityName; ?>Search"  class="form-control"  placeholder="اكتب للبحث ...">
            </div>
        </div>
    </div>
    <hr>
    <div class="tab-content">
        <div id="tab1" class="tab-pane fade in active">
            <div class="row">
                <?php foreach ($entities as $entity): ?>
                    <div class="col-md-3 col-xs-6 <?= $entityName; ?>-item" data-id="<?= $entity->id; ?>">
                        <div class="white-box table-box-items">  
                            <label class="select-checkbox">
                                <input class="entity-checkbox" type="checkbox" name="entity" data-id="<?= $entity->id; ?>" value="<?= $entity->id; ?>">
                                <span></span>
                            </label>
                            <center>                                               
                                <h2><b><?= $entity->name; ?></b></h2>
                                <hr>
                                <a href="#tab3" class="btn btn-danger btn-edit" data-toggle="tab" onclick="editEntity(<?= $entity->id; ?>)">تعديل</a>
                                <a class="btn btn-danger btn-delete" data-id="<?= $entity->id; ?>">حذف</a>
                            </center>
                        </div>
                    </div>
                <?php endforeach; ?>   
            </div>
        </div>
        <div id="tab2" class="tab-pane fade">
            <div class="row">
                <div class="col-md-12">
                    <div class="white-box">                    
                        <h2><b>إضافة عنصر جديد</b></h2>
                        <hr>
                        <form id="<?= $entityName;?>Add">
                            <div class="row">                                                       
                                <div class="form-group">                                                       
                                    <?php foreach ($entityData as $field => $attributes): ?>
                                        <div class="<?php echo $attributes['class_id'] ;?>">  
                                            <?php                                             
                                                echo '<label for="' . $attributes['id'] . '"><b>' . $attributes['placeholder'] . '</b></label>';
                                                
                                                switch ($attributes['type']) {
                                                    case 'text':
                                                    case 'date':
                                                    case 'email':
                                                    case 'password':
                                                    case 'file':
                                                    case 'phone':
                                                        echo '<input type="' . $attributes['type'] . '" class="form-control" id="' . $attributes['id'] . '" name="' . $field . '" placeholder="' . $attributes['placeholder'] . '" ' . ($attributes['required'] ? 'required' : '') . ' ' . (isset($attributes['accept']) ? 'accept="' . $attributes['accept'] . '"' : '') . '>';
                                                        break;

                                                    case 'select':
                                                        echo '<select class="form-control" id="' . $attributes['id'] . '" name="' . $field . '" ' . ($attributes['required'] ? 'required' : '') . '>';
                                                        foreach ($attributes['options'] as $value => $label) 
                                                        {
                                                            echo '<option value="' . $value . '">' . $label . '</option>';
                                                        }
                                                        echo '</select>';
                                                        break;

                                                    case 'textarea':
                                                        echo '<textarea class="form-control" id="' . $attributes['id'] . '" name="' . $field . '" placeholder="' . $attributes['placeholder'] . '" ' . ($attributes['required'] ? 'required' : '') . '></textarea>';
                                                        break;

                                                    default:
                                                        echo '<input type="text" class="form-control" id="' . $attributes['id'] . '" name="' . $field . '" placeholder="' . $attributes['placeholder'] . '" ' . ($attributes['required'] ? 'required' : '') . '>';
                                                }
                                            ?>
                                        </div>                   
                                    <?php endforeach; ?>    
                                    <button type="submit" class="col-md-12 col-xs-12 btn btn-danger btn-lg">حفظ</button>                                                                                                                                      
                                </div>                             
                            </div>
                        </form>                                        
                    </div>
                </div>
            </div>
        </div>
        <div id="tab3" class="tab-pane fade">
            <div class="row">
                <div class="col-md-12">
                    <div class="white-box">                    
                        <h2><b>تعديل عنصر</b></h2>
                        <hr>
                        <form id="<?= $entityName; ?>Edit">
                            <div class="row">
                                <div class="form-group">
                                    <?php foreach ($entityData as $field => $attributes): ?>
                                        <div class="<?php echo $attributes['class_id'] ;?>" id="<?= $entityName . '_X' ?>">
                                            <?php
                                            echo '<label for="' . $attributes['id'] . '_X"><b>' . $attributes['placeholder'] . '</b></label>';
                                            switch ($attributes['type']) {
                                                case 'text':
                                                case 'email':
                                                case 'password':
                                                case 'file':
                                                case 'phone':
                                                    echo '<input type="' . $attributes['type'] . '" class="form-control" id="' . $entityName . '_' . $attributes['id'] . '_X' . '" name="' . $field . '" placeholder="' . $attributes['placeholder'] . '" ' . ($attributes['required'] ? 'required' : '') . ' ' . (isset($attributes['accept']) ? 'accept="' . $attributes['accept'] . '"' : '') . '>';
                                                    break;

                                                case 'select':
                                                    echo '<select class="form-control" id="' . $entityName . '_' . $attributes['id'] . '_X' . '" name="' . $field . '" ' . ($attributes['required'] ? 'required' : '') . '>';
                                                    foreach ($attributes['options'] as $value => $label) {
                                                        echo '<option value="' . $value . '">' . $label . '</option>';
                                                    }
                                                    echo '</select>';
                                                    break;

                                                case 'textarea':
                                                    echo '<textarea class="form-control" id="' . $entityName . '_' . $attributes['id'] . '_X' . '" name="' . $field . '" placeholder="' . $attributes['placeholder'] . '" ' . ($attributes['required'] ? 'required' : '') . '></textarea>';
                                                    break;

                                                case 'radio':
                                                    echo '<table style="text-align:center;border-collapse: collapse;width:100%"><thead><tr><th style="background-color:#efeeee;width:50%;text-align:center;border: 1px solid #efeeee;padding: 8px">نعم</th><th style="background-color:#efeeee;width:50%;text-align:center;border: 1px solid #efeeee;padding: 8px">لا</th></tr></thead><tbody>';
                                                    foreach ($attributes['options'] as $value) {
                                                        echo '<td style="border: 1px solid #efeeee;padding: 8px"><input type="radio" id="' . $entityName . '_' . $attributes['id'] . '_' . $value . '_X' . '" name="' . $field . '" value="' . $value . '"></td>';
                                                    }
                                                    echo '</tbody></table><br>';
                                                    break;

                                                case 'checkbox':
                                                    foreach ($attributes['options'] as $value) {
                                                        echo '<input type="checkbox" id="' . $entityName . '_' . $attributes['id'] . '_' . $value . '_X' . '" name="' . $field . '[]" value="' . $value . '" ' . ($attributes['required'] ? 'required' : '') . '>';
                                                        echo '<label for="' . $entityName . '_' . $attributes['id'] . '_' . $value . '_X">' . $value . '</label>';
                                                    }
                                                    break;

                                                default:
                                                    echo '<input type="text" class="form-control" id="' . $entityName . '_' . $attributes['id'] . '_X' . '" name="' . $field . '" placeholder="' . $attributes['placeholder'] . '" ' . ($attributes['required'] ? 'required' : '') . '>';
                                            }
                                            ?>
                                        </div>
                                    <?php endforeach; ?>
                                    <button type="submit" class="col-md-12 col-xs-12 btn btn-danger btn-lg">حفظ</button>
                                </div>
                            </div>
                        </form>                                          
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        $(document).ready(function () 
        {
            const entityName = '<?= $entityName; ?>';                

            //Search
            $(`#${entityName}Search`).on('input', function () 
            {            
                const query = $(this).val().toLowerCase();
                $(`.${entityName}-item`).each(function () {
                    const itemName = $(this).find('h2').text().toLowerCase();
                    $(this).toggle(itemName.includes(query));
                });
            });        
            


            //Sorting
            $('#sortBy, #sortOrder').on('change', function () 
            {
                const sortBy = $('#sortBy').val();
                const sortOrder = $('#sortOrder').val();
                let items = $(`.${entityName}-item`).toArray();
                items.sort(function (a, b) {
                    const aValue = $(a).find('h2').text().toLowerCase();
                    const bValue = $(b).find('h2').text().toLowerCase();
                    if (sortBy === 'id') {                    
                        return (sortOrder === 'asc' ? 1 : -1) * (parseInt($(a).data('id')) - parseInt($(b).data('id')));
                    } else {                    
                        if (aValue < bValue) return (sortOrder === 'asc' ? -1 : 1);
                        if (aValue > bValue) return (sortOrder === 'asc' ? 1 : -1);
                        return 0;
                    }
                });            
                $(`#tab1 .row`).html(items);
            });        
            $('#sortBy').trigger('change');
                    

            //Insert
            const Addform = `#${entityName}Add`;
            $(Addform).on('submit', function (e) {
                e.preventDefault();
                const formData = {};
                let isValid = true;
                const fileInput = $(`${Addform} input[type="file"]`)[0];

                // Collect all input, textarea, select, and radio fields
                $(`${Addform} input, ${Addform} select, ${Addform} textarea`).each(function () {
                    const fieldName = $(this).attr('name');
                    const fieldType = $(this).attr('type');
                    const fieldValue = $(this).val().trim();

                    // Handling checkboxes: Collect all checked values into an array
                    if (fieldType === 'checkbox') {
                        if ($(this).is(':checked')) {
                            if (!formData[fieldName]) formData[fieldName] = [];
                            formData[fieldName].push($(this).val());
                        }
                        return;
                    }
                    
                    // Handling radio buttons: Only store the value of the selected option
                    if (fieldType === 'radio') {                    
                        // Check if this radio button is selected
                        if ($(this).is(':checked')) {
                            formData[fieldName] = $(this).val();                         
                        }
                        return;
                    }



                    // Handling textarea and other fields
                    if (!fieldValue && fieldType !== 'file' && fieldType !== 'radio' && fieldType !== 'checkbox') { // Skip file, radio, and checkbox
                        alert(`يرجى إدخال ${$(this).attr('placeholder')}`);
                        isValid = false;
                        return false;
                    }

                    if (fieldType !== 'file') {
                        formData[fieldName] = fieldValue;
                    }
                });

                if (!isValid) return;

                

                // If there's an image file, read and encode it as Base64
                if (fileInput && fileInput.files.length > 0) {                
                    const reader = new FileReader();
                    const file = fileInput.files[0];
                    
                    reader.onload = function (e) {
                        // Add the Base64 string to the form data
                        formData.file = e.target.result;
                        // Send the form data via AJAX                   
                        $.ajax({
                            url: '<?= base_url("Admin/add_entity") ?>',
                            type: 'POST',
                            contentType: 'application/json',
                            data: JSON.stringify({
                                table: entityName,
                                fields_entity: formData
                            }),
                            success: function (response) {
                                if (response.status === 'success') {                                
                                    location.reload();
                                } else {
                                    alert('حدث خطأ أثناء الإضافة.');
                                }
                            },
                            error: function (xhr) {
                                alert(`حدث خطأ: ${xhr.responseJSON?.message }`);
                            }
                        });
                    };

                    reader.onerror = function () {
                        alert('حدث خطأ أثناء قراءة الصورة. يرجى المحاولة مرة أخرى.');
                    };

                    reader.readAsDataURL(file); // Read file as a Base64 Data URL
                } else {                
                    // Send form data without an image
                    $.ajax({
                        url: '<?= base_url("Admin/add_entity") ?>',
                        type: 'POST',
                        contentType: 'application/json',
                        data: JSON.stringify({
                            table: entityName,
                            fields_entity: formData
                        }),
                        success: function (response) {
                            if (response.status === 'success') {                            
                                location.reload();
                            } else {
                                alert(response.message);
                            }
                        },
                        error: function (xhr) {
                            alert(`حدث خطأ: ${xhr.responseJSON?.message || 'حاول مرة أخرى.'}`);
                        }
                    });
                }
            });



            //Delete
            $('.btn-delete').on('click', function () 
            {            
                const entityId = $(this).data('id');            
                if (!confirm(`هل أنت متأكد أنك تريد حذف هذا العنصر؟`)) return;
                $.ajax({
                    url: `<?= base_url("Admin/delete_entity") ?>`,
                    type: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify({
                        table: entityName,
                        id_entity: entityId
                    }),
                    success: function (response) {
                        if (response.status === 'success') {
                            location.reload();
                        } else {
                            alert('حدث خطأ أثناء الحذف.');
                        }
                    },
                    error: function (xhr) {
                        alert(`حدث خطأ: ${xhr.responseJSON?.message || 'حاول مرة أخرى.'}`);
                    }
                });
            });


            //Bulk Delete
            $('#bulkDeleteBtn').on('click', function () 
            {
                if (selectedEntities.length === 0) {
                    alert('يرجى تحديد العناصر المراد حذفها.');
                    return;
                }
                const selectedNames = selectedEntities.map(id => $(`.entity-checkbox[data-id="${id}"]`).closest('.entity-item').find('h2').text()).join('\n');            
                if (!confirm(`هل أنت متأكد أنك تريد حذف العناصر التالية؟\n\n${selectedNames}`)) return;
                $.ajax({
                    url: `<?= base_url("Admin/bulk_delete") ?>`,
                    type: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify({
                        table: entityName,
                        ids: selectedEntities
                    }),
                    success: function (response) {
                        if (response.status === 'success') {
                            location.reload();
                        } else {
                            alert('حدث خطأ أثناء الحذف.');
                        }
                    },
                    error: function (xhr) {
                        alert(`حدث خطأ: ${xhr.responseJSON?.message || 'حاول مرة أخرى.'}`);
                    }
                });
            });
            

            const Editform = `#${entityName}Edit`;
            $(Editform).on('submit', function (e) {
                e.preventDefault();
                const entityId = window.editEntityId;
                let formData = new FormData(); // To handle form and file uploads

                // Collect form data
                $(`#${entityName}Edit [id*="${entityName}"]`).each(function () {
                    const fieldName = $(this).attr('name');

                    if ($(this).is(':radio')) {
                        // Handle radio inputs
                        if ($(`input[name="${fieldName}"]:checked`).length > 0) {
                            const selectedValue = $(`input[name="${fieldName}"]:checked`).val();
                            formData.append(fieldName, selectedValue);
                        }
                    } else if ($(this).is(':checkbox')) {
                        // Handle checkbox inputs
                        if ($(this).is(':checked')) {
                            formData.append(fieldName, $(this).val());
                        }
                    } else if ($(this).is('input[type="file"]')) {
                        // Handle file inputs (e.g., images)
                        if ($(this)[0].files.length > 0) {
                            const fileInput = $(this)[0];
                            const file = fileInput.files[0];

                            // Directly append file to FormData
                            formData.append('file', file);
                        }
                    } else if ($(this).is('input, textarea, select')) {
                        // Handle other input types
                        const fieldValue = $(this).val().trim();
                        if (fieldValue !== '') {
                            formData.append(fieldName, fieldValue);
                        }
                    }
                });

                // If no fields were selected
                if (formData.keys().length === 0) {
                    alert('يرجى إدخال البيانات لتحديث الحقول.');
                    return;
                }
                // Append other necessary data to FormData
                formData.append('table', entityName);
                formData.append('id_entity', entityId);

                // Send form data
                sendData(formData);
            });

            // Function to send data via AJAX
            function sendData(formData) {
                $.ajax({
                    url: '<?= base_url("Admin/update_post_entity") ?>',
                    type: 'POST',
                    processData: false,  // Don't process data
                    contentType: false,  // Don't set content-type header
                    data: formData,  // Send FormData directly
                    success: function (response) {
                        if (response.status === 'success') {
                            location.reload();
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function (xhr) {
                        alert(`حدث خطأ: ${xhr.responseJSON?.message}`);
                    }
                });
            }

            // Multiple Select
            let selectedEntities = [];
            $('.entity-checkbox').on('change', function () {
                const entityId = $(this).data('id');
                if (this.checked) {
                    if (!selectedEntities.includes(entityId)) {
                        selectedEntities.push(entityId);
                    }
                } else {
                    selectedEntities = selectedEntities.filter(id => id !== entityId);
                }
                console.log('Selected Entities:', selectedEntities);
            });

            // Select All
            $('#selectallBtn').on('click', function () {
                const checkboxes = $('.entity-checkbox');
                const allChecked = checkboxes.length === checkboxes.filter(':checked').length;            
                checkboxes.prop('checked', !allChecked);            
                if (allChecked) {
                    selectedEntities = [];
                } else {
                    selectedEntities = checkboxes.map(function () {
                        return $(this).data('id');
                    }).get();
                }
                $(this).text(allChecked ? 'تحديد الكل' : 'الغاء تحديد الكل');
                console.log('Selected Entities:', selectedEntities);
            });
        });    

        function editEntity(id) {
            window.editEntityId = id;
            const entityName = '<?= $entityName; ?>';

            // Update IDs in the form
            $('div[id*="_"]').each(function () {
                const currentDivId = $(this).attr('id');
                const newDivId = currentDivId.replace(/_([0-9]+|X)$/, `_${id}`);
                $(this).attr('id', newDivId);
                $(this).find('[id*="_"]').each(function () {
                    const currentInputId = $(this).attr('id');
                    const updatedInputId = currentInputId.replace(/_([0-9]+|X)$/, `_${id}`);
                    $(this).attr('id', updatedInputId);
                });
            });

            // Fetch entity data
            $.ajax({
                url: `<?= base_url("Admin/data_grap") ?>`,
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({
                    table: entityName,
                    id_entity: id
                }),
                success: function (response) {
                    if (response.status === 'success') {
                        // Populate form fields with response data
                        $.each(response.data, function (field, value) {
                            const fieldId = `${entityName}_${field}_${id}`;
                            const element = $(`[id^="${entityName}_${field}_"][id$="_${id}"]`);

                            if (element.length) {
                                if (element.is('input[type="text"], textarea, input[type="email"], input[type="password"], input[type="phone"], input[type="date"]')) {
                                    // Handle text, textarea, and similar inputs
                                    element.val(value);
                                } else if (element.is('select')) {
                                    // Handle select dropdown
                                    element.val(value).change();
                                } else if (element.is('input[type="radio"]')) {
                                    // Handle radio buttons with specific structure
                                    const radioId = `${entityName}_${field}_${value}_${id}`;
                                    $(`#${radioId}`).prop('checked', true);
                                } else if (element.is('input[type="checkbox"]')) {
                                    // Handle checkboxes with specific structure
                                    if (Array.isArray(value)) {
                                        value.forEach(val => {
                                            const checkboxId = `${entityName}_${field}_${val}_${id}`;
                                            $(`#${checkboxId}`).prop('checked', true);
                                        });
                                    } else {
                                        const checkboxId = `${entityName}_${field}_${value}_${id}`;
                                        $(`#${checkboxId}`).prop('checked', true);
                                    }
                                } else if (element.is('img')) {
                                    // Handle image elements
                                    element.attr('src', value);
                                }
                            }
                        });
                    } else {
                        alert('حدث خطأ.');
                    }
                },
                error: function (xhr) {
                    alert(`حدث خطأ: ${xhr.responseJSON?.message}`);
                }
            });
        }


    </script>
    <script>
        // Add an event listener to all radio buttons
        const radios = document.querySelectorAll('input[type="radio"]');
        
        radios.forEach(radio => {
            radio.addEventListener('change', function() {
                // Ensure checked is only added to the selected radio button
                if (this.checked) {
                    // Adding 'checked' manually (though it's automatically added)
                    radios.forEach(r => r.removeAttribute('checked')); // Remove checked from all others
                    this.setAttribute('checked', 'checked'); // Add checked to the selected one
                }
            });
        });
    </script>
<?php ;};?>