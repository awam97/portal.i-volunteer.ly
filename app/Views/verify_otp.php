<div class="login-panel">
    <div class="login-card white-box">
        <br>
        <center>
            <?php include(APPPATH . 'Views/generalheader.php'); ?>                                                                       
            <div class="box-title"><?= $page_title ;?></div>                        
        </center>
        <form class="login-form form-horizontal" method="POST" id="form_login" action="<?= base_url('verify_otp') ?>">
            <div class="form-group">
                <div class="col-xs-12">
                    <input type="text" class="form-control" name="otp" id="otp" placeholder="رمز التحقق" autocomplete="off">
                </div>            
                <div class="col-xs-12">
                    <button type="submit" class="login-button btn btn-info btn-lg">التحقق من الرمز</button>
                </div>
            </div>
        </form>                
    </div>
</div>
