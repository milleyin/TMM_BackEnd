<?php

/**
 * This is the model class for table "{{account_log}}".
 *
 * The followings are the available columns in table '{{account_log}}':
 * @property string $id
 * @property string $account_no
 * @property string $account_id
 * @property integer $account_type
 * @property string $to_account_id
 * @property integer $to_account_type
 * @property string $manage_account_id
 * @property integer $manage_account_type
 * @property integer $funds_type
 * @property string $funds_type_name
 * @property integer $money_type
 * @property string $use_money
 * @property string $count
 * @property string $total
 * @property string $money
 * @property string $no_money
 * @property string $cash_count
 * @property string $pay_count
 * @property string $refund_count
 * @property string $consume_count
 * @property string $not_consume_count
 * @property string $after_count
 * @property string $after_total
 * @property string $after_money
 * @property string $after_no_money
 * @property string $after_cash_count
 * @property string $after_pay_count
 * @property string $after_refund_count
 * @property string $after_consume_count
 * @property string $after_not_consume_count
 * @property string $info_id
 * @property integer $info_type
 * @property string $info
 * @property string $name
 * @property string $ip
 * @property string $address
 * @property string $add_time
 * @property string $up_time
 * @property string $up_count
 * @property integer $log_status
 * @property integer $centre_status
 * @property integer $status
 */
class AccountLog extends CActiveRecord
{
	/**
	 * 虚构角色的ID
	 * @var unknown
	 */
	const fiction_role_id=0;
	/*****************************************被操作的角色账户类型***************************************************************/
	/**
	 * 代理商
	 * @var integer
	 */
	const account_agent=1;
	/**
	 * 商家
	 * @var integer
	 */
	const account_store=2;
	/**
	 * 外部
	 * @var integer
	 */
	const account_other=3;
	/**
	 *	用户(组织者)
	 * @var integer
	 */
	const account_user=4;
	/**
	 *	tmm 平台
	 * @var integer
	 */
	const account_tmm=5;
	/**
	 * system系统账户
	 * @var integer
	 */
	const account_system=6;
	/**
	 * 解释字段 account_type 的含义
	 * @var array
	 */
	public static $_account_type=array(
			self::account_agent=>'代理商',
			self::account_store=>'商家',
			self::account_other=>'外部',
			self::account_user=>'用户',
			self::account_tmm=>'平台',
			self::account_system=>'系统'
	);
	/**
	 * 解释字段 account_type 的关联关系
	 * @var unknown
	 */
	public static $__account_type=array(
			self::account_agent=>'AccountLog_Agent',
			self::account_store=>'AccountLog_StoreUser',
			self::account_other=>'other',
			self::account_user=>'AccountLog_User',
			self::account_tmm=>'tmm',
			self::account_system=>'system',
	);
	/**
	 * 其他(非系统)ID
	 * @var integer
	 */
	const account_id_other=self::fiction_role_id;
	/**
	 * 平台ID
	 * @var integer
	 */
	const account_id_tmm=self::fiction_role_id;
	/**
	 * 系统ID
	 * @var integer
	 */
	const account_id_system=self::fiction_role_id;
	
	/***************************************** 钱将给谁的账户类型***************************************************************/
	/**
	 * 代理商
	 * @var integer
	 */
	const to_account_agent=self::account_agent;
	/**
	 * 商家
	 * @var integer
	 */
	const to_account_store=self::account_store;
	/**
	 *	外部
	 * @var integer
	 */
	const to_account_other=self::account_other;
	/**
	 *	用户(组织者)
	 * @var integer
	 */
	const to_account_user=self::account_user;
	/**
	 *	tmm 平台
	 * @var integer
	 */
	const to_account_tmm=self::account_tmm;
	/**
	 * system系统账户
	 * @var integer
	 */
	const to_account_system=self::account_system;
	/**
	 * 解释字段 to_account_type 的含义
	 * @var array
	 */
	public static $_to_account_type=array(
			self::account_agent=>'代理商',
			self::account_store=>'商家',
			self::account_other=>'外部',
			self::account_user=>'用户',
			self::account_tmm=>'平台',
			self::account_system=>'系统'
	);
	/**
	 * 解释字段 to_account_type 的关联关系
	 * @var unknown
	 */
	public static $__to_account_type=array(
			self::account_agent=>'AccountLog_Agent_TO',
			self::account_store=>'AccountLog_StoreUser_TO',
			self::account_other=>'other',
			self::account_user=>'AccountLog_User_TO',
			self::account_tmm=>'tmm',
			self::account_system=>'system',
	);
	/**
	 * 外部ID
	 * @var integer
	 */
	const to_account_id_other=self::account_id_other;
	/**
	 * 平台ID
	 * @var integer
	*/
	const to_account_id_tmm=self::account_id_tmm;
	/**
	 * 系统ID
	 * @var integer
	 */
	const to_account_id_system=self::account_id_system;
	
	/***************************************** 操作角色类型***************************************************************/
	/**
	 * 代理商
	 * @var integer
	 */
	const manage_account_agent=self::account_agent;
	/**
	 * 商家
	 * @var integer
	 */
	const manage_account_store=self::account_store;
	/**
	 * 外部
	 * @var integer
	 */
	const manage_account_other=self::account_other;
	/**
	 *	用户(组织者)
	 * @var integer
	 */
	const manage_account_user=self::account_user;
	/**
	 *	tmm 平台
	 * @var integer
	 */
	const manage_account_tmm=self::account_tmm;
	/**
	 * system系统账户
	 * @var integer
	 */
	const manage_account_system=self::account_system;
	/**
	 * admin 管理员
	 * @var integer
	 */
	const manage_account_admin=0;
	/**
	 * 解释字段 manage_account_type 的含义
	 * @var array
	 */
	public static $_manage_account_type=array(
			self::manage_account_admin=>'管理员',
			self::account_agent=>'代理商',
			self::account_store=>'商家',
			self::account_other=>'外部',
			self::account_user=>'用户',
			self::account_tmm=>'平台',
			self::account_system=>'系统'
	);
	/**
	 * 解释字段 manage_account_type 的关联关系
	 * @var unknown
	 */
	public static $__manage_account_type=array(
			self::manage_account_admin=>'AccountLog_Admin_Manage',
			self::account_agent=>'AccountLog_Agent_Manage',
			self::account_store=>'AccountLog_StoreUser_Manage',
			self::account_other=>'other',
			self::account_user=>'AccountLog_User_Manage',
			self::account_tmm=>'tmm',
			self::account_system=>'system',
	);
	/**
	 * 外部ID
	 * @var integer
	 */
	const manage_account_id_other=self::account_id_other;
	/**
	 * 平台ID
	 * @var integer
	*/
	const manage_account_id_tmm=self::account_id_tmm;
	/**
	 * 系统ID
	 * @var integer
	 */
	const manage_account_id_system=self::account_id_system;
		
	/***************************************** 钱的类型***************************************************************/	
	/**
	 * 钱的类型 RMB
	 * @var integer
	 */
	const money_type_rmb=0;
	/**
	 * 解释字段 money_type 的含义
	 * @var array
	 */
	public static $_money_type=array('RMB');
	
