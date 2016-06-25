<?php
/* @var $this Tmm_attendController */
/* @var $model Attend */

$this->breadcrumbs=array(
	'活动（代付）报名管理页'=>array('admin'),
	$model->name,
);
?>

<h1>查看 活动（代付）报名 <font color='#eb6100'><?php echo CHtml::encode($model->name) ?></font></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
			'name'=>'id',
		),
		array(
			'name'=>'actives_id',
			'value'=>$model->Attend_Shops->name
		),
		array(
			'name'=>'founder_id',
			'value'=>$model->Attend_User_Founder->phone,
		),
		array(
			'name'=>'user_id',			
			'value'=>$model->Attend_User->phone,
		),
		array(
			'name'=>'p_id',
			'type'=>'raw',
			'value'=>$model->p_id == 0 ? '报名人' : CHtml::link($model->Attend_Attend_P->name,array('/admin/tmm_attend/view','id'=>$model->p_id))
		),
		array(
			'name'=>'number',
			'value'=>$model->p_id != 0 ? '--' : $model->number
		),
		array(
			'name'=>'people',
			'value'=>$model->p_id != 0 ? '--' : $model->people,
		),
		array(
			'name'=>'children',
			'value'=>$model->p_id != 0 ? '--' : $model->children
		),
		array(
			'name'=>'name',
		),
		array(
			'name'=>'phone',
			'value'=>$model->is_people == $model::is_people_yes ? $model->phone : '--',
		),
		array(
			'name'=>'is_people',
			'value'=>$model::$_is_people[$model->is_people]
		),
		array(
			'name'=>'gender',
            'value'=>$model::$_gender[$model->gender]
		),
		array(
			'name'=>'add_time',
			'type'=>'datetime',
		),
		array(
			'name'=>'up_time',
			'type'=>'datetime',
		),
		array(
			'name'=>'status',
			'value'=>$model::$_status[$model->status],
		),
	),
)); 
?>
