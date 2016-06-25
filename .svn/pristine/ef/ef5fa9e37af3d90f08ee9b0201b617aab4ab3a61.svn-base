<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv = "X-UA-Compatible" content = "IE=edge,chrome=1" />
	<meta name="robots"  content="none">
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo Yii::app()->charset?>">
	<meta name="language" content="<?php echo Yii::app()->language?>">	
	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/admin/screen.css" media="screen, projection">
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/admin/print.css" media="print">
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/admin/ie.css" media="screen, projection">
	<![endif]-->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/admin/main.css">
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/admin/form.css">
	<title><?php echo CHtml::encode($this->Title); ?></title>
</head>
<body>
<?php
Yii::app()->clientScript->registerScript('frames', '
	
if(self.frames.name != "")
	window.parent.frames.location.href="'.Yii::app()->createUrl('/admin').'";

');
?>
<div class="container" id="page">
	<div id="header">
		<div id="logo">
			<?php
				 echo CHtml::link(CHtml::image(Yii::app()->request->baseUrl.'/images/admin/logo.png','加载中……',array('title'=>$this->name)),
				 	array('/admin'));
				 echo '<span>'.CHtml::encode($this->name).'</span>'; 
		 ?>
		</div>
	</div><!-- header -->
	<?php echo $content; ?>
	<div class="clear"></div>	
	<div id="footer">
		Copyright &copy; <?php echo date('Y'); ?> by My Company.
		All Rights Reserved. Powered by <?php echo CHtml::link('回到首页',array('/')) ?>
	</div><!-- footer -->
</div><!-- page -->

</body>
</html>
