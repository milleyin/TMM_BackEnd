<?php

/**
 * This is the model class for table "{{password}}".
 *
 * The followings are the available columns in table '{{password}}':
 * @property string $id
 * @property integer $role_type
 * @property string $role_id
 * @property integer $manage_type
 * @property string $manage_id
 * @property integer $password_type
 * @property string $password
 * @property string $salt
 * @property string $use_count
 * @property string $up_count
 * @property string $error_count
 * @property string $use_error
 * @property string $use_ip
 * @property string $use_time
 * @property string $use_address
 * @property string $last_ip
 * @property string $last_time
 * @property string $last_address
 * @property string $add_time
 * @property string $up_time
 * @property integer $centre_status
 * @property integer $status
 */
class Password extends CActiveRecord
{
	/*****************************************归属密码角色***************************************************************/
	/**
	 * 管理员
	 * @var unknown
	 */
	const role_type_admin = 0;
	/**
	 * 代理商
	 * @var integer
	 */
	const role_type_agent = 1;
	/**
	 * 商家
	 * @var integer
	 */
	const role_type_store = 2;
	/**
	 *	用户(组织者)
	 * @var integer
	 */
	const role_type_user = 4;
	/**
	 * 解释字段 role_type 的含义
	 * @var array
	 */
	public static $_role_type=array(
			self::role_type_admin =>'管理员',
			self::role_type_agent=>'代理商',
			self::role_type_store=>'商家',
			self::role_type_user=>'用户',
	);
	/**
	 * 解释字段 role_type 的相关数据模型名
	 * @var unknown
	 */
	public static $_role_type_model=array(
			self::role_type_admin =>'Admin',
			self::role_type_agent=>'Agent',
			self::role_type_store=>'StoreUser',
			self::role_type_user=>'User',
	);
	/**
	 * 解释字段 role_type 的关联关系
	 * @var unknown
	*/
	public static $__role_type=array(
			self::role_type_admin=>'Password_Admin',
			self::role_type_agent=>'Password_Agent',
			self::role_type_store=>'Password_StoreUser',
			self::role_type_user=>'Password_User',
	);
	/**
	 * 角色的名称
	 * @var unknown
	 */
	public static $_role_name=array(
			self::role_type_admin=>'username',
			self::role_type_agent=>'phone',
			self::role_type_store=>'phone',
			self::role_type_user=>'phone',
	);
	/*****************************************最后操作密码***************************************************************/
	/**
	 * admin 管理员
	 * @var integer
	 */
	const manage_type_admin=self::role_type_admin;
	/**
	 * 代理商
	 * @var integer
	 */
	const manage_type_agent=self::role_type_agent;
	/**
	 * 商家
	 * @var integer
	 */
	const manage_type_store=self::role_type_store;
	/**
	 *	用户(组织者)
	 * @var integer
	 */
	const manage_type_user=self::role_type_user;

