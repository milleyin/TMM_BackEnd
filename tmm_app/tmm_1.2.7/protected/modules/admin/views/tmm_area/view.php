<?php
/* @var $this Tmm_areaController */
/* @var $model Area */

$this->breadcrumbs=array(
	'管理页'=>array('admin'),
	$model->name,
);
?>

<h1>查看 地址信息<font color='#eb6100'><?php echo $model->name; ?></font></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
			'name'=>'id',
		),
		array(
			'name'=>'name',
		),
		array(
			'name'=>'nid',
		),
		array(
			'name'=>'pid',
			'value'=>isset($model->Area_Area_P->name)?CHtml::link($model->Area_Area_P->name,array('/admin/tmm_area/view','id'=>$model->Area_Area_P->id)):'省',
			'type'=>'raw',
		),
		array(
			'name'=>'agent_id',			
			'value'=>isset($model->Area_Agent->phone)?CHtml::link($model->Area_Agent->phone,array('/admin/tmm_agent/view','id'=>$model->Area_Agent->id)):'未分配运营商',
			'type'=>'raw',
		),		
		array(
			'name'=>'admin_id',
			'value'=>isset($model->Area_Admin->username)?CHtml::link($model->Area_Admin->username,array('/admin/tmm_admin/view','id'=>$model->Area_Admin->id)):'热门未设置',
			'type'=>'raw',
		),
		array(
			'name'=>'admin_time',
			'value'=>$model->admin_time == 0 ? '--' : Yii::app()->format->datetime($model->admin_time),
			//'type'=>'datetime',
		),
		array(
			'name'=>'status_hot',	
			'value'=>$model::$_status_hot[$model->status_hot],
		),
		array(
				'name'=>'sort',
		),
	),
)); 
?>