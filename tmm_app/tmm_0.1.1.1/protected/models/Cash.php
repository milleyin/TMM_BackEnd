<?php

/**
 * This is the model class for table "{{cash}}".
 *
 * The followings are the available columns in table '{{cash}}':
 * @property string $id
 * @property string $cash_type
 * @property string $cash_id
 * @property string $admin_id_first
 * @property string $remark_first
 * @property string $first_time
 * @property string $admin_id_double
 * @property string $remark_double
 * @property string $double_time
 * @property string $admin_id_submit
 * @property string $remark_submit
 * @property string $submit_time
 * @property string $money
 * @property string $price
 * @property string $fee_price
 * @property string $fact_price
 * @property string $bank_card_id
 * @property string $bank_id
 * @property string $bank_name
 * @property string $bank_branch
 * @property string $bank_code
 * @property string $bank_type
 * @property string $bank_identity
 * @property integer $cash_status
 * @property integer $audit_status
 * @property integer $pay_type
 * @property string $pay_time
 * @property string $add_time
 * @property string $up_time
 * @property integer $status
 */
class Cash extends CActiveRecord
{
	/********************************谁提现 （申请人）**********************************/
// 	/**
// 	 * 管理
// 	 * @var unknown
// 	 */
// 	const cash_type_admin=1;
	/**
	 *  代理商
	 * @var unknown
	 */
	const cash_type_agent=2;
	/**
	 * 商家
	 * @var unknown
	 */
	const cash_type_store=3;
	/**
	 * 用户(组织者)
	 * @var unknown
	 */
	const cash_type_user=4;
	/**
	 * 解释字段 status 的含义
	 * @var array
	 */
	public static $_cash_type=array(2=>'代理商',3=>'商家',4=>'用户');

	public static $__cash_type=array(
			2=>'Cash_Agent',
			3=>'Cash_StoreUser',
			4=>'Cash_User',
	);
	public static $__cash_type_name=array(
			2=>'phone',
			3=>'phone',
			4=>'phone',
	);
	/**
	 * 提现申请记录===》角色钱包 状态
	 * $_cash_type ===》$_account_type
	 * @var array
	 */
	public static $_cash_type_account=array(
		//self::cash_type_admin=>Account::system,
		self::cash_type_agent=>Account::agent,
		self::cash_type_store=>Account::store,
		self::cash_type_user=>Account::user,
	);
	/********************************提现状态*********************************************/

	/**
	 * 提现状态 提现失败
	 */
	const cash_status_cash_not=-1;
	/**
	 * 提现状态 待提现
	 */
	const cash_status_cashing=0;
	/**
	 * 提现状态 已提现
	 */
	const cash_status_cash=1;

	/**
	 * 解释字段 status 的含义
	 * @var array
	 */
	public static $_cash_status=array(-1=>'提现失败',0=>'待提现',1=>'已提现');

	/********************************审核状态*********************************************/
	/**
	 * 审核状态 确认失败
	 */
	const audit_status_submit_not_pass=-3;
	/**
	 * 审核状态 待复失败
	 */
	const audit_status_double_not_pass=-2;
	/**
	 * 审核状态 初审失败
	 */
	const  audit_status_first_not_pass=-1;
	/**
	 * 审核状态 待初审
	 */
	const audit_status_first=0;
	/**
	 * 审核状态 待复审
	 */
	const audit_status_double=1;
	/**
	 * 审核状态 待确认
	 */
	const audit_status_submit=2;
	/**
	 * 审核状态 已确认
	 */
	const audit_status_finish=3;
	/**
	 * 解释字段 status 的含义
	 * @var array
	 */
	public static $_audit_status=array(-3=>'确认失败',-2=>'待复失败',-1=>'初审失败',0=>'待初审',1=>'待复审',2=>'待确认',3=>'已确认');