	/**
	 * 解释字段 manage_type 的含义
	 * @var array
	 */
	public static $_manage_type=array(
			self::manage_type_admin=>'管理员',
			self::manage_type_agent=>'代理商',
			self::manage_type_store=>'商家',
			self::manage_type_user=>'用户',
	);
	/**
	 * 解释字段 manage_type 的关联关系
	 * @var unknown
	*/
	public static $__manage_type=array(
			self::manage_type_admin=>'Password_Admin_Manage',
			self::manage_type_agent=>'Password_Agent_Manage',
			self::manage_type_store=>'Password_StoreUser_Manage',
			self::manage_type_user=>'Password_User_Manage',
	);
	/**
	 * 与回滚错误日志 对应操作者
	 * @var unknown
	 */
	public static $_manage_type_error_log=array(
			self::manage_type_admin=>ErrorLog::admin,
			self::manage_type_agent=>ErrorLog::operator,
			self::manage_type_store=>ErrorLog::store,
			self::manage_type_user=>ErrorLog::user,
	);
	/**
	 * 操作角色的名称
	 * @var array
	 */
	public static $_manage_role_name=array(
			self::manage_type_admin=>'username',
			self::manage_type_agent=>'phone',
			self::manage_type_store=>'phone',
			self::manage_type_user=>'phone',
	);
	/*****************************************密码类型***************************************************************/
	/**
	 * 密码类型 支付密码
	 * @var integer
	 */
	const password_type_pay=0;
	/**
	 * 解释字段 password_type 的含义
	 * @var array
	 */
	public static $_password_type = array('支付密码');
	/**
	 * 密码短信的类型
	 * @var unknown
	 */
	public static $_password_type_sms = array(
		self::password_type_pay=>SmsLog::use_pay_password, //支付密码
	);
	/**
	 * 秘密加密的方法名
	 * @var array
	 */
	public static $__password_type=array(
		self::password_type_pay=>'encryptPay',
	);
	/**
	 *	密码错误次数限制 0 不限制
	 * @var unknown
	 */
	public static $_use_error_limit=array(
		self::password_type_pay=>3,
	);
	/**
	 * 创建密码的类型
	 * @var array
	 */
	public static $_password_type_create=array(
			self::password_type_pay=>'create_pay',
	);
	/**
	 * 修改密码的类型
	 * @var array
	 */
	public static $_password_type_update=array(
			self::password_type_pay=>'update_pay',
	);
	/**
	 * 验证密码的类型
	 * @var array
	 */
	public static $_password_type_validate=array(
			self::password_type_pay=>'validate_pay',
	);
	/*****************************************核心状态 （预留）***************************************************************/
	/**
	 * 无效的
	 * @var integer
	 */
	const centre_status_invalid=0;
	/**
	 * 有效的
	 * @var integer
	 */
	const centre_status_valid=1;
	/**
	 * 解释字段 centre_status 的含义
	 * @var array
	 */
 	public static $_centre_status=array('无效的','有效的');
 	
 	/*****************************************记录状态 ***************************************************************/
 	/**
 	 * 删除
 	 * @var integer
 	 */
 	const cstatus_del=1;
 	/**
 	 * 禁用
 	 * @var integer
 	 */
 	const status_disabled=1;
 	/**
 	 * 正常
 	 * @var integer
 	 */
 	const status_normal=1;
	/**
	 * 解释字段 status 的含义
	 * @var array
	 */
	public static $_status=array(-1=>'删除','禁用','正常');
	/*****************************************验证通过***************************************************************/
	/**
	 * 验证通过
	 * @var integer
	 */
	const password_pass = 1;
	
	/**
	 * 搜索的时间类型
	 * @var array
	 */
	public $search_time_type;
	/**
	 *	解释搜索类型时间的含义 search_time_type
	 * @var string
	 */
	public static $_search_time_type = array('上次时间','最近时间','添加时间','更新时间'); 
	/**
	 *	解释搜索类型时间 search_time_type 的字段搜索
	 * @var string
	 */
	public $__search_time_type = array('use_time','last_time','add_time','up_time'); 
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
	 * 密码
	 * @var unknown
	 */
	public $_pwd;
	/**
	 * 确认密码
	 * @var unknown
	 */
	public $_confirm_pwd;
	/**
	 * 短信
	 * @var unknown
	 */
	public $sms;
	/**
	 * 是否验证
	 * @var unknown
	 */
	public $is_validator;
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{password}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			//array('role_type, role_id, manage_type, manage_id', 'required'),
			array('role_type, manage_type, password_type, centre_status, status,use_time, last_time, add_time, up_time', 'numerical', 'integerOnly'=>true),
			array('role_id, manage_id, use_count, up_count, error_count, use_error', 'length', 'max'=>11),
			array('password, use_address, last_address', 'length', 'max'=>128),
			array('use_ip, last_ip', 'length', 'max'=>15),
			array('use_time, last_time, add_time, up_time', 'length', 'max'=>10),
			//角色类型
			array('role_type','in','range'=>array_keys(self::$_role_type)),
			//操作角色类型
			array('manage_type','in','range'=>array_keys(self::$_manage_type)),
			//密码类型
			array('password_type','in','range'=>array_keys(self::$_password_type)),
			//核心状态
			array('centre_status','in','range'=>array_keys(self::$_centre_status)),
			//状态
			array('status','in','range'=>array_keys(self::$_status)),
			//更新
			array('_pwd,_confirm_pwd', 'required','on'=>'create,update'),
			array('_pwd,_confirm_pwd','match','pattern'=>'/^\d{6}$/','message'=>'{attribute} 只能是6位数字组成','on'=>'create,update'),
			array('_confirm_pwd', 'compare', 'compareAttribute'=>'_pwd','on'=>'create,update'),
			array('_pwd,_confirm_pwd','safe','on'=>'create,update'),
			array('search_time_type,search_start_time,search_end_time,id, role_type, role_id, manage_type, manage_id, password,password_type,salt, use_count, up_count, error_count, use_error, use_ip, use_time, use_address, last_ip, last_time, last_address, add_time, up_time, centre_status, status','unsafe','on'=>'create,update'),

