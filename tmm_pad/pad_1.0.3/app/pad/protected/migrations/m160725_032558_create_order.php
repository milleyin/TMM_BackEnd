<?php
/**
 * 创建订单主表
 * @author Changhai Zhan
 *
 */
class m160725_032558_create_order extends CDbMigration
{
    public function up()
    {
        $tableName = Order::model()->tableName();
        $this->createTable($tableName, array(
            'id' => "bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID'",
            'p_id' => "bigint(20) UNSIGNED NOT NULL DEFAULT '0' COMMENT '父类ID'",
            'type' => "tinyint(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '类型'",
            'order_no' => "varchar(256) NOT NULL DEFAULT '' COMMENT '订单号'",
            'role_id' => "bigint(20) UNSIGNED NOT NULL DEFAULT '0' COMMENT '归属角色'",
            'money' => "bigint(20) UNSIGNED NOT NULL DEFAULT '0' COMMENT '订单价格'",
            'trade_money' => "bigint(20) UNSIGNED NOT NULL DEFAULT '0' COMMENT '支付价格'",
            'trade_no' => "varchar(256) NOT NULL DEFAULT '' COMMENT '回调订单号'",
            'trade_id' => "varchar(256) NOT NULL DEFAULT '' COMMENT '支付账号'",
            'trade_name' => "varchar(256) NOT NULL DEFAULT '' COMMENT '支付账号昵称'",
            'trade_type' => "tinyint(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '支付类型'",
            'trade_time' => "int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '支付时间'",
            'manager_id' => "bigint(20) UNSIGNED NOT NULL DEFAULT '0' COMMENT '操作角色'",
            'up_time' => "int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '更新时间'",
            'add_time' => "int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间'",
            'pay_status' => "tinyint(3) NOT NULL DEFAULT '0' COMMENT '支付状态'",
            'status' => "tinyint(3) NOT NULL DEFAULT '1' COMMENT '状态'",
            'PRIMARY KEY (`id`)',
        ),"ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='订单主表'");
        Yii::app()->cache->flush();
        return true;
    }

    public function down()
    {
        $tableName = Order::model()->tableName();
        $this->dropTable($tableName);
        Yii::app()->cache->flush();
        return true;
    }
}