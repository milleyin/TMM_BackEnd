<div class="row-fluid top_line">
	<div class="span7">
		<div class="name">
			<span>
				<?php echo CHtml::encode($data->Thrand_Shops->name);?>
			</span>
		</div>
		<div class="row-fluid address">
			<span class="sapn10">
			<?php			
				if(mb_strlen($data->Thrand_Shops->list_info,'utf-8')>72)
					echo CHtml::encode(mb_substr($data->Thrand_Shops->list_info,0,72,'utf-8')).' ...';
				else
					echo CHtml::encode($data->Thrand_Shops->list_info);
			?>
			</span>
		</div>
	</div>
	<div class="span3">
		<div class="pull-right spot_img">
			<?php 
				echo Yii::app()->controller->dot_list_show_img($data->Thrand_Shops->list_img);
			?>
		</div>
	</div>
	<div class="span2">
		<div class="btn_group">
			<div class="thrand_id" style="display:none;">
			<?php echo CHtml::encode($data->id);?>
			</div>
			<!--
			<a href="<?php echo Yii::app()->createUrl('/admin/tmm_actives/thrand_view_thrand',array('id'=>$data->id));?>" class="choose">
			-->

				<?php
				echo CHtml::ajaxButton('查看',array('/admin/tmm_thrand/view','id'=>$data->id),array(
					'cache'=>true,
					'success'=>'function(html){
											jQuery("#view_thrand_select").html(html);
											$("#view_thrand_select").dialog("open");
										}',
				),array('class'=>'thrand_view_detail'));
				?>
			<?php echo CHtml::button('选择',array('class'=>'choose','thrand_id'=> CHtml::encode($data->id)));?>
		</div>
	</div>
</div>