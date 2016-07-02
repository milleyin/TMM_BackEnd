<?php
/**
 * 验证分成比例
 * 用法 ext.Validator.Validator_push
 */
class Validator_push extends CValidator
{
	protected function validateAttribute($object,$attribute)
	{	
		if($object->$attribute>100 || $object->$attribute<0)
			$this->addError($object,$attribute,'{attribute} 不能大于100或小于0');
		elseif(!preg_match('/^[0-9]{1,3}(.[0-9]{1,2})?$/', $object->$attribute))
			$this->addError($object,$attribute,'{attribute} 只能有两位小数');	
	}
}