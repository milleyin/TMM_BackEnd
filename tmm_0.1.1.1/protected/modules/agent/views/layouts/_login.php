<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?php echo Yii::app()->charset?>">
    <meta name="language" content="<?php echo Yii::app()->language?>">
    <meta name="robots" content="noindex,nofollow" />
    <title><?php echo CHtml::encode($this->Title); ?></title>
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/agent/dist/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/agent/login/login.css">
<!--    <script src="--><?php //echo Yii::app()->request->baseUrl; ?><!--/css/agent/dist/js/jquery-1.11.3.min.js"></script>-->
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/css/agent/dist/js/bootstrap.min.js"></script>
    <!--[if lt IE 9]>
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/css/agent/dist/js/html5.js"></script>
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/css/agent/dist/js/respond.js"></script>
    <![endif]-->
</head>
<?php echo $content; ?>
</html>



