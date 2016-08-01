<?php
/**
 * 小数验证
 * @author Changhai Zhan
 *
 */
class FunctionValidator extends CValidator
{
    /**
     * 容许空白的
     * @var boo
     */
    public $allowEmpty = true;
    /**
     * 允许的
     * @var unknown
     */
    public $allowed = array();
    /**
     * 回调方法
     * @var unknown
     */
    public $function;
    
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
        if (in_array($object->$attribute, $this->allowed)) {
            return;
        }
        if ($this->function) {
            $this->evaluateExpression($this->function, array($object, $attribute));
        }
    }
}