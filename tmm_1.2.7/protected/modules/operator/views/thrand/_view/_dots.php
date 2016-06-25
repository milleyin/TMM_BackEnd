<?php 
	$item_arr = Pro::circuit_info($model->Thrand_Pro,'Pro_Thrand_Dot');
	$data_array = isset($item_arr['data_arr']) && $item_arr['data_arr'] ? $item_arr['data_arr'] : array();
	$info_array = isset($item_arr['info_arr']) && $item_arr['info_arr'] ? $item_arr['info_arr'] : array();
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
						<span class="name">景点名称：</span>
						<span><?php echo CHtml::encode($info_array['dot_data'][$dot_name]->name); ?></span>
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
								echo CHtml::link(CHtml::encode($items->Pro_Items->name),array('/operator/' . $items->Pro_Items->Items_ItemsClassliy->admin . '/view', 'id'=>$items->Pro_Items->id));

								if ($items->Pro_Items->status != Items::status_online)
								{
							?>
							<span style="color:red;">已<?php echo  CHtml::encode(Items::$_status[$items->Pro_Items->status]); ?></span>
							<?php 
								}
							?>
							</span>
							<span class="list_classliy" title="<?php echo CHtml::encode($items->Pro_Items->Items_ItemsClassliy->info); ?>">
								<?php echo CHtml::encode($items->Pro_Items->Items_ItemsClassliy->name); ?>
							</span>
				   		</div>
				   		
					  	<div class="list_address">
							<span class="name">地址：</span>
							<?php 
									echo CHtml::encode(
										$items->Pro_Items->Items_area_id_p_Area_id->name. 
										$items->Pro_Items->Items_area_id_m_Area_id->name.
										$items->Pro_Items->Items_area_id_c_Area_id->name. 
										$items->Pro_Items->address
									); 
							?>
					 	</div>
					 	<div class="list_store">
							<label class="name">运营商：</label>
							<span title="运营商公司名称">
								<?php echo CHtml::encode($items->Pro_Items->Items_agent->firm_name); ?>
							</span>
							<span title="运营商账号"> | 
								<?php echo CHtml::encode(Yii::app()->controller->setHideKey($items->Pro_Items->Items_agent->phone)); ?>
							</span>
							<label class="name">
								供应商：
							</label>
							<span title="供应商公司名称">
								<?php echo CHtml::encode($items->Pro_Items->Items_StoreContent->name); ?>
							</span> | 
							<span title="供应商账号">
								<?php echo CHtml::encode(Yii::app()->controller->setHideKey($items->Pro_Items->Items_StoreContent->Content_Store->phone)); ?>
							</span>
					 	</div>
						<div class="list_fare">
							<span class="fare_title" >
									商品信息
							</span>
							 <?php  echo Fare::show_fare($items->Pro_ProFare,$items->Pro_Items->Items_ItemsClassliy->append=="Hotel"?true:false,true); ?>
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
                	<?php echo CHtml::encode($info_array[$key]['info']);?>
                </p>
			</div>
			<div style="clear: both;"></div>
		</div>			
	<?php 
		}
	?>
	</div>
</div>