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
                        <div class="col-md-9 col-xs-8">
                            <h2><b><?php echo $name; ?></b></h2>
                        </div>
                        <!--<div class="col-md-3 col-xs-4">
                            <?php if (!empty($loginType)){ ?>                                
                                <button type="submit" class="btn btn-info btn-lg">سجل في النشاط</button>                        
                            <?php ;} ?>
                        </div>-->
                    </div>           
                </div>  
            </div>
            <div class="white-box">
                <div class="row">
                    <div class="col-md-6">
                        <?php 
                            $folderPath = "uploads/activities_files/";                
                            $filePath = glob($folderPath . $id . ".*");                 
                            $fileExtension = $filePath ? pathinfo($filePath[0], PATHINFO_EXTENSION) : 'png';
                        ?>
                        <img style="border-radius:20px" width="100%" src="https://portal.i-volunteer.ly/uploads/activities_files/<?= $id; ?>.<?php echo $fileExtension;?>"> 
                    </div>
                    <div class="col-md-6">
                        <table style="text-align:center;width: 100%; border-collapse: collapse; margin-top: 20px;">
                            <tr>
                                <td style="background-color: #f2f2f2;padding: 10px; border: 1px solid #ddd;"><b>تاريخ بدء النشاط</b></td>
                                <td style="padding: 10px; border: 1px solid #ddd;"><?php echo $date_from; ?></td>
                            </tr>
                            <tr>
                                <td style="background-color: #f2f2f2;padding: 10px; border: 1px solid #ddd;"><b>تاريخ انتهاء النشاط</b></td>
                                <td style="padding: 10px; border: 1px solid #ddd;"><?php echo $date_to; ?></td>
                            </tr>
                            <tr>
                                <td style="background-color: #f2f2f2;padding: 10px; border: 1px solid #ddd;"><b>المدينة</b></td>
                                <td style="padding: 10px; border: 1px solid #ddd;"><?php echo $city_name; ?></td>
                            </tr>
                            <tr>
                                <td style="background-color: #f2f2f2;padding: 10px; border: 1px solid #ddd;"><b>المؤسسة</b></td>
                                <td style="padding: 10px; border: 1px solid #ddd;"><?php echo $organisation; ?></td>
                            </tr>
                            <tr>
                                <td style="background-color: #f2f2f2;padding: 10px; border: 1px solid #ddd;"><b>وصف النشاط</b></td>
                                <td style="padding: 10px; border: 1px solid #ddd;"><?php echo $description; ?></td>
                            </tr>
                            <tr>
                                <td style="background-color: #f2f2f2;padding: 10px; border: 1px solid #ddd;"><b>المتطلبات للمشاركة</b></td>
                                <td style="padding: 10px; border: 1px solid #ddd;"><?php echo $required_files; ?></td>
                            </tr>
                            <tr>
                                <td style="background-color: #f2f2f2;padding: 10px; border: 1px solid #ddd;"><b>التكفل بالمواصلات</b></td>
                                <td style="padding: 10px; border: 1px solid #ddd;"><?php echo $transportation == 0 ? 'لا' : 'نعم'; ?></td>
                            </tr>
                            <tr>
                                <td style="background-color: #f2f2f2;padding: 10px; border: 1px solid #ddd;"><b>التكفل بالإقامة</b></td>
                                <td style="padding: 10px; border: 1px solid #ddd;"><?php echo $residency == 0 ? 'لا' : 'نعم'; ?></td>
                            </tr>
                            <tr>
                                <td style="background-color: #f2f2f2;padding: 10px; border: 1px solid #ddd;"><b>التكفل بالإعاشة</b></td>
                                <td style="padding: 10px; border: 1px solid #ddd;"><?php echo $expenses == 0 ? 'لا' : 'نعم'; ?></td>
                            </tr>
                            <tr style="">
                                <td style="background-color: #f2f2f2;padding: 10px; border: 1px solid #ddd;"><b>التكفل بالتدريب</b></td>
                                <td style="padding: 10px; border: 1px solid #ddd;"><?php echo $training == 0 ? 'لا' : 'نعم'; ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>