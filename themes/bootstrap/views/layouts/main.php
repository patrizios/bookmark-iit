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
    <script src="<?php echo $theme_path; ?>/js/bootstrap.min.js"></script>
  </head>

  <body>
    <div class="container">
      <div class="header">
        <div class="row">
            <div class="col-lg-6">
                <h3 class="text-muted">
                  <?php echo CHtml::encode(Yii::app()->name); ?>
                </h3>
            </div>
            <div class="col-lg-6">
                <?php
                $this->widget(
                    'zii.widgets.CMenu',
                    array(
                        'items'=>array(
                            array(
                                'label'     => 'Home',
                                'url'       => array('/bookmark/index')
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
                                'label'     => 'Logout',
                                'url'       => array('/site/logout'),
                                'visible'   => !Yii::app()->user->isGuest
                            )
                        ),
                        'htmlOptions' => array(
                            'class' => 'nav nav-pills'
                        ),
                        'activeCssClass' => 'active'
                    )
                ); ?>
            </div>
          </div>
      </div>

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
