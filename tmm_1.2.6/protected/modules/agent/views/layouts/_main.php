<!DOCTYPE html>
<html>
<head>
    <meta name="language" content="<?php echo Yii::app()->language?>">
    <meta name="robots" content="noindex,nofollow" />
    <title><?php echo CHtml::encode($this->Title); ?></title>
    <meta charset="<?php echo Yii::app()->charset?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/agent/css/leftside.css">
 </head>
<!--[if lt IE 9]>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/css/agent/dist/js/html5.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/css/agent/dist/js/respond.js"></script>
    <![endif]-->
    <?php echo $content; ?>
</html>
