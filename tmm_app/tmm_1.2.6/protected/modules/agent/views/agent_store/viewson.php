<div class="content_box">
	<div class="head">
		<?php
			echo $this->breadcrumbs(array(
					'商家子账号'=>array('adminSon'), 
					'账号详情',
					$model->phone,
			));
		?>
	</div>  <!--.head-->
	<div class="box_div">
		<div style="visibility:hidden"><span>账号详情</span></div>
		<div class="title"><span>账号详情</span></div>
		<div class="box box_one">
			<div class="box_left">
				<img src="<?php echo Yii::app()->request->baseUrl;?>/css/agent/images/head.png" class="head_img">
			</div>  <!-- .box_left -->
			<div class="box_right">
				<div class="account_table">
					<table border="0" class="account_info">
						<tbody>
						<tr>
							<td>ID：</td>
							<td><?php echo CHtml::encode($model->id); ?></td>
						</tr>
						<tr>
							<td>登录手机号：</td>
							<td><?php echo CHtml::encode($model->phone); ?></td>
						</tr>
						<tr>
							<td>归属商家：</td>
							<td><?php echo CHtml::encode($model->Store_Store->Store_Content->name).'︱'.CHtml::encode($model->Store_Store->phone);?></td>
						</tr>
						<tr>
							<td>项目数：</td>
							<td><?php echo CHtml::encode($model->Store_Items_Manage_Count); ?></td>
						</tr>
						<tr>
							<td>订单量：</td>
							<td><?php echo CHtml::encode('待查'); ?></td>
						</tr>
						<tr>
							<td>账号状态：</td>
							<td class="<?php echo $model->status==1?"state_normal":'state';?>"><?php echo CHtml::encode($model::$_status[$model->status]); ?></td>
						</tr>
						<tr>
							<td>注册时间：</td>
							<td><?php echo CHtml::encode(date('Y-m-d H:i:s' ,$model->add_time)); ?></td>
						</tr>
						<tr>
							<td>登录次数：</td>
							<td><?php echo CHtml::encode($model->count); ?></td>
						</tr>
						<tr>
							<td>最后登录时间：</td>
							<td><?php echo CHtml::encode(date('Y-m-d H:i:s' ,$model->up_time)); ?></td>
						</tr>
						<tr>
						</tbody>
					</table>
				</div>
			</div>   <!-- .box_right -->
		</div> <!--  .box -->
	</div>   <!-- .box_div -->
