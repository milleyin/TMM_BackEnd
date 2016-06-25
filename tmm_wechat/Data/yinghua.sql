--
-- 表的结构 `wx_yh_user`
--

DROP TABLE IF EXISTS `wx_yh_user`;
CREATE TABLE IF NOT EXISTS `wx_yh_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `code_id` char(12) NOT NULL COMMENT '用户消费码ID',
  `mobile` char(15) NOT NULL COMMENT '用户手机',
  `openid` varchar(150) NOT NULL DEFAULT '' COMMENT '微信用户的openid',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `status` tinyint(4)  NOT NULL DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='用户表';

--
-- 表的结构 `wx_yh_order`
--

DROP TABLE IF EXISTS `wx_yh_order`;
CREATE TABLE IF NOT EXISTS `wx_yh_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '订单ID',
  `order_no` char(32) NOT NULL DEFAULT '' COMMENT '订单编号',
  `user_id` char(15) NOT NULL COMMENT '用户id',
  `type` tinyint(4)  unsigned NOT NULL DEFAULT '1' COMMENT '票的类型1普通票2夜票',
  `price` decimal(13,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '票的单价',
  `number` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '购买数量',
  `total_price` decimal(13,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '总额',
  `code_url` varchar(1000) NOT NULL COMMENT '订单二维码的链接',
  `order_status` tinyint(4)  NOT NULL DEFAULT '0' COMMENT '订单状态0未支付1已支付2已消费',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '预定时间',
  `exchange_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '兑票时间',
  `status` tinyint(4)  NOT NULL DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='订单表';

--
-- 表的结构 `wx_yh_sms`
--
DROP TABLE IF EXISTS `wx_yh_sms`;
CREATE TABLE IF NOT EXISTS `wx_yh_sms` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `phone` varchar(11) NOT NULL COMMENT '手机号码',
  `sms_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '发给谁 角色id',
  `sms_type` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '发给谁 角色类型0其他1=管理员2=用户',
  `role_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '操作角色id',
  `role_type` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '操作角色类型0其他1=管理员2=用户',
  `sms_use` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '短信用途 1购票',
  `code` varchar(6) NOT NULL DEFAULT '' COMMENT '手机验证码',
  `sms_content` varchar(255) NOT NULL COMMENT '手机内容',
  `sms_source` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '短信来源（手机，平板，电脑）',
  `sms_ip` varchar(15) DEFAULT '' COMMENT '发送短信IP',
  `login_address` varchar(100) DEFAULT '' COMMENT '登录地址（手机端）',
  `error_count` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '错误次数统计',
  `sms_error` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '最大错误次数',
  `end_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '失效时间',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `is_code` tinyint(3) NOT NULL DEFAULT '1' COMMENT '验证 有效 没有使用 0为已经使用',
  `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '记录状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='短信记录表';




--
-- 表的结构 `wx_yh_racing`
--

DROP TABLE IF EXISTS `wx_yh_racing`;
CREATE TABLE IF NOT EXISTS `wx_yh_racing` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '参赛ID',
  `openid` varchar(150) NOT NULL DEFAULT '' COMMENT '微信用户的openid',
  `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '昵称',
  `mobile` char(15) NOT NULL DEFAULT '' COMMENT '联系电话',
  `introduce` varchar(500) NOT NULL DEFAULT '' COMMENT '自我介绍',
  `poll` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '票数',
  `vote_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后投票时间',
  `racing_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '参赛时间',
  `audit_status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '审核状态-1不通过0待审1通过',
  `audit_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '审核时间',
  `status` tinyint(4)  NOT NULL DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='参赛表';



--
-- 表的结构 `wx_yh_racing_img`
--

DROP TABLE IF EXISTS `wx_yh_racing_img`;
CREATE TABLE IF NOT EXISTS `wx_yh_racing_img` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `to_id` int(10) unsigned NOT NULL COMMENT '参赛者id',
  `img_url` varchar(1000) NOT NULL DEFAULT '' COMMENT '参赛者图片',
  `img_introduce` varchar(500) NOT NULL DEFAULT '' COMMENT '图片介绍',
  `upload_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上传时间',
  `status` tinyint(4)  NOT NULL DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='参赛照片表';


--
-- 表的结构 `wx_yh_vote_log`
--

DROP TABLE IF EXISTS `wx_yh_vote_log`;
CREATE TABLE IF NOT EXISTS `wx_yh_vote_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `to_id` int(10) unsigned NOT NULL COMMENT '参赛者id',
  `from_id` varchar(150) NOT NULL DEFAULT '' COMMENT '投票者openid',
  `vote_ip` varchar(15) NOT NULL DEFAULT '' COMMENT '投票ip',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '投票时间',
  `status` tinyint(4)  NOT NULL DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='投票记录表';
