<?php
/**
 * 更新广告表字段 sort
 * @author Changhai Zhan
 *
 */
class m160415_074425_up_ad_sort extends CDbMigration
{
	public function up()
	{
		$tableName = Ad::model()->tableName();
		$this->alterColumn($tableName, 'sort', "INT(11) UNSIGNED NOT NULL DEFAULT 50 COMMENT '排序'");
		Yii::app()->cache->flush();
		return true;
	}
	
	public function down()
	{
		$tableName = Ad::model()->tableName();
		$this->alterColumn($tableName, 'sort', "TINYINT(3) UNSIGNED NOT NULL DEFAULT 50 COMMENT '排序'");
		Yii::app()->cache->flush();
		return true;
	}
}