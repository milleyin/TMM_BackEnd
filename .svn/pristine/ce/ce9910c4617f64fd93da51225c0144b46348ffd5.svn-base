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
     * @var boo
     */
    public $allowEmpty = true;
    /**
     * 正则表达式
     * @var string
     */
    public $pattern = '/^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]+$/';
    
    /**
     * 验证属性
     * (non-PHPdoc)
     * @see CValidator::validateAttribute()
     */
    protected function validateAttribute($object, $attribute)
    {
        if ($this->allowEmpty && $this->isEmpty($object->$attribute)) {
            return;
        }
        if (is_array($object->$attribute)) {
            $this->addError($object, $attribute, Yii::t('yii','{attribute} is invalid.'));
            return;
        }
        if ( !preg_match($this->pattern, $object->$attribute))
            $this->addError($object, $attribute, '{attribute} 必须是数字和字母组合');
    }
}