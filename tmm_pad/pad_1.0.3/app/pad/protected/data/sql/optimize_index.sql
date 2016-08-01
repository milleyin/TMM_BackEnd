--
-- 广告
--
ALTER TABLE  `pad_ad` ADD INDEX (  `type` );
--
-- 密码
--
ALTER TABLE  `pad_password` ADD INDEX (  `type` );
--
-- 角色
--
ALTER TABLE  `pad_role` ADD INDEX (  `type` );
--
-- 广告选择
--
ALTER TABLE  `pad_select` ADD INDEX (  `ad_type` );
--
-- 菜单
--
ALTER TABLE  `pad_menu` ADD INDEX (  `type` );
--
-- 短信
--
ALTER TABLE  `pad_sms` ADD INDEX (  `type` );
ALTER TABLE  `pad_sms` ADD INDEX (  `use_type` );
--
-- 资源
--
ALTER TABLE  `pad_upload` ADD INDEX (  `type` );