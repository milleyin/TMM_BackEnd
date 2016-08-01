<?php
/**
 * 获取抽奖机会
 * @author Changhai Zhan
 *
 */
class m160725_074043_update_chance extends CDbMigration
{
    public function up()
    {
        $tableName = Chance::model()->tableName();
        $this->addColumn($tableName, 'type', "tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '配置类型' AFTER `config_id`");
        Yii::app()->cache->flush();
        return true;
    }

    public function down()
    {
        $tableName = Chance::model()->tableName();  
        $this->dropColumn($tableName, 'type');
        Yii::app()->cache->flush();
        return true;
    }
}