<!DOCTYPE html>
<html>
<head>
 <meta name="robots" content="none" />
  <meta charset="<?php echo Yii::app()->charset ?>">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/agent/css/breadcrumbs.css">
 <title><?php echo CHtml::encode($this->Title); ?></title>
</head>
<!--[if lt IE 9]>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/css/agent/dist/js/html5.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/css/agent/dist/js/respond.js"></script>
<![endif]-->
<body>
<?php 
	if(!YII_DEBUG)
		Yii::app()->clientScript->registerScript('frames', '
if(self.frames.name != "content")
	window.parent.frames.location.href="'.Yii::app()->createUrl('/agent').'";
		');

	if (isset(Yii::app()->components['agent']->loginRequiredAjaxResponse) && Yii::app()->components['agent']->loginRequiredAjaxResponse){
       Yii::app()->clientScript->registerScript('ajaxLoginRequired', '
 jQuery(document).ajaxComplete(function(event,request,options){
		if (jQuery.trim(request.responseText)== "'.Yii::app()->components['agent']->loginRequiredAjaxResponse.'") {
			window.parent.frames.location.href="'.Yii::app()->createUrl('/agent/tmm_login').'";
 		}
 });
		');
 	}

?>	
	<?php echo $content; ?>
</body>
</html>
