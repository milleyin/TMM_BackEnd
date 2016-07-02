<?php
/**
 * 验证分成比例
 * 用法 ext.Validator.Validator_push
 */
class Validator_money extends CValidator
{
	protected function validateAttribute($object,$attribute)
	{			
		if(!preg_match('/^[0-9]{1,10}(.[0-9]{1,2})?$/', $object->$attribute))
			$this->addError($object,$attribute,'{attribute} 只能有两位小数');
	}
}