<?php
/* @var $this Tmm_storeController */
/* @var $model StoreUser */
/* @var $form CActiveForm */
?>

<div class="form wide">

	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'store-user-form',
		'enableAjaxValidation'=>true,
		'enableClientValidation'=>true,
		'clientOptions'=>array(
			'validateOnSubmit'=>true,
		),
		'htmlOptions'=>array('enctype'=>'multipart/form-data'),
	)); ?>

	<p class="note">这些字段 <span class="required">*</span>是必须的.</p>

	<?php echo $form->errorSummary(array($model,$model->Store_Content)); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'phone'); ?>
		<?php echo $form->textField($model,'phone',array('size'=>15,'maxlength'=>15)); ?>
		<?php echo $form->error($model,'phone'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'_pwd'); ?>
		<?php echo $form->passwordField($model,'_pwd',array('size'=>18,'maxlength'=>18)); ?>
		<?php echo $form->error($model,'_pwd'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'confirm_pwd'); ?>
		<?php echo $form->passwordField($model,'confirm_pwd',array('size'=>18,'maxlength'=>18)); ?>
		<?php echo $form->error($model,'confirm_pwd'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model->Store_Content,'name'); ?>
		<?php echo $form->textField($model->Store_Content,'name',array('size'=>30,'maxlength'=>40)); ?>
		<?php echo $form->error($model->Store_Content,'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model->Store_Content,'area_id_p'); ?>
		<?php echo $form->dropDownList($model->Store_Content,'area_id_p',Area::data_array_id(),array(
			'ajax'=>array(
				'type'=>'POST',
				'url'=>Yii::app()->createUrl('/admin/tmm_home/area'),
				'dataType'=>'json',
				'data'=>array('area_id_p'=>'js:this.value',Yii::app()->request->csrfTokenName=>Yii::app()->request->csrfToken),
				//	'update'=>'#'.CHtml::activeId($model,'area_id_m'),
				'success'=>'function(data){
									jQuery("#'.CHtml::activeId($model->Store_Content,'area_id_m').'").html(data[0]);
									jQuery("#'.CHtml::activeId($model->Store_Content,'area_id_c').'").html(data[1]);
							}',
			),
		));
		?>
		<?php echo $form->error($model->Store_Content,'area_id_p'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model->Store_Content,'area_id_m'); ?>
		<?php echo $form->dropDownList($model->Store_Content,'area_id_m',Area::data_array_id($model->Store_Content->area_id_p),array(
			'ajax'=>array(
				'type'=>'POST',
				'url'=>Yii::app()->createUrl('/admin/tmm_home/area'),
				'data'=>array('area_id_m'=>'js:this.value',Yii::app()->request->csrfTokenName=>Yii::app()->request->csrfToken),
				'update'=>'#'.CHtml::activeId($model->Store_Content,'area_id_c'),
			),
		)); ?>
		<?php echo $form->error($model->Store_Content,'area_id_m'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model->Store_Content,'area_id_c'); ?>
		<?php echo $form->dropDownList($model->Store_Content,'area_id_c',Area::data_array_id($model->Store_Content->area_id_m)); ?>
		<?php echo $form->error($model->Store_Content,'area_id_c'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model->Store_Content,'address'); ?>
		<?php echo $form->textField($model->Store_Content,'address',array('size'=>40,'maxlength'=>50)); ?>
		<?php echo $form->error($model->Store_Content,'address'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model->Store_Content,'store_tel'); ?>
		<?php echo $form->textField($model->Store_Content,'store_tel',array('size'=>15,'maxlength'=>15)); ?>
		<?php echo $form->error($model->Store_Content,'store_tel'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model->Store_Content,'store_postcode'); ?>
		<?php echo $form->textField($model->Store_Content,'store_postcode'); ?>
		<?php echo $form->error($model->Store_Content,'store_postcode'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model->Store_Content,'lx_contacts'); ?>
		<?php echo $form->textField($model->Store_Content,'lx_contacts',array('size'=>15,'maxlength'=>15)); ?>
		<?php echo $form->error($model->Store_Content,'lx_contacts'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model->Store_Content,'lx_identity_code'); ?>
		<?php echo $form->textField($model->Store_Content,'lx_identity_code',array('size'=>20,'maxlength'=>18)); ?>
		<?php echo $form->error($model->Store_Content,'lx_identity_code'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model->Store_Content,'lx_phone'); ?>
		<?php echo $form->textField($model->Store_Content,'lx_phone',array('size'=>15,'maxlength'=>15)); ?>
		<?php echo $form->error($model->Store_Content,'lx_phone'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model->Store_Content,'identity_before'); ?>
		<?php echo $form->fileField($model->Store_Content,'identity_before'); ?>
		<?php echo $form->error($model->Store_Content,'identity_before'); ?>
	</div>
	<?php
	if($this->file_exists_uploads($model->Store_Content->identity_before)){
		echo '<div class="row"><label>'.$model->Store_Content->getAttributeLabel('identity_before').'</label>';
		echo $this->show_img($model->Store_Content->identity_before);
		echo '</div>';
	}
	?>
	<div class="row">
		<?php echo $form->labelEx($model->Store_Content,'identity_after'); ?>
		<?php echo $form->fileField($model->Store_Content,'identity_after',array('style'=>'width:300px;')); ?>
		<?php echo $form->error($model->Store_Content,'identity_after'); ?>
	</div>
	<?php
	if($this->file_exists_uploads($model->Store_Content->identity_after)){
		echo '<div class="row"><label>'.$model->Store_Content->getAttributeLabel('identity_after').'</label>';
		echo $this->show_img($model->Store_Content->identity_after);
		echo '</div>';
	}
	?>
	<div class="row">
		<?php echo $form->labelEx($model->Store_Content,'identity_hand'); ?>
		<?php echo $form->fileField($model->Store_Content,'identity_hand',array('style'=>'width:300px;')); ?>
		<?php echo $form->error($model->Store_Content,'identity_hand'); ?>
	</div>
	<?php
	if($this->file_exists_uploads($model->Store_Content->identity_hand)){
		echo '<div class="row"><label>'.$model->Store_Content->getAttributeLabel('identity_hand').'</label>';
		echo $this->show_img($model->Store_Content->identity_hand);
		echo '</div>';
	}
	?>
	<div class="row">
		<?php echo $form->labelEx($model->Store_Content,'com_contacts'); ?>
		<?php echo $form->textField($model->Store_Content,'com_contacts',array('size'=>15,'maxlength'=>15)); ?>
		<?php echo $form->error($model->Store_Content,'com_contacts'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model->Store_Content,'com_phone'); ?>
		<?php echo $form->textField($model->Store_Content,'com_phone',array('size'=>15,'maxlength'=>15)); ?>
		<?php echo $form->error($model->Store_Content,'com_phone'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model->Store_Content,'com_identity'); ?>
		<?php echo $form->textField($model->Store_Content,'com_identity',array('size'=>20,'maxlength'=>18)); ?>
		<?php echo $form->error($model->Store_Content,'com_identity'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model->Store_Content,'bl_code'); ?>
		<?php echo $form->textField($model->Store_Content,'bl_code',array('size'=>30,'maxlength'=>40)); ?>
		<?php echo $form->error($model->Store_Content,'bl_code'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model->Store_Content,'bl_img'); ?>
		<?php echo $form->fileField($model->Store_Content,'bl_img',array('style'=>'width:300px;')); ?>
		<?php echo $form->error($model->Store_Content,'bl_img'); ?>
	</div>
	<?php
	if($this->file_exists_uploads($model->Store_Content->bl_img)){
		echo '<div class="row"><label>'.$model->Store_Content->getAttributeLabel('bl_img').'</label>';
		echo $this->show_img($model->Store_Content->bl_img);
		echo '</div>';
	}
	?>
	<div class="row">
		<?php echo $form->labelEx($model->Store_Content,'son_limit'); ?>
		<?php echo $form->textField($model->Store_Content,'son_limit',array('style'=>'width:100px;')); ?>
		<?php echo $form->error($model->Store_Content,'son_limit'); ?>
	</div>
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->Store_Content->isNewRecord ? '创建' : '保存'); ?>
	</div>

	<?php $this->endWidget(); ?>

</div><!-- form -->