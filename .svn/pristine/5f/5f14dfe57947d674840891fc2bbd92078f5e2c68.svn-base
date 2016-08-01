<?php
/**
 * 更新 中奖记录表
 * @author Changhai Zhan
 *
 */
class m160727_032735_update_record extends CDbMigration
{
    public function up()
    {
        $tableName = Record::model()->tableName();
        $this->addColumn($tableName, 'print_status', "tinyint(3) NOT NULL DEFAULT '-1' COMMENT '打印状态' AFTER `exchange_status`");
        Yii::app()->cache->flush();
        return true;
    }

    public function down()
    {
        $tableName = Record::model()->tableName();  
        $this->dropColumn($tableName, 'print_status');
        Yii::app()->cache->flush();
        return true;
    }
}