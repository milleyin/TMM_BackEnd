--
-- 抽奖记录
--
ALTER TABLE  `pad_record` ADD  `print_status` tinyint(3) NOT NULL DEFAULT '-1' COMMENT '打印状态' AFTER `exchange_status`;