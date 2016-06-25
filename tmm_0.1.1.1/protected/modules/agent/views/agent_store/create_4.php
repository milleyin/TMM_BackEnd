<div class="content_box">
<?php
	echo $this->breadcrumbs(array(
				'商家账号管理'=>array('admin'),
				'新增商家',
				$model->phone,
			));
?>
  <!--.title-->
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
        <a class="done">4</a>
        <span class="text_done">添加标签</span>
        <div class="line done"></div>
      </div>
      <div class="create_steap_five">
        <a class="undone">5</a>
        <span class="text_undone">注册完成</span>
        <div class="line line_last undone"></div>
      </div>
  </div>    <!-- .create_nav -->
  <div class="content create_business_content">
    <div class="content_four">
      <div class="box_div">
        <div class="box box_one"> 
       
            <img src="<?php echo Yii::app()->request->baseUrl;?>/css/agent/images/business_logo.png" class="head_img">
            <div class="right">
              <div class="business_name">
                <span class="big"><?php echo CHtml::encode($model->Store_Content->name);?></span>
                <span class="small">
                <?php 
                		echo CHtml::encode(
			                	$model->Store_Content->Content_area_id_p_Area_id->name.
			             		$model->Store_Content->Content_area_id_m_Area_id->name.
			             		$model->Store_Content->Content_area_id_c_Area_id->name.
			             		$model->Store_Content->address
						);
					?>
						</span>
              </div>
              <div class="already_tags">        	
                     <?php foreach($model->Store_TagsElement as $v) { ?>
						<div class="tag_img selected" title="点击移除" style="cursor:pointer;">
							<?php echo CHtml::image(Yii::app()->request->baseUrl.'/css/agent/images/tag_bg.png');?>
							<span><?php echo CHtml::encode($v->TagsElement_Tags->name); ?></span>
							<span class="tags_id" style="display:none"><?php echo CHtml::encode($v->tags_id);?></span>
						</div>
					<?php
						} 
					?>    
              </div>
            </div> <!-- .right -->
        </div> <!--  .box -->
        </div>

       <div class="box_div">
        <div class="title"><span>选择标签</span></div>
          <div class="box box_two">     
          <?php 
	          $this->widget('zii.widgets.CListView', array(
	          		'id'=>'items_list',
	          		'dataProvider'=>$tags_model,
	          		'itemView'=>'_tag',
	          		'template'=>"{items}\n{summary}\n{pager}",
					'viewData'=>array('select_id'=>$model->id),
	          		'pager'=>array(
	          				'class'=>'CLinkPager',
	          				'header'=>'',
	          				'cssFile'=>Yii::app()->request->baseUrl.'/css/agent/css/pager.css',
	          		),
	          ));         
          ?>
        </div> <!--  .box -->
      </div>   <!-- .box_div -->
      <?php
		$form = $this->beginWidget('CActiveForm', array(
		    'id'=>'store-user-form',
		    'enableClientValidation'=>true,
		    'clientOptions'=>array(
		        'validateOnSubmit'=>true
		    ),
		));
		echo $form->hiddenField($model,'create_status');
	?>
      <div class="row enter">
       		<?php echo CHtml::link('上一步',array('/agent/agent_store/create_3','id'=>$model->id),array('id'=>'back_card_info'))?>
       		
            <?php echo CHtml::submitButton('注册完成',array('id'=>'add_tag')); ?>      
      </div>
      <?php $this->endWidget(); ?>
    </div>  <!-- .content_four -->   
  </div>   <!--  .content -->
  <?php 
  if(Yii::app()->request->enableCsrfValidation)
  {
  	$csrfTokenName = Yii::app()->request->csrfTokenName;
  	$csrfToken = Yii::app()->request->csrfToken;
  	$csrf = "\n\t\tdata:{'$csrfTokenName':'$csrfToken','tag_ids':tag_ids},\n";
  }else
  	$csrf = "\n\t\tdata:{'tag_ids':tag_ids},\n";
  $url=Yii::app()->createUrl('/agent/agent_store/tags',array('id'=>$model->id));
  
   		Yii::app()->clientScript->registerScript('search', "
jQuery('body').on('click','.optional',function(){
	var tag_ids=jQuery(this).find('.tags_id').html();
	this_click=jQuery(this);
	html=this_click.html();
	jQuery.ajax({
		'cache':true,
		'type':'POST',$csrf 		'url':'$url&type=yes',
		'success':function(data){
			if(data !=1)
				alert(data);
			else{
				jQuery('.already_tags').append('<div class=\"tag_img selected\" style=\"cursor:pointer;\" style=\"点击移除\">'+html+'</div>');
				this_click.removeClass('optional');  
        	 	this_click.addClass('selected');  
        	 	this_click.attr('title','已选择');
        	 	this_click.find('img').attr('src','".Yii::app()->request->baseUrl.'/css/agent/images/tag_bg_already.png'."');
			}
		},
	});
	return false;
});     
jQuery('body').on('click','.already_tags .selected',function(){
	var tag_ids=jQuery(this).find('.tags_id').html();
	this_click=jQuery(this);
	jQuery.ajax({
		'cache':true,
		'type':'POST',$csrf 		'url':'$url&type=no',
		'success':function(data){
			if(data !=1)
				alert(data);
			else{
				this_click.remove();
				var ids=new Array();
				jQuery('.items .tags_id').each(function (){
						ids.push($(this).html());
				});
				if(jQuery.inArray(tag_ids, ids) != -1){
					selected=jQuery('.items .tag_img').eq(jQuery.inArray(tag_ids, ids));
					selected.removeClass('selected');  
        	 		selected.addClass('optional');  
        	 		selected.attr('title','点击添加');
        	 		selected.find('img').attr('src','".Yii::app()->request->baseUrl.'/css/agent/images/tag_bg.png'."');	
				}
			}
		},
	});
	return false;
});   
		");

  ?>
  <div class="copyright">
    <span>Copyright &copy; TMM365.com All Rights Reserved</span>
  </div>  <!--.copyright--> 
</div>  <!--.content_box-->
