<?php
/**
 * 验证银行类型
 * 用法 ext.Validator.Validator_bank
 */
class Validator_bank extends CValidator
{
	protected function validateAttribute($object,$attribute)
	{	
		if(!Bank::model()->findByPk($object->$attribute))
			$this->addError($object,$attribute, '{attribute} 不是有效的值');
	}
}