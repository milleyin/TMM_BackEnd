<?php
$this->pageTitle=Yii::app()->name . ' - 错误页';
?>

<div class="content_box" style="padding:20px 20px 200px 20px">
	<?php echo $this->breadcrumbs(
			array(
				'错误页'
			),
			CHtml::link('首页',array('/agent'),array('target'=>"_parent"))
	);?>
	<h2>错误 <?php echo $code; ?></h2>
	<div class="error" style="color:red;">
			<?php echo CHtml::encode($message); ?>
			如有问题请联系管理员！
	</div>
</div>

