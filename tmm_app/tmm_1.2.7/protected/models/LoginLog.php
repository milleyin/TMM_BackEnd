<?php

/**
 * This is the model class for table "{{login_log}}".
 *
 * The followings are the available columns in table '{{login_log}}':
 * @property string $id
 * @property string $type
 * @property string $login_id
 * @property string $login_time
 * @property string $login_ip
 * @property string $login_address
 * @property string $login_source
 * @property string $login_type
 * @property string $login_error
 * @property string $add_time
 * @property string $up_time
 * @property integer $status
 */
class LoginLog extends CActiveRecord
{
	/**
	 * 获取登录地址的名字
	 * @var unknown
	 */
	const login_address_name = 'login_address_name';
	/**
	 * 获取登录地址的名字
	 * @var unknown
	 */
	const login_error_name = 'login_error_name';

	/********************登录角色类型*****************/
	/**
	 * 管理员
	 * @var integer
	 */
	const admin = 1;
	/**
	 * 代理商
	 * @var integer
	 */
	const agent = 2;
	/**
	 * 代理商
	 * @var integer
	 */
	const store = 3;
	/**
	 * 用户
	 * @var integer
	 */
	const user = 4;
	/**
	 * 解释字段 type 的含义
	 * @var array
	 */
	public static $_type = array(1=>'管理员',2=>'运营商',3=>'商家',4=>'用户');
	/**
	 * 解释字段 type 的关联关系名 含义
	 * @var array
	*/
	public static $__type = array(1=>'Login_Admin',2=>'Login_Agent',3=>'Login_StoreUser',4=>'Login_User');
	/**
	 * 解释字段 type 的关联关系名 含义
	 * @var array
	 */
	public static $__type_name = array(
			1=>array('username', 'name'),
			2=>array('phone', 'firm_name'),
			3=>'phone',
			4=>array('phone', 'nickname')
	);
	/**
	 * 解释字段 type 的场景 过滤数据
	 * @var array
	 */
	public static $_type_scenario= array(1=>'create',2=>'create',3=>'create',4=>'create');

	/********************登录类型*****************/
	/**
	 * 获取登录的类型名
	 * @var unknown
	 */
	const login_type_name = 'login_type_name';
	/**
	 * 登录类型 密码
	 * @var integer
	 */
	const login_type_password=1;
	/**
	 * 登录类型 短信登录
	 * @var integer
	 */
	const login_type_sms=2;
	/**
	 * 登录类型 自动登录
	 * @var integer
	 */
	const login_type_auto=3;
	/**
	 * 登录类型--解释字段 login_type 的含义
	 * @var array
	 */
	public static $_login_type = array(1=>'密码登录',2=>'短信登录',3=>'自动登录');

	/********************登录来源*****************/	
	/**
	 * 登录来源 IOS
	 * @var integer 
	 */
	const login_source_ios=1;
	/**
	 * 登录来源 安卓
	 * @var integer
	 */
	const login_source_android=2;
	/**
	 * 登录来源 PC
	 * @var integer
	 */
	const login_source_pc=3;
	/**
	 * 登录来源 微信
	 * @var integer
	 */
	const login_source_weixin=4;
	/**
	 * 解释字段 login_source 的含义
	 * @var array
	 */
	public static $_login_source=array(1=>'IOS',2=>'安卓',3=>'PC',4=>'微信');
	
