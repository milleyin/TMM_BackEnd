-- phpMyAdmin SQL Dump
-- version 4.1.8
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 2016-01-15 17:48:59
-- 服务器版本： 5.5.40-log
-- PHP Version: 5.4.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `tmm_365tmm`
--

-- --------------------------------------------------------

--
-- 表的结构 `tmm_action`
--

DROP TABLE IF EXISTS `tmm_action`;
CREATE TABLE IF NOT EXISTS `tmm_action` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` char(30) NOT NULL DEFAULT '' COMMENT '行为唯一标识',
  `title` char(80) NOT NULL DEFAULT '' COMMENT '行为说明',
  `remark` char(140) NOT NULL DEFAULT '' COMMENT '行为描述',
  `rule` text NOT NULL COMMENT '行为规则',
  `log` text NOT NULL COMMENT '日志规则',
  `type` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '类型',
  `status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '状态',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='系统行为表' AUTO_INCREMENT=12 ;

--
-- 转存表中的数据 `tmm_action`
--

INSERT INTO `tmm_action` (`id`, `name`, `title`, `remark`, `rule`, `log`, `type`, `status`, `update_time`) VALUES
(1, 'user_login', '用户登录', '积分+10，每天一次', 'table:member|field:score|condition:uid={$self} AND status>-1|rule:score+10|cycle:24|max:1;', '[user|get_nickname]在[time|time_format]登录了后台', 1, 1, 1387181220),
(2, 'add_article', '发布文章', '积分+5，每天上限5次', 'table:member|field:score|condition:uid={$self}|rule:score+5|cycle:24|max:5', '', 2, 0, 1380173180),
(3, 'review', '评论', '评论积分+1，无限制', 'table:member|field:score|condition:uid={$self}|rule:score+1', '', 2, 1, 1383285646),
(4, 'add_document', '发表文档', '积分+10，每天上限5次', 'table:member|field:score|condition:uid={$self}|rule:score+10|cycle:24|max:5', '[user|get_nickname]在[time|time_format]发表了一篇文章。\r\n表[model]，记录编号[record]。', 2, 0, 1386139726),
(5, 'add_document_topic', '发表讨论', '积分+5，每天上限10次', 'table:member|field:score|condition:uid={$self}|rule:score+5|cycle:24|max:10', '', 2, 0, 1383285551),
(6, 'update_config', '更新配置', '新增或修改或删除配置', '', '', 1, 1, 1383294988),
(7, 'update_model', '更新模型', '新增或修改模型', '', '', 1, 1, 1383295057),
(8, 'update_attribute', '更新属性', '新增或更新或删除属性', '', '', 1, 1, 1383295963),
(9, 'update_channel', '更新导航', '新增或修改或删除导航', '', '', 1, 1, 1383296301),
(10, 'update_menu', '更新菜单', '新增或修改或删除菜单', '', '', 1, 1, 1383296392),
(11, 'update_category', '更新分类', '新增或修改或删除分类', '', '', 1, 1, 1383296765);

-- --------------------------------------------------------

--
-- 表的结构 `tmm_action_log`
--

