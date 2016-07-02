<?php
/**
 * 验证分成比例
 *  用法 ext.Validator.Validator_identity
 */
class Validator_identity extends CValidator
{
	protected function validateAttribute($object,$attribute)
	{	
		Yii::import('application.extensions.identity.Identity');
		if($object->$attribute && !preg_match('/^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}([0-9]|X)$/', $object->$attribute))
			$this->addError($object,$attribute,'{attribute} 不是有效的值');
		elseif($object->$attribute && !Identity::isCard($object->$attribute))
			$this->addError($object,$attribute,'{attribute} 不是有效的值');
	}
}