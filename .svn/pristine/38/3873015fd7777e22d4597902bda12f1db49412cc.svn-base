<?php
/**
 * 添加字段 sort
 * @author Changhai Zhan
 *
 */
class m160704_024643_shop_add_sort extends CDbMigration
{
    public function up()
    {
        $tableName = Shop::model()->tableName();
        $this->addColumn($tableName, 'sort', "INT( 11 ) NOT NULL DEFAULT '500' COMMENT '排序' AFTER `add_time`");
        $this->getDbConnection()->getSchema()->getTable($tableName, true);
        return true;
    }
    
    public function down()
    {
        $tableName = Shop::model()->tableName();  
        $this->dropColumn($tableName, 'sort');
        $this->getDbConnection()->getSchema()->getTable($tableName, true);
        return true;
    }
}