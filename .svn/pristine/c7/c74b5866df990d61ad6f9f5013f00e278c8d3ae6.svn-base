<?php

/**
 * This is the model class for table "{{account}}".
 *
 * The followings are the available columns in table '{{account}}':
 * @property string $id
 * @property string $account_type
 * @property string $account_id
 * @property integer $money_type
 * @property string $count
 * @property string $total
 * @property string $money
 * @property string $no_money
 * @property string $cash_count
 * @property string $pay_count
 * @property string $refund_count
 * @property string $consume_count
 * @property string $not_consume_count
 * @property string $up_count
 * @property string $add_time
 * @property string $up_time
 * @property integer $status
 */
class Account extends CActiveRecord
{
	/**
	 * 虚构角色的ID
	 * @var unknown
	 */
	const fiction_role_id=0;
	/*****************************************账户类型**************************************************************/
	/**
	 * 代理商
	 * @var integer
	 */
	const agent=1;
	/**
	 * 商家
	 * @var integer
	 */
	const store=2;
	/**
	 * 其他(非系统)
	 * @var integer
	 */
	const other=3;
	/**
	 *	用户(组织者)
	 * @var integer
	 */
	const user=4;
	/**
	 *	tmm 平台
	 * @var integer
	 */
	const tmm=5;
	/**
	 * system系统账户
	 * @var integer
	 */
	const system=6;	
	/**
	 * 解释字段 account_type 的含义
	 * @var array
	 */
	public static $_account_type=array(1=>'运营商',2=>'供应商',3=>'外部',4=>'用户',5=>'平台',6=>'系统');
	/**
	 * 其他(非系统)ID
	 * @var integer
	 */
	const other_account_id=self::fiction_role_id;
	/**
	 * 平台ID
	 * @var integer
	 */
	const tmm_account_id=self::fiction_role_id;
	/**
	 * 系统ID
	 * @var integer
	 */
	const system_account_id=self::fiction_role_id;
	/**
	 * 钱包类型关联角色
	 * @var unknown
	 */
	public static $__account_type=array(			
			self::agent=>'Account_Agent',
			self::store=>'Account_StoreUser',
			self::other=>'other',
			self::user=>'Account_User',
			self::tmm=>'tmm',
			self::system=>'system',
	);
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
	
	/***************************************** 账户状态***************************************************************/
	/**
	 * 账户状态 删除
	 * @var integer
	 */
	const status_del=-1;
	/**
	 * 账户状态 禁用
	 * @var integer
	 */
	const status_disable=0;
	/**
	 * 账户状态 正常
	 * @var integer
	 */
	const status_normal=1;
	/**
	 * 解释字段 status 的含义
	 * @var array
	 */
	public static $_status=array(-1=>'删除','禁用','正常');
	
	/***************************************** 角色名称 ***************************************************************/
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
	 * 角色名称
	 * @var unknown
	 */
	public static $_role_name=array(
			self::agent=>'phone',
			self::store=>'phone',
			self::user=>'phone',
	);
	
	/***************************************** 角色名称 ***************************************************************/	
	
	private static $money_attributes=array();
	
	/***************************************** 角色名称 ***************************************************************/
	/**
	 * 创建账户的错误
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
	public static $_search_time_type=array('添加时间','更新时间');	
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
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{account}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			//array('account_type, account_id', 'required'),
			array('money_type, status', 'numerical', 'integerOnly'=>true),
			array('account_type, account_id, up_count', 'length', 'max'=>11),
			//array('count, total, money, no_money, cash_count, pay_count, refund_count, consume_count, not_consume_count', 'length', 'max'=>13),
			array('add_time, up_time', 'length', 'max'=>10),				
			array('count, total, money, no_money, cash_count, pay_count, refund_count, consume_count, not_consume_count', 'length', 'max'=>12),	

			//角色类型
			array('account_type','in','range'=>array_keys(self::$_account_type)),
			//钱的类型
			array('money_type','in','range'=>array_keys(self::$_money_type)),
			//验证钱
			array('count, total, money, no_money, cash_count, pay_count, refund_count, consume_count, not_consume_count','ext.Validator.Validator_money'),
			//创建钱包 验证id
			array('account_id','validateAccountId','on'=>'create'),
			
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('search_time_type,search_start_time,search_end_time,id, account_type, account_id, money_type, count, total, money, no_money, cash_count, pay_count, refund_count, consume_count, not_consume_count, up_count, add_time, up_time, status', 'safe', 'on'=>'search'),
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
			'Account_Agent'=>array(self::BELONGS_TO,'Agent','account_id'),
			'Account_User'=>array(self::BELONGS_TO,'User','account_id'),
			'Account_StoreUser'=>array(self::BELONGS_TO,'StoreUser','account_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'account_type' => '角色类型',
			'account_id' => '角色账号',
			'money_type' => '钱类型',
			'count' => '统计总额',
			'total' => '总额',
			'money' => '可用余额',
			'no_money' => '已冻结',
			'cash_count' => '已提现',
			'pay_count' => '已付款',
			'refund_count' => '已退款',
			'consume_count' => '已消费',
			'not_consume_count' => '未消费',
			'up_count' => '更新次数',
			'add_time' => '创建时间',
			'up_time' => '更新时间',
			'status' => '记录状态',
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
			
			$criteria->with = array(
					'Account_Agent',
					'Account_User',
					'Account_StoreUser',
			);
			
			if($this->search_time_type != '' && isset($this->__search_time_type[$this->search_time_type]))
			{
				if($this->search_start_time !='' && $this->search_end_time !='')
					$criteria->addBetweenCondition('t.'.$this->__search_time_type[$this->search_time_type],strtotime($this->search_start_time),strtotime($this->search_end_time)+3600*24-1);
				elseif($this->search_start_time !='' && $this->search_end_time =='')
					$criteria->compare('t.'.$this->__search_time_type[$this->search_time_type],'>='.strtotime($this->search_start_time));
				elseif($this->search_start_time =='' && $this->search_end_time !='')
					$criteria->compare('t.'.$this->__search_time_type[$this->search_time_type],'<=' . (strtotime($this->search_end_time)+3600*24-1));
			}
			//角色账户
			$criteria_=false;
			if (isset(self::$_account_type[$this->account_type],self::$__account_type[$this->account_type])) 
			{
				$relation=self::$__account_type[$this->account_type];				
				if(isset(self::$_role_fiction_name[$relation]))
				{
					$criteria->compare('t.account_id',self::fiction_role_id,true);
					$criteria_=true;
				}
				elseif(isset(self::$_role_name[$this->account_type]))
				{
					$name=self::$_role_name[$this->account_type];				
					$criteria->compare($relation.'.'.$name,$this->account_id,true);
					$criteria_=true;				
				}
			}
			if(! $criteria_ && $this->account_id != NULL)
			{
				$relations=self::$__account_type;
				$couditions = array();
				$couditions[]='t.account_id LIKE :account_id';
				foreach ($relations as $type=>$relation)
				{
					if( ( !isset(self::$_role_fiction_name[$relation])) && isset(self::$_role_name[$type]))
						$couditions[]='(t.account_type='.$type.' AND '.$relation.'.'.self::$_role_name[$type].' LIKE :account_id)';
				}
				$criteria->addCondition( implode(' OR ', $couditions));
				$criteria->params[':account_id']='%'.strtr($this->account_id,array('%'=>'\%', '_'=>'\_', '\\'=>'\\\\')).'%';
			}
			$criteria->compare('t.account_type',$this->account_type,true);
			
			$criteria->compare('t.id',$this->id,true);
			
			$criteria->compare('t.money_type',$this->money_type);
			$criteria->compare('t.count',$this->count,true);
			$criteria->compare('t.total',$this->total,true);
			$criteria->compare('t.money',$this->money,true);
			$criteria->compare('t.no_money',$this->no_money,true);
			$criteria->compare('t.cash_count',$this->cash_count,true);
			$criteria->compare('t.pay_count',$this->pay_count,true);
			$criteria->compare('t.refund_count',$this->refund_count,true);
			$criteria->compare('t.consume_count',$this->consume_count,true);
			$criteria->compare('t.not_consume_count',$this->not_consume_count,true);
			$criteria->compare('t.up_count',$this->up_count,true);
			if($this->add_time != '')
				$criteria->addBetweenCondition('t.add_time',strtotime($this->add_time),(strtotime($this->add_time)+3600*24-1));
			if($this->up_time != '')
				$criteria->addBetweenCondition('t.up_time',strtotime($this->up_time),(strtotime($this->up_time)+3600*24-1));
			$criteria->compare('t.status',$this->status);
		}
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
					'pageSize'=>Yii::app()->params['admin_pageSize'],
			),
			'sort'=>array(
					'defaultOrder'=>'t.add_time desc', //设置默认排序
			),
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Account the static model class
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
		if(parent::beforeSave())
		{		
			if($this->isNewRecord)
				$this->up_time=$this->add_time=time();
			else
				$this->up_time=time();			
			return true;
		}else
			return false;
	}
	
	/**
	 * 验证虚构角色的id
	 */
	public function validateAccountId()
	{
		if(! $this->hasErrors())
		{			
			if(isset(self::$_account_type[$this->account_type],self::$__account_type[$this->account_type]))
			{
				$relation=self::$__account_type[$this->account_type];
				if(isset(self::$_role_fiction_name[$relation]))
				{
					 if($this->account_id != self::fiction_role_id)
					 	$this->addError('account_id', '账户角色 不是有效值');
				}
				else
				{
					$modelNames=explode('_', $relation);
					if(isset($modelNames[1]))
					{
						if(! $modelNames[1]::model()->findByPk($this->account_id,array('select'=>'id')))
							$this->addError('account_id', '账户角色 不是有效值');
					}
					else
						$this->addError('account_id', '账户角色 不是有效值');
				}
			}else 
				$this->addError('account_id', '账户角色 不是有效值');
		}
	}
	
