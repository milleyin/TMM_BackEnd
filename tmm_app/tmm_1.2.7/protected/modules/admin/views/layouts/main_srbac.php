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
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/admin/main/right/screen.css" media="screen, projection">
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/admin/main/right/print.css" media="print">
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/admin/main/right/ie.css" media="screen, projection">
	<![endif]-->

	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/admin/main/right/main.css">
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/admin/main/right/form.css">
	<title><?php echo CHtml::encode((isset($this->module->id) && $this->module->id=='srbac' ) ?$this->pageTitle : $this->Title); ?></title>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/admin/main/right/right.css">
	
</head>
<body>
<?php 
	if (isset(Yii::app()->components['admin']->loginRequiredAjaxResponse) && Yii::app()->components['admin']->loginRequiredAjaxResponse)
	{
       Yii::app()->clientScript->registerScript('ajaxLoginRequired', '
 jQuery(document).ajaxComplete(function(event,request,options){
		if (jQuery.trim(request.responseText)== "'.Yii::app()->components['admin']->loginRequiredAjaxResponse.'") {
			window.parent.frames.location.href="'.Yii::app()->createUrl('/admin/tmm_login').'";
 		}
 });
		');
 	} 	
 	if(!Yii::app()->getModule('srbac')->debug)
 		Yii::app()->clientScript->registerScript('srbac_ajax', '
 jQuery(document).ajaxComplete(function(event,request,options){
		if (jQuery.trim(request.responseText)=="'.Yii::app()->params['admin_srbac_ajax'].'") {
 			alert("Error:403 你没有权限执行此方法！ '.Yii::app()->request->getUrl().'");
 		}
 });
		');
?>
<div class="container" id="page">
	<?php 
		if(isset($this->breadcrumbs))
		{
			if(isset($this->module->id) && $this->module->id != 'srbac')
				$this->reload();
			$this->widget('zii.widgets.CBreadcrumbs', array(
				'homeLink'=>CHtml::link('首页',array('/admin'),array('target'=>'_parent','title'=>'后台首页')),
				'links'=>$this->breadcrumbs,
			)); 
		}
	?>
	<!-- breadcrumbs -->	
	<?php echo $content; ?>
	
	<div class="clear"></div>
	<div id="footer">
		Copyright &copy; <?php echo date('Y'); ?> by My Company. All Rights Reserved. 
	</div><!-- footer -->
</div><!-- page -->
</body>
</html>