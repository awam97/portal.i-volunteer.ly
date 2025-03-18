<?php if (!empty($loginType)){ ?>
    <div class="redirect-dashboard">        
        <a href="<?php echo base_url($loginType . '/dashboard'); ?>" class="btn btn-danger">لوحة التحكم</a>
    </div>
<?php ;} ?>

<?php include(APPPATH . 'Views/generalheader.php'); ?> 
<hr>

    <div class="row">
        <div class="col-md-12">
        <div class="login-panel">
                <div class="page-title-box-lite">
                    <div class="box-title-lite">
                        <div class="row">
                            <div class="col-md-12">
                                <h2><b><?php echo $entities->name; ?></b></h2>
                            </div>                        
                        </div>           
                    </div>  
                </div>
                <div class="white-box">
                    <div class="row">
                        <div class="col-md-12">
                            <?php echo $entities->post_content;?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
