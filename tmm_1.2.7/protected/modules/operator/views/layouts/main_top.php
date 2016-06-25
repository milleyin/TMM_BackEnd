<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv = "X-UA-Compatible" content = "IE=edge,chrome=1" />
	<meta name="robots"  content="none">
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo Yii::app()->charset ?>">
	<meta name="language" content="<?php echo Yii::app()->language?>">
	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/operator/main/top/screen.css" media="screen, projection">
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/operator/main/top/print.css" media="print">
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/operator/main/top/ie.css" media="screen, projection">
	<![endif]-->

	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/operator/main/top/main.css">
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/operator/main/top/form.css">

	<title><?php echo CHtml::encode($this->Title); ?></title>
</head>
<body>
<?php
if(!YII_DEBUG){
	Yii::app()->clientScript->registerScript('frames', '
			
if(self.frames.name != "admin_top")
	window.parent.frames.location.href="'.Yii::app()->createUrl('/operator').'";
			
	');
}
?>
<div id="admin_log">
	<?php 
	echo CHtml::link(
		CHtml::image(Yii::app()->request->baseUrl.'/images/operator/logo.png',
			'加载中……',
			array('title'=>$this->name,)
		),
		array('/operator'),
		array('target'=>'_parent')
	) 
	?>
</div>
<div class="container" id="page">
	<?php echo $content; ?>	
</div>
</body>
</html>
