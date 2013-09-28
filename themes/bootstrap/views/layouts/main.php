<?php /* @var $this Controller */ ?>
<?php $theme_path = Yii::app()->theme->baseUrl; ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="<?php echo $theme_path; ?>/assets/ico/favicon.png">

    <title><?php echo CHtml::encode($this->pageTitle); ?></title>

    <!-- Bootstrap core CSS -->
    <link href="<?php echo $theme_path; ?>/css/bootstrap.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?php echo $theme_path; ?>/css/jumbotron-narrow.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="../../assets/js/html5shiv.js"></script>
      <script src="../../assets/js/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>
    <div class="container">
      <div class="header">
        <?php /*  <ul class="nav nav-pills pull-right">
          <li class="active"><a href="#">Home</a></li>
          <li><a href="<?php echo $this->createUrl('site/about'); ?>">About</a></li>
          <li><a href="<?php echo $this->createUrl('site/page', array('about')); ?>">Contact</a></li>
       </ul> */ ?>

       <?php
        $this->widget(
            'zii.widgets.CMenu',
            array(
                'items'=>array(
                    array(
                        'label'     => 'Home',
                        'url'       => array('/site/index')
                    ),
                    array(
                        'label'     => 'About',
                        'url'       => array('/site/page', 'view'=>'about')
                    ),
                    array(
                        'label'     => 'Contact',
                        'url'       => array('/site/contact')
                    ),
                    array(
                        'label'     => 'Login',
                        'url'       => array('/site/login'),
                        'visible'   => Yii::app()->user->isGuest
                    ),
                    array(
                        'label'     => 'Logout ('.Yii::app()->user->name.')',
                        'url'       => array('/site/logout'),
                        'visible'   => !Yii::app()->user->isGuest
                    )
                ),
                'htmlOptions' => array(
                    'class' => 'nav nav-pills pull-right'
                ),
                'activeCssClass' => 'active'
            )
        ); ?>

        <h3 class="text-muted">
          <?php echo CHtml::encode(Yii::app()->name); ?>
        </h3>
      </div>
      <h1><?php echo CHtml::encode($this->pageTitle); ?></h1>

      <div class="row">
        <div class="col-lg-12">
          <?php echo $content; ?>
        </div>
      </div>

      <div class="footer">
        <p>&copy; Patrizio Sepe 2013</p>
      </div>

    </div> <!-- /container -->

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
  </body>
</html>
