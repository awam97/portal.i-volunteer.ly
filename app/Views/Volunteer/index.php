<html>
    <head>        
        <meta charset="UTF-8">
        <meta name="description" content="The small framework with powerful features">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" type="image/png" href="uploads/logo.ico">
        <title><?= $page_title;?></title>        
        <script type="text/javascript">var baseurl = '<?php echo base_url();?>';</script>                
        <?php include(APPPATH . 'Views/topcss.php'); ?>        
        <?php include(APPPATH . 'Views/scripts.php'); ?> 
    </head>
    <body class="normal-body" dir="rtl">                                                               
        <div id="wrapper">
            <div class="container-fluid">                
                <div class="col-md-3 col-sm-3 right-page">
                    <div class="col-md-12">
                        <?php include(APPPATH . 'Views/Volunteer/navigation.php'); ?>  
                    </div>
                </div>  
                <div class="col-md-9 col-sm-9 left-page">
                    <div class="col-md-12"> 
                        <div>
                            <div class="page-title-box">
                                <div class="box-title"><?= $page_title ;?></div>           
                            </div>
                        </div>
                        <br> 
                        <?php include(APPPATH . 'Views/Volunteer/'.$page_name.'.php'); ?>                        
                    </div>    
                </div> 
                <br>
                <br>
                <?php include(APPPATH . 'Views/generalfooter.php'); ?>  
            </div>
        </div>                
    </body>
</html>