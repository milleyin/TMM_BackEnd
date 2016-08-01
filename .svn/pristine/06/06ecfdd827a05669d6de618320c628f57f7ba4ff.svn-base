<?php
/**
 * 更新抽奖配置表
 * @author Changhai Zhan
 *
 */
class m160725_025033_update_config extends CDbMigration
{
    public function up()
    {
        $tableName = Config::model()->tableName();
        $this->addColumn($tableName, 'type', "tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '配置类型' AFTER `manager_id`");
        $this->addColumn($tableName, 'chance_number', "int(11) NOT NULL DEFAULT '1' COMMENT '次数/天' AFTER `type`");
        $this->addColumn($tableName, 'money', "bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '支付金额' AFTER `number`");
        Yii::app()->cache->flush();
        return true;
    }

    public function down()
    {
        $tableName = Config::model()->tableName();  
        $this->dropColumn($tableName, 'type');
        $this->dropColumn($tableName, 'chance_number');
        $this->dropColumn($tableName, 'money');
        Yii::app()->cache->flush();
        return true;
    }
}