DROP TABLE IF EXISTS `tmm_action_log`;
CREATE TABLE IF NOT EXISTS `tmm_action_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `action_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '行为id',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '执行用户id',
  `action_ip` bigint(20) NOT NULL COMMENT '执行行为者ip',
  `model` varchar(50) NOT NULL DEFAULT '' COMMENT '触发行为的表',
  `record_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '触发行为的数据id',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '日志备注',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '执行行为的时间',
  PRIMARY KEY (`id`),
  KEY `action_ip_ix` (`action_ip`),
  KEY `action_id_ix` (`action_id`),
  KEY `user_id_ix` (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED COMMENT='行为日志表' AUTO_INCREMENT=48 ;

--
-- 转存表中的数据 `tmm_action_log`
--

INSERT INTO `tmm_action_log` (`id`, `action_id`, `user_id`, `action_ip`, `model`, `record_id`, `remark`, `status`, `create_time`) VALUES
(1, 1, 1, 0, 'member', 1, 'tmm_admin在2016-01-14 11:13登录了后台', 1, 1452741226),
(2, 1, 1, 0, 'member', 1, 'tmm_admin在2016-01-14 15:21登录了后台', 1, 1452756068),
(3, 1, 1, 0, 'member', 1, 'tmm_admin在2016-01-14 15:29登录了后台', 1, 1452756576),
(4, 1, 1, 0, 'member', 1, 'tmm_admin在2016-01-14 15:37登录了后台', 1, 1452757027),
(5, 11, 1, 0, 'category', 1, '操作url：/code-php/365tmm/index.php?s=/Admin/Category/edit.html', 1, 1452762880),
(6, 11, 1, 0, 'category', 2, '操作url：/code-php/365tmm/index.php?s=/Admin/Category/edit.html', 1, 1452762992),
(7, 11, 1, 0, 'category', 39, '操作url：/code-php/365tmm/index.php?s=/Admin/Category/add.html', 1, 1452763021),
(8, 9, 1, 0, 'channel', 3, '操作url：/code-php/365tmm/index.php?s=/Admin/Channel/edit.html', 1, 1452763059),
(9, 11, 1, 0, 'category', 1, '操作url：/code-php/365tmm/index.php?s=/Admin/Category/edit.html', 1, 1452763101),
(10, 11, 1, 0, 'category', 2, '操作url：/code-php/365tmm/index.php?s=/Admin/Category/edit.html', 1, 1452763132),
(11, 9, 1, 0, 'channel', 2, '操作url：/code-php/365tmm/index.php?s=/Admin/Channel/edit.html', 1, 1452763166),
(12, 6, 1, 0, 'config', 38, '操作url：/code-php/365tmm/index.php?s=/Admin/Config/edit.html', 1, 1452764723),
(13, 11, 1, 0, 'category', 39, '操作url：/code-php/365tmm/index.php?s=/Admin/Category/edit.html', 1, 1452766473),
(14, 11, 1, 0, 'category', 2, '操作url：/code-php/365tmm/index.php?s=/Admin/Category/edit.html', 1, 1452766480),
(15, 11, 1, 0, 'category', 40, '操作url：/code-php/365tmm/index.php?s=/Admin/Category/add.html', 1, 1452766538),
(16, 11, 1, 0, 'category', 2, '操作url：/code-php/365tmm/index.php?s=/Admin/Category/edit.html', 1, 1452766595),
(17, 7, 1, 0, 'model', 2, '操作url：/code-php/365tmm/index.php?s=/Admin/Model/update.html', 1, 1452766779),
(18, 1, 1, 2130706433, 'member', 1, 'tmm_admin在2016-01-14 18:26登录了后台', 1, 1452767198),
(19, 1, 1, 2130706433, 'member', 1, 'tmm_admin在2016-01-15 09:45登录了后台', 1, 1452822332),
(20, 1, 1, 1903486168, 'member', 1, 'tmm_admin在2016-01-15 13:03登录了后台', 1, 1452834218),
(21, 1, 1, 3071281737, 'member', 1, 'tmm_admin在2016-01-15 13:37登录了后台', 1, 1452836232),
(22, 1, 1, 3071281737, 'member', 1, 'tmm_admin在2016-01-15 13:38登录了后台', 1, 1452836287),
(23, 11, 1, 3071281737, 'category', 41, '操作url：/admin/category/add.html', 1, 1452836556),
(24, 1, 1, 1903486168, 'member', 1, 'tmm_admin在2016-01-15 13:55登录了后台', 1, 1452837336),
(25, 1, 1, 3071281660, 'member', 1, 'tmm_admin在2016-01-15 14:06登录了后台', 1, 1452838004),
(26, 1, 3, 3071281737, 'member', 3, 'wintonzhang在2016-01-15 14:57登录了后台', 1, 1452841048),
(27, 1, 1, 3071281737, 'member', 1, 'tmm_admin在2016-01-15 14:57登录了后台', 1, 1452841063),
(28, 1, 3, 3071281737, 'member', 3, 'wintonzhang在2016-01-15 14:58登录了后台', 1, 1452841127),
(29, 1, 2, 3071281660, 'member', 2, 'yuwenzhang在2016-01-15 15:02登录了后台', 1, 1452841322),
(30, 8, 1, 3071281660, 'attribute', 5, '操作url：/admin/attribute/update.html', 1, 1452843797),
(31, 8, 1, 3071281660, 'attribute', 3, '操作url：/admin/attribute/update.html', 1, 1452843815),
(32, 1, 1, 1903486168, 'member', 1, 'tmm_admin在2016-01-15 17:06登录了后台', 1, 1452848771),
(33, 1, 1, 3071281737, 'member', 1, 'tmm_admin在2016-01-15 17:07登录了后台', 1, 1452848856),
(34, 1, 3, 3071281737, 'member', 3, 'wintonzhang在2016-01-15 17:07登录了后台', 1, 1452848879),
(35, 6, 1, 3071281737, 'config', 33, '操作url：/admin/config/edit.html', 1, 1452848909),
(36, 6, 1, 3071281737, 'config', 33, '操作url：/Admin/Config/edit.html', 1, 1452849024),
(37, 6, 1, 3071281660, 'config', 33, '操作url：/Admin/Config/edit.html', 1, 1452849320),
(38, 6, 1, 3071281737, 'config', 33, '操作url：/Admin/Config/edit.html', 1, 1452849524),
(39, 1, 3, 3071281737, 'member', 3, 'wintonzhang在2016-01-15 17:19登录了后台', 1, 1452849548),
(40, 1, 3, 3071281737, 'member', 3, 'wintonzhang在2016-01-15 17:24登录了后台', 1, 1452849865),
(41, 1, 3, 3071281660, 'member', 3, 'wintonzhang在2016-01-15 17:26登录了后台', 1, 1452850010),
(42, 1, 1, 3071281660, 'member', 1, 'tmm_admin在2016-01-15 17:27登录了后台', 1, 1452850065),
(43, 11, 1, 1903486168, 'category', 41, '操作url：/Admin/Category/edit.html', 1, 1452850172),
(44, 11, 1, 1903486168, 'category', 41, '操作url：/Admin/Category/edit.html', 1, 1452850175),
(45, 11, 1, 1903486168, 'category', 41, '操作url：/Admin/Category/edit.html', 1, 1452850358),
(46, 11, 1, 1903486168, 'category', 41, '操作url：/Admin/Category/edit.html', 1, 1452850361),
(47, 1, 3, 3071281737, 'member', 3, 'wintonzhang在2016-01-15 17:41登录了后台', 1, 1452850865);

-- --------------------------------------------------------

--
-- 表的结构 `tmm_addons`
--

DROP TABLE IF EXISTS `tmm_addons`;
CREATE TABLE IF NOT EXISTS `tmm_addons` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(40) NOT NULL COMMENT '插件名或标识',
  `title` varchar(20) NOT NULL DEFAULT '' COMMENT '中文名',
  `description` text COMMENT '插件描述',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `config` text COMMENT '配置',
  `author` varchar(40) DEFAULT '' COMMENT '作者',
  `version` varchar(20) DEFAULT '' COMMENT '版本号',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '安装时间',
  `has_adminlist` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否有后台列表',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='插件表' AUTO_INCREMENT=17 ;

--
-- 转存表中的数据 `tmm_addons`
--

INSERT INTO `tmm_addons` (`id`, `name`, `title`, `description`, `status`, `config`, `author`, `version`, `create_time`, `has_adminlist`) VALUES
(15, 'EditorForAdmin', '后台编辑器', '用于增强整站长文本的输入和显示', 1, '{"editor_type":"2","editor_wysiwyg":"1","editor_height":"500px","editor_resize_type":"1"}', 'thinkphp', '0.1', 1383126253, 0),
(16, 'SiteStat', '站点统计信息', '统计站点的基础信息', 1, '{"title":"\\u7cfb\\u7edf\\u4fe1\\u606f","width":"2","display":"1"}', 'thinkphp', '0.1', 1452741308, 0),
(5, 'Editor', '前台编辑器', '用于增强整站长文本的输入和显示', 1, '{"editor_type":"2","editor_wysiwyg":"1","editor_height":"300px","editor_resize_type":"1"}', 'thinkphp', '0.1', 1379830910, 0),
(6, 'Attachment', '附件', '用于文档模型上传附件', 1, 'null', 'thinkphp', '0.1', 1379842319, 1),
(9, 'SocialComment', '通用社交化评论', '集成了各种社交化评论插件，轻松集成到系统中。', 1, '{"comment_type":"1","comment_uid_youyan":"","comment_short_name_duoshuo":"","comment_data_list_duoshuo":""}', 'thinkphp', '0.1', 1380273962, 0);

-- --------------------------------------------------------

--
-- 表的结构 `tmm_attachment`
--

DROP TABLE IF EXISTS `tmm_attachment`;
CREATE TABLE IF NOT EXISTS `tmm_attachment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `title` char(30) NOT NULL DEFAULT '' COMMENT '附件显示名',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '附件类型',
  `source` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '资源ID',
  `record_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '关联记录ID',
  `download` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '下载次数',
  `size` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '附件大小',
  `dir` int(12) unsigned NOT NULL DEFAULT '0' COMMENT '上级目录ID',
  `sort` int(8) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`),
  KEY `idx_record_status` (`record_id`,`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='附件表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `tmm_attribute`
--

DROP TABLE IF EXISTS `tmm_attribute`;
CREATE TABLE IF NOT EXISTS `tmm_attribute` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '字段名',
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '字段注释',
  `field` varchar(100) NOT NULL DEFAULT '' COMMENT '字段定义',
  `type` varchar(20) NOT NULL DEFAULT '' COMMENT '数据类型',
  `value` varchar(100) NOT NULL DEFAULT '' COMMENT '字段默认值',
  `remark` varchar(100) NOT NULL DEFAULT '' COMMENT '备注',
  `is_show` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否显示',
  `extra` varchar(255) NOT NULL DEFAULT '' COMMENT '参数',
  `model_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '模型id',
  `is_must` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否必填',
  `status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '状态',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `validate_rule` varchar(255) NOT NULL,
  `validate_time` tinyint(1) unsigned NOT NULL,
  `error_info` varchar(100) NOT NULL,
  `validate_type` varchar(25) NOT NULL,
  `auto_rule` varchar(100) NOT NULL,
  `auto_time` tinyint(1) unsigned NOT NULL,
  `auto_type` varchar(25) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `model_id` (`model_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='模型属性表' AUTO_INCREMENT=33 ;

--
-- 转存表中的数据 `tmm_attribute`
--

INSERT INTO `tmm_attribute` (`id`, `name`, `title`, `field`, `type`, `value`, `remark`, `is_show`, `extra`, `model_id`, `is_must`, `status`, `update_time`, `create_time`, `validate_rule`, `validate_time`, `error_info`, `validate_type`, `auto_rule`, `auto_time`, `auto_type`) VALUES
(1, 'uid', '用户ID', 'int(10) unsigned NOT NULL ', 'num', '0', '', 0, '', 1, 0, 1, 1384508362, 1383891233, '', 0, '', '', '', 0, ''),
(2, 'name', '标识', 'char(40) NOT NULL ', 'string', '', '同一根节点下标识不重复', 1, '', 1, 0, 1, 1383894743, 1383891233, '', 0, '', '', '', 0, ''),
(3, 'title', '标题', 'char(80) NOT NULL ', 'string', '', '标题长度不能超过36个字符', 1, '', 1, 0, 1, 1452843815, 1383891233, '', 0, '', 'regex', '', 0, 'function'),
(4, 'category_id', '所属分类', 'int(10) unsigned NOT NULL ', 'string', '', '', 0, '', 1, 0, 1, 1384508336, 1383891233, '', 0, '', '', '', 0, ''),
(5, 'description', '描述', 'char(140) NOT NULL ', 'textarea', '', '描述长度不能超过140个字符', 1, '', 1, 0, 1, 1452843797, 1383891233, '', 0, '', 'regex', '', 0, 'function'),
(6, 'root', '根节点', 'int(10) unsigned NOT NULL ', 'num', '0', '该文档的顶级文档编号', 0, '', 1, 0, 1, 1384508323, 1383891233, '', 0, '', '', '', 0, ''),
(7, 'pid', '所属ID', 'int(10) unsigned NOT NULL ', 'num', '0', '父文档编号', 0, '', 1, 0, 1, 1384508543, 1383891233, '', 0, '', '', '', 0, ''),
(8, 'model_id', '内容模型ID', 'tinyint(3) unsigned NOT NULL ', 'num', '0', '该文档所对应的模型', 0, '', 1, 0, 1, 1384508350, 1383891233, '', 0, '', '', '', 0, ''),
(9, 'type', '内容类型', 'tinyint(3) unsigned NOT NULL ', 'select', '2', '', 1, '1:目录\r\n2:主题\r\n3:段落', 1, 0, 1, 1384511157, 1383891233, '', 0, '', '', '', 0, ''),
(10, 'position', '推荐位', 'smallint(5) unsigned NOT NULL ', 'checkbox', '0', '多个推荐则将其推荐值相加', 1, '1:列表推荐\r\n2:频道页推荐\r\n4:首页推荐', 1, 0, 1, 1383895640, 1383891233, '', 0, '', '', '', 0, ''),
(11, 'link_id', '外链', 'int(10) unsigned NOT NULL ', 'num', '0', '0-非外链，大于0-外链ID,需要函数进行链接与编号的转换', 1, '', 1, 0, 1, 1383895757, 1383891233, '', 0, '', '', '', 0, ''),
(12, 'cover_id', '封面', 'int(10) unsigned NOT NULL ', 'picture', '0', '0-无封面，大于0-封面图片ID，需要函数处理', 1, '', 1, 0, 1, 1384147827, 1383891233, '', 0, '', '', '', 0, ''),
(13, 'display', '可见性', 'tinyint(3) unsigned NOT NULL ', 'radio', '1', '', 1, '0:不可见\r\n1:所有人可见', 1, 0, 1, 1386662271, 1383891233, '', 0, '', 'regex', '', 0, 'function'),
(14, 'deadline', '截至时间', 'int(10) unsigned NOT NULL ', 'datetime', '0', '0-永久有效', 1, '', 1, 0, 1, 1387163248, 1383891233, '', 0, '', 'regex', '', 0, 'function'),
(15, 'attach', '附件数量', 'tinyint(3) unsigned NOT NULL ', 'num', '0', '', 0, '', 1, 0, 1, 1387260355, 1383891233, '', 0, '', 'regex', '', 0, 'function'),
(16, 'view', '浏览量', 'int(10) unsigned NOT NULL ', 'num', '0', '', 1, '', 1, 0, 1, 1383895835, 1383891233, '', 0, '', '', '', 0, ''),
(17, 'comment', '评论数', 'int(10) unsigned NOT NULL ', 'num', '0', '', 1, '', 1, 0, 1, 1383895846, 1383891233, '', 0, '', '', '', 0, ''),
(18, 'extend', '扩展统计字段', 'int(10) unsigned NOT NULL ', 'num', '0', '根据需求自行使用', 0, '', 1, 0, 1, 1384508264, 1383891233, '', 0, '', '', '', 0, ''),
(19, 'level', '优先级', 'int(10) unsigned NOT NULL ', 'num', '0', '越高排序越靠前', 1, '', 1, 0, 1, 1383895894, 1383891233, '', 0, '', '', '', 0, ''),
(20, 'create_time', '创建时间', 'int(10) unsigned NOT NULL ', 'datetime', '0', '', 1, '', 1, 0, 1, 1383895903, 1383891233, '', 0, '', '', '', 0, ''),
(21, 'update_time', '更新时间', 'int(10) unsigned NOT NULL ', 'datetime', '0', '', 0, '', 1, 0, 1, 1384508277, 1383891233, '', 0, '', '', '', 0, ''),
(22, 'status', '数据状态', 'tinyint(4) NOT NULL ', 'radio', '0', '', 0, '-1:删除\r\n0:禁用\r\n1:正常\r\n2:待审核\r\n3:草稿', 1, 0, 1, 1384508496, 1383891233, '', 0, '', '', '', 0, ''),
(23, 'parse', '内容解析类型', 'tinyint(3) unsigned NOT NULL ', 'select', '0', '', 0, '0:html\r\n1:ubb\r\n2:markdown', 2, 0, 1, 1384511049, 1383891243, '', 0, '', '', '', 0, ''),
(24, 'content', '文章内容', 'text NOT NULL ', 'editor', '', '', 1, '', 2, 0, 1, 1383896225, 1383891243, '', 0, '', '', '', 0, ''),
(25, 'template', '详情页显示模板', 'varchar(100) NOT NULL ', 'string', '', '参照display方法参数的定义', 1, '', 2, 0, 1, 1383896190, 1383891243, '', 0, '', '', '', 0, ''),
(26, 'bookmark', '收藏数', 'int(10) unsigned NOT NULL ', 'num', '0', '', 1, '', 2, 0, 1, 1383896103, 1383891243, '', 0, '', '', '', 0, ''),
(27, 'parse', '内容解析类型', 'tinyint(3) unsigned NOT NULL ', 'select', '0', '', 0, '0:html\r\n1:ubb\r\n2:markdown', 3, 0, 1, 1387260461, 1383891252, '', 0, '', 'regex', '', 0, 'function'),
(28, 'content', '下载详细描述', 'text NOT NULL ', 'editor', '', '', 1, '', 3, 0, 1, 1383896438, 1383891252, '', 0, '', '', '', 0, ''),
(29, 'template', '详情页显示模板', 'varchar(100) NOT NULL ', 'string', '', '', 1, '', 3, 0, 1, 1383896429, 1383891252, '', 0, '', '', '', 0, ''),
(30, 'file_id', '文件ID', 'int(10) unsigned NOT NULL ', 'file', '0', '需要函数处理', 1, '', 3, 0, 1, 1383896415, 1383891252, '', 0, '', '', '', 0, ''),
(31, 'download', '下载次数', 'int(10) unsigned NOT NULL ', 'num', '0', '', 1, '', 3, 0, 1, 1383896380, 1383891252, '', 0, '', '', '', 0, ''),
(32, 'size', '文件大小', 'bigint(20) unsigned NOT NULL ', 'num', '0', '单位bit', 1, '', 3, 0, 1, 1383896371, 1383891252, '', 0, '', '', '', 0, '');

-- --------------------------------------------------------

--
-- 表的结构 `tmm_auth_extend`
--

DROP TABLE IF EXISTS `tmm_auth_extend`;
CREATE TABLE IF NOT EXISTS `tmm_auth_extend` (
  `group_id` mediumint(10) unsigned NOT NULL COMMENT '用户id',
  `extend_id` mediumint(8) unsigned NOT NULL COMMENT '扩展表中数据的id',
  `type` tinyint(1) unsigned NOT NULL COMMENT '扩展类型标识 1:栏目分类权限;2:模型权限',
  UNIQUE KEY `group_extend_type` (`group_id`,`extend_id`,`type`),
  KEY `uid` (`group_id`),
  KEY `group_id` (`extend_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户组与分类的对应关系表';

--
-- 转存表中的数据 `tmm_auth_extend`
--

INSERT INTO `tmm_auth_extend` (`group_id`, `extend_id`, `type`) VALUES
(1, 1, 1),
(1, 1, 2),
(1, 2, 1),
(1, 2, 2),
(1, 3, 1),
(1, 3, 2),
(1, 4, 1),
(1, 37, 1),
(2, 1, 1),
(2, 2, 1),
(2, 39, 1),
(2, 40, 1),
(2, 41, 1);

-- --------------------------------------------------------

--
-- 表的结构 `tmm_auth_group`
--

DROP TABLE IF EXISTS `tmm_auth_group`;
CREATE TABLE IF NOT EXISTS `tmm_auth_group` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户组id,自增主键',
  `module` varchar(20) NOT NULL COMMENT '用户组所属模块',
  `type` tinyint(4) NOT NULL COMMENT '组类型',
  `title` char(20) NOT NULL DEFAULT '' COMMENT '用户组中文名称',
  `description` varchar(80) NOT NULL DEFAULT '' COMMENT '描述信息',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '用户组状态：为1正常，为0禁用,-1为删除',
  `rules` varchar(500) NOT NULL DEFAULT '' COMMENT '用户组拥有的规则id，多个规则 , 隔开',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `tmm_auth_group`
--

INSERT INTO `tmm_auth_group` (`id`, `module`, `type`, `title`, `description`, `status`, `rules`) VALUES
(1, 'admin', 1, '默认用户组', '', 1, '1,2,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,79,80,81,82,83,84,86,87,88,89,90,91,92,93,94,95,96,97,100,102,103,105,106'),
(2, 'admin', 1, '内容部', '内容部', 1, '1,2,7,8,9,10,11,12,13,14,15,16,17,18,53,71,72,73,74,79,215,216');

-- --------------------------------------------------------

--
-- 表的结构 `tmm_auth_group_access`
--

DROP TABLE IF EXISTS `tmm_auth_group_access`;
CREATE TABLE IF NOT EXISTS `tmm_auth_group_access` (
  `uid` int(10) unsigned NOT NULL COMMENT '用户id',
  `group_id` mediumint(8) unsigned NOT NULL COMMENT '用户组id',
  UNIQUE KEY `uid_group_id` (`uid`,`group_id`),
  KEY `uid` (`uid`),
  KEY `group_id` (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `tmm_auth_group_access`
--

INSERT INTO `tmm_auth_group_access` (`uid`, `group_id`) VALUES
(2, 2),
(3, 2);

-- --------------------------------------------------------

--
-- 表的结构 `tmm_auth_rule`
--

DROP TABLE IF EXISTS `tmm_auth_rule`;
CREATE TABLE IF NOT EXISTS `tmm_auth_rule` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '规则id,自增主键',
  `module` varchar(20) NOT NULL COMMENT '规则所属module',
  `type` tinyint(2) NOT NULL DEFAULT '1' COMMENT '1-url;2-主菜单',
  `name` char(80) NOT NULL DEFAULT '' COMMENT '规则唯一英文标识',
  `title` char(20) NOT NULL DEFAULT '' COMMENT '规则中文描述',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否有效(0:无效,1:有效)',
  `condition` varchar(300) NOT NULL DEFAULT '' COMMENT '规则附加条件',
  PRIMARY KEY (`id`),
  KEY `module` (`module`,`status`,`type`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=217 ;

--
-- 转存表中的数据 `tmm_auth_rule`
--

INSERT INTO `tmm_auth_rule` (`id`, `module`, `type`, `name`, `title`, `status`, `condition`) VALUES
(1, 'admin', 2, 'Admin/Index/index', '首页', 1, ''),
(2, 'admin', 2, 'Admin/Article/mydocument', '内容', 1, ''),
(3, 'admin', 2, 'Admin/User/index', '用户', 1, ''),
(4, 'admin', 2, 'Admin/Addons/index', '扩展', 1, ''),
(5, 'admin', 2, 'Admin/Config/group', '系统', 1, ''),
(7, 'admin', 1, 'Admin/article/add', '新增', 1, ''),
(8, 'admin', 1, 'Admin/article/edit', '编辑', 1, ''),
(9, 'admin', 1, 'Admin/article/setStatus', '改变状态', 1, ''),
(10, 'admin', 1, 'Admin/article/update', '保存', 1, ''),
(11, 'admin', 1, 'Admin/article/autoSave', '保存草稿', 1, ''),
(12, 'admin', 1, 'Admin/article/move', '移动', 1, ''),
(13, 'admin', 1, 'Admin/article/copy', '复制', 1, ''),
(14, 'admin', 1, 'Admin/article/paste', '粘贴', 1, ''),
(15, 'admin', 1, 'Admin/article/permit', '还原', 1, ''),
(16, 'admin', 1, 'Admin/article/clear', '清空', 1, ''),
(17, 'admin', 1, 'Admin/article/index', '文档列表', 1, ''),
(18, 'admin', 1, 'Admin/article/recycle', '回收站', 1, ''),
(19, 'admin', 1, 'Admin/User/addaction', '新增用户行为', 1, ''),
(20, 'admin', 1, 'Admin/User/editaction', '编辑用户行为', 1, ''),
(21, 'admin', 1, 'Admin/User/saveAction', '保存用户行为', 1, ''),
(22, 'admin', 1, 'Admin/User/setStatus', '变更行为状态', 1, ''),
(23, 'admin', 1, 'Admin/User/changeStatus?method=forbidUser', '禁用会员', 1, ''),
(24, 'admin', 1, 'Admin/User/changeStatus?method=resumeUser', '启用会员', 1, ''),
(25, 'admin', 1, 'Admin/User/changeStatus?method=deleteUser', '删除会员', 1, ''),
(26, 'admin', 1, 'Admin/User/index', '用户信息', 1, ''),
(27, 'admin', 1, 'Admin/User/action', '用户行为', 1, ''),
(28, 'admin', 1, 'Admin/AuthManager/changeStatus?method=deleteGroup', '删除', 1, ''),
(29, 'admin', 1, 'Admin/AuthManager/changeStatus?method=forbidGroup', '禁用', 1, ''),
(30, 'admin', 1, 'Admin/AuthManager/changeStatus?method=resumeGroup', '恢复', 1, ''),
(31, 'admin', 1, 'Admin/AuthManager/createGroup', '新增', 1, ''),
(32, 'admin', 1, 'Admin/AuthManager/editGroup', '编辑', 1, ''),
(33, 'admin', 1, 'Admin/AuthManager/writeGroup', '保存用户组', 1, ''),
(34, 'admin', 1, 'Admin/AuthManager/group', '授权', 1, ''),
(35, 'admin', 1, 'Admin/AuthManager/access', '访问授权', 1, ''),
(36, 'admin', 1, 'Admin/AuthManager/user', '成员授权', 1, ''),
(37, 'admin', 1, 'Admin/AuthManager/removeFromGroup', '解除授权', 1, ''),
(38, 'admin', 1, 'Admin/AuthManager/addToGroup', '保存成员授权', 1, ''),
(39, 'admin', 1, 'Admin/AuthManager/category', '分类授权', 1, ''),
(40, 'admin', 1, 'Admin/AuthManager/addToCategory', '保存分类授权', 1, ''),
(41, 'admin', 1, 'Admin/AuthManager/index', '权限管理', 1, ''),
(42, 'admin', 1, 'Admin/Addons/create', '创建', 1, ''),
(43, 'admin', 1, 'Admin/Addons/checkForm', '检测创建', 1, ''),
(44, 'admin', 1, 'Admin/Addons/preview', '预览', 1, ''),
(45, 'admin', 1, 'Admin/Addons/build', '快速生成插件', 1, ''),
(46, 'admin', 1, 'Admin/Addons/config', '设置', 1, ''),
(47, 'admin', 1, 'Admin/Addons/disable', '禁用', 1, ''),
(48, 'admin', 1, 'Admin/Addons/enable', '启用', 1, ''),
(49, 'admin', 1, 'Admin/Addons/install', '安装', 1, ''),
(50, 'admin', 1, 'Admin/Addons/uninstall', '卸载', 1, ''),
(51, 'admin', 1, 'Admin/Addons/saveconfig', '更新配置', 1, ''),
(52, 'admin', 1, 'Admin/Addons/adminList', '插件后台列表', 1, ''),
(53, 'admin', 1, 'Admin/Addons/execute', 'URL方式访问插件', 1, ''),
(54, 'admin', 1, 'Admin/Addons/index', '插件管理', 1, ''),
(55, 'admin', 1, 'Admin/Addons/hooks', '钩子管理', 1, ''),
(56, 'admin', 1, 'Admin/model/add', '新增', 1, ''),
(57, 'admin', 1, 'Admin/model/edit', '编辑', 1, ''),
(58, 'admin', 1, 'Admin/model/setStatus', '改变状态', 1, ''),
(59, 'admin', 1, 'Admin/model/update', '保存数据', 1, ''),
(60, 'admin', 1, 'Admin/Model/index', '模型管理', 1, ''),
(61, 'admin', 1, 'Admin/Config/edit', '编辑', 1, ''),
(62, 'admin', 1, 'Admin/Config/del', '删除', 1, ''),
(63, 'admin', 1, 'Admin/Config/add', '新增', 1, ''),
(64, 'admin', 1, 'Admin/Config/save', '保存', 1, ''),
(65, 'admin', 1, 'Admin/Config/group', '网站设置', 1, ''),
(66, 'admin', 1, 'Admin/Config/index', '配置管理', 1, ''),
(67, 'admin', 1, 'Admin/Channel/add', '新增', 1, ''),
(68, 'admin', 1, 'Admin/Channel/edit', '编辑', 1, ''),
(69, 'admin', 1, 'Admin/Channel/del', '删除', 1, ''),
(70, 'admin', 1, 'Admin/Channel/index', '导航管理', 1, ''),
(71, 'admin', 1, 'Admin/Category/edit', '编辑', 1, ''),
(72, 'admin', 1, 'Admin/Category/add', '新增', 1, ''),
(73, 'admin', 1, 'Admin/Category/remove', '删除', 1, ''),
(74, 'admin', 1, 'Admin/Category/index', '分类管理', 1, ''),
(75, 'admin', 1, 'Admin/file/upload', '上传控件', -1, ''),
(76, 'admin', 1, 'Admin/file/uploadPicture', '上传图片', -1, ''),
(77, 'admin', 1, 'Admin/file/download', '下载', -1, ''),
(94, 'admin', 1, 'Admin/AuthManager/modelauth', '模型授权', 1, ''),
(79, 'admin', 1, 'Admin/article/batchOperate', '导入', 1, ''),
(80, 'admin', 1, 'Admin/Database/index?type=export', '备份数据库', 1, ''),
(81, 'admin', 1, 'Admin/Database/index?type=import', '还原数据库', 1, ''),
(82, 'admin', 1, 'Admin/Database/export', '备份', 1, ''),
(83, 'admin', 1, 'Admin/Database/optimize', '优化表', 1, ''),
(84, 'admin', 1, 'Admin/Database/repair', '修复表', 1, ''),
(86, 'admin', 1, 'Admin/Database/import', '恢复', 1, ''),
(87, 'admin', 1, 'Admin/Database/del', '删除', 1, ''),
(88, 'admin', 1, 'Admin/User/add', '新增用户', 1, ''),
(89, 'admin', 1, 'Admin/Attribute/index', '属性管理', 1, ''),
(90, 'admin', 1, 'Admin/Attribute/add', '新增', 1, ''),
(91, 'admin', 1, 'Admin/Attribute/edit', '编辑', 1, ''),
(92, 'admin', 1, 'Admin/Attribute/setStatus', '改变状态', 1, ''),
(93, 'admin', 1, 'Admin/Attribute/update', '保存数据', 1, ''),
(95, 'admin', 1, 'Admin/AuthManager/addToModel', '保存模型授权', 1, ''),
(96, 'admin', 1, 'Admin/Category/move', '移动', -1, ''),
(97, 'admin', 1, 'Admin/Category/merge', '合并', -1, ''),
(98, 'admin', 1, 'Admin/Config/menu', '后台菜单管理', -1, ''),
(99, 'admin', 1, 'Admin/Article/mydocument', '内容', -1, ''),
(100, 'admin', 1, 'Admin/Menu/index', '菜单管理', 1, ''),
(101, 'admin', 1, 'Admin/other', '其他', -1, ''),
(102, 'admin', 1, 'Admin/Menu/add', '新增', 1, ''),
(103, 'admin', 1, 'Admin/Menu/edit', '编辑', 1, ''),
(104, 'admin', 1, 'Admin/Think/lists?model=article', '文章管理', -1, ''),
(105, 'admin', 1, 'Admin/Think/lists?model=download', '下载管理', 1, ''),
(106, 'admin', 1, 'Admin/Think/lists?model=config', '配置管理', 1, ''),
(107, 'admin', 1, 'Admin/Action/actionlog', '行为日志', 1, ''),
(108, 'admin', 1, 'Admin/User/updatePassword', '修改密码', 1, ''),
(109, 'admin', 1, 'Admin/User/updateNickname', '修改昵称', 1, ''),
(110, 'admin', 1, 'Admin/action/edit', '查看行为日志', 1, ''),
(205, 'admin', 1, 'Admin/think/add', '新增数据', 1, ''),
(111, 'admin', 2, 'Admin/article/index', '文档列表', -1, ''),
(112, 'admin', 2, 'Admin/article/add', '新增', -1, ''),
(113, 'admin', 2, 'Admin/article/edit', '编辑', -1, ''),
(114, 'admin', 2, 'Admin/article/setStatus', '改变状态', -1, ''),
(115, 'admin', 2, 'Admin/article/update', '保存', -1, ''),
(116, 'admin', 2, 'Admin/article/autoSave', '保存草稿', -1, ''),
(117, 'admin', 2, 'Admin/article/move', '移动', -1, ''),
(118, 'admin', 2, 'Admin/article/copy', '复制', -1, ''),
(119, 'admin', 2, 'Admin/article/paste', '粘贴', -1, ''),
(120, 'admin', 2, 'Admin/article/batchOperate', '导入', -1, ''),
(121, 'admin', 2, 'Admin/article/recycle', '回收站', -1, ''),
(122, 'admin', 2, 'Admin/article/permit', '还原', -1, ''),
(123, 'admin', 2, 'Admin/article/clear', '清空', -1, ''),
(124, 'admin', 2, 'Admin/User/add', '新增用户', -1, ''),
(125, 'admin', 2, 'Admin/User/action', '用户行为', -1, ''),
(126, 'admin', 2, 'Admin/User/addAction', '新增用户行为', -1, ''),
(127, 'admin', 2, 'Admin/User/editAction', '编辑用户行为', -1, ''),
(128, 'admin', 2, 'Admin/User/saveAction', '保存用户行为', -1, ''),
(129, 'admin', 2, 'Admin/User/setStatus', '变更行为状态', -1, ''),
(130, 'admin', 2, 'Admin/User/changeStatus?method=forbidUser', '禁用会员', -1, ''),
(131, 'admin', 2, 'Admin/User/changeStatus?method=resumeUser', '启用会员', -1, ''),
(132, 'admin', 2, 'Admin/User/changeStatus?method=deleteUser', '删除会员', -1, ''),
(133, 'admin', 2, 'Admin/AuthManager/index', '权限管理', -1, ''),
(134, 'admin', 2, 'Admin/AuthManager/changeStatus?method=deleteGroup', '删除', -1, ''),
(135, 'admin', 2, 'Admin/AuthManager/changeStatus?method=forbidGroup', '禁用', -1, ''),
(136, 'admin', 2, 'Admin/AuthManager/changeStatus?method=resumeGroup', '恢复', -1, ''),
(137, 'admin', 2, 'Admin/AuthManager/createGroup', '新增', -1, ''),
(138, 'admin', 2, 'Admin/AuthManager/editGroup', '编辑', -1, ''),
(139, 'admin', 2, 'Admin/AuthManager/writeGroup', '保存用户组', -1, ''),
(140, 'admin', 2, 'Admin/AuthManager/group', '授权', -1, ''),
(141, 'admin', 2, 'Admin/AuthManager/access', '访问授权', -1, ''),
(142, 'admin', 2, 'Admin/AuthManager/user', '成员授权', -1, ''),
(143, 'admin', 2, 'Admin/AuthManager/removeFromGroup', '解除授权', -1, ''),
(144, 'admin', 2, 'Admin/AuthManager/addToGroup', '保存成员授权', -1, ''),
(145, 'admin', 2, 'Admin/AuthManager/category', '分类授权', -1, ''),
(146, 'admin', 2, 'Admin/AuthManager/addToCategory', '保存分类授权', -1, ''),
(147, 'admin', 2, 'Admin/AuthManager/modelauth', '模型授权', -1, ''),
(148, 'admin', 2, 'Admin/AuthManager/addToModel', '保存模型授权', -1, ''),
(149, 'admin', 2, 'Admin/Addons/create', '创建', -1, ''),
(150, 'admin', 2, 'Admin/Addons/checkForm', '检测创建', -1, ''),
(151, 'admin', 2, 'Admin/Addons/preview', '预览', -1, ''),
(152, 'admin', 2, 'Admin/Addons/build', '快速生成插件', -1, ''),
(153, 'admin', 2, 'Admin/Addons/config', '设置', -1, ''),
(154, 'admin', 2, 'Admin/Addons/disable', '禁用', -1, ''),
(155, 'admin', 2, 'Admin/Addons/enable', '启用', -1, ''),
(156, 'admin', 2, 'Admin/Addons/install', '安装', -1, ''),
(157, 'admin', 2, 'Admin/Addons/uninstall', '卸载', -1, ''),
(158, 'admin', 2, 'Admin/Addons/saveconfig', '更新配置', -1, ''),
(159, 'admin', 2, 'Admin/Addons/adminList', '插件后台列表', -1, ''),
(160, 'admin', 2, 'Admin/Addons/execute', 'URL方式访问插件', -1, ''),
(161, 'admin', 2, 'Admin/Addons/hooks', '钩子管理', -1, ''),
(162, 'admin', 2, 'Admin/Model/index', '模型管理', -1, ''),
(163, 'admin', 2, 'Admin/model/add', '新增', -1, ''),
(164, 'admin', 2, 'Admin/model/edit', '编辑', -1, ''),
(165, 'admin', 2, 'Admin/model/setStatus', '改变状态', -1, ''),
(166, 'admin', 2, 'Admin/model/update', '保存数据', -1, ''),
(167, 'admin', 2, 'Admin/Attribute/index', '属性管理', -1, ''),
(168, 'admin', 2, 'Admin/Attribute/add', '新增', -1, ''),
(169, 'admin', 2, 'Admin/Attribute/edit', '编辑', -1, ''),
(170, 'admin', 2, 'Admin/Attribute/setStatus', '改变状态', -1, ''),
(171, 'admin', 2, 'Admin/Attribute/update', '保存数据', -1, ''),
(172, 'admin', 2, 'Admin/Config/index', '配置管理', -1, ''),
(173, 'admin', 2, 'Admin/Config/edit', '编辑', -1, ''),
(174, 'admin', 2, 'Admin/Config/del', '删除', -1, ''),
(175, 'admin', 2, 'Admin/Config/add', '新增', -1, ''),
(176, 'admin', 2, 'Admin/Config/save', '保存', -1, ''),
(177, 'admin', 2, 'Admin/Menu/index', '菜单管理', -1, ''),
(178, 'admin', 2, 'Admin/Channel/index', '导航管理', -1, ''),
(179, 'admin', 2, 'Admin/Channel/add', '新增', -1, ''),
(180, 'admin', 2, 'Admin/Channel/edit', '编辑', -1, ''),
(181, 'admin', 2, 'Admin/Channel/del', '删除', -1, ''),
(182, 'admin', 2, 'Admin/Category/index', '分类管理', -1, ''),
(183, 'admin', 2, 'Admin/Category/edit', '编辑', -1, ''),
(184, 'admin', 2, 'Admin/Category/add', '新增', -1, ''),
(185, 'admin', 2, 'Admin/Category/remove', '删除', -1, ''),
(186, 'admin', 2, 'Admin/Category/move', '移动', -1, ''),
(187, 'admin', 2, 'Admin/Category/merge', '合并', -1, ''),
(188, 'admin', 2, 'Admin/Database/index?type=export', '备份数据库', -1, ''),
(189, 'admin', 2, 'Admin/Database/export', '备份', -1, ''),
(190, 'admin', 2, 'Admin/Database/optimize', '优化表', -1, ''),
(191, 'admin', 2, 'Admin/Database/repair', '修复表', -1, ''),
(192, 'admin', 2, 'Admin/Database/index?type=import', '还原数据库', -1, ''),
(193, 'admin', 2, 'Admin/Database/import', '恢复', -1, ''),
(194, 'admin', 2, 'Admin/Database/del', '删除', -1, ''),
(195, 'admin', 2, 'Admin/other', '其他', 1, ''),
(196, 'admin', 2, 'Admin/Menu/add', '新增', -1, ''),
(197, 'admin', 2, 'Admin/Menu/edit', '编辑', -1, ''),
(198, 'admin', 2, 'Admin/Think/lists?model=article', '应用', -1, ''),
(199, 'admin', 2, 'Admin/Think/lists?model=download', '下载管理', -1, ''),
(200, 'admin', 2, 'Admin/Think/lists?model=config', '应用', -1, ''),
(201, 'admin', 2, 'Admin/Action/actionlog', '行为日志', -1, ''),
(202, 'admin', 2, 'Admin/User/updatePassword', '修改密码', -1, ''),
(203, 'admin', 2, 'Admin/User/updateNickname', '修改昵称', -1, ''),
(204, 'admin', 2, 'Admin/action/edit', '查看行为日志', -1, ''),
(206, 'admin', 1, 'Admin/think/edit', '编辑数据', 1, ''),
(207, 'admin', 1, 'Admin/Menu/import', '导入', 1, ''),
(208, 'admin', 1, 'Admin/Model/generate', '生成', 1, ''),
(209, 'admin', 1, 'Admin/Addons/addHook', '新增钩子', 1, ''),
(210, 'admin', 1, 'Admin/Addons/edithook', '编辑钩子', 1, ''),
(211, 'admin', 1, 'Admin/Article/sort', '文档排序', 1, ''),
(212, 'admin', 1, 'Admin/Config/sort', '排序', 1, ''),
(213, 'admin', 1, 'Admin/Menu/sort', '排序', 1, ''),
(214, 'admin', 1, 'Admin/Channel/sort', '排序', 1, ''),
(215, 'admin', 1, 'Admin/Category/operate/type/move', '移动', 1, ''),
(216, 'admin', 1, 'Admin/Category/operate/type/merge', '合并', 1, '');

-- --------------------------------------------------------

--
-- 表的结构 `tmm_category`
--

DROP TABLE IF EXISTS `tmm_category`;
CREATE TABLE IF NOT EXISTS `tmm_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '分类ID',
  `name` varchar(30) NOT NULL COMMENT '标志',
  `title` varchar(50) NOT NULL COMMENT '标题',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID',
  `sort` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序（同级有效）',
  `list_row` tinyint(3) unsigned NOT NULL DEFAULT '10' COMMENT '列表每页行数',
  `meta_title` varchar(50) NOT NULL DEFAULT '' COMMENT 'SEO的网页标题',
  `keywords` varchar(255) NOT NULL DEFAULT '' COMMENT '关键字',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  `template_index` varchar(100) NOT NULL COMMENT '频道页模板',
  `template_lists` varchar(100) NOT NULL COMMENT '列表页模板',
  `template_detail` varchar(100) NOT NULL COMMENT '详情页模板',
  `template_edit` varchar(100) NOT NULL COMMENT '编辑页模板',
  `model` varchar(100) NOT NULL DEFAULT '' COMMENT '关联模型',
  `type` varchar(100) NOT NULL DEFAULT '' COMMENT '允许发布的内容类型',
  `link_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '外链',
  `allow_publish` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否允许发布内容',
  `display` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '可见性',
  `reply` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否允许回复',
  `check` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '发布的文章是否需要审核',
  `reply_model` varchar(100) NOT NULL DEFAULT '',
  `extend` text NOT NULL COMMENT '扩展设置',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '数据状态',
  `icon` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分类图标',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_name` (`name`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='分类表' AUTO_INCREMENT=42 ;

--
-- 转存表中的数据 `tmm_category`
--

INSERT INTO `tmm_category` (`id`, `name`, `title`, `pid`, `sort`, `list_row`, `meta_title`, `keywords`, `description`, `template_index`, `template_lists`, `template_detail`, `template_edit`, `model`, `type`, `link_id`, `allow_publish`, `display`, `reply`, `check`, `reply_model`, `extend`, `create_time`, `update_time`, `status`, `icon`) VALUES
(1, 'site', '官网内容', 0, 0, 10, '', '', '', '', '', '', '', '2', '2,1', 0, 0, 1, 0, 0, '1', '', 1379474947, 1452763101, 1, 0),
(2, 'join', '加入我们', 1, 1, 10, '', '', '', '', '', '', '', '2', '2', 0, 1, 1, 0, 1, '1', '', 1379475028, 1452766595, 1, 31),
(39, 'news', '新闻动态', 1, 0, 10, '', '', '', '', '', '', '', '2', '2', 0, 1, 1, 1, 0, '', '', 1452763021, 1452766473, 1, 0),
(40, 'hydt', '行业动态', 39, 0, 10, '', '', '', '', '', '', '', '2', '2', 0, 1, 1, 1, 0, '', '', 1452766538, 1452766538, 1, 0),
(41, 'a', '公司新闻', 39, 0, 10, '', '', '', '', '', '', '', '2', '2', 0, 1, 1, 1, 1, '', '', 1452836556, 1452850361, 1, 3);

-- --------------------------------------------------------

--
-- 表的结构 `tmm_channel`
--

DROP TABLE IF EXISTS `tmm_channel`;
CREATE TABLE IF NOT EXISTS `tmm_channel` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '频道ID',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级频道ID',
  `title` char(30) NOT NULL COMMENT '频道标题',
  `url` char(100) NOT NULL COMMENT '频道连接',
  `sort` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '导航排序',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态',
  `target` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '新窗口打开',
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- 转存表中的数据 `tmm_channel`
--

INSERT INTO `tmm_channel` (`id`, `pid`, `title`, `url`, `sort`, `create_time`, `update_time`, `status`, `target`) VALUES
(1, 0, '首页', 'Index/index', 1, 1379475111, 1379923177, 1, 0),
(2, 0, '新闻内容', 'Article/index?category=news', 2, 1379475131, 1452763166, 1, 0),
(3, 0, '官网', 'http://www.365tmm.com', 3, 1379475154, 1452763059, 1, 0);

-- --------------------------------------------------------

--
-- 表的结构 `tmm_config`
--

DROP TABLE IF EXISTS `tmm_config`;
CREATE TABLE IF NOT EXISTS `tmm_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '配置ID',
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '配置名称',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '配置类型',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '配置说明',
  `group` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '配置分组',
  `extra` varchar(255) NOT NULL DEFAULT '' COMMENT '配置值',
  `remark` varchar(100) NOT NULL COMMENT '配置说明',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态',
  `value` text NOT NULL COMMENT '配置值',
  `sort` smallint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_name` (`name`),
  KEY `type` (`type`),
  KEY `group` (`group`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=39 ;

--
-- 转存表中的数据 `tmm_config`
--

INSERT INTO `tmm_config` (`id`, `name`, `type`, `title`, `group`, `extra`, `remark`, `create_time`, `update_time`, `status`, `value`, `sort`) VALUES
(1, 'WEB_SITE_TITLE', 1, '网站标题', 1, '', '网站标题前台显示标题', 1378898976, 1379235274, 1, '田觅觅', 0),
(2, 'WEB_SITE_DESCRIPTION', 2, '网站描述', 1, '', '网站搜索引擎描述', 1378898976, 1379235841, 1, '田觅觅，农旅一体化互联网平台，把农产品送到城里来，把城里人带到农村去 田觅觅帮你解决周边玩的问题，让你享受轻松优雅的生活。', 1),
(3, 'WEB_SITE_KEYWORD', 2, '网站关键字', 1, '', '网站搜索引擎关键字', 1378898976, 1381390100, 1, '田觅觅 农旅一体化 O2O 农产品 旅游 美食 田园 甜蜜蜜 觅境 觅趣 觅鲜 觅友 觅食 寻觅', 8),
(4, 'WEB_SITE_CLOSE', 4, '关闭站点', 1, '0:关闭,1:开启', '站点关闭后其他用户不能访问，管理员可以正常访问', 1378898976, 1379235296, 1, '1', 1),
(9, 'CONFIG_TYPE_LIST', 3, '配置类型列表', 4, '', '主要用于数据解析和页面表单的生成', 1378898976, 1379235348, 1, '0:数字\r\n1:字符\r\n2:文本\r\n3:数组\r\n4:枚举', 2),
(10, 'WEB_SITE_ICP', 1, '网站备案号', 1, '', '设置在网站底部显示的备案号，如“沪ICP备12007941号-2', 1378900335, 1379235859, 1, '粤ICP备14093215号-2', 9),
(11, 'DOCUMENT_POSITION', 3, '文档推荐位', 2, '', '文档推荐位，推荐到多个位置KEY值相加即可', 1379053380, 1379235329, 1, '1:列表页推荐\r\n2:频道页推荐\r\n4:网站首页推荐', 3),
(12, 'DOCUMENT_DISPLAY', 3, '文档可见性', 2, '', '文章可见性仅影响前台显示，后台不收影响', 1379056370, 1379235322, 1, '0:所有人可见\r\n1:仅注册会员可见\r\n2:仅管理员可见', 4),
(13, 'COLOR_STYLE', 4, '后台色系', 1, 'default_color:默认\r\nblue_color:紫罗兰', '后台颜色风格', 1379122533, 1379235904, 1, 'default_color', 10),
(20, 'CONFIG_GROUP_LIST', 3, '配置分组', 4, '', '配置分组', 1379228036, 1384418383, 1, '1:基本\r\n2:内容\r\n3:用户\r\n4:系统', 4),
(21, 'HOOKS_TYPE', 3, '钩子的类型', 4, '', '类型 1-用于扩展显示内容，2-用于扩展业务处理', 1379313397, 1379313407, 1, '1:视图\r\n2:控制器', 6),
(22, 'AUTH_CONFIG', 3, 'Auth配置', 4, '', '自定义Auth.class.php类配置', 1379409310, 1379409564, 1, 'AUTH_ON:1\r\nAUTH_TYPE:2', 8),
(23, 'OPEN_DRAFTBOX', 4, '是否开启草稿功能', 2, '0:关闭草稿功能\r\n1:开启草稿功能\r\n', '新增文章时的草稿功能配置', 1379484332, 1379484591, 1, '1', 1),
(24, 'DRAFT_AOTOSAVE_INTERVAL', 0, '自动保存草稿时间', 2, '', '自动保存草稿的时间间隔，单位：秒', 1379484574, 1386143323, 1, '60', 2),
(25, 'LIST_ROWS', 0, '后台每页记录数', 2, '', '后台数据每页显示记录数', 1379503896, 1380427745, 1, '10', 10),
(26, 'USER_ALLOW_REGISTER', 4, '是否允许用户注册', 3, '0:关闭注册\r\n1:允许注册', '是否开放用户注册', 1379504487, 1379504580, 1, '0', 3),
(27, 'CODEMIRROR_THEME', 4, '预览插件的CodeMirror主题', 4, '3024-day:3024 day\r\n3024-night:3024 night\r\nambiance:ambiance\r\nbase16-dark:base16 dark\r\nbase16-light:base16 light\r\nblackboard:blackboard\r\ncobalt:cobalt\r\neclipse:eclipse\r\nelegant:elegant\r\nerlang-dark:erlang-dark\r\nlesser-dark:lesser-dark\r\nmidnight:midnight', '详情见CodeMirror官网', 1379814385, 1384740813, 1, 'ambiance', 3),
(28, 'DATA_BACKUP_PATH', 1, '数据库备份根路径', 4, '', '路径必须以 / 结尾', 1381482411, 1381482411, 1, './Data/', 5),
(29, 'DATA_BACKUP_PART_SIZE', 0, '数据库备份卷大小', 4, '', '该值用于限制压缩后的分卷最大长度。单位：B；建议设置20M', 1381482488, 1381729564, 1, '20971520', 7),
(30, 'DATA_BACKUP_COMPRESS', 4, '数据库备份文件是否启用压缩', 4, '0:不压缩\r\n1:启用压缩', '压缩备份文件需要PHP环境支持gzopen,gzwrite函数', 1381713345, 1381729544, 1, '1', 9),
(31, 'DATA_BACKUP_COMPRESS_LEVEL', 4, '数据库备份文件压缩级别', 4, '1:普通\r\n4:一般\r\n9:最高', '数据库备份文件的压缩级别，该配置在开启压缩时生效', 1381713408, 1381713408, 1, '9', 10),
(32, 'DEVELOP_MODE', 4, '开启开发者模式', 4, '0:关闭\r\n1:开启', '是否开启开发者模式', 1383105995, 1383291877, 1, '0', 11),
(33, 'ALLOW_VISIT', 3, '不受限控制器方法', 0, '', '', 1386644047, 1452849524, 1, '0:article/draftbox\r\n1:article/mydocument\r\n2:Category/tree\r\n3:Index/verify\r\n4:file/upload\r\n5:file/download\r\n6:user/updatePassword\r\n7:user/updateNickname\r\n8:user/submitPassword\r\n9:user/submitNickname\r\n10:file/uploadPicture', 0),
(34, 'DENY_VISIT', 3, '超管专限控制器方法', 0, '', '仅超级管理员可访问的控制器方法', 1386644141, 1386644659, 1, '0:Addons/addhook\r\n1:Addons/edithook\r\n2:Addons/delhook\r\n3:Addons/updateHook\r\n4:Admin/getMenus\r\n5:Admin/recordList\r\n6:AuthManager/updateRules\r\n7:AuthManager/tree', 0),
(35, 'REPLY_LIST_ROWS', 0, '回复列表每页条数', 2, '', '', 1386645376, 1387178083, 1, '10', 0),
(36, 'ADMIN_ALLOW_IP', 2, '后台允许访问IP', 4, '', '多个用逗号分隔，如果不配置表示不限制IP访问', 1387165454, 1387165553, 1, '', 12),
(37, 'SHOW_PAGE_TRACE', 4, '是否显示页面Trace', 4, '0:关闭\r\n1:开启', '是否显示页面Trace信息', 1387165685, 1387165685, 1, '0', 1),
(38, 'HOME_LIST_ROWS', 0, '前台列表每页条数', 2, '', '', 1452764673, 1452764723, 1, '10', 0);

-- --------------------------------------------------------

--
-- 表的结构 `tmm_document`
--

DROP TABLE IF EXISTS `tmm_document`;
CREATE TABLE IF NOT EXISTS `tmm_document` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '文档ID',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `name` char(40) NOT NULL DEFAULT '' COMMENT '标识',
  `title` char(80) NOT NULL COMMENT '标题',
  `category_id` int(10) unsigned NOT NULL COMMENT '所属分类',
  `description` char(140) NOT NULL COMMENT '描述',
  `root` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '根节点',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属ID',
  `model_id` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '内容模型ID',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '2' COMMENT '内容类型',
  `position` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '推荐位',
  `link_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '外链',
  `cover_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '封面',
  `display` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '可见性',
  `deadline` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '截至时间',
  `attach` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '附件数量',
  `view` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '浏览量',
  `comment` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '评论数',
  `extend` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '扩展统计字段',
  `level` int(10) NOT NULL DEFAULT '0' COMMENT '优先级',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '数据状态',
  PRIMARY KEY (`id`),
  KEY `idx_category_status` (`category_id`,`status`),
  KEY `idx_status_type_pid` (`status`,`uid`,`pid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='文档模型基础表' AUTO_INCREMENT=26 ;

--
-- 转存表中的数据 `tmm_document`
--

INSERT INTO `tmm_document` (`id`, `uid`, `name`, `title`, `category_id`, `description`, `root`, `pid`, `model_id`, `type`, `position`, `link_id`, `cover_id`, `display`, `deadline`, `attach`, `view`, `comment`, `extend`, `level`, `create_time`, `update_time`, `status`) VALUES
(1, 1, '', '品牌经理', 2, '', 0, 0, 2, 2, 0, 0, 0, 1, 0, 0, 8, 0, 0, 0, 1387260660, 1452837619, 1),
(2, 1, '', '市场总监', 2, '', 0, 0, 2, 2, 0, 0, 5, 1, 0, 0, 0, 0, 0, 0, 1452766680, 1452838089, 1),
(3, 1, '', '国家旅游局拟多项举措促乡村旅游发展', 40, '8月18日，全国乡村旅游提升与旅游扶贫推进会议在安徽黄山市召开。国家旅游局局长李金早在会上表示，要充分发挥乡村旅游在扶贫开发中的战略作用，着力将乡村旅游建设成为美丽乡村的重要载体。', 0, 0, 2, 2, 0, 0, 1, 1, 0, 0, 0, 0, 0, 0, 1452766940, 1452766940, 1),
(4, 1, '', '客家文化之乡、金柚之乡', 40, '8月8-9日，田觅觅工作小组在九州田园张德林董事长和中华永续城市发展教育基金会、广东省海峡两岸交流促进会农业委员会常务副会长何培才老师的陪同下，踏访了客家文化之乡、金柚之乡——梅州市的大埔县和平远县。', 0, 0, 2, 2, 0, 0, 2, 1, 0, 0, 0, 0, 0, 0, 1452766980, 1452826892, 1),
(5, 1, '', ' 广东土围楼中规模最大', 40, ' 广东土围楼中规模最大 广东土围楼中规模最大 广东土围楼中规模最大', 0, 0, 2, 2, 0, 0, 2, 1, 0, 0, 0, 0, 0, 0, 1452822390, 1452822390, 1),
(6, 1, '', '田觅觅介绍', 41, '平台即时将运营商管辖区域的供应商订单收入存入钱包，运营商可随时登录平台查看农旅资源收益及详情，从而更好地规划运营方案。', 0, 0, 2, 2, 0, 0, 4, 1, 0, 0, 0, 0, 0, 0, 1452836949, 1452836949, 1),
(7, 1, '', '社区运营经理', 2, '', 0, 0, 2, 2, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 1452837735, 1452837735, 1),
(8, 1, '', '旅游产品经理', 2, '', 0, 0, 2, 2, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 1452837756, 1452837756, 1),
(9, 1, '', 'PHP工程师', 2, '', 0, 0, 2, 2, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 1452837790, 1452837790, 1),
(10, 1, '', '人力资源专员', 2, '', 0, 0, 2, 2, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 1452837821, 1452837821, 1),
(11, 1, '', '北京严整出境游 三家旅行社出境游被叫停', 40, '神舟国旅相关人员透露，被叫停出境游的主要原因是出境游客非法滞留。神舟国旅相关人员透露，被叫停出境游的主要原因是出境游客非法滞留。神舟国旅相关人员透露，被叫停出境游的主要原因是出境游客非法滞留。', 0, 0, 2, 2, 0, 0, 3, 1, 0, 0, 0, 0, 0, 0, 1452838785, 1452838785, 1),
(12, 1, '', '一二三四五六七八九十一二三四五六七八九十一二三四五六七八九十一二三四五六', 40, '一二三四五六七八九十一二三四五六七八九十一二三四五六七八九十一二三四五六七八九十一二三四五六七八九十一二三四五六七八九十一二三四五六七八九十一二三四五六七八九十一二三四五六七八九十一二三四五六七八九十一二三四五六七八九十一二三四五六七八九十一二三四五六七八九十一二三四五六七八九十', 0, 0, 2, 2, 0, 0, 6, 1, 0, 0, 0, 0, 0, 0, 1452838800, 1452845169, 1),
(13, 1, '', '旅游，乐哉？悠哉？', 41, '闻牧歌唱晚，声声悠扬\r\n\r\n视衰草萋萋，浩渺远方\r\n\r\n细数昨日残阳\r\n\r\n\r\n挥泪洒琼江\r\n', 0, 0, 2, 2, 0, 0, 7, 1, 0, 0, 0, 0, 0, 0, 1452839785, 1452839785, 1),
(14, 1, '', '一条鲤鱼的自白', 41, '我是一条鲤鱼，以前生活在一片从来没有游到过尽头的湖泊中，直到有一天，我和几十个一起出游的小伙伴被一只大网网住后，就被放在了这个叫黑坑的地方，这个新家，一点也不大，而且水质特别差......', 0, 0, 2, 2, 0, 0, 5, 1, 0, 0, 0, 0, 0, 0, 1452839940, 1452839991, 1),
(15, 1, '', '旅游文明从“厕所”开始', 41, '为取消四星级、五星级以及奢华厕所点赞！景区的五星级不在于奢华厕所，而应在于售票员对游客的一个微笑，检票员对游客的一些尊重，讲解员对游客的一份耐心…总之和五星级厕所的马桶材质无关。', 0, 0, 2, 2, 0, 0, 8, 1, 0, 0, 0, 0, 0, 0, 1452840043, 1452840043, 1),
(16, 1, '', '人生在勤，不索何获？', 41, '不要以为那些活得光鲜的人只是运气好，因为你没有看到他们的努力。更因为没有一件事是不通过努力就会平白无故的成功，人生在勤，不索何获？', 0, 0, 2, 2, 0, 0, 9, 1, 0, 0, 0, 0, 0, 0, 1452840162, 1452840162, 1),
(17, 1, '', '沐爱鹞鹰岩山、洞、路杂记', 41, '筠连县沐爱场东出五、六百米，有座叫鹞鹰岩的山。儿时在老家的时候，远远望着这座山，以及这座山左边连绵不断的潘家山（兴隆村）、右边的火星山（峨坪村）一排山峰，挡着了看见沐爱场和看见山那边一切景物的视线。对它有些埋怨：这匹山为什么长那么高呢？', 0, 0, 2, 2, 0, 0, 10, 1, 0, 0, 0, 0, 0, 0, 1452840360, 1452840535, 1),
(18, 1, '', '旅游局拟多项举措促乡村旅游发展', 40, '国家旅游局拟多项举措促乡村旅游发展', 0, 0, 2, 2, 0, 0, 11, 1, 0, 0, 0, 0, 0, 0, 1452841740, 1452849536, 1),
(19, 3, '', 'android开发', 2, '', 0, 0, 2, 2, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 1452842217, 1452842217, 1),
(20, 3, '', '测试工程师', 2, '', 0, 0, 2, 2, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 1452842228, 1452842228, 1),
(21, 3, '', 'iOS开发', 2, '', 0, 0, 2, 2, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 1452842238, 1452842238, 1),
(22, 3, '', '测试开发', 2, '', 0, 0, 2, 2, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 1452842260, 1452842260, 1),
(23, 3, '', '文字编辑', 2, '', 0, 0, 2, 2, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 1452842275, 1452842275, 1),
(24, 3, '', '市场总监', 2, '', 0, 0, 2, 2, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 1452842280, 1452848137, 1),
(25, 3, '', '我游千岛湖', 40, '我想决心以生态文化为品牌，做到自然和人的和谐才是永恒的主题。听着这热气腾腾的导游介绍，我分明感受到另一道风景——人的风景。自然的、原始的、地方特色的，也许就是最好的风景。而科学的、文明的、理智的人，则肯定是风景中的风景啊！', 0, 0, 2, 2, 0, 0, 3, 1, 0, 0, 0, 0, 0, 0, 1452850968, 1452850968, 1);

-- --------------------------------------------------------

--
-- 表的结构 `tmm_document_article`
--

DROP TABLE IF EXISTS `tmm_document_article`;
CREATE TABLE IF NOT EXISTS `tmm_document_article` (
  `id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文档ID',
  `parse` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '内容解析类型',
  `content` text NOT NULL COMMENT '文章内容',
  `template` varchar(100) NOT NULL DEFAULT '' COMMENT '详情页显示模板',
  `bookmark` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '收藏数',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='文档模型文章表';

--
-- 转存表中的数据 `tmm_document_article`
--

INSERT INTO `tmm_document_article` (`id`, `parse`, `content`, `template`, `bookmark`) VALUES
(1, 0, '<h1>\r\n	<p style="font-family:''Microsoft YaHei'';font-size:18px;vertical-align:baseline;color:#403A39;">\r\n		<p style="font-size:13px;">\r\n			职位描述：\r\n		</p>\r\n		<p style="font-size:13px;">\r\n			1、 负责项目品牌策划方案的规划，并组织实施；\r\n		</p>\r\n		<p style="font-size:13px;">\r\n			2、 负责制定和完善项目的整体营销策划和具体实施方案；\r\n		</p>\r\n		<p style="font-size:13px;">\r\n			3、 负责展会、会议、活动等相关渠道拓展、洽谈，促进品牌合作；\r\n		</p>\r\n		<p style="font-size:13px;">\r\n			4、 微信公众号内容更新及推广、维护；\r\n		</p>\r\n		<p style="font-size:13px;">\r\n			5、 互联网渠道品牌推广及维护。\r\n		</p>\r\n		<p style="font-size:13px;">\r\n			任职资格：&nbsp;\r\n		</p>\r\n		<p style="font-size:13px;">\r\n			1、熟练掌握品牌策划、定位、广告策划、创意、推广的系列专业技巧；\r\n		</p>\r\n		<p style="font-size:13px;">\r\n			2、有3年以上策划经验，操作过完整的项目品牌策划优先；\r\n		</p>\r\n		<p style="font-size:13px;">\r\n			3、创意独到，思维活跃，文笔犀利，善于各种形式文案撰写；\r\n		</p>\r\n		<p style="font-size:13px;">\r\n			4、善于沟通、组织协调能力强，有着良好作品品质鉴赏能力；\r\n		</p>\r\n		<p style="font-size:13px;">\r\n			5、对工作有着极强的责任心，充满热情，能够给团队带来鲜活冲力\r\n		</p>\r\n		<p style="font-size:13px;">\r\n			6、能够承受一定的工作压力, 用心去解决工作过程中遇到的困难与挫折。\r\n		</p>\r\n	</p>\r\n	<p style="font-family:''Microsoft YaHei'';font-size:18px;vertical-align:baseline;color:#403A39;">\r\n		<br />\r\n	</p>\r\n</h1>\r\n<p>\r\n	<strong></strong> \r\n</p>', '', 0),
(2, 0, '<p style="font-family:''Microsoft YaHei'';font-size:18px;vertical-align:baseline;color:#403A39;">\r\n	<span style="font-size:13px;font-family:''sans serif'', tahoma, verdana, helvetica;line-height:1.5;">职位描述：</span>\r\n</p>\r\n<p style="font-size:13px;">\r\n	1、负责O2O整体品牌战略和各终端产品的市场推广策略。\r\n</p>\r\n<p style="font-size:13px;">\r\n	2、通过线上线下达成品牌及产品推广目标。\r\n</p>\r\n<p style="font-size:13px;">\r\n	3、根据业务发展、用户需求和市场研究情况，参与制定公司O2O产品的市场发展战略以及市场发展目标。\r\n</p>\r\n<p style="font-size:13px;">\r\n	4、拓展公司的市场策略，把握公司在行业中的发展方向，完成公司在行业中的市场定位和市场机会分析，及时提供市场反馈。\r\n</p>\r\n<p style="font-size:13px;">\r\n	5、建立并管理O2O的市场招商团队，并完成年度招商目标。\r\n</p>\r\n<p style="font-size:13px;">\r\n	6、组织和实施各类大型市场招商活动。\r\n</p>\r\n<p style="font-size:13px;">\r\n	<br />\r\n</p>\r\n<p style="font-size:13px;">\r\n	任职资格：\r\n</p>\r\n<p style="font-size:13px;">\r\n	1、全日制本科以上学历，10年以上市场领域工作经验；\r\n</p>\r\n<p style="font-size:13px;">\r\n	2、对互联网的营销方式、品牌推广有深厚的积累和独立见解，粉丝营销、饥饿营销、社区运营等相关互联网的市场推广方式。\r\n</p>\r\n<p style="font-size:13px;">\r\n	3、精通互联网市场营销，熟悉各类媒体运作方式，具有新媒体的相关从业经验。\r\n</p>\r\n<p style="font-size:13px;">\r\n	4、熟悉常用的互联网/移动营销和推广模式，对O2O产品推广有深刻的理解，了解互联网或无线互联网的产品发展方向及渠道战略。\r\n</p>\r\n<p style="font-size:13px;">\r\n	5、具有敏锐的商业和市场意识，具备很强的策划能力，极强的工作执行力、具有优秀的资源整合能力和业务推进能力。\r\n</p>\r\n<p style="font-size:13px;">\r\n	6、善于沟通、思维灵活，执行力强，合作意识强。\r\n</p>\r\n<p style="font-size:13px;">\r\n	7、有BAT或OTA平台市场推广和招商经验优先\r\n</p>', '', 0),
(3, 0, '<span style="color:#403A39;font-family:''Microsoft YaHei'';font-size:18px;line-height:36px;">8月18日，全国乡村旅游提升与旅游扶贫推进会议在安徽黄山市召开。国家旅游局局长李金早在会上表示，要充分发挥乡村旅游在扶贫开发中的战略作用，着力将乡村旅游建设成为美丽乡村的重要载体。</span><span style="color:#403A39;font-family:''Microsoft YaHei'';font-size:18px;line-height:36px;">8月18日，全国乡村旅游提升与旅游扶贫推进会议在安徽黄山市召开。国家旅游局局长李金早在会上表示，要充分发挥乡村旅游在扶贫开发中的战略作用，着力将乡村旅游建设成为美丽乡村的重要载体。</span><span style="color:#403A39;font-family:''Microsoft YaHei'';font-size:18px;line-height:36px;">8月18日，全国乡村旅游提升与旅游扶贫推进会议在安徽黄山市召开。国家旅游局局长李金早在会上表示，要充分发挥乡村旅游在扶贫开发中的战略作用，着力将乡村旅游建设成为美丽乡村的重要载体。</span><span style="color:#403A39;font-family:''Microsoft YaHei'';font-size:18px;line-height:36px;">8月18日，全国乡村旅游提升与旅游扶贫推进会议在安徽黄山市召开。国家旅游局局长李金早在会上表示，要充分发挥乡村旅游在扶贫开发中的战略作用，着力将乡村旅游建设成为美丽乡村的重要载体。</span><span style="color:#403A39;font-family:''Microsoft YaHei'';font-size:18px;line-height:36px;">8月18日，全国乡村旅游提升与旅游扶贫推进会议在安徽黄山市召开。国家旅游局局长李金早在会上表示，要充分发挥乡村旅游在扶贫开发中的战略作用，着力将乡村旅游建设成为美丽乡村的重要载体。</span><span style="color:#403A39;font-family:''Microsoft YaHei'';font-size:18px;line-height:36px;">8月18日，全国乡村旅游提升与旅游扶贫推进会议在安徽黄山市召开。国家旅游局局长李金早在会上表示，要充分发挥乡村旅游在扶贫开发中的战略作用，着力将乡村旅游建设成为美丽乡村的重要载体。</span><span style="color:#403A39;font-family:''Microsoft YaHei'';font-size:18px;line-height:36px;">8月18日，全国乡村旅游提升与旅游扶贫推进会议在安徽黄山市召开。国家旅游局局长李金早在会上表示，要充分发挥乡村旅游在扶贫开发中的战略作用，着力将乡村旅游建设成为美丽乡村的重要载体。</span>', '', 0),
(4, 0, '<p>\r\n	<span style="color:#403A39;font-family:''Microsoft YaHei'';font-size:16px;line-height:16px;"><img src="/Uploads/Editor/2016-01-15/5698602260390.jpg" alt="" /><br />\r\n</span> \r\n</p>\r\n<p>\r\n	<span style="color:#403A39;font-family:''Microsoft YaHei'';font-size:16px;line-height:16px;">8月8-9日，田觅觅工作小组在九州田园张德林董事长和中华永续城市发展教育基金会、广东省海峡两岸交流促进会农业委员会常务副会长何培才老师的陪同下，踏访了客家文化之乡、金柚之乡——梅州市的大埔县和平远县。 广东土围楼中规模最大、设计最精美、保存最完整的民居古建筑花萼楼、“华侨之乡”百侯镇、有梅州市第二个客天下之称的双髻山森林公园、大东梯田、金穗休闲养生旅游产业园等都在本次的踏访名单内。</span><span style="color:#403A39;font-family:''Microsoft YaHei'';font-size:16px;line-height:16px;">8月8-9日，田觅觅工作小组在九州田园张德林董事长和中华永续城市发展教育基金会、广东省海峡两岸交流促进会农业委员会常务副会长何培才老师的陪同下，踏访了客家文化之乡、金柚之乡——梅州市的大埔县和平远县。 广东土围楼中规模最大、设计最精美、保存最完整的民居古建筑花萼楼、“华侨之乡”百侯镇、有梅州市第二个客天下之称的双髻山森林公园、</span> \r\n</p>\r\n<p>\r\n	<span style="color:#403A39;font-family:''Microsoft YaHei'';font-size:16px;line-height:16px;"><img src="/Uploads/Editor/2016-01-15/56986030268c8.jpg" alt="" /><br />\r\n</span> \r\n</p>\r\n<p>\r\n	<span style="color:#403A39;font-family:''Microsoft YaHei'';font-size:16px;line-height:16px;"><br />\r\n</span> \r\n</p>\r\n<p>\r\n	<span style="color:#403A39;font-family:''Microsoft YaHei'';font-size:16px;line-height:16px;">大东梯田、金穗休闲养生旅游产业园等都在本次的踏访名单内。</span><span style="color:#403A39;font-family:''Microsoft YaHei'';font-size:16px;line-height:16px;">8月8-9日，<img src="/Uploads/Editor/2016-01-15/569861096f1b2.png" alt="" />田觅觅工作小组在九州田园张德林董事长和中华永续城市发展教育基金会、广东省海峡两岸交流促进会农业委员会常务副会长何培才老师的陪同下，踏访了客家文化之乡、金柚之乡——梅州市的大埔县和平远县。 广东土围楼中规模最大、设计最精美、保存最完整的民居古建筑花萼楼、“华侨之乡”百侯镇、有梅州市第二个客天下之称的双髻山森林公园、大东梯田、金穗休闲养生旅游产业园等都在本次的踏访名单内。</span><span style="color:#403A39;font-family:''Microsoft YaHei'';font-size:16px;line-height:16px;">8月8-9日，田觅觅工作小组在九州田园张德林董事长和中华永续城市发展教育基金会、广东省海峡两岸交流促进会农业委员会常务副会长何培才老师的陪同下，踏访了客家文化之乡、金柚之乡——梅州市的大埔县和平远县。 广东土围楼中规模最大、设计最精美、保存最完整的民居古建筑花萼楼、“华侨之乡”百侯镇、有梅州市第二个客天下之称的双髻山森林公园、大东梯田、金穗休闲养生旅游产业园等都在本次的踏访名单内。</span> \r\n</p>', '', 0),
(5, 0, '<span style="color:#403A39;font-family:''Microsoft YaHei'';font-size:16px;line-height:16px;">&nbsp;广东土围楼中规模最大</span><span style="color:#403A39;font-family:''Microsoft YaHei'';font-size:16px;line-height:16px;">&nbsp;广东土围楼中规模最大</span><span style="color:#403A39;font-family:''Microsoft YaHei'';font-size:16px;line-height:16px;">&nbsp;广东土围楼中规模最大</span><span style="color:#403A39;font-family:''Microsoft YaHei'';font-size:16px;line-height:16px;">&nbsp;广东土围楼中规模最大</span><span style="color:#403A39;font-family:''Microsoft YaHei'';font-size:16px;line-height:16px;">&nbsp;广东土围楼中规模最大</span><span style="color:#403A39;font-family:''Microsoft YaHei'';font-size:16px;line-height:16px;">&nbsp;广东土围楼中规模最大</span><span style="color:#403A39;font-family:''Microsoft YaHei'';font-size:16px;line-height:16px;">&nbsp;广东土围楼中规模最大</span><span style="color:#403A39;font-family:''Microsoft YaHei'';font-size:16px;line-height:16px;">&nbsp;广东土围楼中规模最大</span><span style="color:#403A39;font-family:''Microsoft YaHei'';font-size:16px;line-height:16px;">&nbsp;广东土围楼中规模最大</span><span style="color:#403A39;font-family:''Microsoft YaHei'';font-size:16px;line-height:16px;">&nbsp;广东土围楼中规模最大</span><span style="color:#403A39;font-family:''Microsoft YaHei'';font-size:16px;line-height:16px;">&nbsp;广东土围楼中规模最大</span>', '', 0),
(6, 0, '<p>\r\n	<span style="color:#403A39;font-family:''Microsoft YaHei'';font-size:18px;line-height:36px;">田觅觅——特色乡村心入口。带城里人寻觅乡村，寻觅美好，回到山、水、天地与自然的归宿。引领人们享受自然和自在，唤醒身心本能的需求，吸收最原始最纯净的生活源素。</span><img src="/Public/Home/images/business-model.png" alt="田觅觅商业模式" />\r\n</p>\r\n<p>\r\n	<h2 style="font-weight:normal;font-family:''Microsoft YaHei'';font-size:28px;vertical-align:baseline;color:#403A39;">\r\n		多种方式个性推荐\r\n	</h2>\r\n	<p style="font-family:''Microsoft YaHei'';font-size:18px;vertical-align:baseline;color:#808080;">\r\n		通过地理位置定位，推荐给符合用户当前位置的周边农旅资源；根据用户的搜索记录，推荐搜索热词相关的农旅资源；通过用户个性标签，推荐符合用户标签的农旅资源。\r\n	</p>\r\n	<p>\r\n		<br />\r\n	</p>\r\n	<h2 style="font-weight:normal;font-family:''Microsoft YaHei'';font-size:28px;vertical-align:baseline;color:#403A39;">\r\n		自定义旅游内容\r\n	</h2>\r\n	<p style="font-family:''Microsoft YaHei'';font-size:18px;vertical-align:baseline;color:#808080;">\r\n		用户可根据自己的旅游需求与爱好，选择农旅资源项目，组合成景点、线路；同时用户也可随意组合多个景点，规划自己的旅游线路。\r\n	</p>\r\n	<p>\r\n		<br />\r\n	</p>\r\n	<h2 style="font-weight:normal;font-family:''Microsoft YaHei'';font-size:28px;vertical-align:baseline;color:#403A39;">\r\n		快捷下单支付\r\n	</h2>\r\n	<p style="font-family:''Microsoft YaHei'';font-size:18px;vertical-align:baseline;color:#808080;">\r\n		选择农旅资源，轻松填写订单信息，快速完成订单支付。支付方式可支持钱包支付、支付宝、微信、信用卡等方式。\r\n	</p>\r\n	<h2 style="font-weight:normal;font-family:''Microsoft YaHei'';font-size:28px;vertical-align:baseline;color:#403A39;">\r\n		灵活管理农旅资源\r\n	</h2>\r\n	<p style="font-family:''Microsoft YaHei'';font-size:18px;vertical-align:baseline;color:#808080;">\r\n		可建立多个子账号对农旅资源进行分权管理，如一个子账号负责农旅资源的管理，另一子账号负责订单接收、扫码消费等功能，各子账号各施其职，提高供应商服务效率。\r\n	</p>\r\n	<p>\r\n		<br />\r\n	</p>\r\n	<p>\r\n		<h2 style="font-weight:normal;font-family:''Microsoft YaHei'';font-size:28px;vertical-align:baseline;color:#403A39;">\r\n			即时处理用户订单\r\n		</h2>\r\n		<p style="font-family:''Microsoft YaHei'';font-size:18px;vertical-align:baseline;color:#808080;">\r\n			当用户下单时，平台通过各种消息推送机制（如手机短信、站内通知、邮件通知等），立刻通知供应商处理用户订单。供应商随时随地登录APP，查询、处理我的订单，让订单不再等待。\r\n		</p>\r\n		<h2 style="font-weight:normal;font-family:''Microsoft YaHei'';font-size:28px;vertical-align:baseline;color:#403A39;">\r\n			随时关注营收明细\r\n		</h2>\r\n		<p style="font-family:''Microsoft YaHei'';font-size:18px;vertical-align:baseline;color:#808080;">\r\n			供应商接收订单时显示其所得收入，订单完成时即时将用户收入存入钱包；同时可通过我的钱包随时查询主、子账号的农旅资源营收详细情况。\r\n		</p>\r\n		<p>\r\n			<br />\r\n		</p>\r\n		<p>\r\n			<h2 style="font-weight:normal;font-family:''Microsoft YaHei'';font-size:28px;vertical-align:baseline;color:#403A39;">\r\n				灵活管理资源\r\n			</h2>\r\n			<p style="font-family:''Microsoft YaHei'';font-size:18px;vertical-align:baseline;color:#808080;">\r\n				当地资源部门组成区域运营商，平台分配运营商区域权限，统一管理所在区域供应商以及供应商的农旅资源。\r\n			</p>\r\n			<h2 style="font-weight:normal;font-family:''Microsoft YaHei'';font-size:28px;vertical-align:baseline;color:#403A39;">\r\n				轻松完成区域运营\r\n			</h2>\r\n			<p style="font-family:''Microsoft YaHei'';font-size:18px;vertical-align:baseline;color:#808080;">\r\n				提供完善的分销体系功能、营销管理功能（如红包功能、优惠券功能、代金券功能等方式进行营销以及各种促销功能）轻松帮助运营商销售农旅资源。\r\n			</p>\r\n			<p>\r\n				<br />\r\n			</p>\r\n			<p>\r\n				<h2 style="font-weight:normal;font-family:''Microsoft YaHei'';font-size:28px;vertical-align:baseline;color:#403A39;">\r\n					运营收入即时监控\r\n				</h2>\r\n				<p style="font-family:''Microsoft YaHei'';font-size:18px;vertical-align:baseline;color:#808080;">\r\n					平台即时将运营商管辖区域的供应商订单收入存入钱包，运营商可随时登录平台查看农旅资源收益及详情，从而更好地规划运营方案。\r\n				</p>\r\n			</p>\r\n		</p>\r\n	</p>\r\n</p>', '', 0),
(11, 0, '<p style="font-family:Simsun;font-size:14px;">\r\n	本报记者罗丹报道 从今天开始，出境游组团将不得“代办签证”，谁组团就由谁签证就由谁负责。在昨天下午出台的《北京市整顿和规范出境旅游市场秩序工作方案》里，对出境游市场的管理和整顿进行了一系列新规定。\r\n</p>\r\n<p style="font-family:Simsun;font-size:14px;">\r\n	　<strong>　三家旅行社被取消出境游资格</strong>\r\n</p>\r\n<p style="font-family:Simsun;font-size:14px;">\r\n	　　在方案执行的同时，北京有三家旅行社被取消了经营出境游的资格。<span id="ad_dst1"></span>\r\n</p>\r\n<p style="font-family:Simsun;font-size:14px;">\r\n	&nbsp; &nbsp;他们分别是：招商局旅游总公司、中商国际旅行社、北京神舟国际旅行社。这三家公司的出境游业务被暂停，进行内部整改并接受旅游局的检查。\r\n</p>\r\n<p style="font-family:Simsun;font-size:14px;">\r\n	　　除此之外，中国民间国际旅游公司将被停止出境游业务3个月，接受整改，3个月后视情况再定处理结果。\r\n</p>\r\n<p style="font-family:Simsun;font-size:14px;">\r\n	　　神舟国旅相关人员透露，被叫停出境游的主要原因是出境游客非法滞留。\r\n</p>\r\n<p style="font-family:Simsun;font-size:14px;">\r\n	　　<strong>新方案严格治理出境游</strong>\r\n</p>\r\n<p style="font-family:Simsun;font-size:14px;">\r\n	　　在工作方案中，除了明令禁止“代办签证”之外，还公布了一系列对拥有出境游资格的旅行社的整改管理办法。其中包括对于出境游组团社要和聘用人签订劳动合同、制订档案管理制度。签证专办员只能为本社办理签证业务，一旦离职就必须将其专办卡交回给北京市旅游局，并立即书面通知目的地国使馆。\r\n</p>\r\n<p style="font-family:Simsun;font-size:14px;">\r\n	　　除了对出境组团社严格要求外，方案特别增加了对出境游组团社门市部的要求。北京市旅游局还将大力打击“零负团费”的问题，对于恶意降价影响出境游市场正常秩序的行为进行惩处。市民及旅行社均可举报出境游低于成本价销售的现象。\r\n</p>\r\n<p style="font-family:Simsun;font-size:14px;">\r\n	　　举报电话：65150198；\r\n</p>\r\n<p style="font-family:Simsun;font-size:14px;">\r\n	　　电子信箱：lvyou_zfdd@sina.com.\r\n</p>\r\n<p style="font-family:Simsun;font-size:14px;">\r\n	　　<strong>链接</strong>\r\n</p>\r\n<p style="font-family:Simsun;font-size:14px;">\r\n	　　北京共有69家出境游组团社，出境游领队为2161人；\r\n</p>\r\n<p style="font-family:Simsun;font-size:14px;">\r\n	　　自1997年起到2004年止，北京地区出境人数累计为168万人次，平均年递增45%。\r\n</p>\r\n<span style="font-family:Simsun;font-size:14px;line-height:20px;">搜狗(</span><a href="http://www.sogou.com/" target="_blank">www.sogou.com</a><span style="font-family:Simsun;font-size:14px;line-height:20px;">)搜索:“</span><a href="http://www.sogou.com/web?query=%B3%F6%BE%B3%D3%CE&amp;sogouhome=&amp;pid=sohunews" target="_blank">出境游</a><span style="font-family:Simsun;font-size:14px;line-height:20px;">”,共找到</span><span><b>&nbsp;763,559&nbsp;</b></span><span style="font-family:Simsun;font-size:14px;line-height:20px;">个相关网页.</span>', '', 0),
(7, 0, '<p style="font-size:13px;">\r\n	职位描述：\r\n</p>\r\n<p style="font-size:13px;">\r\n	1、根据不同属性的目标人群，建立社群；\r\n</p>\r\n<p style="font-size:13px;">\r\n	2、制定并完善社群运营计划及相关制度，推动社群良性发展，提升人气；\r\n</p>\r\n<p style="font-size:13px;">\r\n	3、策划并实施社群线下各类活动，提升社群粘性及活跃度；\r\n</p>\r\n<p style="font-size:13px;">\r\n	4、招募并管理社区达人，维护达人关系并发挥其价值；\r\n</p>\r\n<p style="font-size:13px;">\r\n	5、挖掘和把握社群各层次需求，并保持和用户的沟通。\r\n</p>\r\n<p style="font-size:13px;">\r\n	任职资格：\r\n</p>\r\n<p style="font-size:13px;">\r\n	1、资深网虫，有社区网站或活动运营经验，熟悉网络社区运营模式；熟悉微博、微信、百度贴吧、豆瓣、人人网等SNS平台；\r\n</p>\r\n<p style="font-size:13px;">\r\n	2、有较强的语言文字表达能力和敏锐的活动策划和执行能力，创意出彩，善于制造话题和策划社区活动，并引导用户参与互动；\r\n</p>\r\n<p style="font-size:13px;">\r\n	3、需要人选有创意有爆点、脑洞大开；懂市场懂营销；\r\n</p>\r\n<p style="font-size:13px;">\r\n	4、热爱旅游，具备良好的职业素质，善于应对处理各种突发状况。\r\n</p>', '', 0),
(8, 0, '<p style="font-size:13px;">\r\n	职位描述：\r\n</p>\r\n<p style="font-size:13px;">\r\n	1.负责根据产品定位、市场趋势制定内容战略规划，设计旅游产品，丰富线路资源；\r\n</p>\r\n<p style="font-size:13px;">\r\n	2.负责帮助资源提供方进行产品线路文档的编写，管理及上线工作；\r\n</p>\r\n<p style="font-size:13px;">\r\n	3.有能力分析，独立策划、执行重点旅游产品的标准化开发并制定相应SOP\r\n</p>\r\n<p style="font-size:13px;">\r\n	4.配合产品运营、推广和宣传，提供相应产品支持，配合提供专题相关内容；\r\n</p>\r\n<p style="font-size:13px;">\r\n	5.进行产品与运营模式的内外部调研分析，收集用户反馈并进行数据分析；\r\n</p>\r\n<p style="font-size:13px;">\r\n	6.供应商的开发、采购与合作，完成考察评估、合作洽谈议价、合同管理、后期维护等工作；\r\n</p>\r\n<p style="font-size:13px;">\r\n	7. 关注并调研行业动向以及竞争对手产品并提出产品改进方案。\r\n</p>\r\n<p style="font-size:13px;">\r\n	任职资格：\r\n</p>\r\n<p style="font-size:13px;">\r\n	1.2年以上旅游产品相关工作经验\r\n</p>\r\n<p style="font-size:13px;">\r\n	2.热爱旅游，关注社交产品及社会化产品的行业动向，关注互联网和移动互联网行业趋势、用户体验和产品细节；\r\n</p>\r\n<p style="font-size:13px;">\r\n	3.思路清晰，思维活跃，善于与他人尤其是旅行社联系合作；\r\n</p>\r\n<p style="font-size:13px;">\r\n	4.具有一定的线路策划能力，较强的旅游市场触觉和服务意识，能够提供丰富的旅游相关选题；\r\n</p>\r\n<p style="font-size:13px;">\r\n	5.有创造力，具有优秀的协调能力和沟通能力，较好的计划能力、组织能力和创新能力；\r\n</p>\r\n<p style="font-size:13px;">\r\n	6.有责任感和进取心，抗压能力强，积极乐观，有良好的团队合作意识；\r\n</p>\r\n<p style="font-size:13px;">\r\n	7.具有本科以上学历，有旅行社工作经验者尤其是自己策划过行程的人士优先。\r\n</p>', '', 0),
(9, 0, '<p style="font-size:13px;">\r\n	职位描述：\r\n</p>\r\n<p style="font-size:13px;">\r\n	1.负责开发公司网站后台、运营系统等核心模块\r\n</p>\r\n<p style="font-size:13px;">\r\n	2.负责网站平台相关业务分析、原型实现，完成产品研发\r\n</p>\r\n<p style="font-size:13px;">\r\n	3.配合研发经理进行技术决策，进行技术风险评估\r\n</p>\r\n<p style="font-size:13px;">\r\n	4.协助、指导工程师解决关键问题，设计开发关键性功能模块\r\n</p>\r\n<p style="font-size:13px;">\r\n	5.团队管理，指导并带领普通程序员进行技术开发\r\n</p>\r\n<p style="font-size:13px;">\r\n	任职要求：\r\n</p>\r\n<p style="font-size:13px;">\r\n	1.良好的英文读写能力；\r\n</p>\r\n<p style="font-size:13px;">\r\n	2.三年以上互联网开发经验，有SOAP、Restful等Web Service开发经验者优先；\r\n</p>\r\n<p style="font-size:13px;">\r\n	3.精通PHP开发语言，深入了解PHP的性能最优化和安全最大化；\r\n</p>\r\n<p style="font-size:13px;">\r\n	4.深入研究PHP框架，了解yii、Joomla、Symfony、Drupal、Laravel等不同框架的优缺点；\r\n</p>\r\n<p style="font-size:13px;">\r\n	5.具有SQL索引优化、查询优化和存储优化经验，能单独进行数据库设计；\r\n</p>\r\n<p style="font-size:13px;">\r\n	6.有良好的编码习惯，结构清晰，命名规范，逻辑性强，能迅速代码中的错误并加以改正；\r\n</p>\r\n<p style="font-size:13px;">\r\n	7.熟悉面向对象的软件设计方法，对面向对象的设计模式有较深的理解并能熟练应用；\r\n</p>\r\n<p style="font-size:13px;">\r\n	8.具备很好的学习钻研能力，有很强的事业心和进取精神，能承受一定的工作压力\r\n</p>\r\n<p style="font-size:13px;">\r\n	9.具备强烈的责任心，对工作有激情，良好的沟通能力，良好的团队合作精神。\r\n</p>\r\n<p style="font-size:13px;">\r\n	加分项\r\n</p>\r\n<p style="font-size:13px;">\r\n	1. 有个人网站，在上面发表过原创的技术文章\r\n</p>\r\n<p style="font-size:13px;">\r\n	2. 有 GitHub 账号，可以看到你的开源代码\r\n</p>\r\n<p style="font-size:13px;">\r\n	3. 如有独立的作品，请附在简历中\r\n</p>\r\n<p style="font-size:13px;">\r\n	4. 懂科学上网，可使用英文关键字搜索或者查看英文文档1.负责开发公司网站后台\r\n</p>', '', 0),
(10, 0, '<p style="font-size:13px;">\r\n	职位描述：\r\n</p>\r\n<p style="font-size:13px;">\r\n	1、根据公司整体战略，全面统筹制定事业部人力资源战略规划，并在实施中不断优化和提升；\r\n</p>\r\n<p style="font-size:13px;">\r\n	2、深入了解业务发展以及团队特点，结合组织战略，协助开展各项HR相关工作；\r\n</p>\r\n<p style="font-size:13px;">\r\n	3、参与业务体系的经营及战略，理解业务单元的商业需求及发展战略；\r\n</p>\r\n<p style="font-size:13px;">\r\n	4、负责人员的配置与招聘制度、薪酬结构设计、绩效考评制定；\r\n</p>\r\n<p style="font-size:13px;">\r\n	5、 负责全公司人才发展体系的建设， 协助管理层进行人才管理、团队发展、组织氛围建设；\r\n</p>\r\n<p style="font-size:13px;">\r\n	6、做好团队建设和 企业文化建设\r\n</p>\r\n<p style="font-size:13px;">\r\n	&nbsp;\r\n</p>\r\n<p style="font-size:13px;">\r\n	任职要求：\r\n</p>\r\n<p style="font-size:13px;">\r\n	1、5年以上人力资源从业经验，其中2年以上互联网行业HRM工作经历优先；\r\n</p>\r\n<p style="font-size:13px;">\r\n	2、有较丰富的HR团队管理经验，熟悉人力资源至少两到三个模块；\r\n</p>\r\n<p style="font-size:13px;">\r\n	3、思维open，乐于接受挑战，极强的自我驱动力，思路及逻辑清晰，善于分析及解决问题；\r\n</p>\r\n<p style="font-size:13px;">\r\n	4、擅长团队建设，人才发展体系构建优先。\r\n</p>', '', 0),
(12, 0, '<p style="color:#000000;font-family:Simsun;font-size:14px;font-style:normal;font-weight:normal;text-align:start;text-indent:0px;">\r\n	昨天，对于上海旅游业对迪斯尼合作条件的“冷眼”，香港旅游发展局以电子邮件方式向《每日经济新闻》作出回应：“希望旅客无论是参团或是以‘自由行’方式来港，都能够方便地购买香港迪斯尼乐园的入场门票”，将“致力促进业界之间的沟通和联系，希望业界和迪斯尼能尽快达成共识，作出最适当的安排。<span style="line-height:1.5;">”</span> \r\n</p>\r\n<p style="font-family:Simsun;font-size:14px;">\r\n	　　香港旅发局总干事臧明华表示，为了增加旅客留港时间和消费，香港旅游发展局已将迪斯尼乐园列入2005年重点推广工作计划，要将迪斯尼乐园和湿地公园等项目，与海洋公园、博物馆、缆车等串起来，大力吸引家庭旅客。\r\n</p>\r\n<p style="font-family:Simsun;font-size:14px;">\r\n	　　“多个市场的家庭旅客皆对香港非常感兴趣，主要是邻近的短途市场，当中以内地最热烈。”臧明华进一步强调家庭旅游对推广香港旅游业的重要性，称旅发局的目标是要吸引家庭旅客及高收益的商务旅客带同家人来香港，参与不同的旅游节目，同时增加在香港消费，为旅游以及相关的界别带来更大的经济效益。”\r\n</p>\r\n<p style="font-family:Simsun;font-size:14px;">\r\n	　　香港旅游发展局在邮件中提到，从今年底到明年初，香港还将陆续推出四大旅游设施，除迪斯尼外，还包括“昂平360”缆车和主题村、香港湿地公署和幻彩咏香江等新的景点项目。\r\n</p>\r\n<p style="font-family:Simsun;font-size:14px;">\r\n	　　到2006年，以针对性的推广策略，将这些新项目与现有的旅游景点重新包装推出\r\n</p>', '', 0),
(13, 0, '<p style="text-indent:2em;color:#444444;font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	闻牧歌唱晚，声声悠扬\r\n</p>\r\n<p style="text-indent:2em;color:#444444;font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	视衰草萋萋，浩渺远方\r\n</p>\r\n<p style="text-indent:2em;color:#444444;font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	细数昨日残阳\r\n</p>\r\n<p style="text-indent:2em;color:#444444;font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	挥泪洒琼江\r\n</p>\r\n<p style="text-indent:2em;color:#444444;font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	落英缤纷的9月末，料理完爸爸的后事，为舒展多日疲惫的倦容，平复久已伤恸的身心，我和漪漪放下冗杂的生活繁累，轻装起航，来到甲天下的桂林，梦想着摇曳在清风碧水中，淌洋在微波湖面上，象鸟儿融入山涧云雾，象野鹤驰骋于峡谷山峦。\r\n</p>\r\n<p style="text-indent:2em;color:#444444;font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	分明看见青山顶，船在青山顶上行。第一天，我们荡漾在如青罗丝带的漓江上，银灰和碧绿相间的远山像一幅幅色彩分明的油画长卷在漓江两岸缓缓展开，碧波倒影，如梦似幻。此时此刻，我们的心情如在半空翻飞的水鸟一样轻松舒畅。\r\n</p>\r\n<p style="text-indent:2em;color:#444444;font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	下午，我们来到阳朔，都说“桂林山水甲天下，阳朔堪称甲桂林。”这里曾被徐霞客誉为“碧莲玉笋的世界。”我们参观了世外桃源，蝴蝶泉等景点，景点里的导游不是我们带队的导游，我们像走马观花一样急匆匆的观赏着，唯恐误了规定的时间，出来后，细心的游客七嘴八舌的和导游争辩起来，说是漏掉了两个景点，一个是十里画廊，一个是月牙泉，各个面红耳赤地理论着，非要导游索赔100元的景点费，而导游怎么肯给呢？\r\n</p>\r\n<p style="text-indent:2em;color:#444444;font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	这天的天气十分燥热，在傍晚回去的路上我感觉肚子疼痛，眼前冒金星，也许是吃漓江小炸鱼吃坏了肚子，或者是中暑了，好在车上有个空座，我可以躺下，就这样，经过两个多小时翻江倒海似的疼痛和恶心，终于到了宾馆，这些游客始终不肯罢休，围着导游非要讨个说法，想要讨回那100元钱。我坐在木墩上疼的直不起腰，拦着出租车，漪漪一步三回头地离开争吵的人群陪我去了医院。\r\n</p>\r\n<p style="text-indent:2em;color:#444444;font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	“咱们走了，导游就不可能赔咱们了。”漪漪一边担心我自己去医院迷了路非要陪我去，一边还惦记着索赔的事。\r\n</p>\r\n<p style="text-indent:2em;color:#444444;font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	“放心吧，导游不会赔的，她不是说了吗？那是赠送给咱们的两个景点。”\r\n</p>\r\n<p style="text-indent:2em;color:#444444;font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	“可是，不管怎么说，景点导游没领咱们去游，她们就没理。”\r\n</p>\r\n<p style="text-indent:2em;color:#444444;font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	“咱们走过去了，导游没给介招，是她们导游之间衔接的不好。”\r\n</p>\r\n<p style="text-indent:2em;color:#444444;font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	“反正是没按行程游完，理当索赔。”漪漪坚持说。\r\n</p>\r\n<p style="text-indent:2em;color:#444444;font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	等我们从医院回来的时候，游人已经散去，因为是拼团，我们不认识别人，也不知道结果如何。\r\n</p>\r\n<p style="text-indent:2em;color:#444444;font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	“肯定索赔了，明天有行程走完的，该回家了，导游不赔行吗？”漪漪很肯定的样子。\r\n</p>\r\n<p style="text-indent:2em;color:#444444;font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	“我跟拼团走的多了，哪有那等索赔的好事？她们有一万条正当的理由等着你呢。”我争辩着。\r\n</p>\r\n<p style="text-indent:2em;color:#444444;font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	第二天，我们没有见到昨天熟悉的游客面孔，导游也换成新人了，她向我俩解释说：“今天你们的行程是游览桂林十大名山，只需在湖上游览一段，为补偿你们昨天的损失，我们赠送你们两江四湖的全程游。”\r\n</p>\r\n<p style="text-indent:2em;color:#444444;font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	漪漪还想上前和那导游理论，我拦住了她。\r\n</p>\r\n<p style="text-indent:2em;color:#444444;font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	“别理她们了，跟她们掰不清那理，我们只管观光好了。”我劝慰着漪漪。\r\n</p>\r\n<p style="text-indent:2em;color:#444444;font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	“我们还要赶时间，这叫什么事？”漪漪不情不愿地跟我上了旅游车。\r\n</p>\r\n<p style="text-indent:2em;color:#444444;font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	漪漪是给私企打工的，每天天还没亮，就起身上班了，晚上披着星星月亮才回家，这样一天下来，还挣不到100元钱，而现在这100元钱就这样打水漂了，可能她心里多少有些不自在，再说这事跟钱多钱少没多大关系，还是导游那方出现了问题，只是我们不想兴师动众，不想跟他们计较，不想给自己心里添堵而已。也许怪我，着急去医院，耽误了索赔。\r\n</p>\r\n<p style="text-indent:2em;color:#444444;font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	“出门在外，我们只用心看风景，别的随它去吧。”我指了指湖岸边“漪漪你看那象鼻山还真的挺像大象呢。”我们相觑而笑了。\r\n</p>\r\n<p style="text-indent:2em;color:#444444;font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	细细碎碎的朝阳铺满了湖面，眼前是湖光山色鸟语花香，日月双塔巍然屹立在美丽的杉湖中，给平静的湖水增添了多姿多彩的梦幻。随着景色渐入佳境，我们的心情也恢复了坦然和愉悦。\r\n</p>\r\n<p style="text-indent:2em;color:#444444;font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	雨霁高烟收素练，风晴细浪吐寒花。第三天的清晨，正是蒙蒙烟雨过后，空气清新，道路却十分湿滑，塞车十分严重，我们在宾馆门前从7点足足等到8点半，旅游的车才过来。可怎知，下面的路途更加漫长，直到下午2点，我们才进入游览风景区。\r\n</p>\r\n<p style="text-indent:2em;color:#444444;font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	“由于时间的关系，通灵大峡谷和自费的古龙山漂流只能任选其一了。”导游漫不经心地侃侃而谈。对于游客来说，是徒步观赏地球大裂缝——通灵大峡谷，观赏其过江龙咬人树地下河风光还有落差170米的瀑布，还是乘皮艇顺古龙山峡谷漂流，观赏集峡谷暗河溶洞原始植被众峰绝壁溪流奇石为一体的原始画廊？俩个景点就像鱼和熊掌不能兼得，哪个都不舍得放弃，而被导游轻描淡写那么一规划，只能舍其一了。\r\n</p>\r\n<p style="text-indent:2em;color:#444444;font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	第四天我们的行程突然被改变了，把我们带到了按行程应在最后一天去的北海市。真是天有不测风云，正赶上北海市在刮13级台风，各个景点为了安全起见，都关闭了，天空下着倾盆大雨，地上刮着几尺高的大烟泡，狂风摔打着窗棂，好一个地动山摇的架势，在这样恶劣的天气下，北海市仍像刚刚出浴的仙女一般光洁清丽。\r\n</p>\r\n<p style="text-indent:2em;color:#444444;font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	在北海的上空，乌突突的云彩翻滚着，游动着，低低的压向了大海，在北海海面上，怒吼的浪潮一浪高过一浪，还好我们很庆幸，借着刮台风的机遇，看见了大海光鲜的另一面，一个咆哮中的大海，也不妄此行。\r\n</p>\r\n<p style="text-indent:2em;color:#444444;font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	水秀山清眉远长，归来闲倚小阁窗。车窗外细雨纤纤，更秀出绿色的原野浓郁滋润，更突显盛开的鲜花娇艳欲滴。第五天，我们观赏了跨越中越边境的亚洲第一大瀑布——德天瀑布，之后我们幸运地吃了笑谈中的混血鸡和混血鱼，下午我们准备启程去明仕山庄。\r\n</p>\r\n<p style="text-indent:2em;color:#444444;font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	“在座的28位游客，如果超过20位游客去名仕山庄，我们的团购门票将会降到100元，”车内一片哗然，导游接着说，“众所周知，导游是靠门票收取提成的，而每个人我只能提取10元，这200元钱已足够我儿子的奶粉钱了，最重要的是你们不要错过这么好的景点。”\r\n</p>\r\n<p style="text-indent:2em;color:#444444;font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	“我们又不是慈善机构，不是来做奉献的。”旅游车里不知谁在讷讷地嘀咕着。\r\n</p>\r\n<p style="text-indent:2em;color:#444444;font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	“漪漪，你怎么不下车？”我诧异地问。\r\n</p>\r\n<p style="text-indent:2em;color:#444444;font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	“我看介绍了，和漓江的景差不多，我就不去了。”漪漪好像很坚定地说\r\n</p>\r\n<p style="text-indent:2em;color:#444444;font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	“漓江是看远山，这里看的虽然是相同的山，可是近观，不一样的。”漪漪仍旧无动于衷，她依然决定不去。在她的心里，是不是还对阳朔那100元的事耿耿于怀？还在心存不满？\r\n</p>\r\n<p style="text-indent:2em;color:#444444;font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	“漪漪，你不去这个景点一定会后悔的，”漪漪一点不以为然，你即便省下这100元，也弥补不了阳朔的损失，不仅无济于事而且又失去了看这个景点的机会，那不是拿别人的过错惩罚自己吗？\r\n</p>\r\n<p style="text-indent:2em;color:#444444;font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	明仕山庄真的是美轮美奂，仿佛误入人间仙境一般，喀斯特地貌的山峦环绕着波光潋滟的湖面，一群群放牧的牛羊在湖边自由自在地嬉戏吃草，一排排错落有秩的白族瓦房点缀在群山脚下，从远方若隐若现地传来对唱的的山歌。划一叶扁舟飘荡在湖面上，把酒临风，宠辱皆忘。\r\n</p>\r\n<p style="text-indent:2em;color:#444444;font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	自在飞花轻似梦，无边丝雨细如愁。\r\n</p>\r\n<p style="text-indent:2em;color:#444444;font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	八天的行程，去掉来回坐飞机耽搁的时间，观光游览的时间仅仅只有五天，而在这五天的游览中，除了陶醉于迷人的风光之外，还有一点小小的思考，比如景区里和景区外的导游如何才能衔接好，比如怎样才能在完成行程上的景点之后，再如何权衡好自费景点，再比如说遇到不可抗力阻挠，怎样弥补给游客行程里不能履行的行程等等。\r\n</p>\r\n<p style="text-indent:2em;color:#444444;font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	旅游行业正在蓬勃迅猛的发展中，如何在不断运行中寻找不足，并加以改进，将是弥足珍贵的，如果是这样，定会迎来越来越多的爱好旅游的人们，投入到浩浩荡荡的旅游团体中。这样旅游行业才能取信于民，服务于民。\r\n</p>\r\n<p style="text-indent:2em;color:#444444;font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	古人说的好：“早占取，韶光共追游，但莫管春寒，醉红自暖”。让我们尽早抓住最好的时机走进大自然，拥抱大自然吧。\r\n</p>', '', 0),
(14, 0, '<p style="text-indent:2em;color:#444444;font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	我是一条鲤鱼，以前生活在一片从来没有游到过尽头的湖泊中，直到有一天，我和几十个一起出游的小伙伴被一只大网网住后，就被放在了这个叫黑坑的地方，这个新家，一点也不大，而且水质特别差，当初一起来到这里的小伙伴都已不知了去向，但我还在这里，日复一日，年复一年。每隔几天就会有新的鱼儿的加入，但过不了几天又莫名奇妙的失踪，这些，水里的鱼儿都知道，但谁也没有去研究过，大家想的最多的就是——怎么吃到更多的食物。\r\n</p>\r\n<p style="text-indent:2em;color:#444444;font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	我从来不相信水面上落下的食物是可以吃的，虽然有些味道让鱼发狂，但我并不会随它们一起，像嗑药一般，疯狂抢食，因为我观察过，大多吃了那些食物的鱼一下就消失在视线里了，有的最多也就挣扎几下。我只吃水底层的食物，但有时候，黑坑塘子的老板也会在夜里撒一些粉末，那种味道让鱼作呕，几天不想进食，所以，大家都会聚集到水中间，这时，我也会饿几天，但恢复水中环境后，我还是不会去吃水面上落下的食物，因为，它们太香了，散发着危险的信号，尤其是从离岸4米、5米、6米的距离处落下的食物，更危险。这就是我生活在这个塘子里几年时间仍然还在这里的原因。现在，我已经有十几斤重了，是这个塘子里最大的鱼，但我仍然孤独的活着，从不聚群，因为傲娇的我根本不需要。有时候我也会蹦出水面透透气，看到岸边坐着的人类，他们也会看到我，然后大呼小叫，我总是鄙视的看他们一眼，再甩给他们一个尾巴，然后回到水中。\r\n</p>\r\n<p style="text-indent:2em;color:#444444;font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	今天又是个来新鱼的日子，一大早，一群一群的从我身边游过，唧唧咋咋的乱叫唤，让人心烦，还有几条背上绑了牌牌，优哉游哉的，打扰了我的好梦，算了，出去看看吧，先跃出水面透口气，哎呦！我—去，满满的都是人类，我就呵呵了，这一天到晚的，就知道坐在岸边，拿个竿竿，难道他们就是这样生存的？不需要寻找食物和交配？算了，这不是我一个鱼能想明白的，还是寻找食物吧！正准备走时，一团球状的东西砸了下来，吓了老子一跳，这是什么味道？我怎么从来没有闻过？太香了！不行，越是香的越是危险，但我怎么也转不动身体，当这团东西在水中雾化开来时，我已经被迷的神魂颠倒，肚子也开始饥肠咕咕，试着用嘴碰了一下，这团食物一个抖动，然后开始掉落，我再一碰，没有了反应，这下我放心了，贪婪的开始吃着，一会儿，又一团掉到了我身边，我开始不由自己控制，大口的吃着，这岸边的食物就是好吃，突然，我的上颚一紧，好像被什么东西刺穿了，是钩子！我开始往水中游，但有个更大的力在把我往反的方向拽，不过，这力气还不足以对我产生威胁，但这该死的钩子就是甩不掉，我继续用着力，始终保持自己不露出水面，只要我在水里，我就是安全的，就这样，我挣扎着，那个来自岸上的力也没有放弃，不知过了多久，一个小时？两个小时？还是更久……我已经没有力气了，我高估了自己的力量，这时，我被慢慢的拉出水面，当我看到离人类越来越近时，我用尽最后的力气再次翻身，试图逃脱，但这已是无济于事了，我再次被网住，然后被一个人类抱在了怀里，而另一个人类站在我的对面，我的周围站满了人类，都来围观，议论纷纷，在人群中我看到了经常撒药的那个老头，他的脸色不好看，抱我的人兴奋的喊了声：“茄子”，对面的人手里的小盒子一闪……\r\n</p>', '', 0);
INSERT INTO `tmm_document_article` (`id`, `parse`, `content`, `template`, `bookmark`) VALUES
(15, 0, '<p style="text-indent:2em;color:#444444;font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	旅游文明从“厕所”开始\r\n</p>\r\n<p style="text-indent:2em;color:#444444;font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	近日，国家旅游局宣布将出台新的旅游厕所等级评定标准，今后旅游景区将取消四星级、五星级以及奢华厕所，取而代之的是环保、实用的厕所。此举获得了广泛的舆论支持，很多网友表示，景区需要的真的不是奢华厕所。\r\n</p>\r\n<p style="text-indent:2em;color:#444444;font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	众所周知，世界上有三个国际组织的英文简称同为“WTO”：世界贸易组织、世界旅游组织、世界厕所组织。正如世界厕所组织发起人所说，“厕所是人类文明的尺度”。厕所是全世界通用的嗅觉语言和视觉语言，文明沟通中最短的直线，体现文明进化历程。作为世界大国、文明古国和旅游大国，我国厕所严重短缺、管理滞后局面必须改变。“厕所革命”是一项国家文明工程。今年我国启动为期三年的全国旅游厕所建设和管理行动，厕所是文明窗口，旅游要素，进步的体现。作为旅游大国，我国旅游厕所脏乱差，游客反映强烈，是公共服务体系最薄弱环节。面对几千年的歧视厕所、鄙视厕所、厕所文化缺失及顽固如厕陋习，迫切需要针对旅游“最后角落”开展一场革命性变革。厕所虽小，却是一种全世界通用的嗅觉语言和视觉语言，是文明沟通中最短的直线，“厕所是人类文明的尺度”，而厕所被我们忽略得太久了。就全国实施为期三年的“旅游厕所革命”这一话题，国家旅游局局长李金早17日接受了新华社记者专访，指出“境外游客对中国旅游环境印象最差的就是厕所”。\r\n</p>\r\n<p style="text-indent:2em;color:#444444;font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	我国传统文化重“进口”不重“出口”，只谈美味佳肴，将厕所看做污秽之地而耻于启齿。我们要确立厕所的文明窗口观念。厕所不是“脏、乱、差”的代名词，不是垃圾场，而应成为人们放松、愉悦、享受之地。欧阳修许多妙句诞生于如厕，“余平生所作文章，多在三上，乃马上、枕上、厕上也”，还被认为有失大雅。我们要像重视餐厅一样去重视厕所。要像重视客厅一样去打理厕所。要像重视景点一样去美化厕所。今年全国旅游工作会议提出“吃、厕、住、行、游、购、娱”旅游七要素，增加“厕”并放在吃后面，就是因为吃饭是人生之本，如厕是人生之急。然而如厕问题长期被忽视。相信很多游客都有过找不到厕所或排队如厕的急躁情绪，孩子随地便溺，只能尴尬地以“童子尿无害”来应对旁人的白眼；相信很多游客面对一些景区随处可见的“黄白之物”，只能无奈地嘟囔几句“素质真差”。可见，景区首先要做到的是满足游客的基本需要，然后再谈其他。\r\n</p>\r\n<p style="text-indent:2em;color:#444444;font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	厕所也是脸面。许多地方不惜花大量人力财力去推介景区景点，却不愿扎扎实实建好管好厕所。一个“脏、乱、差”的厕所可以瞬间毁掉旅游推介的百般努力，负面影响很难挽回。我国年接待游客超过37亿人次。国内旅游一趟平均每人上8次厕所，游客每年在旅游如厕次数超过270亿次。发现美、享受美、传播美，首先就要解决天文数字的如厕问题。厕所问题长期困扰着我们，几成无解之题。建成世界旅游强国，必须从厕所这类基础事情抓起。政府与市场、企业与社会，管理者与消费者全社会总动员，建设厕所文明。厕所革命仅有厕所建设者、管理者行动远远不够，厕所使用者必须自我“革命”。每个人都应养成文明如厕习惯，抵制粗鄙如厕行为。 如厕不文明，我国公民出境旅游饱受诟病，令我们汗颜。每一位公民都要有文明如厕自觉性。从国内做起，从娃娃抓起，进行终生教育。今后三年，我们每年都要向社会公开发布《中国旅游厕所建设管理行动报告》。要弘扬厕所建设管理先进，厕所脏乱差少典型的地方要曝光。三年旅游厕所建管行动不能解决全部问题，但一定要播下厕所文明的种子。\r\n</p>\r\n<p style="text-indent:2em;color:#444444;font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	厕所是旅游公共服务设施，重要的基础设施，应明确地方政府为主体。要推动地方政府将厕所纳入当地政府基础设施建设规划，推动业主单位、主管部门和地方政府在厕所建管中承担主体责任。小小厕所，建设管理涉及旅游、规划、工商、税务、建设、环保、市政、环卫等部门，要按照市场化、社会化理念，整合资源。到2017年底，全国旅游景区景点、旅游线路沿线、交通集散点、乡村旅游点、旅游餐馆、旅游娱乐场所、休闲步行区等旅游厕所要达到优良标准。“三分建设、七分管理”。要探索“以商养厕”之路。把厕所作为发展机会、商机，让厕所建、管有商可经、有利可获。一部厕所史也是一部技术变革史。抽水马桶发明，带来人类厕所革命。我国厕所由露天到室内、旱式到水冲，有了很大进步，但总体技术应用水平落后。要积极采用新技术、新材料建设厕所，使厕所符合现代时尚、方便实用、节能节水、保护环境要求。\r\n</p>\r\n<p style="text-indent:2em;color:#444444;font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	作为游客，“三急”时刻对厕所的要求是什么？首先是好找，这就要求景区厕所数量多、位置明显。如此，游客才能在景区的人潮汹涌中“杀”出一条“上厕路”；其次是好用，毕竟不同于家庭或星级宾馆，干净无味、注重隐私、方便特殊群体应该就及格了。让厕所回归本源，它就是个“五谷轮回之所”，清洁、无异味就够了。如果非要有所提高，也应在“免冲”和“生态保护”上下功夫。如今，反对奢靡之风已成为社会的共识，这不仅是中华民族的传统美德，更是国人价值观念、人生追求和行为作风的体现。奢侈之花必定结出精神颓废、事业衰退、世风败坏的恶果。如果星级厕所成为“时尚”，那就是一种悲哀了。\r\n</p>\r\n<p style="text-indent:2em;color:#444444;font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	为取消四星级、五星级以及奢华厕所点赞！景区的五星级不在于奢华厕所，而应在于售票员对游客的一个微笑，检票员对游客的一些尊重，讲解员对游客的一份耐心…总之和五星级厕所的马桶材质无关。\r\n</p>', '', 0),
(16, 0, '<p style="text-indent:2em;color:#444444;font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	1.\r\n</p>\r\n<p style="text-indent:2em;color:#444444;font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	<span style="line-height:1.5;">刘洋结婚<span style="line-height:1.5;">半年，刚去了泰国度完蜜月，</span>朋友<span style="line-height:1.5;">圈里各种晒旅游和秀恩</span>爱<span style="line-height:1.5;">。可是一回到</span>家里<span style="line-height:1.5;">所有的</span>愉快心情<span style="line-height:1.5;">全都没有了，因为他家里被洗劫了一空，大到电脑，小到刮胡刀的充电器全部被盗走。</span></span>\r\n</p>\r\n<p style="text-indent:2em;color:#444444;font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	他跟我们开玩笑道：“难道这贼的刮胡刀跟我的一模一样？充电器给我拿走了，刮胡刀不要，也真神奇。”最后他一拍桌子站起来道：“弄他擦的！这个天杀的贼厮！这回好了，终于不用天天英雄联盟不睡觉了。”\r\n</p>\r\n<p style="text-indent:2em;color:#444444;font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	我苦笑了一下，表示同情。\r\n</p>\r\n<p style="text-indent:2em;color:#444444;font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	可是没过了多久，就看见他每天都睡眼惺松的跟我说：“昨天又在网吧熬了一宿夜，这天杀的贼偷老子电脑，害得老子天天泡网吧。”\r\n</p>\r\n<p style="text-indent:2em;color:#444444;font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	我问他：“听说你老婆怀孕了？”\r\n</p>\r\n<p style="text-indent:2em;color:#444444;font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	他答：“是啊！”\r\n</p>\r\n<p style="text-indent:2em;color:#444444;font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	我又问：“那你还天天打游戏，你老婆不和你急吗？”\r\n</p>\r\n<p style="text-indent:2em;color:#444444;font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	他一撇嘴：“她敢跟我急？男人在家没地位还行了？她怀孕又不是我怀孕，凭什么不让我玩游戏。”\r\n</p>\r\n<p style="text-indent:2em;color:#444444;font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	从此，我对他真是“刮目相看”。\r\n</p>\r\n<p style="text-indent:2em;color:#444444;font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	2.\r\n</p>\r\n<p style="text-indent:2em;color:#444444;font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	老历是单位里的同事，刚有小孩不久，老婆是全职太太，家里所有的开销都要他一人包办。\r\n</p>\r\n<p style="text-indent:2em;color:#444444;font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	由于他在单位里的是坐办公室，比较清闲。所以他每天在自己的电脑上下载了一个能卖钱的网页游戏天天的挂着刷怪。有一次聊天，他叼着烟卷、玩着游戏，语重心长地跟我说：“你以后一定不要找一个全职太太当老婆，现在小孩的开销多大，除了养孩子一个月还要随这么多份子钱，还要维持家里的生活必需，男人真是太累了。我都打算和别人合买一个出租车天天出晚班了。”\r\n</p>\r\n<p style="text-indent:2em;color:#444444;font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	“好啊！现在出租车还是挺赚钱的。你还是别玩你这游戏了，费时间不说被老板看见也不好。”\r\n</p>\r\n<p style="text-indent:2em;color:#444444;font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	“你不懂，这个游戏一个月能卖差不多300快钱呢。我现在家里已经按不起宽带了，只能在单位里刷怪卖钱了。”\r\n</p>\r\n<p style="text-indent:2em;color:#444444;font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	一个月后我再去老历那，闲聊时问他：“出租车生意还行吧？”\r\n</p>\r\n<p style="text-indent:2em;color:#444444;font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	他叼着烟卷、打着游戏说：“哎，别提了，我现在刚学会开车，买个出租车也不少钱，更害怕手生出点事故就赔了。最近正在合计着有什么新的项目看看做点兼职，好像进点衣服摆个地摊也不错。”\r\n</p>\r\n<p style="text-indent:2em;color:#444444;font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	我呵呵的一笑，把文件给了他走出了办公室。\r\n</p>\r\n<p style="text-indent:2em;color:#444444;font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	3.\r\n</p>\r\n<p style="text-indent:2em;color:#444444;font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	单位里新来个同事小龙直接塞到了我的部门下，他每天都跟我混在一起，可是刚刚二十出头的他上班的第二天就买了一个苹果6s手机拿到我面前显摆，“哥！你也赶紧换个苹果得了，现在没苹果多没面子啊，也不贵，才六千多。”\r\n</p>\r\n<p style="text-indent:2em;color:#444444;font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	我愕然的问着：“你刚来一个月给你开多少钱？”\r\n</p>\r\n<p style="text-indent:2em;color:#444444;font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	“实习一千五啊！”\r\n</p>\r\n<p style="text-indent:2em;color:#444444;font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	“那你哪来的钱？”\r\n</p>\r\n<p style="text-indent:2em;color:#444444;font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	“家里给的。现在这是主流。”\r\n</p>\r\n<p style="text-indent:2em;color:#444444;font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	一个月后，小龙拿着手机跟我说：“哥，你别天天的在这闷头算题了，毕业这么多年了有什么用。你也下载个斗地主，我们一起玩，还能赢话费呢。”\r\n</p>\r\n<p style="text-indent:2em;color:#444444;font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	我终于忍不住的对他说：“首先我觉得我这九百多块钱的手机也挺好，不仅好看，能微信也能支付宝，还能看点气预报呢。其次我算题是因为我当家教就必须把今天要给讲的东西复习好，不能误人子弟。还有你年纪轻轻价值观却如此混乱，我不知道你都在想什么，你每天除了玩游戏就是聊附近人居然还觉得自己做的很对？你这么下去恐怕实习期结束就是你失业的开始吧！”\r\n</p>\r\n<p style="text-indent:2em;color:#444444;font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	小龙呆了一会：“你不是常说年纪轻就是资本嘛，那我把斗地主删了吧。”\r\n</p>\r\n<p style="text-indent:2em;color:#444444;font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	我最后无语的说道：“你以为你把游戏删掉了就不会再玩游戏了吗？”\r\n</p>\r\n<p style="text-indent:2em;color:#444444;font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	4.\r\n</p>\r\n<p style="text-indent:2em;color:#444444;font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	大学时我门寝室六人天天玩的一款游戏叫做穿越火线，把无数的大好时光浪费在了上面。\r\n</p>\r\n<p style="text-indent:2em;color:#444444;font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	一天，宿舍里的老五给我打电话抱怨：“今天又被老板骂了，我就在办公桌上迷糊了一会，他就跟个幽灵一样出现在了我的后面，当着众人这顿给我切的，体无完肤，你说让我面子往哪放？真XX不想干了。”\r\n</p>\r\n<p style="text-indent:2em;color:#444444;font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	我问：“那你为什么白天这么困呢？”\r\n</p>\r\n<p style="text-indent:2em;color:#444444;font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	他说：“昨天晚上打火线打到了半夜三点，有个战队跟咱们战队杠上了！所以……”\r\n</p>\r\n<p style="text-indent:2em;color:#444444;font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	我打断他：“毕业快四年了你还在玩？”\r\n</p>\r\n<p style="text-indent:2em;color:#444444;font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	他惊讶道：“怎么了？你有两年没玩了吧？现在你肯定不是对手。”\r\n</p>\r\n<p style="text-indent:2em;color:#444444;font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	我冷冷的说：“好吧，那你以后怎么打算的呢？”\r\n</p>\r\n<p style="text-indent:2em;color:#444444;font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	他说：“走一步看一步呗，要不你给我弄个职业规划，挺羡慕你的，又创业，又上班，还兼职。再说我白天上班这么累，晚上打个游戏放放松怎么了。”\r\n</p>\r\n<p style="text-indent:2em;color:#444444;font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	我沉默了好久，最后敷衍道：“我这边马上就要去家教了，等有空我给你规划规划。”\r\n</p>\r\n<p style="text-indent:2em;color:#444444;font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	5.\r\n</p>\r\n<p style="text-indent:2em;color:#444444;font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	我不知道从什么时候开始，年轻人这么会堕落自己的人生了。他们总是愿意去评论别人的是非而不顾自己的生活方式，他们以为那些活的光鲜的人只不过是运气好，他们总愿意把年轻就是资本挂在嘴边，殊不知他们的生活方式已经跟五六十岁的人生没有了任何区别。\r\n</p>\r\n<p style="text-indent:2em;color:#444444;font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	就像我的朋友总愿意挖苦我的生活也未必就是正确的，我完全同意，因为现在尝试创业的我很有可能明天就会失败而负债累累。我也把自己一天的时间安排的满满当当没有一点和他们一起去经营所谓的花天酒地的“人脉”在他们看来是那么不合群。但是我不怕失败，我知道年纪轻轻不做出改变那就是再过着等死的生活。失败了没关系，再来过就好，毕竟我还没有倒下。而你们也只能每天这样游戏扯淡着这循环又毫无意义的日子了，我知道我永远不会允许自己和你们一样。\r\n</p>\r\n<p style="text-indent:2em;color:#444444;font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	因为我总是告诫自己：不要年纪轻轻就把自己变成老头，有时候最怕的就是把年纪轻轻还有都是时间挂在嘴边。而要记住的是，年轻不是资本，年轻又努力才会成为资本。每个人的可笑都在于嘴上说着要为将来的自己奋斗拼搏，行为上却做着好吃懒做、怨天尤人的勾当。\r\n</p>\r\n<p style="text-indent:2em;color:#444444;font-family:Simsun;font-size:14px;background-color:#FFFFFF;">\r\n	所以，不要以为那些活得光鲜的人只是运气好，因为你没有看到他们的努力。更因为没有一件事是不通过努力就会平白无故的成功，人生在勤，不索何获？\r\n</p>', '', 0),
(17, 0, '<p style="text-indent:2em;font-size:15px;font-family:arial, ''Microsoft Yahei'', sans-serif;">\r\n	<span style="line-height:2;font-size:18px;font-family:''Microsoft YaHei'';">筠连县沐爱场东出五、六百米，有座叫鹞鹰岩的山。儿时在老家的时候，远远望着这座山，以及这座山左边连绵不断的潘家山（兴隆村）、右边的火星山（峨坪村）一排山峰，挡着了看见沐爱场和看见山那边一切景物的视线。对它有些埋怨：这匹山为什么长那么高呢？</span> \r\n</p>\r\n<p style="text-indent:2em;font-size:15px;font-family:arial, ''Microsoft Yahei'', sans-serif;">\r\n	<span style="font-size:18px;font-family:''Microsoft YaHei'';line-height:2;">其实对这座鹞鹰岩山更为恐惧的还在后面。</span> \r\n</p>\r\n<p style="text-indent:2em;font-size:15px;font-family:arial, ''Microsoft Yahei'', sans-serif;">\r\n	<span style="font-size:18px;font-family:''Microsoft YaHei'';line-height:2;">对这座鹞鹰岩山感到恐惧 在哪里呢？那就是鹞鹰岩那段山沟路上的深沟和山洞。这条路，自从沐爱——沐义通公路后，于上世纪八十年代中后期走的人逐渐减少，到90年代彻底废弃，现在已经没有人再走了。</span> \r\n</p>\r\n<p style="text-indent:2em;font-size:15px;font-family:arial, ''Microsoft Yahei'', sans-serif;">\r\n	<span style="font-size:18px;font-family:''Microsoft YaHei'';line-height:2;">鲁迅说，世间本无路，人走的多了便成路了。沐爱鹞鹰岩那段路本来是无路的，是先人修好了路，后人跟着走出来的。以前沐义到沐爱也许走的是沿二山（就是碾盘咀）上百步梯那边转，绕一个手大拐弯弯路程。所以沐义一个姓徐主持，用清石板修成了这条直上沐爱场的路。我们在这里感谢这位修路的前辈。</span><span style="font-size:18px;font-family:''Microsoft YaHei'';line-height:2;">(&nbsp;文章阅读网：www.sanwen.net )</span> \r\n</p>\r\n<p style="text-indent:2em;font-size:15px;font-family:arial, ''Microsoft Yahei'', sans-serif;">\r\n	<span style="font-size:18px;font-family:''Microsoft YaHei'';line-height:2;">儿时稍微大一些的时候，随母亲赶沐爱场或者是去沐爱占家村外婆家。从家里出发，过了大地社二队（现在叫沐荫村二组）的土地包，再乘船过沐浴堂河，就进入三、四尺宽的石板路。经过河坎上毛家屋边后，到青杠湾门口，就快到板板桥了。过了板板桥，进入习武坝（小时候我们说是叙武坝）中心地段，再过一小桥，就开始爬坡了，爬完这第一小斜坡，也就开始进入恐惧地带了。</span> \r\n</p>\r\n<p style="text-indent:2em;font-size:15px;font-family:arial, ''Microsoft Yahei'', sans-serif;">\r\n	<span style="font-size:18px;font-family:''Microsoft YaHei'';line-height:2;">这第一小坡大概两三百米长，路不是很陡，坡中途有一窝硬头黄竹子，对它的记忆，是雨天路滑，或者是天要黑了，我们曾经在此捡倒了的小竹子用着处路或者防盗。竹子右面有一两户人家，竹子左边是斜坡到沟，沟那边又是一山，即碾盘咀潘家山那边延绵来的山，那边也是很远才有人家户，在这边倘若遇到危险是喊不应对边人家户的。沟这边除了这里有人家户外，一直顺山爬上鹞鹰岩丫（崖）口上面，约2000多米长的地段，两山冷清清的都没有人家户。</span> \r\n</p>\r\n<p style="text-indent:2em;font-size:15px;font-family:arial, ''Microsoft Yahei'', sans-serif;">\r\n	<span style="font-size:18px;font-family:''Microsoft YaHei'';line-height:2;">第一段小坡后，便是一段平路，这段平路是从罗罗平山的半山腰过的，罗罗平山顶上有沐爱温家的古墓，据说墓主人是个皇太师。这半山上的路，横山而过，大约8.9百米长。横山路左边是几百米深的深沟，沟那边也是很高很陡的大山，即碾盘咀那边的山。走了一半横山路后，能够很清楚的看见沟那边山的半山腰有一水洞，涨水天，那洞里流出的水很大，形成很美丽的瀑布，据说这洞是沐爱金銮坝的一个消水口，古人在金銮坝那边水沟里放米糠进去，这边水里会有米糠出。横山路的右边是几十米高坡地树林。路逐渐与沟接近，沟与路落差相等、路与沟相距四五尺远的时候，路就完全进入鹞鹰岩深沟地带了。</span> \r\n</p>\r\n<p style="text-indent:2em;font-size:15px;font-family:arial, ''Microsoft Yahei'', sans-serif;">\r\n	<span style="font-size:18px;font-family:''Microsoft YaHei'';line-height:2;">这深沟地带，站在此处，抬头看鹞鹰岩山丫。真可以借杜牧的《山行》前两句“远上寒山石径斜，白云深处有人家”作比喻。不过，这里石径更陡，从底爬上鹞鹰岩，有三、四段比较陡的石梯路，而且白云深处却是恐怖山岩洞，没有人家。</span> \r\n</p>\r\n<p style="text-indent:2em;font-size:15px;font-family:arial, ''Microsoft Yahei'', sans-serif;">\r\n	<span style="font-size:18px;font-family:''Microsoft YaHei'';line-height:2;">两边山高、光线暗、路陡、无人烟、沟深无可以做房子的平地，无法居住人。山中有的树林还茂密，也很 少看见做生产的人，阴森森的，真是恐怖！</span> \r\n</p>\r\n<p style="text-indent:2em;font-size:15px;font-family:arial, ''Microsoft Yahei'', sans-serif;">\r\n	<span style="font-size:18px;font-family:''Microsoft YaHei'';line-height:2;">第二段陡坡后，有一处凉水，来往过路人经常在此饮水，以储藏或者补充因为快步通过这段无人烟地段流失的水分。</span> \r\n</p>\r\n<p style="text-indent:2em;font-size:15px;font-family:arial, ''Microsoft Yahei'', sans-serif;">\r\n	<span style="font-size:18px;font-family:''Microsoft YaHei'';line-height:2;">第三四段陡坡，是最恐惧的地方。</span> \r\n</p>\r\n<p style="text-indent:2em;font-size:15px;font-family:arial, ''Microsoft Yahei'', sans-serif;">\r\n	<span style="font-size:18px;font-family:''Microsoft YaHei'';line-height:2;">爬上第三段陡坡，左边是深沟，远一点是敞水岩，岩上大约10米高的敞水瀑布，冲在深氹里，水塘水深。右边是半岩上锉的小石梯路。路很窄，也许当初修路因为岩笔直实在困难，没有修很宽，大概两三尺宽吧，但也够害怕的了。因为若不小心掉下悬崖去，肯定不死都是残废。这段路爬完了，稍微转下弯，便是最恐惧的黑符符的鹞鹰洞了，洞很深。这里虽然路稍微宽一点，但洞太可怕了，因为听说那里旧社会有棒老二抢人，而且把人杀了就丢在那个瀑布水下的水塘里。</span> \r\n</p>\r\n<p style="text-indent:2em;font-size:15px;font-family:arial, ''Microsoft Yahei'', sans-serif;">\r\n	<span style="font-size:18px;font-family:''Microsoft YaHei'';line-height:2;">这里有个真实的事。解放前这鹞鹰洞里埋了个叫花子，这是真实的，解放前有父子两个叫花子，老叫花子是父亲死在那里后，儿子很小，没有办法，就把父亲随便刨点土埋在洞那边泥地里，小叫花子儿子走了。多少年过去了，解放了，新中国成立了。有天，来了10多、20个解放军，带队的提的是手枪，其余是背的步枪。这队解放军，来到这洞口，提手枪的解放军，看了看老叫花子的土堆堆坟墓，就理了理老叫花子的坟墓上的草，然后在洞口站好，敬了礼，放了一排子弹，就走了。从那次后，再也没有人再来祭这个叫花子的坟墓了。前几天，我还又去问住在沐爱场猪市坝那边的一个姓陈的木匠师傅，陈师傅80多岁了，他头脑清醒，他说是有这会事情。</span> \r\n</p>\r\n<p style="text-indent:2em;font-size:15px;font-family:arial, ''Microsoft Yahei'', sans-serif;">\r\n	<span style="font-size:18px;font-family:''Microsoft YaHei'';line-height:2;">所以，小时候听说那里还有坟，从那里过总害怕。害怕洞里突然窜出什么东西出来。我10多岁的时候，有一次，我中午和几个老年人从那里过，突然洞里飞出一只鸟，吓得我趴在路上，…。</span> \r\n</p>\r\n<p style="text-indent:2em;font-size:15px;font-family:arial, ''Microsoft Yahei'', sans-serif;">\r\n	<span style="font-size:18px;font-family:''Microsoft YaHei'';line-height:2;">第四段陡坡，是过了鹞鹰洞门以后，往沐爱场这边平坝爬。陡坡是绕着鹞鹰洞背上爬的。再往上面爬，就开始看见不远处有人家户了，心理就开始舒坦了，咚咚跳的心开始平静、放松。到沐爱场猪市坝就进入沐爱场了。</span> \r\n</p>\r\n<p style="text-indent:2em;font-size:15px;font-family:arial, ''Microsoft Yahei'', sans-serif;">\r\n	<span style="font-size:18px;font-family:''Microsoft YaHei'';line-height:2;">我与母亲上下沐爱场，路经鹞鹰岩，母亲说一定要结伴（至少三、四人）同行。而且要与老诚忠厚的人一路，也最好是邻居熟人。我逐渐学会了走这段困难路的经验，后来人大了，再过这段路，也就没有什么好怕的了。</span> \r\n</p>\r\n<p style="text-indent:2em;font-size:15px;font-family:arial, ''Microsoft Yahei'', sans-serif;">\r\n	<span style="font-size:18px;font-family:''Microsoft YaHei'';line-height:2;">上世纪80年代初的一天，母亲还受一孩子的姑姑委托，在回沐义的时候，带一个8.9岁的小孩子（他去街上住的姑姑家耍）一路回沐义。母亲当时说：那孩子将来会有出息，一路与我很诚恳，不乱跳，…。这孩子现在已经在外地工作了。</span> \r\n</p>\r\n<p style="text-indent:2em;font-size:15px;font-family:arial, ''Microsoft Yahei'', sans-serif;">\r\n	<span style="font-size:18px;font-family:''Microsoft YaHei'';line-height:2;">鹞鹰洞还是分上洞和下洞的。我们路过的是下洞，上洞在现在还在走的那条水泥路坎上。</span> \r\n</p>\r\n<p style="text-indent:2em;font-size:15px;font-family:arial, ''Microsoft Yahei'', sans-serif;">\r\n	<span style="font-size:18px;font-family:''Microsoft YaHei'';line-height:2;">这条路从修建到现在废弃，大概不到两百年时间。修好路后，那些沐爱鲤鱼槽（礼义），沐爱场等地方有往来嫁娶的，坐四人，或者八人抬的大花轿，迎亲的队伍也许是走另外的路的。</span> \r\n</p>\r\n<p style="text-indent:2em;font-size:15px;font-family:arial, ''Microsoft Yahei'', sans-serif;">\r\n	<span style="font-size:18px;font-family:''Microsoft YaHei'';line-height:2;">鹞鹰岩还有个传说，小时候听大人讲的。说是很早很早以前，当年左边的鹞鹰岩的鹞鹰嘴朝右边的鹞鹰洞（上洞）这边长，说是眼看就要长来插入鹞鹰洞了，如果靠拢，洞里就要出妖怪，妖怪就要害人。所以雷公把那鹞鹰嘴打断了，打断的鹞鹰嘴石头，断裂在鹞鹰岩满沟都是，现在也还能够看见，有横七八竖的乱石，这些乱石估计有的上万斤重一砣的。人们就用雷公打断的鹞鹰嘴石头来修建这条路。这故事大概是说，修建这条路不容易，那么多石头怎么打的？或者也说明，远古的时候，这里山峰更为险峻，后来山峰曾经发生过垮塌，沟里才会有那么多大乱石。…。</span> \r\n</p>\r\n<p style="text-indent:2em;font-size:15px;font-family:arial, ''Microsoft Yahei'', sans-serif;">\r\n	<span style="font-size:18px;font-family:''Microsoft YaHei'';line-height:2;">11月份下筠连，与几个七、八十岁的大爷同车，车行至沐爱与巡司交接路段的官盘山，几个大爷说：好在毛主席打下基础，现在交通方便，国民党连修一条马路都很难修通。是啊，就沐义到沐爱来说，就有好几条公路了，现在也正在打另外一条水泥路。许多村公路还打成了水泥路了。</span> \r\n</p>\r\n<p style="text-indent:2em;font-size:15px;font-family:arial, ''Microsoft Yahei'', sans-serif;">\r\n	<span style="font-size:18px;font-family:''Microsoft YaHei'';line-height:2;">真是今非昔比，沐爱鹞鹰岩的山、洞、路，就让它成为记忆吧！</span> \r\n</p>', '', 0),
(18, 0, '<p>\r\n	国家旅游局拟多项举措促乡村旅游发展国家旅游局拟多项举措促乡村旅游发展国家旅游局拟多项举措促乡村旅游发展国家旅游局拟多项举措促乡村旅游发展\r\n</p>\r\n<p>\r\n	<img src="/Uploads/Picture/2016-01-15/56988fb12879d.jpg" /> \r\n</p>\r\n<p>\r\n	<p>\r\n		国家旅游局拟多项举措促乡村旅游发展国家旅游局拟多项举措促乡村旅游发展国家旅游局拟多项举措促乡村旅游发展国家旅游局拟多项举措促乡村旅游发展\r\n	</p>\r\n	<p>\r\n		<img src="/Uploads/Picture/2016-01-15/56988fb12879d.jpg" />\r\n	</p>\r\n	<div>\r\n		<br />\r\n	</div>\r\n	<p>\r\n		国家旅游局拟多项举措促乡村旅游发展国家旅游局拟多项举措促乡村旅游发展国家旅游局拟多项举措促乡村旅游发展国家旅游局拟多项举措促乡村旅游发展\r\n	</p>\r\n	<p>\r\n		<img src="/Uploads/Picture/2016-01-15/56988fb12879d.jpg" />\r\n	</p>\r\n	<div>\r\n		<br />\r\n	</div>\r\n	<p>\r\n		国家旅游局拟多项举措促乡村旅游发展国家旅游局拟多项举措促乡村旅游发展国家旅游局拟多项举措促乡村旅游发展国家旅游局拟多项举措促乡村旅游发展\r\n	</p>\r\n	<p>\r\n		<img src="/Uploads/Picture/2016-01-15/56988fb12879d.jpg" />\r\n	</p>\r\n	<div>\r\n		<br />\r\n	</div>\r\n	<p>\r\n		国家旅游局拟多项举措促乡村旅游发展国家旅游局拟多项举措促乡村旅游发展国家旅游局拟多项举措促乡村旅游发展国家旅游局拟多项举措促乡村旅游发展\r\n	</p>\r\n	<p>\r\n		<img src="/Uploads/Picture/2016-01-15/56988fb12879d.jpg" />\r\n	</p>\r\n	<div>\r\n		<br />\r\n	</div>\r\n</p>', '', 0),
(19, 0, '<p style="font-size:13px;">\r\n	职位描述：\r\n</p>\r\n<p style="font-size:13px;">\r\n	1、根据公司整体战略，全面统筹制定事业部人力资源战略规划，并在实施中不断优化和提升；\r\n</p>\r\n<p style="font-size:13px;">\r\n	2、深入了解业务发展以及团队特点，结合组织战略，协助开展各项HR相关工作；\r\n</p>\r\n<p style="font-size:13px;">\r\n	3、参与业务体系的经营及战略，理解业务单元的商业需求及发展战略；\r\n</p>\r\n<p style="font-size:13px;">\r\n	4、负责人员的配置与招聘制度、薪酬结构设计、绩效考评制定；\r\n</p>\r\n<p style="font-size:13px;">\r\n	5、 负责全公司人才发展体系的建设， 协助管理层进行人才管理、团队发展、组织氛围建设；\r\n</p>\r\n<p style="font-size:13px;">\r\n	6、做好团队建设和 企业文化建设\r\n</p>\r\n<p style="font-size:13px;">\r\n	&nbsp;\r\n</p>\r\n<p style="font-size:13px;">\r\n	任职要求：\r\n</p>\r\n<p style="font-size:13px;">\r\n	1、5年以上人力资源从业经验，其中2年以上互联网行业HRM工作经历优先；\r\n</p>\r\n<p style="font-size:13px;">\r\n	2、有较丰富的HR团队管理经验，熟悉人力资源至少两到三个模块；\r\n</p>\r\n<p style="font-size:13px;">\r\n	3、思维open，乐于接受挑战，极强的自我驱动力，思路及逻辑清晰，善于分析及解决问题；\r\n</p>\r\n<p style="font-size:13px;">\r\n	4、擅长团队建设，人才发展体系构建优先。\r\n</p>', '', 0),
(20, 0, '<p style="font-size:13px;">\r\n	职位描述：\r\n</p>\r\n<p style="font-size:13px;">\r\n	1、根据公司整体战略，全面统筹制定事业部人力资源战略规划，并在实施中不断优化和提升；\r\n</p>\r\n<p style="font-size:13px;">\r\n	2、深入了解业务发展以及团队特点，结合组织战略，协助开展各项HR相关工作；\r\n</p>\r\n<p style="font-size:13px;">\r\n	3、参与业务体系的经营及战略，理解业务单元的商业需求及发展战略；\r\n</p>\r\n<p style="font-size:13px;">\r\n	4、负责人员的配置与招聘制度、薪酬结构设计、绩效考评制定；\r\n</p>\r\n<p style="font-size:13px;">\r\n	5、 负责全公司人才发展体系的建设， 协助管理层进行人才管理、团队发展、组织氛围建设；\r\n</p>\r\n<p style="font-size:13px;">\r\n	6、做好团队建设和 企业文化建设\r\n</p>\r\n<p style="font-size:13px;">\r\n	&nbsp;\r\n</p>\r\n<p style="font-size:13px;">\r\n	任职要求：\r\n</p>\r\n<p style="font-size:13px;">\r\n	1、5年以上人力资源从业经验，其中2年以上互联网行业HRM工作经历优先；\r\n</p>\r\n<p style="font-size:13px;">\r\n	2、有较丰富的HR团队管理经验，熟悉人力资源至少两到三个模块；\r\n</p>\r\n<p style="font-size:13px;">\r\n	3、思维open，乐于接受挑战，极强的自我驱动力，思路及逻辑清晰，善于分析及解决问题；\r\n</p>\r\n<p style="font-size:13px;">\r\n	4、擅长团队建设，人才发展体系构建优先。\r\n</p>', '', 0),
(21, 0, '<p style="font-size:13px;">\r\n	职位描述：\r\n</p>\r\n<p style="font-size:13px;">\r\n	1、根据公司整体战略，全面统筹制定事业部人力资源战略规划，并在实施中不断优化和提升；\r\n</p>\r\n<p style="font-size:13px;">\r\n	2、深入了解业务发展以及团队特点，结合组织战略，协助开展各项HR相关工作；\r\n</p>\r\n<p style="font-size:13px;">\r\n	3、参与业务体系的经营及战略，理解业务单元的商业需求及发展战略；\r\n</p>\r\n<p style="font-size:13px;">\r\n	4、负责人员的配置与招聘制度、薪酬结构设计、绩效考评制定；\r\n</p>\r\n<p style="font-size:13px;">\r\n	5、 负责全公司人才发展体系的建设， 协助管理层进行人才管理、团队发展、组织氛围建设；\r\n</p>\r\n<p style="font-size:13px;">\r\n	6、做好团队建设和 企业文化建设\r\n</p>\r\n<p style="font-size:13px;">\r\n	&nbsp;\r\n</p>\r\n<p style="font-size:13px;">\r\n	任职要求：\r\n</p>\r\n<p style="font-size:13px;">\r\n	1、5年以上人力资源从业经验，其中2年以上互联网行业HRM工作经历优先；\r\n</p>\r\n<p style="font-size:13px;">\r\n	2、有较丰富的HR团队管理经验，熟悉人力资源至少两到三个模块；\r\n</p>\r\n<p style="font-size:13px;">\r\n	3、思维open，乐于接受挑战，极强的自我驱动力，思路及逻辑清晰，善于分析及解决问题；\r\n</p>\r\n<p style="font-size:13px;">\r\n	4、擅长团队建设，人才发展体系构建优先。\r\n</p>', '', 0),
(22, 0, '<p style="font-size:13px;">\r\n	职位描述：\r\n</p>\r\n<p style="font-size:13px;">\r\n	1、根据公司整体战略，全面统筹制定事业部人力资源战略规划，并在实施中不断优化和提升；\r\n</p>\r\n<p style="font-size:13px;">\r\n	2、深入了解业务发展以及团队特点，结合组织战略，协助开展各项HR相关工作；\r\n</p>\r\n<p style="font-size:13px;">\r\n	3、参与业务体系的经营及战略，理解业务单元的商业需求及发展战略；\r\n</p>\r\n<p style="font-size:13px;">\r\n	4、负责人员的配置与招聘制度、薪酬结构设计、绩效考评制定；\r\n</p>\r\n<p style="font-size:13px;">\r\n	5、 负责全公司人才发展体系的建设， 协助管理层进行人才管理、团队发展、组织氛围建设；\r\n</p>\r\n<p style="font-size:13px;">\r\n	6、做好团队建设和 企业文化建设\r\n</p>\r\n<p style="font-size:13px;">\r\n	&nbsp;\r\n</p>\r\n<p style="font-size:13px;">\r\n	任职要求：\r\n</p>\r\n<p style="font-size:13px;">\r\n	1、5年以上人力资源从业经验，其中2年以上互联网行业HRM工作经历优先；\r\n</p>\r\n<p style="font-size:13px;">\r\n	2、有较丰富的HR团队管理经验，熟悉人力资源至少两到三个模块；\r\n</p>\r\n<p style="font-size:13px;">\r\n	3、思维open，乐于接受挑战，极强的自我驱动力，思路及逻辑清晰，善于分析及解决问题；\r\n</p>\r\n<p style="font-size:13px;">\r\n	4、擅长团队建设，人才发展体系构建优先。\r\n</p>', '', 0),
(23, 0, '<p style="font-size:13px;">\r\n	职位描述：\r\n</p>\r\n<p style="font-size:13px;">\r\n	1、根据公司整体战略，全面统筹制定事业部人力资源战略规划，并在实施中不断优化和提升；\r\n</p>\r\n<p style="font-size:13px;">\r\n	2、深入了解业务发展以及团队特点，结合组织战略，协助开展各项HR相关工作；\r\n</p>\r\n<p style="font-size:13px;">\r\n	3、参与业务体系的经营及战略，理解业务单元的商业需求及发展战略；\r\n</p>\r\n<p style="font-size:13px;">\r\n	4、负责人员的配置与招聘制度、薪酬结构设计、绩效考评制定；\r\n</p>\r\n<p style="font-size:13px;">\r\n	5、 负责全公司人才发展体系的建设， 协助管理层进行人才管理、团队发展、组织氛围建设；\r\n</p>\r\n<p style="font-size:13px;">\r\n	6、做好团队建设和 企业文化建设\r\n</p>\r\n<p style="font-size:13px;">\r\n	&nbsp;\r\n</p>\r\n<p style="font-size:13px;">\r\n	任职要求：\r\n</p>\r\n<p style="font-size:13px;">\r\n	1、5年以上人力资源从业经验，其中2年以上互联网行业HRM工作经历优先；\r\n</p>\r\n<p style="font-size:13px;">\r\n	2、有较丰富的HR团队管理经验，熟悉人力资源至少两到三个模块；\r\n</p>\r\n<p style="font-size:13px;">\r\n	3、思维open，乐于接受挑战，极强的自我驱动力，思路及逻辑清晰，善于分析及解决问题；\r\n</p>\r\n<p style="font-size:13px;">\r\n	4、擅长团队建设，人才发展体系构建优先。\r\n</p>', '', 0),
(24, 0, '<p style="font-size:13px;">\r\n	<span style="font-size:18px;line-height:2;">职位描述：</span> \r\n</p>\r\n<p style="font-size:13px;">\r\n	<span style="font-size:18px;line-height:2;">1、根据公司整体战略，全面统筹制定事业部人力资源战略规划，并在实施中不断优化和提升；</span> \r\n</p>\r\n<p style="font-size:13px;">\r\n	<span style="font-size:18px;line-height:2;">2、深入了解业务发展以及团队特点，结合组织战略，协助开展各项HR相关工作；</span> \r\n</p>\r\n<p style="font-size:13px;">\r\n	<span style="font-size:18px;line-height:2;">3、参与业务体系的经营及战略，理解业务单元的商业需求及发展战略；</span> \r\n</p>\r\n<p style="font-size:13px;">\r\n	<span style="font-size:18px;line-height:2;">4、负责人员的配置与招聘制度、薪酬结构设计、绩效考评制定；</span> \r\n</p>\r\n<p style="font-size:13px;">\r\n	<span style="font-size:18px;line-height:2;background-color:#E53333;">5、 负责全公司人才发展体系的建设， 协助管理层进行人才管理、团队发展、组织氛围建设；</span> \r\n</p>\r\n<p style="font-size:13px;">\r\n	<span style="font-size:18px;line-height:2;">6、做好团队建设和 企业文化建设</span> \r\n</p>\r\n<p style="font-size:13px;">\r\n	<br />\r\n</p>\r\n<p style="font-size:13px;">\r\n	<span style="font-size:18px;line-height:2;">任职要求：</span> \r\n</p>\r\n<p style="font-size:13px;">\r\n	<span style="font-size:18px;line-height:2;">1、5年以上人力资源从业经验，其中2年以上互联网行业HRM工作经历优先；</span> \r\n</p>\r\n<p style="font-size:13px;">\r\n	<span style="font-size:18px;line-height:2;">2、有较丰富的HR团队管理经验，熟悉人力资源至少两到三个模块；</span> \r\n</p>\r\n<p style="font-size:13px;">\r\n	<span style="font-size:18px;line-height:2;">3、思维open，乐于接受挑战，极强的自我驱动力，思路及逻辑清晰，善于分析及解决问题；</span> \r\n</p>\r\n<p style="font-size:13px;">\r\n	<span style="font-size:18px;line-height:2;">4、擅长团队建设，人才发展体系构建优先。</span> \r\n</p>', '', 0);
INSERT INTO `tmm_document_article` (`id`, `parse`, `content`, `template`, `bookmark`) VALUES
(25, 0, '<span style="color:#993366;font-family:Simsun;font-size:14px;line-height:26px;background-color:#F5F5F5;">我早就想去千岛湖，这个好听的名字，是</span><a href="http://sanwenzx.com/sanwenzhuanti/2010/0129/16063.html"><u>旅游</u></a><span style="color:#993366;font-family:Simsun;font-size:14px;line-height:26px;background-color:#F5F5F5;">开发的东风吹出了一个挺有诗意的新名词。早先叫陈蔡水库，原先人们都在叫新安江水库。</span><br />\r\n<span style="color:#993366;font-family:Simsun;font-size:14px;line-height:26px;background-color:#F5F5F5;">　　</span><br />\r\n<span style="color:#993366;font-family:Simsun;font-size:14px;line-height:26px;background-color:#F5F5F5;">　　</span><a href="http://sanwenzx.com/plus/search.php?kwtype=0&amp;keyword=%C7%EF%"><u>秋日</u></a><span style="color:#993366;font-family:Simsun;font-size:14px;line-height:26px;background-color:#F5F5F5;">的</span><a href="http://sanwenzx.com/plus/search.php?kwtype=0&amp;keyword=%D1%F4%"><u>阳光</u></a><span style="color:#993366;font-family:Simsun;font-size:14px;line-height:26px;background-color:#F5F5F5;">照在东白湖渡口，高而陡的土坡下躺着两条木船，人坐在木船里凭木船在哒哒哒的轰鸣声中环湖行驶。清风徐来，水波不兴，木船的身影渐渐远去，站在坡上会有一种“孤帆远影碧空尽，唯见长江天际流”的感受。大家舍舟登岸，便见到一片桔林，累累的红桔挂坠枝头，猕猴桃酸甜甜的刚好尝新，这山区天然的</span><a href="http://sanwenzx.com/plus/search.php?kwtype=0&amp;keyword=+%C2%CC"><u>绿色</u></a><span style="color:#993366;font-family:Simsun;font-size:14px;line-height:26px;background-color:#F5F5F5;">食品使人们馋涎欲滴，真是流连忘返。</span><br />\r\n<span style="color:#993366;font-family:Simsun;font-size:14px;line-height:26px;background-color:#F5F5F5;">　　<img src="/Uploads/Editor/2016-01-15/5698beff3eb2b.jpg" alt="" /></span><br />\r\n<span style="color:#993366;font-family:Simsun;font-size:14px;line-height:26px;background-color:#F5F5F5;">　　漫步在东白湖的大坝上，极目远眺，碧波浩淼，苍山拥翠，曲水环绕，人犹如置身天然的氧气库，清新极了！“前人栽树，后人乘凉”，当年一亿多立方米的陈蔡水库，如今的东白湖已是市民能吃上纯净水、放心水的水源！是啊，有山有水，风景才能相映成趣。游了东白湖，又去了西岩。</span><br />\r\n<span style="color:#993366;font-family:Simsun;font-size:14px;line-height:26px;background-color:#F5F5F5;">　　</span><br />\r\n<span style="color:#993366;font-family:Simsun;font-size:14px;line-height:26px;background-color:#F5F5F5;">　　西岩观瀑，可算得东白湖一景。如在春夏</span><a href="http://sanwenzx.com/plus/search.php?kwtype=0&amp;keyword=%BC%BE%"><u>季节</u></a><span style="color:#993366;font-family:Simsun;font-size:14px;line-height:26px;background-color:#F5F5F5;">，那瀑布訇然作声，水花翩舞，如玉龙奔腾，把人引入一种缥渺之境，绝不比雁荡的大龙湫、三折瀑逊色。可惜我们去的秋季，比较干旱，瀑布雄浑的景象不曾见着。不过，就在这通向瀑布的大溪，沿溪走走，也是蛮有诗情画意的。溪涧涓涓</span><a href="http://sanwenzx.com/sanwenzhuanti/2010/0319/18478.html"><u>流水</u></a><span style="color:#993366;font-family:Simsun;font-size:14px;line-height:26px;background-color:#F5F5F5;">不断，不时溅起水花，似乎向游客们频频招手。水清透碧，十分澄澈，尽看到溪底累累的卵石，千姿百态，吸引眼球。两旁是陡峭的岩石和山峰。要是在春季，那无名的小花</span><a href="http://sanwenzx.com/plus/search.php?kwtype=0&amp;keyword=%D0%A1%"><u>小草</u></a><span style="color:#993366;font-family:Simsun;font-size:14px;line-height:26px;background-color:#F5F5F5;">，那山坡上的丛丛映山红开放，该是多么生机勃勃的风景。要是在</span><a href="http://sanwenzx.com/plus/search.php?kwtype=0&amp;keyword=+%CF%C4"><u>夏天</u></a><span style="color:#993366;font-family:Simsun;font-size:14px;line-height:26px;background-color:#F5F5F5;">，你会想到“蝉噪林愈静，鸟鸣山更幽”，如今则可以体会到“秋林红艳”的滋味，而且这种荒竦和古朴的风味在喧闹的尘世中是寻觅不到的，只有在古人的画卷中偶而见到，脚下淙淙的溪流，虽没有喷玉泻珠的飞泉来沾湿你的衣袜，你可以悠闲地穿着皮鞋爬过石坡，穿行在长长的峡谷之中，慢慢品尝西岩的野、奇、险了。西岩的古木保存得格外的好，村边路口十多株名贵古树还挂着牌子呢！</span><br />\r\n<span style="color:#993366;font-family:Simsun;font-size:14px;line-height:26px;background-color:#F5F5F5;">　　<img src="/Uploads/Editor/2016-01-15/5698bf0cc226b.jpg" alt="" /></span><br />\r\n<span style="color:#993366;font-family:Simsun;font-size:14px;line-height:26px;background-color:#F5F5F5;">　　哦，又到了东白湖，这是文化里的山水啊！并非所有的旧屋都是景观，但东白湖的古建筑群——斯宅民居则是。且不说数千平方米的斯盛居和华国公别墅，单是那条斯盛居通向笔峰书院的松啸湾就使你神往了。这条名为松啸湾的山径小道，大有情趣。鹅卵石古道两旁，至今古木参天，楸树黄檀，互相掩映，罗汉松、龙爪槐，虬枝横出，山玉兰紫薇花，争芳斗艳。柳杉冬青等，郁郁葱葱，堆兰叠翠，笔峰书院就在松啸湾上面，原先破败不堪的旧屋正在整修，要不了多少</span><a href="http://sanwenzx.com/sanwenzhuanti/2010/0131/16169.html"><u>时间</u></a><span style="color:#993366;font-family:Simsun;font-size:14px;line-height:26px;background-color:#F5F5F5;">，这里自有一番风景。站在松啸湾隆起的土坡上，俯瞰斯盛居，房屋鳞次栉比，合成一个硕大的四合院，蔚为壮观。上林溪畔的华国公别墅也在崛起。文保科长告诉我，如今资金充裕，百姓意识新潮，斯宅民居在不日之将来会以全新面貌展现在人们面前。你要了解诸暨古代的建筑工艺，诸如石雕、木雕、砖雕之类，准得走进这古建筑群去细细品尝，保证你在《百马图》之类的雕刻</span><a href="http://sanwenzx.com/plus/search.php?kwtype=0&amp;keyword=%D2%D5%"><u>艺术</u></a><span style="color:#993366;font-family:Simsun;font-size:14px;line-height:26px;background-color:#F5F5F5;">前不肯举步呢！你看这千柱屋，不是山水里的文化吗？</span><br />\r\n<span style="color:#993366;font-family:Simsun;font-size:14px;line-height:26px;background-color:#F5F5F5;">　　</span><br />\r\n<span style="color:#993366;font-family:Simsun;font-size:14px;line-height:26px;background-color:#F5F5F5;">　　我想决心以生态文化为品牌，做到自然和人的和谐才是</span><a href="http://sanwenzx.com/plus/search.php?kwtype=0&amp;keyword=%D3%C0%"><u>永恒</u></a><span style="color:#993366;font-family:Simsun;font-size:14px;line-height:26px;background-color:#F5F5F5;">的主题。听着这热气腾腾的导游介绍，我分明感受到另一道风景——人的风景。自然的、原始的、地方特色的，也许就是最好的风景。而科学的、文明的、理智的人，则肯定是风景中的风景啊！</span>', '', 0);

-- --------------------------------------------------------

--
-- 表的结构 `tmm_document_download`
--

DROP TABLE IF EXISTS `tmm_document_download`;
CREATE TABLE IF NOT EXISTS `tmm_document_download` (
  `id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文档ID',
  `parse` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '内容解析类型',
  `content` text NOT NULL COMMENT '下载详细描述',
  `template` varchar(100) NOT NULL DEFAULT '' COMMENT '详情页显示模板',
  `file_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文件ID',
  `download` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '下载次数',
  `size` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '文件大小',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='文档模型下载表';

-- --------------------------------------------------------

--
-- 表的结构 `tmm_file`
--

DROP TABLE IF EXISTS `tmm_file`;
CREATE TABLE IF NOT EXISTS `tmm_file` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '文件ID',
  `name` char(30) NOT NULL DEFAULT '' COMMENT '原始文件名',
  `savename` char(20) NOT NULL DEFAULT '' COMMENT '保存名称',
  `savepath` char(30) NOT NULL DEFAULT '' COMMENT '文件保存路径',
  `ext` char(5) NOT NULL DEFAULT '' COMMENT '文件后缀',
  `mime` char(40) NOT NULL DEFAULT '' COMMENT '文件mime类型',
  `size` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文件大小',
  `md5` char(32) NOT NULL DEFAULT '' COMMENT '文件md5',
  `sha1` char(40) NOT NULL DEFAULT '' COMMENT '文件 sha1编码',
  `location` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '文件保存位置',
  `create_time` int(10) unsigned NOT NULL COMMENT '上传时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_md5` (`md5`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='文件表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `tmm_hooks`
--

DROP TABLE IF EXISTS `tmm_hooks`;
CREATE TABLE IF NOT EXISTS `tmm_hooks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(40) NOT NULL DEFAULT '' COMMENT '钩子名称',
  `description` text NOT NULL COMMENT '描述',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '类型',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `addons` varchar(255) NOT NULL DEFAULT '' COMMENT '钩子挂载的插件 ''，''分割',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

--
-- 转存表中的数据 `tmm_hooks`
--

INSERT INTO `tmm_hooks` (`id`, `name`, `description`, `type`, `update_time`, `addons`) VALUES
(1, 'pageHeader', '页面header钩子，一般用于加载插件CSS文件和代码', 1, 0, ''),
(2, 'pageFooter', '页面footer钩子，一般用于加载插件JS文件和JS代码', 1, 0, 'ReturnTop'),
(3, 'documentEditForm', '添加编辑表单的 扩展内容钩子', 1, 0, 'Attachment'),
(4, 'documentDetailAfter', '文档末尾显示', 1, 0, 'Attachment,SocialComment'),
(5, 'documentDetailBefore', '页面内容前显示用钩子', 1, 0, ''),
(6, 'documentSaveComplete', '保存文档数据后的扩展钩子', 2, 0, 'Attachment'),
(7, 'documentEditFormContent', '添加编辑表单的内容显示钩子', 1, 0, 'Editor'),
(8, 'adminArticleEdit', '后台内容编辑页编辑器', 1, 1378982734, 'EditorForAdmin'),
(13, 'AdminIndex', '首页小格子个性化显示', 1, 1382596073, 'SiteStat'),
(14, 'topicComment', '评论提交方式扩展钩子。', 1, 1380163518, 'Editor'),
(16, 'app_begin', '应用开始', 2, 1384481614, '');

-- --------------------------------------------------------

--
-- 表的结构 `tmm_member`
--

DROP TABLE IF EXISTS `tmm_member`;
CREATE TABLE IF NOT EXISTS `tmm_member` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `nickname` char(16) NOT NULL DEFAULT '' COMMENT '昵称',
  `sex` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '性别',
  `birthday` date NOT NULL DEFAULT '0000-00-00' COMMENT '生日',
  `qq` char(10) NOT NULL DEFAULT '' COMMENT 'qq号',
  `score` mediumint(8) NOT NULL DEFAULT '0' COMMENT '用户积分',
  `login` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '登录次数',
  `reg_ip` bigint(20) NOT NULL DEFAULT '0' COMMENT '注册IP',
  `reg_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '注册时间',
  `last_login_ip` bigint(20) NOT NULL DEFAULT '0' COMMENT '最后登录IP',
  `last_login_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '会员状态',
  PRIMARY KEY (`uid`),
  KEY `status` (`status`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='会员表' AUTO_INCREMENT=4 ;

--
-- 转存表中的数据 `tmm_member`
--

INSERT INTO `tmm_member` (`uid`, `nickname`, `sex`, `birthday`, `qq`, `score`, `login`, `reg_ip`, `reg_time`, `last_login_ip`, `last_login_time`, `status`) VALUES
(1, 'tmm_admin', 0, '0000-00-00', '', 10, 16, 0, 1452741194, 3071281660, 1452850065, 1),
(2, 'yuwenzhang', 0, '0000-00-00', '', 10, 1, 0, 0, 3071281660, 1452841322, 1),
(3, 'wintonzhang', 0, '0000-00-00', '', 10, 7, 0, 0, 3071281737, 1452850865, 1);

-- --------------------------------------------------------

--
-- 表的结构 `tmm_menu`
--

DROP TABLE IF EXISTS `tmm_menu`;
CREATE TABLE IF NOT EXISTS `tmm_menu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '文档ID',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '标题',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID',
  `sort` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序（同级有效）',
  `url` char(255) NOT NULL DEFAULT '' COMMENT '链接地址',
  `hide` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否隐藏',
  `tip` varchar(255) NOT NULL DEFAULT '' COMMENT '提示',
  `group` varchar(50) DEFAULT '' COMMENT '分组',
  `is_dev` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否仅开发者模式可见',
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=122 ;

--
-- 转存表中的数据 `tmm_menu`
--

INSERT INTO `tmm_menu` (`id`, `title`, `pid`, `sort`, `url`, `hide`, `tip`, `group`, `is_dev`) VALUES
(1, '首页', 0, 1, 'Index/index', 0, '', '', 0),
(2, '内容', 0, 2, 'Article/mydocument', 0, '', '', 0),
(3, '文档列表', 2, 0, 'article/index', 1, '', '内容', 0),
(4, '新增', 3, 0, 'article/add', 0, '', '', 0),
(5, '编辑', 3, 0, 'article/edit', 0, '', '', 0),
(6, '改变状态', 3, 0, 'article/setStatus', 0, '', '', 0),
(7, '保存', 3, 0, 'article/update', 0, '', '', 0),
(8, '保存草稿', 3, 0, 'article/autoSave', 0, '', '', 0),
(9, '移动', 3, 0, 'article/move', 0, '', '', 0),
(10, '复制', 3, 0, 'article/copy', 0, '', '', 0),
(11, '粘贴', 3, 0, 'article/paste', 0, '', '', 0),
(12, '导入', 3, 0, 'article/batchOperate', 0, '', '', 0),
(13, '回收站', 2, 0, 'article/recycle', 1, '', '内容', 0),
(14, '还原', 13, 0, 'article/permit', 0, '', '', 0),
(15, '清空', 13, 0, 'article/clear', 0, '', '', 0),
(16, '用户', 0, 3, 'User/index', 0, '', '', 0),
(17, '用户信息', 16, 0, 'User/index', 0, '', '用户管理', 0),
(18, '新增用户', 17, 0, 'User/add', 0, '添加新用户', '', 0),
(19, '用户行为', 16, 0, 'User/action', 0, '', '行为管理', 0),
(20, '新增用户行为', 19, 0, 'User/addaction', 0, '', '', 0),
(21, '编辑用户行为', 19, 0, 'User/editaction', 0, '', '', 0),
(22, '保存用户行为', 19, 0, 'User/saveAction', 0, '"用户->用户行为"保存编辑和新增的用户行为', '', 0),
(23, '变更行为状态', 19, 0, 'User/setStatus', 0, '"用户->用户行为"中的启用,禁用和删除权限', '', 0),
(24, '禁用会员', 19, 0, 'User/changeStatus?method=forbidUser', 0, '"用户->用户信息"中的禁用', '', 0),
(25, '启用会员', 19, 0, 'User/changeStatus?method=resumeUser', 0, '"用户->用户信息"中的启用', '', 0),
(26, '删除会员', 19, 0, 'User/changeStatus?method=deleteUser', 0, '"用户->用户信息"中的删除', '', 0),
(27, '权限管理', 16, 0, 'AuthManager/index', 0, '', '用户管理', 0),
(28, '删除', 27, 0, 'AuthManager/changeStatus?method=deleteGroup', 0, '删除用户组', '', 0),
(29, '禁用', 27, 0, 'AuthManager/changeStatus?method=forbidGroup', 0, '禁用用户组', '', 0),
(30, '恢复', 27, 0, 'AuthManager/changeStatus?method=resumeGroup', 0, '恢复已禁用的用户组', '', 0),
(31, '新增', 27, 0, 'AuthManager/createGroup', 0, '创建新的用户组', '', 0),
(32, '编辑', 27, 0, 'AuthManager/editGroup', 0, '编辑用户组名称和描述', '', 0),
(33, '保存用户组', 27, 0, 'AuthManager/writeGroup', 0, '新增和编辑用户组的"保存"按钮', '', 0),
(34, '授权', 27, 0, 'AuthManager/group', 0, '"后台 \\ 用户 \\ 用户信息"列表页的"授权"操作按钮,用于设置用户所属用户组', '', 0),
(35, '访问授权', 27, 0, 'AuthManager/access', 0, '"后台 \\ 用户 \\ 权限管理"列表页的"访问授权"操作按钮', '', 0),
(36, '成员授权', 27, 0, 'AuthManager/user', 0, '"后台 \\ 用户 \\ 权限管理"列表页的"成员授权"操作按钮', '', 0),
(37, '解除授权', 27, 0, 'AuthManager/removeFromGroup', 0, '"成员授权"列表页内的解除授权操作按钮', '', 0),
(38, '保存成员授权', 27, 0, 'AuthManager/addToGroup', 0, '"用户信息"列表页"授权"时的"保存"按钮和"成员授权"里右上角的"添加"按钮)', '', 0),
(39, '分类授权', 27, 0, 'AuthManager/category', 0, '"后台 \\ 用户 \\ 权限管理"列表页的"分类授权"操作按钮', '', 0),
(40, '保存分类授权', 27, 0, 'AuthManager/addToCategory', 0, '"分类授权"页面的"保存"按钮', '', 0),
(41, '模型授权', 27, 0, 'AuthManager/modelauth', 0, '"后台 \\ 用户 \\ 权限管理"列表页的"模型授权"操作按钮', '', 0),
(42, '保存模型授权', 27, 0, 'AuthManager/addToModel', 0, '"分类授权"页面的"保存"按钮', '', 0),
(43, '扩展', 0, 7, 'Addons/index', 0, '', '', 0),
(44, '插件管理', 43, 1, 'Addons/index', 0, '', '扩展', 0),
(45, '创建', 44, 0, 'Addons/create', 0, '服务器上创建插件结构向导', '', 0),
(46, '检测创建', 44, 0, 'Addons/checkForm', 0, '检测插件是否可以创建', '', 0),
(47, '预览', 44, 0, 'Addons/preview', 0, '预览插件定义类文件', '', 0),
(48, '快速生成插件', 44, 0, 'Addons/build', 0, '开始生成插件结构', '', 0),
(49, '设置', 44, 0, 'Addons/config', 0, '设置插件配置', '', 0),
(50, '禁用', 44, 0, 'Addons/disable', 0, '禁用插件', '', 0),
(51, '启用', 44, 0, 'Addons/enable', 0, '启用插件', '', 0),
(52, '安装', 44, 0, 'Addons/install', 0, '安装插件', '', 0),
(53, '卸载', 44, 0, 'Addons/uninstall', 0, '卸载插件', '', 0),
(54, '更新配置', 44, 0, 'Addons/saveconfig', 0, '更新插件配置处理', '', 0),
(55, '插件后台列表', 44, 0, 'Addons/adminList', 0, '', '', 0),
(56, 'URL方式访问插件', 44, 0, 'Addons/execute', 0, '控制是否有权限通过url访问插件控制器方法', '', 0),
(57, '钩子管理', 43, 2, 'Addons/hooks', 0, '', '扩展', 0),
(58, '模型管理', 68, 3, 'Model/index', 0, '', '系统设置', 0),
(59, '新增', 58, 0, 'model/add', 0, '', '', 0),
(60, '编辑', 58, 0, 'model/edit', 0, '', '', 0),
(61, '改变状态', 58, 0, 'model/setStatus', 0, '', '', 0),
(62, '保存数据', 58, 0, 'model/update', 0, '', '', 0),
(63, '属性管理', 68, 0, 'Attribute/index', 1, '网站属性配置。', '', 0),
(64, '新增', 63, 0, 'Attribute/add', 0, '', '', 0),
(65, '编辑', 63, 0, 'Attribute/edit', 0, '', '', 0),
(66, '改变状态', 63, 0, 'Attribute/setStatus', 0, '', '', 0),
(67, '保存数据', 63, 0, 'Attribute/update', 0, '', '', 0),
(68, '系统', 0, 4, 'Config/group', 0, '', '', 0),
(69, '网站设置', 68, 1, 'Config/group', 0, '', '系统设置', 0),
(70, '配置管理', 68, 4, 'Config/index', 0, '', '系统设置', 0),
(71, '编辑', 70, 0, 'Config/edit', 0, '新增编辑和保存配置', '', 0),
(72, '删除', 70, 0, 'Config/del', 0, '删除配置', '', 0),
(73, '新增', 70, 0, 'Config/add', 0, '新增配置', '', 0),
(74, '保存', 70, 0, 'Config/save', 0, '保存配置', '', 0),
(75, '菜单管理', 68, 5, 'Menu/index', 0, '', '系统设置', 0),
(76, '导航管理', 68, 6, 'Channel/index', 0, '', '系统设置', 0),
(77, '新增', 76, 0, 'Channel/add', 0, '', '', 0),
(78, '编辑', 76, 0, 'Channel/edit', 0, '', '', 0),
(79, '删除', 76, 0, 'Channel/del', 0, '', '', 0),
(80, '分类管理', 68, 2, 'Category/index', 0, '', '系统设置', 0),
(81, '编辑', 80, 0, 'Category/edit', 0, '编辑和保存栏目分类', '', 0),
(82, '新增', 80, 0, 'Category/add', 0, '新增栏目分类', '', 0),
(83, '删除', 80, 0, 'Category/remove', 0, '删除栏目分类', '', 0),
(84, '移动', 80, 0, 'Category/operate/type/move', 0, '移动栏目分类', '', 0),
(85, '合并', 80, 0, 'Category/operate/type/merge', 0, '合并栏目分类', '', 0),
(86, '备份数据库', 68, 0, 'Database/index?type=export', 0, '', '数据备份', 0),
(87, '备份', 86, 0, 'Database/export', 0, '备份数据库', '', 0),
(88, '优化表', 86, 0, 'Database/optimize', 0, '优化数据表', '', 0),
(89, '修复表', 86, 0, 'Database/repair', 0, '修复数据表', '', 0),
(90, '还原数据库', 68, 0, 'Database/index?type=import', 0, '', '数据备份', 0),
(91, '恢复', 90, 0, 'Database/import', 0, '数据库恢复', '', 0),
(92, '删除', 90, 0, 'Database/del', 0, '删除备份文件', '', 0),
(93, '其他', 0, 5, 'other', 1, '', '', 0),
(96, '新增', 75, 0, 'Menu/add', 0, '', '系统设置', 0),
(98, '编辑', 75, 0, 'Menu/edit', 0, '', '', 0),
(104, '下载管理', 102, 0, 'Think/lists?model=download', 0, '', '', 0),
(105, '配置管理', 102, 0, 'Think/lists?model=config', 0, '', '', 0),
(106, '行为日志', 16, 0, 'Action/actionlog', 0, '', '行为管理', 0),
(108, '修改密码', 16, 0, 'User/updatePassword', 1, '', '', 0),
(109, '修改昵称', 16, 0, 'User/updateNickname', 1, '', '', 0),
(110, '查看行为日志', 106, 0, 'action/edit', 1, '', '', 0),
(112, '新增数据', 58, 0, 'think/add', 1, '', '', 0),
(113, '编辑数据', 58, 0, 'think/edit', 1, '', '', 0),
(114, '导入', 75, 0, 'Menu/import', 0, '', '', 0),
(115, '生成', 58, 0, 'Model/generate', 0, '', '', 0),
(116, '新增钩子', 57, 0, 'Addons/addHook', 0, '', '', 0),
(117, '编辑钩子', 57, 0, 'Addons/edithook', 0, '', '', 0),
(118, '文档排序', 3, 0, 'Article/sort', 1, '', '', 0),
(119, '排序', 70, 0, 'Config/sort', 1, '', '', 0),
(120, '排序', 75, 0, 'Menu/sort', 1, '', '', 0),
(121, '排序', 76, 0, 'Channel/sort', 1, '', '', 0);

-- --------------------------------------------------------

--
-- 表的结构 `tmm_model`
--

DROP TABLE IF EXISTS `tmm_model`;
CREATE TABLE IF NOT EXISTS `tmm_model` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '模型ID',
  `name` char(30) NOT NULL DEFAULT '' COMMENT '模型标识',
  `title` char(30) NOT NULL DEFAULT '' COMMENT '模型名称',
  `extend` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '继承的模型',
  `relation` varchar(30) NOT NULL DEFAULT '' COMMENT '继承与被继承模型的关联字段',
  `need_pk` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '新建表时是否需要主键字段',
  `field_sort` text NOT NULL COMMENT '表单字段排序',
  `field_group` varchar(255) NOT NULL DEFAULT '1:基础' COMMENT '字段分组',
  `attribute_list` text NOT NULL COMMENT '属性列表（表的字段）',
  `template_list` varchar(100) NOT NULL DEFAULT '' COMMENT '列表模板',
  `template_add` varchar(100) NOT NULL DEFAULT '' COMMENT '新增模板',
  `template_edit` varchar(100) NOT NULL DEFAULT '' COMMENT '编辑模板',
  `list_grid` text NOT NULL COMMENT '列表定义',
  `list_row` smallint(2) unsigned NOT NULL DEFAULT '10' COMMENT '列表数据长度',
  `search_key` varchar(50) NOT NULL DEFAULT '' COMMENT '默认搜索字段',
  `search_list` varchar(255) NOT NULL DEFAULT '' COMMENT '高级搜索的字段',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `engine_type` varchar(25) NOT NULL DEFAULT 'MyISAM' COMMENT '数据库引擎',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='文档模型表' AUTO_INCREMENT=4 ;

--
-- 转存表中的数据 `tmm_model`
--

INSERT INTO `tmm_model` (`id`, `name`, `title`, `extend`, `relation`, `need_pk`, `field_sort`, `field_group`, `attribute_list`, `template_list`, `template_add`, `template_edit`, `list_grid`, `list_row`, `search_key`, `search_list`, `create_time`, `update_time`, `status`, `engine_type`) VALUES
(1, 'document', '基础文档', 0, '', 1, '{"1":["1","2","3","4","5","6","7","8","9","10","11","12","13","14","15","16","17","18","19","20","21","22"]}', '1:基础', '', '', '', '', 'id:编号\r\ntitle:标题:article/index?cate_id=[category_id]&pid=[id]\r\ntype|get_document_type:类型\r\nlevel:优先级\r\nupdate_time|time_format:最后更新\r\nstatus_text:状态\r\nview:浏览\r\nid:操作:[EDIT]&cate_id=[category_id]|编辑,article/setstatus?status=-1&ids=[id]|删除', 0, '', '', 1383891233, 1384507827, 1, 'MyISAM'),
(2, 'article', '文章', 1, '', 1, '{"1":["3","24","5","12"],"2":["9","13","2","19","10","16","17","26","20","14","11","25"]}', '1:基础,2:扩展', '', '', '', '', 'id:编号\r\ntitle:标题:article/edit?cate_id=[category_id]&id=[id]\r\ncontent:内容', 0, '', '', 1383891243, 1452766779, 1, 'MyISAM'),
(3, 'download', '下载', 1, '', 1, '{"1":["3","28","30","32","2","5","31"],"2":["13","10","27","9","12","16","17","19","11","20","14","29"]}', '1:基础,2:扩展', '', '', '', '', 'id:编号\r\ntitle:标题', 0, '', '', 1383891252, 1387260449, 1, 'MyISAM');

-- --------------------------------------------------------

--
-- 表的结构 `tmm_picture`
--

DROP TABLE IF EXISTS `tmm_picture`;
CREATE TABLE IF NOT EXISTS `tmm_picture` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id自增',
  `path` varchar(255) NOT NULL DEFAULT '' COMMENT '路径',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '图片链接',
  `md5` char(32) NOT NULL DEFAULT '' COMMENT '文件md5',
  `sha1` char(40) NOT NULL DEFAULT '' COMMENT '文件 sha1编码',
  `status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '状态',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

--
-- 转存表中的数据 `tmm_picture`
--

INSERT INTO `tmm_picture` (`id`, `path`, `url`, `md5`, `sha1`, `status`, `create_time`) VALUES
(1, '/Uploads/Picture/2016-01-14/5697769e241b0.png', '', 'b35a62471f530365340673f584dd1608', '98509f409eb89172c8f400e9be6b0f5c312bb781', 1, 1452766878),
(2, '/Uploads/Picture/2016-01-14/56977702864e0.png', '', '417d3cbb521e86c5667eaef265f43b99', '6cdabd04ad7ffb2546db13bbe90256b10ffd7b35', 1, 1452766978),
(3, '/Uploads/Picture/2016-01-15/569886c9888be.jpg', '', 'a2b6ce7dfa0a5e090050e03bd4aaec12', '66147295c920beaf253c3ec42a0d7e0890f79516', 1, 1452836553),
(4, '/Uploads/Picture/2016-01-15/569888536ec47.jpg', '', '32ad5351c37b09a3c5f8fe9ffca825d3', 'fc99461eb2ab464353ac369c2ff0b482a3c5b3cb', 1, 1452836947),
(5, '/Uploads/Picture/2016-01-15/56988b184ec68.jpg', '', '7beda119c9edeab7f5cbdfad4fabe238', 'ae3c782de42997424f4e8015ced553855ccd4a1b', 1, 1452837656),
(6, '/Uploads/Picture/2016-01-15/56988fb12879d.jpg', '', '2d000f7df23a282bd2466fd776f15681', '6b0e3d29fa1311f489fd44f7877813bafbaf73d6', 1, 1452838833),
(7, '/Uploads/Picture/2016-01-15/56989353d51d9.jpg', '', '56fe01bef0dc6ca0bd14f26314a923c4', '3df67ff14aaef31a2d35273779dde813c5f2ad82', 1, 1452839763),
(8, '/Uploads/Picture/2016-01-15/56989469d9061.jpg', '', '0ba514a4c3a44e9a8c9c0eec090ad062', '4e2066602b6d3067635825e9fbc10c575185c7b3', 1, 1452840041),
(9, '/Uploads/Picture/2016-01-15/569894e11cc96.jpg', '', '9549a4ea1cde58f67679ef0ac05aa9b1', 'b2cd3749256f741d209f9d52588316306027c0a4', 1, 1452840161),
(10, '/Uploads/Picture/2016-01-15/569895b749056.jpg', '', 'b3e7ea46291fd56bbe87e17e6dcd0973', '9cf0035223039d9f5e7de08bf27d1e46c17fafc2', 1, 1452840375),
(11, '/Uploads/Picture/2016-01-15/56989c29aa548.jpg', '', '2b5545ba43841cecce1aa25068a4f586', '28ac3db84fbadc9baeb4d70e0a133327ef10874a', 1, 1452842025);

-- --------------------------------------------------------

--
-- 表的结构 `tmm_ucenter_admin`
--

DROP TABLE IF EXISTS `tmm_ucenter_admin`;
CREATE TABLE IF NOT EXISTS `tmm_ucenter_admin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '管理员ID',
  `member_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '管理员用户ID',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '管理员状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='管理员表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `tmm_ucenter_app`
--

DROP TABLE IF EXISTS `tmm_ucenter_app`;
CREATE TABLE IF NOT EXISTS `tmm_ucenter_app` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '应用ID',
  `title` varchar(30) NOT NULL COMMENT '应用名称',
  `url` varchar(100) NOT NULL COMMENT '应用URL',
  `ip` char(15) NOT NULL COMMENT '应用IP',
  `auth_key` varchar(100) NOT NULL COMMENT '加密KEY',
  `sys_login` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '同步登陆',
  `allow_ip` varchar(255) NOT NULL COMMENT '允许访问的IP',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '应用状态',
  PRIMARY KEY (`id`),
  KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='应用表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `tmm_ucenter_member`
--

DROP TABLE IF EXISTS `tmm_ucenter_member`;
CREATE TABLE IF NOT EXISTS `tmm_ucenter_member` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `username` char(16) NOT NULL COMMENT '用户名',
  `password` char(32) NOT NULL COMMENT '密码',
  `email` char(32) NOT NULL COMMENT '用户邮箱',
  `mobile` char(15) NOT NULL COMMENT '用户手机',
  `reg_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '注册时间',
  `reg_ip` bigint(20) NOT NULL DEFAULT '0' COMMENT '注册IP',
  `last_login_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `last_login_ip` bigint(20) NOT NULL DEFAULT '0' COMMENT '最后登录IP',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(4) DEFAULT '0' COMMENT '用户状态',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `status` (`status`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='用户表' AUTO_INCREMENT=4 ;

--
-- 转存表中的数据 `tmm_ucenter_member`
--

INSERT INTO `tmm_ucenter_member` (`id`, `username`, `password`, `email`, `mobile`, `reg_time`, `reg_ip`, `last_login_time`, `last_login_ip`, `update_time`, `status`) VALUES
(1, 'tmm_admin', 'a0ad7bc3adeacfe4f5e88a867ff9f130', 'mooremo@yeah.net', '', 1452741194, 0, 1452850065, 3071281660, 1452741194, 1),
(2, 'yuwenzhang', '9476aec29919bfaee48cfb086711ac79', 'fdsfdsf@ewfe.sdfsd', '', 1452840854, 3071281737, 1452841322, 3071281660, 1452840854, 1),
(3, 'wintonzhang', 'cdd789a35ef678534e991e8f7a079267', 'asdsadas@sefsd.fssdf', '', 1452841004, 3071281737, 1452850865, 3071281737, 1452841004, 1);

-- --------------------------------------------------------

--
-- 表的结构 `tmm_ucenter_setting`
--

DROP TABLE IF EXISTS `tmm_ucenter_setting`;
CREATE TABLE IF NOT EXISTS `tmm_ucenter_setting` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '设置ID',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '配置类型（1-用户配置）',
  `value` text NOT NULL COMMENT '配置数据',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='设置表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `tmm_url`
--

DROP TABLE IF EXISTS `tmm_url`;
CREATE TABLE IF NOT EXISTS `tmm_url` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '链接唯一标识',
  `url` char(255) NOT NULL DEFAULT '' COMMENT '链接地址',
  `short` char(100) NOT NULL DEFAULT '' COMMENT '短网址',
  `status` tinyint(2) NOT NULL DEFAULT '2' COMMENT '状态',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_url` (`url`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='链接表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `tmm_userdata`
--

DROP TABLE IF EXISTS `tmm_userdata`;
CREATE TABLE IF NOT EXISTS `tmm_userdata` (
  `uid` int(10) unsigned NOT NULL COMMENT '用户id',
  `type` tinyint(3) unsigned NOT NULL COMMENT '类型标识',
  `target_id` int(10) unsigned NOT NULL COMMENT '目标id',
  UNIQUE KEY `uid` (`uid`,`type`,`target_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
