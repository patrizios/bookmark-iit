<?php
/* @var $this SiteController */
/* @var $model ContactForm */
/* @var $form CActiveForm */

$this->pageTitle    = 'Contact Us';
$this->breadcrumbs  = array(
    'Contact',
);
?>

<?php if(Yii::app()->user->hasFlash('contact')): ?>

<div class="flash-success">
    <?php echo Yii::app()->user->getFlash('contact'); ?>
</div>

<?php else: ?>

<p>
If you have business inquiries or other questions, please fill out the following form to contact us. Thank you.
<br/>
</p>

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
    ); ?>

    <p class="note"><small>Fields with <span class="required">*</span> are required.</small><br/>
<br/>
</p>
    <?php
        echo $form->errorSummary(
            $model,
            $header = null,
            $footer = null,
            $htmlOptions = array(
                'class' => 'alert alert-danger'
            )
        );
    ?>

    <div class="form-group">
        <?php echo $form->bootstrapLabel($model, 'name'); ?>
        <?php echo $form->bootstrapTextField($model, 'name'); ?>
    </div>


    <div class="form-group">
        <?php echo $form->bootstrapLabel($model, 'email'); ?>
        <?php echo $form->bootstrapEmailField($model, 'email'); ?>
    </div>

    <div class="form-group">
        <?php echo $form->bootstrapLabel($model, 'subject'); ?>
        <?php echo $form->bootstrapTextField($model, 'subject', 8); ?>
    </div>

    <div class="form-group">
        <?php echo $form->bootstrapLabel($model, 'body'); ?>
        <?php echo $form->bootstrapTextArea($model, 'body'); ?>
    </div>

    <?php if(CCaptcha::checkRequirements()): ?>
        <div class="form-group">

            <?php echo $form->bootstrapLabel($model, 'verifyCode'); ?>

            <div>
                <?php $this->widget('CCaptcha'); ?>
                <?php echo $form->bootstrapTextField($model, 'verifyCode'); ?>
            </div>

            <?php echo $form->error($model, 'verifyCode'); ?>
        </div>
        <div class="form-group">
            <div class="col-md-offset-3">
                <small>Please enter the letters as they are shown in the image above.
                <br/>Letters are not case-sensitive.</small>
            </div>
        </div>

    <?php endif; ?>

    <div class="form-group buttons">
        <?php echo $form->bootstrapSubmit('Submit'); ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- form -->

<?php endif; ?>