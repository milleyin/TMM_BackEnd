<table class="table item_box">
	<tbody>
	<tr>
		<th class="s_head">
			<div class="total_title">
				<?php echo CHtml::link(CHtml::encode($data->Dot_Shops->name), array("/agent/agent_".$data->Dot_ShopsClassliy->admin."/view","id"=>$data->id));?>
			</div>
			<div class="row-fluid date">
				<div class="span3">
					<div class="pull-left little_tag"><span>浏</span></div>
					<span class="orderid"><?php echo CHtml::encode($data->Dot_Shops->brow); ?></span>
				</div>
				<div class="span3">
					<div class="pull-left little_tag"><span>分</span></div>
					<span class="orderid"><?php echo CHtml::encode($data->Dot_Shops->share); ?></span>
				</div>
				<div class="span3">
					<div class="pull-left little_tag"><span>赞</span></div>
					<span class="orderid"><?php echo CHtml::encode($data->Dot_Shops->praise); ?></span>
				</div>
				<span class="datetime"><?php echo CHtml::encode( date('Y年m月d日', $data->Dot_Shops->pub_time)); ?></span>
			</div>  <!-- .date -->
		</th>
	</tr>
<?php foreach($data->Dot_Pro as $pro) { ?>

	<tr>
		<td>
			<div class="row-fluid">
				<div class="span8 spot_info">
					<div class="row-fluid controls controls-row name">
						<span class="span5" title="<?php echo CHtml::encode($pro->Pro_Items->name);?>"><?php echo CHtml::link(CHtml::encode(mb_substr($pro->Pro_Items->name,0,5,'utf-8').' ...'),array('/agent/agent_'.$pro->Pro_ItemsClassliy->admin.'/view','id'=>$pro->Pro_Items->id)); ?></span>
						<?php 
							if($pro->Pro_Items->status != Items::status_online)
							{
						?>
						<span style="color: red">（已<?php echo Items::$_status[$pro->Pro_Items->status];?>）</span>
						<?php 
							}
						?>
						<div class="span1 pull-right little_tag">
							<span><?php echo CHtml::encode($pro->Pro_ItemsClassliy->name); ?></span>
						</div>
					</div>
					<div class="row-fluid address">
						<span class="span3">地址：</span>
						<span class="span9">
							<?php echo CHtml::encode($pro->Pro_Items->Items_area_id_p_Area_id->name).
								CHtml::encode($pro->Pro_Items->Items_area_id_m_Area_id->name).
								CHtml::encode($pro->Pro_Items->Items_area_id_c_Area_id->name).
								CHtml::encode($pro->Pro_Items->Items_StoreContent->address);
							?>
						</span>
					</div>
				</div>
				<div class="span3 pull-right spot_img">
					<?php
					if(isset($pro->Pro_Items->Items_ItemsImg['0']))
						echo CHtml::link($this->show_img($pro->Pro_Items->Items_ItemsImg['0']->img),array('/agent/agent_'.$pro->Pro_ItemsClassliy->admin.'/view','id'=>$pro->Pro_Items->id));
					?>
				</div>
			</div>
		</td>
	</tr>
<?php }?>
	<tr class="last">
		<td>
			<div class="check_failed">
				<div class="row-fluid btn_group">
					<a href="<?php echo Yii::app()->createUrl("/agent/agent_shops/delete",array("id"=>$data->id)); ?>" class="delete">删除</a>
					<a href="<?php echo Yii::app()->createUrl("/agent/agent_dot/update",array("id"=>$data->id)); ?>">编辑</a>
				</div>
			</div>
		</td>
	</tr>
	</tbody>
</table>  <!-- .table -->