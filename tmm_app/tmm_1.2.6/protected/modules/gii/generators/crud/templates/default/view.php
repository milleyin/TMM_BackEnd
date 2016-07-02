<?php
/**
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
?>
<?php echo "<?php\n"; ?>
/* @var $this <?php echo $this->getControllerClass(); ?> */
/* @var $model <?php echo $this->getModelClass(); ?> */

<?php
$nameColumn=$this->guessNameColumn($this->tableSchema->columns);
$label=$this->pluralize($this->class2name($this->modelClass));
echo "\$this->breadcrumbs=array(
	'管理页'=>array('admin'),
	\$model->{$nameColumn},
);\n";
?>
?>

<h1>查看 <?php echo $this->modelClass." <font color='#eb6100'><?php echo CHtml::encode(\$model->{$nameColumn}) ?>"; ?></font></h1>

<?php echo "<?php"; ?> $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
<?php
foreach($this->tableSchema->columns as $column){
	if(strpos($column->name,'time'))
	echo "\t\tarray(
			'name'=>'".$column->name."',
			'type'=>'datetime',
		),\n";
	elseif($column->name==='status')
	echo "\t\tarray(
			'name'=>'".$column->name."',
			'value'=>\$model::\$_status[\$model->status],
		),\n";
	else
	echo "\t\tarray(
			'name'=>'".$column->name."',
		),\n";
}?>
	),
)); ?>
<div class="row">
		<span class="buttons">
	<?php echo "<?php"; ?>
		echo CHtml::ajaxButton('审核通过',array('/<?php echo $this->controller; ?>/pass','id'=>$model->id),
		array(
				'cache'=>true,
				'success'=>'function(html){
					jQuery("#audit").html(html);
					$("#audit").dialog("open");
					$("#audit").dialog({"title":"审核通过"});
				}',
		))?>
		</span>
		<span class="buttons">
	<?php echo "<?php"; ?>
		echo CHtml::ajaxButton('审核不通过',array('/<?php echo $this->controller; ?>/nopass','id'=>$model->id),
		array(
				'cache'=>true,
				'success'=>'function(html){
					jQuery("#audit").html(html);
					$("#audit").dialog("open");
					$("#audit").dialog({"title":"审核不通过"});
				}',	
		))?>
		</span>
</div>
<?php echo "<?php"; ?>
	$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
			'id'=>'audit',//弹窗ID
			'options'=>array(//传递给JUI插件的参数
					'title'=>'审核',
					'autoOpen'=>false,//是否自动打开
					'width'=>'550px',//宽度
					'height'=>'auto',//高度
					'modal' => true,
					'buttons'=>array(
						//	'关闭'=>'js:function(){$(this).dialog("close");}',//关闭按钮
					),
			),
	));
	$this->endWidget();
?>
