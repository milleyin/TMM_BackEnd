<?php
/**
 * 
 * @author Changhai Zhan
 *
 */
class AreaValidator extends CValidator
{
    /**
     * 容许空白的
     * @var unknown
     */
    public $allowEmpty = true;
    
    /**
     * 验证属性
     * (non-PHPdoc)
     * @see CValidator::validateAttribute()
     */
	protected function validateAttribute($object, $attribute)
	{
	    if ($this->allowEmpty && $this->isEmpty($object->$attribute))
	        return;
        if (is_array($object->$attribute))
        {
            $this->addError($object, $attribute, Yii::t('yii','{attribute} is invalid.'));
            return;
        }
	    if ( !Area::model()->validateAttribute($attribute, $object->$attribute))
	        $this->addError($object, $attribute, Yii::t('yii','{attribute} is invalid.'));
	}
}