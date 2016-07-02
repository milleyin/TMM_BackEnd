<?php
/* @var $this Tmm_organizerController */
/* @var $model Organizer */

$this->breadcrumbs=array(
	'线路管理页',
	$model->name=>array('/admin/tmm_'.$model->Shops_ShopsClassliy->admin.'/view','id'=>$model->id),
    '设置推荐理由',
);
?>
<h1>设置推荐理由</h1>
<div class="form wide">
    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'shops-form',
        'enableAjaxValidation'=>false,
        'enableClientValidation'=>true,
        'clientOptions'=>array(
            'validateOnSubmit'=>true,
        ),
    )); ?>
    <?php echo $form->errorSummary($model); ?>
    <div class="row">
        <?php echo $form->labelEx($model,'selected_info'); ?>
        <?php echo $form->textArea($model,'selected_info',array('style'=>'width:300px;height:100px;'));?>
        <?php echo $form->error($model,'selected_info'); ?>
    </div>
    <div class="row buttons">
        <?php echo CHtml::submitButton('保存'); ?>
    </div>
    <?php $this->endWidget(); ?>

</div><!-- form -->