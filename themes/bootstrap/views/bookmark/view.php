<?php
/* @var $this BookmarkController */
/* @var $model Bookmark */

$this->breadcrumbs=array(
  'Bookmarks'=>array('index'),
  $model->title,
);
/*
$this->menu=array(
  array('label'=>'List Bookmark', 'url'=>array('index')),
  array('label'=>'Create Bookmark', 'url'=>array('create')),
  array('label'=>'Update Bookmark', 'url'=>array('update', 'id'=>$model->id)),
  array('label'=>'Delete Bookmark', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
  array('label'=>'Manage Bookmark', 'url'=>array('admin')),
); */
?>

<h3><?php echo $model->title; ?></h3>

<span class="label label-info">
  <a href="<?php echo $model->url; ?>"
     target="_blank"
     style="color:white;"
  >
    <?php
        echo CHtml::encode(
            TextHelper::shortenize($model->url, 50, '...')
        );
    ?>
    <span class="glyphicon glyphicon-share-alt"></span>
  </a>
</span>


<?php if (trim($model->description)): ?>
  <h4>Description</h4>
  <p>
    <?php echo CHtml::encode($model->description); ?>
  </p>
<?php endif; ?>
<?php /*
$this->widget('zii.widgets.CDetailView', array(
  'data'=>$model,
  'attributes'=>array(
    'id',
    'url',
    'title',
    'description',
    'content',
    'created',
    'updated',
  ),
)); */ ?>