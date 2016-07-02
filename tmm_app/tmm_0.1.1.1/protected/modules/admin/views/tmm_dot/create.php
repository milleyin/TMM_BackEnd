<?php
/* @var $this Tmm_dotController */
/* @var $model Dot */

$this->breadcrumbs=array(
	'运营商管理页'=>array('/admin/tmm_agent/admin'),
	$model->Dot_Shops->Shops_Agent->phone=>array('/admin/tmm_agent/view','id'=>$model->Dot_Shops->Shops_Agent->id),
	'商品(点)创建页',
);
?>

<h1>创建 商品(点)</h1>

<?php $this->renderPartial('_form', array(					
					'model'=>$model,	
					'dataProvider'=>$dataProvider,
					'model_search'=>$model_search,
)); ?>