<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv = "X-UA-Compatible" content ="IE=edge,chrome=1" />
    <meta name="robots"  content="none">
    <meta http-equiv="Content-Type" content="text/html; charset=<?php echo Yii::app()->charset ?>">
    <meta name="language" content="<?php echo Yii::app()->language?>">
    <!--<meta http-equiv="pragma" content="no-cache" />-->
    <!-- blueprint CSS framework -->
    <link rel="stylesheet" type="text/css" href="<?php echo $this->getAssets(); ?>/css/main/screen.css" media="screen, projection">
    <link rel="stylesheet" type="text/css" href="<?php echo $this->getAssets(); ?>/css/main/print.css" media="print">
    <!--[if lt IE 8]>
    <link rel="stylesheet" type="text/css" href="<?php echo $this->getAssets(); ?>/css/main/ie.css" media="screen, projection">
    <![endif]-->
    <link rel="stylesheet" type="text/css" href="<?php echo $this->getAssets(); ?>/css/main/main.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $this->getAssets(); ?>/css/main/form.css">
    <title><?php echo CHtml::encode($this->title); ?></title>
    <link rel="stylesheet" type="text/css" href="<?php echo $this->getAssets(); ?>/css/main/last.css">
</head>
<body>
<?php
    if (isset(Yii::app()->components['user']->loginRequiredAjaxResponse) && Yii::app()->components['user']->loginRequiredAjaxResponse)
    {
       Yii::app()->clientScript->registerScript('ajaxLoginRequired', '
jQuery(document).ajaxComplete(function(event, request, options) {
  if (request.responseJSON && request.responseJSON.errorCode && request.responseJSON.errorCode == 302 && request.responseJSON.location == "' . CHtml::normalizeUrl(Yii::app()->components['user']->loginRequiredAjaxResponse) . '") {
    window.location.href = "' . CHtml::normalizeUrl(Yii::app()->components['user']->loginRequiredAjaxResponse) . '";
  }
});
        ');
     }
?>
    <div id="menu">
        <?php $this->renderPartial('/layouts/_common/_menu'); ?>
    </div>
    <!-- menu -->    
    <div id="main">
    
        <div id="menuitem">
            <?php $this->renderPartial('/layouts/_common/_menuitem'); ?>
        </div>
        <!-- menuitem -->    
        <div class="container" id="page" >
            <?php 
                if (isset($this->breadcrumbs))
                {
                    $this->breadcrumbs['刷新页面'] = Yii::app()->request->getUrl();
                    $this->breadcrumbs['<<  返回'] = 'javascript:history.go(-1);';
                    $this->widget('zii.widgets.CBreadcrumbs', array(
                        'homeLink'=>CHtml::link('首页', Yii::app()->homeUrl, array('title'=>$this->name)),
                        'links'=>$this->breadcrumbs,
                    ));
                }
            ?>
            <!-- breadcrumbs -->    
            <?php echo $content; ?>    
            <!-- content -->
            <div class="clear"></div>
            <div id="footer">
                Copyright &copy; <?php echo date('Y'); ?> by My Company. All Rights Reserved. Powered by 
                <strong>
                    <?php echo CHtml::link(Helper::getPowered(), 'http://www.365tmm.com/', array('target'=>'_blank')) ?>
                </strong>
            </div>
            <!-- footer -->
        </div>
        <div class="clear"></div>    
    </div>
    <!-- page -->
</body>
</html>
