<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */

$this->pageTitle    = 'Login';
$this->breadcrumbs  = array(
    'Login',
);
?>

<p>Please fill out the following form with your login credentials:</p>

<div class="form">
    <?php
        $form = $this->beginWidget(
            'ActiveForm',
            array(
                'id'                        => 'contact-form',
                'enableClientValidation'    => true,
                'errorMessageCssClass'      => 'label label-danger',
                'clientOptions'             => array(
                    'validateOnSubmit' => true,
                ),
                'htmlOptions'               => array(
                    'role'  => 'form',
                    'class' => 'form-horizontal',
                ),
            )
        );
    ?>

    <div class="form-group">
        <?php echo $form->bootstrapLabel($model, 'username'); ?>
        <?php echo $form->bootstrapTextField($model, 'username'); ?>
    </div>

    <div class="form-group">
        <?php echo $form->bootstrapLabel($model, 'password'); ?>
        <?php echo $form->bootstrapPasswordField($model, 'password'); ?>
    </div>

    <div class="checkbox">
        <div class="col-md-offset-2">
            <?php echo $form->checkBox($model, 'rememberMe'); ?>
            <?php echo $form->label($model, 'rememberMe'); ?>
            <?php echo $form->error($model, 'rememberMe'); ?>
        </div>
    </div>
    <br />
    <div class="form-group">
        <?php echo $form->bootstrapSubmit('Login'); ?>
    </div>

<?php $this->endWidget(); ?>
</div><!-- form -->
