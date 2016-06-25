<?php 
	foreach ($model->Order_OrderShops as $order_shops)
	{
?>
<div class="items_container">
	<?php 
		foreach ($order_shops->OrderShops_OrderItems as $key=>$data)
		{ 
	?>
		<div class="view">
			<div class="list_left">
				<div class="list_title">
					<span class="list_name">
						<?php 
							echo '<strong style="font-style: italic;">',$key+1,'：</strong>';
							echo CHtml::link($data->items_name, array('/operator/'.$data->OrderItems_ItemsClassliy->admin.'/view', 'id'=>$data->items_id));
						?>
					</span>					
					 | 
					<span class="name">
							状态：
					</span>
					<span title="接单状态">
                         <?php  echo CHtml::encode(OrderItems::$_is_shops[$data->is_shops]); ?>
                     </span>
                          |     
					<span class="name">
						消费状态：
					</span>
					 <span title="<?php echo $data->employ_time==0 ? '' : date('Y-m-d H:i:s', $data->employ_time); ?>">
                             <?php echo CHtml::encode(OrderItems::$_is_barcode[$data->is_barcode]); ?>
                      </span>	
                          |
                     <span title="是否免费">
                           <?php echo CHtml::encode(Items::$_free_status[$data->items_free_status]); ?>
                     </span>
			
					<span class="list_classliy" title="<?php echo CHtml::encode($data->OrderItems_ItemsClassliy->info); ?>">
						<?php echo CHtml::encode($data->items_c_name); ?>
					</span>
				</div>
				<div class="list_address">
					<span>
						地址:
					</span>
					<?php echo CHtml::encode($data->items_address); ?>
				</div>
				<div class="list_store">
					<label class="label_title">
						运营商:
					</label>
					<span title="运营商公司名称">
						<?php echo CHtml::encode($data->OrderItems_Agent->firm_name); ?>
					</span>
					<span title="运营商账号">
						<?php echo CHtml::encode(Yii::app()->controller->setHideKey($data->OrderItems_Agent->phone)); ?>
					</span>
					<label class="label_title">
						供应商:
					</label>
					<span title="供应商公司名称">
						<?php echo CHtml::encode($data->OrderItems_StoreUser->Store_Content->name); ?>
					</span>
					<span title="供应商账号">
						<?php echo CHtml::encode(Yii::app()->controller->setHideKey($data->OrderItems_StoreUser->phone)); ?>
					</span>
				</div>
				<div class="list_fare">
					<span class="fare_title">
						商品信息
					</span>
					<?php echo Fare::show_order_organizer_fare($data, $data->items_c_id == Items::items_hotel ? true : false); ?>
				</div>
			</div>
			<div class="list_right">
				<h6>
					<?php echo $data->getAttributeLabel('shops_info');?>
				</h6>
				<?php echo CHtml::encode($data->shops_info);?>
			</div>
			<div style="clear: both;">
			</div>
		</div>
		<?php } ?>
</div>
<?php 
	}
?>