	/********************登录记录状态*****************/
	/**
	 * 登录状态 删除
	 * @var integer
	 */
	const status_del=-1;
	/**
	 * 登录状态 无效
	 * @var integer
	 */
	const status_void=0;
	/**
	 * 登录状态 有效
	 * @var integer
	 */
	const status_valid=1;
	/**
	 * 解释字段 status 的含义
	 * @var array
	 */
	public static $_status=array(-1=>'删除','无效','有效');
	/**
	 * 搜索的时间类型
	 * @var array
	*/
	public $search_time_type;
	/**
	 *	解释搜索类型时间的含义 search_time_type
	 * @var string
	 */
	public static $_search_time_type=array('登录时间','创建时间','更新时间');
	/**
	 *	解释搜索类型时间 search_time_type 的字段搜索
	 * @var string
	*/
	public $__search_time_type=array('login_time','add_time','up_time');
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
		return '{{login_log}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			//array('type, login_id, login_time, login_source, login_type, login_error, add_time, up_time', 'required'),
			array('status', 'numerical', 'integerOnly'=>true),
			array('type, login_id, login_time, login_source, login_type, login_error', 'length', 'max'=>11),
			array('login_ip', 'length', 'max'=>15),
			array('login_address', 'length', 'max'=>100),
			array('add_time, up_time', 'length', 'max'=>10),
				
			array('type','in','range'=>array_keys(self::$_type)),
			array('login_source','in','range'=>array_keys(self::$_login_source)),
			array('status','in','range'=>array_keys(self::$_status)),
			array('login_type','in','range'=>array_keys(self::$_login_type)),
			//添加登录日志
			array('login_error', 'required','on'=>'create'),
			array('login_error,login_address','safe','on'=>'create'),
			array('search_time_type,search_start_time,search_end_time,id, type, login_id, login_time, login_ip, login_source, login_type, add_time, up_time, status','unsafe','on'=>'create'),
			
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('search_time_type,search_start_time,search_end_time,id, type, login_id, login_time, login_ip, login_address, login_source, login_type, login_error, add_time, up_time, status', 'safe', 'on'=>'search'),
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
			'Login_Admin' => array(self::BELONGS_TO, 'Admin', 'login_id'),
			'Login_Agent' => array(self::BELONGS_TO, 'Agent', 'login_id'),
			'Login_StoreUser' => array(self::BELONGS_TO, 'StoreUser', 'login_id'),
			'Login_User' => array(self::BELONGS_TO, 'User', 'login_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'type' => '用户类型',
			'login_id' => '用户名称',
			'login_time' => '登录时间',
			'login_ip' => '登录IP',
			'login_address' => '登录地址',
			'login_source' => '登录来源',
			'login_type' => '登录类型',
			'login_error' => '登录错误次数',
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
				'Login_Admin'=>array('select'=>'username, name'),
				'Login_Agent'=>array('select'=>'phone, firm_name'),
				'Login_StoreUser'=>array('select'=>'phone'),
				'Login_User'=>array('select'=>'phone, nickname')
			);
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
			$criteria->compare('t.type',$this->type,true);
			//综合搜索
			if (isset( self::$__type[$this->type],self::$__type_name[$this->type],self::$__type_name[$this->type])) 
			{
				$relation = self::$__type[$this->type];
				$name = self::$__type_name[$this->type];
				$conditions = array('login_id=:login_id');
				$criteria->params[':login_id'] = $this->login_id;
				$criteria->params[':login_id_like'] ='%'.strtr($this->login_id,array('%'=>'\%', '_'=>'\_', '\\'=>'\\\\')).'%';
				if(is_array($name) && isset($name[0],$name[1]))
				{
					$conditions[] = $relation.'.'.$name[0].' LIKE :login_id_like';
					$conditions[] = $relation.'.'.$name[1].' LIKE :login_id_like';
				}else 
					$conditions[] = $relation.'.'.$name.' LIKE :login_id_like';
				
				$criteria->addCondition( implode(' OR ', $conditions));
			}
			elseif($this->login_id != null)
			{
				$relations= self::$__type;
				$conditions = array('login_id=:login_id');
				$criteria->params[':login_id'] = $this->login_id;
				$criteria->params[':login_id_like'] ='%'.strtr($this->login_id,array('%'=>'\%', '_'=>'\_', '\\'=>'\\\\')).'%';
				foreach ($relations as $type=>$relation)
				{
					if(isset(self::$__type_name[$type]))
					{
						$name = self::$__type_name[$type];
						if(is_array($name) && isset($name[0],$name[1]))
						{
							$conditions[] = '(`t`.`type`='.$type.' AND `'.$relation.'`.`'.$name[0].'` LIKE :login_id_like)';
							$conditions[] = '(`t`.`type`='.$type.' AND `'.$relation.'`.`'.$name[1].'` LIKE :login_id_like)';
						}else 
							$conditions[] = '(`t`.`type`='.$type.' AND `'.$relation.'`.`'.$name.'` LIKE :login_id_like)';
					}
				}
				$criteria->addCondition( implode(' OR ', $conditions));
			}
			
