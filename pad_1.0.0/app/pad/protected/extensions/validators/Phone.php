<?php
/**
 * 验证分成比例
 *  用法 ext.Validator.Validator_identity
 */
class Phone extends CValidator
{
    /**
     * 验证属性
     * (non-PHPdoc)
     * @see CValidator::validateAttribute()
     */
	protected function validateAttribute($object, $attribute)
	{
		if($object->$attribute && !preg_match('/^1[34578][0-9]{9}$/', $object->$attribute))
			$this->addError($object, $attribute, '{attribute} 不是有效的值');
	}
}