	/**
	 * 获取角色信息
	 * @param $type
	 * @param $data
	 * @return string
	 */
	public static function account_type($type, $data) 
	{
		return self::getAccountType($data,$type);
	}
	
	/**
	 * 获取简单的详情信息
	 * @param unknown $model
	 * @param unknown $type
	 * @return string
	 */
	public static function getAccountType($model,$type)
	{
		if(isset(self::$_account_type[$type],self::$__account_type[$type]))
		{
			$relation=self::$__account_type[$type];		
			if(isset(self::$_role_fiction_name[$relation]))
				return self::$_role_fiction_name[$relation];
			else
			{
				$name=isset(self::$_role_name[$type]) ? self::$_role_name[$type] : '';
				return $name=='' ? '未知角色' : (isset($model->$relation->$name) ? $model->$relation->$name :'未知角色');
			}
		}
		return '未知角色';
	}
	
	/************************************重写方法 ******************************************************************/
	
	/**
	 * 验证钱错误
	 * @param unknown $money
	 * @return boolean
	 */
	public static function isMoney($money)
	{
		if(! preg_match('/^[0-9]{1,12}(.[0-9]{1,2})?$/', $money))
			return false;
		return true;
	}
	
	/**
	 * 账户的存在
	 * @param unknown $account_id
	 * @param unknown $account_type
	 * @param unknown $money_type
	 * @return Ambigous <static, mixed, NULL, multitype:static , multitype:unknown Ambigous <static, NULL> , multitype:, multitype:unknown >
	 */
	public static function existAccount($account_id=self::tmm_account_id,$account_type=self::tmm,$money_type=self::money_type_rmb)
	{
		$criteria=new CDbCriteria;
		$criteria->addColumnCondition(array(
			'account_id'=>$account_id,
			'account_type'=>$account_type,
			'money_type'=>$money_type,
		));
		return self::model()->find($criteria);
	}
	
	/**
	 * 修改账户的状态
	 * @param number $account_id
	 * @param number $account_type
	 * @param number $money_type
	 * @param number $status
	 * @param number $limit
	 * @return boolean true false
	 */
	public static function setAccountStatus($account_id=self::tmm_account_id,$account_type=self::tmm,$money_type=self::money_type_rmb,$status=self::status_disable,$limit=1)
	{
		$criteria=new CDbCriteria;
		$criteria->addColumnCondition(array(
				'account_id'=>$account_id,
				'account_type'=>$account_type,
				'money_type'=>$money_type,
		));
		$criteria->limit=$limit;
		return self::model()->updateAll(array(
				'status'=>$status,
				'up_time'=>time(),
				'up_count'=>new CDbExpression('`up_count`+1'),
		),$criteria);
	}
	
	/**
	 * 创建账户
	 * @param unknown $account_id
	 * @param unknown $account_type
	 * @param unknown $money_type
	 */
	public static function createAccount($account_id=self::tmm_account_id,$account_type=self::tmm,$money_type=self::money_type_rmb,$verify=true)
	{
		if($verify)
		{
			$model=self::existAccount($account_id,$account_type,$money_type);
			if($model)
				return $model;
		}
		$default='0.00';
		$model=new Account;
		$model->scenario='create';
		$model->account_id=$account_id;
		$model->account_type=$account_type;
		$model->money_type=$money_type;
		$model->count=$default;
		$model->total=$default;
		$model->money=$default;
		$model->no_money=$default;
		$model->cash_count=$default;
		$model->pay_count=$default;
		$model->refund_count=$default;
		$model->consume_count=$default;
		$model->not_consume_count=$default;
		$model->up_count=0;
		$model->status=self::status_normal;//有效的
		if($model->save())
			return $model;
		else
		{
			self::$create_error=$model->getErrors();
			return false;
		}
	}
	
	/**
	 *  获取账户信息
	 * @param unknown $account_id
	 * @param unknown $account_type
	 * @param unknown $money_type
	 */
	public static function getAccount($account_id=self::tmm_account_id, $account_type=self::tmm, $money_type=self::money_type_rmb)
	{
		$model=self::existAccount($account_id,$account_type,$money_type,false);
		if ($model)
			return $model;
		
		return self::createAccount($account_id,$account_type,$money_type);
	}
	
