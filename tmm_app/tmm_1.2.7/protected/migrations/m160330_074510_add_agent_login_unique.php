<?php

class m160330_074510_add_agent_login_unique extends CDbMigration
{
	public function up()
	{
		$tableName = Agent::model()->tableName();
		$this->addColumn($tableName,'login_unique',"varchar(128) NOT NULL DEFAULT '' COMMENT '本次登录凭证' AFTER `last_ip`");
		return true;
	}
	
	public function down()
	{
		$tableName = Agent::model()->tableName();
		$this->dropColumn($tableName,'login_unique');
		return true;
	}
}