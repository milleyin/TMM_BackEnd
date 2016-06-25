<?php
/* @var $this ExpressController */
/* @var $model Express */

$this->breadcrumbs = array(
    '中奖发货管理页'=>array('admin'),
    $model->name=>array('view', 'id'=>$model->id),
    '创建发货信息页',
);
?>

<h1>发货信息 <font color='#eb6100'><?php echo CHtml::encode($model->name); ?></font></h1>

<div class="form wide">

<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'express-form',
    'focus'=>array($model, 'express_name'),
    'enableAjaxValidation'=>false,
    'enableClientValidation'=>true,
    'clientOptions'=>array(
        'validateOnSubmit'=>true,
    ),
)); ?>

    <p class="note">这些字段<span class="required">*</span>是必须的。</p>

    <?php echo $form->errorSummary($model); ?>

    <div class="row value">
        <?php echo $form->label($model,'name'); ?>
        <?php echo CHtml::encode($model->name); ?>
    </div>

    <div class="row value">
        <?php echo $form->label($model,'phone'); ?>
        <?php echo CHtml::encode($model->phone); ?>
    </div>

    <div class="row value">
        <?php echo $form->label($model,'address'); ?>
        <?php echo CHtml::encode($model->Express_Area_province->name . $model->Express_Area_city->name . $model->Express_Area_district->name . $model->address);?>
    </div>
    
    <div class="row value">
        <?php echo $form->label($model->Express_Record, 'prize_name'); ?>
        <?php echo CHtml::encode($model->Express_Record->prize_name); ?>
    </div>
    
    <div class="row">
        <?php echo $form->label($model->Express_Record->Record_Upload, 'path'); ?>
        <?php echo CHtml::image($model->Express_Record->Record_Upload->getUrlPath(), CHtml::encode($model->Express_Record->Record_Upload->info), array('style'=>'widht:150px;height:150px;')); ?>
    </div>
    
    <div class="row value">
        <?php echo $form->label($model->Express_Record, 'prize_info'); ?>
        <?php echo CHtml::encode($model->Express_Record->prize_info);?>
    </div>
    
    <div class="row value">
        <?php echo $form->label($model->Express_Record->Record_Store, 'store_name'); ?>
        <?php echo CHtml::encode($model->Express_Record->Record_Store->store_name);?>
    </div>
    
    <div class="row value">
        <?php echo $form->label($model->Express_Record->Record_Store, 'phone'); ?>
        <?php echo CHtml::encode($model->Express_Record->Record_Store->phone);?>
    </div>
    
    <div class="row value">
        <?php echo $form->label($model->Express_Record->Record_Pad, 'name'); ?>
        <?php echo CHtml::encode($model->Express_Record->Record_Pad->name);?>
    </div>
    
    <div class="row value">
        <?php echo $form->label($model->Express_Record->Record_Pad, 'number'); ?>
        <?php echo CHtml::encode($model->Express_Record->Record_Pad->number);?>
    </div>
    
    <div class="row">
        <?php echo $form->labelEx($model,'express_name'); ?>
        <?php echo $form->textField($model,'express_name',array('size'=>22,'maxlength'=>32)); ?>
        <?php echo $form->error($model,'express_name'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'express_code'); ?>
        <?php echo $form->textField($model,'express_code',array('size'=>40,'maxlength'=>128)); ?>
        <?php echo $form->error($model,'express_code'); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? '创建' : '保存'); ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- form -->