	/**************************************** 资金类型 **************************************************************/
	/********** 资金类型 (进账) **********/
	/**
	 * 资金类型  在线充值
	 * @var integer
	 */
	 const funds_type_entry_recharge=11;
	 /**
	  * 资金类型  提现失败
	  * @var integer
	  */
	 const funds_type_entry_cash_fail=12;
	 /**
	  * 资金类型  订单收益
	  * @var integer
	  */
	 const funds_type_entry_order_income=13;
	 /**
	  * 资金类型  觅趣收益
	  * @var integer
	  */
	 const funds_type_entry_actives_tour_income=14;
	 /**
	  * 资金类型  订单退款
	  * @var integer
	  */
	 const funds_type_entry_order_refund=15;
	 /**
	  * 资金类型  服务费用退款
	  * @var integer
	  */
	 const funds_type_entry_refund_actives_tour_charge=16;
	 /**
	  * 资金类型  订单补偿
	  * @var integer
	  */
	 const funds_type_entry_order_compensate=17;
	 /**
	  * 资金类型  服务费用补偿
	  * @var integer
	  */
	 const funds_type_entry_actives_tour_compensate=18;
	 
	 /****************************************** 冻结 ********************************************/
	 /**
	  * 资金类型  提现申请（冻结） 只有log_status = log_status_success 处理成功的记录 作为记录不进不出 否则 资金类型 (出账)
	  * @var integer
	  */
	 const funds_type_pending_cash_frozen=21; 
	 	 
	 /********** 资金类型 (出账) **********/
	 /**
	  * 资金类型  提现成功
	  * @var integer
	  */
	 const funds_type_deduct_cash_success=31;
	 /**
	  * 资金类型  支付订单 
	  * @var integer
	  */
	 const funds_type_deduct_order_pay=32;
	
	 /********** 资金类型 (记录 非进非出) **********/
	 /**
	  * 资金类型  在线支付
	  * @var integer
	  */
	 const funds_type_record_alipay=41;
	 /**
	  * 资金类型  退款申请
	  * @var integer
	  */
	 const funds_type_record_order_refund_frozen=42;
	 /**
	  * 资金类型  退款失败
	  * @var integer
	  */
	 const funds_type_record_order_refund_fail=43;
	 /**
	  * 资金类型  扫描消费
	  * @var integer
	  */
	 const funds_type_record_order_items_consume=44;
	 /**
	  * 资金类型  过期消费
	  * @var integer
	  */
	 const funds_type_record_order_items_past=45;
	 /**
	  * 资金类型  服务费用
	  * @var integer
	  */
	 const funds_type_record_actives_tour_charge=46; 
	 /**
	  * 资金类型  订单违约
	  * @var integer
	  */
	 const funds_type_record_order_penalty=47;
	 /**
	  * 资金类型  服务费用违约
	  * @var integer
	  */
	 const funds_type_record_actives_tour_penalty=48;
	 
	 /**
	  * 解释字段 money_type 的含义
	  * @var array
	  */
	 public static $_funds_type=array(
	 	self::funds_type_entry_recharge=>'在线充值',								//入
	 	self::funds_type_entry_cash_fail=>'提现失败',								//入
	 	self::funds_type_entry_order_income=>'订单收益',						//入
	 	self::funds_type_entry_actives_tour_income=>'觅趣收益',			//入
	 	//1.2.6
 		self::funds_type_entry_order_refund=>'订单退款',									//入 用户
	 	self::funds_type_entry_refund_actives_tour_charge=>'服务费用退款',		//入 用户	 	
 		self::funds_type_entry_order_compensate=>'订单补偿',							//入 角色 除（退款用户）
 		self::funds_type_entry_actives_tour_compensate=>'服务费用补偿',			//入	代理商
	 		
	 	self::funds_type_pending_cash_frozen=>'提现申请',						// 只有成功的申请 计 记录 否则 计 出
 	 		
	 	self::funds_type_deduct_cash_success=>'提现成功',						//出
	 	self::funds_type_deduct_order_pay=>'支付订单',							//出
	 			 		
	 	self::funds_type_record_alipay=>'在线支付', 									//记录
	 	//1.2.6
	 	self::funds_type_record_order_refund_frozen=>'退款申请',			//记录  有成功 失败
	 	self::funds_type_record_order_refund_fail=>'退款失败',				//记录 	
	 	
	 	self::funds_type_record_order_items_consume=>'扫描消费',		//记录
	 	self::funds_type_record_order_items_past=>'过期消费',				//记录
	 	self::funds_type_record_actives_tour_charge=>'服务费用',			//记录
	 	//1.2.6
	 	self::funds_type_record_order_penalty=>'订单违约',						//记录 用户
	 	self::funds_type_record_actives_tour_penalty=>'服务费用违约',	//记录 用户 	
	 );
	 /**
	  * 流水的单号前缀
	  * @var unknown
	  */
	 public static $__funds_type=array(
	 		self::funds_type_entry_recharge=>'ER',												//入 在线充值
	 		self::funds_type_entry_cash_fail=>'EC',												//入 提现失败
	 		self::funds_type_entry_order_income=>'EOI',									//入 订单收益
	 		self::funds_type_entry_actives_tour_income=>'ETI',							//入 觅趣收益
	 		
	 		self::funds_type_entry_order_refund=>'EOR',									//入 订单退款
	 		self::funds_type_entry_refund_actives_tour_charge=>'EATC',			//入 服务费用退款
	 		self::funds_type_entry_order_compensate=>'EOCP',						//入 订单补偿
	 		self::funds_type_entry_actives_tour_compensate=>'EATCP',			//入 服务费用补偿
	 		
	 		self::funds_type_pending_cash_frozen=>'PCF',					// 只有成功的 => 作： 记录 ,否则 => 作： 出 提现申请
	 		
	 		self::funds_type_deduct_cash_success=>'DCS',				//出 提现成功
	 		self::funds_type_deduct_order_pay=>'DOC',					//出 支付订单 钱包
	 		
	 		self::funds_type_record_alipay=>'RA',								//记录 在线支付
	 		self::funds_type_record_order_refund_frozen=>'ROR',	//记录 申请退款
	 		self::funds_type_record_order_refund_fail=>'ROF',			//记录 退款失败
	 		self::funds_type_record_order_items_consume=>'ROIC',	//记录 扫描消费
	 		self::funds_type_record_order_items_past=>'ROIP',			//记录 过期消费
	 		self::funds_type_record_actives_tour_charge=>'RATC',	//记录 服务费用
	 		//1.2.6
	 		self::funds_type_record_order_penalty=>'ROP',				//记录 订单违约
	 		self::funds_type_record_actives_tour_penalty=>'RATP',	//记录 服务费用违约
	 );
	 /***************************************** 核心状态 ***************************************************************/
	
	 /**
	  * 核心状态 收益金额
	  * @var integer
	  */
	 const centre_status_entry=1;
	 /**
	  * 核心状态 冻结金额  // 只有成功的 => 作： 记录金额 ,否则 => 作： 出账金额 
	  * @var integer
	  */
	 const centre_status_pending=2;
	 /**
	  * 核心状态 支出金额
	  * @var integer
	  */
	 const centre_status_deduct=3;
	 /**
	  * 核心状态 记录金额 （非进非出）
	  * @var integer
	  */
	 const centre_status_record=4;
	 /**
	  * 解释字段 centre_status 的含义
	  * @var array
	  */
	 public static $_centre_status=array(
		 	1=>'收益金额',
		 	2=>'冻结金额',
		 	3=>'支出金额',
		 	4=>'记录金额',
	 );
	 
