<?php
/* @var $this Tmm_storeController */
/* @var $data StoreUser */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('phone')); ?>:</b>
	<?php echo CHtml::encode($data->phone); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('password')); ?>:</b>
	<?php echo CHtml::encode($data->password); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('p_id')); ?>:</b>
	<?php echo CHtml::encode($data->p_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('agent_id')); ?>:</b>
	<?php echo CHtml::encode($data->agent_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('icon')); ?>:</b>
	<?php echo CHtml::encode($data->icon); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('count')); ?>:</b>
	<?php echo CHtml::encode($data->count); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('phone_type')); ?>:</b>
	<?php echo CHtml::encode($data->phone_type); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('login_token')); ?>:</b>
	<?php echo CHtml::encode($data->login_token); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('login_mac')); ?>:</b>
	<?php echo CHtml::encode($data->login_mac); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('last_mac')); ?>:</b>
	<?php echo CHtml::encode($data->last_mac); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('login_error')); ?>:</b>
	<?php echo CHtml::encode($data->login_error); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('error_count')); ?>:</b>
	<?php echo CHtml::encode($data->error_count); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('login_ip')); ?>:</b>
	<?php echo CHtml::encode($data->login_ip); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('login_time')); ?>:</b>
	<?php echo CHtml::encode($data->login_time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('login_address')); ?>:</b>
	<?php echo CHtml::encode($data->login_address); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('last_ip')); ?>:</b>
	<?php echo CHtml::encode($data->last_ip); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('last_time')); ?>:</b>
	<?php echo CHtml::encode($data->last_time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('last_address')); ?>:</b>
	<?php echo CHtml::encode($data->last_address); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('add_time')); ?>:</b>
	<?php echo CHtml::encode($data->add_time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('up_time')); ?>:</b>
	<?php echo CHtml::encode($data->up_time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('out_time')); ?>:</b>
	<?php echo CHtml::encode($data->out_time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
	<?php echo CHtml::encode($data->status); ?>
	<br />

	*/ ?>

</div>