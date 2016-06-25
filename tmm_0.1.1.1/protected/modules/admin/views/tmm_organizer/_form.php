<?php
/* @var $this Tmm_organizerController */
/* @var $model Organizer */
/* @var $form CActiveForm */
?>
<div class="form wide">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'organizer-form',
	'enableAjaxValidation'=>true,
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
	'htmlOptions' => array('enctype'=>'multipart/form-data'),
)); ?>
	<p class="note">这些字段 <span class="required">*</span>是必须的.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'firm_name'); ?>
		<?php echo $form->textField($model,'firm_name',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'firm_name'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'firm_phone'); ?>
		<?php echo $form->textField($model,'firm_phone',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'firm_phone'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'firm_postcode'); ?>
		<?php echo $form->textField($model,'firm_postcode',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'firm_postcode'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'area_id_p'); ?>
		<?php echo $form->dropDownList($model,'area_id_p',Area::data_array_id(),array(
				'ajax'=>array(
					'type'=>'POST',
					'url'=>Yii::app()->createUrl('/admin/tmm_home/area'),
					'dataType'=>'json',
					'data'=>array('area_id_p'=>'js:this.value',Yii::app()->request->csrfTokenName=>Yii::app()->request->csrfToken),
                //	'update'=>'#'.CHtml::activeId($model,'area_id_m'),
					'success'=>'function(data){
							jQuery("#'.CHtml::activeId($model,'area_id_m').'").html(data[0]);
							jQuery("#'.CHtml::activeId($model,'area_id_c').'").html(data[1]);
					}',
           		 ),
			));
		?>
		<?php echo $form->error($model,'area_id_p'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'area_id_m'); ?>
		<?php echo $form->dropDownList($model,'area_id_m',Area::data_array_id($model->area_id_p),array(
				'ajax'=>array(
						'type'=>'POST',
						'url'=>Yii::app()->createUrl('/admin/tmm_home/area'),
						'data'=>array('area_id_m'=>'js:this.value',Yii::app()->request->csrfTokenName=>Yii::app()->request->csrfToken),
						'update'=>'#'.CHtml::activeId($model,'area_id_c'),
				),		
		)); ?>
		<?php echo $form->error($model,'area_id_m'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'area_id_c'); ?>
		<?php echo $form->dropDownList($model,'area_id_c',Area::data_array_id($model->area_id_m)); ?>	
		<?php echo $form->error($model,'area_id_c'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'address'); ?>
		<?php echo $form->textField($model,'address',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'address'); ?>
	</div>	
	<div class="row">
		<?php echo $form->labelEx($model,'law_name'); ?>
		<?php echo $form->textField($model,'law_name',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'law_name'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'law_identity'); ?>
		<?php echo $form->textField($model,'law_identity',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'law_identity'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'law_phone'); ?>
		<?php echo $form->textField($model,'law_phone',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'law_phone'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'bl_code'); ?>
		<?php echo $form->textField($model,'bl_code',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'bl_code'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'bl_img'); ?>
		<?php echo $form->fileField($model,'bl_img',array('style'=>'width:300px;')); ?>
		<?php echo $form->error($model,'bl_img'); ?>
	</div>
		<?php 
		if(file_exists($model->bl_img)){
			echo '<div class="row"><label>'.$model->getAttributeLabel('bl_img').'</label>';
			echo $this->show_img($model->bl_img);
			echo '</div>';
		}
	?>
	<div class="row">
		<?php echo $form->labelEx($model,'manage_name'); ?>
		<?php echo $form->textField($model,'manage_name',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'manage_name'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'manage_phone'); ?>
		<?php echo $form->textField($model,'manage_phone',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'manage_phone'); ?>
	</div>
		<div class="row">
		<?php echo $form->labelEx($model,'manage_identity'); ?>
		<?php echo $form->textField($model,'manage_identity',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'manage_identity'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'identity_begin'); ?>
		<?php echo $form->fileField($model,'identity_begin',array('style'=>'width:300px;')); ?>	
		<?php echo $form->error($model,'identity_begin'); ?>
	</div>
	<?php 
		if(file_exists($model->identity_begin)){
			echo '<div class="row"><label>'.$model->getAttributeLabel('identity_begin').'</label>';
			echo $this->show_img($model->identity_begin);
			echo '</div>';
		}
	?>
	<div class="row">
		<?php echo $form->labelEx($model,'identity_after'); ?>
		<?php echo $form->fileField($model,'identity_after',array('style'=>'width:300px;')); ?>
		<?php echo $form->error($model,'identity_after'); ?>
	</div>
	<?php 
		if(file_exists($model->identity_after)){
			echo '<div class="row"><label>'.$model->getAttributeLabel('identity_after').'</label>';
			echo $this->show_img($model->identity_after);
			echo '</div>';
		}
	?>
	<div class="row">
		<?php echo $form->labelEx($model,'identity_hand'); ?>
		<?php echo $form->fileField($model,'identity_hand',array('style'=>'width:300px;')); ?>
		<?php echo $form->error($model,'identity_hand'); ?>
	</div>
	<?php 
		if(file_exists($model->identity_hand)){
			echo '<div class="row"><label>'.$model->getAttributeLabel('identity_hand').'</label>';
			echo $this->show_img($model->identity_hand);
			echo '</div>';
		}
	?>
	<div class="row">
		<?php echo $form->labelEx($model,'identity_job'); ?>
		<?php echo $form->fileField($model,'identity_job',array('style'=>'width:300px;')); ?>
		<?php echo $form->error($model,'identity_job'); ?>
	</div>
	<?php 
		if(file_exists($model->identity_job)){
			echo '<div class="row"><label>'.$model->getAttributeLabel('identity_job').'</label>';
			echo $this->show_img($model->identity_job);
			echo '</div>';
		}
	?>
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? '创建' : '保存'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->