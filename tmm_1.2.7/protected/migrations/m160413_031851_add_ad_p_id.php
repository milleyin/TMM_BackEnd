<?php

class m160413_031851_add_ad_p_id extends CDbMigration
{
	public function up()
	{
		$tableName = Ad::model()->tableName();
		$this->addColumn($tableName,'p_id',"int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '定级' AFTER `id`");
		return true;
	}
	
	public function down()
	{
		$tableName = Ad::model()->tableName();
		$this->dropColumn($tableName,'p_id');
		return true;
	}
}