<html lang="ar">
    <head>        
        <meta charset="UTF-8">
        <meta name="description" content="The small framework with powerful features">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" type="image/png" href="uploads/logo.ico">
        <title><?= $page_title;?></title>        
        <script type="text/javascript">var baseurl = '<?php echo base_url();?>';</script>
    </head>
    <body dir="rtl" class="centered-body">                                               
        <?php include(APPPATH . 'Views/topcss.php'); ?>
        <?php include(APPPATH . 'Views/scripts.php'); ?>
        <div id="wrapper">
            <div class="container-fluid">
                <div class="col-md-12">
                    <div class="col-md-4">                                                
                    </div> 
                    <div class="col-md-4 main-page">                                                                          
                        <?php if($page_name=='login')
                        {
                            include(APPPATH . 'Views/login.php'); 
                        }
                        else
                        {
                             
                            include(APPPATH . 'Views/'.$page_name.'.php'); 
                        ;}?>
                        <br>                     
                        <?php include(APPPATH . 'Views/generalfooter.php'); ?>     
                    </div>    
                    <div class="col-md-4">
                    </div>   
                </div>                   
            </div>
        </div>                
    </body>
</html>