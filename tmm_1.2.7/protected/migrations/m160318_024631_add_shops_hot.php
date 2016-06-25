<?php
/**
 * 添加商品主要表的字段
 * @author Changhai Zhan
 *
 */
class m160318_024631_add_shops_hot extends CDbMigration
{
	public function up()
	{
		$tableName = Shops::model()->tableName();
		$this->addColumn($tableName,'hot',"tinyint(3)  NOT NULL DEFAULT '0' COMMENT '热门' AFTER `is_sale`");
		$this->addColumn($tableName,'hot_time',"INT(10) unsigned NOT NULL DEFAULT '0' COMMENT '热门时间' AFTER `hot`");
		return true;
	}
	
	public function down()
	{
		$tableName = Shops::model()->tableName();
		$this->dropColumn($tableName,'hot');
		$this->dropColumn($tableName,'hot_time');
		return true;
	}
}