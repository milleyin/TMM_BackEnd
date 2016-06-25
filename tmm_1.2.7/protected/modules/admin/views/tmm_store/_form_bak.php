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
<?php

/*
      $this->widget('ext.ueditor.UeditorWidget',
        array(
            'name'=>'excerpt_editor',
            'id'=>'Post_excerpt',
            'value' => '输入值',
            'config'=>array(
               // 'serverUrl' => Yii::app()->createUrl('/admin/tmm_store/restore'),//指定serverUrl
                'toolbars'=>array(
                    array('source','link','bold','italic','underline','forecolor','superscript','insertimage','spechars','blockquote')
                ),
                'initialFrameHeight'=>'150',
                'initialFrameWidth'=>'95%'
            ),
            'htmlOptions' => array('rows'=>3,'class'=>'span12 controls')
    ));
 * 
 */
            ?>
	<?php echo $form->errorSummary(array($model,$model->Store_Content)); ?>
<table width="100%" border="0" cellpadding="8" cellspacing="0" class="tableBasic form" >
      <tbody>
      <tr width="80%" style="border:10px solid red;">
        <th align="left" width="15%"><?php echo $form->labelEx($model,'phone'); ?></th>
        <td width="35%"> <?php echo $form->textField($model,'phone'); ?><?php echo $form->error($model,'phone'); ?></td>
        <th align="left" width="15%"><?php echo $form->labelEx($model,'password'); ?></th>        
        <td width="35%"><?php echo $form->passwordField($model,'password'); ?><?php echo $form->error($model,'password'); ?></td>
      </tr>  
  
      <tr>
         <th align="left" width="15%"><?php echo $form->labelEx($model,'count'); ?></th>
        <td width="35%"> <?php echo $form->textField($model,'count'); ?><?php echo $form->error($model,'count'); ?></td>
        <th align="left" width="15%"><?php echo $form->labelEx($model,'phone_type'); ?></th>        
        <td width="35%"><?php echo $form->textField($model,'phone_type'); ?><?php echo $form->error($model,'phone_type'); ?></td>
      </tr>  
      <tr>
         <th align="left" width="15%"><?php echo $form->labelEx($model,'login_token'); ?></th>
        <td width="35%"><?php echo $form->textField($model,'login_token'); ?><?php echo $form->error($model,'login_token'); ?></td>
        <th align="left" width="15%"><?php echo $form->labelEx($model,'login_mac'); ?></th>        
        <td width="35%"><?php echo $form->textField($model,'login_mac'); ?><?php echo $form->error($model,'login_mac'); ?></td>
      </tr> 
      <tr>
         <th align="left" width="15%"><?php echo $form->labelEx($model,'last_mac'); ?></th>
        <td width="35%"><?php echo $form->textField($model,'last_mac'); ?><?php echo $form->error($model,'last_mac'); ?></td>
        <th align="left" width="15%"><?php echo $form->labelEx($model,'login_error'); ?></th>        
        <td width="35%"><?php echo $form->textField($model,'login_error'); ?><?php echo $form->error($model,'login_error'); ?></td>
      </tr>
      <tr>
         <th align="left" width="15%"><?php echo $form->labelEx($model,'login_address'); ?></th>
        <td width="35%"><?php echo $form->textField($model,'login_address'); ?><?php echo $form->error($model,'login_address'); ?></td>
        <th align="left" width="15%"><?php echo $form->labelEx($model,'last_address'); ?></th>        
        <td width="35%"><?php echo $form->textField($model,'last_address'); ?><?php echo $form->error($model,'last_address'); ?></td>
      </tr>
      <!--
      <tr>
         <th align="left" width="15%"><?php echo $form->labelEx($model,'icon'); ?></th>
        <td width="35%"><?php echo $form->fileField($model,'icon'); 
                        if($this->file_exists_uploads($model->icon)){
				echo CHtml::image($model->icon,'icon',array('width'=>'20px','height'=>'20px','id'=>'icon'));
			}?><?php echo $form->error($model,'icon'); ?></td>
        <th align="left" width="15%">
        </th>        
        <td width="35%"></td>
      </tr>
      -->
      <tr>
         <th align="left" width="15%"><?php echo $form->labelEx($model->Store_Content,'name'); ?></th>
        <td width="35%"><?php echo $form->textField($model->Store_Content,'name'); ?><?php echo $form->error($model->Store_Content,'name'); ?></td>
        <th align="left" width="15%"><?php echo $form->labelEx($model->Store_Content,'push'); ?></th>        
        <td width="35%"><?php echo $form->textField($model->Store_Content,'push'); ?><?php echo $form->error($model->Store_Content,'push'); ?></td>
      </tr>
       <tr>
         <th align="left" width="15%"><?php echo $form->labelEx($model->Store_Content,'cash'); ?></th>
        <td width="35%"><?php echo $form->textField($model->Store_Content,'cash'); ?><?php echo $form->error($model->Store_Content,'cash'); ?></td>
        <th align="left" width="15%"><?php echo $form->labelEx($model->Store_Content,'money'); ?></th>        
        <td width="35%"><?php echo $form->textField($model->Store_Content,'money'); ?><?php echo $form->error($model->Store_Content,'money'); ?></td>
      </tr>
      <tr>
         <th align="left" width="15%"><?php echo $form->labelEx($model->Store_Content,'deposit'); ?></th>
        <td width="35%"><?php echo $form->textField($model->Store_Content,'deposit'); ?><?php echo $form->error($model->Store_Content,'deposit'); ?></td>
        <th align="left" width="15%"><?php echo $form->labelEx($model->Store_Content,'address'); ?></th>        
        <td width="35%"><?php echo $form->textField($model->Store_Content,'address'); ?><?php echo $form->error($model->Store_Content,'address'); ?></td>
      </tr>
    
      <tr>
        <th align="left" width="15%">选择区域：</th>
        <td width="35%">
            <?php echo $form->dropDownList($model->Store_Content,'area_id_p',Area::data_array(),array(
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
            <?php echo $form->dropDownList($model->Store_Content,'area_id_m',Area::data_array($model->Store_Content->area_id_p),array(
                            'ajax'=>array(
                                            'type'=>'POST',
                                            'url'=>Yii::app()->createUrl('/admin/tmm_home/area'),
                                            'data'=>array('area_id_m'=>'js:this.value',Yii::app()->request->csrfTokenName=>Yii::app()->request->csrfToken),
                                            'update'=>'#'.CHtml::activeId($model->Store_Content,'area_id_c'),
                            ),		
            )); ?>
            <?php echo $form->error($model->Store_Content,'area_id_m'); ?>
            <?php echo $form->dropDownList($model->Store_Content,'area_id_c',Area::data_array($model->Store_Content->area_id_m)); ?>	
            <?php echo $form->error($model->Store_Content,'area_id_c'); ?>
        </td>
       </tr>
       <tr>
         <th align="left" width="15%"><?php echo $form->labelEx($model->Store_Content,'store_tel'); ?></th>
        <td width="35%"><?php echo $form->textField($model->Store_Content,'store_tel'); ?><?php echo $form->error($model->Store_Content,'store_tel'); ?></td>
        <th align="left" width="15%"><?php echo $form->labelEx($model->Store_Content,'store_postcode'); ?></th>        
        <td width="35%"><?php echo $form->textField($model->Store_Content,'store_postcode'); ?><?php echo $form->error($model->Store_Content,'store_postcode'); ?></td>
      </tr>
      <tr>
         <th align="left" width="15%"><?php echo $form->labelEx($model->Store_Content,'lx_contacts'); ?></th>
        <td width="35%"><?php echo $form->textField($model->Store_Content,'lx_contacts'); ?><?php echo $form->error($model->Store_Content,'lx_contacts'); ?></td>
        <th align="left" width="15%"><?php echo $form->labelEx($model->Store_Content,'identity_before'); ?></th>        
        <td width="35%"><?php echo $form->fileField($model->Store_Content,'identity_before');
                              if($this->file_exists_uploads($model->Store_Content->identity_before)){
                                    echo CHtml::image($model->Store_Content->identity_before,'identity_before',array('width'=>'20px','height'=>'20px','id'=>'identity_before'));
                              } ?><?php echo $form->error($model->Store_Content,'identity_before'); ?></td>
       </tr>
      <tr>
         <th align="left" width="15%"><?php echo $form->labelEx($model->Store_Content,'lx_phone'); ?></th>
        <td width="35%"><?php echo $form->textField($model->Store_Content,'lx_phone'); ?><?php echo $form->error($model->Store_Content,'lx_phone'); ?></td>
        <th align="left" width="15%"><?php echo $form->labelEx($model->Store_Content,'identity_after'); ?></th>
        <td width="35%"><?php echo $form->fileField($model->Store_Content,'identity_after');
                              if($this->file_exists_uploads($model->Store_Content->identity_after)){
                                    echo CHtml::image($model->Store_Content->identity_after,'identity_after',array('width'=>'20px','height'=>'20px','id'=>'identity_after'));
                              }  ?><?php echo $form->error($model->Store_Content,'identity_after'); ?></td>
      </tr>
        
      <tr>
        <th align="left" width="15%"><?php echo $form->labelEx($model->Store_Content,'lx_identity_code'); ?></th>        
        <td width="35%"><?php echo $form->textField($model->Store_Content,'lx_identity_code'); ?><?php echo $form->error($model->Store_Content,'lx_identity_code'); ?></td>
        <th align="left" width="15%"><?php echo $form->labelEx($model->Store_Content,'identity_hand'); ?></th>        
        <td width="35%"><?php echo $form->fileField($model->Store_Content,'identity_hand'); 
                              if($this->file_exists_uploads($model->Store_Content->identity_hand)){
                                    echo CHtml::image($model->Store_Content->identity_hand,'identity_hand',array('width'=>'20px','height'=>'20px','id'=>'identity_hand'));
                              } ?><?php echo $form->error($model->Store_Content,'identity_hand'); ?></td>
      </tr>
      <tr>
         <th align="left" width="15%"><?php echo $form->labelEx($model->Store_Content,'com_contacts'); ?></th>
        <td width="35%"><?php echo $form->textField($model->Store_Content,'com_contacts'); ?><?php echo $form->error($model->Store_Content,'com_contacts'); ?></td>
        <th align="left" width="15%"><?php echo $form->labelEx($model->Store_Content,'bl_img'); ?></th>
        <td width="35%"><?php echo $form->fileField($model->Store_Content,'bl_img'); 
                              if($this->file_exists_uploads($model->Store_Content->bl_img)){
                                    echo CHtml::image($model->Store_Content->bl_img,'bl_img',array('width'=>'20px','height'=>'20px','id'=>'bl_img'));
                              } ?><?php echo $form->error($model->Store_Content,'bl_img'); ?></td>
      </tr>
      <tr>
         <th align="left" width="15%"><?php echo $form->labelEx($model->Store_Content,'com_identity'); ?></th>
        <td width="35%"><?php echo $form->textField($model->Store_Content,'com_identity'); ?><?php echo $form->error($model->Store_Content,'com_identity'); ?></td>
        <th align="left" width="15%"><?php echo $form->labelEx($model->Store_Content,'bl_code'); ?></th>        
        <td width="35%"><?php echo $form->textField($model->Store_Content,'bl_code'); ?><?php echo $form->error($model->Store_Content,'bl_code'); ?></td>
      </tr>
      <tr>
          <th align="left" width="15%"><?php echo $form->labelEx($model->Store_Content,'com_phone'); ?></th>        
        <td width="35%"><?php echo $form->textField($model->Store_Content,'com_phone'); ?><?php echo $form->error($model->Store_Content,'com_phone'); ?></td>
         
        <th align="left" width="15%"><?php echo $form->labelEx($model->Store_Content,'bank_id'); ?></th>        
        <td width="35%"><?php echo $form->dropDownList($model->Store_Content,'bank_id',Bank::data()); ?><?php echo $form->error($model->Store_Content,'bank_id'); ?></td>
      </tr>
      <tr>
         <th align="left" width="15%"><?php echo $form->labelEx($model->Store_Content,'bank_name'); ?></th>
        <td width="35%"><?php echo $form->textField($model->Store_Content,'bank_name'); ?><?php echo $form->error($model->Store_Content,'bank_name'); ?></td>
        <th align="left" width="15%"><?php echo $form->labelEx($model->Store_Content,'bank_branch'); ?></th>        
        <td width="35%"><?php echo $form->textField($model->Store_Content,'bank_branch'); ?><?php echo $form->error($model->Store_Content,'bank_branch'); ?></td>
      </tr>
      <tr>
         <th align="left" width="15%"><?php echo $form->labelEx($model->Store_Content,'bank_code'); ?></th>
        <td width="35%"><?php echo $form->textField($model->Store_Content,'bank_code'); ?><?php echo $form->error($model->Store_Content,'bank_code'); ?></td>
        <th align="left" width="15%"><?php echo $form->labelEx($model->Store_Content,'son_limit'); ?></th>        
        <td width="35%"><?php echo $form->textField($model->Store_Content,'son_limit');?><?php echo $form->error($model->Store_Content,'son_limit'); ?></td>
      </tr>
      <tr>
         <th align="left" width="15%"><?php echo $form->labelEx($model->Store_Content,'audit'); ?></th>
        <td width="35%"><?php echo $form->dropDownList($model->Store_Content,'audit',$model::$_status); ?><?php echo $form->error($model->Store_Content,'audit'); ?></td>
        <th align="left" width="15%"><?php echo $form->labelEx($model->Store_Content,'pass_time'); ?></th>        
        <td width="35%"><?php echo $form->textField($model->Store_Content,'pass_time');?><?php echo $form->error($model->Store_Content,'pass_time'); ?></td>
      </tr>
      </tbody>
 </table>
       


	<div class="row buttons">
		<?php echo CHtml::submitButton($model->Store_Content->isNewRecord ? '创建' : '保存'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->