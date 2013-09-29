<?php
/* @var $this BookmarkController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Bookmarks',
);

$this->menu=array(
	array('label'=>'Create Bookmark', 'url'=>array('create')),
	array('label'=>'Manage Bookmark', 'url'=>array('admin')),
);
?>

<h1>Bookmarks</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
