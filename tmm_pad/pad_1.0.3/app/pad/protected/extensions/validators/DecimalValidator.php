<?php
/**
 * 小数验证
 * @author Changhai Zhan
 *
 */
class DecimalValidator extends CValidator
{
    /**
     * 容许空白的
     * @var boo
     */
    public $allowEmpty = true;
    /**
     * 整数范围
     * @var string
     */
    public $integer = '1,9';
    /**
     * 小数范围
     * @var string
     */
    public $decimal = '1,2';
    /**
     * 允许的
     * @var unknown
     */
    public $allowed = array();
    /**
     * 正则表达式
     * @var unknown
     */
    public $pattern;
    /**
     * @var integer|float upper limit of the number. Defaults to null, meaning no upper limit.
     */
    public $max;
    /**
     * @var integer|float lower limit of the number. Defaults to null, meaning no lower limit.
     */
    public $min;
    /**
     * @var string user-defined error message used when the value is too big.
     */
    public $tooBig;
    /**
     * @var string user-defined error message used when the value is too small.
     */
    public $tooSmall;
    
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
        if (!$this->pattern) {
            $this->pattern = '/^[0-9]{' . $this->integer . '}(\.[0-9]{' . $this->decimal . '})?$/';
        }
        if ( !preg_match($this->pattern, $object->$attribute)) {
            $this->addError($object, $attribute, Yii::t('yii','{attribute} is invalid.'));
        }
        if($this->min!==null && $object->$attribute<$this->min)
        {
            $message=$this->tooSmall!==null?$this->tooSmall:Yii::t('yii','{attribute} is too small (minimum is {min}).');
            $this->addError($object,$attribute,$message,array('{min}'=>$this->min));
        }
        if($this->max!==null && $object->$attribute>$this->max)
        {
            $message=$this->tooBig!==null?$this->tooBig:Yii::t('yii','{attribute} is too big (maximum is {max}).');
            $this->addError($object,$attribute,$message,array('{max}'=>$this->max));
        }
    }
}