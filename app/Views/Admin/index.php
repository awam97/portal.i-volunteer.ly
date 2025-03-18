<html lang="<?= $language ;?>">
<head>        
    <meta charset="UTF-8">
    <meta name="description" content="The small framework with powerful features">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/png" href="<?= base_url(); ?>uploads/logo.ico">
    <title><?= $page_title; ?></title>        
    <script type="text/javascript">var baseurl = '<?= base_url(); ?>';</script>                
    <?php include(APPPATH . 'Views/topcss.php'); ?>  
    <?php include(APPPATH . 'Views/scripts.php'); ?> 
</head>
<body class="normal-body" dir="<?php if($language == 'en'){echo 'ltr';}else{echo 'rtl';}?>">
    <div class="preloader">
        <div class="cssload-speeding-wheel"></div>
    </div>                                                               
    <div id="wrapper">
        <div class="container-fluid row">                
            <div class="col-md-3 <?php if($language == 'en'){echo 'left-page';}else{echo 'right-page';}; ?> col-xs-12">
                <div class="col-md-12">
                    <?php include(APPPATH . 'Views/Admin/navigation.php'); ?>
                </div>
            </div>  
            
            <div class="col-md-9 <?php if($language == 'en'){echo 'right-page';}else{echo 'left-page';}; ?> col-xs-12">
                <div class="col-md-12"> 
                    <div>
                        <div class="page-title-box">
                            <h1><div class="box-title"><b><?= $page_title; ?></b></div></h1>        
                        </div>
                        <?php include(APPPATH . 'Views/Admin/' . $page_name . '.php'); ?>
                    </div> 
                </div>
                
            </div>
        </div>
    </div>                
</body>
</html>
<script>
    window.addEventListener('load', () => {
      const preloader = document.querySelector('.preloader');
      setTimeout(() => {
        preloader.style.transition = 'opacity 0.5s ease';
        preloader.style.opacity = '0';
        setTimeout(() => {
          preloader.style.display = 'none';
        }, 500);
      });
    });
</script>
