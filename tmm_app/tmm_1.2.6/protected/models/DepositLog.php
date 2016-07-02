<?php

/**
 * 保证金记录
 * This is the model class for table "{{deposit_log}}".
 *
 * The followings are the available columns in table '{{deposit_log}}':
 * @property string $id
 * @property string $admin_id
 * @property string $deposit_id
 * @property integer $deposit_who
 * @property string $deposit_old
 * @property string $deposit
 * @property integer $deposit_status
 * @property string $reason
 * @property string $add_time
 * @property string $up_time
 * @property integer $status
 */
class DepositLog extends CActiveRecord
{
	/*******************************保证金操作类型**********************************/
	/**
	 * 保证金操作类型 扣除
	 * @var integer
	 */
	const type_deduct=-1;
	/**
	 * 保证金操作类型 添加
	 * @var integer
	 */
	const type_add=1;
	
	/*******************************操作角色**********************************/
	/**
	 * 管理员操作
	 * @var integer
	 */
	const admin=1;
	
	/*******************************被设置的元素**********************************/
	/**
	 * 被设置 组织者
	 * @var integer
	 */
	const deposit_organizer=1;
	/**
	 * 被设置 代理商
	 * @var integer
	 */
	const deposit_agent=2;
	/**
	 * 被设置 商家
	 * @var integer
	 */
	const deposit_store=3;
	
	/*******************************我是分隔线**********************************/
	/**
	 * 解释字段 deposit_who 的含义
	 * @var array
	 */
	public static $_deposit_who=array(1=>'组织者',2=>'代理商',3=>'商家');
	/**
	 * 解释字段 deposit_who 的关联表
	 * @var array
	 */
	public static $__deposit_who=array(
			1=>'DepositLog_Organizer',			//组织者
			2=>'DepositLog_Agent',					//代理商
			3=>'DepositLog_StoreContent',			//商家
	);
	/**
	 * 解释字段 deposit_who 的关联表
	 * @var array
	 */
	public static $__type=array(
		1=>'DepositLog_User',			//组织者
		2=>'DepositLog_Agent',			//代理商
		3=>'DepositLog_StoreUser',	    //商家
	);
	/**
	 * 保证金状态 扣除保证金
	 * @var integer
	 */
	const deposit_status_deduct = -1;
	/**
	 * 保证金状态 添加保证金
	 * @var integer
	 */
	const deposit_status_add = 1;
	/**
	 * 解释字段 deposit_status 的含义
	 * @var array
	 */
	public static $_deposit_status=array(-1=>'扣除保证金',1=>'添加保证金');
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
	public static $_search_time_type=array('创建时间','更新时间');
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
		return '{{deposit_log}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
		//	array('admin_id, deposit_id, deposit_who, deposit_status, add_time, up_time', 'required'),
			array('deposit_who, deposit_status, status', 'numerical', 'integerOnly'=>true),
			array('admin_id, deposit_id', 'length', 'max'=>11),
			array('deposit,deposit_old', 'length', 'max'=>13),
			array('reason', 'length', 'max'=>200),
			array('add_time, up_time', 'length', 'max'=>10),
			
