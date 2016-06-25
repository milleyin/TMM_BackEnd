
--
-- 表的结构 `tmm_account`
--

CREATE TABLE IF NOT EXISTS `tmm_account` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `account_type` int(11) unsigned NOT NULL COMMENT '角色类型',
  `account_id` int(11) unsigned NOT NULL COMMENT '角色账号',
  `count` decimal(13,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '资金统计总额',
  `total` decimal(13,2) NOT NULL DEFAULT '0.00' COMMENT '资金总额',
  `money` decimal(13,2) NOT NULL DEFAULT '0.00' COMMENT '可提现金额',
  `no_money` decimal(13,2) NOT NULL DEFAULT '0.00' COMMENT '冻结金额',
  `cash_count` decimal(13,2) NOT NULL DEFAULT '0.00' COMMENT '提现总额',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `up_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '记录状态0禁用1正常-1删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='角色钱包' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `tmm_bills`
--

CREATE TABLE IF NOT EXISTS `tmm_bills` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `order_id` int(11) unsigned NOT NULL COMMENT '归属订单',
  `order_no` varchar(128) NOT NULL COMMENT '订单号',
  `order_items_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '归属订单项目详细',
  `group_no` varchar(128) NOT NULL DEFAULT '' COMMENT '结伴 id 号',
  `organizer_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '归属组织者表',
  `order_organizer_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '归属组织者订单详情表',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '归属用户',
  `order_shops_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '归属订单商品表（复制表）',
  `shops_name` varchar(128) NOT NULL DEFAULT '' COMMENT '商品名称',
  `agent_id` int(11) unsigned NOT NULL COMMENT '项目归属代理商',
  `store_id` int(11) unsigned NOT NULL COMMENT '归属商家',
  `manager_id` int(11) unsigned NOT NULL COMMENT '商家管理者',
  `shops_id` int(11) unsigned NOT NULL COMMENT '商品来源',
  `shops_c_id` int(11) unsigned NOT NULL COMMENT '归属商品分类',
  `shops_c_name` varchar(20) NOT NULL COMMENT '归属商品分类名称',
  `items_id` int(11) unsigned NOT NULL COMMENT '项目来源',
  `items_c_id` int(11) unsigned NOT NULL COMMENT '归属项目分类',
  `items_c_name` varchar(20) NOT NULL COMMENT '归属项目分类名称',
  `items_name` varchar(100) NOT NULL COMMENT '项目名称',
  `group_price` decimal(13,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '服务费',
  `user_order_count` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '用户下单数量',
  `user_pay_count` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '用户支付数量',
  `user_submit_count` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '用户确认出游数量',
  `user_price` decimal(13,2) NOT NULL DEFAULT '0.00' COMMENT '实际服务费用/人',
  `user_go_count` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '用户出游人数量',
  `user_price_count` decimal(13,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '下单总额',
  `items_push` float(5,2) NOT NULL DEFAULT '0.00' COMMENT '平台对项目的抽成 %(生效值)',
  `items_push_orgainzer` float(5,2) NOT NULL DEFAULT '0.00' COMMENT '组织者对项目的抽成 %(生效值)',
  `items_push_store` float(5,2) NOT NULL DEFAULT '0.00' COMMENT '商家对项目的抽成 %(生效值)',
  `items_push_agent` float(5,2) NOT NULL DEFAULT '0.00' COMMENT '代理商平台对项目的抽成 %(生效值)',
  `items_money` decimal(13,2) NOT NULL DEFAULT '0.00' COMMENT '平台对项目的抽成(计算后)',
  `items_money_orgainzer` decimal(13,2) NOT NULL DEFAULT '0.00' COMMENT '组织者对项目的抽成(计算后)',
  `items_money_store` decimal(13,2) NOT NULL DEFAULT '0.00' COMMENT '商家对项目的抽成(计算后)',
  `items_money_agent` decimal(13,2) NOT NULL DEFAULT '0.00' COMMENT '代理商平台对项目的抽成(计算后)',
  `price` decimal(13,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '项目价格',
  `number` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '购买数量',
  `total` decimal(13,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '总计总额',
  `cash_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '提现记录',
  `cash_status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '0没有 1已申请 2 已提现 ',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `up_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `son_status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '0没有添加组织着细账 1已添加',
  `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '记录状态0禁用1正常-1删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='项目账单详情' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `tmm_cash`
--

CREATE TABLE IF NOT EXISTS `tmm_cash` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `cash_type` int(11) unsigned NOT NULL COMMENT '元素类型',
  `cash_id` int(11) unsigned NOT NULL COMMENT '申请人',
  `admin_id_first` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '管理员ID(初)',
  `remark_first` varchar(100) DEFAULT '' COMMENT '备注(初)',
  `first_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '(初)时间',
  `admin_id_double` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '管理员ID(复)',
  `remark_double` varchar(100) DEFAULT '' COMMENT '备注(复)',
  `double_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '(复)时间',
  `admin_id_submit` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '管理员ID(确认)',
  `remark_submit` varchar(100) DEFAULT '' COMMENT '备注(确认)',
  `submit_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '(确认)时间',
  `money` decimal(13,2) NOT NULL DEFAULT '0.00' COMMENT '提现金额',
  `price` decimal(13,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '提现金额',
  `fee_price` decimal(13,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '提现费用',
  `fact_price` decimal(13,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '实际到账金额',
  `cash_name` varchar(128) NOT NULL DEFAULT '' COMMENT '提现姓名',
  `bank_code` varchar(100) DEFAULT '' COMMENT '提现账号',
  `bank_info` varchar(100) DEFAULT '' COMMENT '提现账号分行',
  `cash_status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '付款状态 0待付款1=已成功2=申请3=失败',
  `audit_status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '审核状态0待审核1=申成2=申失3=初成4=初败5=确成6=确败',
  `pay_type` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '提现类型1=支付宝2=银行...',
  `pay_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '到账时间',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `up_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '记录状态0禁用1正常-1删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='提现申请记录' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `tmm_order`
--

CREATE TABLE IF NOT EXISTS `tmm_order` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `order_organizer_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '归属组织者总订单表',
  `son_order_count` decimal(13,2) NOT NULL DEFAULT '0.00' COMMENT '子订单总费用',
  `order_no` varchar(128) NOT NULL COMMENT '订单号',
  `order_type` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '订单类型 0未知 1 点（多个点组合） 2 线 3 结伴游',
  `user_id` int(11) unsigned NOT NULL COMMENT ' 用户ID',
  `order_price` decimal(13,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '订单总价',
  `pay_price` decimal(13,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '支付回调总价',
  `price` decimal(13,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '实际支付总价',
  `trade_no` varchar(255) DEFAULT '' COMMENT '支付回调',
  `trade_name` varchar(128) DEFAULT '' COMMENT '支付账号',
  `service_price` decimal(13,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '第三方支付服务费',
  `service_fee` float(5,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '第三方支付服务费率%',
  `pay_type` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '支付类型1=支付宝2=微信...',
  `user_go_count` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户出游人数量',
  `user_price` decimal(13,2) NOT NULL DEFAULT '0.00' COMMENT '服务费用/人',
  `user_price_fact` decimal(13,2) NOT NULL DEFAULT '0.00' COMMENT '实际服务费用/人',
  `pay_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '支付成功时间',
  `status_go` tinyint(3) NOT NULL DEFAULT '0' COMMENT '记录状态0没选择 1去 -1不去',
  `go_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '出游时间',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `up_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `pay_status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '支付状态1=已支付0=未支付2=已过期',
  `order_status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '订单状态1=已支付0=未支付2=已退款....',
  `centre_status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态',
  `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '记录状态0禁用1正常-1删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='订单主表 ' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `tmm_order_items`
--

CREATE TABLE IF NOT EXISTS `tmm_order_items` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `order_items_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '归属活动项目id 默认为零表示 （可能是自助游 或者是活动总定订单）',
  `organizer_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '归属组织者表',
  `order_organizer_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '归属组织者订单详情表',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '归属用户',
  `order_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '归属订单表',
  `order_shops_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '归属订单商品表（复制表）',
  `store_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '项目归属商家',
  `manager_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商家管理者',
  `agent_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '项目归属代理商',
  `shops_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商品来源',
  `shops_name` varchar(128) NOT NULL DEFAULT '' COMMENT '商品名称',
  `shops_c_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '归属商品分类',
  `shops_c_name` varchar(20) NOT NULL DEFAULT '0' COMMENT '归属商品分类名称',
  `items_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '项目来源',
  `items_c_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '归属项目分类',
  `items_c_name` varchar(20) NOT NULL DEFAULT '0' COMMENT '归属项目分类名称',
  `items_name` varchar(128) NOT NULL DEFAULT '0' COMMENT '项目名称',
  `items_address` varchar(200) NOT NULL DEFAULT '0' COMMENT '项目地址',
  `items_push` float(5,2) NOT NULL DEFAULT '0.00' COMMENT '平台对项目的抽成 %(生效值)',
  `items_push_orgainzer` float(5,2) NOT NULL DEFAULT '0.00' COMMENT '组织者对项目的抽成 %(生效值)',
  `items_push_store` float(5,2) NOT NULL DEFAULT '0.00' COMMENT '商家对项目的抽成 %(生效值)',
  `items_push_agent` float(5,2) NOT NULL DEFAULT '0.00' COMMENT '代理商平台对项目的抽成 %(生效值)',
  `items_map` varchar(100) DEFAULT '' COMMENT '地图',
  `items_phone` varchar(20) DEFAULT '' COMMENT '联系电话',
  `items_weixin` varchar(20) DEFAULT '' COMMENT '微信号',
  `items_content` text NOT NULL COMMENT '项目详细内容',
  `items_img` varchar(100) DEFAULT '' COMMENT '随机一张图片',
  `items_start_work` time NOT NULL DEFAULT '00:00:00' COMMENT '工作开始时间',
  `items_end_work` time NOT NULL DEFAULT '23:59:59' COMMENT '工作结束时间',
  `items_up_time` int(10) unsigned NOT NULL COMMENT '最后一次更新时间',
  `items_pub_time` int(10) unsigned NOT NULL COMMENT '项目审核通过时间',
  `shops_sort` tinyint(3) unsigned NOT NULL DEFAULT '50' COMMENT '点排序',
  `shops_day_sort` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '区分天单位(半天)',
  `shops_half_sort` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '线 结伴游 排序',
  `shops_dot_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '当前项目关联点id',
  `shops_thrand_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '当前项目关联线id',
  `shops_info` text NOT NULL COMMENT '项目简介',
  `shops_up_time` int(10) unsigned NOT NULL COMMENT '最后一次更新时间',
  `shops_pub_time` int(10) NOT NULL COMMENT '商品审核通过时间',
  `total` decimal(13,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '总计总额',
  `employ_time` int(10) unsigned NOT NULL COMMENT '消费时间',
  `barcode` varchar(100) NOT NULL DEFAULT '' COMMENT '消费码',
  `is_shops` tinyint(3) NOT NULL DEFAULT '0' COMMENT '商家同意是否接单 0没选择 1同意 -1 不同意',
  `is_barcode` tinyint(3) NOT NULL DEFAULT '0' COMMENT '-1 无效 0 可消费 1已消费',
  `scan_barcode` varchar(100) NOT NULL DEFAULT '' COMMENT '扫码',
  `add_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  `up_time` int(10) unsigned NOT NULL COMMENT '更新时间',
  `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '记录状态1正常0禁用-1删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='订单项目详细情况' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `tmm_order_items_fare`
--

CREATE TABLE IF NOT EXISTS `tmm_order_items_fare` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `order_items_fare_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '归属活动项目价格id 默认为零表示 （可能是自助游 或者是活动总定订单）',
  `order_items_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '归属订单项目详细',
  `organizer_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '归属组织者',
  `order_organizer_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '归属组织者订单详情表',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '归属用户',
  `order_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '归属订单表',
  `order_shops_id` int(11) unsigned NOT NULL COMMENT '归属订单商品表（复制表）',
  `store_id` int(11) unsigned NOT NULL COMMENT '归属商家',
  `manager_id` int(11) unsigned NOT NULL COMMENT '商家管理者',
  `agent_id` int(11) unsigned NOT NULL COMMENT '项目归属代理商',
  `shops_id` int(11) unsigned NOT NULL COMMENT '商品来源',
  `shops_c_id` int(11) unsigned NOT NULL COMMENT '归属商品分类',
  `items_id` int(11) unsigned NOT NULL COMMENT '项目来源',
  `items_c_id` int(11) unsigned NOT NULL COMMENT '归属项目分类',
  `fare_id` int(11) unsigned NOT NULL COMMENT '归属价格fare表',
  `fare_name` varchar(24) NOT NULL DEFAULT '' COMMENT '名称',
  `fare_info` varchar(64) DEFAULT '' COMMENT '类型',
  `fare_number` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '平方',
  `fare_price` decimal(13,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '商品价格',
  `fare_up_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  `price` decimal(13,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '付款价格',
  `number` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '购买数量',
  `total` decimal(13,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '总计总额',
  `add_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  `up_time` int(10) unsigned NOT NULL COMMENT '更新时间',
  `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '记录状态1正常0禁用-1删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='订单项目详细购买价格' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `tmm_order_organizer`
--

CREATE TABLE IF NOT EXISTS `tmm_order_organizer` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `group_no` varchar(128) NOT NULL DEFAULT '' COMMENT '结伴 id 号',
  `organizer_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '组织者id',
  `group_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '结伴游商品id',
  `shops_c_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '关联商品数据模型表（shops_classliy）主键id',
  `shops_c_name` varchar(20) NOT NULL DEFAULT '' COMMENT '归属商品分类名称',
  `shops_name` varchar(128) NOT NULL DEFAULT '' COMMENT '商品名称',
  `shops_list_img` varchar(128) NOT NULL DEFAULT '' COMMENT '列表头图',
  `shops_page_img` varchar(128) NOT NULL DEFAULT '' COMMENT '详情头图',
  `shops_list_info` varchar(128) NOT NULL DEFAULT '' COMMENT '列表简介',
  `shops_page_info` varchar(128) NOT NULL DEFAULT '' COMMENT '详情简介',
  `shops_pub_time` int(10) NOT NULL DEFAULT '0' COMMENT '发布时间',
  `shops_up_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `shops_add_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `group_price` decimal(13,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '服务费',
  `group_remark` text NOT NULL COMMENT '结伴游备注',
  `group_start_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '报名开始时间',
  `group_end_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '报名结束时间',
  `group_group_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '出游时间',
  `group_group` tinyint(3) NOT NULL DEFAULT '0' COMMENT '团状态',
  `user_order_count` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户下单数量',
  `user_pay_count` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户支付数量',
  `user_submit_count` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户确认出游数量',
  `user_price` decimal(13,2) NOT NULL DEFAULT '0.00' COMMENT '实际服务费用',
  `user_go_count` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户出游人数量',
  `user_price_count` decimal(13,2) NOT NULL DEFAULT '0.00' COMMENT '下单总额',
  `total` decimal(13,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '总计总额',
  `is_organizer` tinyint(3) NOT NULL DEFAULT '0' COMMENT '组织着确认出游 -1 不去 0 没选择 1去',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间(下单时间)',
  `up_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '状态0禁用1启用-1删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='组织者订单详情表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `tmm_order_retinue`
--

CREATE TABLE IF NOT EXISTS `tmm_order_retinue` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `order_id` int(11) unsigned NOT NULL COMMENT '订单ID',
  `order_no` varchar(128) NOT NULL COMMENT '订单号',
  `son_order_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '子订单ID',
  `son_order_no` varchar(128) NOT NULL DEFAULT '0' COMMENT '子订单号',
  `user_id` int(11) unsigned NOT NULL COMMENT '用户ID',
  `retinue_id` int(11) unsigned NOT NULL COMMENT '来源id',
  `retinue_name` varchar(20) NOT NULL COMMENT '被保人姓名',
  `retinue_gender` tinyint(3) unsigned NOT NULL COMMENT '被保人性别(1、男 2、女)',
  `retinue_identity` varchar(18) NOT NULL COMMENT '被保人身份证号',
  `retinue_phone` varchar(11) NOT NULL COMMENT '被保人手机号码',
  `insure_name` varchar(20) DEFAULT '' COMMENT '投保人姓名',
  `insure_gender` tinyint(3) unsigned DEFAULT '0' COMMENT '投保人性别(1、男 2、女)',
  `insure_identity` varchar(18) DEFAULT '' COMMENT '投保人身份证号',
  `insure_phone` varchar(11) DEFAULT '' COMMENT '投保人手机号码',
  `insure_age` int(3) unsigned DEFAULT '0' COMMENT '投保年龄',
  `is_main` tinyint(3) NOT NULL DEFAULT '0' COMMENT '是否是主要的联系人（0 不是1 是）',
  `insure_no` varchar(128) DEFAULT '' COMMENT '保单号',
  `insure_verify` varchar(128) DEFAULT '' COMMENT '验真码',
  `start_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '开始时间',
  `end_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '结束时间',
  `insure_price` decimal(13,2) unsigned DEFAULT '0.00' COMMENT '保险金额',
  `fact_price` decimal(13,2) unsigned DEFAULT '0.00' COMMENT '实际金额',
  `insure_number` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '保险份数',
  `beneficiary` varchar(15) NOT NULL DEFAULT '法定' COMMENT '收益人',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `up_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '记录状态0禁用1正常-1删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='订单随行人员表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `tmm_order_shops`
--

CREATE TABLE IF NOT EXISTS `tmm_order_shops` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `order_id` int(11) unsigned NOT NULL COMMENT '归属订单id',
  `user_id` int(10) unsigned DEFAULT NULL COMMENT '用户ID',
  `shops_id` int(11) unsigned NOT NULL COMMENT '商品的来源关联商品（shops）主键id',
  `shops_c_id` int(11) unsigned NOT NULL COMMENT '关联商品数据模型表（shops_classliy）主键id',
  `shops_c_name` varchar(20) NOT NULL DEFAULT '' COMMENT '归属商品分类名称',
  `shops_agent_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '代理商ID',
  `shops_name` varchar(128) NOT NULL DEFAULT '' COMMENT '商品名称',
  `shops_list_img` varchar(128) NOT NULL DEFAULT '' COMMENT '列表头图',
  `shops_page_img` varchar(128) NOT NULL DEFAULT '' COMMENT '详情头图',
  `shops_list_info` varchar(128) NOT NULL DEFAULT '' COMMENT '列表简介',
  `shops_page_info` varchar(128) NOT NULL DEFAULT '' COMMENT '详情简介',
  `shops_pub_time` int(10) NOT NULL COMMENT '通过时间',
  `shops_add_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `shops_up_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `group_organizer_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '组织者id',
  `group_price` decimal(13,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '服务费',
  `group_remark` text NOT NULL COMMENT '结伴游备注',
  `group_start_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '报名开始时间',
  `group_end_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '报名结束时间',
  `group_time` int(10) unsigned NOT NULL COMMENT '出游时间',
  `group` tinyint(3) NOT NULL DEFAULT '0' COMMENT '团状态',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `up_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '状态0禁用1启用',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='复制商品表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `tmm_rebate`
--

CREATE TABLE IF NOT EXISTS `tmm_rebate` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `order_id` int(11) unsigned NOT NULL COMMENT '订单ID',
  `order_no` varchar(128) NOT NULL COMMENT '订单号',
  `admin_id_first` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '管理员ID(初)',
  `remark_first` varchar(100) DEFAULT '' COMMENT '备注(初)',
  `first_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '(初)时间',
  `admin_id_double` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '管理员ID(复)',
  `remark_double` varchar(100) DEFAULT '' COMMENT '备注(复)',
  `double_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '(复)时间',
  `admin_id_submit` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '管理员ID(确认)',
  `remark_submit` varchar(100) DEFAULT '' COMMENT '备注(确认)',
  `submit_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '(确认)时间',
  `user_id` int(11) unsigned NOT NULL COMMENT ' 用户ID',
  `old_price` decimal(13,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '退款前价格',
  `out_price` decimal(13,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '需退款价格',
  `fact_price` decimal(13,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '实际退款价格',
  `user_name` varchar(128) NOT NULL DEFAULT '' COMMENT '退款姓名',
  `bank_code` varchar(100) DEFAULT '' COMMENT '退款账号',
  `refund_id` int(11) unsigned NOT NULL COMMENT '退款原因ID',
  `info` varchar(100) DEFAULT '' COMMENT '退款原因',
  `bank_info` varchar(100) DEFAULT '' COMMENT '账号分行',
  `error_info` varchar(100) DEFAULT '' COMMENT '退款失败原因',
  `rebate_status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '退款状态 0待付款1=已成功2=申请3=失败',
  `audit_status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '审核状态0待审核1=申成2=申失3=初成4=初败5=确成6=确败',
  `pay_type` tinyint(3) NOT NULL DEFAULT '1' COMMENT '退款类型1=支付宝2=微信...',
  `pay_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '退款成功时间',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `up_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '记录状态0禁用1正常-1删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='退款订单' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `tmm_scancode_log`
--

CREATE TABLE IF NOT EXISTS `tmm_scancode_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `store_manage_id` int(11) unsigned NOT NULL COMMENT '扫描商家',
  `code` varchar(255) DEFAULT '' COMMENT '扫描码',
  `is_error` tinyint(3) NOT NULL DEFAULT '1' COMMENT '是否扫码错误1=正2错',
  `error_right` tinyint(3) NOT NULL DEFAULT '0' COMMENT '扫码错误是否清除',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '记录状态0禁用1正常-1删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='扫描码日志表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `tmm_scan_code`
--

CREATE TABLE IF NOT EXISTS `tmm_scan_code` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `order_id` int(11) unsigned NOT NULL COMMENT '归属订单',
  `order_no` varchar(128) NOT NULL COMMENT '订单号',
  `user_id` int(11) unsigned NOT NULL COMMENT ' 用户ID',
  `is_organizer` tinyint(3) NOT NULL DEFAULT '0' COMMENT '是否有组织者',
  `store_id` int(11) unsigned NOT NULL COMMENT '归属商家',
  `agent_id` int(11) unsigned NOT NULL COMMENT '项目归属代理商',
  `store_manage_id` int(11) unsigned NOT NULL COMMENT '扫描商家',
  `order_items_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '详目ID',
  `code` varchar(255) DEFAULT '' COMMENT '扫描码',
  `error_num` tinyint(3) NOT NULL DEFAULT '0' COMMENT '扫码错误次数',
  `code_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '扫码成功时间',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `up_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `code_status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '扫描码状态1=已使用0=未使用2=已过期...',
  `push_status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '预留推送消息',
  `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '记录状态0禁用1正常-1删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='扫描码' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `tmm_son_order`
--

CREATE TABLE IF NOT EXISTS `tmm_son_order` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `son_order_no` varchar(128) NOT NULL DEFAULT '' COMMENT '子订单号',
  `order_id` int(11) unsigned NOT NULL COMMENT '归属订单',
  `order_no` varchar(128) NOT NULL COMMENT '订单号',
  `type_id` tinyint(3) unsigned DEFAULT '1' COMMENT ' 子订单类型 1 保险',
  `user_id` int(11) unsigned NOT NULL COMMENT ' 用户ID',
  `order_price` decimal(13,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '子订单总价',
  `pay_price` decimal(13,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '子支付回调总价',
  `price` decimal(13,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '子实际支付总价',
  `trade_no` varchar(255) DEFAULT '' COMMENT '子支付回调',
  `trade_name` varchar(128) NOT NULL DEFAULT '' COMMENT '子支付账号',
  `service_price` decimal(13,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '第三方支付服务费',
  `service_fee` float(5,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '第三方支付服务费率%',
  `pay_type` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '支付类型1=支付宝2=银行...',
  `pay_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '支付成功时间',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `up_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `pay_status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '支付状态1=已支付0=未支付2=已过期',
  `order_status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '订单状态1=已支付0=未支付2=已退款....',
  `centre_status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态',
  `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '记录状态0禁用1正常-1删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='子订单主表 ' AUTO_INCREMENT=1 ;