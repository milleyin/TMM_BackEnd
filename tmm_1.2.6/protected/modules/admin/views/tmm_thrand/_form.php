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


	if(isset($model->Thrand_Pro[0]->Pro_Thrand_Dot) && !empty($model->Thrand_Pro[0]->Pro_Thrand_Dot)){
		$item_arr = Pro::circuit_info($model->Thrand_Pro,'Pro_Thrand_Dot');
	}else
		$item_arr=array();
	$data_array = isset($item_arr['data_arr']) && $item_arr['data_arr'] ? $item_arr['data_arr'] : array();
	$info_array = isset($item_arr['info_arr']) && $item_arr['info_arr'] ? $item_arr['info_arr'] : array();

	?>

	<!-- .create_nav -->
	<div class="content create_spot">
		<div class="content_one">
			<!-- 左边 -->
			<?php
			$form=$this->beginWidget('CActiveForm', array(
				'id'=>'thrand-form',
				'enableAjaxValidation'=>true,
				'enableClientValidation'=>true,
				'clientOptions'=>array(
					'validateOnSubmit'=>true,
				),
				'htmlOptions'=>array('class'=>'create_left_form'),
			));
			?>
			<div class="box_div">
				<div style="visibility:hidden"><span>线名称</span></div>
				<div class="title"><span>线名称</span></div>
				<div class="box left">
					<div class="spot_content">
						<div class="row-fluid spot_name">
							<?php echo $form->textField($model->Thrand_Shops,'name',array('class'=>'left_search','placeholder'=>'请输入点名称')); ?>
							<?php echo $form->error($model->Thrand_Shops,'name'); ?>

							<?php echo $form->hiddenField($model->Thrand_Shops,'cost_info'); ?>
							<?php echo $form->error($model->Thrand_Shops,'cost_info'); ?>

							<?php echo $form->hiddenField($model->Thrand_Shops,'book_info'); ?>
							<?php echo $form->error($model->Thrand_Shops,'book_info'); ?>
						</div>
						<div class="bottom_line"></div>
						<!-- 一天的框 -->
						<!-- .spot -->
						<div id="day_dot_wrap">
							<?php
							$day=1;
							if(!empty($data_array))
							{
								foreach ($data_array as $key=>$data_dot_sort)
								{
									for($day;$day<$key;$day++)
									{
										?>
										<div class="spot_div">
											<div class="row-fluid spot">
												<div class="row-fluid span1 date_all">
													<a href="javascript:;" class="set_day_sort" data-sort="<?php echo CHtml::encode($day);?>"><?php echo CHtml::encode(Pro::item_swithc($day));?></a>
												</div>
												<!-- .date_all -->
												<div class="row-fluid span11 right line_right_box dot_info_wrap">
													<div class="message">
														待添加
													</div>
												</div>
												<!-- .right -->
											</div>
											<!-- .spot -->
										</div>
										<!-- .spot_div -->
										<?php
									}
									$day=$key;
									?>
									<div class="spot_div">
										<div class="row-fluid spot">
											<div class="row-fluid span1 date_all">
												<a href="javascript:;" class="set_day_sort" data-sort="<?php echo CHtml::encode($key);?>"><?php echo CHtml::encode(Pro::item_swithc($key));?></a>
											</div>
											<!-- .date_all -->
											<div class="row-fluid span11 right line_right_box dot_info_wrap">
												<?php

												foreach ($data_dot_sort as $key_dot=>$data_dot)
												{
													foreach ($data_dot as $dot_id => $data_items)
													{
														?>
														<div class="module one">
															<div class="spot_name">
																<span><?php echo CHtml::encode($info_array['dot_data'][$dot_id]->name);?></span>
																<?php
																if($info_array['dot_data'][$dot_id]->status != Shops::status_online)
																{
																	?>
																	<span style="color: red;">（已<?php echo Shops::$_status[$info_array['dot_data'][$dot_id]->status];?>）</span>
																	<?php
																}
																?>
																<div class="pull-right edit">
																	<a href="javascript:;" class="dot_delete"><img src="<?php echo Yii::app()->request->baseUrl;?>/css/agent/images/line/delete.png"></a>
																</div>
															</div>
															<!-- 添加项目信息 -->
															<?php
															foreach ($data_items as $sort => $items)
															{
																?>
																<div class="row_item_info">
																	<div class="row-fluid noe">
																		<div class="span1 num">
																			<a href="javascript:;"><?php echo CHtml::encode($sort+1);?></a>
																		</div>
																		<div class="span10 content">
																			<div class="spot_info">
																				<div class="row-fluid controls controls-row name">
																					<span class="span5"><?php echo CHtml::encode($items->Pro_Items->name);?></span>
																					<?php
																					if($items->Pro_Items->status != Items::status_online)
																					{
																						?>
																						<span style="color: red">（已<?php echo Items::$_status[$items->Pro_Items->status];?>）</span>
																						<?php
																					}
																					?>
																					<input type="hidden" name="<?php echo 'Pro['.$key.']['.$key_dot.']['.$dot_id.']['.$sort.']';?>" value="<?php echo $items->Pro_Items->id;?>">
																					<div class="span1 pull-right little_tag"><span><?php echo CHtml::encode($items->Pro_Items->Items_ItemsClassliy->name);?></span></div>
																				</div>
																				<div class="row-fluid address">
																					<span class="span3">地址：</span>
				                        <span class="span9">
				                        <?php echo CHtml::encode(
											$items->Pro_Items->Items_area_id_p_Area_id->name.
											$items->Pro_Items->Items_area_id_m_Area_id->name.
											$items->Pro_Items->Items_area_id_c_Area_id->name.
											$items->Pro_Items->address);
										?>
                                            </span>
																				</div>
																			</div>
																			<!-- .spot_info -->
																		</div>
																		<!-- .content -->
																	</div>
																	<!-- .noe -->
																	<div class="good_info">
																		<div class="box_div">
																			<div style="visibility:hidden"><span>已选商品</span></div>
																			<div class="title"><span>已选商品</span></div>
																			<div class="box box_one">
																				<ul>
																					<!-- add 商品价格信息 -->
																					<?php
																					if($items->Pro_Items->Items_ItemsClassliy->append=="Hotel")
																					{
																						foreach ($items->Pro_ProFare as $fare_key=>$ProFare)
																						{
																							?>
																							<li>
																								<span><?php echo CHtml::encode($ProFare->ProFare_Fare->name);?></span>
																								<span><?php echo CHtml::encode($ProFare->ProFare_Fare->info);?></span>
																								<span><?php echo CHtml::encode($ProFare->ProFare_Fare->number);?></span>
																								<span><?php echo CHtml::encode($ProFare->ProFare_Fare->price);?></span>
																								<span><img class="fare_delete" src="<?php echo Yii::app()->request->baseUrl;?>/css/agent/images/line/delete.png"></span>
																								<input type="hidden" name="<?php echo 'ProFare['.$key.']['.$key_dot.']['.$dot_id.']['.$sort.']['.$items->Pro_Items->id.']['.$fare_key.']';?>" value="<?php echo $ProFare->ProFare_Fare->id;?>">
																							</li>
																							<?php
																						}
																					}else{
																						foreach ($items->Pro_ProFare as $fare_key=>$ProFare)
																						{
																							?>
																							<li>
																								<span><?php echo CHtml::encode($ProFare->ProFare_Fare->name);?></span>
																								<span><?php echo CHtml::encode($ProFare->ProFare_Fare->info);?></span>
																								<span><?php echo CHtml::encode($ProFare->ProFare_Fare->price);?></span>
																								<span><img class="fare_delete" src="<?php echo Yii::app()->request->baseUrl;?>/css/agent/images/line/delete.png"></span>
																								<input type="hidden" name="<?php echo 'ProFare['.$key.']['.$key_dot.']['.$dot_id.']['.$sort.']['.$items->Pro_Items->id.']['.$fare_key.']';?>" value="<?php echo $ProFare->ProFare_Fare->id;?>">
																							</li>
																							<?php
																						}
																					}
																					?>
																				</ul>
																			</div>
																			<!-- .box -->
																		</div>
																		<!-- .box_div -->
																	</div>
																	<!-- .good_info -->
																</div>
																<?php
															}
															?>
														</div>
														<?php
													}
												}
												?>

											</div>
											<!-- .right -->
										</div>
										<!-- .spot -->
									</div>
									<!-- .spot_div -->

									<?php
									$day++;
								}
							}
							?>
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
				<div style="visibility:hidden"><span>选择点</span></div>
				<div class="title"><span>选择点</span></div>
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
						<div class="constraint">
							<ul>
								<li>按归属:</li>
								<li><a href="#" id="select_all" class="click">全部</a></li>
								<li><a href="#" id="select_me_create">我创建的点</a></li>
								<li><a href="#" id="select_no_me_create">別人创建的点</a></li>
							</ul>
						</div>
						<?php $this->endWidget(); ?>

						<?php
						Yii::app()->clientScript->registerScript('search', "
jQuery('#dot_search form').submit(function(){
     $.fn.yiiListView.update(
      'dot_list',{'data':\$(this).serialize()}
    );
  return false;
});
jQuery(document).on('click','#select_all',function() {
    $.fn.yiiListView.update(
    'dot_list',{'data':'create='}
  );
    jQuery(this).addClass(\"click\").parent().siblings().find('a').removeClass(\"click\");    
  return false;
});
jQuery(document).on('click','#select_me_create',function() {
    $.fn.yiiListView.update(
    'dot_list',{'data':'create=1'}
  );
    jQuery(this).addClass(\"click\").parent().siblings().find('a').removeClass(\"click\");      
  return false;
});
jQuery(document).on('click','#select_no_me_create',function() {
    $.fn.yiiListView.update(
    'dot_list',{'data':'create=-1'}
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
							'id'=>'dot_list',
							'dataProvider'=>$search_model['dataProvider'],
							'itemView'=>'_list_dot',
							'template'=>"{items}\n{summary}\n{pager}",
							'enableHistory'=>true,
							'summaryText'=>$summaryText,
							'emptyText'=>'<p>千里之行，始于足下，一切从创建点开始！</p>',
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
			'model'=>$model->Thrand_Shops,
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
			'model'=>$model->Thrand_Shops,
			'filespath'=>Yii::app ()->basePath .'/..'.Yii::app()->params['uploads_items_editor_eat'],
			'filesurl'=>Yii::app ()->baseUrl . Yii::app()->params['uploads_items_editor_eat'],
			'attribute'=>'book_info',
		));
		?>
	</div>

</div>
<?php
$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
	'id'=>'view_dot_select',//弹窗ID
	'options'=>array(//传递给JUI插件的参数
		'title'=>'选择项目',
		'autoOpen'=>false,//是否自动打开
		'width'=>'750px',//宽度
		'height'=>'auto',//高度
		'modal' => 'true',
	),
));
$this->endWidget();
?>
<script type="text/javascript">
	/*<![CDATA[*/
	jQuery(function($) {

		var day_arr = ['一','二','三','四','五','六','七','八','九','十'];
		// 当前已经添加第几个点
		var half_sort = 0;
		// 点击添加的天数
		var day_sort = <?php echo $day-1; ?>;
		// 当前操作的天数
		var set_day = 1;
		// 操作点击操作的天的内容
		var initial_text = '';
		function get_current_day(day) {
			var key = day % 2;
			if (key == 0) {
				return '第'+day_arr[Math.ceil(day/2)-1]+'天下午';
			}else {
				return '第'+day_arr[Math.ceil(day/2)-1]+'天上午';
			}
		}
		/**
		 * 获取模板
		 */
		function get_tpl(id)
		{
			return jQuery('#'+id).html();
		}

		/**
		 * 添加模板
		 */
		function add_html(ele, html)
		{
			return ele.append(html);
		}

		/**
		 * 替换模板标签
		 */
		function replace_data(tag, val, tpl)
		{
			return tpl.replace(new RegExp('{{' +tag + '}}', 'g'), val);
		}

		/**
		 * 替换模板变量
		 */
		function set_tpl(datas, tpl)
		{
			for (data in datas) {
				tpl =  replace_data(data, datas[data], tpl);
			}
			return tpl;
		}

		/**
		 * 获取最外层div对象
		 * ele_th 确认按钮
		 * return 最外层div对象
		 */
		function get_wrap(ele_th) {
			return jQuery(ele_th).parent().parent().parent().parent();
		}
		/**
		 * 获取点的名称
		 * ele_p 最外层div对象
		 */
		function get_dot_name(ele_p) {
			return ele_p.find('.spot_name span').html();
		}
		/**
		 * 获当前取点的id
		 * ele_p 最外层div对象
		 */
		function get_dot_id(ele_p) {
			return ele_p.find('#dot_id').html();
		}
		/**
		 * 获取项目的名称
		 * ele_p 最外层div对象
		 */
		function get_item_name(ele_p) {
			return ele_p.find('.spot_info .controls .span8').html();
		}
		/**
		 * 获当前取点以下项目的id
		 * ele_s 最外层div对象下项目的对象
		 */
		function get_item_id(ele_s) {
			return ele_s.find('.item_id').html();
		}
		/**
		 * 获当前取点以下项目的类型
		 * ele_s 最外层div对象下项目的对象
		 */
		function get_item_type(ele_s) {
			return ele_s.find('.item_type').html();
		}
		/**
		 * 获取项目地址的名称
		 * ele_p 最外层div对象
		 */
		function get_address_name(ele_p) {
			return ele_p.find('.spot_info .address span').eq(1).html();
		}
		/**
		 * 获取项目价格信息
		 * fare_ele 商品价格对象
		 */
		function get_fare_name(fare_ele,eq) {
			return fare_ele.find('span').eq(eq).html();
		}
		/**
		 * 获取项目价格的id
		 * fare_ele 商品价格对象
		 */
		function get_fare_id(fare_ele) {
			return fare_ele.find('input').val();
		}
		/**
		 *
		 * 更新所有点的排序
		 */
		function update_dots_sort()
		{
			var th = jQuery('#day_dot_wrap');
			th.find('.spot_div').each(function(index, day) {
				var day_obj = jQuery(day);
				var dots = day_obj.find('.module');
				if(dots){
					// 循环点
					dots.each(function(dot_index, dot_ele) {
						var dot_obj = jQuery(dot_ele);
						// 更新项目的排序
						update_items_sort(dot_obj, dot_index);
					});
				}
			});
		}
		/**
		 *
		 * 更新所有项目的排序
		 */
		function update_items_sort(dot_obj, dot_key)
		{
			var items = dot_obj.find('.row_item_info');
			if (items) {
				items.each(function(item_index, item_ele) {
					var item_obj = jQuery(item_ele);
					// 更新项目的序号
					item_obj.find('.num a').html(item_index + 1);
					var item_input_obj = item_obj.find('.spot_info input');
					var item_input_name = item_input_obj.attr('name');
					// Pro[1][0][33][0]
					// 按'['切割取到各个值
					var old_item_arr = item_input_name.split('[');
					// 把排序好的值和原来不变的值拼接回去
					var new_item_input_name = old_item_arr[0] + '[' + old_item_arr[1] + '[' + dot_key + ']' + '[' + old_item_arr[3] + '[' + item_index + ']';
					// 排好序后，赋值回去
					item_input_obj.attr('name', new_item_input_name);
					// 更新价格的排序
					update_fares_sort(item_obj, dot_key, item_index);
				});
			}
		}
		/**
		 *
		 * 更新所有价格的排序
		 */
		function update_fares_sort(item_obj, dot_key, item_key)
		{
			var fare_inputs = item_obj.find('.good_info li input');
			if (fare_inputs) {
				fare_inputs.each(function(fare_index, fare_input_ele) {
					var self = jQuery(fare_input_ele);
					var fare_input_name = self.attr('name');
					// ProFare[1][0][33][0][49][1]
					// 按'['切割取到各个值
					var old_fare_arr = fare_input_name.split('[');
					// 把排序好的值和原来不变的值拼接回去
					var new_fare_input_name = old_fare_arr[0] + '[' + old_fare_arr[1] + '[' + dot_key + ']'
						+ '[' + old_fare_arr[3] + '[' + item_key + ']' + '[' + old_fare_arr[5] + '[' + fare_index + ']';
					// 排好序后，赋值回去
					self.attr('name', new_fare_input_name);
				});
			}
		}

		/**
		 * 获取数据格式
		 */
		function get_dot_all(ele_p)
		{
			var data = {
//            'dot_name':'',
//            'item_list':[
//                {
//                    'item_name':'',
//                    'item_type':'',
//                    'item_address':'',
//                    'item_id': {'name':'', 'value':''},
//                    'fares_info':[
//                        {
//                            'fare_name':'',
//                            'fare_info':'',
//                            'fare_number':'',
//                            'fare_price':'',
//                            'fare_id':{'name':'', 'value':''}
//                        }
//                    ]
//                }
//            ]
			};

			var item_key = 0;
			var is_checked=false;
			var items_arr = [];
			var dot_id = get_dot_id(ele_p);
			// 点的名称
			data.dot_name = get_dot_name(ele_p);
			ele_p.find('.project').each(function(index, item) {
				var item_obj = jQuery(item);
				var item_id = get_item_id(item_obj);
				var fare_arr = [];
				item_obj.find(".good_info ul li").each(function(key, fare) {
					var fare_obj = jQuery(fare);
					if (fare_obj.find('input').is(':checked'))
					{
						var fare_data_obj = {
							'fare_name': get_fare_name(fare_obj, 0),
							'fare_info': get_fare_name(fare_obj, 1),
							'fare_number':get_fare_name(fare_obj, 3) ?  get_fare_name(fare_obj, 2): '',
							'fare_price': get_fare_name(fare_obj, 3) ?  get_fare_name(fare_obj, 3): get_fare_name(fare_obj, 2),
							'fare_id':{'name':'ProFare[' + set_day + '][' + half_sort + ']['+ dot_id +'][' + item_key + '][' + item_id + '][' + key +']', 'value':get_fare_id(fare_obj)}
						};
						fare_arr.push(fare_data_obj);
						is_checked=true;
					}

				});
				if(is_checked){
					var key_val = 'Pro[' + set_day + '][' + half_sort + ']['+ dot_id +'][' + item_key + ']';

					var item_data_obj = {
						'item_name': get_item_name(item_obj),
						'item_type': get_item_type(item_obj),
						'item_address': get_address_name(item_obj),
						'item_id': {'name':key_val, 'value':item_id},
						'fares_info': fare_arr
					};
					items_arr.push(item_data_obj);

					item_key++;
					is_checked=false;
				}
			});
			// 项目信息
			data.item_list = items_arr;

			return data;
		}

		/**
		 * 返回编译后的内容
		 */
		function compile_tpl(p_obj) {
			var data = get_dot_all(p_obj);
			// 获取点的模板内容
			var dot_html = set_tpl({'dot_name':data.dot_name},get_tpl('dot_tpl'));
			// 获取项目的模板内容
			var items_html = '';
			for (item_key in data.item_list) {
				// 获取商品信息的模板内容
				var fares_html = '';
				for (fare_key in data.item_list[item_key].fares_info) {
					if (data.item_list[item_key].fares_info[fare_key].fare_number) {
						fares_html += set_tpl({
							'fare_name': data.item_list[item_key].fares_info[fare_key].fare_name,
							'fare_info': data.item_list[item_key].fares_info[fare_key].fare_info,
							'fare_number': data.item_list[item_key].fares_info[fare_key].fare_number,
							'fare_price': data.item_list[item_key].fares_info[fare_key].fare_price,
							'fare_input_name': data.item_list[item_key].fares_info[fare_key].fare_id.name,
							'fare_input_value': data.item_list[item_key].fares_info[fare_key].fare_id.value
						}, get_tpl('fare_hotel_tpl'));
					} else {
						fares_html += set_tpl({
							'fare_name': data.item_list[item_key].fares_info[fare_key].fare_name,
							'fare_info': data.item_list[item_key].fares_info[fare_key].fare_info,
							'fare_number': data.item_list[item_key].fares_info[fare_key].fare_number,
							'fare_price': data.item_list[item_key].fares_info[fare_key].fare_price,
							'fare_input_name': data.item_list[item_key].fares_info[fare_key].fare_id.name,
							'fare_input_value': data.item_list[item_key].fares_info[fare_key].fare_id.value
						}, get_tpl('fare_tpl'));
					}
				}
				items_html += set_tpl({
					'item_sort': parseInt(item_key, 10)+1,
					'item_name': data.item_list[item_key].item_name,
					'item_type': data.item_list[item_key].item_type,
					'item_address': data.item_list[item_key].item_address,
					'item_input_name': data.item_list[item_key].item_id.name,
					'item_input_value': data.item_list[item_key].item_id.value,
					'item_fares_info': fares_html
				}, get_tpl('item_tpl'));
			}

			return set_tpl({'items_info':items_html},dot_html);
		}

		jQuery('body').on('click','#add',function(){
			day_sort++;
			var max_day = <?php echo Yii::app()->params['shops_thrand_day_number']; ?>;
			if ((day_sort / 2) > max_day )
			{
				alert('最多只能添加' + max_day + '天');
				return false;
			}
			var tpl = get_tpl('day_empty_tpl');
			var data = {'day':get_current_day(day_sort), 'day_sort':day_sort};
			add_html(jQuery('#day_dot_wrap'), set_tpl(data,tpl));
			update_dots_sort();
			return false;
		});


		jQuery('body').on('mouseenter','.date_all a',function(){
			initial_text = jQuery(this).text();
			jQuery(this).text("点击插入");
		});
		jQuery('body').on('mouseout','.date_all a',function(){
			jQuery(this).text(initial_text);
		});

		jQuery('body').on('click','.set_day_sort',function(){
			set_day = jQuery(this).attr('data-sort');
			var top = jQuery(this).position().top+70;
			jQuery(".box_div.right .triangle").css("top",top);
			return false;
		});

		jQuery('body').on('click','.btn_group .choose',function(){
			if(day_sort==0)
			{
				alert('请先添加日程！');
				return false;
			}
			var th=this;
			jQuery.ajax({
				'url':jQuery(th).attr('href'),
				'cache':true,
				'success':function(html){
					jQuery("#view_dot_select").html(html);
					jQuery("#view_dot_select").dialog("open");
				},
			});
			return false;
		});
		jQuery('body').on('click','#cancel',function(){
			jQuery("#view_dot_select").dialog("close");
			return false;
		});
		jQuery('body').on('click','#sure',function(){
			var p_obj = get_wrap(this);
			if (p_obj.find('input:checked').length == 0) {
				alert('你还没有选择项目的价格');
				return false;
			}
			var dot_item_fare_html = compile_tpl(p_obj);
			var spot_div_entry = jQuery('#day_dot_wrap .spot_div').eq(set_day-1);
			if (spot_div_entry) {
				spot_div_entry.find('.message').remove();
				spot_div_entry.find('.dot_info_wrap').append(dot_item_fare_html);

			} else {
				alert('请添加日程');
			}

			update_dots_sort();

			jQuery("#view_dot_select").dialog("close");
			return false;
		});

		// 点的删除
		jQuery('body').on('click','#day_dot_wrap .dot_delete',function(){
			var thisObj = jQuery(this);
			var dot_obj = thisObj.parent().parent().parent();
			var day_obj = dot_obj.parent();

			if (day_obj.find('.module ').length ==  1) {
				dot_obj.remove();
				day_obj.append('<div class="message">待添加</>');
			} else {
				dot_obj.remove();
			}

			update_dots_sort();

			return false;
		});

		// 商品价格信息的删除
		jQuery('body').on('click','#day_dot_wrap .fare_delete',function(){
			var thisObj = jQuery(this);
			// 它自己的容器 li
			var fare_li = thisObj.closest('li');
			// 商品价格信息父容器 ul
			var fare_ul = fare_li.closest('ul');
			// 项目信息的容器 .row_item_info
			var fare_row_item_info = fare_ul.closest('.row_item_info');
			// 点信息的容器
			var fare_module = fare_row_item_info.closest('.module');
			// dot_info_wrap容器
			var fare_dot_info_wrap = fare_module.parent();

			// 先判断是否只有一个价格信息了
			if (fare_ul.find('li').length ==  1) {
				// 判断是否只有一个项目信息了
				if (fare_module.find('.row_item_info').length == 1) {
					// 判断是否只有一个点信息了
					// 删除整个点
					if (fare_dot_info_wrap.find('.module').length == 1) {
						fare_module.remove();
						fare_dot_info_wrap.append('<div class="message">待添加</>');
					}
					fare_module.remove();
				}
				// 已经是最后一个商品价格了，则删除整个项目
				fare_row_item_info.remove();
			} else {
				// 删除自己
				fare_li.remove();
			}

			update_dots_sort();

			return false;
		});
	});
	/*]]>*/
</script>
<style>
	.popup_box {
		width: 85%;
		height: 600px;
		overflow-x: auto;
		overflow-y: auto;
		padding: 0 50px;
		position: static;
		z-index: 10001;
		border: 1px solid #000;
		background: #fff;
		display: block;
		background: #FFF8ED;
	}
</style>
<?php // 空的天的模板 ?>
<div id="day_empty_tpl" style="display:none;">
	<div class="spot_div">
		<div class="row-fluid spot">
			<div class="row-fluid span1 date_all">
				<a href="javascript:;" class="set_day_sort" data-sort="{{day_sort}}">{{day}}</a>
			</div>
			<!-- .date_all -->
			<div class="row-fluid span11 right line_right_box dot_info_wrap">
				<div class="message">
					<!--
                <a href="javascript:;" class="delete"><img src="<?php echo Yii::app()->request->baseUrl;?>/css/agent/images/line/delete.png"></a> 待添加
                -->
					待添加
				</div>
			</div>
			<!-- .right -->
		</div>
		<!-- .spot -->
	</div>
	<!-- .spot_div -->
</div>
<?php // 点的模板 ?>
<div id="dot_tpl" style="display:none;">
	<div class="module one">
		<div class="spot_name">
			<span>{{dot_name}}</span>
			<div class="pull-right edit">
				<a href="javascript:;" class="dot_delete"><img src="<?php echo Yii::app()->request->baseUrl;?>/css/agent/images/line/delete.png"></a>
			</div>
		</div>
		<!-- 添加项目信息 -->
		{{items_info}}
	</div>
</div>
<?php // 项目的模板 ?>
<div id="item_tpl" style="display:none;">
	<div class="row_item_info">
		<div class="row-fluid noe">
			<div class="span1 num">
				<a href="javascript:;">{{item_sort}}</a>
			</div>
			<div class="span10 content">
				<div class="spot_info">
					<div class="row-fluid controls controls-row name">
						<span class="span5">{{item_name}}</span>
						<input type="hidden" name="{{item_input_name}}" value="{{item_input_value}}">
						<div class="span1 pull-right little_tag"><span>{{item_type}}</span></div>
					</div>
					<div class="row-fluid address">
						<span class="span3">地址：</span>
						<span class="span9">{{item_address}}</span>
					</div>
				</div>
				<!-- .spot_info -->
			</div>
			<!-- .content -->
		</div>
		<!-- .noe -->
		<div class="good_info">
			<div class="box_div">
				<div style="visibility:hidden"><span>已选商品</span></div>
				<div class="title"><span>已选商品</span></div>
				<div class="box box_one">
					<ul>
						<!-- add 商品价格信息 -->
						{{item_fares_info}}
					</ul>
				</div>
				<!-- .box -->
			</div>
			<!-- .box_div -->
		</div>
		<!-- .good_info -->
	</div>
</div>
<?php // 吃，玩价格的模板 ?>
<div id="fare_tpl" style="display:none;">
	<li>
		<span>{{fare_name}}</span>
		<span>{{fare_info}}</span>
		<span>{{fare_price}}</span>
		<span><img class="fare_delete" src="<?php echo Yii::app()->request->baseUrl;?>/css/agent/images/line/delete.png"></span>
		<input type="hidden" name="{{fare_input_name}}" value="{{fare_input_value}}">
	</li>
</div>
<?php // 住价格的模板 ?>
<div id="fare_hotel_tpl" style="display:none;">
	<li>
		<span>{{fare_name}}</span>
		<span>{{fare_info}}</span>
		<span>{{fare_number}}</span>
		<span>{{fare_price}}</span>
		<span><img class="fare_delete" src="<?php echo Yii::app()->request->baseUrl;?>/css/agent/images/line/delete.png"></span>
		<input type="hidden" name="{{fare_input_name}}" value="{{fare_input_value}}">
	</li>
</div>