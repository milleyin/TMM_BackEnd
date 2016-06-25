<?php
/* @var $this StoreController */
/* @var $model StoreContent */
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
			<?php echo $form->label($model->Content_Store, 'phone'); ?>
			<?php echo $form->textField($model->Content_Store,'phone',array('size'=>11,'maxlength'=>11)); ?>
		</div>
		
		<div class="row">
			<?php echo $form->label($model, 'name'); ?>
			<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>128)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model, 'deposit'); ?>
			<?php echo $form->textField($model,'deposit',array('size'=>13,'maxlength'=>13)); ?>
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
			<?php echo $form->label($model, 'address'); ?>
			<?php echo $form->textField($model,'address',array('size'=>60,'maxlength'=>100)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model, 'store_tel'); ?>
			<?php echo $form->textField($model,'store_tel',array('size'=>15,'maxlength'=>15)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model, 'store_postcode'); ?>
			<?php echo $form->textField($model,'store_postcode',array('size'=>10,'maxlength'=>10)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model, 'lx_contacts'); ?>
			<?php echo $form->textField($model,'lx_contacts',array('size'=>15,'maxlength'=>15)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model, 'lx_identity_code'); ?>
			<?php echo $form->textField($model,'lx_identity_code',array('size'=>32,'maxlength'=>32)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model, 'lx_phone'); ?>
			<?php echo $form->textField($model,'lx_phone',array('size'=>15,'maxlength'=>15)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model, 'com_contacts'); ?>
			<?php echo $form->textField($model,'com_contacts',array('size'=>15,'maxlength'=>15)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model, 'com_phone'); ?>
			<?php echo $form->textField($model,'com_phone',array('size'=>15,'maxlength'=>15)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model, 'com_identity'); ?>
			<?php echo $form->textField($model,'com_identity',array('size'=>32,'maxlength'=>32)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model, 'bl_code'); ?>
			<?php echo $form->textField($model,'bl_code',array('size'=>60,'maxlength'=>100)); ?>
		</div>

		<div class="row">
			<?php echo $form->label($model->Content_Store, 'login_time'); ?>
			<?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
						'language'=>'zh-CN',
						'model'=>$model->Content_Store,
						'attribute'=>'login_time',
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
			<?php echo $form->label($model->Content_Store, 'last_time'); ?>
			<?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
						'language'=>'zh-CN',
						'model'=>$model->Content_Store,
						'attribute'=>'last_time',
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
			<?php echo $form->label($model->Content_Store, 'up_time'); ?>
			<?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
						'language'=>'zh-CN',
						'model'=>$model->Content_Store,
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
			<?php echo $form->label($model->Content_Store, 'add_time'); ?>
			<?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
						'language'=>'zh-CN',
						'model'=>$model->Content_Store,
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
		<?php echo $form->label($model->Content_Store,'status'); ?>
		<?php echo $form->dropDownList($model->Content_Store, 'status', array(''=>'--请选择--')+StoreUser::$_status);?> 
	</div>
	
	<div class="row">
		<?php echo $form->label($model,'search_time_type'); ?>
		<?php echo $form->dropDownList($model, 'search_time_type', array(''=>'--请选择--')+StoreUser::$_search_time_type);?> 
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
		?>	
		</div>
	
	<div class="row buttons">
		<?php echo CHtml::submitButton('搜索'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->