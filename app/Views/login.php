<div class="login-panel">
    <div class="login-card white-box">
        <br>
        <center>
            <?php include(APPPATH . 'Views/generalheader.php'); ?>                                                                       
            <div class="box-title"><?= $page_title ;?></div>                        
        </center>
        <form class="login-form form-horizontal" method="POST" id="form_login" action="<?= base_url('verify_login') ?>">
            <div class="form-group">
                <div class="col-xs-12">
                    <input type="text" class="form-control" name="user" id="user" placeholder="اسم المستخدم / البريد الالكتروني / رقم الهاتف" autocomplete="off">
                </div>            
                <div class="col-xs-12">
                    <input type="password" class="form-control" name="password" id="password" placeholder="كلمة المرور" autocomplete="off">
                </div>
                <div class="col-xs-12">
                <button type="submit" class="login-button btn btn-info btn-lg">تسجيل الدخول</button>
                </div>
            </div>
            <hr>
            <div class="form-group text-center m-t-20">
                <div class="col-xs-12">                    
                    <a href="<?= base_url('register') ?>" class="register-button btn btn-danger btn-lg">انشاء حساب جديد</a>
                </div>
            </div>
            <center>
                <a href="<?= base_url('forgot_password') ?>">هل نسيت كلمة المرور ؟</a>
            </center>
        </form>                
    </div>
</div>
