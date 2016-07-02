<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/admin/subjoin/subjoin.css" >
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/css/admin/subjoin/subjoin.js"></script>


<div style="min-width:1400px;">
	<!--项目 描述切换-->
	<div class="info_nav_float">
		<div class="info_nav_float_left">添加项目</div>
		<div class="info_nav_float_right">添加描述</div>
	</div>
	<div class="info_nav_float_clear"></div>
	<div class="info_nav_thrand"></div>

	<!--添加项目-->
	<div class="create_items">
		<div class="content_box scenic_spot line_all" id="line_operate">
			<?php

//			if(isset($model->Actives_Pro[0]->Pro_Thrand_Dot) && !empty($model->Actives_Pro[0]->Pro_Thrand_Dot)){
//				$item_arr = Pro::circuit_info($model->Actives_Pro,'Pro_Thrand_Dot');
//			}else
//				$item_arr=array();
//			$data_array = isset($item_arr['data_arr']) && $item_arr['data_arr'] ? $item_arr['data_arr'] : array();
//			$info_array = isset($item_arr['info_arr']) && $item_arr['info_arr'] ? $item_arr['info_arr'] : array();
				if( isset($model->Actives_Pro[0])  && $model->Actives_Pro[0]->thrand_id)
					$thrand_id = $model->Actives_Pro[0]->thrand_id;
				else
					$thrand_id = '';

			?>

			<!-- .create_nav -->
			<div class="content create_spot">
				<div class="content_one">
					<!-- 左边 -->
					<?php
					$form=$this->beginWidget('CActiveForm', array(
						'id'=>'actives-form',
						'enableAjaxValidation'=>true,
						'enableClientValidation'=>true,
						'clientOptions'=>array(
							'validateOnSubmit'=>true,
						),
						'htmlOptions'=>array('class'=>'create_left_form'),
					));
					?>
					<?php echo $form->errorSummary(array($model,$model->Actives_Shops)); ?>
					<div class="box_div">
						<div style="visibility:hidden"><span>活动名称</span></div>
						<div class="title"><span>活动名称</span></div>
						<div class="box left">
							<div class="spot_content">
								<div class="row-fluid spot_name">
									<?php echo $form->labelEx($model->Actives_Shops,'name'); ?>：
									<?php echo $form->textField($model->Actives_Shops,'name',array('class'=>'','placeholder'=>'请输入活动名称')); ?>
									<?php echo $form->error($model->Actives_Shops,'name'); ?>
									<!--费用包含-->
									<?php echo $form->hiddenField($model->Actives_Shops,'cost_info'); ?>
									<?php echo $form->error($model->Actives_Shops,'cost_info'); ?>
									<!--预订须知简介-->
									<?php echo $form->hiddenField($model->Actives_Shops,'book_info'); ?>
									<?php echo $form->error($model->Actives_Shops,'book_info'); ?>
									<!--活动备注-->
									<?php echo $form->hiddenField($model,'remark'); ?>
									<?php echo $form->error($model,'remark'); ?>
									<!--活动单价-->
									<?php echo $form->hiddenField($model,'price',array('class'=>'','placeholder'=>'请输入活动单价')); ?>
									<?php echo $form->error($model,'price'); ?>
									<!--线ID-->
									<?php echo $form->hiddenField($model,'thrand_id',array('value'=>$thrand_id)); ?>
									<?php echo $form->error($model,'thrand_id'); ?>
								</div>
								<div class="row-fluid spot_name">
									<?php echo $form->labelEx($model,'actives_type'); ?>：
									<?php //echo $form->dropDownList($model,'actives_type',array(''=>'--请选择--')+Actives::$_actives_type,array('style'=>'min-width:150px;')); ?>
									<?php echo $form->dropDownList($model,'actives_type',array(Actives::actives_type_tour=>Actives::$_actives_type[Actives::actives_type_tour]),array('style'=>'min-width:150px;')); ?>
									<?php echo $form->error($model,'actives_type'); ?>
								</div>
								<div class="row-fluid spot_name">
									<?php echo $form->labelEx($model,'tour_type'); ?>：
									<?php //echo $form->dropDownList($model,'tour_type',array(''=>'--请选择--')+Actives::$_tour_type,array('style'=>'min-width:150px;')); ?>
									<?php echo $form->dropDownList($model,'tour_type',array(Actives::tour_type_thrand=>Actives::$_tour_type[Actives::tour_type_thrand]),array('style'=>'min-width:150px;')); ?>
									<?php echo $form->error($model,'tour_type'); ?>
								</div>
								<div class="row-fluid spot_name">
									<?php echo $form->labelEx($model,'is_open'); ?>：
									<?php echo $form->dropDownList($model,'is_open',Actives::$_is_open,array('style'=>'min-width:150px;')); ?>
									<?php echo $form->error($model,'is_open'); ?>
								</div>
								<div class="row-fluid spot_name">
									<?php echo $form->labelEx($model,'pay_type'); ?>：
									<?php echo $form->dropDownList($model,'pay_type',Actives::$_pay_type,array('style'=>'min-width:150px;')); ?>
									<?php echo $form->error($model,'pay_type'); ?>
								</div>
								<div class="row-fluid spot_name">
									<?php echo $form->labelEx($model,'number'); ?>：
									<?php echo $form->textField($model,'number',array('class'=>'','placeholder'=>'请输入活动初始数量')); ?>
									<?php echo $form->error($model,'number'); ?>
								</div>
								<div class="row-fluid spot_name">
									<?php echo $form->labelEx($model,'tour_price'); ?>：
									<?php echo $form->textField($model,'tour_price',array('class'=>'','placeholder'=>'请输入活服务费')); ?>
									<?php echo $form->error($model,'tour_price'); ?>
								</div>
								<div class="row-fluid spot_name">
									<?php echo $form->labelEx($model,'start_time'); ?>：
									<?php
									$model->start_time=(!empty($model->start_time) && $model->start_time != 0)?(is_numeric($model->start_time))?date('Y-m-d',$model->start_time):0:0;
									$this->widget('zii.widgets.jui.CJuiDatePicker',array(
										'language'=>Yii::app()->language,
										'model'=>$model,
										'attribute'=>'start_time',
										'options'=>array(
											'minDate'=>'new date()',
											'flat'=>true,
											'dateFormat'=>'yy-mm-dd',
											'showOn' => 'focus',
											'showOtherMonths' => true,
											'selectOtherMonths' => true,
											'changeMonth' => true,
											'changeYear' => true,
											'htmlOptions'=>array(),
										),
									));
									?>
									<?php echo $form->error($model,'start_time'); ?>
								</div>
								<div class="row-fluid spot_name">
									<?php echo $form->labelEx($model,'end_time'); ?>：
									<?php
									$model->end_time=(!empty($model->end_time) && $model->end_time != 0)?(is_numeric($model->end_time))?date('Y-m-d',$model->end_time):0:0;
									$this->widget('zii.widgets.jui.CJuiDatePicker',array(
										'language'=>Yii::app()->language,
										'model'=>$model,
										'attribute'=>'end_time',
										'options'=>array(
											'minDate'=>'new date()',
											'flat'=>true,
											'dateFormat'=>'yy-mm-dd',
											'showOn' => 'focus',
											'showOtherMonths' => true,
											'selectOtherMonths' => true,
											'changeMonth' => true,
											'changeYear' => true,
											'htmlOptions'=>array(),
										),
									));
									?>
									<?php echo $form->error($model,'end_time'); ?>
								</div>
								<div class="row-fluid spot_name">
									<?php echo $form->labelEx($model,'go_time'); ?>：
									<?php
									$model->go_time=(!empty($model->go_time) && $model->go_time != 0)?(is_numeric($model->go_time))?date('Y-m-d',$model->go_time):0:0;
									$this->widget('zii.widgets.jui.CJuiDatePicker',array(
										'language'=>Yii::app()->language,
										'model'=>$model,
										'attribute'=>'go_time',
										'options'=>array(
											'minDate'=>'new date()',
											'flat'=>true,
											'dateFormat'=>'yy-mm-dd',
											'showOn' => 'focus',
											'showOtherMonths' => true,
											'selectOtherMonths' => true,
											'changeMonth' => true,
											'changeYear' => true,
											'htmlOptions'=>array(),
										),
									));
									?>
									<?php echo $form->error($model,'go_time'); ?>
								</div>
								<div class="bottom_line"></div>
								<!-- 一天的框 -->
								<div class="row-fluid top_line day_thrand_wrap">
								<?php
								if($thrand_id)
									$thrand_model = $this->thrand_data_form($thrand_id);
									if(isset($model) && $model->organizer_id)
								{
								?>
										<div class="span7">
											<div class="name">
												<span>
													<?php //echo CHtml::encode($model->Actives_Shops->name);?>
													<?php echo CHtml::encode($thrand_model->Thrand_Shops->name);?>
												</span>
											</div>
										<div class="row-fluid address">
												<span class="sapn10">
													<?php
													if(mb_strlen($thrand_model->Thrand_Shops->list_info,'utf-8')>72)
														echo CHtml::encode(mb_substr($thrand_model->Thrand_Shops->list_info,0,72,'utf-8')).' ...';
													else
														echo CHtml::encode($thrand_model->Thrand_Shops->list_info?$thrand_model->Thrand_Shops->list_info:'暂无列表信息');

													?>
												</span>
											</div>
										</div>
										<div class="span3">
											<div class="pull-right spot_img">
												<?php
												echo Yii::app()->controller->dot_list_show_img($thrand_model->Thrand_Shops->list_img);
												?>
											</div>
										</div>
										<div class="span2">
											<div class="btn_group">
												<?php echo CHtml::button('删除',array('class'=>'choose choose_clear'));?>
											</div>
										</div>
								<?php } ?>
								</div>
								<!-- .spot_div -->
								<div class="add">
									<a href="javascirpt:;" id="add">+</a>
								</div>
							</div>
							<!-- .spot_content -->
						</div>
						<!--  .box -->
					</div>
					<!-- .box_div -->
					<div class="row enter">
						<?php echo CHtml::submitButton($model->isNewRecord ? '创建' : '保存'); ?>
					</div>
					<?php $this->endWidget(); ?>
					<!-- 右边 -->
					<div class="box_div right">
						<div style="visibility:hidden"><span>选择线</span></div>
						<div class="title"><span>选择线</span></div>
						<div class="triangle"><img src="<?php echo Yii::app()->request->baseUrl;?>/css/agent/images/line/box.png"></div>
						<!-- 三角形移动 -->
						<div class="box right">
							<div class="spot_content"  id="dot_search">

								<?php

								$form=$this->beginWidget('CActiveForm', array(
									'action'=>Yii::app()->createUrl($this->route),
									'method'=>'get',
									'htmlOptions'=>array('class'=>'create_right_form'),
								));
								?>
								<div class="input-append element_group">
									<input type="text" name="search_info" placeholder="名称/商家" >
									<?php echo CHtml::submitButton('',array('class'=>'add-on element_icon search'));?>
								</div>
								<!--
								<div class="constraint">
									<ul>
										<li>按归属:</li>
										<li><a href="#" id="select_all" class="click">全部</a></li>
										<li><a href="#" id="select_me_create">我创建的点</a></li>
										<li><a href="#" id="select_no_me_create">別人创建的点</a></li>
									</ul>
								</div>
								-->
								<?php $this->endWidget(); ?>

								<?php
								Yii::app()->clientScript->registerScript('search', "
jQuery('#dot_search form').submit(function(){
     $.fn.yiiListView.update(
      'thrand_list',{'data':\$(this).serialize()}
    );
  return false;
});
jQuery(document).on('click','#select_all',function() {
    $.fn.yiiListView.update(
    'thrand_list',{'data':'create='}
  );
    jQuery(this).addClass(\"click\").parent().siblings().find('a').removeClass(\"click\");
  return false;
});
jQuery(document).on('click','#select_me_create',function() {
    $.fn.yiiListView.update(
    'thrand_list',{'data':'create=1'}
  );
    jQuery(this).addClass(\"click\").parent().siblings().find('a').removeClass(\"click\");
  return false;
});
jQuery(document).on('click','#select_no_me_create',function() {
    $.fn.yiiListView.update(
    'thrand_list',{'data':'create=-1'}
  );
    jQuery(this).addClass(\"click\").parent().siblings().find('a').removeClass(\"click\");
  return false;
});
");
								$summaryText=<<<"EOD"
<div class="text">
  <script type="text/javascript">
    jQuery(function($) {
  	  jQuery(".text").html('<span>'+({end}-{start}+1)+'条数据/共{pages}页</span>');
    })
  </script>
</div>
EOD;
								$this->widget('zii.widgets.CListView', array(
									'id'=>'thrand_list',
									'dataProvider'=>$search_model['dataProvider'],
									'itemView'=>'thrand_list_thrand',
									'template'=>"{sorter}\n{items}\n{summary}\n{pager}",
									'enableHistory'=>true,
									'summaryText'=>$summaryText,
									'emptyText'=>'<p>千里之行，始于足下，一切从创建点开始！</p>',
									//'afterAjaxUpdate'=>'alert(1)',
									'sortableAttributes'=>array(
										'id',
										'Thrand_Shops.name',
										'Thrand_Shops.add_time',
									),
									'pager'=>array(
										'class'=>'CLinkPager',
										'header'=>'',
										'cssFile'=>Yii::app()->request->baseUrl.'/css/admin/thrand/pager.css',
									),
									'cssFile'=>Yii::app()->request->baseUrl.'/css/admin/thrand/style.css',
								));
								?>


								<!-- .top_line -->
							</div>
							<!-- .spot_content -->
						</div>
						<!--  .box -->
					</div>
					<!-- .box_div -->
				</div>
				<!-- .content_one -->
			</div>


			<!--.content_box-->
		</div>
	</div>
	<!--添加描述-->
	<div class="create_descirbe">
		<?php

		$this->renderPartial('/_common/_html',array(
			'form'=>$form,
			'width'=>'98%',
			'height'=>280,
			'model'=>$model->Actives_Shops,
			'filespath'=>Yii::app ()->basePath .'/..'.Yii::app()->params['uploads_items_editor_eat'],
			'filesurl'=>Yii::app ()->baseUrl . Yii::app()->params['uploads_items_editor_eat'],
			'attribute'=>'cost_info',
		));
		?>
		<?php
		$this->renderPartial('/_common/_html',array(
			'form'=>$form,
			'width'=>'98%',
			'height'=>280,
			'model'=>$model->Actives_Shops,
			'filespath'=>Yii::app ()->basePath .'/..'.Yii::app()->params['uploads_items_editor_eat'],
			'filesurl'=>Yii::app ()->baseUrl . Yii::app()->params['uploads_items_editor_eat'],
			'attribute'=>'book_info',
		));
		?>
		<?php
		$this->renderPartial('/_common/_html',array(
			'form'=>$form,
			'width'=>'98%',
			'height'=>280,
			'model'=>$model,
			'filespath'=>Yii::app ()->basePath .'/..'.Yii::app()->params['uploads_items_editor_eat'],
			'filesurl'=>Yii::app ()->baseUrl . Yii::app()->params['uploads_items_editor_eat'],
			'attribute'=>'remark',
		));
		?>
	</div>

</div>
<?php
$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
	'id'=>'view_thrand_select',//弹窗ID
	'options'=>array(//传递给JUI插件的参数
		'title'=>'查看项目',
		'autoOpen'=>false,//是否自动打开
		'width'=>'1300px',//宽度
		'height'=>'auto',//高度
		'modal' => 'true',
	),
));
$this->endWidget();
?>

<script language="javascript">
	/*<![CDATA[*/
	jQuery(function($) {

		var thrand_id = jQuery('#Actives_thrand_id').val();
		var thrand_this = '';
		if(thrand_id)
			$('.add').css('display','none');
		/**
		 * 获得线ID
		 */
		function get_thrand_id(thiss){
			return  jQuery(thiss).attr('thrand_id');
		}

		/**
		 *  设置线ID值  隐藏值
		 */
		function set_thrand_id_hidden(vals){
			jQuery('#Actives_thrand_id').val(vals);
		}
		/**
		 *  获得线 名称 地址 图片========string
		 */
		function get_thrand_data(thiss){
			var thrand_obj = jQuery(thiss).parent().parent().parent();
			var thrand_arr = new Array();

			thrand_arr['name'] = thrand_obj.find('.span7 .name span').html();
			thrand_arr['addr'] = thrand_obj.find('.span7 .address span').html();
			thrand_arr['imgs'] = thrand_obj.find('.span3 .spot_img img').attr('src');

			return thrand_arr;
		}
		/**
		 *  获得线 名称 地址 图片========dom
		 */
		function get_thrand_data_dom(thiss){
			var thrand_obj = jQuery(thiss).parent().parent().parent();
			var thrand_arr = new Array();

			thrand_arr['name'] = thrand_obj.find('.span7').html();
			thrand_arr['imgs'] = thrand_obj.find('.span3').html();

			return thrand_arr;
		}

		/**
		 * 获得左边显示 样式
		 */
		function get_tpl(thrand_data){
			var tpls = '<div class="span7">'+ thrand_data['name'] +'</div><div class="span3">'+ thrand_data['imgs'] +'</div>';
			tpls += '<div class="span2"><div class="btn_group"><?php echo CHtml::button('删除',array('class'=>'choose choose_clear'));?></div></div>';
			return tpls;
		}
		/**
		 * 设置按钮是否可点
		 */
		function set_button_dis(thiss,val){
			jQuery(thiss).attr('disabled',val);
			if(val==true)
				jQuery(thiss).css({'background':'red','cursor':'no-drop'});
			else
				jQuery(thiss).css({'background':'none','cursor':'pointer'});
		}

		/**
		 * 左边显示选择线的内容
		 */
		function set_left_content(thiss){
			var thrand_data = get_thrand_data_dom(thiss);
			var tpls =  get_tpl(thrand_data);

			jQuery('.day_thrand_wrap').html(tpls);
		}

		/**
		 * 点击 选择 线 触发事件
		 */
		jQuery('body').on('click','.right .btn_group .choose',function(){
			/**
			 * 当前是否已经选择了一条线
			 * return false
			 */
			if(thrand_id)
			{
				alert('已经选择了一条线！');
				return false;
			}
			/**
			 * 获得当前选择点
			 */
			thrand_id = get_thrand_id(this);
			/**
			 * 设置 当前 选择按纽 不可点
			 */
			set_button_dis(this,true);
			/**
			 *  设置当前选择 线路 到左边显示
			 */
			set_left_content(this);
			/**
			 * 设置隐藏字段 线ID
			 */
			set_thrand_id_hidden(thrand_id);
			$('.add').css('display','none');
			thrand_this = this;
		});

		/**
		 * 点击删除
		 */
		jQuery('body').on('click','.choose_clear',function(){
			if(!confirm("确定要删除吗？"))
				return false;

			jQuery('.day_thrand_wrap').html('');
			set_button_dis(thrand_this,false);
			thrand_id = 0;
			set_thrand_id_hidden(thrand_id);
			$('.add').css('display','block');
		});

	});
	/*]]>*/

</script>
