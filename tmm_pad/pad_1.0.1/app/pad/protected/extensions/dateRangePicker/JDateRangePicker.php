<?php

/**
 * JDateRangePicker class file.
 *
 * @author jerry2801 <jerry2801@gmail.com>
 * @version alpha 4 (2010-6-7 14:33)
 *
 * A typical usage of JDateRangePicker is as follows:
 * <pre>
 * $this->widget('ext.dateRangePicker.JDateRangePicker',array(
 *     'name'=>CHtml::activeName($model,'startDateToForm'),
 *     'value'=>$model->startDateToForm,
 *     'name2'=>CHtml::activeName($model,'endDateToForm'),
 *     'value2'=>$model->endDateToForm,
 * ));
 * </pre>
 */


Yii::import('ext.my97DatePicker.JMy97DatePicker');

class JDateRangePicker extends CWidget
{
    public $name;
    public $value;
	public $htmlOptions=array();
	public $options=array();

    public $name2;
    public $value2;
	public $htmlOptions2=array();
	public $options2=array();

    public $template='{start} - {end}';

    public function run()
    {
        ob_start();
        $this->widget('JMy97DatePicker',array(
            'name'=>$this->name,
            'value'=>$this->value,
            'htmlOptions'=>$this->htmlOptions,
            'options'=>$this->options,
        ));
        $startControl=ob_get_clean();

        ob_start();
        $this->widget('JMy97DatePicker',array(
            'name'=>$this->name2,
            'value'=>$this->value2,
            'htmlOptions'=>$this->htmlOptions2,
            'options'=>$this->options2,
        ));
        $endControl=ob_get_clean();
        echo strtr($this->template,array('{start}'=>$startControl,'{end}'=>$endControl));
    }

}