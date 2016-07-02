--
-- 表的结构 `tmm_items` 项目主表
--
CREATE TABLE IF NOT EXISTS `tmm_items` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `c_id` INT(11) UNSIGNED NOT NULL COMMENT '关联项目数据模型表（items_classliy）主键id',
  `agent_id` INT(11) UNSIGNED NOT NULL COMMENT '关联代理商用户表（agent）主键id',
  `store_id` INT(11) UNSIGNED NOT NULL COMMENT '关联商家用户表(store)主键id',
  `manager_id` INT(11) UNSIGNED NOT NULL COMMENT '项目管理者(关联商家用户表（store）主键 id',
  `name` VARCHAR(100) NOT NULL COMMENT '项目名称',
  `area_id_p` INT(11) UNSIGNED NOT NULL COMMENT '关联地名表(aera)主键id (p_id=0) 省(市)',
  `area_id_m` INT(11) UNSIGNED NOT NULL COMMENT '关联地名表(aera)主键id (p_id=t.area_id_p) 市(区)',
  `area_id_c` INT(11) UNSIGNED NOT NULL COMMENT '关联地名表(aera)主键id (p_id=t.area_id_m) 县(区)',
  `address` VARCHAR(100) NOT NULL COMMENT '详细地址',
  `push` FLOAT(5,2) NOT NULL DEFAULT 0.00 COMMENT '平台对项目的抽成 %',
  `info` VARCHAR(100) NULL COMMENT '简介',
  `phone` VARCHAR(20) NULL COMMENT '联系电话',
  `weixin` VARCHAR(20) NULL COMMENT '微信号',
  `content` TEXT NOT NULL DEFAULT '' COMMENT '项目详细内容',
  `audit` TINYINT(3) NOT NULL DEFAULT 0 COMMENT '审核状态-1不通过 0 未审核 1 通过',  
  `down` int(10) unsigned NOT NULL DEFAULT '1' COMMENT '下单量',
  `start_work` TIME NOT NULL DEFAULT '00:00:00' COMMENT '工作开始时间',
  `end_work` TIME NOT NULL DEFAULT '23:59:59' COMMENT '工作结束时间',
  `pub_time` INT(10) unsigned NOT NULL COMMENT '发布时间',
  `add_time` INT(10) UNSIGNED NOT NULL COMMENT '创建时间',
  `up_time` INT(10) UNSIGNED NOT NULL COMMENT '最后一次更新时间',
  `status` TINYINT(3) NOT NULL DEFAULT 1 COMMENT '记录状态',
  PRIMARY KEY (`id`)
 )ENGINE = InnoDB COMMENT = '项目主表' DEFAULT CHARSET=utf8;
 
--
-- 表的结构 `tmm_items_classliy` 项目类型
--
 CREATE TABLE IF NOT EXISTS `tmm_items_classliy` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `name` VARCHAR(20) NOT NULL COMMENT '项目数据模型id',
  `info` VARCHAR(200) NULL COMMENT '项目数据模型简介说明',
  `front` VARCHAR(45) NOT NULL COMMENT '前端控制器',
  `tem_index` VARCHAR(45) NOT NULL DEFAULT 'index' COMMENT '前端封面模板',
  `tem_list` VARCHAR(45) NOT NULL DEFAULT 'list' COMMENT '前端列表模板',
  `tem_page` VARCHAR(45) NOT NULL DEFAULT 'page' COMMENT '前端内容模板',
  `admin` VARCHAR(45) NOT NULL COMMENT '后端控制器',
  `main` VARCHAR(45) NOT NULL DEFAULT 'Items' COMMENT '主要表的模型',
  `append` VARCHAR(45) NOT NULL COMMENT '附加表数据模型（数据模型 ：不是表名）',
  `add_time` INT(10) UNSIGNED NOT NULL COMMENT '添加时间',
  `up_time` INT(10) UNSIGNED NOT NULL COMMENT '更新时间',
  `status` TINYINT(3) NOT NULL DEFAULT 1 COMMENT '记录的状态',
  PRIMARY KEY (`id`)
)ENGINE = InnoDB  DEFAULT CHARSET=utf8 COMMENT = '项目模型表（核心）';

