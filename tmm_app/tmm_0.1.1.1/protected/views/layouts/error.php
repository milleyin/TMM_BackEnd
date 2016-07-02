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
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/admin/main/right/right.css">
	
</head>
<body>
<div class="container" id="page">
	<?php 
		if(isset($this->breadcrumbs))
		{
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
