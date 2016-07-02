<?php
/* @var $this Tmm_organizerController */
/* @var $model Organizer */

$this->breadcrumbs=array(
    '供应商主账号管理页'=>array('/admin/tmm_store/admin'),
	$model->Items_StoreContent->Content_Store->phone=>array('/admin/tmm_store/view','id'=>$model->Items_StoreContent->id),
    '选择项目类型',
);
?>
<h1>选择项目类型</h1>
<div class="form wide">
    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'items-form',
        'enableAjaxValidation'=>false,
        'enableClientValidation'=>true,
        'clientOptions'=>array(
            'validateOnSubmit'=>true,
        ),
    )); ?>
    <?php echo $form->errorSummary($model); ?>
    <div class="row">
        <?php echo $form->labelEx($model,'c_id'); ?>
        <?php echo $form->dropDownList($model,'c_id',ItemsClassliy::data()); ?>
        <?php echo $form->error($model,'c_id'); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton('提交选择'); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->