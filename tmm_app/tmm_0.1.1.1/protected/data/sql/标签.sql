



-- ----------------------------
-- Table structure for tmm_tags   标签总表
-- ----------------------------
DROP TABLE IF EXISTS `tmm_tags`;
CREATE TABLE `tmm_tags` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) unsigned NOT NULL COMMENT '创建人',
  `name` varchar(20) NOT NULL DEFAULT '' COMMENT '标签名',
  `weight` int(10) unsigned NOT NULL DEFAULT '1' COMMENT '权重',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '50' COMMENT '排序',
  `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '状态0禁用1启用',
  `add_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  `up_time` int(10) unsigned NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='标签总表';

-- ----------------------------
-- Table structure for tmm_tags_select   标签归属分类表  
-- ----------------------------
DROP TABLE IF EXISTS `tmm_tags_select`;
CREATE TABLE `tmm_tags_select` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) unsigned NOT NULL COMMENT '创建人',
  `tags_id` int(11) unsigned NOT NULL COMMENT '标签类型ID',
  `type_id` int(11) unsigned NOT NULL COMMENT '标签类型ID',
  `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '状态0禁用1启用',
  `add_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  `up_time` int(10) unsigned NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='标签归属分类表';

-- ----------------------------
-- Table structure for tmm_tags_element   元素(商品,角色)选择标签  
-- ----------------------------
DROP TABLE IF EXISTS `tmm_tags_element`;
CREATE TABLE `tmm_tags_element` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `select_id` int(11) unsigned NOT NULL COMMENT '选择人ID',
  `select_type` int(11) unsigned NOT NULL COMMENT '选择人类型',
  `element_id` int(11) unsigned NOT NULL COMMENT '元素ID',
  `element_type` int(11) unsigned NOT NULL COMMENT '元素类型',
  `tags_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '标签类型ID',
  `type_id` int(11) unsigned NOT NULL COMMENT '标签类型ID',
  `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '状态0禁用1启用',
  `add_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  `up_time` int(10) unsigned NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='元素(商品,角色)选择标签表';

-- ----------------------------
-- Table structure for tmm_tags_type   标签类型表（无限级分类） 
-- ----------------------------
DROP TABLE IF EXISTS `tmm_tags_type`;
CREATE TABLE `tmm_tags_type` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `p_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '父ID 0',
  `name` varchar(20) NOT NULL COMMENT '标签名',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '50' COMMENT '排序',
  `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '状态0禁用1启用',
  `add_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  `up_time` int(10) unsigned NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='标签类型表';


