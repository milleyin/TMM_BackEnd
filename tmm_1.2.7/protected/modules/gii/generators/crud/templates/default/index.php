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
$label=$this->pluralize($this->class2name($this->modelClass));
echo "\$this->breadcrumbs = array(
	'垃圾回收页',
);\n";
?>
?>
<h1>垃圾回收页 <?php echo $this->pluralize($this->class2name($this->modelClass)); ?></h1>

<?php echo "<?php"; ?>

  $Confirmation= "你确定执行此项操作？";
	if(Yii::app()->request->enableCsrfValidation)
	{
		$csrfTokenName = Yii::app()->request->csrfTokenName;
		$csrfToken = Yii::app()->request->csrfToken;
		$csrf = "\n\t\tdata:{ '$csrfTokenName':'$csrfToken'},";
	}else
		$csrf = '';
$click_alert=<<<"EOD"
function(){ 
	if(!confirm("$Confirmation")) return false; 
	var th=this;  
	var afterDelete=function(link,success,data){ if(success) alert(data);};  
	$.fn.yiiGridView.update('<?php echo $this->class2id($this->modelClass); ?>-grid',{  
	type:'POST',
	url:$(this).attr('href'),$csrf
	success:function(data){
	$.fn.yiiGridView.update('<?php echo $this->class2id($this->modelClass); ?>-grid');  
	   afterDelete(th,true,data);  
	},
	error:function(XHR){
	   return afterDelete(th,false,XHR);
	}
  });
    return false;
}
EOD;

$click=<<<"EOD"
function() {  
	if(!confirm("$Confirmation")) return false;
	var th = this,
	afterDelete = function(){};
	jQuery('#<?php echo $this->class2id($this->modelClass); ?>-grid').yiiGridView('update', {
		type: 'POST',
		url: jQuery(this).attr('href'),$csrf
		success: function(data) {
			jQuery('#<?php echo $this->class2id($this->modelClass); ?>-grid').yiiGridView('update');
			afterDelete(th, true, data);
		},
		error: function(XHR) {
			return afterDelete(th, false, XHR);
		}
});
    return false;
}
EOD;

$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'<?php echo $this->class2id($this->modelClass); ?>-grid',
	'dataProvider'=>$model,
	'enableHistory'=>true,
	'columns'=>array(
<?php
$count=0;
foreach($this->tableSchema->columns as $column)
{
	if(++$count==7)
		echo "\t\t/*\n";
	if(strpos($column->name,'time')){
		echo "\t\tarray(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'name'=>'".$column->name."',
				'type'=>'datetime',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:80px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>'
					\$data->getAttributeLabel(\"$column->name\").\":\".Yii::app()->format->FormatDate(\$data->$column->name)
				'),
		),\n";
	}elseif(strpos($column->name,'status') !== false || strpos($column->name,'type') !== false || strpos($column->name,'type') !== false){
		echo "\t\tarray(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'name'=>'".$column->name."',
				'value'=>'\$data::\$_$column->name[\$data->$column->name]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>'
					\$data->getAttributeLabel(\"$column->name\").\":\".\$data::\$_$column->name[\$data->$column->name]
				'),
		),\n";
	}else{
		echo "\t\tarray(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'name'=>'".$column->name."',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:100px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>'
	   				\$data->getAttributeLabel(\"$column->name\").\":\".\$data->$column->name
				'),
		),\n";
	}
}
if($count>=7)
	echo "\t\t*/\n";
?>
		array(
			'class'=>'CButtonColumn',
			'header'=>'操 作',
			'template'=>'{view}{restore}{delete}',
			'buttons'=>array(
					'view'=>array(
						'options'=>array('style'=>'padding:0 8px 0 0;'),
					),
					'restore'=>array(
						'label'=>'还原',
						'url'=>'Yii::app()->createUrl("/<?php echo $this->controller?>/restore", array("id"=>$data->id))',
						'click'=>$click,
						'options'=>array('style'=>'padding:0 8px 0 0;'),
					),
					'delete'=>array(
						'label'=>'彻底删除',
						'options'=>array('style'=>'padding:0 8px 0 0;'),
						'url'=>'Yii::app()->createUrl("/<?php echo $this->controller?>/clear", array("id"=>$data->id))',			
					),
			),
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:150px;'),
		),
	),
)); 
?>
