

--
-- 表的结构 `tmm_refund_log` 延后
--
DROP TABLE IF EXISTS `tmm_refund_log`;
CREATE TABLE IF NOT EXISTS `tmm_refund_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `admin_id` int(11) unsigned NOT NULL COMMENT '管理员的id',
  `user_id` int(11) unsigned NOT NULL COMMENT '用户的id',
  `order_id` int(11) unsigned NOT NULL DEFAULT 0 COMMENT '订单的id',
  `order_no` varchar(128)  NOT NULL DEFAULT ''COMMENT '订单号',
  `refund_id` int(11) unsigned NOT NULL DEFAULT 0 COMMENT '退款理由id',
  `reason` varchar(200) NOT NULL DEFAULT '' COMMENT '退款原因',
  `refund_price` decimal(13,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '退款价格',
  `refund_type` int(11) unsigned NOT NULL DEFAULT 0 COMMENT '退款类型1=仅退款2=商品退款',
  `refund_courier` int(11) unsigned NOT NULL DEFAULT 0 COMMENT '快速公司',
  `refund_courier_num` VARCHAR(32) DEFAULT '' COMMENT '快速单号',  
  `admin_id_first` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '管理员ID(初)',
  `remark_first` varchar(100) DEFAULT '' COMMENT '备注(初)',
  `first_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '(初)时间',
  `admin_id_double` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '管理员ID(复)',
  `remark_double` varchar(100) DEFAULT '' COMMENT '备注(复)',
  `double_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '(复)时间',
  `admin_id_submit` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '管理员ID(确认)',
  `remark_submit` varchar(100) DEFAULT '' COMMENT '备注(确认)',
  `submit_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '(确认)时间',
  `refund_img1` VARCHAR(100) DEFAULT '' COMMENT '退款图片1',
  `refund_img2` VARCHAR(100) DEFAULT '' COMMENT '退款图片2',
  `refund_img3` VARCHAR(100) DEFAULT '' COMMENT '退款图片3',
  `refund_img4` VARCHAR(100) DEFAULT '' COMMENT '退款图片4',
  `refund_img5` VARCHAR(100) DEFAULT '' COMMENT '退款图片5',  
  `is_organizer` TINYINT(3) NOT NULL DEFAULT 0 COMMENT '是否有组织者',
  `push` FLOAT(5,2) NOT NULL DEFAULT 0.00 COMMENT '平台对项目的抽成 %',
  `user_push` FLOAT(5,2) NOT NULL DEFAULT 0 COMMENT '用户退款比例 %',    
  `push_orgainzer` FLOAT(5,2) NOT NULL DEFAULT 0.00 COMMENT '组织者对项目的抽成 %',
  `push_store` FLOAT(5,2) NOT NULL DEFAULT 0.00 COMMENT '商家对项目的抽成 %',
  `push_agent` FLOAT(5,2) NOT NULL DEFAULT 0.00 COMMENT '代理商平台对项目的抽成 %',
  `refund_status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '付款状态 0待付款1=已成功2=申请3=失败',
  `audit_status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '审核状态0待审核1=申成2=申失3=初成4=初败5=确成6=确败',
  `pay_type` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '退款类型1=支付宝2=银行...',
  `refund_time` int(10) unsigned NOT NULL COMMENT '退款时间',
  `add_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  `up_time` int(10) unsigned NOT NULL COMMENT '更新时间',
  `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '记录状态1正常0禁用-1删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='退款记录表';