	/***************************钱方法总结*****************************************************/
	/**
	 	self::funds_type_entry_recharge=>'在线充值',								//入
	 	self::funds_type_entry_cash_fail=>'提现失败',								//入
	 	self::funds_type_entry_order_income=>'订单收益',						//入
	 	self::funds_type_entry_actives_tour_income=>'觅趣收益',				//入
	 	//1.2.7
 		self::funds_type_entry_order_refund=>'订单退款',									    //入 用户
 		self::funds_type_entry_order_refund_penalty=>'违约收益',						//入 平台

	 	self::funds_type_pending_cash_frozen=>'提现申请',						// 只有成功的申请 计 记录 否则 计 出
	 	 		
	 	self::funds_type_deduct_cash_success=>'提现成功',						//出
	 	self::funds_type_deduct_order_pay=>'支付订单',							//出
	 			 		
	 	self::funds_type_record_alipay=>'在线支付', 									//记录	 	
	 	
	 	self::funds_type_record_order_items_consume=>'扫描消费',		//记录
	 	self::funds_type_record_order_items_past=>'过期消费',				//记录
	 	self::funds_type_record_actives_tour_charge=>'服务费用',			//记录
	 	
	 	//1.2.7
	 	self::funds_type_record_order_penalty=>'订单违约',						//记录 用户
	 );
	 */
	/*************************** 钱方法总结*****************************************************/	
	/**
	 *	在线充值
	 * @param unknown $money
	 */
	public static function moneyEntryRecharge($money)
	{
		self::$money_attributes=array(
				'count'=>new CDbExpression('`count`+:count',array(':count'=>$money)),
				'total'=>new CDbExpression('`total`+:total',array(':total'=>$money)),	
				'money'=>new CDbExpression('`money`+:money',array(':money'=>$money)),
		);
	}
	
	/**
	 * 提现失败
	 * @param unknown $money
	 */
	public static function moneyEntryCashFail($money)
	{
		self::$money_attributes=array(
				'money'=>new CDbExpression('`money`+:money',array(':money'=>$money)),
				'no_money'=>new CDbExpression('`no_money`-:no_money',array(':no_money'=>$money)),
		);
	}
	
	/**
	 * 订单收益
	 * @param unknown $money
	 */
	public static function moneyEntryOrderIncome($money)
	{
		self::$money_attributes=array(				
				'count'=>new CDbExpression('`count`+:count',array(':count'=>$money)),
				'total'=>new CDbExpression('`total`+:total',array(':total'=>$money)),			
				'money'=>new CDbExpression('`money`+:money',array(':money'=>$money)),
		);
	}
	
	/**
	 * 资金汇入 觅趣收益
	 * @param unknown $money
	 */
 	public static function moneyEntryActivesTourIncome($money)
 	{
 		self::$money_attributes=array(
 				'count'=>new CDbExpression('`count`+:count', array(':count'=>$money)),
 				'total'=>new CDbExpression('`total`+:total', array(':total'=>$money)),			
 				'money'=>new CDbExpression('`money`+:money', array(':money'=>$money)),
 		);
 	}
 	
 	/**
 	 * 1.2.7
 	 * 订单退款
 	 * @param unknown $money
 	 */
 	public static function moneyEntryOrderRefund($money)
 	{
 		self::$money_attributes = array(
 				'money'=>new CDbExpression('`money`+:money', array(':money'=>$money)),
 				'not_consume_count'=>new CDbExpression('`not_consume_count`-:not_consume_count', array(':not_consume_count'=>$money)),		
 				'refund_count'=>new CDbExpression('`refund_count`+:refund_count', array(':refund_count'=>$money)),
 		);
 	}
 	
 	/**
 	 * 1.2.7
 	 *违约收益
 	 * @param unknown $money
 	 */
 	public static function moneyEntryOrderRefundPenalty($money)
 	{
 		self::$money_attributes = array(
 				'count'=>new CDbExpression('`count`+:count', array(':count'=>$money)),
 				'total'=>new CDbExpression('`total`+:total', array(':total'=>$money)),			
 				'money'=>new CDbExpression('`money`+:money', array(':money'=>$money)),
 		);
 	}
 	
 	/**
 	 * 提现申请
 	 * @param unknown $money
 	 */
 	public static function moneyPendingCashFrozen($money)
 	{
 		self::$money_attributes=array(
 				'money'=>new CDbExpression('`money`-:money',array(':money'=>$money)),
 				'no_money'=>new CDbExpression('`no_money`+:no_money',array(':no_money'=>$money)),
 		);
 	}
 	
 	/**
 	 * 提现成功
 	 * @param unknown $money
 	 */
 	public static function moneyDeductCashSuccess($money)
 	{
 		self::$money_attributes=array(
 				'total'=>new CDbExpression('`total`-:total',array(':total'=>$money)),
 				'no_money'=>new CDbExpression('`no_money`-:no_money',array(':no_money'=>$money)),
 				'cash_count'=>new CDbExpression('`cash_count`+:cash_count',array(':cash_count'=>$money)),
 		);
 	}
 	
 	/**
 	 * 支付订单
 	 * @param unknown $money
 	 */
 	public static function moneyDeductOrderPay($money)
 	{
 		self::$money_attributes=array(
 				'money'=>new CDbExpression('`money`-:money',array(':money'=>$money)),
 				'not_consume_count'=>new CDbExpression('`not_consume_count`+:not_consume_count',array(':not_consume_count'=>$money)),
 				'pay_count'=>new CDbExpression('`pay_count`+:pay_count',array(':pay_count'=>$money)),		
 		);
 	}

 	/**
 	 * 在线支付
 	 * @param unknown $money
 	 */
 	public static function moneyRecordAlipay($money)
 	{
 		self::$money_attributes=array(		
 				'count'=>new CDbExpression('`count`+:count',array(':count'=>$money)),
 				'total'=>new CDbExpression('`total`+:total',array(':total'=>$money)),
 				'not_consume_count'=>new CDbExpression('`not_consume_count`+:not_consume_count',array(':not_consume_count'=>$money)),
 				'pay_count'=>new CDbExpression('`pay_count`+:pay_count',array(':pay_count'=>$money)),
 		);
 	}
 	
 	/**
 	 * 扫描消费
 	 * @param unknown $money
 	 */
 	public static function moneyRecordOrderItemsConsume($money)
 	{
 		self::$money_attributes=array(
 				'total'=>new CDbExpression('`total`-:total',array(':total'=>$money)),
 				'not_consume_count'=>new CDbExpression('`not_consume_count`-:not_consume_count',array(':not_consume_count'=>$money)),
 				'consume_count'=>new CDbExpression('`consume_count`+:consume_count',array(':consume_count'=>$money)),
 		);
 	}
 	
