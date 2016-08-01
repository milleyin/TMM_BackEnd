<?php
/* @var $this SiteController */
/* @var $error array */

$this->title = Yii::app()->name . ' - 错误';
$this->breadcrumbs = array(
	'错误',
);
?>

<h2>Error <?php echo $error['code']; ?></h2>

<div class="error">
<?php echo CHtml::encode($error['message']); ?>
</div>