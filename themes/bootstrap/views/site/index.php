<?php
/* @var $this SiteController */

$this->pageTitle='';

$clientScript = Yii::app()->clientScript;
$clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/angular.min.js');

$clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/angular.controllers.js');



?>











<? /*

<div ng-app>
  <script>
      var bookmark_list_url    = "<?php echo $this->createUrl('bookmark/jsonlist'); ?>" ;
  </script>




  <div ng-controller="BookmarkCtrl">



      <form ng-submit="addBookmark()">
        <div class="col-lg-12">
          <div class="input-group">
            <input
              ng-model="bookmarkURL"
              type="text"
              class="form-control"
              placeholder="paste a URL"
            >
            <span class="input-group-btn">
              <button class="btn btn-primary" type="button">Add!</button>
            </span>
          </div><!-- /input-group -->
        </div><!-- /.col-lg-6 -->
      </form>

  <br />

      <ul>
          <li ng-repeat="bookmark in bookmarks">
              <a href="{{bookmark.url}}">{{bookmark.title}}</a>
          </li>
      </ul>
  </div>
</div>
*/ ?>

