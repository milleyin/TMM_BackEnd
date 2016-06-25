<?php
/* @var $this ExpressController */
/* @var $model Express */

$this->breadcrumbs=array(
    '中奖发货管理页'=>array('admin'),
    '创建领奖信息页',
);
?>

<h1>领奖信息</h1>

<div class="form wide">

<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'express-form',
    //'focus'=>array($model, 'columnName'),
    'enableAjaxValidation'=>false,
    'enableClientValidation'=>true,
    'clientOptions'=>array(
        'validateOnSubmit'=>true,
    ),
)); ?>

    <p class="note">这些字段<span class="required">*</span>是必须的。</p>

    <?php echo $form->errorSummary($model); ?>

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
        <?php echo $form->labelEx($model,'name'); ?>
        <?php echo $form->textField($model,'name',array('size'=>10,'maxlength'=>32)); ?>
        <?php echo $form->error($model,'name'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'phone'); ?>
        <?php echo $form->textField($model,'phone',array('size'=>11,'maxlength'=>11)); ?>
        <?php echo $form->error($model,'phone'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'province'); ?>
        <?php echo $form->dropDownList($model, 'province', array(''=>'--请选择--') + Area::model()->getAreaArray(), array(
                'ajax'=>array(
                    'type'=>'POST',
                    'url'=>CHtml::normalizeUrl(array('area/drop')),
                    'data'=>array('pid'=>'js:this.value', Yii::app()->request->csrfTokenName=>Yii::app()->request->csrfToken),
                    'success'=>'function(data){
                            jQuery("#' . CHtml::activeId($model, 'city') . '").html(data);
                            jQuery("#' . CHtml::activeId($model, 'district') . '").html("<option value=\"\" selected=\"selected\">--请选择--</option>");
                     }',
                ),
        )); ?>
        <?php echo $form->error($model,'province'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'city'); ?>
        <?php echo $form->dropDownList($model, 'city', array(''=>'--请选择--') + Area::model()->getAreaArray($model->province), array(
                'ajax'=>array(
                    'type'=>'POST',
                    'url'=>CHtml::normalizeUrl(array('area/drop')),
                    'data'=>array('pid'=>'js:this.value', Yii::app()->request->csrfTokenName=>Yii::app()->request->csrfToken),
                    'update'=>'#' . CHtml::activeId($model, 'district'),
                ),
        )); ?>
        <?php echo $form->error($model, 'city'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'district'); ?>
        <?php echo $form->dropDownList($model, 'district', array(''=>'--请选择--') + Area::model()->getAreaArray($model->city)); ?>
        <?php echo $form->error($model, 'district'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'address'); ?>
        <?php echo $form->textField($model,'address',array('size'=>60,'maxlength'=>128)); ?>
        <?php echo $form->error($model,'address'); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? '创建' : '保存'); ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- form -->