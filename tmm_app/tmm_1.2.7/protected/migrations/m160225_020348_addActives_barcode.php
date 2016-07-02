<?php

class m160225_020348_addActives_barcode extends CDbMigration
{
	/**
	 * AFTER 之后
	 * (non-PHPdoc)
	 * @see CDbMigration::up()
	 */
	public function up()
	{
		$tableName = Actives::model()->tableName();
		$this->addColumn($tableName,'barcode',"varchar(100) NOT NULL DEFAULT '' COMMENT '消费码' AFTER `actives_status`");
		$this->addColumn($tableName,'barcode_num',"int(11) unsigned NOT NULL DEFAULT '0' COMMENT '扫消费码数量' AFTER `barcode`");
		$this->addColumn($tableName,'pay_type',"tinyint(3) NOT NULL DEFAULT 0 COMMENT '付款方式0=AA 1=全额' AFTER `barcode_num`");
		$this->addColumn($tableName,'is_open',"tinyint(3) NOT NULL DEFAULT 1 COMMENT '对外开放 1=开放 0 不开放' AFTER `pay_type`");
		return true;
	}
	
	public function down()
	{
		$tableName = Actives::model()->tableName();
		$this->dropColumn($tableName,'barcode');
		$this->dropColumn($tableName,'barcode_num');
		$this->dropColumn($tableName,'pay_type');
		$this->dropColumn($tableName,'is_open');
		return true;
	}
	
}