			//支付密码 创建
			array('_pwd,_confirm_pwd', 'required','on'=>'create_pay,update_pay'),
			array('_pwd,_confirm_pwd','match','pattern'=>'/^\d{6}$/','message'=>'{attribute} 只能是6位数字组成','on'=>'create_pay,update_pay'),
			array('_confirm_pwd', 'compare', 'compareAttribute'=>'_pwd','on'=>'create_pay,update_pay'),
			array('_pwd,_confirm_pwd','safe','on'=>'create_pay,update_pay'),
			array('search_time_type,search_start_time,search_end_time,id, role_type, role_id, manage_type, manage_id, password,password_type,salt, use_count, up_count, error_count, use_error, use_ip, use_time, use_address, last_ip, last_time, last_address, add_time, up_time, centre_status, status','unsafe','on'=>'create_pay,update_pay'),
			
			//验证密码短信
			array('sms','is_validator','on'=>'validate_sms'),
			array('sms','required','on'=>'validate_sms'),
			array('sms','safe','on'=>'validate_sms'),
			array('password, salt, search_time_type,search_start_time,search_end_time,id, role_type, role_id, manage_type, manage_id, password_type, use_count, up_count, error_count, use_error, use_ip, use_time, use_address, last_ip, last_time, last_address, add_time, up_time, centre_status, status', 'unsafe', 'on'=>'validate_sms'),

			//验证密码类型 validate_pay
			array('_pwd','required','on'=>'validate,validate_pay'),
			array('_pwd','safe','on'=>'validate,validate_pay'),
			array('_pwd','match','pattern'=>'/^\d{6}$/','message'=>'{attribute} 只能是6位数字组成','on'=>'validate,validate_pay'),
			array('sms,password,salt,search_time_type,search_start_time,search_end_time,id, role_type, role_id, manage_type, manage_id, password_type, use_count, up_count, error_count, use_error, use_ip, use_time, use_address, last_ip, last_time, last_address, add_time, up_time, centre_status, status', 'unsafe', 'on'=>'validate,validate_pay'),
			
