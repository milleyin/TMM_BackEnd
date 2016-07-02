<div class="content_box">
	<?php
	echo $this->breadcrumbs(array(
		'自助游'=>array('admin'),
		'订单详情',
	));
	?>
	<div class="table_boxs">
		<table border="0" class="de">
			<tr>
				<td>订单号：</td><td><?php echo CHtml::encode($model->order_no); ?></td>
			</tr>
			<tr>
				<td>下单用户：</td><td><?php echo CHtml::encode($model->Order_User->phone); ?></td>
			</tr>
			<tr>
				<td>项目费用：</td><td><?php echo CHtml::encode($this->money_floor($model->order_price - $model->son_order_count, 2, 'floor')); ?>元</td>
			</tr>
			<tr>
				<td>保险费用：</td><td><?php echo CHtml::encode($model->son_order_count); ?>元</td>
			</tr>
			<tr>
				<td>总价：</td><td><?php echo CHtml::encode($model->order_price); ?>元</td>
			</tr>
			<tr>
				<td>我的收益：</td><td><?php echo $item_total_money; ?>元</td>
			</tr>
			<tr>
				<td>订单状态：</td><td class="state_normal"><?php echo CHtml::encode(Order::$_order_status[$model->order_status]); ?></td>
			</tr>
			<tr>
				<td>下单时间：</td><td><?php echo CHtml::encode(date('Y-m-d H:i:s',$model->add_time));?></td>
			</tr>
			<tr>
				<td>支付时间：</td><td><?php echo CHtml::encode(date('Y-m-d H:i:s',$model->pay_time));?></td>
			</tr>
		</table>
	</div>

	<div class="de1">
		<span>随行人员</span>

		<table border="0" width="100%">

			<tr>
				<td>姓名</td>
				<td>性别</td>
				<td>身份证号码</td>
				<td>手机号</td>
			</tr>
			<?php  foreach($model->Order_OrderRetinue as $retinue){ ?>
				<tr>
					<td>
						<?php if($retinue->is_main==1){ ?>
							<div id="zhu">主</div>
						<?php }?>
							<?php echo CHtml::encode($retinue->retinue_name); ?>
					</td>
					<td><?php echo CHtml::encode(OrderRetinue::$_retinue_gender[$retinue->retinue_gender]); ?></td>
					<td><?php echo CHtml::encode($retinue->retinue_identity); ?></td>
					<td><?php echo CHtml::encode($retinue->retinue_phone); ?></td>
				</tr>
			<?php } ?>
		</table>

	</div>
	<div class="clear"></div>

	<!--已购买项目内容-->
	<div class="charts">
		<div class="gou">
			<div class="title">已购买的我的项目</div>
			<div class="line"></div>
			<div class="de2">
				<?php 	foreach($model->Order_OrderShops as $k=>$val) { ?>
					<div>
						<p><?php echo CHtml::encode($val->shops_name); ?></p><hr>
						<?php foreach($val->OrderShops_OrderItems as $key=>$order_items){ ?>
							<div class="div">
							<div class="gou_chi_1">
								<div class="tutu"><?php echo $key+1; ?></div>
								<div class="gou_top">
									<ul>
										<li><?php echo CHtml::encode($order_items->items_name); ?></li>
										<li>所属商家：<?php echo CHtml::encode($order_items->OrderItems_StoreUser->Store_Content->name); ?></li>
										<li>地址：<?php echo CHtml::encode($order_items->items_address); ?></li>
										<li>消费时间：<?php echo date('Y-m-d H:i:s',$order_items->employ_time); ?></li>
									</ul>
								</div>
							</div>
							<div class="gou_chi_2">
								<div class="good_info">
									<div class="box_div">
										<div style="visibility:hidden"><span>已选商品</span></div>
										<div class="title2"><span>已选商品</span></div>
										<div class="box box_one">
											<ul>
												<?php foreach($order_items->OrderItems_OrderItemsFare as $fare) {?>
													<li>
														<spn><?php echo CHtml::encode($fare->fare_name); ?></spn>
														<span><?php echo CHtml::encode($fare->fare_info); ?></span>
														<span><?php echo CHtml::encode($fare->fare_price); ?>元</span>
														<span><?php echo CHtml::encode($fare->fare_number); ?>份</span>
													</li>
												<?php } ?>
											</ul>
										</div> <!--  .box -->
									</div>   <!-- .box_div -->
								</div>    <!-- .good_info -->
							</div><!--gou_chi_2-->
							<div class="clear"></div>
						</div>
						<?php } ?>
					</div>
				<?php } ?>
			</div>
		</div>		<!--.login_record_chartone-->
	</div>
<!--版权 -->
	<div style="text-align: center;">
		<span>Copyright © TMM365.com All Rights Reserved</span>
	</div>
</div><!--content_box-->