	 /*****************************************处理状态 ***************************************************************/
	 /**
	  * 处理状态 处理失败
	  * @var integer
	  */
	 const log_status_fail=-1;
	 /**
	  * 处理状态 等待处理
	  * @var integer
	  */
	 const log_status_pending=0;
	 /**
	  * 处理状态 处理成功
	  * @var integer
	  */
	 const log_status_success=1;
	 /**
	  * 解释字段 log_status 的含义  如果是冻结 待处理 只有处理成功 计资金记录 其他算出账
	  * @var array
	  */
	 public static $_log_status=array(-1=>'处理失败',0=>'待处理',1=>'处理成功');
	 
	 /***************************************** 详情类型 ***************************************************************/
	 /******************************进账详情**************************************/
	 /**
	  * 详情类型 没有详情
	  * @var integer
	  */
	 const info_type_none=0;
	/**
	 * 详情类型 充值详情类型
	 * @var integer
	 */
	 const info_type_entry_recharge=self::funds_type_entry_recharge;
	 /**
	  * 详情类型 提现失败
	  * @var integer
	  */
	 const info_type_entry_cash_fail=self::funds_type_entry_cash_fail;
	 /**
	  * 详情类型 订单收益
	  * @var integer
	  */
	 const info_type_entry_order_income=self::funds_type_entry_order_income;
	 /**
	  * 详情类型 觅趣收益
	  * @var integer
	  */
	 const info_type_entry_actives_tour_income=self::funds_type_entry_actives_tour_income;
	 
	 /******************************1.2.6***************************************/
	 /**
	  * 详情类型 订单退款
	  * @var integer
	  */
	 const info_type_entry_order_refund=self::funds_type_entry_order_refund;
	 /**
	  * 详情类型 服务费用退款
	  * @var integer
	  */
	 const info_type_entry_refund_actives_tour_charge=self::funds_type_entry_refund_actives_tour_charge;
	 /**
	  * 详情类型 订单补偿
	  * @var integer
	  */
	 const info_type_entry_order_compensate=self::funds_type_entry_order_compensate;
	 /**
	  * 详情类型 服务费用补偿
	  * @var integer
	  */
	 const info_type_entry_actives_tour_compensate=self::funds_type_entry_actives_tour_compensate;
	 	 
	 /******************************冻结详情**************************************/
	 /**
	  * 详情类型 提现申请（冻结）
	  * @var integer
	  */
	 const info_type_pending_cash_frozen=self::funds_type_pending_cash_frozen;
	 
	 /******************************出账详情**************************************/
	 /**
	  * 详情类型 提现成功
	  * @var integer
	  */
	 const info_type_deduct_cash_success=self::funds_type_deduct_cash_success;
	 /**
	  * 详情类型 支付订单 
	  * @var integer
	  */
	 const info_type_deduct_order_pay=self::funds_type_deduct_order_pay;
	 
	 /******************************记录详情**************************************/
	 /**
	  * 详情类型 在线支付
	  * @var integer
	  */
	 const info_type_record_alipay=self::funds_type_record_alipay;
	 /**
	  * 详情类型 退款申请
	  * @var integer
	  */
	 const info_type_record_order_refund_frozen=self::funds_type_record_order_refund_frozen;
	 /**
	  * 详情类型 退款失败
	  * @var integer
	  */
	 const info_type_record_order_refund_fail=self::funds_type_record_order_refund_fail;
	 /**
	  * 详情类型 扫描消费
	  * @var integer
	  */
	 const info_type_record_order_items_consume=self::funds_type_record_order_items_consume;
	 /**
	  * 详情类型 过期消费
	  * @var integer
	  */
	 const info_type_record_order_items_past=self::funds_type_record_order_items_past;
	 /**
	  * 详情类型 服务费用
	  * @var integer
	  */
	 const info_type_record_actives_tour_charge=self::funds_type_record_actives_tour_charge;
	 /******************************1.2.6**************************************/
	 /**
	  * 详情类型 订单违约
	  * @var integer
	  */
	 const info_type_record_order_penalty=self::funds_type_record_order_penalty;
	 /**
	  * 详情类型 服务费用违约
	  * @var integer
	  */
	 const info_type_record_actives_tour_penalty=self::funds_type_record_actives_tour_penalty;
	 
	 /**
	  * 解释字段 info_type 的含义  如果是冻结 待处理 只有处理成功 计资金记录 其他算出账
	  * @var array
	  */
	 public static $_info_type=array(	
			self::info_type_none=>'没有详情',
	 		
			self::info_type_entry_recharge=>'充值详情',
			self::info_type_entry_cash_fail=>'提现失败',
			self::info_type_entry_order_income=>'订单收益',
			self::info_type_entry_actives_tour_income=>'觅趣收益',
	 		//1.2.6
	 		self::info_type_entry_order_refund=>'订单退款',
	 		self::info_type_entry_refund_actives_tour_charge=>'服务费用退款',
	 		self::info_type_entry_order_compensate=>'订单补偿',
	 		self::info_type_entry_actives_tour_compensate=>'服务费用补偿',
	 		
			self::info_type_pending_cash_frozen=>'提现申请',
	 		
			self::info_type_deduct_cash_success=>'提现成功',		
			self::info_type_deduct_order_pay=>'支付订单',
	 		
			self::info_type_record_alipay=>'在线支付',
	 		//1.2.6
	 		self::info_type_record_order_refund_frozen=>'退款申请',
	 		self::info_type_record_order_refund_fail=>'退款失败',
	 		
		 	self::info_type_record_order_items_consume=>'扫描消费',
	 		self::info_type_record_order_items_past=>'过期消费',
		 	self::info_type_record_actives_tour_charge=>'服务费用',
		 	//1.2.6
	 		self::info_type_record_order_penalty=>'订单违约',
	 		self::info_type_record_actives_tour_penalty=>'服务费用违约',
	 );
	 /**
	  * 解释 _info_type 关联表
	  * @var unknown
	  */
	 public static $__info_type=array(
	 		self::info_type_none=>'',
	 	//	self::info_type_entry_recharge=>'AccountLog_Recharge',   											//待添加
	 	
 	 		self::info_type_entry_cash_fail=>'AccountLog_Cash',											//已添加
	 		self::info_type_entry_order_income=>'AccountLog_OrderItems', 						//已添加
	 		self::info_type_entry_actives_tour_income=>'AccountLog_OrderActives',  		//已添加
	 		self::info_type_pending_cash_frozen=>'AccountLog_Cash',									//已添加
	 		//1.2.6
	 		self::info_type_entry_order_refund=>'AccountLog_Sale',
	 		self::info_type_entry_refund_actives_tour_charge=>'AccountLog_Sale',
	 		self::info_type_entry_order_compensate=>'AccountLog_Order',
	 		self::info_type_entry_actives_tour_compensate=>'AccountLog_Order',
	 		
	 		self::info_type_deduct_cash_success=>'AccountLog_Cash',									//已添加
	 		
	 		self::info_type_deduct_order_pay=>'AccountLog_Order',									//已添加	 		
	 		self::info_type_record_alipay=>'AccountLog_Order',											//已添加
	 		
	 		self::info_type_record_order_refund_frozen=>'AccountLog_Apply',					//已添加
	 		self::info_type_record_order_refund_fail=>'AccountLog_Apply',							//已添加
	 		
	 		self::info_type_record_order_items_consume=>'AccountLog_OrderItems',			//已添加
	 		self::info_type_record_order_items_past=>'AccountLog_OrderItems',					//已添加
	 		self::info_type_record_actives_tour_charge=>'AccountLog_Order',						//已添加
	 		//1.2.6
	 		self::info_type_record_order_penalty=>'AccountLog_Sale',
	 		self::info_type_record_actives_tour_penalty=>'AccountLog_Sale',
	 );
	 /**
	  * 详情ID 没有详情ID
	  * @var unknown
	  */
	 const info_id_none=0;
	 /**
	  * 显示info的简单信息
	  * @var unknown
	  */
	 public static $_info_name=array(
	 		
	 		//self::info_type_entry_recharge=>'id',   												//待添加 		
	 		self::info_type_entry_cash_fail=>'id',												//已添加
	 		self::info_type_entry_order_income=>'items_name', 					//已添加
	 		self::info_type_entry_actives_tour_income=>'actives_no', 			//已添加
	 		//1.2.6
	 		self::info_type_entry_order_refund=>'sale_no',									//已添加
	 		self::info_type_entry_refund_actives_tour_charge=>'sale_no',			//已添加
	 		self::info_type_entry_order_compensate=>'order_no',						//已添加
	 		self::info_type_entry_actives_tour_compensate=>'order_no',  		//已添加
	 		
	 		self::info_type_pending_cash_frozen=>'id',									//已添加
	 		
	 		self::info_type_deduct_cash_success=>'id',									//已添加
	 		self::info_type_deduct_order_pay=>'order_no',							//已添加
	 		
	 		self::info_type_record_alipay=>'order_no',									//已添加
	 		
	 		self::info_type_record_order_refund_frozen=>'id',						//已添加
	 		self::info_type_record_order_refund_fail=>'id',								//已添加
	 		
	 		self::info_type_record_order_items_consume=>'items_name',		//已添加
	 		self::info_type_record_order_items_past=>'items_name',				//已添加
	 		self::info_type_record_actives_tour_charge=>'order_no',				//已添加
	 		//1.2.6
	 		self::info_type_record_order_penalty=>'sale_no',
	 		self::info_type_record_actives_tour_penalty=>'sale_no',
	 );
	 
