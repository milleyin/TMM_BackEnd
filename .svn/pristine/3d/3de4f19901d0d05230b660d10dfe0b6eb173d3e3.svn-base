<?php
/**
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
?>
<?php echo "<?php\n"; ?>
/* @var $this <?php echo $this->getControllerClass(); ?> */
/* @var $model <?php echo $this->getModelClass(); ?> */
/* @var $form CActiveForm */
?>
<p>
你可以输入比较运算符,这是可选的 (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) 开始你的每一个搜索的值来指定应该如何做比较。
</p>
<div class="wide form">

<?php echo "<?php \$form=\$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl(\$this->route),
	'method'=>'get',
)); ?>\n"; ?>

<?php foreach($this->tableSchema->columns as $column): ?>
<?php
	$field=$this->generateInputField($this->modelClass,$column);
	if(strpos($field,'password')!==false)
		continue;
	elseif(strpos($column->name,'time')){
		?>
		<div class="row">
			<?php echo "<?php echo \$form->label(\$model,'{$column->name}'); ?>\n"; ?>
			<?php echo "<?php "; ?>$this->widget('zii.widgets.jui.CJuiDatePicker',array(
						'language'=>'zh-CN',
						'model'=>$model,
						'attribute'=>'<?php echo $column->name?>',
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
								//'maxlength'=>10,
								//'size'=>10,
						),
					));
			<?php echo "?>"?>	
		</div>
		<?php }elseif($column->name==='status'){?>
	<div class="row">
			<?php echo "<?php echo \$form->label(\$model,'{$column->name}'); ?>\n"; ?>
			<?php echo "<?php echo ";?>$form->dropDownList($model,'status',array(''=>'--请选择--')+$model::$_status); ?>
		</div>
		<?php }else{?>
		<div class="row">
			<?php echo "<?php echo \$form->label(\$model,'{$column->name}'); ?>\n"; ?>
			<?php echo "<?php echo ".$this->generateActiveField($this->modelClass,$column)."; ?>\n"; ?>
		</div>

<?php }endforeach; ?>

	<div class="row">
		<?php echo "<?php echo \$form->label(\$model,'search_time_type'); ?>\n"; ?>
		<?php echo "<?php echo ";?>$form->dropDownList($model,'search_time_type',array(''=>'--请选择--')+$model::$_search_time_type);?> 
	</div>
	<div class="row">
		<?php echo "<?php echo \$form->label(\$model,'search_start_time'); ?>\n"; ?>
		<?php echo "<?php "; ?>$this->widget('zii.widgets.jui.CJuiDatePicker',array(
					'language'=>'zh-CN',
					'model'=>$model,
					'attribute'=>'search_start_time',
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
							//'maxlength'=>10,
							//'size'=>10,
					),
				));
		<?php echo "?>"?>
		</div>
		<div class="row">
		<?php echo "<?php echo \$form->label(\$model,'search_end_time'); ?>\n"; ?>
		<?php echo "<?php "; ?>$this->widget('zii.widgets.jui.CJuiDatePicker',array(
					'language'=>'zh-CN',
					'model'=>$model,
					'attribute'=>'search_end_time',
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
							//'maxlength'=>10,
							//'size'=>10,
					),
				));
		<?php echo "?>"?>
	</div>
	
	<div class="row buttons">
		<?php echo "<?php echo CHtml::submitButton('搜索'); ?>\n"; ?>
	</div>

<?php echo "<?php \$this->endWidget(); ?>\n"; ?>

</div><!-- search-form -->