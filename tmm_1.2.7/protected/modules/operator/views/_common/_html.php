<?php
/*
$this->renderPartial('/_common/_form/_html',array(
		'form'=>$form,
		'width'=>'98%',
		'height'=>280,
		'model'=>$model,
		'filespath'=>Yii::app ()->basePath .'/..'.Yii::app()->params['uploads_editor'];
		'filesurl'=>Yii::app ()->baseUrl .Yii::app()->params['uploads_editor'];
		'attribute'=>'content',
));
*/
if(!isset($model) || !isset($attribute))
 return false;
if(!isset($height))
	$height='200px';
if(!isset($width))
	$width='85%';
if(!isset($filespath))
	$filespath=Yii::app ()->basePath .'/..'.Yii::app()->params['uploads_editor'];
if(!isset($filesurl))
	$filesurl= Yii::app ()->baseUrl .Yii::app()->params['uploads_editor'];
if(!is_dir($filespath))
		mkdir($filespath, 0777, true);
echo '<div class="row">';
echo $form->labelEx($model,$attribute);
echo '<div style="margin-left:105px;width:92%;">';
		$this->widget('application.extensions.editor.CKkceditor', array(
		"model" => $model, // Data-Model
		"attribute" =>$attribute, // Attribute in the Data-Model
		"height" => $height,
		"width" =>$width,
//        "toolbar_Full"=>array(
//            array( 'Image')
//        ),

//        "toolbar_Full" =>  "array(
//
// 		            array('Cut','Copy','Paste','PasteText','PasteFromWord','-','Print', 'SpellChecker', 'Scayt'),
// 		            array('Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'),
// 		            array('Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField'),
// 		            '/',
// 		            array('Bold','Italic','Underline','Strike','-','Subscript','Superscript'),
// 		            array('NumberedList','BulletedList','-','Outdent','Indent','Blockquote','CreateDiv'),
// 		            array('JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'),
// 		            array('BidiLtr', 'BidiRtl'),
// 		            array('Link','Unlink','Anchor')
// 		       )"      ,

     //   "toolbar_Full" =>  " array('Source','-','Save','NewPage','Preview','-','Templates')",

//          "config" => array (
//  			"toolbar" => array(
//                 array('Cut','Copy','Paste','PasteText','PasteFromWord','-','Print', 'SpellChecker', 'Scayt'),
//  		            array('Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'),
//  		            array('Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField'),
//  		            '/',
//  		            array('Bold','Italic','Underline','Strike','-','Subscript','Superscript'),
//  		            array('NumberedList','BulletedList','-','Outdent','Indent','Blockquote','CreateDiv'),
//  		            array('JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'),
//  		            array('BidiLtr', 'BidiRtl'),
//  		            array('Link','Unlink','Anchor'),
//  		            array('Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak','Iframe'),
//  		            '/',
//  		            array('Styles','Format','Font','FontSize'),
//  		            array('TextColor','BGColor'),
//  		            array('Maximize', 'ShowBlocks','-','About')
//  		       )
//  		 ),
  //   "filespath" =>(!$model->isNewRecord)? Yii::app ()->basePath .'/../upload/editor/' : '',
	//	"filesurl" =>(!$model->isNewRecord)? Yii::app ()->baseUrl . '/upload/editor/' : '',
		//"filespath" =>'http://192.168.0.20'.$filespath,
		"filespath" =>$filespath,
		"filesurl" =>$filesurl,

));
		echo '</div>';
		echo $form->error($model,$attribute,array(),false);
?>
</div>