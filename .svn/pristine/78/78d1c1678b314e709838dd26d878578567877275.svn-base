<?php
/**
 * 优化表索引
 * @author Changhai Zhan
 *
 */
class m160708_034554_optimize_index extends CDbMigration
{
    public function up()
    {
        $this->createIndex('type', Ad::model()->tableName(), 'type');
        $this->createIndex('type', Password::model()->tableName(), 'type');
        $this->createIndex('type', Role::model()->tableName(), 'type');
        $this->createIndex('ad_type', Select::model()->tableName(), 'ad_type');
        $this->createIndex('type', Menu::model()->tableName(), 'type');
        $this->createIndex('type', Sms::model()->tableName(), 'type');
        $this->createIndex('use_type', Sms::model()->tableName(), 'use_type');
        $this->createIndex('type', Upload::model()->tableName(), 'type');
        Yii::app()->cache->flush();
        return true;
    }

    public function down()
    {
        $this->dropIndex('type', Ad::model()->tableName());
        $this->dropIndex('type', Password::model()->tableName());
        $this->dropIndex('type', Role::model()->tableName());
        $this->dropIndex('ad_type', Select::model()->tableName());
        $this->dropIndex('type', Menu::model()->tableName());
        $this->dropIndex('type', Sms::model()->tableName());
        $this->dropIndex('use_type', Sms::model()->tableName());
        $this->dropIndex('type', Upload::model()->tableName());
        Yii::app()->cache->flush();
        return true;
    }
}