<?php
/* @var $this Tmm_organizerController */
/* @var $model Organizer */

$this->breadcrumbs=array(
	'运营商管理页'=>array('/admin/tmm_agent/admin'),
	$model->Shops_Agent->phone=>array('/admin/tmm_agent/view','id'=>$model->Shops_Agent->id),
    '选择商品类型',
);
?>
<h1>选择商品类型(运营商)</h1>
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
        <?php echo $form->labelEx($model,'c_id'); ?>
        <?php echo $form->dropDownList($model,'c_id',ShopsClassliy::data(true,array(''=>'--请选择--'),ShopsClassliy::create_agent)); ?>
        <?php echo $form->error($model,'c_id'); ?>
    </div>
    <div class="row buttons">
        <?php echo CHtml::submitButton('提交选择'); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->