<?php
/* @var $this BookmarkController */
/* @var $model Bookmark */
/* @var $form CActiveForm */

$js = $this->renderPartial(
    'js/_search_js',
    $data           = null,
    $return         = true
);
Yii::app()->clientScript->registerScript('search_bookmark', $js);

?>
<div class="search-form" >

    <?php
        $form=$this->beginWidget(
            'ActiveForm',
            array(
                'action'        => Yii::app()->createUrl($this->route),
                'method'        => 'get',
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
                'full_search',
                'Search'
            );
        ?>
    </div>
<?php /*
    <div class="form-group">
        <?php echo $form->bootstrapTextField($model, 'full_search', 10); ?>
    </div>

    <div class="form-group buttons">
        <?php echo $form->bootstrapSubmit('Search', $offset = 0); ?>
    </div>
*/ ?>
    <?php $this->endWidget(); ?>
</div><!-- search-form -->