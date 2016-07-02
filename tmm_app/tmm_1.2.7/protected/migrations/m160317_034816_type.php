<?php

class m160317_034816_type extends CDbMigration
{
	public function up()
	{
		$tableName = Type::model()->tableName();
		$this->createTable($tableName, array(
				"id"=>"int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID'",
				"role_type"=>"tinyint(3) UNSIGNED NOT NULL COMMENT '角色类型'",
				"role_id"=>"int(11) UNSIGNED NOT NULL COMMENT '角色账号'",
				"type"=>"int(11) UNSIGNED NOT NULL COMMENT '类型用途'",
				"name"=>"varchar(32) NOT NULL COMMENT '类型名'",
				"value"=>"varchar(128) NOT NULL COMMENT '类型值'",
				"info"=>"varchar(128) NOT NULL DEFAULT '' COMMENT '类型说明'",
				"options"=>"varchar(128) NOT NULL DEFAULT '' COMMENT '类型属性'",
				"sort"=>"int(11) UNSIGNED NOT NULL DEFAULT 50 COMMENT '排序'",
				"add_time"=>"int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间'",
				"up_time"=>"int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间'",
				"status"=>"tinyint(3) NOT NULL DEFAULT 1 COMMENT '记录状态'",
				'PRIMARY KEY (`id`)',
		),"ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '类型值表'");
		
		return true;
	}

	public function down()
	{	
		$tableName = Type::model()->tableName();
		$this->dropTable($tableName);
		return true;
	}
}