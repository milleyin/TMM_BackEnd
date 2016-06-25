<?php 
	foreach ($model->Order_OrderShops as $order_shops)
	{
		$data_array = array();
		$info_array = array();
		foreach ($order_shops->OrderShops_OrderItems as $value)
		{
			$data_array[$value->shops_day_sort][$value->shops_half_sort][$value->shops_dot_id][$value->shops_sort] = $value;
			if ($value->shops_half_sort==0 && $value->shops_sort==0)
				$info_array[$value->shops_day_sort]['shops_info'] = $value->shops_info;
		}
?>
<div id="thrand_content">
	<div class="thrand_title">
		<span>行程安排</span>
	</div>
	<div class="day_list">
	<?php
		foreach ($data_array as $key=>$data_dot_sort)
		{
	?>
		<div class="day_info">
			<div class="day_content">
				<span><?php echo CHtml::encode(Pro::item_swithc($key)); ?></span>
			</div>
			<div class="dot_list">
		<?php
	        foreach ($data_dot_sort as $data_dot)
			{
	        	foreach ($data_dot as $dot_name => $data_items)
				{
	   	?>
				<div class="dot_info">
					<div class="dot_content">
						<span class="name">景点ID：</span>
						<span><?php echo CHtml::encode($dot_name); ?></span>
					</div>
					<div class="item_list">
					<?php 
					foreach ($data_items as $sort => $items) 
					{
					?>
						<div class="item_info">
						<div class="list_title">
							<span class="list_name">
							<strong style="font-style: italic;"><?php echo CHtml::encode($sort+1);?>：</strong>
							<?php 		
								echo CHtml::link(CHtml::encode($items->items_name),array('/operator/' . $items->OrderItems_ItemsClassliy->admin . '/view', 'id'=>$items->items_id));
							?>
							</span> | 
							<span class="name">
							状态：
							</span>
						    <span title="接单状态">
                                    <?php  echo CHtml::encode(OrderItems::$_is_shops[$value->is_shops]); ?>
                             </span>
                               |     
							<span class="name">
								消费状态：
							</span>
							 <span title="<?php echo $items->employ_time==0 ? '' : date('Y-m-d H:i:s', $items->employ_time); ?>">
                                        <?php echo CHtml::encode(OrderItems::$_is_barcode[$items->is_barcode]); ?>
                             </span>	
                             |
                         	<span title="是否免费">
                                    <?php echo CHtml::encode(Items::$_free_status[$items->items_free_status]); ?>
                             </span>
                             
							<span class="list_classliy" title="<?php echo CHtml::encode($items->OrderItems_ItemsClassliy->info); ?>">
								<?php echo CHtml::encode($items->items_c_name); ?>
							</span>
				   		</div>
				   		
					  	<div class="list_address">
							<span class="name">地址：</span>
							<?php 
									echo CHtml::encode($items->items_address); 
							?>
					 	</div>
					 	<div class="list_store">
							<label class="name">运营商：</label>
							<span title="运营商公司名称">
								<?php echo CHtml::encode($items->OrderItems_Agent->firm_name); ?>
							</span>
							<span title="运营商账号"> | 
								<?php echo CHtml::encode(Yii::app()->controller->setHideKey($items->OrderItems_Agent->phone)); ?>
							</span>
							<label class="name">
								供应商：
							</label>
							<span title="供应商公司名称">
								<?php echo CHtml::encode($items->OrderItems_StoreUser->Store_Content->name); ?>
							</span> | 
							<span title="供应商账号">
								<?php echo CHtml::encode(Yii::app()->controller->setHideKey($items->OrderItems_StoreUser->phone)); ?>
							</span>
					 	</div>
						<div class="list_fare">
							<span class="fare_title" >
									商品信息
							</span>
							<?php echo Fare::show_order_organizer_fare($items, $items->items_c_id == Items::items_hotel ? true : false); ?>
						</div>
						</div>
				<?php 
					}
				?>
					</div>			
				</div>
		<?php 
				}
			}
		?>
			</div>
			<div class="day_right">
			     <h4>日程亮点：</h4>
                <p>
                	<?php echo CHtml::encode($info_array[$key]['shops_info']);?>
                </p>
			</div>
			<div style="clear: both;"></div>
		</div>			
	<?php 
		}
	?>
	</div>
</div>
<?php 
	}
?>