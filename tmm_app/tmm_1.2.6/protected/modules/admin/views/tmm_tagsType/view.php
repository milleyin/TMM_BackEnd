<?php
/* @var $this Tmm_tagsTypeController */
/* @var $model TagsType */

$this->breadcrumbs=array(
	'树形管理页'=>array('tree'),
	'管理页'=>array('admin'),
	$model->name,
);
if($model->p_id !=0)
	$this->breadcrumbs['选择添加标签']=array('select','id'=>$model->id);

?>

<h1>查看 标签分类 <font color='#eb6100'><?php echo CHtml::encode($model->name); ?></font></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
				'name'=>'id',
		),
		array(
				'name'=>'p_id',
		),
		array(
				'name'=>'name',
		),
		array(
				'name'=>'sort',
		),
		array(
				'name'=>'status',
				'value'=>$model::$_status[$model->status],
		),
		array(
				'name'=>'is_user',
				'value'=>$model::$_is_user[$model->is_user],
		),
		array(
				'name'=>'is_search',
				'value'=>$model::$_is_search[$model->is_search],
		),
		array(
				'name'=>'is_left',
				'value'=>$model::$_is_left[$model->is_left],
		),
		array(
				'name'=>'_select_tags',
				'value'=>	$model->p_id !=0
					? ($model->select_tags(isset($model->TagsType_TagsSelect)?$model->TagsType_TagsSelect:array()))
					: '顶级标签分类不能有标签',
				'type'=>'raw',
		),
		array(
				'name'=>'add_time',
				'type'=>'datetime',
		),
		array(
				'name'=>'up_time',
				'type'=>'datetime',
		),
	),
)); ?>
