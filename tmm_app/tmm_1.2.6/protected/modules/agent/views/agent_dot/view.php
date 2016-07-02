<div class="content_box scenic_spot" id="spot_detail_pack">
	<?php
	echo $this->breadcrumbs(array(
		'点'=>array('/agent/agent_dot/admin'),
		'点详情',
		$model->Dot_Shops->name,
	));
	?><!--.title-->
	<div class="content_div">
		<div class="box_div">
			<div style="visibility:hidden"><span>详情介绍</span></div>
			<div class="title"><span>详情介绍</span></div>
			<div class="box">
				<div class="account_table">
					<table border="0" class="account_info">
						<tbody>
						<tr>
							<td>点名称：</td>
							<td><?php echo CHtml::encode($model->Dot_Shops->name); ?></td>
						</tr>
						<tr>
							<td>浏览量：</td>
							<td><?php echo CHtml::encode($model->Dot_Shops->brow); ?></td>
						</tr>
						<tr>
							<td>分享量：</td>
							<td><?php echo CHtml::encode($model->Dot_Shops->share); ?></td>
						</tr>
						<tr>
							<td>点赞量：</td>
							<td><?php echo CHtml::encode($model->Dot_Shops->praise); ?></td>
						</tr>
						<tr>
							<td>审核状态：</td>
							<td class="state_normal"><?php echo CHtml::encode(Shops::$_audit[$model->Dot_Shops->audit]); ?></td>
						</tr>
						<tr>
							<td>发布状态：</td>
							<td class="state_normal"><?php echo CHtml::encode(Shops::$_status[$model->Dot_Shops->status]); ?></td>
						</tr>
						<tr>
							<td>点标签：</td>
							<td>
								<?php  foreach($model->Dot_TagsElement as $v){ ?>
									<div class="tag_img one">
										<img src="<?php echo Yii::app()->request->baseUrl; ?>/css/agent/images/tag_bg.png"><span><?php echo CHtml::encode($v->TagsElement_Tags->name); ?></span>
									</div>
								<?php } ?>
							</td>
						</tr>
						<tr>
							<td class="text_top">列表页显示：</td>
							<td class="brief_intro">
								<div class="brief_intro_img">
									<?php
										if($model->Dot_Shops->list_img)
											echo $this->show_img($model->Dot_Shops->list_img);
									?>
								</div>
								<div class="intro">
									<?php echo CHtml::encode($model->Dot_Shops->list_info); ?>
								</div>
							</td>
						</tr>
						<tr>
							<td class="text_top">详情页显示：</td>
							<td class="brief_intro">
								<div class="brief_intro_img">
									<?php
										if($model->Dot_Shops->page_img)
											echo $this->show_img($model->Dot_Shops->page_img);
									?>
								</div>
								<div class="intro">
									<?php echo CHtml::encode($model->Dot_Shops->page_info); ?>
								</div>
							</td>
						</tr>
						<tr>
							<td>发布时间：</td>
							<td><?php echo CHtml::encode(date('Y-m-d H:i:s' ,$model->Dot_Shops->pub_time)); ?></td>
						</tr>
						</tbody>
					</table>
				</div>
			</div> <!--  .box -->
		</div>   <!-- .box_div -->

		<?php
			if(isset($model->Dot_Pro))
				$this->renderPartial('_items_view', array(
					'model'=>$model,
				));
		?>
</div>  <!--.content_div-->
</div>