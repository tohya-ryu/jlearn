<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" 
      href="<?php $this->base_uri('assets/style.css');?>"/>
    <script type="text/javascript" src="
      <?php $this->base_uri('assets/framework.js');?>"></script>
    <script type="text/javascript" src="/assets/framework-auth.js"></script>    

    <title><?php echo $this->view->title;?></title>
  </head>
    
  <body>
    <?php $this->view->render_content(); ?>
  </body>

</html>
