<?php
/* @var $this BookmarkController */
/* @var $data Bookmark */
?>
<div class="view list-group-item">
  <h4>
    <?php $title = $data->title ? CHtml::encode($data->title) : 'No title'; ?>
    <?php echo CHtml::link($title, array('view', 'id'=>$data->id)); ?>
  </h4>
  <p>
    <small>
      <strong>
        <a class  = "text-muted"
           href   = "<?php echo $data->url; ?>"
           target = "_blank"
        >
          <?php
              echo CHtml::encode(
                  TextHelper::shortenize($data->url, 50, '...')
              );
          ?>
          <span class="glyphicon glyphicon-share-alt"></span>
        </a>
      </strong>
    </small>
  </p>
  <?php if (trim($data->description)): ?>
    <p>
      <?php echo CHtml::encode($data->description); ?>
    </p>
  <?php endif; ?>
</div>