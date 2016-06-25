
<div class="view" >
		<div class="list_left" >
				<div class="list_title">
					<span class="list_name"><?php echo CHtml::encode($data->name);?></span>
					<span class="list_classliy"  title="<?php echo  CHtml::encode($data->Items_ItemsClassliy->info);?>">
						<?php echo CHtml::encode($data->Items_ItemsClassliy->name);?>
					</span>
				</div>
				<div class="list_address">	
					<span>地址:</span>
						<?php echo CHtml::encode(
										$data->Items_area_id_p_Area_id->name.
										$data->Items_area_id_m_Area_id->name.
										$data->Items_area_id_c_Area_id->name.
										$data->address);
						 ?>
				</div>
				<div class="list_store">
					<span title="<?php echo CHtml::encode($data->Items_StoreContent->Content_Store->phone)?>">所属供应商:</span><?php echo CHtml::encode($data->Items_StoreContent->name); ?>
				</div>
		</div>
		<div class="list_img" >
		   <?php
		   		$img='';
				if(isset($data->Items_ItemsImg[0]))	
					$img=$data->Items_ItemsImg[0]->img;
				echo Yii::app()->controller->show_img($img);
		   ?>
		</div>
		<div class="list_id" style="display: none">
			<?php echo CHtml::hiddenField('Pro[][items_id]',$data->id,array('id'=>false))?>
		</div>	
		<div class="list_right" >
			<div class="list_eq" style="display: none"><?php echo $index;?></div>
			<div class="list_button_add">
				<?php  
					echo CHtml::button('选择',array('class'=>'select_items'));
				?>
			</div>
			<div class="list_button_view">
				<?php		
					echo CHtml::ajaxButton('查看',array('/admin/tmm_'.$data->Items_ItemsClassliy->admin.'/view','id'=>$data->id),array(
									'cache'=>true,
									'success'=>'function(html){
										jQuery("#view_items").html(html);
										$("#view_items").dialog("open");
									}',
							));
					?>
			</div>
		</div>
		<div style="clear: both;"></div>
		<div class="list_fare">
			<span>商品信息</span>
			 <?php  echo Fare::show_fare($data->Items_Fare,$data->Items_ItemsClassliy->append=='Hotel'?true:false); ?>
		</div>
</div>