<?php 
$this->pageTitle=isset($this->name) ? $this->name : Yii::app()->name . ' - 权限错误';
$this->breadcrumbs=array(
	'权限错误',
);
?>
<?php if(isset($ajax)){?>
	<?php echo Yii::app()->params['admin_srbac_ajax']; Yii::app()->end();?>
<?php }else{?>
	<h2 style="color:red">
		<?php echo "Error:".$error["code"]." '".$error["title"]."'" ?>
	</h2>
	<p>
	  	<?php echo $error["message"] ?>
	</p>
<?php }?>