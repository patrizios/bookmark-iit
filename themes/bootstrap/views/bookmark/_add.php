<?php
/* @var $this BookmarkController */
/* @var $model Bookmark */
/* @var $form CActiveForm */

$js = $this->renderPartial(
    'js/_add_js',
    $data           = null,
    $return         = true
);
Yii::app()->clientScript->registerScript('add_bookmark', $js);
?>

<div class="add-form" >

    <?php
        $form=$this->beginWidget(
            'ActiveForm',
            array(
                'action'        => Yii::app()->createUrl($this->route),
                'method'        => 'post',
                'htmlOptions'   => array(
                    'role'  => 'form',
                    'class' => 'form',
                ),
            )
        );
    ?>

    <div class="form-group">
        <?php
            echo $form->boostrapTextButton(
                $model,
                'url',
                'Add!',
                'Paste a URL'
            );
        ?>
    </div>

    <div id="add-errors"
        class="alert alert-danger"
        style="margin-top:10px; display:none;"
    >
        <button type="button" class="close" aria-hidden="true">&times;</button>
        <ul></ul>
    </div>

    <?php $this->endWidget(); ?>

<?php /*
    <div id="add-errors" class="alert alert-danger" style="display:none">
    </div> */ ?>
</div><!-- search-form -->