 	/**
 	 * 过期消费
 	 * @param unknown $money
 	 */
 	public static function moneyRecordOrderItemsPast($money)
 	{
 		self::$money_attributes=array(
 				'total'=>new CDbExpression('`total`-:total',array(':total'=>$money)),
 				'not_consume_count'=>new CDbExpression('`not_consume_count`-:not_consume_count',array(':not_consume_count'=>$money)),
 				'consume_count'=>new CDbExpression('`consume_count`+:consume_count',array(':consume_count'=>$money)),
 		);
 	}
 	
 	/**
 	 * 服务费用
 	 * @param unknown $money
 	 */
 	public static function moneyRecordActivesTourCharge($money)
 	{
 		self::$money_attributes=array(
 				'total'=>new CDbExpression('`total`-:total',array(':total'=>$money)),
 				'not_consume_count'=>new CDbExpression('`not_consume_count`-:not_consume_count',array(':not_consume_count'=>$money)),
 				'consume_count'=>new CDbExpression('`consume_count`+:consume_count',array(':consume_count'=>$money)),
 		);
 	}
 	
 	/**
 	 * 1.2.7
 	 * 订单违约
 	 * @param unknown $money
 	 */
 	public static function moneyRecordOrderPenalty($money)
 	{
 		self::$money_attributes=array(
 				'total'=>new CDbExpression('`total`-:total',array(':total'=>$money)),
 				'not_consume_count'=>new CDbExpression('`not_consume_count`-:not_consume_count',array(':not_consume_count'=>$money)),
 				'consume_count'=>new CDbExpression('`consume_count`+:consume_count',array(':consume_count'=>$money)),
 		);
 	}

 	/******************************************执行钱包的操作**********************************/
 	/**
 	 * 执行钱包的操作
 	 * @param integer $id
 	 */
 	public static function moneyExecuteByPk($id)
 	{
 		if (!empty(self::$money_attributes))
 		{
	 		self::$money_attributes['up_time'] = time();
	 		self::$money_attributes['up_count'] = new CDbExpression('`up_count`+1');
	 		return self::model()->updateByPk($id, self::$money_attributes);
 		}
 		return false;
 	}

 	/**
 	 * 执行钱包的操作
 	 * @param integer $account_id
 	 * @param integer $account_type
 	 * @param integer $money_type
 	 * @param integer $limit
 	 * @return Ambigous <number, unknown>
 	 */
 	public static function moneyExecute($account_id,$account_type,$money_type=self::money_type_rmb,$limit=1)
 	{
 		$criteria=new CDbCriteria;
 		$criteria->addColumnCondition(array(
 				'account_id'=>$account_id,
 				'account_type'=>$account_type,
 				'money_type'=>$money_type,
 		));
 		$criteria->limit = $limit;
 		self::$money_attributes['up_time'] = time();
 		self::$money_attributes['up_count'] = new CDbExpression('`up_count`+1');
 		return self::model()->updateAll(self::$money_attributes,$criteria);
 	}
 	
 	/*******************************************钱包的资金流水**************************************************/
 	/**
 	 *  		$funds_type=array(
 				AccountLog::funds_type_entry_recharge=>'moneyEntryRecharge',												//入 在线充值
 				AccountLog::funds_type_entry_cash_fail=>'moneyEntryCashFail',													//入	提现失败
 				AccountLog::funds_type_entry_order_income=>'moneyEntryOrderIncome',									//入	订单收益
 				AccountLog::funds_type_entry_actives_tour_income=>'moneyEntryActivesTourIncome',				//入	觅趣收益
 				//1.2.7
		 		AccountLog::funds_type_entry_order_refund=>'moneyEntryOrderRefund',									//入 订单退款
 				AccountLog::funds_type_entry_order_refund_penalty=>'moneyEntryOrderRefundPenalty',			//入 违约收益
 		
 				AccountLog::funds_type_pending_cash_frozen=>'moneyPendingCashFrozen',								// 只有成功的申请 计 记录 否则 计 出	提现申请
 		
 				AccountLog::funds_type_deduct_cash_success=>'moneyDeductCashSuccess',								//出	提现成功
 				AccountLog::funds_type_deduct_order_pay=>'moneyDeductOrderPay',										//出	支付订单
 		
 				AccountLog::funds_type_record_alipay=>'moneyRecordAlipay',														//记录	在线支付
 				
				AccountLog::funds_type_record_order_items_consume=>'moneyRecordOrderItemsConsume',	//记录	扫描消费
 				AccountLog::funds_type_record_order_items_past=>'moneyRecordOrderItemsPast',					//记录	过期消费
 				AccountLog::funds_type_record_actives_tour_charge=>'moneyRecordActivesTourCharge',			//记录	服务费用
 				//1.2.7
	 			AccountLog::funds_type_record_order_penalty=>'moneyRecordOrderPenalty',								//记录 订单违约
 		);
 	 */
 	
 	/**
 	 * 订单违约
 	 * @param float $money
 	 * @param array $who_role
 	 * @param array $info
 	 * @return boolean
 	 */
 	public static function moneyRecordOrderPenaltyRmb($money,$who_role,$info)
 	{
 		$money_type = self::money_type_rmb;
 		$admin = isset(Yii::app()->admin) ? Yii::app()->admin->id : 0;
 		//验证
 		if (self::isMoney($money) && $admin && isset($who_role['account_id'], $who_role['account_type'], $info['info_id'], $info['info'], $info['name']))
 		{
 			//订单违约
 			$info['info_type']=AccountLog::info_type_record_order_penalty;
 			$old_model = self::getAccount($who_role['account_id'],$who_role['account_type'],$money_type);
 			//订单违约
 			self::moneyRecordOrderPenalty($money);
 			//执行钱包操作
 			if ($old_model && self::moneyExecuteByPk($old_model->id) && (!!$new_model=self::model()->findByPk($old_model->id)))
 			{	
 				return AccountLog::createLog(
 						//操作的钱
 						$money,
 						//操作类型 订单违约
 						AccountLog::funds_type_record_order_penalty,
 						//原来的
 						self::getAccountArray($old_model),
 						//更新后的
 						self::getAccountArray($new_model,'after_'),
 						//订单违约 详情
 						$info,
 						//核心 记录
 						AccountLog::centre_status_record,
 						//谁的 订单违约
 						array('account_id'=>$new_model->account_id, 'account_type'=>$new_model->account_type),
 						//付给 谁的账户
 						array('to_account_id'=>self::tmm_account_id, 'to_account_type'=>self::tmm),
 						//操作者 订单违约 当前登录管理员
 						 array(
 								'manage_account_id'=>$admin,
 								'manage_account_type'=>AccountLog::manage_account_admin,
 						),
 						//订单违约 默认成功
 						AccountLog::log_status_success,
 						//订单违约 钱类型
 						$money_type
 				);
 			}
 		}
 		return false;
 	}
 	
