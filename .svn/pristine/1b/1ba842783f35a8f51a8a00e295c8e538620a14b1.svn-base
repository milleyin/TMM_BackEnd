<div class="row-fluid top_line">
	<div class="copy_div">
		<div class="span8 spot_info">
			<div class="row-fluid controls controls-row name">
				<span class="span5"><?php echo CHtml::encode($data->name);?></span>
				<div class="span1 pull-right little_tag"><span><?php echo CHtml::encode($data->Items_ItemsClassliy->name);?></span></div>
			</div>
			<div class="row-fluid address">
				<span class="text">地址：</span>
				<span>
					<?php echo CHtml::encode(
						$data->Items_area_id_p_Area_id->name.
						$data->Items_area_id_m_Area_id->name.
						$data->Items_area_id_c_Area_id->name.
						$data->address);
					?>
				</span>
			</div>
			<div class="row-fluid belong_business">
				<span class="text">所属商家：</span>
				<span><?php echo CHtml::encode($data->Items_StoreContent->name); ?></span>
			</div>
		</div>
		<div class="pull-right spot_img">
			<?php
			$img='';
			if(isset($data->Items_ItemsImg[0]))
				$img=$data->Items_ItemsImg[0]->img;
				echo Yii::app()->controller->show_img($img,'','',array('style'=>'width:130px;height:60px;'));
			?>
		</div>
		<div class="list_id" style="display: none">
			<?php echo CHtml::hiddenField('Pro[][items_id]',$data->id,array('id'=>false))?>
		</div>
	</div>
	<div class="good_info">
		<div class="box_div">
			<div class="list_eq" style="display: none"><?php echo $index;?></div>
			<div style="visibility:hidden"><span>商品信息</span></div>
			<div class="title"><span>商品信息</span></div>
			<div class="box box_one">
				<?php  echo Fare::show_fare_ul($data->Items_Fare,$data->Items_ItemsClassliy->append=='Hotel'?true:false); ?>
			</div> <!--  .box -->
		</div>   <!-- .box_div -->
	</div>    <!-- .good_info -->
	<div class="btn_group">
		<?php
		echo CHtml::button('选择',array('class'=>'choose'));
		?>
		<?php
		echo CHtml::ajaxButton('查看',array('/agent/agent_'.$data->Items_ItemsClassliy->admin.'/view','id'=>$data->id),array(
			'cache'=>true,
			'success'=>'function(html){
										jQuery("#view_items").html(html);
										$("#view_items").dialog("open");
										return false;
									}',
		),array('class'=>'detail'));
		?>
	</div>
</div> <!-- .row-fluid -->