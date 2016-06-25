<?php
/* @var $this Tmm_hotelController */
/* @var $model->Hotel_Items Hotel */
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
			<?php echo $form->label($model->Hotel_Items,'agent_id'); ?>
			<?php echo $form->textField($model->Hotel_Items,'agent_id',array('size'=>11,'maxlength'=>11)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model->Hotel_Items,'store_id'); ?>
			<?php echo $form->textField($model->Hotel_Items,'store_id',array('size'=>11,'maxlength'=>11)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model->Hotel_Items,'manager_id'); ?>
			<?php echo $form->textField($model->Hotel_Items,'manager_id',array('size'=>11,'maxlength'=>11)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model->Hotel_Items,'name'); ?>
			<?php echo $form->textField($model->Hotel_Items,'name',array('size'=>60,'maxlength'=>100)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model->Hotel_Items,'area_id_p'); ?>
            <?php echo $form->dropDownList($model->Hotel_Items,'area_id_p',Area::data_array_name(),array(
                'ajax'=>array(
                    'type'=>'POST',
                    'url'=>Yii::app()->createUrl('/admin/tmm_home/area_name'),
                    'dataType'=>'json',
                    'data'=>array('area_id_p'=>'js:this.value',Yii::app()->request->csrfTokenName=>Yii::app()->request->csrfToken),
                    //	'update'=>'#'.CHtml::activeId($model->Hotel_Items,'area_id_m'),
                    'success'=>'function(data){
								jQuery("#'.CHtml::activeId($model->Hotel_Items,'area_id_m').'").html(data[0]);
								jQuery("#'.CHtml::activeId($model->Hotel_Items,'area_id_c').'").html(data[1]);
						}',
                ),
            ));
            ?>
		</div>

		<div class="row">
			<?php echo $form->label($model->Hotel_Items,'area_id_m'); ?>
            <?php echo $form->dropDownList($model->Hotel_Items,'area_id_m',Area::data_array_name($model->Hotel_Items->area_id_p,true,false),array(
                'ajax'=>array(
                    'type'=>'POST',
                    'url'=>Yii::app()->createUrl('/admin/tmm_home/area_name'),
                    'data'=>array('area_id_m'=>'js:this.value',Yii::app()->request->csrfTokenName=>Yii::app()->request->csrfToken),
                    'update'=>'#'.CHtml::activeId($model->Hotel_Items,'area_id_c'),
                ),
            )); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model->Hotel_Items,'area_id_c'); ?>
            <?php echo $form->dropDownList($model->Hotel_Items,'area_id_c',Area::data_array_name($model->Hotel_Items->area_id_m,true,false)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model->Hotel_Items,'address'); ?>
			<?php echo $form->textField($model->Hotel_Items,'address',array('size'=>60,'maxlength'=>100)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model->Hotel_Items,'push'); ?>
			<?php echo $form->textField($model->Hotel_Items,'push'); ?>
		</div>
		<div class="row">
			<?php echo $form->label($model->Hotel_Items,'push_orgainzer'); ?>
			<?php echo $form->textField($model->Hotel_Items,'push_orgainzer'); ?>
		</div>
		<div class="row">
			<?php echo $form->label($model->Hotel_Items,'push_store'); ?>
			<?php echo $form->textField($model->Hotel_Items,'push_store'); ?>
		</div>
		<div class="row">
			<?php echo $form->label($model->Hotel_Items,'push_agent'); ?>
			<?php echo $form->textField($model->Hotel_Items,'push_agent'); ?>
		</div>
		<div class="row">
			<?php echo $form->label($model->Hotel_Items,'info'); ?>
			<?php echo $form->textField($model->Hotel_Items,'info',array('size'=>60,'maxlength'=>100)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model->Hotel_Items,'phone'); ?>
			<?php echo $form->textField($model->Hotel_Items,'phone',array('size'=>20,'maxlength'=>20)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model->Hotel_Items,'weixin'); ?>
			<?php echo $form->textField($model->Hotel_Items,'weixin',array('size'=>20,'maxlength'=>20)); ?>
		</div>
		
		<div class="row">
			<?php echo $form->label($model->Hotel_Items,'lng'); ?>
			<?php echo $form->textField($model->Hotel_Items,'lng',array('size'=>10,'maxlength'=>10)); ?>
		</div>
		<div class="row">
			<?php echo $form->label($model->Hotel_Items,'lat'); ?>
			<?php echo $form->textField($model->Hotel_Items,'lat',array('size'=>10,'maxlength'=>10)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model->Hotel_Items,'content'); ?>
			<?php echo $form->textArea($model->Hotel_Items,'content',array('rows'=>6, 'cols'=>50)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model->Hotel_Items,'audit'); ?>
			<?php echo $form->dropDownList($model->Hotel_Items,'audit',array(''=>'--请选择--')+Items::$_audit); ?>	
		</div>

		<div class="row">
			<?php echo $form->label($model->Hotel_Items,'down'); ?>
			<?php echo $form->textField($model->Hotel_Items,'down',array('size'=>10,'maxlength'=>10)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model->Hotel_Items,'start_work'); ?>
			<?php 
				$this->widget('ext.juidatetimepicker.EJuiDateTimePicker', array(
						'model'     => $model->Hotel_Items,
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
			<?php echo $form->label($model->Hotel_Items,'end_work'); ?>
			<?php 
				$this->widget('ext.juidatetimepicker.EJuiDateTimePicker', array(
					'model'     => $model->Hotel_Items,
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
			<?php echo $form->label($model->Hotel_Items,'pub_time'); ?>
			<?php $this->widget('zii.widgets.jui.CJuiDatePicker',array(
						'language'=>Yii::app()->language,
						'model'=>$model->Hotel_Items,
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
			<?php echo $form->label($model->Hotel_Items,'add_time'); ?>
			<?php $this->widget('zii.widgets.jui.CJuiDatePicker',array(
						'language'=>Yii::app()->language,
						'model'=>$model->Hotel_Items,
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
			<?php echo $form->label($model->Hotel_Items,'up_time'); ?>
			<?php $this->widget('zii.widgets.jui.CJuiDatePicker',array(
						'language'=>Yii::app()->language,
						'model'=>$model->Hotel_Items,
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
			<?php echo $form->label($model->Hotel_Items,'free_status'); ?>
			<?php echo $form->dropDownList($model->Hotel_Items,'free_status',array(''=>'--请选择--')+Items::$_free_status); ?>
		</div>
		<div class="row">
			<?php echo $form->label($model->Hotel_Items,'status'); ?>
			<?php echo $form->dropDownList($model->Hotel_Items,'status',array(''=>'--请选择--')+Items::$_status); ?>
		</div>
		
	<div class="row">
		<?php echo $form->label($model->Hotel_Items,'search_time_type'); ?>
		<?php echo $form->dropDownList($model->Hotel_Items,'search_time_type',array(''=>'--请选择--')+Items::$_search_time_type);?> 
	</div>
	<div class="row">
		<?php echo $form->label($model->Hotel_Items,'search_start_time'); ?>
		<?php $this->widget('zii.widgets.jui.CJuiDatePicker',array(
					'language'=>Yii::app()->language,
					'model'=>$model->Hotel_Items,
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
		<?php echo $form->label($model->Hotel_Items,'search_end_time'); ?>
		<?php $this->widget('zii.widgets.jui.CJuiDatePicker',array(
					'language'=>Yii::app()->language,
					'model'=>$model->Hotel_Items,
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
		?>	
	</div>
		
	<div class="row buttons">
		<?php echo CHtml::submitButton('搜索'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->