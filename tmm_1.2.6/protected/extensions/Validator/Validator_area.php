<?php
/**
 * 验证地址选择是不是对的
 * 用法 ext.Validator.Validator_area
 */
class Validator_area extends CValidator
{
	protected function validateAttribute($object,$attribute)
	{
		if(!Area::is_p($object->area_id_p))
			$this->addError($object, 'area_id_p', '{attribute} 不是有效的值');
		elseif(!Area::is_m($object->area_id_m))
			$this->addError($object, 'area_id_m', '{attribute} 不是有效的值');
		elseif(!Area::is_c($object->area_id_c))
			$this->addError($object, 'area_id_c', '{attribute} 不是有效的值');
	}
}