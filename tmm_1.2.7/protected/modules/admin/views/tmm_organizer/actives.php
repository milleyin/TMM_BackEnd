<?php
/* @var $this Tmm_organizerController */
/* @var $model Organizer */

$this->breadcrumbs=array(
    '代理商管理页'=>array('admin'),
    $model->Actives_Organizer->firm_phone=>array('view','id'=>$model->id),
    '选择觅趣类型',
);
?>
<h1>选择觅趣类型(代理商)</h1>
<div class="form wide">
    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'actives-form',
        'enableAjaxValidation'=>false,
        'enableClientValidation'=>true,
        'clientOptions'=>array(
            'validateOnSubmit'=>true,
        ),
    )); ?>
    <?php echo $form->errorSummary($model); ?>
    <div class="row">
        <?php echo $form->labelEx($model,'tour_type'); ?>
        <?php echo $form->dropDownList($model,'tour_type',array(''=>'--请选择--')+Actives::$_tour_type); ?>
        <?php echo $form->error($model,'tour_type'); ?>
    </div>
    <div class="row buttons">
        <?php echo CHtml::submitButton('提交选择'); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->