 	/**
 	 * 违约收益
 	 *  用户 给 平台
 	 * @param float $money
 	 * @param array $to_role
 	 * @param array $info
 	 * @return boolean
 	 */
 	public static function moneyEntryOrderRefundPenaltyRmb($money,$to_role,$info)
 	{
 		$money_type = self::money_type_rmb;
 		$admin = isset(Yii::app()->admin) ? Yii::app()->admin->id : 0;
 		//验证
 		if (self::isMoney($money) && $admin && isset($to_role['to_account_id'], $to_role['to_account_type'], $info['info_id'], $info['info'], $info['name']))
 		{
 			//违约收益
 			$info['info_type'] = AccountLog::info_type_entry_order_refund_penalty;
 			$old_model = self::getAccount(self::tmm_account_id, self::tmm, $money_type);
 			//订单退款
 			self::moneyEntryOrderRefundPenalty($money);
 			//执行钱包操作
 			if ($old_model && self::moneyExecuteByPk($old_model->id) && (!! $new_model = self::model()->findByPk($old_model->id)))
 			{
 				return AccountLog::createLog(
 						//操作的钱
 						$money,
 						//操作类型 违约收益
 						AccountLog::funds_type_entry_order_refund_penalty,
 						//原来的
 						self::getAccountArray($old_model),
 						//更新后的
 						self::getAccountArray($new_model,'after_'),
 						//违约收益详情
 						$info,
 						//核心 进
 						AccountLog::centre_status_entry,
 						//平台
 						array('account_id'=>self::tmm_account_id, 'account_type'=>self::tmm),
 						//来自 用户
 						$to_role,
 						//操作者 默认管理员
 						array(
 								'manage_account_id'=>$admin,
 								'manage_account_type'=>AccountLog::manage_account_admin,
 						),
 						//违约收益  成功记录
 						AccountLog::log_status_success,
 						//违约收益 钱的类型
 						$money_type
 				);
 			}
 		}
 		return false;
 	}
 	
 	/**
 	 * 订单退款 需要管理员登录
 	 * 用户给用户
 	 * @param unknown $money
 	 * @param unknown $who_role
 	 * @param unknown $info
 	 * @return Ambigous <multitype:, boolean, number, unknown>|boolean
 	 */
 	public static function moneyEntryOrderRefundRmb($money,$who_role,$info)
 	{
 		$money_type = self::money_type_rmb;
 		$admin = isset(Yii::app()->admin) ? Yii::app()->admin->id : 0;
 		//验证
 		if (self::isMoney($money) && $admin && isset($who_role['account_id'], $who_role['account_type'], $info['info_id'], $info['info'], $info['name']))
 		{
 			//订单退款
 			$info['info_type'] = AccountLog::info_type_entry_order_refund;
 			$old_model = self::getAccount($who_role['account_id'], $who_role['account_type'], $money_type);
 			//订单退款
 			self::moneyEntryOrderRefund($money);
 			//执行钱包操作
 			if ($old_model && self::moneyExecuteByPk($old_model->id) && (!! $new_model = self::model()->findByPk($old_model->id)))
 			{
 				return AccountLog::createLog(
 						//操作的钱
 						$money,
 						//操作类型 订单退款
 						AccountLog::funds_type_entry_order_refund,
 						//原来的
 						self::getAccountArray($old_model),
 						//更新后的
 						self::getAccountArray($new_model,'after_'),
 						//订单退款详情
 						$info,
 						//核心 进
 						AccountLog::centre_status_entry,
 						//用户
 						array('account_id'=>$new_model->account_id, 'account_type'=>$new_model->account_type),
 						//来自 用户
 						array('to_account_id'=>$new_model->account_id, 'to_account_type'=>$new_model->account_type),
 						//操作者 默认管理员
 						array(
 								'manage_account_id'=>$admin,
 								'manage_account_type'=>AccountLog::manage_account_admin,
 						),
 						//订单退款  成功记录
 						AccountLog::log_status_success,
 						//订单退款 钱的类型
 						$money_type
 				);
 			}
 		}
 		return false;
 	}
 	
 	/**
 	 * 服务费用
 	 * @param unknown $money
 	 * @param unknown $who_role
 	 * @param unknown $info
 	 * @param unknown $to_role
 	 * @return Ambigous <multitype:, boolean, number, unknown>|boolean
 	 */
 	public static function moneyRecordActivesTourChargeRmb($money,$who_role,$info,$to_role)
 	{
 		$money_type=self::money_type_rmb;		
 		if(isset(Yii::app()->store) && Yii::app()->store->id)
 		{
 			$manage_role=array(
 					'manage_account_id'=>Yii::app()->store->id,
 					'manage_account_type'=>AccountLog::manage_account_store,
 			);
 		}
 		else
 		{
 			$manage_role=array(
 					'manage_account_id'=>AccountLog::fiction_role_id,
 					'manage_account_type'=>AccountLog::manage_account_system,
 			);
 		}
 		
 		if(self::isMoney($money) && isset($who_role['account_id'],$who_role['account_type'],$to_role['to_account_id'],$to_role['to_account_type'],$info['info_id'],$info['info'],$info['name']))
 		{
 			//服务费用
 			$info['info_type']=AccountLog::info_type_record_actives_tour_charge;
 			$old_model=self::getAccount($who_role['account_id'],$who_role['account_type'],$money_type);
 			//服务费用
 			self::moneyRecordActivesTourCharge($money);
 			//执行钱包操作
 			if($old_model && self::moneyExecuteByPk($old_model->id))
 			{
 				$new_model=self::model()->findByPk($old_model->id);
 				return AccountLog::createLog(
 						//操作的钱
 						$money,
 						//操作类型 服务费用
 						AccountLog::funds_type_record_actives_tour_charge,
 						//原来的
 						self::getAccountArray($old_model),
 						//更新后的
 						self::getAccountArray($new_model,'after_'),
 						//服务费用 详情
 						$info,
 						//核心 记录
 						AccountLog::centre_status_record,
 						//谁的服务费用
 						array('account_id'=>$old_model->account_id, 'account_type'=>$old_model->account_type),
 						//付给 谁的账户
 						$to_role,
 						//操作者服务费用 当前登录的商家 或系统
 						$manage_role,
 						//扫描消费 默认成功
 						AccountLog::log_status_success,
 						//扫描消费 钱类型
 						$money_type
 				);
 			}
 		}
 		return false;
 	}
 	