			//选择创建密码
			array('password_type','required','on'=>'select'),
			array('password_type', 'safe', 'on'=>'select'),
			array('sms, password, salt, search_time_type, search_start_time, search_end_time, id, role_type, role_id, manage_type, manage_id, use_count, up_count, error_count, use_error, use_ip, use_time, use_address, last_ip, last_time, last_address, add_time, up_time, centre_status, status', 'unsafe', 'on'=>'select'),
				
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			// 不能搜索的 password,salt
			array('search_time_type,search_start_time,search_end_time,id, role_type, role_id, manage_type, manage_id, password_type, use_count, up_count, error_count, use_error, use_ip, use_time, use_address, last_ip, last_time, last_address, add_time, up_time, centre_status, status', 'safe', 'on'=>'search'),
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
				//归属管理员
				'Password_Admin'=>array(self::BELONGS_TO,'Admin','role_id'),
				//归属代理商
				'Password_Agent'=>array(self::BELONGS_TO,'Agent','role_id'),
				//归属商家
				'Password_StoreUser'=>array(self::BELONGS_TO,'StoreUser','role_id'),
				//归属用户
				'Password_User'=>array(self::BELONGS_TO,'User','role_id'),
				//操作人 管理员
				'Password_Admin_Manage'=>array(self::BELONGS_TO,'Admin','manage_id'),
				//操作人 代理商
				'Password_Agent_Manage'=>array(self::BELONGS_TO,'Agent','manage_id'),
				//操作人 商家
				'Password_StoreUser_Manage'=>array(self::BELONGS_TO,'StoreUser','manage_id'),
				//操作人 用户
				'Password_User_Manage'=>array(self::BELONGS_TO,'User','manage_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'role_type' => '角色类型',
			'role_id' => '角色账号',
			'manage_type' => '操作角色类型',
			'manage_id' => '操作角色',
			'password_type' => '密码类型',
			'password' => '密码',
			'salt' => '盐密码',
			'use_count' => '通过次数',
			'up_count' => '修改次数',
			'error_count' => '错误统计',
			'use_error' => '单次错误',
			'use_ip' => '上次IP',
			'use_time' => '上次时间',
			'use_address' => '上次地址',
			'last_ip' => '最近IP',
			'last_time' => '最近时间',
			'last_address' => '最近地址',
			'add_time' => '创建时间',
			'up_time' => '更新时间',
			'centre_status' => '核心状态',
			'status' => '状态',
			'search_time_type'=>'时间类型',
			'search_start_time'=>'开始时间',
			'search_end_time'=>'结束时间',
			'_pwd'=>'密码',
			'_confirm_pwd'=>'确认密码',
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
		if($criteria ==='')
		{
			$criteria=new CDbCriteria;
			$criteria->with=array(
					//归属管理员
					'Password_Admin'=>array('select'=>'username'),
					//归属代理商
					'Password_Agent'=>array('select'=>'phone'),
					//归属商家
					'Password_StoreUser'=>array('select'=>'phone'),
					//归属用户
					'Password_User'=>array('select'=>'phone'),
					//操作人 管理员
					'Password_Admin_Manage'=>array('select'=>'username'),
					//操作人 代理商
					'Password_Agent_Manage'=>array('select'=>'phone'),
					//操作人 商家
					'Password_StoreUser_Manage'=>array('select'=>'phone'),
					//操作人 用户
					'Password_User_Manage'=>array('select'=>'phone'),
			);
			if ($this->status != -1)
				$criteria->compare('t.status','<>-1');
			if($this->search_time_type != '' && isset($this->__search_time_type[$this->search_time_type]))
			{
				if($this->search_start_time !='' && $this->search_end_time !='')
					$criteria->addBetweenCondition('t.'.$this->__search_time_type[$this->search_time_type],strtotime($this->search_start_time),strtotime($this->search_end_time)+3600*24-1);
				elseif($this->search_start_time !='' && $this->search_end_time =='')
					$criteria->compare('t.'.$this->__search_time_type[$this->search_time_type],'>='.strtotime($this->search_start_time));
				elseif($this->search_start_time =='' && $this->search_end_time !='')
					$criteria->compare('t.'.$this->__search_time_type[$this->search_time_type],'<=' . (strtotime($this->search_end_time)+3600*24-1));
			}			
			$criteria->compare('t.id',$this->id,true);
			//归属者
			if (isset(self::$_role_type[$this->role_type],self::$__role_type[$this->role_type],self::$_role_name[$this->role_type]))
			{
				$relation = self::$__role_type[$this->role_type];		
				$name = self::$_role_name[$this->role_type];
				$couditions = array();
				$couditions[] = '`t`.`role_id`=:role_id';
				$criteria->params[':role_id']=$this->role_id;				
				$couditions[]=$relation.'.'.$name.' LIKE :like_role_id';
				$criteria->addCondition( implode(' OR ', $couditions));
				$criteria->params[':like_role_id']='%'.strtr($this->role_id,array('%'=>'\%', '_'=>'\_', '\\'=>'\\\\')).'%';
			}
			else if ($this->role_id != NULL)
			{
				$relations=self::$__role_type;
				$couditions = array();
				$couditions[]='`t`.`role_id`=:role_id';
				$criteria->params[':role_id']=$this->role_id;
				foreach ($relations as $type=>$relation)
				{
					if (isset(self::$_role_name[$type]))
						$couditions[]='(`t`.`role_type`='.$type.' AND `'.$relation.'`.`'.self::$_role_name[$type].'` LIKE :like_role_id)';
				}
				$criteria->addCondition( implode(' OR ', $couditions));
				$criteria->params[':like_role_id']='%'.strtr($this->role_id,array('%'=>'\%', '_'=>'\_', '\\'=>'\\\\')).'%';				
			}
			$criteria->compare('t.role_type',$this->role_type);
			//操作者
			if (isset(self::$_manage_type[$this->manage_type],self::$__manage_type[$this->manage_type],self::$_manage_role_name[$this->manage_type]))
			{
				$relation = self::$__manage_type[$this->manage_type];
				$name = self::$_manage_role_name[$this->manage_type];
				$couditions = array();
				$couditions[] = '`t`.`manage_id`=:manage_id';
				$criteria->params[':manage_id']=$this->manage_id;
				$couditions[]=$relation.'.'.$name.' LIKE :like_manage_id';
				$criteria->addCondition( implode(' OR ', $couditions));
				$criteria->params[':like_manage_id']='%'.strtr($this->manage_id,array('%'=>'\%', '_'=>'\_', '\\'=>'\\\\')).'%';
			}
			else if ($this->manage_id != NULL)
			{
				$relations=self::$__manage_type;
				$couditions = array();
				$couditions[]='`t`.`manage_id`=:manage_id';
				$criteria->params[':manage_id']=$this->manage_id;
				foreach ($relations as $type=>$relation)
				{
					if (isset(self::$_manage_role_name[$type]))
						$couditions[]='(`t`.`manage_type`='.$type.' AND `'.$relation.'`.`'.self::$_manage_role_name[$type].'` LIKE :like_manage_id)';
				}
				$criteria->addCondition( implode(' OR ', $couditions));
				$criteria->params[':like_manage_id']='%'.strtr($this->manage_id,array('%'=>'\%', '_'=>'\_', '\\'=>'\\\\')).'%';
			}
			$criteria->compare('t.manage_type',$this->manage_type);
			
			$criteria->compare('t.password_type',$this->password_type);
			$criteria->compare('t.use_count',$this->use_count,true);
			$criteria->compare('t.up_count',$this->up_count,true);
			$criteria->compare('t.error_count',$this->error_count,true);
			$criteria->compare('t.use_error',$this->use_error,true);
			$criteria->compare('t.use_ip',$this->use_ip,true);
			if ($this->use_time != '')
				$criteria->addBetweenCondition('t.use_time',strtotime($this->use_time),(strtotime($this->use_time)+3600*24-1));
			$criteria->compare('t.use_address',$this->use_address,true);
			$criteria->compare('t.last_ip',$this->last_ip,true);
			if ($this->last_time != '')
				$criteria->addBetweenCondition('t.last_time',strtotime($this->last_time),(strtotime($this->last_time)+3600*24-1));
			$criteria->compare('t.last_address',$this->last_address,true);
			if ($this->add_time != '')
				$criteria->addBetweenCondition('t.add_time',strtotime($this->add_time),(strtotime($this->add_time)+3600*24-1));
			if ($this->up_time != '')
				$criteria->addBetweenCondition('t.up_time',strtotime($this->up_time),(strtotime($this->up_time)+3600*24-1));
			$criteria->compare('t.centre_status',$this->centre_status);
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
	 * @return Password the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/**
	 * 加密 支付密码
	 * @param unknown $password
	 * @return string
	 */
	public static function encryptPay($password,$salt)
	{
		return sha1(sha1(md5(md5($password.$salt)).md5(md5($salt.$password))));
	}
	
	/**
	 * 加密 默认
	 * @param unknown $password
	 * @return string
	 */
	public static function encrypt($password,$salt)
	{
		return sha1(sha1(md5($password.$salt).md5($salt.$password)));
	}
	
	/**
	 * 验证密码是否对
	 * @param unknown $password 数据库密码
	 * @param unknown $salt			盐密码
	 * @param unknown $pwd_type	密码类型
	 * @param unknown $pwd			输入密码
	 * @return boolean
	 */
	public static function comparePwd($password,$salt,$pwd_type,$pwd)
	{
		$function = isset(self::$__password_type[$pwd_type])?self::$__password_type[$pwd_type]:'encrypt';
		return $password === self::$function($pwd,$salt);
	}
	
	/**
	 * 获取随机的盐 用于加密
	 * @param unknown $id
	 * @return string
	 */
	public static function getSalt($id)
	{
		return md5($id.time().mt_rand(10000000,99999999));
	}
	
	/**
	 * 获取角色的名称
	 * @param unknown $model
	 * @param unknown $type
	 * @param unknown $attributes
	 * @return string|multitype:
	 */
	public static function getRoleName($model,$type,$attributes=array())
	{
		if (empty($attributes))
			$attributes=self::$_role_name;
		if (isset(self::$_role_type[$type],self::$__role_type[$type],$attributes[$type]))
		{
			$relation=self::$__role_type[$type];
			$name = $attributes[$type];
			return isset($model->$relation->$name) ? $model->$relation->$name : '未知角色';
		}
		
		return '未知角色';
	}
	
	/**
	 * 获取管理角色的名称
	 * @param unknown $model
	 * @param unknown $type
	 * @param unknown $attributes
	 * @return string|multitype:
	 */
	public static function getManageRoleName($model,$type,$attributes=array())
	{
		if(empty($attributes))
			$attributes=self::$_manage_role_name;
		if(isset(self::$_manage_type[$type],self::$__manage_type[$type],$attributes[$type]))
		{
			$relation=self::$__manage_type[$type];
			$name=$attributes[$type];
			return isset($model->$relation->$name) ? $model->$relation->$name : '未知角色';		
		}
		
		return '未知角色';
	}
	
	/**
	 * 保存之前的操作
	 * @see CActiveRecord::beforeSave()
	 */
	public function beforeSave()
	{
		if (parent::beforeSave())
		{
			if($this->isNewRecord)
			{
				$this->use_time = $this->last_time = $this->up_time = $this->add_time = time();
				$this->use_ip = $this->last_ip = Yii::app()->request->userHostAddress;
			}
			else
			{
				$this->up_time=time();
				$this->last_ip = $this->use_ip;
				$this->use_ip = Yii::app()->request->userHostAddress;
				$this->last_time = $this->use_time;
				$this->use_time = time();
			}
			return true;
		}else
			return false;
	}
	
	/**
	 * 是否存在密码
	 * @param integer $role_id
	 * @param integer $role_type
	 * @param integer $pwd_type
	 * @param integer $centre_status
	 * @param unknown $status
	 * @return Ambigous <static, mixed, NULL, multitype:static , multitype:unknown Ambigous <static, NULL> , multitype:, multitype:unknown >
	 */
	public static function existPwd($role_id, $role_type, $pwd_type)
	{
		$criteria=new CDbCriteria;
		$criteria->addColumnCondition(array(
			'role_id'=>$role_id,							//角色ID
			'role_type'=>$role_type,				//角色类型
			'password_type'=>$pwd_type,		//密码类型
		));
		return self::model()->find($criteria);
	}
	
	/**
	 * 验证密码是否对
	 * @param unknown $role_id
	 * @param unknown $role_type
	 * @param unknown $pwd_type
	 * @param unknown $pwd
	 * @param unknown $centre_status
	 * @param unknown $status
	 * @return multitype:string number
	 * 		-6 验证不通过
	 * 	 	-5异常错误
	 * 		-4 已锁定
	 * 		-3 错误次数过多
	 * 		-2 未设置
	 * 		-1  不正确 
	 * 		0  不正确 剩余次数
	 * 		1  正确
	 */
	public static function validatePwd($role_id, $role_type, $pwd_type, $pwd, $centre_status = self::centre_status_valid, $status=self::status_normal)
	{
		if (isset(self::$_password_type[$pwd_type]))
		{
			$model = self::existPwd($role_id, $role_type, $pwd_type);
			$name = self::$_password_type[$pwd_type];
			if ($model)
			{
				$model->scenario = isset(self::$_password_type_validate[$pwd_type]) ? self::$_password_type_validate[$pwd_type] : 'validate';
				$model->attributes = $pwd;
				if(! $model->validate())
					return array('name'=>$name,'value'=>-6,'surplus'=>-1,'error'=>$model->getErrors());
				if($model->status != $status)  				//验证记录
					return array('name'=>$name,'value'=>-4,'surplus'=>-1);
				if($model->status != $centre_status)	//验证核心状态 
					return array('name'=>$name,'value'=>-4,'surplus'=>-1);
				//验证错误次数是否超出
				if(isset(self::$_use_error_limit[$pwd_type]))
					$limit_error = self::$_use_error_limit[$pwd_type];
				else 
					$limit_error = 0;
				if($limit_error <= 0)	//不限制
				{
					if(self::comparePwd($model->password,$model->salt,$model->password_type,$model->_pwd))
						//通过
						return array('name'=>$name,'value'=>self::password_pass,'surplus'=>-1,'status'=>self::addUsePwd($model));				
					else
						//错误 无次数限制
						return array('name'=>$name,'value'=>-1,'surplus'=>-1,'status'=>self::addErrorCountPwd($model)); 				
				}
				else
				{
					$surplus=$limit_error-$model->use_error;
					if($surplus <= 0)
						return array('name'=>$name,'value'=>-3,'surplus'=>0);				//次数过多
					else
					{
						if(self::comparePwd($model->password,$model->salt,$model->password_type,$model->_pwd))
							//验证通过
							return array('name'=>$name,'value'=>self::password_pass,'surplus'=>-1,'status'=>self::addUsePwd($model));					
						elseif($surplus-1 == 0)
							//次数过多
							return array('name'=>$name,'value'=>-3,'surplus'=>0,'status'=>self::addErrorCountPwd($model));
						else
							//错误 有次数限制
							return array('name'=>$name,'value'=>0,'surplus'=>$surplus-1,'status'=>self::addErrorCountPwd($model));
					}				
				}
			}
			else
				return array('name'=>$name,'value'=>-2,'surplus'=>-1); //未设置
		}
		return array('name'=>'','value'=>-5,'surplus'=>-1);//异常
	}

	/**
	 * 	 	-5异常错误
	 * 		-4 已锁定
	 * 		-3 错误次数过多
	 * 		-2 未设置
	 * 		-1  不正确 
	 * 		0  不正确 剩余次数
	 * 		1  正确
	 * @param unknown $name
	 * @param unknown $value
	 * @param unknown $surplus
	 * @return multitype:string number unknown
	 */
	public static function getValidatePwdResult($result)
	{
		if(! isset($result['name'],$result['value'],$result['surplus']))
		{
			$result['name']='';
			$result['value']=-5;
			$result['surplus']=-1;
		}
		$results=array(
			-6=>array('name'=>'{name}格式验证不通过,请重新输入'),
			-5=>array('name'=>'{name}异常错误,请重试'),
			-4=>array('name'=>'{name}已锁定，请联系管理员解除锁定'),
			-3=>array('name'=>'{name}错误次数过多，请设置新的密码'),
			-2=>array('name'=>'{name}未设置，请设置后再使用'),
			-1=>array('name'=>'{name}不正确，请重新输入'),
			 0=>array('name'=>'{name}不正确，你还可以输入{surplus}次'),
			 self::password_pass=>array('name'=>'{name}验证通过'),
		);
		$result['name'] = strtr($results[$result['value']]['name'],array(
				'{name}'=>$result['name'],
				'{surplus}'=>$result['surplus']
		));
		return $result;
	}
	
	/**
	 * 创建密码
	 * @param unknown $pwd_type
	 * @param unknown $pwd_info $pwd_info['_pwd'],$pwd_info['_confirm_pwd'],
	 * @param unknown $role_who $role_who['role_id'],$role_who['role_type']
	 * @param unknown $manage_role $manage_role['manage_id'],$manage_role['manage_type']
	 * @param unknown $centre_status
	 * @param unknown $status
	 * @return Ambigous model
	 */
	public static function createPwd($pwd_type,$role_who,$data,$manage_role,$centre_status=self::centre_status_valid,$status=self::status_normal)
	{
		if(isset($role_who['role_id'],$role_who['role_type'],$manage_role['manage_id'],$manage_role['manage_type']))
		{
			$model=self::existPwd($role_who['role_id'], $role_who['role_type'], $pwd_type);
			if($model)
				return false;
			$model = new Password;
			$model->scenario = isset(self::$_password_type_create[$pwd_type]) ? self::$_password_type_create[$pwd_type] : 'create';
			$model->attributes = $data;
			$model->manage_id = $manage_role['manage_id'];
			$model->manage_type = $manage_role['manage_type'];
			$model->role_id = $role_who['role_id'];
			$model->role_type = $role_who['role_type'];
			$model->password_type = $pwd_type;
			$model->centre_status = $centre_status;
			$model->status = $status;
			return $model;
		}
		return false;
	}
		
	/**
	 * 修改密码
	 * @param unknown $pwd_type
	 * @param unknown $pwd_info
	 * @param unknown $role_who
	 * @param unknown $manage_role
	 * @param unknown $centre_status
	 * @param unknown $status
	 * @throws Exception
	 * @return Ambigous model
	 */
	public static function updatePwd($pwd_type,$role_who,$data,$manage_role,$centre_status=self::centre_status_valid,$status=self::status_normal)
	{
		if(isset($role_who['role_id'],$role_who['role_type'],$manage_role['manage_id'],$manage_role['manage_type']))
		{
			$model=self::existPwd($role_who['role_id'], $role_who['role_type'], $pwd_type);
			if(! $model)
				return false;
			$model->scenario=isset(self::$_password_type_update[$pwd_type])?self::$_password_type_update[$pwd_type]:'update';
			$model->attributes=$data;
			$model->manage_id = $manage_role['manage_id'];
			$model->manage_type = $manage_role['manage_type'];
			$model->centre_status = $centre_status;
			$model->status = $status;
			return $model;
		}
		return false;
	}
	
	/**
	 * 执行创建或者更新 方法
	 * @param unknown $model
	 * @return Ambigous <number, unknown>
	 */
	public static function executeCreateUpdatePwd($model)
	{
		if(! $model->validate())
			return false;
		if($model->isNewRecord)
		{
			if(! $model->save(false))
				return false;
			$salt = self::getSalt($model->id);
			$function = isset(self::$__password_type[$model->password_type])?self::$__password_type[$model->password_type]:'encrypt';
			return self::model()->updateByPk($model->id, array(
					'salt'=>$salt,
					'password'=>self::$function($model->_confirm_pwd, $salt),
			));
		}
		else
		{
			$salt = self::getSalt($model->id);
			$function = isset(self::$__password_type[$model->password_type])?self::$__password_type[$model->password_type]:'encrypt';
			$model->salt = $salt;
			$model->password = self::$function($model->_confirm_pwd, $salt);
			$model->use_error = 0;
			$model->up_count=$model->up_count+1;
			return $model->save(false);
		}
	}

	/**
	 * 重置错误次数
	 * @param unknown $model
	 * @return Ambigous <number, unknown>
	 */
	public static function resetErrorPwd($model)
	{
		return self::model()->updateByPk($model->id, array(
				'use_error'=>0,
				'up_time'=>time(),
		));
	}
	
	/**
	 * 添加错误计算
	 */
	public static function addErrorCountPwd($model)
	{
		return self::model()->updateByPk($model->id, array(
				'use_error'=>new CDbExpression('`use_error`+1'),
				'error_count'=>new CDbExpression('`error_count`+1'),
				'up_time'=>time(),
		));
	}
	
	/**
	 * 添加通过次数 消除错误次数
	 */
	public static function addUsePwd($model)
	{
		return self::model()->updateByPk($model->id, array(
				'use_error'=>0,
				'use_count'=>new CDbExpression('`use_count`+1'),
				'up_time'=>time(),
		));
	}
	
	/**
	 * 检测是否存在角色
	 * @param unknown $id
	 * @param unknown $role_type
	 * @return boolean
	 */
	public static function existRole($id, $role_type)
	{
		if (isset(self::$_role_type[$role_type],self::$_role_type_model[$role_type]))
		{
			$modelName = self::$_role_type_model[$role_type];
			return $modelName::model()->findByPk($id);
		}
		return false;
	}
	
	/**
	 * 设置已验证
	 */
	public function is_validator()
	{
		$this->is_validator = true;
	}
	
	/**
	 * 验证短信是否有效
	 * @param unknown $type
	 * @return boolean
	 */
	public function verifycode($type=Password::password_type_pay)
	{
		if(isset(Password::$_password_type[$type],Password::$_password_type_sms[$type]))
			$sms_use = Password::$_password_type_sms[$type];		//短信的用途
		else
		{
			$this->addError('sms', '密码短信类型 不存在');
			return false;
		}		
		if(! $this->is_validator)
			$this->validate();
		$model = User::model()->findByPk(Yii::app()->api->id,'status=:status',array(':status'=>User::status_suc));
		if(! $model)
		{
			$this->addError('sms', '用户账号 已禁用或未登录');
			return false;
		}
		if(! $this->hasErrors())
		{
			Yii::import('ext.Send_sms.Send_sms');
			$params=array(
					'sms_id'=>Yii::app()->api->id,
					'sms_type'=>SmsLog::sms_user,
					'role_id'=>Yii::app()->api->id,
					'role_type'=>SmsLog::send_user,
					'sms_use'=>$sms_use,
			);
			if (Send_sms::verifycode($model->phone, $params, $this->sms))
				return true;
			$this->addError('sms', '短信验证码 错误');
		}	
		return false;
	}
}
