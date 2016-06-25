<div class="content_box" id="page_project_create">
    <?php
    echo $this->breadcrumbs(array(
        '项目'=>array('admin'),
        '项目创建',
    ));
    ?>  <!--.title-->
  <div class="create_nav modify_project_info">
      <div class="create_steap_one four_steaps_width">
        <a class="done">1</a>
        <span class="text_done">选择归属商家</span>
        <div class="line line_first done"></div>
      </div>
      <div class="create_steap_two four_steaps_width">
        <a class="undone">2</a>
        <span class="text_undone">填写项目信息</span>
        <div class="line undone"></div>
      </div>
      <div class="create_steap_three four_steaps_width">
        <a class="undone">3</a>
        <span class="text_undone">选择标签</span>
        <div class="line undone"></div>
      </div>
      <div class="create_steap_five four_steaps_width">
        <a class="undone">4</a>
        <span class="text_undone">提交审核</span>
        <div class="line line_last undone"></div>
      </div>
  </div>    <!-- .create_nav -->
  <div class="content">
       <?php
            $form = $this->beginWidget('CActiveForm', array(
                'id'=>'items-form',
                'enableClientValidation'=>true,
                'clientOptions'=>array(
                    'validateOnSubmit'=>true
                ),
            ));
         ?>
      <div class="content_one">
        <div class="box_div">
        <div class="choose">
          <label>选择归属商家：</label>
             <?php echo $form->dropDownList($model,'id',StoreContent::data(true,array())); ?>
        </div>
        <div class="box">
            <div class="choose_business_table" id="store_info">
        
              </div>
            </div>
         </div>   <!-- .box_div -->
          <div class="row enter">
     			<?php echo CHtml::submitButton('下一步'); ?>
          </div>
      </div>  <!-- .content_one -->
    <?php $this->endWidget(); ?>
  </div>   <!--  .content -->
  
  <div class="copyright">
    <span>Copyright &copy; TMM365.com All Rights Reserved</span>
  </div>  <!--.copyright--> 
  
</div>  <!--.content_box-->
  <?php  
  if(Yii::app()->request->enableCsrfValidation)
  {
  	$csrfTokenName = Yii::app()->request->csrfTokenName;
  	$csrfToken = Yii::app()->request->csrfToken;
  	$csrf = "{'id':this.value,'$csrfTokenName':'$csrfToken'}";
  }else
  	$csrf = "{'id':this.value}"; 
  ?>
<script type="text/javascript">
    /*<![CDATA[*/
    jQuery(function($){
        jQuery('body').on('change','#<?php echo CHtml::activeId($model, 'id')?>',function(){
            jQuery.ajax({
                'type':'POST',
                'url':'<?php echo Yii::app()->createUrl('/agent/agent_items/info'); ?>',
                'data':<?php echo $csrf;?>,
                 'success':function(data){
           				jQuery("#store_info").html(data);
        		},
        		'cache':false
        	});
        	return false;
        });
        jQuery('body #<?php echo CHtml::activeId($model, 'id')?>').trigger('change');
    });
    /*]]>*/
</script>