<?php
/* @var $this RoleController */
/* @var $model Role */

$this->breadcrumbs = array(
	$model->username,
);
?>

<h1>我的信息 <font color='#eb6100'><?php echo CHtml::encode($model->username); ?></font></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
				'name'=>'id',
		),
		array(
                'name'=>'username',
        ),
        array(
                'name'=>'phone',
        ),
        array(
                'name'=>'name',
        ),
        array(
                'name'=>'Admin_Role.manager_id',
        ),
        array(
                'name'=>'Admin_Role.count',
        ),
        array(
                'name'=>'Admin_Role.error_count',
        ),
		array(
				'name'=>'Admin_Role.login_error',		        
		),
        array(
                'name'=>'Admin_Role.login_time',
                'type'=>'datetime',
        ),
        array(
                'name'=>'Admin_Role.login_ip',
        ),
        array(
                'name'=>'Admin_Role.last_time',
                'type'=>'datetime',
        ),
        array(
                'name'=>'Admin_Role.last_ip',
        ),
        array(
                'name'=>'up_time',
                'type'=>'datetime',
        ),
        array(
                'name'=>'add_time',
                'type'=>'datetime',
        ),
        array(
                'name'=>'status',
                'value'=>$model::$_status[$model->status],
        ),
	),
)); 
?>