	/***************************************** 资金流水状态***************************************************************/
	/**
	 * 资金流水状态 删除
	 * @var integer
	 */
	const status_del=-1;
	/**
	 * 资金流水状态 禁用
	 * @var integer
	 */
	const status_disable=0;
	/**
	 * 资金流水状态 正常
	 * @var integer
	 */
	const status_normal=1;
	/**
	 * 解释字段 status 的含义 资金流水状态
	 * @var array
	 */
	public static $_status=array(-1=>'删除','禁用','正常');
	
	/*****************************************角色名称***************************************************************/
	/**
	 * 虚构角色名称
	 * @var array
	 */
	public static $_role_fiction_name=array(
			'other'=>'外部',
			'tmm'=>'平台',
			'system'=>'系统',
	);
	/**
	 * 虚构角色的类型 和 ID
	 * @var unknown
	 */
	public static $_fiction_role=array(
			self::account_other=>self::fiction_role_id,
			self::account_system=>self::fiction_role_id,
			self::account_tmm=>self::fiction_role_id,
	);
	
	/**
	 * 角色名称
	 * @var unknown
	 */
	public static $_role_name=array(
		''=>array(
			self::account_agent=>'phone',
			self::account_store=>'phone',
			self::account_user=>'phone',
		),
		'to'=>array(
			self::account_agent=>'phone',
			self::account_store=>'phone',
			self::account_user=>'phone',
		),
		'manage'=>array(
			self::manage_account_admin=>'username',
			self::account_agent=>'phone',
			self::account_store=>'phone',
			self::account_user=>'phone',
		),
	);
	
	/**
	 * 创建资金流水的错误
	 * @var unknown
	 */
	public static $create_error;	
	/**
	 * 搜索的时间类型
	 * @var array
	 */
	public $search_time_type;
	/**
	 *	解释搜索类型时间的含义 search_time_type
	 * @var string
	 */
	public static $_search_time_type=array('创建时间','修改时间'); 
	/**
	 *	解释搜索类型时间 search_time_type 的字段搜索
	 * @var string
	 */
	public $__search_time_type=array('add_time','up_time'); 
	/**
	 * 搜索开始的时间
	 * @var string
	 */
	public $search_start_time;
	/**
	 * 搜索结束的时间
	 * @var string
	 */
	public $search_end_time;	
	/**
	 *统计搜索条件
	 * @var unknown
	 */
	public $criteria_search;
	/**
	 * 统计 rmb
	 * @var unknown
	 */
	public $money_rmb_count;
	/**
	 * 收益统计 rmb
	 * @var unknown
	 */
	public $money_entry_rmb_count;
	/**
	 * 支出统计 rmb
	 * @var unknown
	 */
	public $money_deduct_rmb_count;
	/**
	 * 冻结统计
	 * @var unknown
	 */
	public $money_pending_rmb_count;
	/**
	 * 记录统计
	 * @var unknown
	 */
	public $money_record_rmb_count;
	/**
	 * 搜索角色id
	 * @var unknown
	 */
	public $accurate_id;
	/**
	 * 搜索角色类型
	 * @var unknown
	 */
	public $accurate_type;
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{account_log}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			//array('account_no, funds_type_name, info', 'required'),
			array('account_type, to_account_type, manage_account_type, funds_type, money_type, info_type, log_status, centre_status, status', 'numerical', 'integerOnly'=>true),
			array('account_no, funds_type_name, name', 'length', 'max'=>128),
			array('account_id, to_account_id, manage_account_id, info_id, up_count,info_type,funds_type', 'length', 'max'=>11),
			array('use_money, count, total, money, no_money, cash_count, pay_count, refund_count, consume_count, not_consume_count, after_count, after_total, after_money, after_no_money, after_cash_count, after_pay_count, after_refund_count, after_consume_count, after_not_consume_count', 'length', 'max'=>13),
			array('ip', 'length', 'max'=>15),
			array('address', 'length', 'max'=>100),
			array('add_time, up_time', 'length', 'max'=>10),
			
