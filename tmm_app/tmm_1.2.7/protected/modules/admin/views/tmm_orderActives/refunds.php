<?php
$this->breadcrumbs=array(		
	'订单管理页'=>array('/admin/tmm_order/admin'),
	'觅趣管理页'=>array('/admin/tmm_order/actives'),
	'觅趣总订单管理页'=>array('admin'),
	$model->actives_no=>array('/admin/tmm_orderActives/view', 'id'=>$model->id),
	'觅趣退款（全）',
);
?>

<h1>觅趣退款（全）<font color='#eb6100'><?php echo CHtml::encode($model->OrderActives_Shops->name . ' [ ' .$model->actives_no .' ]'); ?></font></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
				'name'=>'id',
		),
		array(
				'name'=>'actives_no',
		),
		array(
				'name'=>'organizer_id',
				'value'=>$model->OrderActives_User->phone . ' [ ' . $model->OrderActives_User->nickname .' ]',
				'template'=>"<tr class=\"{class}\"><th>{label}</th><td title=\"".CHtml::encode(
						$model->getAttributeLabel('OrderActives_Actives.is_organizer').'：'.Actives::$_is_organizer[$model->OrderActives_Actives->is_organizer] . "\n" .
						$model->getAttributeLabel('OrderActives_User.nickname').'：'.$model->OrderActives_User->nickname . "\n" .
						(
								$model->OrderActives_Actives->is_organizer == Actives::is_organizer_yes
								?
								(
										$model->getAttributeLabel('OrderActives_User.User_Organizer.firm_name').'：'.$model->OrderActives_User->User_Organizer->firm_name . "\n" .
										$model->getAttributeLabel('OrderActives_User.User_Organizer.firm_phone').'：'.$model->OrderActives_User->User_Organizer->firm_phone ."\n" .
										$model->getAttributeLabel('OrderActives_User.User_Organizer.law_name').'：'.$model->OrderActives_User->User_Organizer->law_name . "\n" .
										$model->getAttributeLabel('OrderActives_User.User_Organizer.manage_name').'：'.$model->OrderActives_User->User_Organizer->manage_name
								)
								:
								''
						))
				. "\">{value}</td></tr>\n",
		),
		array(
				'name'=>'actives_id',
				'type'=>'raw',
				'value'=>CHtml::link($model->OrderActives_Shops->name,array('/admin/tmm_actives/view', 'id'=>$model->OrderActives_Shops->id), array(
					'title'=>
						'觅趣商品'. $model->getAttributeLabel('OrderActives_Actives.id').'：'.$model->OrderActives_Actives->id . "\n" .
						$model->getAttributeLabel('OrderActives_Shops.name').'：'.$model->OrderActives_Shops->name . "\n" .
						$model->getAttributeLabel('OrderActives_Shops.list_info').'：'.$model->OrderActives_Shops->list_info . "\n" .
						$model->getAttributeLabel('OrderActives_Shops.page_info').'：'.$model->OrderActives_Shops->page_info . "\n" .
						$model->getAttributeLabel('OrderActives_Shops.brow').'：'.$model->OrderActives_Shops->brow . "\n" .
						$model->getAttributeLabel('OrderActives_Shops.share').'：'.$model->OrderActives_Shops->share . "\n" .
						$model->getAttributeLabel('OrderActives_Shops.tags_ids').'：'.$model->OrderActives_Shops->tags_ids . "\n" .
						$model->getAttributeLabel('OrderActives_Actives.number').'：'.$model->OrderActives_Actives->number . "\n" .
						$model->getAttributeLabel('OrderActives_Actives.order_number').'：'.$model->OrderActives_Actives->order_number . "\n" .
						$model->getAttributeLabel('OrderActives_Actives.tour_count').'：'.$model->OrderActives_Actives->tour_count . "\n" .
						$model->getAttributeLabel('OrderActives_Actives.order_count').'：'.$model->OrderActives_Actives->order_count  . "\n" .
						$model->getAttributeLabel('OrderActives_Actives.tour_price').'：'.$model->OrderActives_Actives->tour_price
						
				))  . ' [' . $model->OrderActives_Shops->id . ']',
		),
		array(
				'name'=>'OrderActives_Actives.is_open',
				'value'=>Actives::$_is_open[$model->OrderActives_Actives->is_open],
				'template'=>"<tr class=\"{class}\"><th>{label}</th><td title=\"".CHtml::encode(
					$model->getAttributeLabel('OrderActives_Actives.is_open') .'：'. Actives::$_is_open[$model->OrderActives_Actives->is_open] . "\n" .
					$model->getAttributeLabel('OrderActives_Actives.barcode') .'：'. $model->OrderActives_Actives->barcode . "\n" .
					$model->getAttributeLabel('OrderActives_Actives.barcode_num') .'：'. $model->OrderActives_Actives->barcode_num
				). "\">{value}</td></tr>\n",				
		),
		array(
				'name'=>'OrderActives_Actives.pay_type',
				'value'=>Actives::$_pay_type[$model->OrderActives_Actives->pay_type]
		),
		array(
				'name'=>'OrderActives_Actives.order_number',
				'value'=>$model->OrderActives_Actives->order_number . ' / ' .$model->OrderActives_Actives->number,
		),
		array(
				'name'=>'OrderActives_Actives.tour_price',
		),
		array(
				'name'=>'user_price',
		),
		array(
				'name'=>'user_go_count',
		),
		array(
				'name'=>'user_order_count',
		),
		array(
				'name'=>'OrderActives_Actives.tour_count',
		),
		array(
				'name'=>'OrderActives_Actives.order_count',
		),
		array(
				'name'=>'user_order_count',
		),
		array(
				'name'=>'user_submit_count',
		),
		array(
				'name'=>'user_pay_count',
		),
		array(
				'name'=>'user_price_count',
		),
		array(
				'name'=>'total',
		)
	),
));
?>
<br>
<div class="form wide">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'refund-form-form',
	'enableAjaxValidation'=>true,
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>

	<p class="note">这些字段 <span class="required">*</span>是必须的.</p>

	<?php echo $form->errorSummary($models); ?>
		
	<?php 
		foreach ($models as $key=>$refund)
		{
	?>
	<div class="row value">
		<?php echo $form->label($refund->orderModel,'order_no'); ?>
		<strong style="color:#eb6100;"><?php echo $refund->orderModel->order_no; ?></strong>
	</div>
	<div class="row value">
		<?php echo $form->label($refund->orderModel,'user_id'); ?>
		<strong><?php echo $refund->orderModel->Order_User->phone . '</strong> [ ' . $refund->orderModel->Order_User->nickname .' ]' ; ?>
	</div>
	<div class="row value">
		<?php echo $form->label($refund,'price'); ?>
		<strong style="color:#eb6100;"><?php echo $refund->price; ?></strong> 元
	</div>
	
	<div class="row value">
		<?php echo $form->label($refund,'money'); ?>
		<strong><?php echo $refund->money; ?></strong> 元 （已消费：<strong style="color:#eb6100;"><?php echo  Yii::app()->controller->floorSub($refund->price, Yii::app()->controller->floorAdd($refund->money, $refund->fee)); ?></strong> 元）
	</div>
	
	<div class="row value">
		<?php echo $form->label($refund,'fee'); ?>
		<strong><?php echo $refund->fee ; ?></strong> 元   （出游人数：<strong style="color:#eb6100;"><?php echo $refund->orderModel->user_go_count;?></strong> 人 服务费：<strong style="color:#eb6100;"><?php echo $refund->orderModel->user_price_fact;?></strong> 元 / 人 总计：<strong style="color:#eb6100;"><?php echo Yii::app()->controller->floorAdd($refund->money, $refund->fee); ?></strong> 元 ）
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($refund,"[$key]refund"); ?>
		<?php echo $form->textField($refund,"[$key]refund", array('title'=>'退款金额将进入用户钱包')); ?>
		<?php echo $form->error($refund,"[$key]refund"); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($refund,"[$key]deduct"); ?>
		<?php echo $form->textField($refund,"[$key]deduct", array('title'=>'扣除金额将进入平台钱包')); ?>
		<?php echo $form->error($refund,"[$key]deduct"); ?>
	</div>
	<?php echo $form->hiddenField($refund,"[$key]order_id");?>
	<hr style="color:#eb6100;height:2px;">
	<?php 
		}
	?>
		<div class="row">
			<?php echo $form->hiddenField($password, '_pwd');?>
			<?php echo $form->error($password, '_pwd'); ?>
		</div>
		<div class="row buttons">
			<?php echo CHtml::Button('提交', array('onclick'=>'$("#pwd").dialog("open");')); ?>
		</div>

<?php $this->endWidget(); ?>

</div><!-- form -->

	<?php 
			$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
					'id'=>'pwd',
					'htmlOptions'=>array('style'=>'padding:30px 0 40px 0'),
					'options'=>array(
							'title'=>'输入密码',
							'autoOpen'=>false,	//是否自动打开
							'width'=>'450px',			//宽度
							'resizable'=>true,
							'height'=>'auto',			//高度
							'modal' => true,
					),
			));
	?>
	<div class="form wide">
		<div class="row">
			<?php echo $form->labelEx($password, '_pwd'); ?>
			<?php echo $form->passwordField($password, '_pwd', array('size'=>25, 'maxlength'=>6, 'id'=>'pwd_value',));?>
		</div>
		<div class="row buttons">
			<?php 
				echo CHtml::Button('提交', array('onclick'=>'		
					$("#'.CHtml::activeId($password,'_pwd').'").val($("#pwd_value").val());
					$("#pwd").dialog("close");	
					$("#refund-form-form").submit();
				')); 
			?>
		</div>
	</div>
	<?php 
			$this->endWidget();
	?>