			if($this->login_time != '')
				$criteria->addBetweenCondition('t.login_time',strtotime($this->login_time),(strtotime($this->login_time)+3600*24-1));
			$criteria->compare('t.login_ip',$this->login_ip,true);
			$criteria->compare('t.login_address',$this->login_address,true);
			$criteria->compare('t.login_source',$this->login_source);
			$criteria->compare('t.login_type',$this->login_type);
			$criteria->compare('t.login_error',$this->login_error,true);
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
	 * @return LoginLog the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * 获取角色名称
	 * @param unknown $model
	 * @param unknown $type
	 * @return string
	 */
	public static function getRoleName($model,$type)
	{
		if(isset(self::$_type,self::$__type[$type],self::$__type_name[$type]))
		{
			$relation = self::$__type[$type];
			$name = self::$__type_name[$type];
			if(is_array($name) && isset($name[0],$name[1]))
				return isset($model->$relation,$model->$relation->$name[0],$model->$relation->$name[1]) ? $model->$relation->$name[0].' ['.$model->$relation->$name[1].']' : '未知角色';
			else
				return isset($model->$relation,$model->$relation->$name) ? $model->$relation->$name : '未知角色';
		}
		return '未知角色';
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
			{
				$this->login_time=$this->up_time=$this->add_time=time();
				$this->login_ip = Yii::app()->request->userHostAddress;
			}else
				$this->up_time=time();
			return true;
		}else
			return false;
	}
	
	/**
	 * 添加登录日志
	 * @param unknown $type self::$_type
	 * @param unknown $data array('login_error'=>'','login_address'=>'')
	 * @param unknown $status
	 * @return boolean
	 */
	public static function createLog($type,$_this,$status = self::status_valid)
	{
		if(isset(self::$_type[$type]) && $_this->getId())
		{
			$model = new LoginLog;
			$model->scenario = isset(self::$_type_scenario[$type]) ? self::$_type_scenario[$type] : 'create';
			$model->login_id = $_this->getId();
			$model->type = $type;
			//获取登录来源
			$model->login_source = self::getSource();
			//获取登录类型
			$login_type = $_this->getFlash(self::login_type_name);		
			//获取登录错误次数
			$login_error = $_this->getFlash(self::login_error_name);
			//获取登录地址
			$login_address = $_this->getFlash(self::login_address_name);
			$model->login_type = $login_type != null ? $login_type : self::login_type_auto;
			$model->attributes = array(
				'login_error'=>$login_error ? $login_error : 0,
				'login_address'=>$login_address ? $login_address : '',
			);
			$model->status = $status;
			return $model->save();
		}
		return false;
	}
	
	/**
	 * 获取 访问的类型
	 * @return unknown|boolean
	 */
	public static function getSource()
	{
		if(isset($_SERVER['HTTP_USER_AGENT']) && $_SERVER['HTTP_USER_AGENT'])
		{
			$server='tmm_'.strtolower($_SERVER['HTTP_USER_AGENT']);//添加前缀 避免为0
			if(strrpos($server,'micromessenger'))
				return self::login_source_weixin;
			elseif(strrpos($server,'iphone') || strrpos($server, 'ipad') || strrpos($server, 'ipod'))
				return self::login_source_ios;
			elseif(strrpos($server,'android'))
				return self::login_source_android;
			elseif(strrpos($server,'windows nt'))
				return self::login_source_pc;
		}
		return self::login_source_pc;
	}
}
