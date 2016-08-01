<?php
/**
 * 抢菜订单表
 * @author Changhai Zhan
 *
 */
class m160725_064554_create_order_food extends CDbMigration
{
    public function up()
    {
        $tableName = OrderFood::model()->tableName();
        $this->createTable($tableName, array(
            'id' => "bigint(20) UNSIGNED NOT NULL COMMENT 'ID'",
            'user_id' => "bigint(20) UNSIGNED NOT NULL DEFAULT '0' COMMENT '用户'",
            'store_id' => "bigint(20) UNSIGNED NOT NULL DEFAULT '0' COMMENT '体验店'",
            'pad_id' => "bigint(20) UNSIGNED NOT NULL DEFAULT '0' COMMENT '展示屏'",
            'money' => "bigint(20) UNSIGNED NOT NULL DEFAULT '0' COMMENT '订单价格'",
            'manager_id' => "bigint(20) UNSIGNED NOT NULL DEFAULT '0' COMMENT '操作角色'",
            'up_time' => "int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '更新时间'",
            'add_time' => "int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间'",
            'order_status' => "tinyint(3) NOT NULL DEFAULT '0' COMMENT '订单状态'",
            'status' => "tinyint(3) NOT NULL DEFAULT '1' COMMENT '状态'",
            'PRIMARY KEY (`id`)',
        ),"ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='抢菜订单表'");
        Yii::app()->cache->flush();
        return true;
    }

    public function down()
    {
        $tableName = OrderFood::model()->tableName();
        $this->dropTable($tableName);
        Yii::app()->cache->flush();
        return true;
    }
}