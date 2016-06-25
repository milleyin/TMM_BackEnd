<?php
/**
 * 创建表 select
 * @author Changhai Zhan
 *
 */
class m160415_081103_select extends CDbMigration
{
	public function up()
	{
		$tableName = Select::model()->tableName();
		$this->createTable($tableName, array(				
				'id' =>"int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID'",			
				'admin_id' =>"tinyint(3) UNSIGNED NOT NULL COMMENT '类型'",			
				'type' =>"tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '类型'",
				'to_id' =>"int(11) UNSIGNED NOT NULL COMMENT '归属'",			
				'select_id' =>"int(11) UNSIGNED NOT NULL COMMENT '选中'",
				'sort' =>"int(11) UNSIGNED NOT NULL DEFAULT 50 COMMENT '排序'",
				'add_time' =>"int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间'",
				'up_time' =>"int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间'",
				'status'=>"tinyint(3) NOT NULL DEFAULT 1 COMMENT '状态'",
				'PRIMARY KEY (`id`)',
		),"ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='选择表'");
		Yii::app()->cache->flush();
		return true;
	}

	public function down()
	{
		$tableName = Select::model()->tableName();
		$this->dropTable($tableName);
		Yii::app()->cache->flush();
		return true;
	}
}