<?php
/* @var $this Tmm_activesController */
/* @var $model Actives */
/* @var $form CActiveForm */
?>
<p>
你可以输入比较运算符,这是可选的 (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) 开始你的每一个搜索的值来指定应该如何做比较。
</p>
<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

		<div class="row">
			<?php echo $form->label($model,'id'); ?>
			<?php echo $form->textField($model,'id',array('size'=>11,'maxlength'=>11)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'is_organizer'); ?>
			<?php echo $form->dropDownList($model,'is_organizer',array(''=>'--请选择--')+$model::$_is_organizer); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'organizer_id'); ?>
			<?php echo $form->textField($model,'organizer_id',array('size'=>25,'maxlength'=>30)); ?>
		</div>
		
		<div class="row">
			<?php echo $form->label($model->Actives_Shops,'name'); ?>
			<?php echo $form->textField($model->Actives_Shops,'name',array('size'=>25,'maxlength'=>30)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'tour_type'); ?>
			<?php echo $form->dropDownList($model,'tour_type',array(''=>'--请选择--')+$model::$_tour_type); ?>
		</div>
		
		<div class="row">
			<?php echo $form->label($model,'actives_type'); ?>
			<?php echo $form->dropDownList($model,'actives_type',array(''=>'--请选择--')+$model::$_actives_type); ?>
		</div>
		
		<div class="row">
			<?php echo $form->label($model,'is_open'); ?>
			<?php echo $form->dropDownList($model,'is_open',array(''=>'--请选择--')+$model::$_is_open); ?>
		</div>
		
		<div class="row">
			<?php echo $form->label($model,'pay_type'); ?>
			<?php echo $form->dropDownList($model,'pay_type',array(''=>'--请选择--')+$model::$_pay_type); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'tour_count'); ?>
			<?php echo $form->textField($model,'tour_count',array('size'=>11,'maxlength'=>11)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'order_count'); ?>
			<?php echo $form->textField($model,'order_count',array('size'=>11,'maxlength'=>11)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'number'); ?>
			<?php echo $form->textField($model,'number',array('size'=>11,'maxlength'=>11)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'tour_price'); ?>
			<?php echo $form->textField($model,'tour_price',array('size'=>13,'maxlength'=>13)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'barcode'); ?>
			<?php echo $form->textField($model,'barcode',array('size'=>15,'maxlength'=>12)); ?>
		</div>	
		
		<div class="row">
			<?php echo $form->label($model,'barcode_num'); ?>
			<?php echo $form->textField($model,'barcode_num',array('size'=>12,'maxlength'=>11)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'remark'); ?>
			<?php echo $form->textArea($model,'remark',array('style'=>'width:300px;height:50px;')); ?>
		</div>
			
	    <div class="row">
			<?php echo $form->label($model,'actives_status'); ?>
			<?php echo $form->dropDownList($model,'actives_status',array(''=>'--请选择--')+$model::$_actives_status); ?>
		</div>
		
			
		<div class="row">
			<?php echo $form->label($model->Actives_Shops,'is_sale'); ?>
			<?php echo $form->dropDownList($model->Actives_Shops,'is_sale',array(''=>'--请选择--')+Shops::$_is_sale); ?>
		</div>
		
		<div class="row">
			<?php echo $form->label($model->Actives_Shops,'selected'); ?>
			<?php echo $form->dropDownList($model->Actives_Shops,'selected',array(''=>'--请选择--')+Shops::$_selected); ?>
		</div>
		
		<div class="row">
			<?php echo $form->label($model->Actives_Shops,'tops'); ?>
			<?php echo $form->dropDownList($model->Actives_Shops,'tops',array(''=>'--请选择--')+Shops::$_tops); ?>
		</div>
		
		<div class="row">
			<?php echo $form->label($model->Actives_Shops,'selected_tops'); ?>
			<?php echo $form->dropDownList($model->Actives_Shops,'selected_tops',array(''=>'--请选择--')+Shops::$_selected_tops); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model->Actives_Shops,'audit'); ?>
			<?php echo $form->dropDownList($model->Actives_Shops,'audit',array(''=>'--请选择--')+Shops::$_audit); ?>
		</div>
		
		<div class="row">
			<?php echo $form->label($model->Actives_Shops,'hot'); ?>
			<?php echo $form->dropDownList($model->Actives_Shops,'hot',array(''=>'--请选择--')+Shops::$_hot); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'status'); ?>
			<?php echo $form->dropDownList($model,'status',array(''=>'--请选择--')+$model::$_status); ?>
		</div>


	<div class="row">
			<?php echo $form->label($model,'start_time'); ?>
			<?php $this->widget('zii.widgets.jui.CJuiDatePicker',array(
						'language'=>'zh-CN',
						'model'=>$model,
						'attribute'=>'start_time',
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
			?>	
		</div>
			<div class="row">
			<?php echo $form->label($model,'end_time'); ?>
			<?php $this->widget('zii.widgets.jui.CJuiDatePicker',array(
						'language'=>'zh-CN',
						'model'=>$model,
						'attribute'=>'end_time',
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
			?>	
		</div>
			<div class="row">
			<?php echo $form->label($model,'pub_time'); ?>
			<?php $this->widget('zii.widgets.jui.CJuiDatePicker',array(
						'language'=>'zh-CN',
						'model'=>$model,
						'attribute'=>'pub_time',
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
			?>	
		</div>
			<div class="row">
			<?php echo $form->label($model,'go_time'); ?>
			<?php $this->widget('zii.widgets.jui.CJuiDatePicker',array(
						'language'=>'zh-CN',
						'model'=>$model,
						'attribute'=>'go_time',
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
			?>	
		</div>
	
	<div class="row">
		<?php echo $form->label($model,'search_time_type'); ?>
		<?php echo $form->dropDownList($model,'search_time_type',array(''=>'--请选择--')+$model::$_search_time_type);?> 
	</div>
	
	<div class="row">
		<?php echo $form->label($model,'search_start_time'); ?>
		<?php $this->widget('zii.widgets.jui.CJuiDatePicker',array(
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
		?>		</div>
		<div class="row">
		<?php echo $form->label($model,'search_end_time'); ?>
		<?php $this->widget('zii.widgets.jui.CJuiDatePicker',array(
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
		?>	</div>
		
	
	<div class="row">
		<?php echo $form->label($model->Actives_Shops,'search_time_type'); ?>
		<?php echo $form->dropDownList($model->Actives_Shops,'search_time_type',array(''=>'--请选择--')+Shops::$_search_time_type);?> 
	</div>
	
	<div class="row">
		<?php echo $form->label($model->Actives_Shops,'search_start_time'); ?>
		<?php $this->widget('zii.widgets.jui.CJuiDatePicker',array(
					'language'=>'zh-CN',
					'model'=>$model->Actives_Shops,
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
		?>		</div>
		<div class="row">
		<?php echo $form->label($model->Actives_Shops,'search_end_time'); ?>
		<?php $this->widget('zii.widgets.jui.CJuiDatePicker',array(
					'language'=>'zh-CN',
					'model'=>$model->Actives_Shops,
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
		?>	</div>
	<div class="row buttons">
		<?php echo CHtml::submitButton('搜索'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->