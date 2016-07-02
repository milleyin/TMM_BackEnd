<div class="content_box">
<?php
	echo $this->breadcrumbs(array(
				'商家账号管理'=>array('admin'),
				'新增商家',
				$model->phone,
			));
?><!--.title-->
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
        <a class="done">3</a>
        <span class="text_done">证照上传</span>
        <div class="line done"></div>
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
    <div class="content_three">
      <div class="explain">
        <div>/*上传图片大小请勿超过5MB</div>
        <div>/*接受图片格式：PNG、JPGE、BMP</div>
      </div>
         <?php
		$form = $this->beginWidget('CActiveForm', array(
		    'id'=>'store-user-form',
		    'enableClientValidation'=>true,
		    'clientOptions'=>array(
		        'validateOnSubmit'=>true
		    ),
			'htmlOptions'=>array('enctype'=>'multipart/form-data'),
		));
	?>
        <div class="card_box_div">
          <div class="bg_box_div">
            <div></div><div class="bg_box_one"></div>
            <div class="bg_box_two"></div><div></div>
          </div>
          <div class="img_box_div">
            <div class="upleft_box">
            	<?php
					if(file_exists($model->Store_Content->bl_img))
						echo $this->show_img($model->Store_Content->bl_img);
					else 
						echo CHtml::image(Yii::app()->request->baseUrl.'/css/agent/images/default_img.png');
				?>
              <span>营业执照扫描件</span>
            </div>
            <div class="upright_box">
            	<?php
					if(file_exists($model->Store_Content->identity_hand))
						echo $this->show_img($model->Store_Content->identity_hand);
					else 
						echo CHtml::image(Yii::app()->request->baseUrl.'/css/agent/images/default_img.png');
				?>              
				<span>负责人手持身份证</span>
            </div>
          </div>
          <div class="tag_form">
            <div class="row-fluid upleft">
             <?php echo $form->labelEx($model->Store_Content,'bl_img'); ?>
             <?php echo $form->fileField($model->Store_Content,'bl_img'); ?>
             <?php echo $form->error($model->Store_Content,'bl_img'); ?>
            </div>
            <div class="row-fluid upright">
           <?php echo $form->labelEx($model->Store_Content,'identity_hand'); ?>
             <?php echo $form->fileField($model->Store_Content,'identity_hand'); ?>
             <?php echo $form->error($model->Store_Content,'identity_hand'); ?>
            </div>
            <div class="row-fluid downleft">
           <?php echo $form->labelEx($model->Store_Content,'identity_before'); ?>
             <?php echo $form->fileField($model->Store_Content,'identity_before'); ?>
             <?php echo $form->error($model->Store_Content,'identity_before'); ?>
            </div>
            <div class="row-fluid downright">
           <?php echo $form->labelEx($model->Store_Content,'identity_after'); ?>
             <?php echo $form->fileField($model->Store_Content,'identity_after'); ?>
             <?php echo $form->error($model->Store_Content,'identity_after'); ?>
            </div>  
          </div>
          <div class="img_box_div two">
            <div class="downleft_box">
            	<?php
					if(file_exists($model->Store_Content->identity_before))
						echo $this->show_img($model->Store_Content->identity_before);
					else 
						echo CHtml::image(Yii::app()->request->baseUrl.'/css/agent/images/default_img.png');
				?>     
            <span>负责人身份证正面</span>
            </div>
            <div class="downright_box">
            	<?php
					if(file_exists($model->Store_Content->identity_after))
						echo $this->show_img($model->Store_Content->identity_after);
					else 
						echo CHtml::image(Yii::app()->request->baseUrl.'/css/agent/images/default_img.png');
				?>     
            <span>负责人身份证反面</span>
            </div>
          </div>
        </div>  <!-- .box_div -->
        <div class="row enter">
        	 <?php echo CHtml::link('返回',array('/agent/agent_store/create_2','id'=>$model->id))?>
            <?php echo CHtml::submitButton('下一步'); ?>        
        </div>
      	<?php $this->endWidget(); ?>
    </div>   <!-- .content_three -->
  </div>   <!--  .content -->
  
  <div class="copyright">
    <span>Copyright &copy; TMM365.com All Rights Reserved</span>
  </div>  <!--.copyright--> 
</div>  <!--.content_box-->
