<?php
/* @var $this OrderController */
/* @var $model Order */
/* @var $form CActiveForm */
?>
<p>
你可以输入比较运算符,这是可选的 (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) 开始你的每一个搜索的值来指定应该如何做比较。
</p>
<div class="wide form">

<?php $form = $this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>
		<div class="row">
			<?php echo $form->label($model->OrderShops_Order, 'order_no'); ?>
			<?php echo $form->textField($model->OrderShops_Order,'order_no',array('size'=>60,'maxlength'=>128)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model->OrderShops_Order, 'order_type'); ?>
			<?php echo $form->dropDownList($model->OrderShops_Order, 'order_type', array(''=>'--请选择--')+Order::$_order_type); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model, 'user_id'); ?>
			<?php echo $form->textField($model,'user_id',array('size'=>11,'maxlength'=>11)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model->OrderShops_Order, 'order_price'); ?>
			<?php echo $form->textField($model->OrderShops_Order,'order_price',array('size'=>13,'maxlength'=>13)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model->OrderShops_Order, 'price'); ?>
			<?php echo $form->textField($model->OrderShops_Order,'price',array('size'=>13,'maxlength'=>13)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model->OrderShops_Order, 'pay_type'); ?>
			<?php echo $form->dropDownList($model->OrderShops_Order, 'pay_type', array(''=>'--请选择--')+Order::$_pay_type); ?>
		</div>
		
		<div class="row">
			<?php echo $form->label($model->OrderShops_Order, 'pay_time'); ?>
			<?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
						'language'=>'zh-CN',
						'model'=>$model->OrderShops_Order,
						'attribute'=>'pay_time',
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
			<?php echo $form->label($model->OrderShops_Order, 'user_go_count'); ?>
			<?php echo $form->textField($model->OrderShops_Order,'user_go_count',array('size'=>11,'maxlength'=>11)); ?>
		</div>
		
		<div class="row">
			<?php echo $form->label($model->OrderShops_Order, 'status_go'); ?>
			<?php echo $form->dropDownList($model->OrderShops_Order, 'status_go', array(''=>'--请选择--')+Order::$_status_go); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model->OrderShops_Order, 'go_time'); ?>
			<?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
						'language'=>'zh-CN',
						'model'=>$model->OrderShops_Order,
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
			<?php echo $form->label($model->OrderShops_Order, 'add_time'); ?>
			<?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
						'language'=>'zh-CN',
						'model'=>$model->OrderShops_Order,
						'attribute'=>'add_time',
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
			<?php echo $form->label($model->OrderShops_Order, 'up_time'); ?>
			<?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
						'language'=>'zh-CN',
						'model'=>$model->OrderShops_Order,
						'attribute'=>'up_time',
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
			<?php echo $form->label($model->OrderShops_Order, 'order_status'); ?>
			<?php echo $form->dropDownList($model->OrderShops_Order, 'order_status', array(''=>'--请选择--')+Order::$_order_status); ?>
		</div>
		
		<div class="row">
			<?php echo $form->label($model, 'shops_id'); ?>
			<?php echo $form->textField($model, 'shops_id', array('size'=>11,'maxlength'=>11)); ?>
		</div>
		
		<div class="row">
			<?php echo $form->label($model, 'shops_name'); ?>
			<?php echo $form->textField($model, 'shops_name', array('size'=>30,'maxlength'=>25)); ?>
		</div>
		
		<div class="row">
			<?php echo $form->label($model, 'shops_list_info'); ?>
			<?php echo $form->textField($model, 'shops_list_info', array('size'=>40,'maxlength'=>30)); ?>
		</div>
		
		<div class="row">
			<?php echo $form->label($model, 'shops_page_info'); ?>
			<?php echo $form->textField($model, 'shops_page_info', array('size'=>40,'maxlength'=>30)); ?>
		</div>
			
	<div class="row">
		<?php echo $form->label($model, 'search_time_type'); ?>
		<?php echo $form->dropDownList($model, 'search_time_type', array(''=>'--请选择--')+Order::$_search_time_type);?> 
	</div>
	
	<div class="row">
		<?php echo $form->label($model, 'search_start_time'); ?>
		<?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
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
		<?php echo $form->label($model, 'search_end_time'); ?>
		<?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
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
	
	<div class="row buttons">
		<?php echo CHtml::submitButton('搜索'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->