			//角色类型
			array('account_type','in','range'=>array_keys(self::$_account_type)),
			array('to_account_type','in','range'=>array_keys(self::$_to_account_type)),
			array('manage_account_type','in','range'=>array_keys(self::$_manage_account_type)),
			//资金类型
			array('funds_type','in','range'=>array_keys(self::$_funds_type)),
			//详情类型
			array('info_type','in','range'=>array_keys(self::$_info_type)),
			//核心状态
			array('centre_status','in','range'=>array_keys(self::$_centre_status)),
			//日志状态
			array('log_status','in','range'=>array_keys(self::$_log_status)),
			//钱的类型
			array('money_type','in','range'=>array_keys(self::$_money_type)),
			//验证钱
			array('use_money, count, total, money, no_money, cash_count, pay_count, refund_count, consume_count, not_consume_count,after_count, after_total, after_money, after_no_money, after_cash_count, after_pay_count, after_refund_count, after_consume_count, after_not_consume_count','ext.Validator.Validator_money'),
			//必须的
			array('use_money,funds_type,centre_status,log_status,money_type,
					account_type,account_id,to_account_type,to_account_id,manage_account_type,manage_account_id,
					info_id,info_type,info,name,
					count, total, money, no_money, cash_count, pay_count, refund_count, consume_count, not_consume_count,after_count, after_total, after_money, after_no_money, after_cash_count, after_pay_count, after_refund_count, after_consume_count, after_not_consume_count,
					', 'required','on'=>'create'),
			array('use_money,funds_type,centre_status,log_status,money_type,
					account_type,account_id,to_account_type,to_account_id,manage_account_type,manage_account_id,
					info_id,info_type,info,name, 
					count, total, money, no_money, cash_count, pay_count, refund_count, consume_count, not_consume_count,after_count, after_total, after_money, after_no_money, after_cash_count, after_pay_count, after_refund_count, after_consume_count, after_not_consume_count,
					address','safe','on'=>'create'),
			array('search_time_type,search_start_time,search_end_time,id,account_no,funds_type_name,ip,add_time, up_time, up_count, status','unsafe','on'=>'create'),
			
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('search_time_type,search_start_time,search_end_time,id, account_no, account_id, account_type, to_account_id, to_account_type, manage_account_id, manage_account_type, funds_type, funds_type_name, money_type, use_money, count, total, money, no_money, cash_count, pay_count, refund_count, consume_count, not_consume_count, after_count, after_total, after_money, after_no_money, after_cash_count, after_pay_count, after_refund_count, after_consume_count, after_not_consume_count, info_id, info_type, info, name, ip, address, add_time, up_time, up_count, log_status, centre_status, status', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
				//账户
				'AccountLog_Agent'=>array(self::BELONGS_TO,'Agent','account_id'),
				'AccountLog_User'=>array(self::BELONGS_TO,'User','account_id'),
				'AccountLog_StoreUser'=>array(self::BELONGS_TO,'StoreUser','account_id'),				
				//To
				'AccountLog_Agent_TO'=>array(self::BELONGS_TO,'Agent','to_account_id'),
				'AccountLog_User_TO'=>array(self::BELONGS_TO,'User','to_account_id'),
				'AccountLog_StoreUser_TO'=>array(self::BELONGS_TO,'StoreUser','to_account_id'),
				//manage
				'AccountLog_Agent_Manage'=>array(self::BELONGS_TO,'Agent','manage_account_id'),
				'AccountLog_User_Manage'=>array(self::BELONGS_TO,'User','manage_account_id'),
				'AccountLog_StoreUser_Manage'=>array(self::BELONGS_TO,'StoreUser','manage_account_id'),
				'AccountLog_Admin_Manage'=>array(self::BELONGS_TO,'Admin','manage_account_id'),
				
// 				//关联 充值
// 				'AccountLog_Recharge'=>array(self::BELONGS_TO,'Recharge','info_id'),						//待添加
 				//关联 提现
 				'AccountLog_Cash'=>array(self::BELONGS_TO,'Cash','info_id'),									//已添加
				//关联 退款
 				'AccountLog_Sale'=>array(self::BELONGS_TO,'Sale','info_id'),										//已添加
				// 关联 订单项目详情
				'AccountLog_OrderItems'=>array(self::BELONGS_TO,'OrderItems','info_id'), 			//已添加
				//关联 活动总订单
				'AccountLog_OrderActives'=>array(self::BELONGS_TO,'OrderActives','info_id'), 		//已添加		
				//关联 订单
				'AccountLog_Order'=>array(self::BELONGS_TO,'Order','info_id'),								//已添加
				//关联 订单
				'AccountLog_Order'=>array(self::BELONGS_TO,'Order','info_id'),								//已添加
				//关联 售后
				'AccountLog_Sale'=>array(self::BELONGS_TO,'Sale','info_id'),										//已添加
				//关联 申请
				'AccountLog_Apply'=>array(self::BELONGS_TO,'Apply','info_id'),								//已添加
				
				//关联 账户情况
				'AccountLog_Account'=>array(self::BELONGS_TO,'Account',array(							//已添加
					'account_id'=>'account_id',
					'account_type'=>'account_type',
					'money_type'=>'money_type',
				)),				

		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'account_no' => '流水单号',
			'account_id' => '当前账户',
			'account_type' => '角色类型',
			'to_account_id' => 'T/F账户',
			'to_account_type' => 'T/F类型',
			'manage_account_id' => '操作者',
			'manage_account_type' => '操作者类型',
			'funds_type' => '资金编号',
			'funds_type_name' => '资金类型',
			'money_type' => '钱类型',
			'use_money' => '操作金额',			
			'count' => '统计总额',
			'total' => '总额',
			'money' => '可用余额',
			'no_money' => '已冻结',
			'cash_count' => '已提现',
			'pay_count' => '已付款',
			'refund_count' => '已退款',
			'consume_count' => '已消费',
			'not_consume_count' => '未消费',				
			'after_count' => 'A统计总额',
			'after_total' => 'A总额',
			'after_money' => 'A可用余额',
			'after_no_money' => 'A已冻结',
			'after_cash_count' => 'A已提现',
			'after_pay_count' => 'A已付款',
			'after_refund_count' => 'A已退款',
			'after_consume_count' => 'A已消费',
			'after_not_consume_count' => 'A未消费',
			'info_id' => '详情',
			'info_type' => '详情类型',
			'info' => '简介',
			'name' => '名称',
			'ip' => 'IP',
			'address' => '地址',
			'add_time' => '创建时间',
			'up_time' => '更新时间',
			'up_count' => '更新次数',
			'log_status' => '处理状态',
			'centre_status' => '核心状态',
			'status' => '状态',
			'search_time_type'=>'时间类型',
			'search_start_time'=>'开始时间',
			'search_end_time'=>'结束时间',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search($criteria='')
	{
		// @todo Please modify the following code to remove attributes that should not be searched.
		if($criteria ===''){
			$criteria=new CDbCriteria;
			
			$criteria->with=array(
					//账户
					'AccountLog_Agent'=>array('select'=>'phone'),
					'AccountLog_User'=>array('select'=>'phone'),
					'AccountLog_StoreUser'=>array('select'=>'phone'),
					//To
					'AccountLog_Agent_TO'=>array('select'=>'phone'),
					'AccountLog_User_TO'=>array('select'=>'phone'),
					'AccountLog_StoreUser_TO'=>array('select'=>'phone'),
					//manage
					'AccountLog_Agent_Manage'=>array('select'=>'phone'),
					'AccountLog_User_Manage'=>array('select'=>'phone'),
					'AccountLog_StoreUser_Manage'=>array('select'=>'phone'),
					'AccountLog_Admin_Manage'=>array('select'=>'username'),
					
					// 关联 订单项目详情
					'AccountLog_OrderItems'=>array('select'=>'items_name'),	
					//关联 活动总订单
					'AccountLog_OrderActives'=>array('select'=>'actives_no'),
					//关联 订单
					'AccountLog_Order'=>array('select'=>'order_no'),
					//关联 订单
					'AccountLog_Cash'=>array('select'=>'id'),
					//管理账户情况
					'AccountLog_Account'
			);
			
			if($this->search_time_type != '' && isset($this->__search_time_type[$this->search_time_type]))
			{
				if($this->search_start_time !='' && $this->search_end_time !='')
					$criteria->addBetweenCondition('t.'.$this->__search_time_type[$this->search_time_type],strtotime($this->search_start_time),strtotime($this->search_end_time)+3600*24-1);
				elseif($this->search_start_time !='' && $this->search_end_time =='')
					$criteria->compare('t.'.$this->__search_time_type[$this->search_time_type],'>='.strtotime($this->search_start_time));
				elseif($this->search_start_time =='' && $this->search_end_time !='')
					$criteria->compare('t.'.$this->__search_time_type[$this->search_time_type],'<='.strtotime($this->search_end_time)+3600*24-1);
			}
			//精准搜索
			if($this->account_type == '' && $this->account_id == '' && $this->accurate_id !='' && $this->accurate_type !='')
			{
				$criteria->addColumnCondition(array(
						't.account_id'=>$this->accurate_id,
						't.account_type'=>$this->accurate_type,
				));
			}
			$criteria->compare('t.id',$this->id,true);
			$criteria->compare('t.account_no',$this->account_no,true);
			//被操作的账户
			$criteria_=$criteria_to=$criteria_manage=false;
			if(isset(self::$_account_type[$this->account_type],self::$__account_type[$this->account_type]))
			{
				$relation=self::$__account_type[$this->account_type];
				if(isset(self::$_role_fiction_name[$relation]))
				{
					$criteria->compare('t.account_id',self::fiction_role_id,true);
					$criteria_=true;
				}
				elseif(isset(self::$_role_name[''][$this->account_type]))
				{
					$couditions=array();
					$name=self::$_role_name[''][$this->account_type];
					$couditions[]='t.account_id=:account_id';
					$criteria->params[':account_id']=$this->account_id;
					$couditions[]=$relation.'.'.$name.' LIKE :like_account_id';
					$criteria->addCondition( implode(' OR ', $couditions));
					$criteria->params[':like_account_id']='%'.strtr($this->account_id,array('%'=>'\%', '_'=>'\_', '\\'=>'\\\\')).'%';
					$criteria_=true;			
				}
			}
			if(! $criteria_ && $this->account_id != null)
			{
				$relations=self::$__account_type;
				$couditions=array();
				$couditions[]='t.account_id=:account_id';
				foreach ($relations as $type=>$relation)
				{
					if( ( !isset(self::$_role_fiction_name[$relation])) && isset(self::$_role_name[''][$type]))
						$couditions[]='(`t`.`account_type`='.$type.' AND `'.$relation.'`.`'.self::$_role_name[''][$type].'` LIKE :account_id_like)';
				}
				$criteria->addCondition( implode(' OR ', $couditions));
				$criteria->params[':account_id']=$this->account_id;
				$criteria->params[':account_id_like']='%'.strtr($this->account_id,array('%'=>'\%', '_'=>'\_', '\\'=>'\\\\')).'%';
			}
			$criteria->compare('t.account_type',$this->account_type);
			//操作给谁
			if(isset(self::$_to_account_type[$this->to_account_type],self::$__to_account_type[$this->to_account_type]))
			{
				$relation=self::$__to_account_type[$this->to_account_type];
				if(isset(self::$_role_fiction_name[$relation]))
				{
					$criteria->compare('t.to_account_id',self::fiction_role_id,true);
					$criteria_to=true;
				}
				elseif(isset(self::$_role_name['to'][$this->to_account_type]))
				{
					$couditions = array();
					$name=self::$_role_name['to'][$this->to_account_type];
					$couditions[]='t.to_account_id=:to_account_id';
					$criteria->params[':to_account_id']=$this->to_account_id;
					$couditions[]=$relation.'.'.$name.' LIKE :like_to_account_id';
					$criteria->addCondition( implode(' OR ', $couditions));
					$criteria->params[':like_to_account_id']='%'.strtr($this->to_account_id,array('%'=>'\%', '_'=>'\_', '\\'=>'\\\\')).'%';
					$criteria_to=true;				
				}
			}
			if(! $criteria_to && $this->to_account_id != null)
			{
				$relations = self::$__to_account_type;
				$couditions = array();
				$couditions[]='t.to_account_id=:to_account_id';
				foreach ($relations as $type=>$relation)
				{
					if( ( !isset(self::$_role_fiction_name[$relation])) && isset(self::$_role_name['to'][$type]))
						$couditions[]='(`t`.`to_account_type`='.$type.' AND `'.$relation.'`.`'.self::$_role_name['to'][$type].'` LIKE :to_account_id_like)';
				}
				$criteria->addCondition( implode(' OR ', $couditions));
				$criteria->params[':to_account_id']=$this->to_account_id;
				$criteria->params[':to_account_id_like']='%'.strtr($this->to_account_id,array('%'=>'\%', '_'=>'\_', '\\'=>'\\\\')).'%';
			}
			$criteria->compare('t.to_account_type',$this->to_account_type);
			//谁操作的
			if(isset(self::$_manage_account_type[$this->manage_account_type],self::$__manage_account_type[$this->manage_account_type]))
			{
				$relation=self::$__manage_account_type[$this->manage_account_type];
				if(isset(self::$_role_fiction_name[$relation]))
				{
					$criteria->compare('t.manage_account_id',self::fiction_role_id,true);
					$criteria_manage=true;
				}
				elseif(isset(self::$_role_name['manage'][$this->manage_account_type]))
				{
					$name=self::$_role_name['manage'][$this->manage_account_type];
					$couditions = array();
					$couditions[]='t.manage_account_id=:manage_account_id';
					$criteria->params[':manage_account_id']=$this->manage_account_id;
					$couditions[]=$relation.'.'.$name.' LIKE :like_manage_account_id';
					$criteria->addCondition( implode(' OR ', $couditions));
					$criteria->params[':like_manage_account_id']='%'.strtr($this->manage_account_id,array('%'=>'\%', '_'=>'\_', '\\'=>'\\\\')).'%';
					$criteria_manage=true;
				}
			}
			if(! $criteria_manage && $this->manage_account_id != null)
			{
				$relations = self::$__manage_account_type;
				$couditions = array();
				$couditions[]='t.manage_account_id=:manage_account_id';
				foreach ($relations as $type=>$relation)
				{
					if( ( !isset(self::$_role_fiction_name[$relation])) && isset(self::$_role_name['manage'][$type]))
						$couditions[]='(`t`.`manage_account_type`='.$type.' AND `'.$relation.'`.`'.self::$_role_name['manage'][$type].'` LIKE :manage_account_id_like)';
				}
				$criteria->addCondition( implode(' OR ', $couditions));
				$criteria->params[':manage_account_id']=$this->manage_account_id;
				$criteria->params[':manage_account_id_like']='%'.strtr($this->manage_account_id,array('%'=>'\%', '_'=>'\_', '\\'=>'\\\\')).'%';
			}
			$criteria->compare('t.manage_account_type',$this->manage_account_type);
			
			$criteria->compare('t.funds_type',$this->funds_type);
			$criteria->compare('t.funds_type_name',$this->funds_type_name,true);
			$criteria->compare('t.money_type',$this->money_type);
			$criteria->compare('t.use_money',$this->use_money,true);
			$criteria->compare('t.count',$this->count,true);
			$criteria->compare('t.total',$this->total,true);
			$criteria->compare('t.money',$this->money,true);
			$criteria->compare('t.no_money',$this->no_money,true);
			$criteria->compare('t.cash_count',$this->cash_count,true);
			$criteria->compare('t.pay_count',$this->pay_count,true);
			$criteria->compare('t.refund_count',$this->refund_count,true);
			$criteria->compare('t.consume_count',$this->consume_count,true);
			$criteria->compare('t.not_consume_count',$this->not_consume_count,true);
			$criteria->compare('t.after_count',$this->after_count,true);
			$criteria->compare('t.after_total',$this->after_total,true);
			$criteria->compare('t.after_money',$this->after_money,true);
			$criteria->compare('t.after_no_money',$this->after_no_money,true);
			$criteria->compare('t.after_cash_count',$this->after_cash_count,true);
			$criteria->compare('t.after_pay_count',$this->after_pay_count,true);
			$criteria->compare('t.after_refund_count',$this->after_refund_count,true);
			$criteria->compare('t.after_consume_count',$this->after_consume_count,true);
			$criteria->compare('t.after_not_consume_count',$this->after_not_consume_count,true);
			
			if(isset(self::$__info_type[$this->info_type],self::$_info_name[$this->info_type]))
			{
				$relation=self::$__info_type[$this->info_type];
				$name=self::$_info_name[$this->info_type];
				$criteria->compare($relation.'.'.$name,$this->info_id,true);
				$criteria->compare('t.info_type',$this->info_type);
			}
			else
			{
				$criteria->compare('t.info_id',$this->info_id,true);
				$criteria->compare('t.info_type',$this->info_type);
			}	
			$criteria->compare('t.info',$this->info,true);
			
			$criteria->compare('t.name',$this->name,true);
			$criteria->compare('t.ip',$this->ip,true);
			$criteria->compare('t.address',$this->address,true);
			if($this->add_time != '')
				$criteria->addBetweenCondition('t.add_time',strtotime($this->add_time),(strtotime($this->add_time)+3600*24-1));
			if($this->up_time != '')
				$criteria->addBetweenCondition('t.up_time',strtotime($this->up_time),(strtotime($this->up_time)+3600*24-1));
			$criteria->compare('t.up_count',$this->up_count,true);
			$criteria->compare('t.log_status',$this->log_status);
			$criteria->compare('t.centre_status',$this->centre_status);
			$criteria->compare('t.status',$this->status);
		}
		
		$dataProvider = new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
					'pageSize'=>Yii::app()->params['admin_pageSize'],
			),
			'sort'=>array(
					'defaultOrder'=>'t.add_time desc,t.id desc', //设置默认排序
			),
		));
		$this->criteria_search= $dataProvider->getCriteria();
		return $dataProvider;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return AccountLog the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/**
	 * 保存之前的操作
	 * @see CActiveRecord::beforeSave()
	 */
	public function beforeSave()
	{
		if(parent::beforeSave()){		
			if($this->isNewRecord)
				$this->up_time=$this->add_time=time();
			else
				$this->up_time=time();			
			return true;
		}else
			return false;
	}
	
	/**
	 * 统计条件
	 * @param string $id
	 * @param string $type
	 * @param string $criteria
	 * @return Ambigous <string, CDbCriteria>
	 */
	public function getCountCriteria($id='',$type='',$criteria='')
	{
		if(isset(self::$__account_type[$id]))
		{
			$criteria = new CDbCriteria;
			if(isset(self::$_fiction_role[$type]))
			{
				$criteria->addColumnCondition(array(
						'`t`.`account_id`'=>self::fiction_role_id,
						'`t`.`account_type`'=>$type,
				));
			}
			else
			{
				$criteria->addColumnCondition(array(
						'`t`.`account_id`'=>$id,
						'`t`.`account_type`'=>$type,
				));
			}
		}
		elseif($this->criteria_search instanceof CDbCriteria)
			$criteria = clone $this->criteria_search;
		
		return $criteria;
	}
	
	/**
	 * 统计总额
	 * @param string $id
	 * @param string $type
	 * @param string $criteria
	 * @return string
	 */
	public function getRmbCount($id='',$type='',$criteria='')
	{
		$criteria=$this->getCountCriteria($id,$type,$criteria);
		
		if($criteria)
		{
			$criteria->select='SUM(`use_money`) AS money_rmb_count';
			$model=self::model()->find($criteria);
			return $model ? $model->money_rmb_count : '0.00';
		}
		return '0.00';
	}
	
	/**
	 * 记录统计
	 * @param string $id
	 * @param string $type
	 * @param string $criteria
	 * @return string
	 */
	public function getRecordRmbCount($id='',$type='',$criteria='')
	{
		$criteria=$this->getCountCriteria($id,$type,$criteria);
			
		if($criteria)
		{
			$criteria->select='SUM(`use_money`) AS money_record_rmb_count';
			$conditions=array(
					'`t`.`centre_status`=:centre_status_record',
					'`t`.`centre_status`=:centre_status_pending AND `t`.`log_status`=:log_status_success',
			);
			$criteria->params[':centre_status_record']=self::centre_status_record;
			$criteria->params[':centre_status_pending']=self::centre_status_pending;
			$criteria->params[':log_status_success']=self::log_status_success;
			$criteria->addCondition(implode(' OR ', $conditions));
				
			$model=self::model()->find($criteria);
			return $model ? $model->money_record_rmb_count : '0.00';
		}
		return '0.00';
	}

	/**
	 * 收入统计
	 * @param string $id
	 * @param string $type
	 * @param string $criteria
	 * @return string
	 */
	public function getEntryRmbCount($id='',$type='',$criteria='')
	{
		$criteria=$this->getCountCriteria($id,$type,$criteria);
		if($criteria)
		{
			$criteria->select='SUM(`use_money`) AS money_entry_rmb_count';
			$criteria->addColumnCondition(array(
					'`t`.`centre_status`'=>self::centre_status_entry,
			));
			
			$model=self::model()->find($criteria);
			return $model ? $model->money_entry_rmb_count : '0.00';
		}	
		return '0.00';
	}
	
	/**
	 * 支出统计
	 * @param string $id
	 * @param string $type
	 * @param string $criteria
	 * @return string
	 */
	public function getDeductRmbCount($id='',$type='',$criteria='')
	{
		$criteria=$this->getCountCriteria($id,$type,$criteria);
		if($criteria)
		{
			$criteria->select='SUM(`use_money`) AS money_deduct_rmb_count';
			$conditions=array(
				'`t`.`centre_status`=:centre_status_entry',
				'`t`.`centre_status`=:centre_status_pending AND `t`.`log_status`=:log_status_fail',
			);
			$criteria->params[':centre_status_entry']=self::centre_status_deduct;
			$criteria->params[':centre_status_pending']=self::centre_status_pending;
			$criteria->params[':log_status_fail']=self::log_status_fail;
			$criteria->addCondition(implode(' OR ', $conditions));	
			
			$model=self::model()->find($criteria);
			return $model ? $model->money_deduct_rmb_count : '0.00';
		}
		return '0.00';
	}
	
	/**
	 * 冻结统计
	 * @param string $id
	 * @param string $type
	 * @param string $criteria
	 * @return string
	 */
	public function getPendingRmbCount($id='',$type='',$criteria='')
	{
		$criteria=$this->getCountCriteria($id,$type,$criteria);
		if($criteria)
		{
			$criteria->select='SUM(`use_money`) AS money_pending_rmb_count';
			$criteria->addColumnCondition(array(
				'`t`.`centre_status`'=>self::centre_status_pending,
				'`t`.`log_status`'=>self::log_status_pending,
			));
			
			$model=self::model()->find($criteria);
			return $model ? $model->money_pending_rmb_count : '0.00';
		}
		return '0.00';
	}
	
	/**
	 * 获取角色的数据
	 * @param unknown $model
	 * @param unknown $id
	 * @param unknown $type
	 * @param string $role '' OR 'to' OR 'manage' default ''
	 * @param array $attributes default self::$_role_name 
	 * @return Ambigous <multitype:string , string>|string
	 */
	public static function getRoleName($model,$type,$role='',$attributes=array())
	{
		if(empty($attributes))
			$attributes=self::$_role_name;
		if($role=='' && isset(self::$_account_type[$type],self::$__account_type[$type]))
			$relation=self::$__account_type[$type];
		elseif($role=='to' && isset(self::$_to_account_type[$type],self::$__to_account_type[$type]))
			$relation=self::$__to_account_type[$type];
		elseif($role=='manage' && isset(self::$_manage_account_type[$type],self::$__manage_account_type[$type]))	
			$relation=self::$__manage_account_type[$type];
		
		if(isset($relation,self::$_role_fiction_name[$relation]))
			return self::$_role_fiction_name[$relation];
		elseif(isset($relation))
		{
			$name=isset($attributes[$role][$type])?$attributes[$role][$type]:'';
			return $name=='' ? '未知' : (isset($model->$relation->$name) ? $model->$relation->$name :'未知');
		}
		
		return '未知';
	}
	
	/**
	 * 获取简单的详情信息
	 * @param unknown $model
	 * @param unknown $type
	 * @return string
	 */
	public static function getInfoName($model,$type,$tailor=false)
	{
		if(isset(self::$__info_type[$type],self::$_info_name[$type]))
		{
			$relation=self::$__info_type[$type];
			$name=self::$_info_name[$type];
			return $relation== '' ? '无详情' : (isset($model->$relation->$name) ? (!$tailor ? $model->$relation->$name : mb_substr($model->$relation->$name,0,4,'utf-8') . '...' . mb_substr($model->$relation->$name,-4,4,'utf-8') ) :'无详情');
		}
		
		return '无详情';
	}
	
	/**
	 * 获取订单流程单号
	 * @param unknown $id
	 * @return unknown
	 */
	public static function getAccountNo($id,$funds_type)
	{
		$number=Yii::app()->params['account_log_no_default'];
		$prefix=isset(self::$__funds_type[$funds_type]) ? self::$__funds_type[$funds_type] : '';
		if(strlen($id) < $number )
			$id = sprintf('%0'.$number.'s', $id);

		return $prefix.date('YmdHis',time()).$id.mt_rand(100000,999999);
	}
	
	/**
	 * 	
	 *  创建资金日志
	 * @param float $money           						操作的钱
	 * @param integer $funds_type					资金类型 
	 * @param array $old_account						原来账户信息	array('count'=>'', 'total'=>'', 'money'=>'', 'no_money'=>'', 'cash_count'=>'', 'pay_count'=>'', 'refund_count'=>'', 'consume_count'=>'', 'not_consume_count'=>'',)
	 * @param array $new_account						新的账户信息 	array('after_count'=>'', 'after_total'=>'', 'after_money'=>'', 'after_no_money'=>'', 'after_cash_count'=>'', 'after_pay_count'=>'', 'after_refund_count'=>'', 'after_consume_count'=>'', 'after_not_consume_count'=>'',)
	 * @param array $info									详情 					array('info_id'=>'','info_type'=>'','info'=>'','name'=>'','address'=>'')
	 * @param integer $centre_status					核心状态 
	 * 	@param array $who_role							当前的账户 	array('account_id'=>'', 'account_type'=>'',)
	 * @param array $to_role								来自或者接受的账户 		array('to_account_id'=>'', 'to_account_type'=>'',)
	 * @param array $manage_role						操作的角色 		array('manage_account_id'=>'', 'manage_account_type'=>'',)
	 * @param integer $log_status						日志状态
	 * @param integer $money_type					钱的类型
	 * @return multitype:
	 */
	public static function createLog($money,$funds_type,$old_account=array(),$new_account=array(),$info=array(),$centre_status,$who_role=array(),$to_role=array(),$manage_role=array('manage_account_id'=>self::manage_account_id_system, 'manage_account_type'=>self::manage_account_system),$log_status=self::log_status_success,$money_type=self::money_type_rmb)
	{
		$model=new AccountLog;
		$model->scenario='create';
		
		$model->attributes=array_merge(
			array('use_money'=>$money),					//操作金额
			array('funds_type'=>$funds_type),			//资金类型
			$old_account,												//操作之前账户信息
			$new_account,											//操作之后账户信息
			$info,															//资金流水详情
			array('centre_status'=>$centre_status),	//核心状态
			$who_role,													//当前账户
			$to_role,														//来自或者接受的账户
			$manage_role,												//操作人
			array('log_status'=>$log_status),				//日志状态
			array('money_type'=>$money_type)		//钱的类型
		);
		
		if($model->validate())
		{
			$model->funds_type_name=self::$_funds_type[$model->funds_type];
			$model->ip=isset(Yii::app()->request->userHostAddress)?Yii::app()->request->userHostAddress:'127.0.0.1';
			$model->account_no='';
			$model->status=self::status_normal;
			if($model->save(false))
				return self::model()->updateByPk($model->id, array(
					'account_no'=>self::getAccountNo($model->id, $model->funds_type),
				));
		}
		self::$create_error=array($model->getErrors(),$model->getAttributes());
		return false;
	}

	/**
	 * 更新日志的状态
	 * @param unknown $money
	 * @param unknown $info_id
	 * @param unknown $info_type
	 * @param unknown $log_status
	 * @param number $limit
	 * @return Ambigous <number, unknown>|boolean
	 */
	public static function setLogStatus($money,$account_id,$account_type,$info_id,$info_type,$log_status=self::log_status_success,$limit=1)
	{
		if(isset(self::$_log_status[$log_status]))
		{
			$criteria=new CDbCriteria;
			$criteria->addColumnCondition(array(
				'use_money'=>$money,
				'account_id'=>$account_id,					//账户ID
				'account_type'=>$account_type,			//账户类型
				'info_id'=>$info_id,									//详情ID
				'info_type'=>$info_type,						//详情类型
				'log_status'=>self::log_status_pending,	//状态 待处理
			));
			$criteria->limit=$limit;
			$attributes=array(
				'log_status'=>$log_status,
				'up_time'=>time(),
				'up_count'=>new CDbExpression('`up_count`+1'),
			);
			return self::model()->updateAll($attributes,$criteria);
		}
		return false;
	}
}
