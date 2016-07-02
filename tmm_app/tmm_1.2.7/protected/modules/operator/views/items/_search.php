<?php
/* @var $this ItemsController */
/* @var $model Items */
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
			<?php echo $form->label($model, 'id'); ?>
			<?php echo $form->textField($model,'id',array('size'=>11,'maxlength'=>11)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model, 'c_id'); ?>
			<?php echo $form->dropDownList($model,'c_id',ItemsClassliy::data('name'));?> 
		</div>
		
		<div class="row">
			<?php echo $form->label($model, 'name'); ?>
			<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>100)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model, 'store_id'); ?>
			<?php echo $form->textField($model,'store_id',array('size'=>20,'maxlength'=>25)); ?>
		</div>
		
		<div class="row">
			<?php echo $form->label($model,'area_id_p'); ?>
            <?php echo $form->dropDownList($model,'area_id_p',Area::data_array_name(),array(
                'ajax'=>array(
                    'type'=>'POST',
                    'url'=>Yii::app()->createUrl('/operator/home/area_name'),
                    'dataType'=>'json',
                    'data'=>array('area_id_p'=>'js:this.value',Yii::app()->request->csrfTokenName=>Yii::app()->request->csrfToken),
                    //	'update'=>'#'.CHtml::activeId($model,'area_id_m'),
                    'success'=>'function(data){
								jQuery("#'.CHtml::activeId($model,'area_id_m').'").html(data[0]);
								jQuery("#'.CHtml::activeId($model,'area_id_c').'").html(data[1]);
						}',
                ),
            ));
            ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'area_id_m'); ?>
            <?php echo $form->dropDownList($model,'area_id_m',Area::data_array_name($model->area_id_p,true,false),array(
                'ajax'=>array(
                    'type'=>'POST',
                    'url'=>Yii::app()->createUrl('/operator/home/area_name'),
                    'data'=>array('area_id_m'=>'js:this.value',Yii::app()->request->csrfTokenName=>Yii::app()->request->csrfToken),
                    'update'=>'#'.CHtml::activeId($model,'area_id_c'),
                ),
            )); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'area_id_c'); ?>
            <?php echo $form->dropDownList($model,'area_id_c',Area::data_array_name($model->area_id_m,true,false)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model,'address'); ?>
			<?php echo $form->textField($model,'address',array('size'=>60,'maxlength'=>100)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model, 'phone'); ?>
			<?php echo $form->textField($model,'phone',array('size'=>20,'maxlength'=>20)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model, 'weixin'); ?>
			<?php echo $form->textField($model,'weixin',array('size'=>20,'maxlength'=>20)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model, 'content'); ?>
			<?php echo $form->textArea($model,'content',array('rows'=>6, 'cols'=>50)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model, 'down'); ?>
			<?php echo $form->textField($model,'down',array('size'=>10,'maxlength'=>10)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model, 'start_work'); ?>
			<?php 
				$this->widget('ext.juidatetimepicker.EJuiDateTimePicker', array(
						'model'     => $model,
						'attribute'=>'start_work',
						'language'=>Yii::app()->language,
						'mode'    => 'time',
						'options'   => array(
								'flat'=>true,
								'showOn' => 'focus',
								'showSecond'=>true,
								'timeFormat' => 'HH:mm:ss',
								'controlType' =>  'select',
								'defaultValue'=>'00:00:00',
						),
					));
			?>
		</div>

		<div class="row">
			<?php echo $form->label($model, 'end_work'); ?>
			<?php 
				$this->widget('ext.juidatetimepicker.EJuiDateTimePicker', array(
					'model'     => $model,
					'attribute'=>'end_work',
					'language'=>Yii::app()->language,
					'mode'    => 'time',
					'options'   => array(
							'flat'=>true,
							'showOn' => 'focus',
							'showSecond'=>true,
							'timeFormat' => 'HH:mm:ss',
							'controlType' =>  'select',
							'defaultValue'=>'23:59:59',
					),
				));
			?>
		</div>

		<div class="row">
			<?php echo $form->label($model, 'pub_time'); ?>
			<?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
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
			<?php echo $form->label($model, 'add_time'); ?>
			<?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
						'language'=>'zh-CN',
						'model'=>$model,
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
			<?php echo $form->label($model, 'up_time'); ?>
			<?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
						'language'=>'zh-CN',
						'model'=>$model,
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
			<?php echo $form->label($model, 'lng'); ?>
			<?php echo $form->textField($model,'lng',array('size'=>10,'maxlength'=>10)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model, 'lat'); ?>
			<?php echo $form->textField($model,'lat',array('size'=>10,'maxlength'=>10)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model, 'free_status'); ?>
			<?php echo $form->dropDownList($model,'free_status',array(''=>'--请选择--')+$model::$_free_status); ?>
		</div>
		
		<div class="row">
			<?php echo $form->label($model, 'audit'); ?>
			<?php echo $form->dropDownList($model,'audit',array(''=>'--请选择--')+$model::$_audit); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model, 'status'); ?>
			<?php echo $form->dropDownList($model, 'status', array(''=>'--请选择--')+$model::$_status); ?>
		</div>
		
	<div class="row">
		<?php echo $form->label($model,'search_time_type'); ?>
		<?php echo $form->dropDownList($model, 'search_time_type', array(''=>'--请选择--')+$model::$_search_time_type);?> 
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