 	/**
 	 * 过期消费
 	 * @param unknown $money
 	 * @param unknown $who_role
 	 * @param unknown $info
 	 * @param unknown $to_role
 	 * @return Ambigous <multitype:, boolean, number, unknown>|boolean
 	 */
 	public static function moneyRecordOrderItemsPastRmb($money,$who_role,$info)
 	{
 		$money_type=self::money_type_rmb;
 	
 		if(self::isMoney($money) && isset($who_role['account_id'],$who_role['account_type'],$info['info_id'],$info['info'],$info['name']))
 		{
 			//过期消费
 			$info['info_type']=AccountLog::info_type_record_order_items_past;
 			$old_model=self::getAccount($who_role['account_id'],$who_role['account_type'],$money_type);
 			//过期消费
 			self::moneyRecordOrderItemsPast($money);
 			//执行钱包操作
 			if($old_model && self::moneyExecuteByPk($old_model->id))
 			{
 				$new_model=self::model()->findByPk($old_model->id);
 				return AccountLog::createLog(
 						//操作的钱
 						$money,
 						//操作类型 过期消费
 						AccountLog::funds_type_record_order_items_past,
 						//原来的
 						self::getAccountArray($old_model),
 						//更新后的
 						self::getAccountArray($new_model,'after_'),
 						//过期消费 详情
 						$info,
 						//核心 记录
 						AccountLog::centre_status_record,
 						//谁的过期消费
 						array('account_id'=>$old_model->account_id, 'account_type'=>$old_model->account_type),
 						//来自 用户的账户
 						array('to_account_id'=>$old_model->account_id, 'to_account_type'=>$old_model->account_type),
 						//操作者	过期消费 系统
 						array(
 								'manage_account_id'=>self::fiction_role_id,
 								'manage_account_type'=>AccountLog::manage_account_system,
 						),
 						//过期消费 默认成功
 						AccountLog::log_status_success,
 						//过期消费 钱类型
 						$money_type
 				);
 			}
 		}
 		return false;
 	}
 	
 	/**
 	 * 扫描消费 
 	 * @param unknown $money
 	 * @param unknown $who_role
 	 * @param unknown $info
 	 * @param unknown $to_role
 	 * @return Ambigous <multitype:, boolean, number, unknown>|boolean
 	 */
 	public static function moneyRecordOrderItemsConsumeRmb($money,$who_role,$info)
 	{
 		$money_type=self::money_type_rmb;
 		$store_id=isset(Yii::app()->store)?Yii::app()->store->id:0;

		if(self::isMoney($money) && $store_id && isset($who_role['account_id'],$who_role['account_type'],$info['info_id'],$info['info'],$info['name']))
		{
			//扫描消费
			$info['info_type']=AccountLog::info_type_record_order_items_consume;
			$old_model=self::getAccount($who_role['account_id'],$who_role['account_type'],$money_type);
			//扫描消费
			self::moneyRecordOrderItemsConsume($money);
			//执行钱包操作
			if($old_model && self::moneyExecuteByPk($old_model->id))
			{
				$new_model=self::model()->findByPk($old_model->id);
				return AccountLog::createLog(
						//操作的钱
						$money,
						//操作类型 扫描消费
						AccountLog::funds_type_record_order_items_consume,
						//原来的
						self::getAccountArray($old_model),
						//更新后的
						self::getAccountArray($new_model,'after_'),
						//扫描消费 详情
						$info,
						//核心 记录
						AccountLog::centre_status_record,
						//谁的扫描消费
						array('account_id'=>$old_model->account_id, 'account_type'=>$old_model->account_type),
						//来自 用户的账户
						array('to_account_id'=>$old_model->account_id, 'to_account_type'=>$old_model->account_type),
						//操作者扫描消费 当前登录的商家
						array(
							'manage_account_id'=>$store_id,
							'manage_account_type'=>AccountLog::manage_account_store,
						),
						//扫描消费 默认成功
						AccountLog::log_status_success,
						//扫描消费 钱类型
						$money_type
				);
			}
		}
		return false;
 	}
 	
 	/**
 	 * 在线支付
 	 * @param unknown $money
 	 * @param unknown $who_role
 	 * @param unknown $info
 	 * @return Ambigous <multitype:, boolean, number, unknown>|boolean
 	 */
 	public static function moneyRecordAlipayRmb($money,$who_role,$info)
 	{
 		$money_type=self::money_type_rmb;
 		if(self::isMoney($money) && isset($who_role['account_id'],$who_role['account_type'],$info['info_id'],$info['info'],$info['name']))
 		{
 			//在线支付
 			$info['info_type']=AccountLog::info_type_record_alipay;
 			$old_model=self::getAccount($who_role['account_id'],$who_role['account_type'],$money_type);
 			//在线支付
 			self::moneyRecordAlipay($money);
 			//在线支付 执行钱包
 			if($old_model && self::moneyExecuteByPk($old_model->id))
 			{
 				$new_model=self::model()->findByPk($old_model->id);
 				return AccountLog::createLog(
 						//操作的钱
 						$money,
 						//操作类型 在线支付
 						AccountLog::funds_type_record_alipay,
 						//原来的
 						self::getAccountArray($old_model),
 						//更新后的
 						self::getAccountArray($new_model,'after_'),
 						//在线支付 详情
 						$info,
 						//核心 记录
 						AccountLog::centre_status_record,
 						//谁 在线支付
 						array('account_id'=>$old_model->account_id, 'account_type'=>$old_model->account_type),
 						//来自 在线支付 默认外部
 						array('to_account_id'=>self::fiction_role_id, 'to_account_type'=>AccountLog::to_account_other),
 						//操作者 在线支付 默认自己操作
 						array('manage_account_id'=>$old_model->account_id, 'manage_account_type'=>$old_model->account_type),
 						//支付订单 默认成功处理
 						AccountLog::log_status_success,
 						//支付订单 钱类型
 						$money_type
 				);
 			}
 		}
 		return false;
 	}
 	
 	/**
 	 * 支付订单
 	 * @param unknown $money
 	 * @param unknown $who_role
 	 * @param unknown $info
 	 * @return Ambigous boolean
 	 */
 	public static function moneyDeductOrderPayRmb($money,$who_role,$info)
 	{
 		$money_type=self::money_type_rmb;
 		if(self::isMoney($money) && isset($who_role['account_id'],$who_role['account_type'],$info['info_id'],$info['info'],$info['name']))
 		{
 			//支付订单
 			$info['info_type']=AccountLog::info_type_deduct_order_pay;
 			$old_model=self::getAccount($who_role['account_id'],$who_role['account_type'],$money_type);
 			//支付订单
 			self::moneyDeductOrderPay($money);
 			//支付订单 执行钱包
 			if($old_model && self::moneyExecuteByPk($old_model->id))
 			{
 				$new_model=self::model()->findByPk($old_model->id);
 				return AccountLog::createLog(
 						//操作的钱
 						$money,
 						//操作类型 支付订单
 						AccountLog::funds_type_deduct_order_pay,
 						//原来的
 						self::getAccountArray($old_model),
 						//更新后的
 						self::getAccountArray($new_model,'after_'),
 						//支付订单 详情
 						$info,
 						//核心 出
 						AccountLog::centre_status_deduct,
 						//谁 支付订单
 						array('account_id'=>$old_model->account_id, 'account_type'=>$old_model->account_type),
 						//来自 提现申请默认 自己
 						array('to_account_id'=>$old_model->account_id, 'to_account_type'=>$old_model->account_type),
 						//操作者提现申请默认 自己操作
 						array('manage_account_id'=>$old_model->account_id, 'manage_account_type'=>$old_model->account_type),
 						//支付订单 默认成功处理
 						AccountLog::log_status_success,
 						//支付订单 钱类型
 						$money_type
 				);
 			}
 		}
 		return false;
 	}
 	
