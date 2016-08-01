--
-- 抽奖配置
--
ALTER TABLE  `pad_config` ADD  `type` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '配置类型' AFTER `manager_id`;
ALTER TABLE  `pad_config` ADD  `chance_number` int(11) NOT NULL DEFAULT '1' COMMENT '次数/天' AFTER `type`;
ALTER TABLE  `pad_config` ADD  `money` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '支付金额' AFTER `number`;
ALTER TABLE  `pad_config` ADD  `ad_url` varchar(128) NOT NULL DEFAULT '' COMMENT '广告Url' AFTER `info`;