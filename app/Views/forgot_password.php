<div class="login-panel">
    <div class="login-card white-box">
        <br>
        <center>
            <?php include(APPPATH . 'Views/generalheader.php'); ?>                                                                       
            <div class="box-title"><?= $page_title ;?></div>                        
        </center>
        <form class="login-form form-horizontal" method="POST" id="form_login" action="<?= base_url('send_otp') ?>">
            <div class="form-group">
                <div class="col-xs-12">
                    <input type="text" class="form-control" name="phone" id="phone" placeholder="رقم الهاتف" autocomplete="off">
                </div>            
                <div class="col-xs-12">
                    <button type="submit" class="login-button btn btn-info btn-lg">طلب رمز التحقق</button>
                </div>
            </div>
        </form>                
    </div>
</div>
