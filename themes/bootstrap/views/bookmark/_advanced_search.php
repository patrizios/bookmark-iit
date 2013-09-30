<?php
/* @var $this BookmarkController */
/* @var $model Bookmark */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget(
    'ActiveForm',
    array(
        'action'        => Yii::app()->createUrl($this->route),
        'method'        => 'get',
        'htmlOptions'   => array(
            'role'  => 'form',
            'class' => 'form-horizontal',
        ),
    )

); ?>

    <div class="form-group">
        <?php echo $form->bootstrapLabel($model, 'id'); ?>
        <?php echo $form->bootstrapTextField($model, 'id'); ?>
    </div>

    <div class="form-group">
        <?php echo $form->bootstrapLabel($model, 'url'); ?>
        <?php echo $form->bootstrapTextField($model, 'url'); ?>
    </div>

    <div class="form-group">
        <?php echo $form->bootstrapLabel($model, 'title'); ?>
        <?php echo $form->bootstrapTextField($model, 'title'); ?>
    </div>

    <div class="form-group">
        <?php echo $form->bootstrapLabel($model, 'description'); ?>
        <?php echo $form->bootstrapTextField($model, 'description'); ?>
    </div>

    <div class="form-group">
        <?php echo $form->bootstrapLabel($model, 'content'); ?>
        <?php echo $form->bootstrapTextField($model, 'content'); ?>
    </div>
    <div class="form-group buttons">
        <?php echo CHtml::submitButton('Search'); ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->