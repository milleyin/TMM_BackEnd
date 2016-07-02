<?php
/* @var $this Tmm_groupController */
/* @var $model Group */
/* @var $form CActiveForm */

$this->breadcrumbs=array(
    '线路管理页'=>array('/admin/tmm_shops/admin'),
    '线路(结伴游)管理页'=>array('admin'),
    $model->Group_Shops->name=>array('view','id'=>$model->id),
    '线路(结伴游)包装页',
);

?>
<h1>线路(结伴游) 包装<font color='#eb6100'><?php echo CHtml::encode($model->Group_Shops->name); ?></font></h1>
<div class="form wide">
    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'group-pack-form',
        'enableAjaxValidation'=>true,
        'enableClientValidation'=>true,
        'clientOptions'=>array(
            'validateOnSubmit'=>true,
        ),
        'htmlOptions'=>array('enctype'=>'multipart/form-data'),
    )); ?>

    <p class="note">这些字段 <span class="required">*</span>是必须的.</p>

    <?php echo $form->errorSummary($model->Group_Shops); ?>

    <div class="row value">
        <?php echo $form->label($model,'id'); ?>
        <?php echo $model->id; ?>
    </div>
    <div class="row value">
        <?php echo $form->label($model->Group_Shops,'name'); ?>
        <?php echo CHtml::encode($model->Group_Shops->name); ?>
    </div>
    <div class="row value">
        <?php echo $form->label($model->Group_Shops,'brow'); ?>
        <?php echo Chtml::encode($model->Group_Shops->brow);?>
    </div>
    <div class="row value">
        <?php echo $form->label($model->Group_Shops,'share'); ?>
        <?php echo Chtml::encode($model->Group_Shops->share);?>
    </div>
    <div class="row value">
        <?php echo $form->label($model->Group_Shops,'praise'); ?>
        <?php echo Chtml::encode($model->Group_Shops->praise);?>
    </div>
    <div class="row">
        <?php echo $form->labelEx($model->Group_Shops,'list_img'); ?>
        <?php echo $form->fileField($model->Group_Shops,'list_img'); ?>
        <?php echo $form->error($model->Group_Shops,'list_img'); ?>
    </div>
    <?php
    if(file_exists($model->Group_Shops->list_img)){
        echo '<div class="row"><label>'.$model->Group_Shops->getAttributeLabel('list_img').'</label>';
        echo 	$this->show_img($model->Group_Shops->list_img);
        echo '</div>';
    }
    ?>
    <div class="row">
        <?php echo $form->labelEx($model->Group_Shops,'list_info'); ?>
        <?php echo $form->textArea($model->Group_Shops,'list_info',array('style'=>'width:300px;height:80px;')); ?>
        <?php echo $form->error($model->Group_Shops,'list_info'); ?>
    </div>
    <div class="row">
        <?php echo $form->labelEx($model->Group_Shops,'page_img'); ?>
        <?php echo $form->fileField($model->Group_Shops,'page_img'); ?>
        <?php echo $form->error($model->Group_Shops,'page_img'); ?>
    </div>
    <?php
    if(file_exists($model->Group_Shops->page_img)){
        echo '<div class="row"><label>'.$model->Group_Shops->getAttributeLabel('page_img').'</label>';
        echo 	$this->show_img($model->Group_Shops->page_img);
        echo '</div>';
    }
    ?>
    <div class="row">
        <?php echo $form->labelEx($model->Group_Shops,'page_info'); ?>
        <?php echo $form->textArea($model->Group_Shops,'page_info',array('style'=>'width:300px;height:80px;')); ?>
        <?php echo $form->error($model->Group_Shops,'page_info'); ?>
    </div>

    <?php
    if(isset($model->Group_Pro))
        $this->renderPartial('_items', array(
            'model'=>$model,
            'form'=>$form,
        ));
    ?>

    <div class="row buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? '创建' : '保存'); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->