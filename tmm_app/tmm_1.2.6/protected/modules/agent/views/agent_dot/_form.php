<div class="content_box scenic_spot" id="spot_create">
	<?php	
	echo $this->breadcrumbs(array(
		'点'=>array('admin'),
		$model->isNewRecord?'创建点':'编辑点',
	));
	?>
	<!--.title-->
	<?php
	if(isset($model->Dot_Shops->audit) && $model->Dot_Shops->audit == Shops::audit_nopass){
		$audit_nopass = AuditLog::get_audit_log(AuditLog::shops_dot,$model->Dot_Shops->id,AuditLog::nopass);
		?>
		<div class="box check_failed">
			<div><span>审核未通过原因</span></div>
			<div><span><?php echo CHtml::encode($audit_nopass->info); ?></span></div>
		</div>
	<?php }?>
	<div class="create_nav create_sub_business_nav">
		<div class="create_steap_one">
			<a class="done">1</a>
			<span class="text_done">选择项目</span>
			<div class="line line_first done"></div>
		</div>
		<div class="create_steap_two">
			<a class="undone">2</a>
			<span class="text_undone">添加标签</span>
			<div class="line undone"></div>
		</div>
		<div class="create_steap_five">
			<a class="undone">3</a>
			<span class="text_undone">提交审核</span>
			<div class="line line_last undone"></div>
		</div>
	</div>
	<!-- .create_nav -->
	<div class="content create_spot">
		<div class="content_one">
			<!-- 左边 -->
			<!--left form-->
			<?php  $form=$this->beginWidget('CActiveForm', array(
				'id'=>'dot-form',
				'enableAjaxValidation'=>true,
				'enableClientValidation'=>true,
				'clientOptions'=>array(
					'validateOnSubmit'=>true,
				),
				'htmlOptions'=>array('name'=>'agent','class'=>'create_left_form'),
			));  ?>
			<?php  //echo $form->errorSummary($model->Dot_Shops); ?>

				<div class="box_div">
					<div style="visibility:hidden"><span>点名称</span></div>
					<div class="title"><span>点名称</span></div>
					<div class="box left">
						<div class="spot_content">
							<div class="row-fluid spot_name">
								<?php echo $form->textField($model->Dot_Shops,'name',array('class'=>'left_search','placeholder'=>'请输入点名称')); ?>
								<?php echo $form->error($model->Dot_Shops,'name'); ?>
								<div class="group_info">
									<?php
									if(isset($model->Dot_Pro) && is_array($model->Dot_Pro) && isset($model->Dot_Pro[0]->items_id)){
										foreach ($model->Dot_Pro as $Dot_Pro){
											?>
											<div class="row-fluid top_line new_top_line">
												<div class="copy_div">
													<div class="span8 spot_info">
														<div class="row-fluid controls controls-row name">
															<span class="span5"><?php echo CHtml::encode($Dot_Pro->Pro_Items->name);?></span>
															<div class="span1 pull-right little_tag">
												<span >
												<?php echo CHtml::encode($Dot_Pro->Pro_ItemsClassliy->name);?>
												</span>
															</div>
														</div>
														<div class="row-fluid address">
															<span class="span3">地址：</span>
										<span class="span9">
											<?php
											echo CHtml::encode(
												$Dot_Pro->Pro_Items->Items_area_id_p_Area_id->name.
												$Dot_Pro->Pro_Items->Items_area_id_m_Area_id->name.
												$Dot_Pro->Pro_Items->Items_area_id_c_Area_id->name.
												$Dot_Pro->Pro_Items->address
											);
											?>
										</span>
														</div>
														<div class="row-fluid belong_business">
															<span class="span3">所属商家：</span>
															<span class="span9"><?php  echo CHtml::encode($model->Dot_Shops->Shops_Agent->firm_name); ?></span>
														</div>
													</div>
													<div class="pull-right spot_img">
														<?php
														$img='';
														if(isset($Dot_Pro->Pro_Items->Items_ItemsImg[0]))
															$img=$Dot_Pro->Pro_Items->Items_ItemsImg[0]->img;
														//	echo Yii::app()->controller->show_img($img);
														echo CHtml::image($img,'',array('style'=>'width:130px;height:60px;'));
														?>
													</div>
													<div class="list_id" style="display: none">
														<?php echo $form->HiddenField($Dot_Pro, "[]items_id")?>
														<?php // echo CHtml::hiddenField('Pro[][items_id]',$data->id,array('id'=>false))?>
													</div>
													<div class="mask_div">
														<div>
															<a href="#">
																<img src="<?php echo Yii::app()->request->baseUrl?>/css/agent/images/spot/up.png" class="group_up">
															</a>
															<a href="#">
																<img src="<?php echo Yii::app()->request->baseUrl?>/css/agent/images/spot/down.png" class="group_down">
															</a>
															<a href="#">
																<img src="<?php echo Yii::app()->request->baseUrl?>/css/agent/images/spot/delete.png" class="group_remove">
															</a>
														</div>
													</div>
												</div>
											</div>
											<?php
										}
									}
									?>
								</div>
							</div>
							<div class="message">
								待添加
							</div>
						</div>
						<!-- .spot_content -->
					</div>
					<!--  .box -->
				</div>
				<!-- .box_div -->
				<div class="row enter">
					<?php echo CHtml::submitButton('下一步',array('id'=>'choose_business')) ?>
				</div>
			<?php  $this->endWidget(); ?>
			<!-- form end-->
			<!-- 右边 -->
			<div class="box_div right">
				<div style="visibility:hidden"><span>点名称</span></div>
				<div class="title update"><span>点名称</span></div>
				<div class="box right">
					<div class="spot_content">
						<?php

						$form=$this->beginWidget('CActiveForm', array(
							'action'=>Yii::app()->createUrl($this->route),
							'method'=>'get',
							'htmlOptions'=>array('class'=>'create_right_form'),
						));

						?>
							<div class="input-append element_group">		
								<?php echo $form->textField($model_search,'name',array('placeholder'=>'项目/商家/地址')); ?>
								<?php echo CHtml::submitButton('',array('class'=>'add-on element_icon search')); ?>
							</div>
							<div class="constraint">
								<ul>
									<li>按归属:</li>
									<li><a href="#" id="select_all" class="click">全部</a></li>
									<li><a href="#" id="select_me_create">我创建的项目</a></li>
									<li><a href="#" id="select_no_me_create">別人创建的项目</a></li>
								</ul>
								<ul>
									<li>按分类:</li>
									<li><a href="#" id="items_all" class="click">全部</a></li>
									<li><a href="#" id="items_eat">吃</a></li>
									<li><a href="#" id="items_hotel">住</a></li>
									<li><a href="#" id="items_play">玩</a></li>
								</ul>
							</div>
						<?php $this->endWidget(); ?>
						<!--list stars-->

