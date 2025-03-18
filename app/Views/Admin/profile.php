<div class="login-panel">
    <div class="white-box">
        <form id="<?= $entityName;?>Edit">
            <div class="row">                                                       
                <div class="form-group">                                                       
                    <?php foreach ($entityData as $field => $attributes): ?>
                        <div class="col-md-6" id="<?= $entityName .'_X' ?>">  
                            <?php 
                                switch ($attributes['type']) {
                                    case 'text':
                                    case 'email':
                                    case 'password':
                                    case 'file':
                                    case 'phone':
                                        echo '<label>' . $attributes['placeholder'] .'</label>';
                                        echo '<input type="' . $attributes['type'] . '" class="form-control" id="' . $entityName .'_' .$attributes['id'].'_X' . '" name="' . $field . '" placeholder="' . $attributes['placeholder'] . '" ' . ($attributes['required'] ? 'required' : '') . ' ' . (isset($attributes['accept']) ? 'accept="' . $attributes['accept'] . '"' : '') . '>';
                                        break;

                                    case 'select':
                                        echo '<label>' . $attributes['placeholder'] .'</label>';
                                        echo '<select class="form-control" id="' . $entityName .'_' .$attributes['id'].'_X' . '" name="' . $field . '" ' . ($attributes['required'] ? 'required' : '') . '>';foreach ($attributes['options'] as $value => $label) {echo '<option value="' . $value . '">' . $label . '</option>';}
                                        echo '</select>';
                                        break;

                                    case 'textarea':
                                        echo '<label>' . $attributes['placeholder'] .'</label>';
                                        echo '<textarea class="form-control" id="' . $entityName .'_' .$attributes['id'].'_X' . '" name="' . $field . '" placeholder="' . $attributes['placeholder'] . '" ' . ($attributes['required'] ? 'required' : '') . '></textarea>';
                                        break;

                                    default:
                                        echo '<label>' . $attributes['placeholder'] .'</label>';
                                        echo '<input type="text" class="form-control" id="' . $entityName .'_' .$attributes['id'].'_X' . '" name="' . $field . '" placeholder="' . $attributes['placeholder'] . '" ' . ($attributes['required'] ? 'required' : '') . '>';
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

<script>
    $(document).ready(function () 
    {        
        //update
        const entityName = '<?= $entityName; ?>';  
        const entityId = '<?= $admin_id; ?>';  
        const Editform = `#${entityName}Edit`;
        $(Editform).on('submit', function (e) {
            e.preventDefault();
            let formData = new FormData();            
            $(`#${entityName}Edit [id*="${entityName}"]`).each(function () {
                const fieldName = $(this).attr('name');
    
                if ($(this).is(':radio')) {                    
                    if ($(`input[name="${fieldName}"]:checked`).length > 0) {
                        const selectedValue = $(`input[name="${fieldName}"]:checked`).val();
                        formData.append(fieldName, selectedValue);
                    }
                } else if ($(this).is(':checkbox')) {                    
                    if ($(this).is(':checked')) {
                        formData.append(fieldName, $(this).val());
                    }
                } else if ($(this).is('input[type="file"]')) {                    
                    if ($(this)[0].files.length > 0) {
                        const fileInput = $(this)[0];
                        const file = fileInput.files[0];                        
                        formData.append('file', file);
                    }
                } else if ($(this).is('input, textarea, select')) {                    
                    const fieldValue = $(this).val().trim();
                    if (fieldValue !== '') {
                        formData.append(fieldName, fieldValue);
                    }
                }
            });
                
            if (formData.keys().length === 0) {
                alert('يرجى إدخال البيانات لتحديث الحقول.');
                return;
            }
               
            formData.append('table', entityName);
            formData.append('id_entity', entityId);            
            sendData(formData);
        });
            
        function sendData(formData) {
            $.ajax({
                url: '<?= base_url("Admin/update_post_entity") ?>',
                type: 'POST',
                processData: false,
                contentType: false,
                data: formData,
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

        $('div[id*="_"]').each(function() {
            const currentDivId = $(this).attr('id');                       
            const newDivId = currentDivId.replace(/_([0-9]+|X)$/, `_${entityId}`);
            $(this).attr('id', newDivId);            
            $(this).find('[id*="_"]').each(function() {
                const currentInputId = $(this).attr('id');
                const updatedInputId = currentInputId.replace(/_([0-9]+|X)$/, `_${entityId}`);
                $(this).attr('id', updatedInputId);
            });
        });        
        $.ajax({
            url: `<?= base_url("Admin/data_grap") ?>`,
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({
                table: entityName,
                id_entity: entityId
            }),
            success: function(response) {
                if (response.status === 'success') {                                        
                    $.each(response.data, function(field, value) {                                                
                        const fieldId = `<?= $entityName; ?>_${field}_${entityId}`;                                                  
                        const element = $(`#${fieldId}`);                    
                        if (element.length) {
                            if (element.is('input, textarea')) {   
                                if (element.attr('type') === 'password') {
                                    // ✅ Keep password fields empty
                                    element.val('');
                                } else {
                                    element.val(value);
                                }
                            } else if (element.is('select')) {                                
                                element.val(value).change();
                            } else if (element.is('img')) {                                
                                element.attr('src', value);
                            }
                        }
                    });             
                } else {
                    alert('حدث خطأ.');
                }
            },
            error: function(xhr) {
                alert(`حدث خطأ: ${xhr.responseJSON?.message || 'حاول مرة أخرى.'}`);
            }
        });
    })
</script>