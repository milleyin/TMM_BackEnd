<div class="content_box page_project_redact page_project_redact_again" id="page_project_create">
<?php
	echo $this->breadcrumbs(array(
				'项目'=>array('admin'),
				'项目编辑',
				$model->name,
			));
?>
    <?php 
    	$Items='items_'.$model->Items_ItemsClassliy->admin;
    	if($model->audit==Items::audit_nopass){    
    ?>
		    <div class="box check_failed">
		      <div><span>审核不通过原因</span></div>
		      <div><span><?php echo AuditLog::get_audit_log(constant('AuditLog::'.$Items),$model->id,AuditLog::nopass)->info;?></span></div>
		    </div>
    <?php 
		}
	?>
	<!-- .box -->
	<div class="create_nav modify_project_info">
<?php
	if ($model->Items_ItemsClassliy->append == 'Hotel') {
?>
		<div class="create_steap_one four_steaps_width">
			<a class="done">
				1
			</a>
			<span class="text_done">
				修改项目信息
			</span>
			<div class="line line_first done">
			</div>
		</div>
		<div class="create_steap_two four_steaps_width">
			<a class="done">
				2
			</a>
			<span class="text_done">
				选择服务
			</span>
			<div class="line done">
			</div>
		</div>
		<div class="create_steap_three four_steaps_width">
			<a class="done">
				3
			</a>
			<span class="text_done">
				选择标签
			</span>
			<div class="line done">
			</div>
		</div>
		<div class="create_steap_four four_steaps_width">
			<a class="undone">
				4
			</a>
			<span class="text_undone">
				提交审核
			</span>
			<div class="line line_last undone">
			</div>
		</div>
<?php } else { ?>
		<div class="create_steap_one three_steaps_width">
			<a class="done">
				1
			</a>
			<span class="text_done">
				修改项目信息
			</span>
			<div class="line line_first done">
			</div>
		</div>
		<div class="create_steap_two three_steaps_width">
			<a class="done">
				2
			</a>
			<span class="text_done">
				选择标签
			</span>
			<div class="line done">
			</div>
		</div>
		<div class="create_steap_three three_steaps_width">
			<a class="undone">
				3
			</a>
			<span class="text_undone">
				提交审核
			</span>
			<div class="line line_last undone">
			</div>
		</div>
<?php } ?>
	</div>
	<!-- .create_nav -->
	<div class="content">
		<div class="content_three">
			<div class="row-fluid choose_tag">
				<div class="span3">
				</div>
				<table border="0" class="span7 choose_tag">
					<tbody>
						<tr>
							<td>
								项目名称：
							</td>
							<td class="no_first">
								<?php echo CHtml::encode($model->name);?>
							</td>
						</tr>
						<tr>
							<td>
								项目标签：
							</td>
							<td class="tag_img_group already_tags">
			<?php 
					foreach($model->Items_TagsElement as $v) { 
				?>
						<div class="tag_img selected" title="点击移除" style="cursor:pointer;">
							<?php echo CHtml::image(Yii::app()->request->baseUrl.'/css/agent/images/tag_bg.png');?>
							<span><?php echo CHtml::encode($v->TagsElement_Tags->name); ?></span>
							<span class="tags_id" style="display:none"><?php echo CHtml::encode($v->tags_id);?></span>
						</div>
					<?php
						} 
					?>
							</td>
						</tr>
					</tbody>
				</table>
				<div class="row-fluid">
				</div>
				<div class="row-fluid box_div">
					<div class="span3">
					</div>
					<div class="title">
						<span>
							选择标签
						</span>
					</div>
					<div class="span7 box box_two">
		 <?php 
	          $this->widget('zii.widgets.CListView', array(
	          		'id'=>'items_list',
	          		'dataProvider'=>$tags_model,
	          		'itemView'=>'_tag',
	          		'template'=>"{items}\n{summary}\n{pager}",
					'viewData'=>array('select_id'=>$model->id,'admin'=>$model->Items_ItemsClassliy->admin),
	          		'pager'=>array(
	          				'class'=>'CLinkPager',
	          				'header'=>'',
	          				'cssFile'=>Yii::app()->request->baseUrl.'/css/agent/css/pager.css',
	          		),
	          ));         
          ?>
					</div>
					<!-- .box -->
				</div>
				<!-- .box_div -->
				<?php
					$form = $this->beginWidget('CActiveForm', array(
					    'id'=>'items-form',
					    'enableClientValidation'=>true,
					    'clientOptions'=>array(
					        'validateOnSubmit'=>true
					    ),
					));
					echo $form->hiddenField($model,'id');
				?>
				<div class="row-fluid enter choose_tag_group">
					<?php echo CHtml::submitButton('下一步',array('id'=>'choose_tag')); ?>
				</div>
			<?php $this->endWidget(); ?>	
			</div>
		</div>
		<!-- .content_three -->
	</div>
	<!-- .content -->
	<div class="copyright">
		<span>
			Copyright &copy; TMM365.com All Rights Reserved
		</span>
	</div>
	<!--.copyright-->
</div>
<!--.content_box-->

<?php 
  if(Yii::app()->request->enableCsrfValidation)
  {
  	$csrfTokenName = Yii::app()->request->csrfTokenName;
  	$csrfToken = Yii::app()->request->csrfToken;
  	$csrf = "\n\t\tdata:{'$csrfTokenName':'$csrfToken','tag_ids':tag_ids,'type':type},\n";
  }else
  	$csrf = "\n\t\tdata:{'tag_ids':tag_ids,'type':type},\n";
  $url=Yii::app()->createUrl('/agent/agent_items/tags',array('id'=>$model->id));
  
   		Yii::app()->clientScript->registerScript('search', "
jQuery('body').on('click','.optional',function(){
	var type='yes';
	var tag_ids=jQuery(this).find('.tags_id').html();
	this_click=jQuery(this);
	html=this_click.html();
	jQuery.ajax({
		'cache':true,
		'type':'POST',$csrf 		'url':'$url',
		'success':function(data){
			if(data !=1)
				alert(data);
			else{
				jQuery('.already_tags').append('<div class=\"tag_img one selected\" style=\"cursor:pointer;\" title=\"点击移除\">'+html+'</div>');
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
	var type='no';
	var tag_ids=jQuery(this).find('.tags_id').html();
	this_click=jQuery(this);
	jQuery.ajax({
		'cache':true,
		'type':'POST',$csrf 		'url':'$url',
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