<div class="row-fluid top_line">
	<div class="span7">
		<div class="name">
			<span>
				<?php echo CHtml::encode($data->Dot_Shops->name);?>
			</span>
		</div>
		<div class="row-fluid address">
			<span class="sapn10">
			<?php			
				if(mb_strlen($data->Dot_Shops->list_info,'utf-8')>72)
					echo CHtml::encode(mb_substr($data->Dot_Shops->list_info,0,72,'utf-8')).' ...';
				else
					echo CHtml::encode($data->Dot_Shops->list_info);
			?>
			</span>
		</div>
	</div>
	<div class="span3">
		<div class="pull-right spot_img">
			<?php 
				echo Yii::app()->controller->dot_list_show_img($data->Dot_Shops->list_img);
			?>
		</div>
	</div>
	<div class="span2">
		<div class="btn_group">
			<div class="dot_id" style="display:none;">
			<?php echo CHtml::encode($data->id);?>
			</div>
			    <a href="<?php echo Yii::app()->createUrl('/admin/tmm_actives/dot_view_dot',array('id'=>$data->id));?>" class="choose">

				选择
			</a>
		</div>
	</div>
</div>