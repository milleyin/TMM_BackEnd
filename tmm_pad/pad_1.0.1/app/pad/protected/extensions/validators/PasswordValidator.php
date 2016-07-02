<?php
/**
 * 
 * @author Changhai Zhan
 *
 */
class PasswordValidator extends CValidator
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
        if ( !preg_match('/^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]+$/', $object->$attribute))
            $this->addError($object, $attribute, '{attribute} 必须是数字和字母组合');
    }
}