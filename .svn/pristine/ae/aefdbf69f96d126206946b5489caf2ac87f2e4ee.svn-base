<?php
/* @var $this PrizeController */
/* @var $model Prize */

$this->breadcrumbs = array(
    '奖品管理页'=>array('admin'),
    $model->name=>array('view', 'id'=>$model->id),
    '更新谢谢参与页',
);
?>

<h1>更新谢谢参与 <font color='#eb6100'><?php echo CHtml::encode($model->name); ?></font></h1>

<div class="form wide">

<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'prize-form',
    'focus'=>array($model, 'name'),
    'enableAjaxValidation'=>true,
    'enableClientValidation'=>true,
    'clientOptions'=>array(
        'validateOnSubmit'=>true,
    ),
)); ?>

    <p class="note">这些字段<span class="required">*</span>是必须的。</p>

    <?php echo $form->errorSummary($model); ?>

    <div class="row value">
        <?php echo $form->label($model->Prize_Store, 'store_name'); ?>
        <?php echo CHtml::encode($model->Prize_Store->store_name); ?>
    </div>
    
    <div class="row value">
        <?php echo $form->label($model->Prize_Store, 'phone'); ?>
        <?php echo CHtml::encode($model->Prize_Store->phone); ?>
    </div>
    
    <div class="row value">
        <?php echo $form->label($model->Prize_Pad, 'name'); ?>
        <?php echo CHtml::encode($model->Prize_Pad->name); ?>
    </div>
    
    <div class="row value">
        <?php echo $form->label($model->Prize_Pad, 'number'); ?>
        <?php echo CHtml::encode($model->Prize_Pad->number); ?>
    </div>    
    
    <div class="row value">
        <?php echo $form->label($model, 'position'); ?>
        <?php echo CHtml::encode($model->position); ?>
    </div>
    
    <div class="row value">
        <?php echo $form->labelEx($model, 'name'); ?>
        <?php echo CHtml::encode($model->name); ?>
    </div>
    
    <div class="row value">
        <?php echo $form->label($model, 'number'); ?>
        <?php echo CHtml::encode($model->number); ?>
    </div>
    
    <div class="row">
        <?php echo $form->labelEx($model, 'count'); ?>
        <?php echo $form->textField($model, 'count', array('size'=>11,'maxlength'=>11)); ?>
        <span class="hint">-1 表示不限制 修改后<?php echo $model->getAttributeLabel('number'); ?>，则重新计数</span>
        <?php echo $form->error($model, 'count'); ?>
    </div>
    
    <div class="row">
        <?php echo $form->labelEx($model,'odds'); ?>
        <?php echo $form->textField($model,'odds',array('size'=>11)); ?>
        <span class="hint">-1表示必中 -1~10000的整数 1表示 0.01%</span>
        <?php echo $form->error($model,'odds'); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? '创建' : '保存'); ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- form -->