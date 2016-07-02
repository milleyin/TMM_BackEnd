<?php
/* @var $this AdController */
/* @var $model Ad */
/* @var $form CActiveForm */
?>

<div class="form wide">

<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'ad-form',
    'focus'=>array($model, 'name'),
    'enableAjaxValidation'=>true,
    'enableClientValidation'=>true,
    'clientOptions'=>array(
        'validateOnSubmit'=>true,
    ),
    'htmlOptions' => array('enctype'=>'multipart/form-data'),
)); ?>

    <p class="note">这些字段<span class="required">*</span>是必须的。</p>

    <?php echo $form->errorSummary(array($model, $model->Ad_Upload)); ?>

    <?php 
        if ($model->getIsNewRecord())
        {
    ?>
    <div class="row">
        <?php echo $form->labelEx($model, 'type'); ?>
        <?php echo $form->dropDownList($model, 'type', $model::$_type); ?>
        <?php echo $form->error($model, 'type'); ?>
    </div>
    <?php 
        }
    ?>
    <div class="row">
        <?php echo $form->labelEx($model, 'name'); ?>
        <?php echo $form->textField($model, 'name', array('size'=>32, 'maxlength'=>32)); ?>
        <?php echo $form->error($model, 'name'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model->Ad_Upload, 'path'); ?>
        <?php echo $form->fileField($model->Ad_Upload, 'path'); ?>
        <span id="image_hint" class="hint"  <?php echo $model->type == Ad::AD_TYPE_IMAGE ? '' : 'style="display: none;"'; ?>>广告图片：仅支持2M以内，jpg, png, jpeg, gif等格式，图片规格：452x278。 </span>
        <span id="video_hint" class="hint"  <?php echo $model->type ==Ad::AD_TYPE_VIDEO ? '' : 'style="display: none;"'; ?>>广告视频：仅支持50M以内，mp4等格式，建议尺寸16：9。 </span>
        <?php echo $form->error($model->Ad_Upload, 'path'); ?>
    </div>
    
    <div class="row">
        <?php echo $form->labelEx($model->Ad_Upload, 'info'); ?>
        <?php echo $form->textField($model->Ad_Upload, 'info', array('size'=>50, 'maxlength'=>100)); ?>
        <?php echo $form->error($model->Ad_Upload, 'info'); ?>
    </div>
    
    <?php 
        if (file_exists($model->Ad_Upload->getAbsolutePath()))
        {
    ?>
        <div class="row <?php echo $model->Ad_Upload->upload_type != Upload::UPLOAD_UPLOAD_TYPE_IMAGE ? 'value' : '';?>">
            <?php echo $form->labelEx($model->Ad_Upload, '_old_path'); ?>
            <?php 
            if ($model->Ad_Upload->upload_type == Upload::UPLOAD_UPLOAD_TYPE_IMAGE)
                  echo CHtml::image($model->Ad_Upload->getUrlPath(), CHtml::encode($model->Ad_Upload->info), array('style'=>'widht:150px;height:150px;')); 
            else 
                  echo CHtml::link(CHtml::encode($model->name), $model->Ad_Upload->getUrlPath(), array('target'=>'_blank'));
            ?>        
        </div>
    <?php 
        }
    ?>
    <div class="row buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? '创建' : '保存'); ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- form -->
    
<?php
 $image = Ad::AD_TYPE_IMAGE;
 $video = Ad::AD_TYPE_VIDEO;
 $id = CHtml::activeId($model, 'type');
$js = <<<"EOD"
jQuery('body').on('change', '#$id', function() {
    var value = jQuery(this).val();
    if (value == $image) {
        jQuery('#image_hint').show(150);
        jQuery('#video_hint').hide(150);
    } else if (value == $video) {
        jQuery('#video_hint').show(150);
        jQuery('#image_hint').hide(150);
    }
    return false;
});

EOD;

Yii::app()->clientScript->registerScript('ad_form', $js);
?>