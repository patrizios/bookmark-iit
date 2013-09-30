<?php
/* @var $this BookmarkController */
/* @var $dataProvider CActiveDataProvider */

$this->pageTitle    = '';
?>

<ul class="nav nav-tabs" id="myTab">
  <li class="active">

    <a href="#add-tab"><span class="glyphicon glyphicon-plus"></span> Add bookmark</a>
  </li>
  <li><a href="#search-tab"><span class="glyphicon glyphicon-search"></span> Search</a></li>
</ul>

<div class="tab-content">
  <div class="tab-pane active" id="add-tab">
    <br />
    <?php $this->renderPartial('_add', array('model'=>$model)); ?>
  </div>
  <div class="tab-pane" id="search-tab">
    <br />
    <?php $this->renderPartial('_search', array('model'=>$model)); ?>
  </div>
</div>

<script>
  $(function () {
    $('#myTab a').click(function (e) {
      e.preventDefault()
      $(this).tab('show')
    })
});
</script>

<?php $this->widget(
    'zii.widgets.CListView',
    array(
        'dataProvider'          => $dataProvider,
        'itemView'              => '_view',
        'id'                    => 'bookmarklistview',       // must have id corresponding to js above
        'sortableAttributes'    => array(
            'title',
            'created',
        ),
        'pagerCssClass'    => 'yii-pager text-center',
        'pager'         => array(
          'class'          => 'CLinkPager',
          'cssFile'        => false,
          'htmlOptions'    => array('class' => 'pagination'),
          'header'         => false,
          'selectedPageCssClass' => 'active',
          /*'firstPageLabel' => '<<',
          'prevPageLabel'  => '<',
          'nextPageLabel'  => '>',
          'lastPageLabel'  => '>>', */
        ),
    )
);
?>
<?php /* $this->widget('zii.widgets.CListView', array(
    'dataProvider'=>$dataProvider,
    'itemView'=>'_view',
)); */ ?>
