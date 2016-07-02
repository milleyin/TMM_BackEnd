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
		$actives = Actives::model()->tableName();
		$this->addColumn($actives,'barcode',"varchar(100) NOT NULL DEFAULT '' COMMENT '消费码' AFTER `actives_status`");
		$this->addColumn($actives,'barcode_num',"int(11) unsigned NOT NULL DEFAULT '0' COMMENT '扫消费码数量' AFTER `barcode`");
		$this->addColumn($actives,'pay_type',"tinyint(3) NOT NULL DEFAULT 0 COMMENT '付款方式0=AA 1=全额' AFTER `barcode_num`");
		$this->addColumn($actives,'is_open',"tinyint(3) NOT NULL DEFAULT 1 COMMENT '对外开放 1=开放 0 不开放' AFTER `pay_type`");
		return true;
	}
	
	public function down()
	{
		$actives = Actives::model()->tableName();
		$this->dropColumn($actives,'barcode');
		$this->dropColumn($actives,'barcode_num');
		$this->dropColumn($actives,'pay_type');
		$this->dropColumn($actives,'is_open');
		return true;
	}
	
}