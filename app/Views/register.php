<div class="login-panel">
    <div class="login-card white-box">
        <br>
        <center>
            <?php include(APPPATH . 'Views/generalheader.php'); ?>                                                                       
            <div class="box-title"><?= $page_title ;?></div>                        
        </center>
        <form class="login-form form-horizontal" method="POST" id="volunteersAdd">
            <div class="form-group">
                <div class="col-xs-12">
                    <input type="text" class="form-control" name="name" id="name" placeholder="الإسم الثلاثي" autocomplete="off" required>
                </div>   
                <div class="col-xs-6">
                <select class="form-control select-box" name="city_id" id="city_id" required>                     
                    <?php foreach ($cities as $row): ?>
                        <option value="<?= $row->id; ?>"><?= $row->name; ?></option>
                    <?php endforeach; ?>
                </select>
                </div>     
                <div class="col-xs-6">
                    <input type="date" class="form-control" name="birthdate" id="birthdate" placeholder="تاريخ الميلاد" autocomplete="off" required>
                </div> 
                <div class="col-xs-6">
                <select class="form-control select-box" name="gender" id="gender" required>                   
                    <?php foreach ($genders as $row): ?>
                        <option value="<?= $row->id; ?>"><?= $row->name; ?></option>
                    <?php endforeach; ?>                 
                </select>
                </div> 
                <div class="col-xs-6">
                    <input type="email" class="form-control" name="email" id="email" placeholder="البريد الإلكتروني" autocomplete="off">
                </div>     
                <div class="col-xs-6">
                    <input type="text" class="form-control" name="username" id="username" placeholder="اسم المستخدم" autocomplete="off" required> 
                </div>   
                <div class="col-xs-6">
                    <input type="phone" class="form-control" name="phone" id="phone" placeholder="رقم الواتساب" autocomplete="off" required>
                </div>
                <div class="col-xs-6">
                    <input type="text" class="form-control" name="identity" id="identity" placeholder="التعريف الشخصي" autocomplete="off">
                </div>                          
                <div class="col-xs-6">
                    <input type="password" class="form-control" name="password" id="password" placeholder="كلمة المرور" autocomplete="off" required>
                </div>
                <div class="col-xs-12">
                    <input type="text" class="form-control" name="address" id="address" placeholder="عنوان السكن" autocomplete="off" required>
                </div>
                <div class="col-xs-12">
                    <input type="text" class="form-control" name="academic_value" id="academic_value" placeholder="المؤهل العلمي / التخصص" autocomplete="off">
                </div>
                <div class="col-xs-12">
                    <input type="text" class="form-control" name="hobbies" id="hobbies" placeholder="الهوايات" autocomplete="off">
                </div>
                <div class="col-xs-12">
                <button type="submit" class="login-button btn btn-info btn-lg">انشاء حساب جديد</button>
                </div>
            </div>            
            <div class="form-group text-center m-t-20">
                <div class="col-xs-6">                    
                    <a href="<?= base_url('login') ?>" class="register-button btn btn-danger btn-lg">لديك حساب بالفعل ؟</a>
                </div>
                <div class="col-xs-6">
                    <a href="https://i-volunteer.ly" class="register-button btn btn-danger btn-lg">العودة للرئيسية</a>
                </div>
            </div>
        </form>                
    </div>
</div>


<script>
    $(document).ready(function () 
    {
        const entityName = 'volunteers';                                                
        const Registerform = `#${entityName}Add`;

        $(Registerform).on('submit', function (e) {
            e.preventDefault();
            const formData = {};
            let isValid = true;
            const fileInput = $(`${Registerform} input[type="file"]`)[0];
            
            $(`${Registerform} input, ${Registerform} select, ${Registerform} textarea`).each(function () {
                const fieldName = $(this).attr('name');
                const fieldType = $(this).attr('type');
                const fieldValue = $(this).val().trim();                                                                                                        
                formData[fieldName] = fieldValue;                
            });
            
                                                                    
            $.ajax({
                url: '<?= base_url("Home/verify_register") ?>',
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({
                    table: entityName,
                    fields_entity: formData
                }),
                success: function (response) {
                    if (response.status === 'success') {
                        window.location.href = '<?= base_url("Home/success_register") ?>';
                    } else {
                        alert(response.message);
                    }
                },
                error: function (xhr) {
                    alert(response.message);
                }
            });
        });                          
    });    
</script>