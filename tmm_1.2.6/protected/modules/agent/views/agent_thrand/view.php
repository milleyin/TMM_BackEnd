<div class="content_box scenic_spot line_detail_pack" id="spot_detail_pack">
	<?php
	echo $this->breadcrumbs(array(
		'线'=>array('/agent/agent_thrand/admin'),
		'线详情',
		CHtml::encode($model->Thrand_Shops->name)
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
							<td>线名称：</td>
							<td><?php echo CHtml::encode($model->Thrand_Shops->name); ?></td>
						</tr>
						<tr>
							<td>下单量：</td>
							<td><?php echo CHtml::encode($model->Thrand_Shops->brow); ?></td>
						</tr>
						<tr>
							<td>浏览量：</td>
							<td><?php echo CHtml::encode($model->Thrand_Shops->brow); ?></td>
						</tr>
						<tr>
							<td>分享量：</td>
							<td><?php echo CHtml::encode($model->Thrand_Shops->share); ?></td>
						</tr>
						<tr>
							<td>点赞量：</td>
							<td><?php echo CHtml::encode($model->Thrand_Shops->praise); ?></td>
						</tr>
						<tr>
							<td>审核状态：</td>
							<td class="<?php echo  $model->Thrand_Shops->audit == Shops::audit_pass ? 'state_normal' : 'state' ?>">
								<?php echo CHtml::encode(Shops::$_audit[$model->Thrand_Shops->audit]); ?>
							</td>
						</tr>
						<tr>
							<td>发布状态：</td>
							<td class="<?php echo  $model->Thrand_Shops->status == Shops::status_online ? 'state_normal' : 'state' ?>">
								<?php echo CHtml::encode(Shops::$_status[$model->Thrand_Shops->status]); ?>
							</td>
						</tr>
						<tr>
							<td>线标签：</td>
							<td>
								<?php  foreach($model->Thrand_TagsElement as $v){ ?>
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
									if($model->Thrand_Shops->list_img)
										echo $this->show_img($model->Thrand_Shops->list_img);									?>
								</div>
								<div class="intro">
									<?php echo CHtml::encode($model->Thrand_Shops->list_info); ?>
								</div>
							</td>
						</tr>
						<tr>
							<td class="text_top">详情页显示：</td>
							<td class="brief_intro">
								<div class="brief_intro_img">
									<?php
									if($model->Thrand_Shops->page_img)
										echo $this->show_img($model->Thrand_Shops->page_img);
									?>
								</div>
								<div class="intro">
									<?php echo CHtml::encode($model->Thrand_Shops->page_info); ?>
								</div>
							</td>
						</tr>
						<tr>
							<td>发布时间：</td>
							<td><?php echo CHtml::encode(date('Y-m-d H:i:s' ,$model->Thrand_Shops->pub_time)); ?></td>
						</tr>
						</tbody>
					</table>
				</div>
			</div>
			<!--  .box -->
		</div>
		<!-- .box_div -->

		<?php
		if(isset($model->Thrand_Pro))
			$this->renderPartial('_items_view', array(
				'model'=>$model,
			));
		?>

		<div class="copyright">
			<span>Copyright &copy; TMM365.com All Rights Reserved</span>
		</div>
		<!--.copyright-->
	</div>
</div>
	<!--.content_div-->