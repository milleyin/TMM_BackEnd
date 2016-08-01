<?php
/**
 * 更新抽奖配置表
 * @author Changhai Zhan
 *
 */
class m160729_093646_update_config extends CDbMigration
{
    public function up()
    {
        $tableName = Config::model()->tableName();
        $this->addColumn($tableName, 'ad_url', "varchar(128) NOT NULL DEFAULT '' COMMENT '广告Url' AFTER `info`");
        Yii::app()->cache->flush();
        return true;
    }

    public function down()
    {
        $tableName = Config::model()->tableName();  
        $this->dropColumn($tableName, 'ad_url');
        Yii::app()->cache->flush();
        return true;
    }
}