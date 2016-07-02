--
-- 表的结构 `tmm_agent`
--
CREATE TABLE IF NOT EXISTS `tmm_agent` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '代理商主键id',
  `admin_id` INT(11) UNSIGNED NOT NULL COMMENT '归属管理员id',
  `phone` VARCHAR(15) NOT NULL COMMENT '手机号(用户名)唯一',
  `password` VARCHAR(64) NOT NULL COMMENT '密码',
  `merchant_count` INT(11) NOT NULL DEFAULT 0 COMMENT '商家量',
  `push` FLOAT(5,2) NOT NULL DEFAULT 0 COMMENT '代理分成比例 % 最大为100',
  `firm_name` VARCHAR(100) NOT NULL COMMENT '公司名称',
  `area_id_p` INT(11) UNSIGNED NOT NULL COMMENT '关联地名表(aera)主键id (p_id=0) 省(市)',
  `area_id_m` INT(11) UNSIGNED NOT NULL COMMENT '关联地名表(aera)主键id (p_id=t.area_id_p) 市(区)',
  `area_id_c` INT(11) UNSIGNED NOT NULL COMMENT '关联地名表(aera)主键id (p_id=t.area_id_m) 县(区)',
  `address` VARCHAR(100) NOT NULL  COMMENT '详细地址',
  `firm_tel` VARCHAR(20) NOT NULL COMMENT '公司电话',
  `firm_postcode` VARCHAR(10) NOT NULL DEFAULT '' COMMENT '公司邮政编码',
  `bl_code` VARCHAR(100) NOT NULL COMMENT '营业执照编码',
  `bl_img` VARCHAR(100) NOT NULL COMMENT '公司营业执照扫描件',
  `tax_img` VARCHAR(100) NOT NULL COMMENT '税务登记证',
  `occ_img` VARCHAR(100) NOT NULL COMMENT '组织机构代码证图片',
  `com_contacts` VARCHAR(20) NOT NULL COMMENT '公司法人',
  `com_identity` VARCHAR(20) NOT NULL COMMENT '公司法人身份证号码',
  `com_phone` VARCHAR(15) NOT NULL COMMENT '公司法人联系电话',
  `manage_name` VARCHAR(20) NOT NULL COMMENT '公司负责人',
  `manage_identity` VARCHAR(20) NOT NULL COMMENT '公司负责人身份证号码',
  `manage_phone` VARCHAR(15) NOT NULL COMMENT '公司负责人联系电话',
  `identity_hand` VARCHAR(100) NOT NULL COMMENT '负责人手执身份证照片',
  `identity_before` VARCHAR(100) NOT NULL COMMENT '公司负责人身份证扫描件正面',
  `identity_after` VARCHAR(100) NOT NULL COMMENT '公司负责人身份证扫描件反面',
  `bank_id` INT(11) UNSIGNED NOT NULL COMMENT '开户银行',
  `bank_branch` varchar(100) NOT NULL DEFAULT '' COMMENT '开户支行', 
  `bank_name` varchar(20) NOT NULL COMMENT '开户姓名', 
  `bank_code` varchar(50) NOT NULL COMMENT '开户银行账号',
  `deposit` DECIMAL(13,2) NOT NULL DEFAULT 00.00 COMMENT ' 保证金',
  `income_count` DECIMAL(13,2) NOT NULL DEFAULT 0.00 COMMENT '收益总额',
  `cash` DECIMAL(13,2) NOT NULL DEFAULT 0.00 COMMENT '已提现总额',
  `money` DECIMAL(13,2) NOT NULL DEFAULT 0.00 COMMENT '可用金额（可提现）',
  `count` INT(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '登录次数',
  `login_error` INT(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '登录错误次数 登录后清零',
  `error_count` INT(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '登录错误统计',
  `login_time` INT(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '登录时间',
  `login_ip` VARCHAR(15) NOT NULL DEFAULT '' COMMENT '登录ip',
  `last_time` INT(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '上次登录时间',
  `last_ip` VARCHAR(15) NOT NULL DEFAULT '' COMMENT '上次登录ip',
  `add_time` INT(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '注册时间',
  `up_time` INT(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `status` TINYINT(1) NOT NULL DEFAULT 1 COMMENT '状态1正常0冻结-1删除',
  PRIMARY KEY (`id`),
  UNIQUE INDEX `phone_UNIQUE` (`phone` ASC))
ENGINE = InnoDB  DEFAULT CHARSET=utf8 COMMENT = '代理商表';

--
-- 表的结构 `tmm_agent_link`
--

CREATE TABLE IF NOT EXISTS `tmm_agent_link` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `p_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '父级id 0为导航 ',
  `name` varchar(20) NOT NULL COMMENT '链接名字',
  `title` varchar(50) NOT NULL COMMENT 'title 标签值',
  `info` varchar(200) DEFAULT NULL COMMENT '说明',
  `url` varchar(100) DEFAULT '#' COMMENT 'url 地址 #表示组是没有链接',
  `params` varchar(100) NOT NULL DEFAULT 'array()' COMMENT 'params 链接参数',
  `target` varchar(20) NOT NULL DEFAULT '_parent' COMMENT '链接目标',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '50' COMMENT '链接排序',
  `add_time` int(11) unsigned NOT NULL COMMENT '创建时间',
  `up_time` int(11) unsigned NOT NULL COMMENT '更新时间',
  `show` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '是否显示导航',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '记录状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='代理商链接管理表';

--
-- 转存表中的数据 `tmm_agent_link`
--

INSERT INTO `tmm_agent_link` (`id`, `p_id`, `name`, `title`, `info`, `url`, `params`, `target`, `sort`, `add_time`, `up_time`, `show`, `status`) VALUES
(1, 0, '系统首页', '系统首页', '系统首页', '/agent', 'array()', '_parent', 10, 1437550046, 1437640734, 1, 1),
(2, 1, '账号管理', '账号管理', '账号管理', '#', 'array()', '_parent', 10, 1437552804, 1437552804, 1, 1),
(3, 2, '个人信息', '个人信息', '个人信息', '/agent/agent_admin/own', 'array()', 'agent_right', 1, 1437553080, 1437553080, 1, 1),
(4, 1, '系统配置', '系统配置', '系统配置', '#', 'array()', '_parent', 200, 1437617819, 1437617819, 1, 1),
(5, 4, '权限管理', '权限管理', '权限管理', '/srbac', 'array()', 'agent_right', 50, 1437617903, 1437617903, 1, 1),
(6, 1, '开发应用', '开发应用', '开发应用…………………………', '#', 'array()', '_parent', 250, 1437635645, 1437635853, 1, 1),
(7, 6, '后台链接管理', '后台链接管理', '后台链接管理…………。。。1111', '/agent/agent_adminlink/index', 'array()', 'agent_right', 10, 1437636543, 1437640690, 1, 1);