	/*************************支付类型 ****************************/
	/**
	 * 支付宝
	 * @var integer
	 */
	const pay_type_alipay=1;
	/**
	 * 解释字段 pay_type 的含义
	 * @var unknown
	 */
	public static $_pay_type=array(1=>'支付宝');
	/**
	 * 解释字段 status 的含义
	 * @var array
	 */
	public static $_status=array(-1=>'删除','禁用','正常');
	/**
	 * 搜索的时间类型
	 * @var array
	 */
	public $search_time_type;
	/**
	 *	解释搜索类型时间的含义 search_time_type
	 * @var string
	 */
	public static $_search_time_type=array('(初)时间','(复)时间','(确认)时间','到账时间','创建时间','更新时间');
	/**
	 *	解释搜索类型时间 search_time_type 的字段搜索
	 * @var string
	 */
	public $__search_time_type=array('first_time','double_time','submit_time','pay_time','add_time','up_time'); 
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
	 * 手机验证码
	 * @var
	 */
	public $sms;

	/**
	 * 手机
	 */
	public $phone;
	/**
	 * 是否验证
	 * @var
	 */
	public $is_validator;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{cash}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			//array('cash_type, cash_id', 'required'),
			array('cash_status, audit_status, pay_type, status', 'numerical', 'integerOnly'=>true),
			array('cash_type, cash_id, admin_id_first, admin_id_double, admin_id_submit, bank_card_id, bank_id, bank_type', 'length', 'max'=>11),
			array('remark_first, remark_double, remark_submit, bank_branch', 'length', 'max'=>100),
			array('first_time, double_time, submit_time, pay_time, add_time, up_time', 'length', 'max'=>10),
			array('money, price, fee_price, fact_price', 'length', 'max'=>13),
			array('bank_name', 'length', 'max'=>20),
			array('bank_code', 'length', 'max'=>50),
			array('bank_identity', 'length', 'max'=>18),
				
			array('remark_first,remark_double,remark_submit', 'length','min'=>10,'max'=>25),
			//验证钱
			array('money,price,fee_price,fact_price','ext.Validator.Validator_money'),
			//array('','safe','on'=>'create,update'),
			//array('','unsafe','on'=>'create,update'),
			// 商家提现
			array('phone,sms,price','safe','on'=>'store_create'),
			array('search_time_type,search_start_time,search_end_time,id, cash_type, cash_id, admin_id_first, remark_first, first_time, admin_id_double, remark_double, double_time, admin_id_submit, remark_submit, submit_time, money, fee_price, fact_price, bank_card_id, bank_id, bank_name, bank_branch, bank_code, bank_type, bank_identity, cash_status, audit_status, pay_type, pay_time, add_time, up_time, status', 'unsafe', 'on'=>'store_create'),
			// 用户提现
			array('phone,sms,price','safe','on'=>'user_create'),
			array('search_time_type,search_start_time,search_end_time,id, cash_type, cash_id, admin_id_first, remark_first, first_time, admin_id_double, remark_double, double_time, admin_id_submit, remark_submit, submit_time, money, fee_price, fact_price, bank_card_id, bank_id, bank_name, bank_branch, bank_code, bank_type, bank_identity, cash_status, audit_status, pay_type, pay_time, add_time, up_time, status', 'unsafe', 'on'=>'user_create'),

			// 提现====验证价格
			array('price','safe','on'=>'cash_price'),
			array('phone,sms,search_time_type,search_start_time,search_end_time,id, cash_type, cash_id, admin_id_first, remark_first, first_time, admin_id_double, remark_double, double_time, admin_id_submit, remark_submit, submit_time, money, fee_price, fact_price, bank_card_id, bank_id, bank_name, bank_branch, bank_code, bank_type, bank_identity, cash_status, audit_status, pay_type, pay_time, add_time, up_time, status', 'unsafe', 'on'=>'cash_price'),

			// 代理商提现
			array('phone,sms','safe','on'=>'agent_create'),
			array('search_time_type,search_start_time,search_end_time,id, cash_type, cash_id, admin_id_first, remark_first, first_time, admin_id_double, remark_double, double_time, admin_id_submit, remark_submit, submit_time, money, price, fee_price, fact_price, bank_card_id, bank_id, bank_name, bank_branch, bank_code, bank_type, bank_identity, cash_status, audit_status, pay_type, pay_time, add_time, up_time, status', 'unsafe', 'on'=>'agent_create'),

