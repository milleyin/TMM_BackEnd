<?php
/* @var $this PrizeController */
/* @var $model Prize */
/* @var $form CActiveForm */
?>

<div class="form wide">

<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'prize-form',
    'focus'=>array($model, 'name'),
    'enableAjaxValidation'=>true,
    'enableClientValidation'=>true,
    'clientOptions'=>array(
        'validateOnSubmit'=>true,
    ),
    'htmlOptions' => array('enctype'=>'multipart/form-data'),
)); ?>

    <p class="note">这些字段<span class="required">*</span>是必须的。</p>

    <?php echo $form->errorSummary(array($model, $model->Prize_Upload)); ?>

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
        <strong style="color:red">    <?php echo CHtml::encode($model->position); ?>    </strong>
        <span class="hint" >转盘图片位置：<strong  style="color:red"><?php echo (45*($model->position-1)) . '°' , ' ~ ' , (45*$model->position) . '°' ; ?></strong></span>
    </div>
    
    <div class="row">
        <?php echo $form->labelEx($model, 'name'); ?>
        <?php echo $form->textField($model, 'name',array('size'=>32, 'maxlength'=>32)); ?>
        <?php echo $form->error($model, 'name'); ?>
    </div>
    
    <div class="row">
        <?php echo $form->labelEx($model->Prize_Upload, 'path'); ?>
        <?php echo $form->fileField($model->Prize_Upload, 'path'); ?>
         <span class="hint" >仅支持2M以内的jpg, png, jpeg, gif等，奖品图片规格：168*78</span>
        <?php echo $form->error($model->Prize_Upload, 'path'); ?>
    </div>
    
     <div class="row">
        <?php echo $form->labelEx($model->Prize_Upload, 'info'); ?>
        <?php echo $form->textField($model->Prize_Upload, 'info', array('size'=>50, 'maxlength'=>100, 'placeholder'=>'奖品图片')); ?>
        <?php echo $form->error($model->Prize_Upload, 'info'); ?>
    </div>

    <?php
    if (!empty($model->Prize_Upload) && file_exists($model->Prize_Upload->getAbsolutePath()))
    {
        ?>
        <div class="row">
            <?php echo $form->labelEx($model->Prize_Upload, '_old_path'); ?>
            <?php
                echo CHtml::image($model->Prize_Upload->getUrlPath(), CHtml::encode($model->Prize_Upload->info), array('style'=>'widht:150px;height:150px;'));
            ?>
        </div>
        <?php
    }
    ?>

    <div class="row value">
        <?php echo $form->label($model, 'number'); ?>
        <?php echo $model->number; ?>
    </div>
    
    <div class="row">
        <?php echo $form->labelEx($model, 'count'); ?>
        <?php echo $form->textField($model, 'count', array('size'=>11,'maxlength'=>11)); ?>
        <span class="hint">-1 表示不限制 修改后<?php echo $model->getAttributeLabel('number'); ?>，则重新计数</span>
        <?php echo $form->error($model, 'count'); ?>
    </div>
    
    <div class="row">
        <?php echo $form->labelEx($model,'odds'); ?>
        <?php echo $form->textField($model,'odds',array('size'=>11,'maxlength'=>11, 'min'=>0,'max'=>10000)); ?>
        <span class="hint">-1表示必中（多个则按顺序） -1~10000的整数 1表示 0.01%</span>
        <?php echo $form->error($model,'odds'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'info'); ?>
        <?php echo $form->textArea($model, 'info',array('style'=>'width:400px;height:100px;', 'maxlength'=>128)); ?>
        <?php echo $form->error($model, 'info'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'receive_type'); ?>
        <?php echo $form->dropDownList($model, 'receive_type', $model::$_receive_type); ?>
        <?php echo $form->error($model, 'receive_type'); ?>
    </div>

    <div class="row" id="urlRow">
        <?php echo $form->labelEx($model,'url'); ?>
        <?php echo $form->textField($model,'url',array('size'=>60,'maxlength'=>128)); ?>
        <?php echo $form->error($model,'url'); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? '创建' : '保存'); ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- form -->

<?php
$value = $model::PRIZE_RECEIVE_TYPE_YZ;
$id = CHtml::activeId($model, 'receive_type');
$old_value = $model->receive_type;
$name = $model->getAttributeLabel('url');
$js = <<<"EOD"
jQuery("#urlRow label").html('$name<span class="required">*</span>');
function setUrlRow(value) {
     if (value == $value) {
         jQuery('#urlRow').addClass('required');
         jQuery('#urlRow').show(150);
    } else {
        jQuery('#urlRow').removeClass('required');
        jQuery('#urlRow').hide(150);
    }
}
setUrlRow($old_value);
jQuery('body').on('change', '#$id', function() {
    setUrlRow(jQuery(this).val());
    return false;
});

EOD;
    Yii::app()->clientScript->registerScript('prize', $js);
?>