			//添加保证金记录
			array('deposit_status,deposit,reason', 'required','on'=>'create'),
			array('deposit,deposit_status,reason','safe','on'=>'create'),
			array('search_time_type,search_start_time,search_end_time,id, admin_id, deposit_id, deposit_who,deposit_old, add_time, up_time, status','unsafe','on'=>'create'),
			array('deposit','match','pattern'=>'/^[0-9]+(.[0-9]{1,2})?$/','message'=>'{attribute} 只能有二位小数','on'=>'create'),
			array('deposit_status','in','range'=>array_keys(self::$_deposit_status)),
			array('deposit,deposit_status','verify_deposit','on'=>'create'),//验证保证金是否够
				
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('search_time_type,search_start_time,search_end_time,id, admin_id, deposit_id, deposit_who, deposit,deposit_old, deposit_status, reason, add_time, up_time, status', 'safe', 'on'=>'search'),
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
				// 一部分归属组织者
				'DepositLog_Organizer'=>array(self::BELONGS_TO,'Organizer','deposit_id'),
				// 一部分归属代理商
				'DepositLog_Agent'=>array(self::BELONGS_TO,'Agent','deposit_id'),
				// 一部分归属商家
				'DepositLog_StoreContent'=>array(self::BELONGS_TO,'StoreContent','deposit_id'),
				// 操作人归属管理员
				'DepositLog_Admin'=>array(self::BELONGS_TO,'Admin','admin_id'),
				// 归属用户
				'DepositLog_User'=>array(self::BELONGS_TO,'User','deposit_id'),
				// 归属商家
				'DepositLog_StoreUser'=>array(self::BELONGS_TO,'StoreUser','deposit_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'admin_id' => '管理员',
			'deposit_id' => '保证金归属',//'保证金归属id（代理商、商家、组织者）',
			'deposit_who' => '保证金角色',//'保证金归属用户表 （代理商、商家、组织者）',
			'deposit' => '保证金',
			'deposit_old'=>'原保证金',
			'deposit_status' => '保证金类型',//'1缴纳-1扣除',
			'reason' => '备注',
			'add_time' => '创建时间',
			'up_time' => '更新时间',
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
			$criteria->with = array(
				'DepositLog_Admin'=>array('select'=>'username,name'),
				'DepositLog_User'=>array('select'=>'phone,nickname'),
				'DepositLog_Agent'=>array('select'=>'phone'),
				'DepositLog_StoreUser'=>array('select'=>'phone'),
			);
			$criteria->compare('t.status','<>-1');
			if($this->search_time_type != '' && isset($this->__search_time_type[$this->search_time_type]))
			{
				if($this->search_start_time !='' && $this->search_end_time !='')
					$criteria->addBetweenCondition('t.'.$this->__search_time_type[$this->search_time_type],strtotime($this->search_start_time),strtotime($this->search_end_time)+3600*24-1);
				elseif($this->search_start_time !='' && $this->search_end_time =='')
					$criteria->compare('t.'.$this->__search_time_type[$this->search_time_type],'>='.strtotime($this->search_start_time));
				elseif($this->search_start_time =='' && $this->search_end_time !='')
					$criteria->compare('t.'.$this->__search_time_type[$this->search_time_type],'<='.strtotime($this->search_end_time)+3600*24-1);
			}			
			$criteria->compare('t.id',$this->id,true);
			$criteria->compare('DepositLog_Admin.name',$this->admin_id,true);
			$criteria->compare('t.deposit_who',$this->deposit_who);
			if (isset( self::$__type[$this->deposit_who])) {
				$_type = self::$__type[$this->deposit_who];
				if($_type === 'DepositLog_User')
					$criteria->compare('DepositLog_User.nickname',$this->deposit_id,true);
				if($_type === 'DepositLog_Agent')
					$criteria->compare('DepositLog_Agent.phone',$this->deposit_id,true);
				if($_type === 'DepositLog_StoreUser')
					$criteria->compare('DepositLog_StoreUser.phone',$this->deposit_id,true);
			} else {
				$criteria->compare('t.deposit_id',$this->deposit_id,true);
			}
			$criteria->compare('t.deposit',$this->deposit,true);
			$criteria->compare('t.deposit_old',$this->deposit_old,true);
			$criteria->compare('t.deposit_status',$this->deposit_status);
			$criteria->compare('t.reason',$this->reason,true);
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
	 * @return DepositLog the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


	/**
	 * 根据保证金归属类型获取用户名或手机号
	 * @param $type
	 * @param $data
	 * @return string
	 */
	public static function deposit_type($type, $data) {
		if (isset( self::$__type[$type])) {
			$_type = self::$__type[$type];
			if($_type === 'DepositLog_User')
				return $data->$_type->phone . '[' . $data->$_type->nickname . ']';
			if($_type === 'DepositLog_Agent' || $_type === 'DepositLog_StoreUser')
				return $data->$_type->phone;
		} else {
			return '未知角色';
		}
	}

	/**
	 * 根据登录类型获取用户名或手机号，显示
	 * @param $mode
	 * @param $type
	 * @return string
	 */
	public static function show_type($mode, $type) {
		if (isset( self::$__type[$type])) {
			$_type = self::$__type[$type];
			if($_type === 'DepositLog_User')
				return $mode->$_type->phone . '[' . $mode->$_type->nickname . ']';
			if($_type === 'DepositLog_Agent' || $_type === 'DepositLog_StoreUser')
				return $mode->$_type->phone;
		} else {
			return '未知角色';
		}
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
	 * 验证保证金是否合法
	 */
	public function verify_deposit()
	{
		if($this->deposit_status==self::type_deduct && $this->deposit_who && $this->deposit_id)
		{
			if(isset(self::$__deposit_who[$this->deposit_who]))
			{
				$with=self::$__deposit_who[$this->deposit_who];
				if($this->$with->deposit<$this->deposit)
					$this->addError('deposit', $this->getAttributeLabel('deposit').' 扣除不能大于剩余保证金');			
			}
		}else{
			if(isset(self::$__deposit_who[$this->deposit_who]))
			{
				$with=self::$__deposit_who[$this->deposit_who];
				$count=$this->$with->deposit+$this->deposit;		
				if(strlen($count)>10)
					$this->addError('deposit', $this->getAttributeLabel('deposit').' 只能有12位');
			}
		}
	}
	
	
	
}
