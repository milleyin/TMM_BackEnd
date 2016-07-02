<?php
/* @var $this Agent_adminController */
/* @var $data Agent */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('admin_id')); ?>:</b>
	<?php echo CHtml::encode($data->admin_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('phone')); ?>:</b>
	<?php echo CHtml::encode($data->phone); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('password')); ?>:</b>
	<?php echo CHtml::encode($data->password); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('merchant_count')); ?>:</b>
	<?php echo CHtml::encode($data->merchant_count); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('push')); ?>:</b>
	<?php echo CHtml::encode($data->push); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('firm_name')); ?>:</b>
	<?php echo CHtml::encode($data->firm_name); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('area_id_p')); ?>:</b>
	<?php echo CHtml::encode($data->area_id_p); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('area_id_m')); ?>:</b>
	<?php echo CHtml::encode($data->area_id_m); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('area_id_c')); ?>:</b>
	<?php echo CHtml::encode($data->area_id_c); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('address')); ?>:</b>
	<?php echo CHtml::encode($data->address); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('firm_tel')); ?>:</b>
	<?php echo CHtml::encode($data->firm_tel); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('firm_postcode')); ?>:</b>
	<?php echo CHtml::encode($data->firm_postcode); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('bl_code')); ?>:</b>
	<?php echo CHtml::encode($data->bl_code); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('bl_img')); ?>:</b>
	<?php echo CHtml::encode($data->bl_img); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tax_img')); ?>:</b>
	<?php echo CHtml::encode($data->tax_img); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('occ_img')); ?>:</b>
	<?php echo CHtml::encode($data->occ_img); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('com_contacts')); ?>:</b>
	<?php echo CHtml::encode($data->com_contacts); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('com_identity')); ?>:</b>
	<?php echo CHtml::encode($data->com_identity); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('com_phone')); ?>:</b>
	<?php echo CHtml::encode($data->com_phone); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('manage_name')); ?>:</b>
	<?php echo CHtml::encode($data->manage_name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('manage_identity')); ?>:</b>
	<?php echo CHtml::encode($data->manage_identity); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('manage_phone')); ?>:</b>
	<?php echo CHtml::encode($data->manage_phone); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('identity_hand')); ?>:</b>
	<?php echo CHtml::encode($data->identity_hand); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('identity_before')); ?>:</b>
	<?php echo CHtml::encode($data->identity_before); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('identity_after')); ?>:</b>
	<?php echo CHtml::encode($data->identity_after); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('bank_id')); ?>:</b>
	<?php echo CHtml::encode($data->bank_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('bank_branch')); ?>:</b>
	<?php echo CHtml::encode($data->bank_branch); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('bank_name')); ?>:</b>
	<?php echo CHtml::encode($data->bank_name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('bank_code')); ?>:</b>
	<?php echo CHtml::encode($data->bank_code); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('deposit')); ?>:</b>
	<?php echo CHtml::encode($data->deposit); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('income_count')); ?>:</b>
	<?php echo CHtml::encode($data->income_count); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('cash')); ?>:</b>
	<?php echo CHtml::encode($data->cash); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('money')); ?>:</b>
	<?php echo CHtml::encode($data->money); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('count')); ?>:</b>
	<?php echo CHtml::encode($data->count); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('login_error')); ?>:</b>
	<?php echo CHtml::encode($data->login_error); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('error_count')); ?>:</b>
	<?php echo CHtml::encode($data->error_count); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('login_time')); ?>:</b>
	<?php echo CHtml::encode($data->login_time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('login_ip')); ?>:</b>
	<?php echo CHtml::encode($data->login_ip); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('last_time')); ?>:</b>
	<?php echo CHtml::encode($data->last_time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('last_ip')); ?>:</b>
	<?php echo CHtml::encode($data->last_ip); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('add_time')); ?>:</b>
	<?php echo CHtml::encode($data->add_time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('up_time')); ?>:</b>
	<?php echo CHtml::encode($data->up_time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
	<?php echo CHtml::encode($data->status); ?>
	<br />

	*/ ?>

</div>