 	/**
 	 * 提现成功
 	 * @param unknown $money 提现成功的金额
 	 * @param unknown $who_role	谁提现的
 	 * @param unknown $info				提现的详情
 	 * @return Ambigous boolean
 	 */
 	public static function moneyDeductCashSuccessRmb($money,$who_role,$info)
 	{
 		$money_type=self::money_type_rmb;
 		$admin_id = isset(Yii::app()->admin)?Yii::app()->admin->id:0;
 			
 		if(self::isMoney($money) && $admin_id && isset($who_role['account_id'],$who_role['account_type'],$info['info_id'],$info['info'],$info['name']))
 		{
 			//提现成功
 			$info['info_type']=AccountLog::info_type_deduct_cash_success;
 			$old_model=self::getAccount($who_role['account_id'],$who_role['account_type'],$money_type);
 			//提现成功
 			self::moneyDeductCashSuccess($money);
 			//执行钱包操作
 			if($old_model && self::moneyExecuteByPk($old_model->id) &&
 					//提现成功 执行之前的 标记成功
 					AccountLog::setLogStatus(
 							//操作的钱
 							$money,
 							$who_role['account_id'],
 							$who_role['account_type'],					//账户
 							$info['info_id'],AccountLog::info_type_pending_cash_frozen,//提现冻结
 							AccountLog::log_status_success			//提现成功
 					)
 			)
 			{
 				$new_model=self::model()->findByPk($old_model->id);
 				return AccountLog::createLog(
 						//操作的钱
 						$money,
 						//操作类型 提现成功
 						AccountLog::funds_type_deduct_cash_success,
 						//原来的
 						self::getAccountArray($old_model),
 						//更新后的
 						self::getAccountArray($new_model,'after_'),
 						//提现成功的详情
 						$info,
 						//核心 出
 						AccountLog::centre_status_deduct,
 						//谁提现成功
 						array('account_id'=>$old_model->account_id, 'account_type'=>$old_model->account_type),
 						//来自 自己的账户
 						array('to_account_id'=>$old_model->account_id,'to_account_type'=>$old_model->account_type),
 						//操作者 提现成功 默认管理员
 						array(
 								'manage_account_id'=>$admin_id,
 								'manage_account_type'=>AccountLog::manage_account_admin,
 						),
 						//提现成功 默认成功
 						AccountLog::log_status_success,
 						//提现成功 钱类型
 						$money_type
 				);
 			}
 		}
 		return false;
 	}
 	
 	/**
 	 * 提现申请
 	 * @param unknown $money		提现的钱
 	 * @param unknown $who_role	谁提现
 	 * @param unknown $info				提现详情
 	 * @return boolean
 	 */
 	public static function moneyPendingCashFrozenRmb($money,$who_role,$info)
 	{
 		$money_type=self::money_type_rmb;
 		if(self::isMoney($money) && isset($who_role['account_id'],$who_role['account_type'],$info['info_id'],$info['info'],$info['name']))
 		{
 			//提现申请
 			$info['info_type']=AccountLog::info_type_pending_cash_frozen;
 			$old_model=self::getAccount($who_role['account_id'],$who_role['account_type'],$money_type);
 			//提现申请
 			self::moneyPendingCashFrozen($money);
 			//提现申请 执行钱包
 			if($old_model && self::moneyExecuteByPk($old_model->id))
 			{
 				$new_model=self::model()->findByPk($old_model->id);
 				return AccountLog::createLog(
 						//操作的钱
 						$money,
 						//操作类型 提现申请
 						AccountLog::funds_type_pending_cash_frozen,
 						//原来的
 						self::getAccountArray($old_model),
 						//更新后的
 						self::getAccountArray($new_model,'after_'),
 						//提现申请的详情
 						$info,
 						//核心 冻结金额
 						AccountLog::centre_status_pending,
 						//谁提现申请
 						array('account_id'=>$old_model->account_id, 'account_type'=>$old_model->account_type),
 						//来自 提现申请默认 自己
 						array('to_account_id'=>$old_model->account_id, 'to_account_type'=>$old_model->account_type),
 						//操作者提现申请默认 自己操作
 						array('manage_account_id'=>$old_model->account_id, 'manage_account_type'=>$old_model->account_type),
 						//提现申请 默认待处理
 						AccountLog::log_status_pending,
 						//提现申请 钱类型
 						$money_type
 				);
 			}
 		}
 		return false;
 	}
 	
 	/**
 	 * 觅趣旅游活动收益 				（觅趣旅游活动服务费收益）
 	 * @param unknown $money 收到的服务费
 	 * @param unknown $who_role 谁的活动 
 	 * @param unknown $info				活动详情
 	 * @param unknown $to_role		参与活动的人
 	 */
 	public static function moneyEntryActivesTourIncomeRmb($money,$who_role,$info,$to_role)
 	{
 	 	$money_type=self::money_type_rmb;	
		if(isset(Yii::app()->store)?Yii::app()->store->id:0)
			$manage_role=array(
				'manage_account_id'=>Yii::app()->store->id,
				'manage_account_type'=>AccountLog::manage_account_store,
			);
		else
		{
			$manage_role=array(
				'manage_account_id'=>AccountLog::fiction_role_id,
				'manage_account_type'=>AccountLog::manage_account_system,
			);
		}

		if(self::isMoney($money) && isset($who_role['account_id'],$who_role['account_type'],$to_role['to_account_id'],$to_role['to_account_type'],$info['info_id'],$info['info'],$info['name']))
		{
			//旅游活动收益
			$info['info_type']=AccountLog::info_type_entry_actives_tour_income;
			$old_model=self::getAccount($who_role['account_id'],$who_role['account_type'],$money_type);
			//旅游活动收益
			self::moneyEntryActivesTourIncome($money);
			//执行钱包操作
			if($old_model && self::moneyExecuteByPk($old_model->id))
			{
				$new_model=self::model()->findByPk($old_model->id);
				return AccountLog::createLog(
						//操作的钱
						$money,
						//操作类型 旅游活动收益
						AccountLog::funds_type_entry_actives_tour_income,
						//原来的
						self::getAccountArray($old_model),
						//更新后的
						self::getAccountArray($new_model,'after_'),
						//旅游活动收益 详情
						$info,
						//核心 进
						AccountLog::centre_status_entry,
						//谁的旅游活动收益
						array('account_id'=>$old_model->account_id, 'account_type'=>$old_model->account_type),
						//来自 用户的账户
						$to_role,
						//操作者 旅游活动收益 当前登录的商家
						$manage_role,
						//旅游活动收益 默认成功
						AccountLog::log_status_success,
						//旅游活动收益 钱类型
						$money_type
				);		
			}
		}
		return false;
 	}
 	
