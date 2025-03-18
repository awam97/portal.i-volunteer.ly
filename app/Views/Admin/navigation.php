<?php $is_owner = $db->table('admin')->where('id',$admin_id)->get()->getRow()->owner;?>
<div class="login-panel">
    <div class="nav-box">
        <center>
            <?php
                $folderPath = "uploads/admin_files/"; 
                $filePath = glob($folderPath . $admin_id . ".*"); 
                $fileExtension = $filePath ? pathinfo($filePath[0], PATHINFO_EXTENSION) : 'jpg';
                $image_path = $folderPath . $admin_id . '.' . $fileExtension;
                $default_image = 'uploads/user.jpg';
                $image_url = file_exists($image_path) ? base_url($image_path) : base_url($default_image);
            ?>
            <a href="<?= base_url() ?>"><img src="<?= $image_url ?>" class="user-logo"/></a>
            <h3 class="text-white"><?php echo $adminData->name; ?></h3>
            <hr>                        
            <button id="hamburgerMenu" class="btn btn-info">☰ <span style="font-size:18px">القائمة الرئيسية</span></button>
            
            <div id="navMenu" class="menu">
                <a href="<?= base_url('Admin/dashboard') ?>" class="register-button btn btn-danger"><?= $translate->translate('dashboard',$language);?></a>
                <?php if($is_owner == 1){;?>
                    <a href="<?= base_url('Admin/admins') ?>" class="register-button btn btn-danger"><?= $translate->translate('admins',$language);?></a>
                <?php ;};?>
                <a href="<?= base_url('Admin/cities') ?>" class="register-button btn btn-danger"><?= $translate->translate('cities',$language);?></a>
                <a href="<?= base_url('Admin/activities') ?>" class="register-button btn btn-danger"><?= $translate->translate('activities',$language);?></a>
                <a href="<?= base_url('Admin/volunteers') ?>" class="register-button btn btn-danger"><?= $translate->translate('volunteers',$language);?></a>
                <a href="<?= base_url('Admin/volunteer_activities') ?>" class="register-button btn btn-danger"><?= $translate->translate('volunteer_activities',$language);?></a>
                <a href="<?= base_url('Admin/news') ?>" class="register-button btn btn-danger"><?= $translate->translate('news',$language);?></a>
                <a href="<?= base_url('Admin/library') ?>" class="register-button btn btn-danger"><?= $translate->translate('library',$language);?></a>
                <a href="<?= base_url('Admin/profile') ?>" class="register-button btn btn-danger"><?= $translate->translate('profile',$language);?></a>
                <a href="<?= base_url('Admin/logout') ?>" class="register-button btn btn-danger"><?= $translate->translate('logout',$language);?></a>
            </div>
        </center>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('#hamburgerMenu').on('click', function () {
            $('#navMenu').toggle();
        });
    });
</script>