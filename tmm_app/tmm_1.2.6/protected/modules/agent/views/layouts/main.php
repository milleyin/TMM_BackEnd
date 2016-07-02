<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html>
<head>
	<meta name="robots" content="none" />
	<meta charset="<?php echo Yii::app()->charset?>">
	<meta name="language" content="<?php echo Yii::app()->language?>">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/agent/dist/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/agent/css/form.css">
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/agent/css/signin.css">

	<title><?php echo CHtml::encode($this->Title); ?></title>	
</head>
	<!--[if lt IE 9]>
	<script src="<?php echo Yii::app()->request->baseUrl; ?>/css/agent/dist/js/html5.js"></script>
	<script src="<?php echo Yii::app()->request->baseUrl; ?>/css/agent/dist/js/respond.js"></script>
	<![endif]-->
<body>
<?php
Yii::app()->clientScript->registerScript('frames', '
	
if(self.frames.name != "")
	window.parent.frames.location.href="'.Yii::app()->createUrl('/agent').'";

');
?>
 <?php echo $content; ?>
</body>
</html>
