<?php
/* @var $this BookmarkController */
/* @var $model Bookmark */

$this->breadcrumbs=array(
	'Bookmarks'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Bookmark', 'url'=>array('index')),
	array('label'=>'Manage Bookmark', 'url'=>array('admin')),
);
?>

<h1>Create Bookmark</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>