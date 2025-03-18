<?php if (!isset($hidden)|| (isset($hidden) && $hidden == 1) ){;?>
    <link href="https://cdn.jsdelivr.net/npm/summernote/dist/summernote-lite.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote/dist/summernote-lite.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/lang/summernote-ar-AR.min.js"></script>
        
    
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
                <?php
                    $folderPath = "uploads/{$entityName}_files/";                
                    $filePath = glob($folderPath . $entity->id . ".*");                 
                    $fileExtension = $filePath ? pathinfo($filePath[0], PATHINFO_EXTENSION) : 'png';
                    ?>
                    <div class="col-md-4 col-xs-12 <?= $entityName; ?>-item" data-id="<?= $entity->id; ?>" city-id="<?php if (isset($entity->city_id)) echo $entity->city_id; ?>" city-name="<?php if (isset($entity->city_name)) echo $entity->city_name; ?>">
                        <div class="white-box table-box-items-no-padding">                         
                            <div class="table-box-top" style="border-radius: 20px 20px 0px 0px; 
                                background: linear-gradient(to bottom, rgba(48, 67, 0, 0.7), rgba(255, 255, 255, 1)), 
                                url(https://portal.i-volunteer.ly/<?= $folderPath . $entity->id . '.' . $fileExtension; ?>) no-repeat center center;
                                background-size: cover; position: relative; height: 120px; color: white; text-shadow: 0 1px 3px rgba(0, 0, 0, 0.6);
                                display: flex;">
                                <label class="select-checkbox"  style="margin-left: 10px">
                                    <input class="entity-checkbox" type="checkbox" name="entity" data-id="<?= $entity->id; ?>" value="<?= $entity->id; ?>">                            
                                    <span></span>
                                </label>
                                <span><?php if (!empty($entity->activity_id) && ($entity->activity_id > 0)) {$activity = $db->table('activities')->where('id', $entity->activity_id)->get()->getRow();if ($activity) {echo $activity->name;}}?></span> 
                                
                                <span style="margin-left: 10px;margin-right: 10px"><?php if (isset($entity->organisation)) echo $entity->organisation; ?></span>
                            </div>
                            <div class="table-box-bottom">
                                <center>                                                                 
                                    <h4><b><a href="/Admin/<?= $link; ?>/?id=<?= $entity->id; ?>"><?= $entity->name; ?></a></b></h4> 
                                    <hr>                                                               
                                    <?php if (isset($link2)){;?><a class="btn btn-danger" href="/Admin/<?= $link2; ?>/?id=<?= $entity->id; ?>">كشف</a><?php ;};?>
                                    <a href="#tab3" class="btn btn-danger btn-edit" data-toggle="tab" onclick="editEntity(<?= $entity->id; ?>)">تعديل</a>
                                    <a class="btn btn-delete btn-danger btn-info" data-id="<?= $entity->id; ?>">حذف</a>                                
                                </center>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div id="tab2" class="tab-pane fade">
            <div class="row">
                <div class="col-md-12">
                    <div class="white-box">  
                        <div class="row">                  
                            <h2><b>إضافة عنصر جديد</b></h2>
                            <hr>
                            <form id="<?= $entityName;?>Add">
                                <div class="row">                                                                                                                                                                          
                                    <?php foreach ($entityData as $field => $attributes): ?>
                                        <?php if ($attributes['type'] == 'textarea') 
                                        {
                                            $summernote_id = $attributes['id'];
                                            ?>
                                            <script>
                                                document.addEventListener('DOMContentLoaded', function () {
                                                    
                                                    $('#<?= $summernote_id ?>').summernote({
                                                        height: 100,
                                                        placeholder: 'اكتب المحتوى هنا ...',
                                                        lang: 'ar-AR',
                                                        toolbar: [
                                                            ['style', ['bold', 'italic', 'underline', 'clear']],
                                                            ['font', ['strikethrough', 'superscript', 'subscript']],
                                                            ['para', ['ul', 'ol', 'paragraph']],
                                                            ['insert', ['link', 'customMediaButton']], // Add custom button
                                                            ['view', ['fullscreen', 'codeview', 'help']]
                                                        ],
                                                        buttons: {
                                                            customMediaButton: function (context) {
                                                                var ui = $.summernote.ui;
                                                                // Define the button
                                                                var button = ui.button({
                                                                    contents: '<i class="note-icon-picture"></i> إضافة وسائط', // Icon and label
                                                                    tooltip: 'إضافة وسائط',
                                                                    click: function () {
                                                                        customMediaButtonClick('<?= $summernote_id ?>'); // Pass the current editor ID
                                                                    }
                                                                });
                                                                return button.render(); // Return the button as a DOM node
                                                            }
                                                        }
                                                    });
                                                });
                                            </script>
                                            <?php if (empty($attributes['id'])) {$summernote_id = '';echo $summernote_id;}
                                        }?>
                                            
                                        <div class="<?php echo $attributes['class_id'] ;?>">  
                                            <?php                                                 
                                                echo '<label for="' . $attributes['id'] . '"><b>' . $attributes['placeholder'] . '</b></label>';                                             
                                                switch ($attributes['type']) {                                                
                                                    case 'date':
                                                        echo '<input value="' . date('d/m/Y') . '" type="' . $attributes['type'] . '" class="form-control" id="' . $entityName . '_' . $attributes['id'] . '_X' . '" name="' . $field . '" placeholder="' . $attributes['placeholder'] . '" ' . ($attributes['required'] ? 'required' : '') . ' ' . (isset($attributes['accept']) ? 'accept="' . $attributes['accept'] . '"' : '') . '>';
                                                        break;
                                                    case 'text':
                                                    case 'email':
                                                    case 'password':
                                                    case 'file':
                                                    case 'phone':
                                                        echo '<input type="' . $attributes['type'] . '" class="form-control" id="' . $entityName . '_' . $attributes['id'] . '_X' . '" name="' . $field . '" placeholder="' . $attributes['placeholder'] . '" ' . ($attributes['required'] ? 'required' : '') . ' ' . (isset($attributes['accept']) ? 'accept="' . $attributes['accept'] . '"' : '') . '>';
                                                        break;
    
                                                    case 'select':                                                    
                                                        echo '<select class="form-control" id="' . $attributes['id'] . '" name="' . $field . '" ' . ($attributes['required'] ? 'required' : '') . '>';
                                                        foreach ($attributes['options'] as $value => $label) {
                                                            echo '<option value="' . $value . '">' . $label . '</option>';
                                                        }
                                                        echo '</select>';
                                                        break;
    
                                                    case 'textarea':
                                                        echo '<textarea  type="' . $attributes['type'] . '" class="form-control" id="' . $attributes['id'] . '" name="' . $field . '" placeholder="' . $attributes['placeholder'] . '" ' . ($attributes['required'] ? 'required' : '') . '></textarea>';
                                                        break;
    
                                                    case 'radio':
                                                        echo '<table style="text-align:center;border-collapse: collapse;width:100%"><theader><th style="background-color:#efeeee;width:50%;text-align:center;border: 1px solid #efeeee;padding: 8px">نعم</th><th style="background-color:#efeeee;width:50%;text-align:center;border: 1px solid #efeeee;padding: 8px">لا</th><theader><tbody>';
                                                        foreach ($attributes['options'] as $value) {                                                                                                        
                                                            echo '<td style="border: 1px solid #efeeee;padding: 8px"><input type="radio" id="' . $attributes['id'] . '_' . $value . '" name="' . $attributes['id']  . '" value="' . $value . '" ></td>';                                                                                                       
                                                        }
                                                        echo '</tbody></table><br>';
                                                        break;
    
                                                    case 'checkbox':
                                                        foreach ($attributes['options'] as $value) {                                                                                                        
                                                            echo '<input type="checkbox" id="' . $attributes['id'] . '_' . $value . '" name="' . $field . '" value="' . $value . '" ' . ($attributes['required'] ? 'required' : '') . '>';                                                                                                        
                                                        }
                                                        break;
                                                    default:
                                                        echo '<input type="text" class="form-control" id="' . $attributes['id'] . '" name="' . $field . '" placeholder="' . $attributes['placeholder'] . '" ' . ($attributes['required'] ? 'required' : '') . '>';
                                                }
                                            ?>
                                        </div>                   
                                    <?php endforeach; ?>                                                                                                                                                                                                                                                                                                                                                                                                                
                                </div>
                                <hr>
                                <button type="submit" class="col-md-12 col-xs-12 btn btn-danger btn-lg">حفظ</button>   
                            </form> 
                        </div>                                       
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
                                        <div class="col-md-6" id="<?= $entityName . '_X' ?>">
                                            <?php echo '<label for="' . $attributes['id'] . '_X"><b>' . $attributes['placeholder'] . '</b></label>';
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
                                            <!--<?php if ($attributes['type'] == 'textarea') {
                                            $summernote_id = $entityName .'_'.$attributes['id'].'_'.$value;?>
                                            <script>
                                                document.addEventListener('DOMContentLoaded', function () {
                                                    $('#<?= $summernote_id ?>').summernote({
                                                        height: 200,
                                                        placeholder: 'اكتب المحتوى هنا ...',
                                                        lang: 'ar-AR',
                                                        toolbar: [
                                                            ['style', ['bold', 'italic', 'underline', 'clear']],
                                                            ['font', ['strikethrough', 'superscript', 'subscript']],
                                                            ['para', ['ul', 'ol', 'paragraph']],
                                                            ['insert', ['link', 'customMediaButton']], // Add custom button
                                                            ['view', ['fullscreen', 'codeview', 'help']]
                                                        ],
                                                        buttons: {
                                                            customMediaButton: function (context) {
                                                                var ui = $.summernote.ui;
                                                                // Define the button
                                                                var button = ui.button({
                                                                    contents: '<i class="note-icon-picture"></i> إضافة وسائط', // Icon and label
                                                                    tooltip: 'إضافة وسائط',
                                                                    click: function () {
                                                                        customMediaButtonClick('<?= $summernote_id ?>'); // Pass the current editor ID
                                                                    }
                                                                });
                                                                return button.render(); // Return the button as a DOM node
                                                            }
                                                        }
                                                    });
                                                });
                                            </script>
                                            <?php ;}; ?>-->
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
    
    
    <div id="media-popup" style="display:none; position:fixed; top:50%; left:50%;width:80%; transform:translate(-50%, -50%);  background:#fff; border:1px solid #ccc; box-shadow:0 2px 10px rgba(0,0,0,0.5); padding:20px; z-index:1000;">
            <h3><b>إضافة وسائط</b></h3>
            <div style="overflow:auto; max-height:400px">
                <div class="row">
                    <div class="media-library-grid">
                        <?php $entityNamex = 'library';foreach ($db->table($entityNamex)->get()->getResult() as $entityx):                                                                                                                 
                            $allowedImageMimeTypes = ['image/png', 'image/jpeg', 'image/jpg', 'image/gif'];
                            $uploadDir = 'https://portal.i-volunteer.ly/uploads/' . $entityNamex . '_files/';
                            $filePath = null;
                            $placeholderImage = 'https://portal.i-volunteer.ly/uploads/placeholder_image.jpg';
                            $placeholderPDF = 'https://portal.i-volunteer.ly/uploads/placeholder_pdf.jpg';                         
                            $placeholderVideo = 'https://portal.i-volunteer.ly/uploads/placeholder_Video.jpg';                         
                            foreach (['png', 'jpg', 'jpeg', 'gif', 'pdf', 'mp4'] as $ext) {
                                $fileName = $entityx->id . '.' . $ext;
                                $visiblefileName = $entityx->filename ;
                                $potentialFilePath = $uploadDir . $fileName;
                                $placeholder = $placeholderImage;
                                $headers = @get_headers($potentialFilePath, 1);
                                if ($headers && strpos($headers[0], '200')) {                                                                
                                    $mimeType = $headers['Content-Type'] ?? null;
                                    if (strpos($mimeType, 'image') !== false) {
                                        $fileType = 'image';
                                        $filePath = $potentialFilePath;
                                        $placeholder = $filePath;
                                        break;
                                    } elseif ($mimeType === 'video/mp4') {
                                        $fileType = 'video';
                                        $filePath = $potentialFilePath;
                                        $placeholder = $placeholderVideo;
                                        break;
                                    } elseif ($mimeType === 'application/pdf') {
                                        $fileType = 'pdf';
                                        $filePath = $potentialFilePath;
                                        $placeholder = $placeholderPDF;
                                        break;
                                    }                                                                                
                                }
                            };
                            $placeholder = $fileType === 'image' ? $filePath : ($fileType === 'pdf' ? $placeholderPDF : $placeholderVideo);?>
                            <div class="col-md-12">
                                <div class="media-item">
                                    <div class="media-thumbnail">
                                        <a href="#" onclick="insertMediaToEditor('<?= $filePath; ?>', '<?= $fileType; ?>')">
                                            <img  src="<?= $placeholder; ?>" alt="<?= $entityx->name ?? 'Entity'; ?>">
                                        </a>
                                    </div>
                                    <div class="media-details">
                                        <p><b><?= $visiblefileName; ?></b></p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>   
                </div>
            </div>
            <hr>
            <button class="btn btn-danger btn-lg" onclick="closeMediaPopup()">إغلاق</button>
        </div>
    <div id="overlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:999;" onclick="closeMediaPopup()"></div>
    
    
    <script>
    
        function customMediaButtonClick(editorId) {
            var popupDiv = document.getElementById('media-popup');
            var overlay = document.getElementById('overlay');
            popupDiv.style.display = 'block';
            overlay.style.display = 'block';
            document.getElementById('media-popup').setAttribute('data-editor-id', editorId);
        }
        
        function closeMediaPopup() {
            var popupDiv = document.getElementById('media-popup');
            var overlay = document.getElementById('overlay');
            popupDiv.style.display = 'none';
            overlay.style.display = 'none';
        }
        
        function insertMediaToEditor(fileUrl, mediaType) {
            var popupDiv = document.getElementById('media-popup');
            var editorId = popupDiv.getAttribute('data-editor-id');
            if (!editorId) {alert('Editor ID not found.');return;}
            if (mediaType === 'image') {
                $('#' + editorId).summernote('insertImage', fileUrl, function ($image) {
                $image.attr('alt', 'Media');});
            } else if (mediaType === 'video') {
                const videoTag = `<video controls width="100%"><source src="${fileUrl}" type="video/mp4">Your browser does not support the video tag.</video>`;
                $('#' + editorId).summernote('pasteHTML', videoTag);
            } else if (mediaType === 'pdf') {
                const pdfLink = `<a href="${fileUrl}" target="_blank" style="text-decoration: underline; color: blue;">Download/View PDF</a>`;
                $('#' + editorId).summernote('pasteHTML', pdfLink);
            }
            closeMediaPopup();
        }
                                                
        $(document).ready(function () 
        {
            const entityName = '<?= $entityName; ?>';
            const insert_notifications = '<?php if (isset($insert_notifications)) echo $insert_notifications; ?>';
            const notification_type = '<?php if (isset($notification_type)) echo $notification_type; ?>';
        
            //Search
            $(`#${entityName}Search`).on('input', function () 
            {            
                const query = $(this).val().toLowerCase();
                $(`.${entityName}-item`).each(function () {
                    const itemName = $(this).find('h4').text().toLowerCase();
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
                    const aValue = $(a).find('h4').text().toLowerCase();
                    const bValue = $(b).find('h4').text().toLowerCase();
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
                
                $(`${Addform} input, ${Addform} select, ${Addform} textarea`)
                .not('.note-editor input, .note-editor select, .note-editor textarea, .note-frame input, .note-frame select, .note-frame textarea')
                .each(function () {                                
                    const fieldName = $(this).attr('name');
                    const fieldType = $(this).attr('type');
                    const fieldValue = $(this).val().trim();   
                    
                    if (fieldType === 'checkbox') {
                        if ($(this).is(':checked')) {
                            if (!formData[fieldName]) formData[fieldName] = [];
                            formData[fieldName].push($(this).val());
                        }
                        return;
                    }                
                    if (fieldType === 'radio') {
                        if ($(this).is(':checked')) {
                            formData[fieldName] = $(this).val();
                        }
                        return;
                    }                
                    if (!fieldValue && fieldType !== 'file' && fieldType !== 'radio' && fieldType !== 'checkbox') {
                        alert(`يرجى إدخال ${$(this).attr('placeholder')}`);
                        isValid = false;
                        return false;
                    }
                    if (fieldType !== 'file') {
                        formData[fieldName] = fieldValue;                    
                    }
                });
    
                const fileInput = $(`${Addform} input[type="file"]`)[0];   
                
                if (!isValid) return;  
                
                if (fileInput && fileInput.files.length > 0) {                  
                    const reader = new FileReader();
                    const file = fileInput.files[0];                
                    reader.onload = function (e) {                    
                        formData.file = e.target.result;                                      
                        $.ajax({
                            url: '<?= base_url("Admin/add_entity") ?>',
                            type: 'POST',
                            contentType: 'application/json',
                            data: JSON.stringify({
                                table: entityName,      
                                insert_notifications: insert_notifications,
                                notification_type: notification_type,
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
    
                    reader.readAsDataURL(file);
                } else {     
                                                                              
                    $.ajax({
                        url: '<?= base_url("Admin/add_entity") ?>',
                        type: 'POST',
                        contentType: 'application/json',
                        data: JSON.stringify({
                            table: entityName,
                            insert_notifications: insert_notifications,
                            notification_type: notification_type,                       
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
                
                if (!confirm(`هل أنت متأكد أنك تريد حذف العناصر التالية؟`)) return;
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
                        }
                    },
                    error: function (xhr) {
                        alert(response.message);
                    }
                });
            });
            
            
            //update
            const Editform = `#${entityName}Edit`;
            $(Editform).on('submit', function (e) {
                e.preventDefault();
                entityId = window.editEntityId;
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
                            formData.append(fieldName, file);
                        }
                    } else if ($(this).is('input, select')) {                    
                        const fieldValue = $(this).val().trim();
                        if (fieldValue !== '') {
                            formData.append(fieldName, fieldValue);
                        }
                    }
                     else if ($(this).is('textarea')) {                    
                        let htmlContent = $(this).val(); // Get the raw content from the textarea (including HTML)
                    
                        if (htmlContent !== '') {
                            // Remove the style attribute using regex
                            htmlContent = htmlContent.replace(/style\s*=\s*(['"]).*?\1/g, '');
                    
                            // Append the cleaned HTML content to formData
                            formData.append(fieldName, htmlContent);  // Append plain HTML content to formData
                        }
                    }

                });
            
               
                formData.append('table', entityName);
                formData.append('id_entity', entityId);    

                
                $.ajax({
                    url: '<?= base_url("Admin/update_post_entity") ?>',
                    type: 'POST',
                    data: formData,
                    processData: false, // Important for FormData
                    contentType: false, // Important for FormData
                    success: function (response) {
                        if (response.status === 'success') {
                            location.reload();
                        }
                        else
                        {
                            alert(response.message);
                        }
                    },
                    error: function (response) {
                        alert(response.message);
                    }
                });
            });

 
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
                        $.each(response.data, function (field, value) {
                            const fieldId = `${entityName}_${field}_${id}`;
                            const element = $(`[id^="${entityName}_${field}_"][id$="_${id}"]`);
            
                            if (element.length) {
                                if (element.is('input[type="password"]')) {  
                                    // ✅ Prevent password field from being filled with a hashed value
                                    element.val('');
                                } else if (element.is('input[type="text"], input[type="email"], input[type="phone"], input[type="date"]')) {                                
                                    element.val(value);
                                } else if (element.is('select')) {                                
                                    element.val(value).change();
                                } else if (element.is('input[type="radio"]')) {                                
                                    const radioId = `${entityName}_${field}_${value}_${id}`;
                                    $(`#${radioId}`).prop('checked', true);
                                } else if (element.is('input[type="checkbox"]')) {                                
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
                                    element.attr('src', value);
                                } else if (element.is('textarea')) {
                                    element.summernote({
                                        height: 200,
                                        placeholder: 'اكتب المحتوى هنا ...',
                                        lang: 'ar-AR',
                                        toolbar: [
                                            ['style', ['bold', 'italic', 'underline', 'clear']],
                                            ['font', ['strikethrough', 'superscript', 'subscript']],
                                            ['para', ['ul', 'ol', 'paragraph']],
                                            ['insert', ['link', 'customMediaButton']],
                                            ['view', ['fullscreen', 'codeview', 'help']]
                                        ],
                                        buttons: {
                                            customMediaButton: function (context) {
                                                var ui = $.summernote.ui;
                                                var button = ui.button({
                                                    contents: '<i class="note-icon-picture"></i> إضافة وسائط',
                                                    tooltip: 'إضافة وسائط',
                                                    click: function () {
                                                        customMediaButtonClick(element.attr('id'));
                                                    }
                                                });
                                                return button.render();
                                            }
                                        }
                                    });
                                    element.summernote('code', value);
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

        
        const radios = document.querySelectorAll('input[type="radio"]');
        
        radios.forEach(radio => {
            radio.addEventListener('change', function() {            
                if (this.checked) {                
                    radios.forEach(r => r.removeAttribute('checked'));
                    this.setAttribute('checked', 'checked');
                }
            });
        });
    </script>
<?php ;};?>