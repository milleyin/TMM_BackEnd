<div class="items_container">
	<?php 
		foreach ($model->Dot_Pro as $key=>$data)
		{ 
	?>
		<div class="view">
			<div class="list_left">
				<div class="list_title">
					<span class="list_name">
						<?php 
							echo '<strong style="font-style: italic;">',$key+1,'：</strong>';
							echo CHtml::link($data->Pro_Items->name, array('/operator/'.$data->Pro_ItemsClassliy->admin.'/view', 'id'=>$data->Pro_Items->id));
							if($data->Pro_Items->status != Items::status_online) 
								echo '<span style="color: red">（已'. Items::$_status[$data->Pro_Items->status].')</span>'; 
						?>
					</span>
					<span class="list_classliy" title="<?php echo CHtml::encode($data->Pro_ItemsClassliy->info); ?>">
						<?php echo CHtml::encode($data->Pro_ItemsClassliy->name); ?>
					</span>
				</div>
				<div class="list_address">
					<span>
						地址:
					</span>
					<?php 
						echo CHtml::encode(
							$data->Pro_Items->Items_area_id_p_Area_id->name. 
							$data->Pro_Items->Items_area_id_m_Area_id->name.
							$data->Pro_Items->Items_area_id_c_Area_id->name. 
							$data->Pro_Items->address
						); 
					?>
				</div>
				<div class="list_store">
					<label class="label_title">
						运营商:
					</label>
					<span title="运营商公司名称">
						<?php echo CHtml::encode($data->Pro_Items->Items_agent->firm_name); ?>
					</span>
					<span title="运营商账号">
						<?php echo CHtml::encode(Yii::app()->controller->setHideKey($data->Pro_Items->Items_agent->phone)); ?>
					</span>
					<label class="label_title">
						供应商:
					</label>
					<span title="供应商公司名称">
						<?php echo CHtml::encode($data->Pro_Items->Items_StoreContent->name); ?>
					</span>
					<span title="供应商账号">
						<?php echo CHtml::encode(Yii::app()->controller->setHideKey($data->Pro_Items->Items_StoreContent->Content_Store->phone)); ?>
					</span>
				</div>
				<div class="list_fare">
					<span class="fare_title">
						商品信息
					</span>
					<?php echo Fare::show_fare($data->Pro_Items->Items_Fare,$data->Pro_ItemsClassliy->append=='Hotel' ? true : false);?>
				</div>
			</div>
			<div class="list_right">
				<h6>
					<?php echo $data->getAttributeLabel('info');?>
				</h6>
				<?php echo CHtml::encode($data->info);?>
			</div>
			<div style="clear: both;">
			</div>
		</div>
		<?php } ?>
</div>