<?php
$summaryText=<<<"EOD"
<div class="page_text">
	<script type="text/javascript">
		jQuery(function($) {
			jQuery(".page_text").html('<span>'+({end}-{start}+1)+'条数据/共{pages}页</span>');
		})
	</script>
</div>
EOD;
			$this->widget('zii.widgets.CListView', array(
				'id'=>'items_list',
				'dataProvider'=>$dataProvider,
				'itemView'=>'_list_right',
				'template'=>"{items}\n{summary}\n{pager}",
				'enableHistory'=>true,
				'summaryText'=>$summaryText,
				'emptyText'=>'无匹配的数据',
				'pager'=>array(
					'class'=>'CLinkPager',
					'header'=>'',
					'cssFile'=>Yii::app()->request->baseUrl.'/css/agent/css/pager.css',
				),
				'cssFile'=>Yii::app()->request->baseUrl.'/css/agent/css/style.css',
			));			
	?>
						<!--list end-->
					</div>
				</div>
				<!--  .box -->
			</div>
			<!-- .box_div -->
		</div>
		<!-- .content_one -->
	</div>
	<!--  .content -->
	<div class="copyright">
		<span>Copyright &copy; TMM365.com All Rights Reserved</span>
	</div>
	<!--.copyright-->
</div>
<!--.content_box-->
<?php
$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
	'id'=>'view_items',//弹窗ID
	'options'=>array(//传递给JUI插件的参数
		'title'=>'查看项目',
		'autoOpen'=>false,//是否自动打开
		'width'=>'70%',//宽度
		'height'=>'auto',//高度
		'modal' => 'true',

	),
));
$this->endWidget();
?>
<?php
$baseUrl=Yii::app()->request->baseUrl;
$mask=<<<"END"
"<div class='mask_div'><div><a href='#'><img src='$baseUrl/css/agent/images/spot/up.png' class='group_up'></a><a href='#'><img src='$baseUrl/css/agent/images/spot/down.png' class='group_down'></a><a href='#'><img src='$baseUrl/css/agent/images/spot/delete.png' class='group_remove'></a></div></div>"
END;
$div=<<<"END"
"<div class='row-fluid top_line new_top_line'></div>"
END;
$max_number=Yii::app()->params['shops_dot_items_number'];
Yii::app()->clientScript->registerScript('search', "
$('.spot_content form').submit(function(){
		 $.fn.yiiListView.update(
			'items_list',{'data':\$(this).serialize()}
		);
	return false;
});

var create='';
var agent_item='';

jQuery(document).on('click','#select_all',function() {
	create='';
  	$.fn.yiiListView.update(
		'items_list',{'data':'create='+create+'&agent_item='+agent_item}
	);
  	jQuery(this).addClass(\"click\").parent().siblings().find('a').removeClass(\"click\");
	return false;
});
jQuery(document).on('click','#select_me_create',function() {
	create=1;
  	$.fn.yiiListView.update(
		'items_list',{'data':'create='+create+'&agent_item='+agent_item}
	);
  	jQuery(this).addClass(\"click\").parent().siblings().find('a').removeClass(\"click\");
	return false;
});
jQuery(document).on('click','#select_no_me_create',function() {
	create=-1;
  	$.fn.yiiListView.update(
		'items_list',{'data':'create='+create+'&agent_item='+agent_item}
	);
  	jQuery(this).addClass(\"click\").parent().siblings().find('a').removeClass(\"click\");
	return false;
});
jQuery(document).on('click','#items_all',function() {
	agent_item='';
  	$.fn.yiiListView.update(
		'items_list',{'data':'create='+create+'&agent_item='+agent_item}
	);
  	jQuery(this).addClass(\"click\").parent().siblings().find('a').removeClass(\"click\");
	return false;
});
jQuery(document).on('click','#items_eat',function() {
	agent_item=1;
  	$.fn.yiiListView.update(
		'items_list',{'data':'create='+create+'&agent_item='+agent_item}
	);
  	jQuery(this).addClass(\"click\").parent().siblings().find('a').removeClass(\"click\");
	return false;
});
jQuery(document).on('click','#items_hotel',function() {
	agent_item=2;
  	$.fn.yiiListView.update(
		'items_list',{'data':'create='+create+'&agent_item='+agent_item}
	);
  	jQuery(this).addClass(\"click\").parent().siblings().find('a').removeClass(\"click\");
	return false;
});
jQuery(document).on('click','#items_play',function() {
	agent_item=3;
  	$.fn.yiiListView.update(
		'items_list',{'data':'create='+create+'&agent_item='+agent_item}
	);
  	jQuery(this).addClass(\"click\").parent().siblings().find('a').removeClass(\"click\");
	return false;
});


var isExist=jQuery('.message');
//绑定选择事件
jQuery('body').on('click','.btn_group .choose',function(){
	//获得当前点击 ID 值
	var id= jQuery(this).parent().parent().find('.list_id input').val();
	var data=new Array();
	jQuery('.content_box .group_info input[name=\'Pro[][items_id]\']').each(function (){
			data.push($(this).val());
	});
	//比对ID值 是否已在左边添加
	if(jQuery.inArray(id, data) != -1){
		alert('该项目已添加！');
		jQuery(this).attr('disabled',true);
		$(this).css('cursor','not-allowed');
		return false;
	}else if(data.length == $max_number){
		alert('一个点中只能有 $max_number 个项目！');
		return false;	
	}
	//复制要显示的内容
	var content = jQuery(this).parent().parent().find('.copy_div').clone();
	//拼接 三个按纽
	content.append($mask);
	//嵌套背景
    var div=jQuery($div);
    //合并
	div.append(content);
	//赋值到左边显示区域
	jQuery('.left .spot_content .group_info').append(div);
	//移除待添加字样
	if(isExist.length>0){
		jQuery('.message').remove();
	}
	//当前点击选择按纽不可点
	jQuery(this).attr('disabled',true);
	$(this).css('cursor','not-allowed');
	return false;
});
//获得当前元素的index 值
function group_eq(this_eq){
	return this_eq.parent().parent().parent().parent().parent().index();
}
//获得当前对像 html 值
function group_html(eq){
	return jQuery('.group_info .new_top_line').eq(eq).html();
}
//双相赋值
function swap_html(eq_f,html_f,eq_t,html_t){
	jQuery('.group_info .new_top_line').eq(eq_t).html(html_f);
	jQuery('.group_info .new_top_line').eq(eq_f).html(html_t);
}
//绑定向上移按纽
jQuery('body').on('click','.group_up',function(){

	var eq =group_eq(jQuery(this));
	if(eq==0){
		alert('已经是第一个了！');
		return false;
	}
	swap_html(eq,group_html(eq),eq-1,group_html(eq-1));
	return false;
});
//绑定向下移动按纽
jQuery('body').on('click','.group_down',function(){
	var eq =group_eq(jQuery(this));
	if(eq==(jQuery('.group_info').children().length-1))
	{
		alert('已经是最后一个了！');
		return false;
	}
	swap_html(eq,group_html(eq),eq+1,group_html(eq+1));
	return false;
});
//绑定删除按纽
jQuery('body').on('click','.group_remove',function(){
		//获得当前元素
		var eq =group_eq(jQuery(this));
		//获得当前ID
		var id=jQuery(this).parent().parent().parent().parent().parent().find('input[name=\'Pro[][items_id]\']').val();
		var data=new Array();
		//获得所有ID
		jQuery('.items input[name=\'Pro[][items_id]\']').each(function (){
				data.push($(this).val());
		});
		// 当前ID在所有ID中，移除上面添加的CSS 属性
		if(jQuery.inArray(id, data) != -1){
			jQuery('.items .btn_group').eq(jQuery.inArray(id, data)).find('input').removeAttr('disabled').removeAttr('style');
		}
		//移除左边显示内容
		jQuery(this).parent().parent().parent().parent().parent().remove();
		return false;
});
");
?>
