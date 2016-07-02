--
-- 表的结构 `tmm_sms_log`
--
DROP TABLE IF EXISTS `tmm_sms_log`;
CREATE TABLE IF NOT EXISTS `tmm_sms_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `phone` varchar(11) NOT NULL COMMENT '手机号码',
  `sms_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '发给谁 角色id',
  `sms_type` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '发给谁 角色类型0其他1=管理员2=商家3代理商4=用户',
  `role_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '操作角色id',
  `role_type` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '操作角色类型0其他1=管理员2=商家3代理商4=用户',
  `sms_use` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '短信用途 1注册2登录3银行4手机5通知6密码',
  `code` varchar(6) NOT NULL COMMENT '手机验证码',
  `sms_content` varchar(255) NOT NULL COMMENT '手机内容',
  `sms_source` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '短信来源（手机，平板，电脑）',
  `sms_ip` varchar(15) DEFAULT ''  COMMENT '发送短信IP',
  `login_address` VARCHAR(100) DEFAULT '' COMMENT '登录地址（手机端）',
  `error_count` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '错误次数统计',
  `sms_error` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '最大错误次数',
  `end_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '失效时间',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `is_code` tinyint(3) NOT NULL DEFAULT 1 COMMENT '验证 有效 没有使用 0为已经使用',
  `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '记录状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='短信记录日志' AUTO_INCREMENT=1;