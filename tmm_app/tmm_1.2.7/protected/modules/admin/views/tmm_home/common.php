<?php
/* @var $this ZlogController */
/* @var $model AdminLog */

$this->breadcrumbs=array(
	'常用链接管理',
);
?>
<h1>常用链接管理</h1>
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider'=>$model,
	'enableHistory'=>true,
	'columns'=>array(
//  		'id',
// 		array(
// 				'name'=>'user_id',
// 				'value'=>'$data->Home_Admin->username'
// 		),
		array(
				'name'=>'type_id',
				'value'=>'$data->Home_Type->name'
		),
		'sort',
		array(
			'name'=>'add_time',
			'type'=>'datetime',
		),
		array(
			'class'=>'CButtonColumn',
			'header'=>'操 作',
			'template'=>'{goup}{decline}{delete}',
			'buttons'=>array(
					'goup'=>array(
						'label'=>'上移',			
						'url'=>'Yii::app()->createUrl("/dd_money/zhome/goup",array("id"=>$data->id))',		
						'options'=>array('style'=>'padding:0 10px 0 0;')
					),	
					'decline'=>array(
							'label'=>'下移',
							'url'=>'Yii::app()->createUrl("/dd_money/zhome/decline",array("id"=>$data->id))',
							'options'=>array('style'=>'padding:0 10px 0 0;')
					),
					'delete'=>array(
							'url'=>'Yii::app()->createUrl("/dd_money/zhome/delete",array("id"=>$data->id))',
							'options'=>array('style'=>'padding:0 10px 0 0;')
					),
			),	
			'htmlOptions'=>array('style'=>'text-align:center;width:150px;'),	
		),
	),
	'htmlOptions'=>array('style'=>'text-align:center;width:500px;'),
)); ?>
