<?php
/* @var $this AccountController */
/* @var $model Account */

$this->breadcrumbs = array(
	Yii::app()->operator->name=>array('/operator/agent/own'),
	'我的钱包',
);
?>

<h1>查看我的钱包 ：<font color='#eb6100'><?php echo CHtml::encode(Yii::app()->operator->name) ?></font></h1>

<div style="background: #E5F1F4;padding:40px;margin:50px;font-size: 22px;border-radius: 10px;">
	<div class="list" style="padding:0 0 5px 0;">
		<strong>
				<?php //echo $model->getAttributeLabel("count"); ?>
				收益总额：
		</strong>
		<span class="money" style="font-size: 24px;letter-spacing:2px;color:#ED1C24;">
			<?php echo $model->count;?>
		</span> （元）
	</div>
	<div class="list" style="padding:0 0 5px 0;">
		<strong>
				<?php //echo $model->getAttributeLabel("no_money"); ?>
				冻结金额：
		</strong>
		<span class="money" style="font-size: 24px;letter-spacing:2px;color:#ED1C24;">
			<?php echo $model->no_money;?>
		</span> （元）
	</div>
	<div class="list" style="padding:0 0 5px 0;;">
		<strong>
				<?php //echo $model->getAttributeLabel("cash_count"); ?>
				提现总额：
		</strong>
		<span class="money" style="font-size: 24px;letter-spacing:2px;color:#ED1C24;">
			<?php echo $model->cash_count;?>
		</span> （元）
	</div>
	<div class="list" >
		<strong>
				<?php echo $model->getAttributeLabel("money"); ?>：
		</strong>
		<span class="money" style="font-size: 24px;letter-spacing:2px;color:#ED1C24;">
			<?php echo $model->money;?>
		</span> （元）
	</div>
</div>

<div class="row" style="margin:0 50px;">
	<span class="buttons">
		<?php	echo CHtml::Button('提现', array("onclick"=>"location.href='" . Yii::app()->createUrl('/operator/cash/create') . "'"));?>
	</span>
</div>

<?php 
Yii::app()->clientScript->registerScript('view', "
  	var pB=new probarBox();
	$('.money').each(function() {
		pB.loadImg($(this).html(), $(this)[0]);
	});
");
?>
