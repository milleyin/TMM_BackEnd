<?php
/* @var $this Tmm_organizerController */
/* @var $model Organizer */

$this->breadcrumbs=array(
    '运营商管理页'=>array('admin'),
    $model->Push_Agent->phone=>array('view','id'=>$model->Push_Agent->id),
    '设置运营商分成比率',
);
?>
<h1>设置运营商分成比率<font color='#eb6100'><?php echo $model->Push_Agent->phone; ?></font></h1>
<div class="form wide">
    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'agent-form',
        'enableAjaxValidation'=>true,
        'enableClientValidation'=>true,
        'clientOptions'=>array(
            'validateOnSubmit'=>true,
        ),
    )); ?>
    <p class="note">这些字段 <span class="required">*</span>是必须的</p>
    <?php echo $form->errorSummary($model); ?>
    <div class="row value">
        <?php echo $form->label($model,'executed'); ?>
        <?php echo $model::executed($model->Push_Agent->id,Push::push_agent); ?>%
    </div>
    <div class="row">
        <?php echo $form->labelEx($model,'_start_time'); ?>
        <?php $this->widget('zii.widgets.jui.CJuiDatePicker',array(
            'language'=>Yii::app()->language,
            'model'=>$model,
            'attribute'=>'_start_time',
            'value'=>date('Y-m-d'),
            'options'=>array(
                //'maxDate'=>'new date()',
                'minDate'=>'new date()',
                'dateFormat'=>'yy-mm-dd',
                'showOn' => 'focus',
                'showOtherMonths' => true,
                'selectOtherMonths' => true,
                'changeMonth' => true,
                'changeYear' => true,
                'showButtonPanel' => true,
            ),
            'htmlOptions'=>array(
            ),
        ));
        ?>
        <?php echo $form->error($model,'_start_time'); ?>
    </div>
    <div class="row">
        <?php echo $form->labelEx($model,'push'); ?>
        <?php echo $form->textField($model,'push'); ?>%
        <?php echo $form->error($model,'push'); ?>
    </div>
    <div class="row">
        <?php echo $form->labelEx($model,'info'); ?>
        <?php echo $form->textArea($model,'info',array('style'=>'width:300px;height:100px;')); ?>
        <?php echo $form->error($model,'info'); ?>
    </div>
    <div class="row buttons">
        <?php echo CHtml::submitButton('保存'); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->