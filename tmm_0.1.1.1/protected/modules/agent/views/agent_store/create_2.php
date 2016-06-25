<div class="content_box">
<?php
	echo $this->breadcrumbs(array(
				'商家账号管理'=>array('admin'),
				'新增商家',
				$model->phone,
			));
?>
  <div class="create_nav create_business_nav">
      <div class="create_steap_one">
        <a class="done">1</a>
        <span class="text_done">账号信息</span>
        <div class="line line_first done"></div>
      </div>
      <div class="create_steap_two">
        <a class="done">2</a>
        <span class="text_done">公司信息</span>
        <div class="line done"></div>
      </div>
      <div class="create_steap_three">
        <a class="undone">3</a>
        <span class="text_undone">证照上传</span>
        <div class="line undone"></div>
      </div>
      <div class="create_steap_four">
        <a class="undone">4</a>
        <span class="text_undone">添加标签</span>
        <div class="line undone"></div>
      </div>
      <div class="create_steap_five">
        <a class="undone">5</a>
        <span class="text_undone">注册完成</span>
        <div class="line line_last undone"></div>
      </div>
  </div>    <!-- .create_nav -->
  <div class="content create_business_content">
    <div class="content_two">
   <?php
		$form = $this->beginWidget('CActiveForm', array(
		    'id'=>'store-user-form',
			'enableAjaxValidation'=>true,
		    'enableClientValidation'=>true,
		    'focus'=>array($model,'name'),
		    'clientOptions'=>array(
		        'validateOnSubmit'=>true
		    ),
			'htmlOptions'=>array('class'=>'business_info_form'),
		));
	?>		
        <div class="row content_bname">
             <?php echo $form->label($model->Store_Content,'name'); ?>
			<?php echo $form->textField($model->Store_Content,'name',array('class'=>'bname')); ?>
			<?php echo $form->error($model->Store_Content,'name'); ?>      	
        </div>
        <div class="row content_baddress">
        <?php echo $form->label($model->Store_Content,'address'); ?>   
		<?php echo $form->dropDownList($model->Store_Content,'area_id_p',Area::data_array_id(0,array(''=>'省')),array(
				'class'=>'province',
				'ajax'=>array(
				'type'=>'POST',
				'url'=>Yii::app()->createUrl('/agent/agent_home/area'),
				'dataType'=>'json',
				'data'=>array('area_id_p'=>'js:this.value',Yii::app()->request->csrfTokenName=>Yii::app()->request->csrfToken),
				'success'=>'function(data){
									jQuery("#'.CHtml::activeId($model->Store_Content,'area_id_m').'").html(data[0]);
									jQuery("#'.CHtml::activeId($model->Store_Content,'area_id_c').'").html(data[1]);
							}',
			),
		));
		?>
		<?php 
			echo $form->dropDownList($model->Store_Content,'area_id_m',Area::data_array_id($model->Store_Content->area_id_p,array(''=>'市')),array(
			'class'=>'city',
			'ajax'=>array(
				'type'=>'POST',
				'url'=>Yii::app()->createUrl('/agent/agent_home/area'),
				'data'=>array('area_id_m'=>'js:this.value',Yii::app()->request->csrfTokenName=>Yii::app()->request->csrfToken),
				'update'=>'#'.CHtml::activeId($model->Store_Content,'area_id_c'),
			),
		)); ?>
		<?php echo $form->dropDownList($model->Store_Content,'area_id_c',Area::data_array_id($model->Store_Content->area_id_m,array(''=>'区/县')),array(
			'class'=>'area',
		)); ?>
		
		<?php echo $form->textField($model->Store_Content,'address',array('maxlength'=>40,'class'=>'address')); ?>
		
		<?php echo $form->error($model->Store_Content,'area_id_p'); ?>
		<?php echo $form->error($model->Store_Content,'area_id_m'); ?>
        <?php echo $form->error($model->Store_Content,'area_id_c'); ?>
		<?php echo $form->error($model->Store_Content,'address'); ?>
        </div>
        <div class="row content_bphone" >
        	<?php echo $form->label($model->Store_Content,'store_tel'); ?>
			<?php echo $form->textField($model->Store_Content,'store_tel',array('maxlength'=>15,'class'=>'bphone')); ?>
			<?php echo $form->error($model->Store_Content,'store_tel'); ?>
        </div>
        <div class="row content_bemail" >
			<?php echo $form->label($model->Store_Content,'store_postcode'); ?>
			<?php echo $form->textField($model->Store_Content,'store_postcode',array('class'=>'bemail')); ?>
			<?php echo $form->error($model->Store_Content,'store_postcode'); ?>
        </div>
        <div class="row" >
        <?php echo $form->label($model->Store_Content,'bl_code'); ?>
		<?php echo $form->textField($model->Store_Content,'bl_code',array('maxlength'=>30)); ?>
		<?php echo $form->error($model->Store_Content,'bl_code'); ?>
        </div>
        <div class="row" >
       	<?php echo $form->label($model->Store_Content,'com_contacts'); ?>
		<?php echo $form->textField($model->Store_Content,'com_contacts',array('maxlength'=>15)); ?>
		<?php echo $form->error($model->Store_Content,'com_contacts'); ?>
        </div>
        <div class="row" >
        <?php echo $form->label($model->Store_Content,'com_identity'); ?>
		<?php echo $form->textField($model->Store_Content,'com_identity',array('maxlength'=>18)); ?>
		<?php echo $form->error($model->Store_Content,'com_identity'); ?>
        </div>
        <div class="row" >
        <?php echo $form->label($model->Store_Content,'com_phone'); ?>
		<?php echo $form->textField($model->Store_Content,'com_phone',array('maxlength'=>15)); ?>
		<?php echo $form->error($model->Store_Content,'com_phone'); ?>
        </div>
        <div class="row" >
        <?php echo $form->label($model->Store_Content,'lx_contacts'); ?>
		<?php echo $form->textField($model->Store_Content,'lx_contacts',array('maxlength'=>10)); ?>
		<?php echo $form->error($model->Store_Content,'lx_contacts'); ?>
        </div>
        <div class="row" >
        <?php echo $form->label($model->Store_Content,'lx_identity_code'); ?>
		<?php echo $form->textField($model->Store_Content,'lx_identity_code',array('maxlength'=>18)); ?>
		<?php echo $form->error($model->Store_Content,'lx_identity_code'); ?>
        </div>
        <div class="row" >
        <?php echo $form->label($model->Store_Content,'lx_phone'); ?>
		<?php echo $form->textField($model->Store_Content,'lx_phone',array('maxlength'=>15)); ?>
		<?php echo $form->error($model->Store_Content,'lx_phone'); ?>
        </div>        
        <div class="row enter">
       	 <?php echo CHtml::link('返回',array('/agent/agent_store/create','id'=>$model->id))?>
       	 <?php echo CHtml::submitButton('下一步'); ?>
        </div>
	<?php $this->endWidget(); ?>
      
    </div>  <!-- .content_two -->
  </div>   <!--  .content -->
  
  <div class="copyright">
    <span>Copyright &copy; TMM365.com All Rights Reserved</span>
  </div>  <!--.copyright--> 
</div>  <!--.content_box-->
