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
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/operator/main/right/screen.css" media="screen, projection">
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/operator/main/right/print.css" media="print">
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/operator/main/right/ie.css" media="screen, projection">
	<![endif]-->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/operator/main/right/main.css">
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/operator/main/right/form.css">
	<title><?php echo CHtml::encode($this->Title); ?></title>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/operator/main/right/right.css">
</head>
<body><?php 
	if(!YII_DEBUG && !Yii::app()->errorHandler->error)
		Yii::app()->clientScript->registerScript('frames', '
if(self.frames.name != "admin_right")
	window.parent.frames.location.href="'.Yii::app()->createUrl('/operator').'";
		');

	if (isset(Yii::app()->components['operator']->loginRequiredAjaxResponse) && Yii::app()->components['operator']->loginRequiredAjaxResponse){
       Yii::app()->clientScript->registerScript('ajaxLoginRequired', '
 jQuery(document).ajaxComplete(function(event,request,options){
		if (jQuery.trim(request.responseText)== "'.Yii::app()->components['operator']->loginRequiredAjaxResponse.'") {
			window.parent.frames.location.href="'.Yii::app()->createUrl('/operator/login/index').'";
 		}
 });
		');
 	}
?>
<div class="container" id="page">
	<?php 
		if(isset($this->breadcrumbs))
		{	
			$this->reload();
			$this->widget('zii.widgets.CBreadcrumbs', array(
				'homeLink'=>CHtml::link('首页',array('/operator/home/index'),array('target'=>'_parent','title'=>'运营商后台首页')),
				'links'=>$this->breadcrumbs,
			)); 
		}
	?>
	<!-- breadcrumbs -->	
	<?php echo $content; ?>

	<div class="clear"></div>
	<div id="footer">
		Copyright &copy; <?php echo date('Y'); ?> by My Company. All Rights Reserved. Powered by 
		<strong>
			<?php echo CHtml::link($this->powered(),'http://www.365tmm.com/' ,array('target'=>'_blank')) ?>
		</strong>
	</div>
	<!-- footer -->
</div>
	<!-- page -->
<?php echo isset($this->text) ? $this->text : '';?>
	<!-- text -->
</body>
</html>
