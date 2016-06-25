<?php
/**
 * 不同的场景 不同的默认值
 * @author Changhai Zhan
 *
 */
class DefaultValueValidator extends CValidator
{
    /**
     * 默认值
     * @var unknown
     */
	public $value;
	
    /**
     * 验证属性
     * (non-PHPdoc)
     * @see CValidator::validateAttribute()
     */
	protected function validateAttribute($object, $attribute)
	{
	    $object->$attribute = $this->value;
	}
}