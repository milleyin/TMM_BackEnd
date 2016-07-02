<?php 
	$this->breadcrumbs = array(
			Yii::app()->operator->name=>array('/operator/agent/own'),
			'我的银行卡',
	);
	if ($model)
	{
?>
<h1>查看我的银行卡 <font color='#eb6100'><?php echo CHtml::encode($model->bank_name) ?></font></h1>

	<?php 
	$this->widget('zii.widgets.CDetailView', array(
		'data'=>$model,
		'attributes'=>array(
			array(
					'name'=>'bank_id',
					'value'=>$model->BankCard_Bank->name,
			),
			array(
					'name'=>'bank_branch',
			),
			array(
					'name'=>'bank_name',
			),
			array(
					'name'=>'bank_code',
			),
			array(
					'name'=>'add_time',
					'type'=>'datetime',
			),
		),
	));
	?>
	<div class="row" style="margin:50px;">
		<span class="buttons">
			<?php	echo CHtml::Button('更换银行卡', array("onclick"=>"location.href='" . Yii::app()->createUrl('/operator/bankCard/create') . "'"));?>
		</span>
	</div>
<?php
	}
	else
	{	
?>
<div style="background: #E5F1F4;padding:40px;margin-bottom:30px;font-size: 22px;border-radius: 10px;">
	<strong>
			还没有绑定银行卡，
	</strong>
	<span  style="font-size: 24px;letter-spacing:2px;color:#ED1C24;">
		<?php echo CHtml::link('立即绑定', array('/operator/bankCard/create')); ?>
	</span>
</div>
<div class="row" style="margin:0 50px;">
	<span class="buttons">
		<?php	echo CHtml::Button('立即绑定', array("onclick"=>"location.href='" . Yii::app()->createUrl('/operator/bankCard/create') . "'"));?>
	</span>
</div>
<?php } ?>