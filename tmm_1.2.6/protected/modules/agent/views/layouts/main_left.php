<!DOCTYPE html>
<html>
<head>
  <title><?php echo CHtml::encode($this->name); ?></title>
 <meta name="robots" content="none" />
  <meta charset="<?php echo Yii::app()->charset ?>">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/agent/css/leftside.css">
</head>
  <!--[if lt IE 9]>
  <script src="<?php echo Yii::app()->request->baseUrl; ?>/css/agent/dist/js/html5.js"></script>
  <script src="<?php echo Yii::app()->request->baseUrl; ?>/css/agent/dist/js/respond.js"></script>
  <![endif]-->
<body>
<?php
if(!YII_DEBUG)
    Yii::app()->clientScript->registerScript('frames', '
if(self.frames.name != "index")
	window.parent.frames.location.href="'.Yii::app()->createUrl('/agent').'";

	');
?>
 <?php echo $content; ?>
 </body>
</html>

