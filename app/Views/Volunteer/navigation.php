<?php $volunteer_name = $db->table('volunteers')->where('id', $volunteer_id)->get()->getRow()->name;?>
            
<div class="login-panel">
    <div class="nav-box">
        <center>
            <?php
                $folderPath = "uploads/volunteers_files/";
                $filePath = glob($folderPath . $volunteer_id . ".*"); // Find files with any extension for the volunteer ID
            
                // Check if a file was found and extract its extension, or use a default image
                $fileExtension = $filePath ? pathinfo($filePath[0], PATHINFO_EXTENSION) : '';
                $default_image = 'uploads/user.jpg';
            
                // Determine the image URL based on whether the file exists
                $image_url = ($filePath && file_exists($filePath[0])) 
                    ? base_url($filePath[0]) 
                    : base_url($default_image);
            ?>
            <a href="<?= base_url() ?>">
                <img src="<?= $image_url ?>" class="user-logo"/>
            </a>

            <h3 class="text-white"><?php echo $volunteer_name; ?></h3>
            <hr>
            
            <!-- Hamburger Menu Button -->
            <button id="hamburgerMenu" class="btn btn-info">☰ <span style="font-size:18px">القائمة الرئيسية</span></button>

            <!-- Navigation Menu -->
            <div id="navMenu" class="menu">
                <a href="<?= base_url('Volunteer/dashboard') ?>" class="register-button btn btn-danger">لوحة التحكم</a>            
                <a href="<?= base_url('Volunteer/activities') ?>" class="register-button btn btn-danger">النشاطات المتاحة</a>
                <a href="<?= base_url('Volunteer/my_activities') ?>" class="register-button btn btn-danger">نشاطاتي الخاصة</a>
                <a href="<?= base_url('Volunteer/certificates') ?>" class="register-button btn btn-danger">الشهادات</a>
                <a href="<?= base_url('Volunteer/profile') ?>" class="register-button btn btn-danger">ملفي الشخصي</a>
                <a href="<?= base_url('Volunteer/logout') ?>" class="register-button btn btn-danger">تسجيل الخروج</a>
            </div>
        </center>
    </div>
</div>
<script>
$(document).ready(function () {
    // Toggle the menu when the hamburger button is clicked
    $('#hamburgerMenu').on('click', function () {
        $('#navMenu').toggle(); // Toggle the menu visibility
    });
});
</script>