			//结算申请（初审）
			array('remark_first', 'required','on'=>'pass_firets'),
			array('remark_first','safe','on'=>'pass_firets'),
			array('search_time_type,search_start_time,search_end_time,id, cash_type, cash_id, admin_id_first, first_time, admin_id_double, remark_double, double_time, admin_id_submit, remark_submit, submit_time, money, price, fee_price, fact_price, bank_card_id, bank_id, bank_name, bank_branch, bank_code, bank_type, bank_identity, cash_status, audit_status, pay_type, pay_time, add_time, up_time, status','unsafe','on'=>'pass_firets'),

			//结算申请（复审）
			array('remark_double', 'required','on'=>'pass_doubles'),
			array('remark_double','safe','on'=>'pass_doubles'),
			array('search_time_type,search_start_time,search_end_time,id, cash_type, cash_id, admin_id_first, remark_first, first_time, admin_id_double, double_time, admin_id_submit, remark_submit, submit_time, money, price, fee_price, fact_price, bank_card_id, bank_id, bank_name, bank_branch, bank_code, bank_type, bank_identity, cash_status, audit_status, pay_type, pay_time, add_time, up_time, status','unsafe','on'=>'pass_doubles'),

			//结算申请（确认）
			array('remark_submit,fee_price,price', 'required','on'=>'pass_submits'),
			array('remark_submit,fee_price,price','safe','on'=>'pass_submits'),
			array('fee_price,price', 'authenticate_price','on'=>'pass_submits'),
			//验证钱
			array('fee_price,price','ext.Validator.Validator_money','on'=>'pass_submits'),
			array('search_time_type,search_start_time,search_end_time,id, cash_type, cash_id, admin_id_first, remark_first, first_time, admin_id_double, remark_double, double_time, admin_id_submit, submit_time, money, fact_price, bank_card_id, bank_id, bank_name, bank_branch, bank_code, bank_type, bank_identity, cash_status, audit_status, pay_type, pay_time, add_time, up_time, status','unsafe','on'=>'pass_submits'),

			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('search_time_type,search_start_time,search_end_time,id, cash_type, cash_id, admin_id_first, remark_first, first_time, admin_id_double, remark_double, double_time, admin_id_submit, remark_submit, submit_time, money, price, fee_price, fact_price, bank_card_id, bank_id, bank_name, bank_branch, bank_code, bank_type, bank_identity, cash_status, audit_status, pay_type, pay_time, add_time, up_time, status', 'safe', 'on'=>'search'),
		);
	}

	public function authenticate_price(){

		if($this->money != $this->price  )
			$this->addError('price','提现金额应等于 申请金额');

		if($this->money < $this->fee_price)
			$this->addError('fee_price','提现费用不能大于 申请金额');

		if( ($this->price - $this->fee_price) < 0  )
			$this->addError('fee_price','实际到账金额不能小于 0');

	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			//管理员初审核
			'CashFirst_Admin'=>array(self::BELONGS_TO,'Admin','admin_id_first'),
			//管理员复审核
			'CashDouble_Admin'=>array(self::BELONGS_TO,'Admin','admin_id_double'),
			//管理员确认
			'CashSubmit_Admin'=>array(self::BELONGS_TO,'Admin','admin_id_submit'),
			// 关联银行表
			'Cash_Bank'=>array(self::BELONGS_TO,'Bank','bank_id'),
			//代理商
			'Cash_Agent'=>array(self::BELONGS_TO,'Agent','cash_id'),		
			//用户		
			'Cash_User'=>array(self::BELONGS_TO,'User','cash_id'),
			//商家
			'Cash_StoreUser'=>array(self::BELONGS_TO,'StoreUser','cash_id'),
				
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'cash_type' => '角色类型',
			'cash_id' => '角色账号',
			'admin_id_first' => '初审人',
			'remark_first' => '初审备注',
			'first_time' => '初审时间',
			'admin_id_double' => '复审人',
			'remark_double' => '复审备注',
			'double_time' => '复审时间',
			'admin_id_submit' => '确认人',
			'remark_submit' => '确认备注',
			'submit_time' => '确认时间',
			'money' => '提现金额',
			'price' => '实际金额',
			'fee_price' => '提现费用',
			'fact_price' => '到账金额',
			'bank_card_id' => '绑定银行',
			'bank_id' => '开户银行',
			'bank_name' => '开户姓名',
			'bank_branch' => '开户支行',
			'bank_code' => '开户账号',
			'bank_type' => '银行类型',
			'bank_identity' => '开户身份证',
			'cash_status' => '付款状态',
			'audit_status' => '审核状态',
			'pay_type' => '提现类型',
			'pay_time' => '到账时间',
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
			$criteria->compare('t.status','<>-1');
			$criteria->with=array(
				// 关联管理员表
				'CashFirst_Admin'=>array('select'=>'id,name') ,
				'CashDouble_Admin'=>array('select'=>'id,name'),
				'CashSubmit_Admin'=>array('select'=>'id,name'),
				//代理商
				'Cash_Agent'=>array('select'=>'phone'),
				//用户
				'Cash_User'=>array('select'=>'phone'),
				//商家
				'Cash_StoreUser'=>array('select'=>'phone'),
				'Cash_Bank'
			);
			if($this->search_time_type != '' && isset($this->__search_time_type[$this->search_time_type]))
			{
				if($this->search_start_time !='' && $this->search_end_time !='')
					$criteria->addBetweenCondition('t'.$this->__search_time_type[$this->search_time_type],strtotime($this->search_start_time),strtotime($this->search_end_time)+3600*24-1);
				elseif($this->search_start_time !='' && $this->search_end_time =='')
					$criteria->compare('t'.$this->__search_time_type[$this->search_time_type],'>='.strtotime($this->search_start_time));
				elseif($this->search_start_time =='' && $this->search_end_time !='')
					$criteria->compare('t'.$this->__search_time_type[$this->search_time_type],'<='.strtotime($this->search_end_time)+3600*24-1);
			}
			$criteria->compare('t.id',$this->id,true);
						
			//提现的角色
			$criteria_=false;
			if(isset(self::$_cash_type[$this->cash_type],self::$__cash_type[$this->cash_type],self::$__cash_type_name[$this->cash_type]))
			{
				$relation=self::$__cash_type[$this->cash_type];
				$couditions = array();
				$couditions[]='`t`.`cash_id`=:cash_id';
				$criteria->params[':cash_id']=$this->cash_id;
				$couditions[]=$relation.'.'.self::$__cash_type_name[$this->cash_type].' LIKE :like_cash_id';
				$criteria->addCondition( implode(' OR ', $couditions));
				$criteria->params[':like_cash_id']='%'.strtr($this->cash_id,array('%'=>'\%', '_'=>'\_', '\\'=>'\\\\')).'%';
				$criteria_=true;			
			}
			if(! $criteria_ && $this->cash_id != null)
			{
				$relations=self::$__cash_type;
				$criteria->params[':cash_id']=$this->cash_id;
				$couditions = array();
				$couditions[]='`t`.`cash_id`=:cash_id';
				foreach ($relations as $type=>$relation)
				{
					if(isset(self::$__cash_type_name[$type]))
					$couditions[]='(`t`.`cash_type`='.$type.' AND `'.$relation.'`.`'.self::$__cash_type_name[$type].'` LIKE :like_cash_id)';
				}
				$criteria->addCondition( implode(' OR ', $couditions));
				$criteria->params[':like_cash_id']='%'.strtr($this->cash_id,array('%'=>'\%', '_'=>'\_', '\\'=>'\\\\')).'%';			
			}else 
				$criteria->compare('t.cash_id',$this->cash_id,true);
			$criteria->compare('t.cash_type',$this->cash_type,true);
					
			$criteria->compare('CashFirst_Admin.name',$this->admin_id_first,true);
			$criteria->compare('t.remark_first',$this->remark_first,true);
			if($this->first_time != '')
				$criteria->addBetweenCondition('t.first_time',strtotime($this->first_time),(strtotime($this->first_time)+3600*24-1));
			$criteria->compare('CashDouble_Admin.name',$this->admin_id_double,true);
			$criteria->compare('t.remark_double',$this->remark_double,true);
			if($this->double_time != '')
				$criteria->addBetweenCondition('t.double_time',strtotime($this->double_time),(strtotime($this->double_time)+3600*24-1));
			$criteria->compare('CashSubmit_Admin.name',$this->admin_id_submit,true);
			$criteria->compare('t.remark_submit',$this->remark_submit,true);
			if($this->submit_time != '')
				$criteria->addBetweenCondition('t.submit_time',strtotime($this->submit_time),(strtotime($this->submit_time)+3600*24-1));
			$criteria->compare('t.money',$this->money,true);
			$criteria->compare('t.price',$this->price,true);
			$criteria->compare('t.fee_price',$this->fee_price,true);
			$criteria->compare('t.fact_price',$this->fact_price,true);
			$criteria->compare('t.bank_card_id',$this->bank_card_id,true);
			$criteria->compare('Cash_Bank.name',$this->bank_id,true);
			$criteria->compare('t.bank_name',$this->bank_name,true);
			$criteria->compare('t.bank_branch',$this->bank_branch,true);
			$criteria->compare('t.bank_code',$this->bank_code,true);
			$criteria->compare('t.bank_type',$this->bank_type,true);
			$criteria->compare('t.bank_identity',$this->bank_identity,true);
			$criteria->compare('t.cash_status',$this->cash_status);
			$criteria->compare('t.audit_status',$this->audit_status);
			$criteria->compare('t.pay_type',$this->pay_type);
			if($this->pay_time != '')
				$criteria->addBetweenCondition('t.pay_time',strtotime($this->pay_time),(strtotime($this->pay_time)+3600*24-1));
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
	 * 代理商提取记录
	 * @param string $criteria
	 * @return CActiveDataProvider
	 */
	public function search_agent($criteria='')
	{
		// @todo Please modify the following code to remove attributes that should not be searched.
		if($criteria ===''){
			$criteria=new CDbCriteria;
			$criteria->compare('status','<>-1');
			$criteria->addColumnCondition(array(
				'cash_type'=>Cash::cash_type_agent,
				'cash_id'=>Yii::app()->agent->id,
			));

			$this->search_time_type = 4;
			if($this->search_time_type != '' && isset($this->__search_time_type[$this->search_time_type]))
			{
				if($this->search_start_time !='' && $this->search_end_time !='')
					$criteria->addBetweenCondition($this->__search_time_type[$this->search_time_type],strtotime($this->search_start_time),strtotime($this->search_end_time)+3600*24-1);
				elseif($this->search_start_time !='' && $this->search_end_time =='')
					$criteria->compare($this->__search_time_type[$this->search_time_type],'>='.strtotime($this->search_start_time));
				elseif($this->search_start_time =='' && $this->search_end_time !='')
					$criteria->compare($this->__search_time_type[$this->search_time_type],'<='.strtotime($this->search_end_time)+3600*24-1);
			}
			$criteria->compare('audit_status',$this->audit_status);
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
	 * @return Cash the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/**
	 * 保存之前的操作
	 * @see CActiveRecord::beforeSave()
	 */
	public function beforeSave(){
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
	 * 与cash_id 分类ID AuditLog 分类ID绑定
	 * @param $val
	 * @return int|string
	 */
	public static function audit_element($val){
		//	public static $_cash_type=array(1=>'商家',2=>'用户',3=>'组织者',4=>'代理商');
		$element = '';
		switch($val){
			case 1:
				$element = AuditLog::admin;break;
			case 2:
				$element = AuditLog::cash_agent;break;
			case 3:
				$element = AuditLog::cash_store;break;
			case 4:
				$element = AuditLog::cash_user;break;
		}
		return $element;
	}

	/**
	 * 提现申请记录列表
	 * @param $cash_id
	 * @param $cash_type
	 * @return array
	 */
	public static function cash_index($cash_id,$cash_type){

		$criteria = new CDbCriteria;
		$criteria->addCondition(' `cash_type`=:cash_type AND `cash_id`=:cash_id');

		$criteria->params[':cash_type'] = $cash_type;
		$criteria->params[':cash_id'] = $cash_id;

		$criteria->order = ' id desc';
		$count = self::model()->count($criteria);

		$return = array();

		//分页设置
		$return['page'] = Yii::app()->controller->page($criteria, $count, Yii::app()->params['api_pageSize']['user_account_cash_list'], Yii::app()->params['app_api_domain']);
		//根据条件查询
		$model = self::model()->findAll($criteria);
		foreach($model as $log) {
			$return['list_data'][] = array(
				'id'=>$log->id,
				// 提现金额
				'price' =>number_format($log->price,2),
				// 备注(初)
				'remark_first' => CHtml::encode($log->remark_first),
				// 备注(复)
				'remark_double' => CHtml::encode($log->remark_double),
				// 备注(确认)
				'remark_submit' => CHtml::encode($log->remark_submit),
				//审核失败原因
				'remark_error'  => self::cash_remark_error($log->audit_status,$log),
				// 付款状态
				'cash_status'=> array(
					'value' => $log->cash_status,
					'name' => self::$_cash_status[$log->cash_status],
				),
				// 审核状态
				'audit_status'=> array(
					'value' => $log->audit_status,
					'name' => self::$_audit_status[$log->audit_status],
				),
				'add_time' => Yii::app()->format->datetime($log->add_time),
				'up_time' => Yii::app()->format->datetime($log->up_time),
			);
		}

		if(empty($return['list_data']))
		{
			$return['list_data']=array();
			$return['null']='暂无数据！';
		}

		return $return;
	}
	
	/**
	 * 获取角色的名称
	 * @param unknown $model
	 * @param unknown $type
	 * @param string $attribute
	 * @return string
	 */
	public static function getRoleName($model,$type)
	{
		if(isset(self::$_cash_type[$type],self::$__cash_type[$type],self::$__cash_type_name[$type]))
		{
			$relation=self::$__cash_type[$type];
			$attribute=self::$__cash_type_name[$type];
			return isset($model->$relation->$attribute) ? $model->$relation->$attribute : '未知';
		}
		return '未知';
	}

	/**
	 * 返回错误 信息
	 * @param $cash_status
	 * @param $log
	 * @return string
	 */
	private static function cash_remark_error($cash_status,$log){

		switch($cash_status){
			case self::audit_status_first_not_pass :
				$remark_error = CHtml::encode($log->remark_first);break;
			case self::audit_status_double_not_pass :
				$remark_error = CHtml::encode($log->remark_double);break;
			case self::audit_status_submit_not_pass :
				$remark_error = CHtml::encode($log->remark_submit);break;
			default :
				$remark_error = '';break;
		}
		return $remark_error;
	}

	/**
	 * 验证手机短信
	 * 商家提现
	 */
	public function verifycode_cash($id)
	{
		if(! $this->is_validator)
			$this->validate();
		if(!$this->hasErrors())
		{
			Yii::import('ext.Send_sms.Send_sms');
			$params=array(
				'sms_id'=>$id,
				'sms_type'=>SmsLog::sms_store,
				'role_id'=>$id,
				'role_type'=>SmsLog::send_store,
				'sms_use'=>SmsLog::use_cash,
			);
			if (Send_sms::verifycode($this->phone, $params, $this->sms))
				return true;
		}
		$this->addError('sms', '手机验证码 错误');
		return false;
	}
	/**
	 * 验证手机短信
	 * 用户提现
	 */
	public function verifycode_cash_user($id)
	{
		if(! $this->is_validator)
			$this->validate();
		if(!$this->hasErrors())
		{
			Yii::import('ext.Send_sms.Send_sms');
			$params=array(
				'sms_id'=>$id,
				'sms_type'=>SmsLog::sms_user,
				'role_id'=>$id,
				'role_type'=>SmsLog::sms_user,
				'sms_use'=>SmsLog::use_cash,
			);
			if (Send_sms::verifycode($this->phone, $params, $this->sms))
				return true;
		}

		$this->addError('sms', '手机验证码 错误');
		return false;
	}

	/**
	 * 验证手机短信
	 * 代理商提现
	 */
	public function verifycode_agent_cash($id)
	{
		if(! $this->is_validator)
			$this->validate();
		if(!$this->hasErrors())
		{
			Yii::import('ext.Send_sms.Send_sms');
			$params=array(
				'sms_id'=>$id,
				'sms_type'=>SmsLog::sms_agent,
				'role_id'=>$id,
				'role_type'=>SmsLog::send_agent,
				'sms_use'=>SmsLog::use_bank,
			);
			if (Send_sms::verifycode($this->phone, $params, $this->sms))
				return true;
		}
		$this->addError('sms', '手机验证码 错误');
		return false;
	}

}