 	/**
 	 * 订单收入  				（供应商 代理商 运营商 平台 操作者{供应商 系统（过期）}）
 	 * @param unknown $money  收益的钱
 	 * @param unknown $who_role	谁的收益
 	 * @param unknown $info				收益详情
 	 * @param unknown $to_role		来自谁的钱
 	 */
 	public static function moneyEntryOrderIncomeRmb($money,$who_role,$info,$to_role)
 	{
 		$money_type=self::money_type_rmb;
		if(isset(Yii::app()->store)?Yii::app()->store->id:0)
			$manage_role=array(
				'manage_account_id'=>Yii::app()->store->id,
				'manage_account_type'=>AccountLog::manage_account_store,
			);
		else
		{
			$manage_role=array(
				'manage_account_id'=>AccountLog::fiction_role_id,
				'manage_account_type'=>AccountLog::manage_account_system,
			);
		}
		
		if(self::isMoney($money) && isset($who_role['account_id'],$who_role['account_type'],$to_role['to_account_id'],$to_role['to_account_type'],$info['info_id'],$info['info'],$info['name']))
		{
			//订单收益
			$info['info_type']=AccountLog::info_type_entry_order_income;
			$old_model=self::getAccount($who_role['account_id'],$who_role['account_type'],$money_type);
			//订单收益
			self::moneyEntryOrderIncome($money);
			//执行钱包操作
			if($old_model && self::moneyExecuteByPk($old_model->id))
			{
				$new_model=self::model()->findByPk($old_model->id);
				return AccountLog::createLog(
						//操作的钱
						$money,
						//操作类型 订单收益
						AccountLog::funds_type_entry_order_income,
						//原来的
						self::getAccountArray($old_model),
						//更新后的
						self::getAccountArray($new_model,'after_'),
						//订单收益 详情
						$info,
						//核心 进
						AccountLog::centre_status_entry,
						//谁的订单收益
						array('account_id'=>$old_model->account_id, 'account_type'=>$old_model->account_type),
						//来自 用户的账户
						$to_role,
						//操作者 订单收益当前登录的商家
						$manage_role,
						//订单收益 默认成功
						AccountLog::log_status_success,
						//订单收益 钱类型
						$money_type
				);
			}
		}
		return false;	
 	}
 	
 	/**
 	 * 提现失败的执行方法  需要管理员登录
 	 * @param unknown $money  提现失败的钱
 	 * @param unknown $who_role	提现的人
 	 * @param unknown $info				提现的详情  需要提现表的ID
 	 * @return Ambigous <multitype:, boolean, number, unknown>|boolean
 	 */
 	public static function moneyEntryCashFailRmb($money,$who_role,$info)
 	{
 		$money_type=self::money_type_rmb;
		$admin_id = isset(Yii::app()->admin)?Yii::app()->admin->id:0;
		
 		if(self::isMoney($money) && $admin_id && isset($who_role['account_id'],$who_role['account_type'],$info['info_id'],$info['info'],$info['name']))
 		{
 			//提现失败
 			$info['info_type']=AccountLog::info_type_entry_cash_fail;
 			$old_model=self::getAccount($who_role['account_id'],$who_role['account_type'],$money_type);
 			self::moneyEntryCashFail($money);
 			
 			if($old_model && 
 					//执行钱包操作
 					self::moneyExecuteByPk($old_model->id) && 
 					//提现失败
 					AccountLog::setLogStatus(
 							//操作的钱
 							$money,
 							$who_role['account_id'],
 							$who_role['account_type'],//账户
 							$info['info_id'], AccountLog::info_type_pending_cash_frozen,
 							AccountLog::log_status_fail//提现失败
 					)
 			)
 			{
 				$new_model=self::model()->findByPk($old_model->id);
 				return AccountLog::createLog(
 						//操作的钱
 						$money,
 						//操作类型 提现失败
 						AccountLog::funds_type_entry_cash_fail,
 						//原来的
 						self::getAccountArray($old_model),
 						//更新后的
 						self::getAccountArray($new_model,'after_'),
 						//提现的详情
 						$info,
 						//核心 进
 						AccountLog::centre_status_entry,
 						//谁提现
 						array('account_id'=>$old_model->account_id, 'account_type'=>$old_model->account_type),
 						//来自 自己的账户
 						array('to_account_id'=>$old_model->account_id,'to_account_type'=>$old_model->account_type),
 						//操作者 提现默认管理员
 						array(
 								'manage_account_id'=>$admin_id,
 								'manage_account_type'=>AccountLog::manage_account_admin,
 						),
 						//提现失败 默认成功
 						AccountLog::log_status_success,
 						//提现钱类型
 						$money_type
 				);
 			}
 		}
 		return false;
 	}
 	
 	/**
 	 * RMB 在线充值
 	 * @param unknown $money   		充值的钱
 	 * @param unknown $who_role	谁充值
 	 * @param unknown $info				充值情况
 	 * @return Ambigous <multitype:, boolean, number, unknown>|boolean
 	 */
 	public static function moneyEntryRechargeRmb($money,$who_role,$info)
 	{
 		$money_type=self::money_type_rmb;
 		if(self::isMoney($money) && isset($who_role['account_id'],$who_role['account_type'],$info['info_id'],$info['info'],$info['name']))
 		{
 			//充值详情
 			$info['info_type']=AccountLog::info_type_entry_recharge;
 			$old_model=self::getAccount($who_role['account_id'],$who_role['account_type'],$money_type);
 			//在线充值
 			self::moneyEntryRecharge($money);
 			//在线充值 执行钱包
 			if($old_model && self::moneyExecuteByPk($old_model->id))
 			{
 				$new_model=self::model()->findByPk($old_model->id);
 				return AccountLog::createLog(
 						//操作的钱
 						$money,
 						//操作类型 充值
 						AccountLog::funds_type_entry_recharge,
 						//原来的
 						self::getAccountArray($old_model),
 						//更新后的
 						self::getAccountArray($new_model,'after_'),
 						//充值的详情
 						$info,
 						//核心 进
 						AccountLog::centre_status_entry,
 						//谁充值
 						array('account_id'=>$old_model->account_id, 'account_type'=>$old_model->account_type),
 						//来自 充值默认 外部
 						array('to_account_id'=>Account::fiction_role_id,'to_account_type'=>Account::other),					
 						//操作者 充值默认 系统操作
 						array(
 								'manage_account_id'=>AccountLog::manage_account_id_system, 
 								'manage_account_type'=>AccountLog::manage_account_system
 						),
 						//充值的默认成功
 						AccountLog::log_status_success,
 						//充值钱类型
 						$money_type				
 				);
 			}
 		}
 		return false;
 	}
 	/****************************************资金流水执行操作**************************************/
 	
 	/**
 	 * 获取账户数组类型
 	 * @param unknown $model
 	 * @param string $prefix 前缀
 	 * @param unknown $attributes
 	 * @return multitype:NULL
 	 */
 	public static function getAccountArray($model,$prefix='',$attributes=array())
 	{
 		if (empty($attributes))
 			$attributes=array(
 					'count', 
 					'total', 
 					'money', 
 					'no_money', 
 					'cash_count', 
 					'pay_count', 
 					'refund_count', 
 					'consume_count',  					
 					'not_consume_count'
 			);
 		$return = array();
 		if (isset($model->count))
 		{	
 			foreach ($attributes as $attribute)
 				$return[$prefix . $attribute] = $model->$attribute;
 		}
 		
 		return $return;
 	}
}
