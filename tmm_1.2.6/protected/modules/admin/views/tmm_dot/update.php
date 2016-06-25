<?php
/* @var $this Tmm_dotController */
/* @var $model Dot */

$this->breadcrumbs=array(
	'线路管理页'=>array('/admin/tmm_shops/admin'),
	'线路(点)管理页'=>array('admin'),
	$model->Dot_Shops->name=>array('view','id'=>$model->id),
	'更新页',
);

?>

<h1>更新页 线路(点)<font color='#eb6100'><?php echo CHtml::encode($model->Dot_Shops->name); ?></font></h1>

<?php $this->renderPartial('_form', array(					
					'model'=>$model,	
					'dataProvider'=>$dataProvider,
					'model_search'=>$model_search,
			)); 
?>