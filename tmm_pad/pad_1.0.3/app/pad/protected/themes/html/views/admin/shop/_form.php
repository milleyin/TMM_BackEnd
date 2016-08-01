<?php
/* @var $this ShopController */
/* @var $model Shop */
/* @var $form CActiveForm */
?>

<div class="form wide">
<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'shop-form',
    'focus'=>array($model, 'name'),
    'enableAjaxValidation'=>true,
    'enableClientValidation'=>true,
    'clientOptions'=>array(
        'validateOnSubmit'=>true,
    ),
    'htmlOptions' => array('enctype'=>'multipart/form-data'),
)); ?>

    <p class="note">这些字段<span class="required">*</span>是必须的。</p>

    <?php echo $form->errorSummary($model); ?>

    <div class="row value">
        <?php echo $form->label($model->Shop_Pad->Pad_Store, 'store_name'); ?>
        <?php echo CHtml::encode($model->Shop_Pad->Pad_Store->store_name); ?>
    </div>
    <div class="row value">
        <?php echo $form->label($model->Shop_Pad->Pad_Store, 'phone'); ?>
        <?php echo CHtml::encode($model->Shop_Pad->Pad_Store->phone); ?>
    </div>
    
    <div class="row value">
        <?php echo $form->label($model->Shop_Pad, 'name'); ?>
        <?php echo CHtml::encode($model->Shop_Pad->name); ?>
    </div>
        <div class="row value">
        <?php echo $form->label($model->Shop_Pad, 'number'); ?>
        <?php echo CHtml::encode($model->Shop_Pad->number); ?>
    </div>
    
    <div class="row">
        <?php echo $form->labelEx($model, 'name'); ?>
        <?php echo $form->textField($model, 'name',array('size'=>32,'maxlength'=>32)); ?>
        <?php echo $form->error($model, 'name'); ?>
    </div>
    
    <div class="row">
        <?php echo $form->labelEx($model, 'sort'); ?>
        <?php echo $form->textField($model, 'sort', array('size'=>10,'maxlength'=>10)); ?>
        <span class="hint">商品排序：默认500，倒序排序（相等更新时间倒序排序）。</span>
        <?php echo $form->error($model, 'sort'); ?>
    </div>
    
    <div class="row">
        <?php echo $form->labelEx($model->Shop_Upload, 'path'); ?>
        <?php echo $form->fileField($model->Shop_Upload, 'path'); ?>
        <span class="hint">商品图片：仅支持2M以内，jpg, png, jpeg, gif等格式，图片规格：315*606。</span>
        <?php echo $form->error($model->Shop_Upload, 'path'); ?>
    </div>
    
    <div class="row">
        <?php echo $form->labelEx($model->Shop_Upload, 'info'); ?>
        <?php echo $form->textField($model->Shop_Upload, 'info', array('size'=>50, 'maxlength'=>100, 'placeholder'=>'商品图片')); ?>
        <?php echo $form->error($model->Shop_Upload, 'info'); ?>
    </div>
    
    <?php 
        if (file_exists($model->Shop_Upload->getAbsolutePath()))
        {
    ?>
        <div class="row">
            <?php echo $form->label($model->Shop_Upload, '_old_path'); ?>
            <?php echo CHtml::image($model->Shop_Upload->getUrlPath(), CHtml::encode($model->Shop_Upload->info), array('style'=>'widht:150px;height:150px;')); ?>        
        </div>
    <?php 
        }
    ?>

    <div class="row buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? '创建' : '保存'); ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- form -->