<?php
/**
 * 
 * @author Changhai Zhan
 *
 */
class PhoneValidator extends CValidator
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
        if ( !preg_match('/^1[34578][0-9]{9}$/', $object->$attribute))
            $this->addError($object, $attribute, Yii::t('yii','{attribute} is invalid.'));
    }
}