--
-- 表的结构 `tmm_items_img` 项目图片
--
 CREATE TABLE IF NOT EXISTS `tmm_items_img` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `items_id` INT(11) UNSIGNED NOT NULL COMMENT '关联项目主表（items）主键id',
  `c_id` INT(11) UNSIGNED NOT NULL COMMENT '关联项目数据模型表（items_classliy）主键id',
  `agent_id` INT(11) UNSIGNED NOT NULL COMMENT '关联代理商用户表（agent）主键id',
  `store_id` INT(11) UNSIGNED NOT NULL COMMENT '关联商家用户表(store)主键id',
  `img` VARCHAR(100) NOT NULL COMMENT '上传图片原图',
  `litimg` VARCHAR(100) NULL DEFAULT '' COMMENT '上传图片缩略图',
  `sort` TINYINT(3) UNSIGNED NOT NULL DEFAULT 50 COMMENT '图片排序',
  `title` VARCHAR(100) NOT NULL DEFAULT '' COMMENT '图片title',
  `alt` VARCHAR(100) NOT NULL DEFAULT '' COMMENT '图片alt',
  `height` VARCHAR(5) NOT NULL DEFAULT '' COMMENT '图片高',
  `with` VARCHAR(5) NOT NULL DEFAULT '' COMMENT '图片宽',
  `add_time` INT(10) UNSIGNED NOT NULL COMMENT '创建时间',
  `up_time` INT(10) UNSIGNED NOT NULL COMMENT '更新时间',
  `status` TINYINT(3) NOT NULL DEFAULT 1 COMMENT '记录状态',
  PRIMARY KEY (`id`)
)ENGINE = InnoDB DEFAULT CHARSET=utf8 COMMENT = '项目概况图、缩略图表' DEFAULT CHARSET=utf8;

--
-- 表的结构 `tmm_fare` 项目价格
--
CREATE TABLE IF NOT EXISTS `tmm_fare` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `store_id` int(11) unsigned NOT NULL COMMENT '商家ID',
  `agent_id` int(11) unsigned NOT NULL COMMENT '代理商ID',
  `item_id` int(11) unsigned NOT NULL COMMENT '项目ID',
  `c_id` int(11) unsigned NOT NULL COMMENT '项目分类',
  `name` varchar(24) NOT NULL DEFAULT '' COMMENT '名称',
  `info` varchar(64)  NULL DEFAULT '' COMMENT '介绍',
  `number` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '吃（1）玩（1） 住（2）',
  `price` decimal(13,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '商品价格',
  `add_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  `up_time` int(10) unsigned NOT NULL COMMENT '更新时间',
  `status` int(10) NOT NULL DEFAULT '1' COMMENT '状态0禁用1启用',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT = '项目价格表';








/*
--
-- 表的结构 `eat` 吃
--
CREATE TABLE IF NOT EXISTS `eat` (
  `id` INT(11) UNSIGNED NOT NULL COMMENT '主键关联项目总表（items）主键id',
  `c_id` INT(11) UNSIGNED NOT NULL COMMENT '关联数据模型(items_classliy)主键id',
  PRIMARY KEY (`id`)
)ENGINE = InnoDB DEFAULT CHARSET=utf8 COMMENT = '项目吃的附加表';


--
-- 表的结构 `hotel` 住
--
CREATE TABLE IF NOT EXISTS `hotel` (
  `id` INT(11) UNSIGNED NOT NULL COMMENT '主键关联项目总表（items）主键id',
  `c_id` INT(11) UNSIGNED NOT NULL COMMENT '关联数据模型(items_classliy)主键id',
  PRIMARY KEY (`id`),
)ENGINE = InnoDB DEFAULT CHARSET=utf8 COMMENT = '项目住的附加表';

--
-- 表的结构 `hotel` 住
--
CREATE TABLE IF NOT EXISTS `play` (
  `id` INT(11) UNSIGNED NOT NULL COMMENT '主键关联项目总表（items）',
  `c_id` INT(11) UNSIGNED NOT NULL COMMENT '关联数据模型(items_classliy)主键id',
  PRIMARY KEY (`id`),
)ENGINE = InnoDB DEFAULT CHARSET=utf8 COMMENT = '项目玩的附加表'
*/
