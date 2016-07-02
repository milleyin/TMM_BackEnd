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
	'管理页',
);\n";
?>

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#<?php echo $this->class2id($this->modelClass); ?>-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<h1>管理页 <?php echo $this->pluralize($this->class2name($this->modelClass)); ?></h1>
<div>
	<span>
		<?php echo "<?php echo CHtml::link('高级搜索', '#', array('class'=>'search-button')); ?>"; ?>
	</span>
	 <span>
	 	<?php echo "<?php echo CHtml::link('创建{$this->modelClass}', array('create')); ?>";?>
	</span>
</div>
<div class="search-form" style="display:none">
<?php echo "<?php \$this->renderPartial('_search', array(
	'model'=>\$model,
)); ?>\n"; ?>
</div><!-- search-form -->
<?php echo "<?php"; ?>

  $Confirmation = "你确定执行此项操作？";
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
	var th = this;  
	var afterDelete = function(link, success, data){ if(success) alert(data);};  
	$.fn.yiiGridView.update('<?php echo $this->class2id($this->modelClass); ?>-grid', {  
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

Yii::app()->clientScript->registerScript('re-install-date-picker', "
function reinstallDatePicker(id, data) {
<?php
foreach($this->tableSchema->columns as $column) {
	if(strpos($column->name,'time')){
?>
	jQuery('#<?php echo $column->name?>_date').datepicker(jQuery.extend({showMonthAfterYear:false},jQuery.datepicker.regional['zh-CN'],{'maxDate':'new date()','dateFormat':'yy-mm-dd','showOn':'focus','showOtherMonths':true,'selectOtherMonths':true,'changeMonth':true,'changeYear':true,'showButtonPanel':true}));
<?php }
}
?>	
}
");

$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'<?php echo $this->class2id($this->modelClass); ?>-grid',
	'dataProvider'=>$model->search(),
	'enableHistory'=>true,
	'afterAjaxUpdate' => 'reinstallDatePicker',
	'filter'=>$model,
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
				'filter'=>\$this->widget('zii.widgets.jui.CJuiDatePicker',array(
						'language'=>'zh-CN',
						'model'=>\$model,
						'attribute'=>'$column->name',
						'value'=>date('Y-m-d'),
						'options'=>array(
								'maxDate'=>'new date()',
								'dateFormat'=>'yy-mm-dd',
								'showOn' => 'focus',
								'showOtherMonths' => true,
								'selectOtherMonths' => true,
								'changeMonth' => true,
								'changeYear' => true,
								'showButtonPanel' => true,
						),
						'htmlOptions'=>array(
								'id' =>'{$column->name}_date',
						),
					),true),
				'name'=>'".$column->name."',
				'type'=>'datetime',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:80px;'),
				'htmlOptions'=>array('style'=>'text-align:center;','title'=>'
					\$data->getAttributeLabel(\"$column->name\").\"：\".Yii::app()->format->FormatDate(\$data->$column->name)
				'),
		),\n";
	}elseif(strpos($column->name,'status')!== false || strpos($column->name,'type') !== false ){
		echo "\t\tarray(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				//'filter'=>,
				'name'=>'".$column->name."',
				'value'=>'\$data::\$_$column->name[\$data->$column->name]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>'
					\$data->getAttributeLabel(\"$column->name\").\"：\".\$data::\$_$column->name[\$data->$column->name]
				'),
		),\n";
	}elseif(strpos($column->name,'audit') !== false ){
		echo "\t\tarray(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				//'filter'=>,
				'name'=>'".$column->name."',
				'value'=>'\$data::\$_$column->name[\$data->$column->name]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>'
					\$data->getAttributeLabel(\"$column->name\").\"：\".\$data::\$_$column->name[\$data->$column->name]
				'),
		),\n";
		$is_audit=true;
	}else{
		echo "\t\tarray(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				//'filter'=>,
				'name'=>'".$column->name."',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>'
					\$data->getAttributeLabel(\"$column->name\").\"：\".\$data->$column->name
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
			'template'=>'{view}{update}<?php echo isset($is_audit)?'{audit}':''?>{delete}{disable}{start}',
			'buttons'=>array(
					'view'=>array(
						'options'=>array('style'=>'padding:0 8px 0 0;'),
					),
					'update'=>array(
						'options'=>array('style'=>'padding:0 8px 0 0;'),
					),
					<?php 
				if(isset($is_audit))
			echo "'audit'=>array(
 						'label'=>'审核',
						//'visible'=>'\$data->audit==1',
						'url'=>'Yii::app()->createUrl(\"".$this->controller."/view\", array(\"id\"=>\$data->id))',
 					    'options'=>array('style'=>'padding:0 8px 0 0;'),
					),\n";			
					?>				
					'delete'=>array(
						'visible'=>'$data->status==0',
						'options'=>array('style'=>'padding:0 8px 0 0;'),
					),
					'disable'=>array(
						'label'=>'禁用',
						'visible'=>'$data->status==1',
						'url'=>'Yii::app()->createUrl("/<?php echo $this->controller?>/disable", array("id"=>$data->id))',
						'click'=>$click,
						'options'=>array('style'=>'padding:0 8px 0 0;'),
					),
					'start'=>array(
						'label'=>'激活',
						'visible'=>'$data->status==0',
						'url'=>'Yii::app()->createUrl("/<?php echo $this->controller?>/start", array("id"=>$data->id))',
						'click'=>$click,
						'options'=>array('style'=>'padding:0 8px 0 0;'),
					),
			),
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:150px;'),
		),
	),
)); 
?>
