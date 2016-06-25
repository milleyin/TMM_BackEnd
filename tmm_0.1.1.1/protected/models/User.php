<?php

/**
 * This is the model class for table "{{user}}".
 *
 * The followings are the available columns in table '{{user}}':
 * @property string $id
 * @property string $phone
 * @property integer $phone_type
 * @property string $password
 * @property integer $is_organizer
 * @property integer $audit
 * @property string $nickname
 * @property integer $gender
 * @property string $icon
 * @property string $count
 * @property string $login_token
 * @property string $login_mac
 * @property string $last_mac
 * @property string $login_error
 * @property string $error_count
 * @property string $login_time
 * @property string $login_ip
 * @property string $login_address
 * @property string $last_time
 * @property string $last_ip
 * @property string $last_address
 * @property string $login_unique
 * @property string $add_time
 * @property string $up_time
 * @property integer $status
 */
class User extends CActiveRecord
{
	/**
	 * 是否用短信
	 * @var unknown
	 */
	const login_sms = 'login_sms';
	/**
	 * 登录唯一
	 * @var unknown
	 */
	const login_unique = 'login_unique';
	/**
	 * 没有申请
	 * @var integer
	 */
	const audit_draft=0;
	/**
	 * 待审核
	 * @var integer
	 */
	const audit_pending=1;
	/**
	 * 没有通过
	 * @var integer
	 */
	const audit_nopass=2;
	/**
	 * 通过
	 * @var unknown
	 */
	const audit_pass=3;

	/**
	 * 是组织者
	 * @var unknown
	 */
	const organizer=1;

	/**
	 * 删除
	 */
	const status_del = -1;
	/**
	 * 禁用
	 */
	const status_dis = 0;
	/**
	 * 正常
	 */
	const status_suc = 1;
	/**
	 * 解释字段 status 的含义
	 * @var array
	 */
	public static $_status=array(-1=>'删除','禁用','正常');
	/**
	 * 解释字段 audit 的含义
	 * @var unknown
	 */
	public static $_audit=array('未申请', '待审核','未通过','审核通过');
	/**
	 * 解释字段 is_organizer 的含义
	 * @var unknown
	 */
	public static $_is_organizer=array('普通用户','组织者');
	/**
	 * 搜索的时间类型
	 * @var array
	 */
	public $search_time_type;
	/**
	 *	解释搜索类型时间的含义 search_time_type
	 * @var string
	 */
	public static $_search_time_type=array('登录时间','上次登录时间','创建时间','更新时间'); 
	/**
	 *	解释搜索类型时间 search_time_type 的字段搜索
	 * @var string
	 */
	public $__search_time_type=array('login_time','last_time','add_time','up_time'); 
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
	 * 确认密码
	 * @var string
	 */
	public $confirm_pwd;
	/**
	 * 密码
	 * @var string
	 */
	public $_pwd;
	/**
	 * 新密码
	 * @var string
	 */
	public $new_pwd;
	/**
	 * 短信
	 */
	public $sms;
	/**
	 * 解释字段 gender 的含义
	 * @var array
	 */
	public static $_gender=array('保密','男','女');


	public $is_validator;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{user}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('phone,nickname', 'required'),
			array('phone_type, is_organizer, audit, gender, status', 'numerical', 'integerOnly'=>true),
			array('count, login_error, error_count', 'length', 'max'=>11),
			array('password', 'length','min'=>32,'max'=>32),
			array('nickname', 'length','min'=>2,'max'=>20),
			array('icon, login_address, last_address', 'length', 'max'=>100),
			array('login_token, login_mac, last_mac', 'length', 'max'=>128),
			array('login_time, last_time, add_time, up_time', 'length', 'max'=>10),
			array('login_ip, last_ip', 'length', 'max'=>15),
			array('phone','unique'),
			array('phone', 'length','min'=>11, 'max'=>11),
			array('phone','match','pattern'=>Yii::app()->params['pattern']['phone'],'message'=>'{attribute} 不是有效的'),
			array('nickname','match','pattern'=>'/^[\x{4e00}-\x{9fa5}A-Za-z0-9]+$/u','message'=>'{attribute} 必须由字母数字或汉字组成'),
				
			//创建、修改用户
			array('_pwd,confirm_pwd,gender', 'required','on'=>'create'),
			array('gender', 'required','on'=>'update'),
			array('gender','in','range'=>array_keys(self::$_gender)),
			array('_pwd, confirm_pwd', 'length','min'=>8,'max'=>20,'on'=>'create,update'),
			array('_pwd','match','pattern'=>'/^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]+$/','message'=>'密码必须有数字和字母组合！','on'=>'create,update'),
			array('confirm_pwd', 'compare', 'compareAttribute'=>'_pwd','on'=>'create,update'),	
			array('phone,nickname,_pwd,confirm_pwd,gender','safe','on'=>'create,update'),
			array('login_unique,search_time_type,search_start_time,search_end_time,id, phone_type, password, is_organizer, audit, icon, count, login_token, login_mac, last_mac, login_error, error_count, login_time, login_ip, login_address, last_time, last_ip, last_address, add_time, up_time, status','unsafe','on'=>'create,update'),

			// 修改用户昵称、性别
			array('nickname,gender', 'required','on'=>'api_user_update'),
			array('gender','in','range'=>array_keys(self::$_gender)),
			array('nickname,gender','safe','on'=>'api_user_update'),
			array('login_unique,search_time_type,search_start_time,search_end_time,id, phone, phone_type, password, is_organizer, audit, icon, count, login_token, login_mac, last_mac, login_error, error_count, login_time, login_ip, login_address, last_time, last_ip, last_address, add_time, up_time, status', 'unsafe', 'on'=>'api_user_update'),

			//用户修改当前手机号码====老手机号
			array('phone,sms', 'required','on'=>'user_update_old'),
			array('phone','match','pattern'=>Yii::app()->params['pattern']['phone'],'on'=>'user_update_old'),
			array('phone,sms', 'safe', 'on'=>'user_update_old'),
			array('phone','is_old','on'=>'user_update_old'),

			//用户修改当前手机号码====新手机号
			array('phone,sms', 'required','on'=>'user_update_new'),
			array('phone','match','pattern'=>Yii::app()->params['pattern']['phone'],'on'=>'user_update_new'),
			array('phone,sms', 'safe', 'on'=>'user_update_new'),
			array('phone','is_new','on'=>'user_update_new'),
			array('login_unique,search_time_type,search_start_time,search_end_time,id, phone_type, password, is_organizer, audit, nickname, gender, icon, count, login_token, login_mac, last_mac, login_error, error_count, login_time, login_ip, login_address, last_time, last_ip, last_address, add_time, up_time, status', 'unsafe', 'on'=>'user_update_new'),
						
			array('phone','is_validator','on'=>'user_update_old,user_update_new,user_bank'),

			//用户绑定银行呀
			array('phone,sms', 'required','on'=>'user_bank'),
			array('phone','match','pattern'=>Yii::app()->params['pattern']['phone'],'on'=>'user_bank'),
			array('phone,sms', 'safe', 'on'=>'user_bank'),
			array('phone','is_old','on'=>'user_bank'),
			array('login_unique,search_time_type,search_start_time,search_end_time,id, phone_type, password, is_organizer, audit, nickname, gender, icon, count, login_token, login_mac, last_mac, login_error, error_count, login_time, login_ip, login_address, last_time, last_ip, last_address, add_time, up_time, status', 'unsafe', 'on'=>'user_bank'),

			//用户提现手机号难
			array('phone,sms', 'required','on'=>'user_cash'),
			array('phone','match','pattern'=>Yii::app()->params['pattern']['phone'],'on'=>'user_cash'),
			array('phone,sms', 'safe', 'on'=>'user_cash'),
			array('phone','is_old','on'=>'user_cash'),

			// 手机用户更新密码验证
			array('phone,sms,_pwd,confirm_pwd', 'required','on'=>'api_pwd_update'),
			array('phone', 'match', 'pattern'=>Yii::app()->params['pattern']['phone'],'on'=>'api_pwd_update'),
			array('_pwd, confirm_pwd', 'length','min'=>8,'max'=>20,'on'=>'api_pwd_update'),
			array('_pwd','match','pattern'=>'/^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]+$/','message'=>'密码必须有数字和字母组合！','on'=>'api_pwd_update'),
			array('confirm_pwd', 'compare', 'compareAttribute'=>'_pwd','on'=>'api_pwd_update'),
			array('phone','is_validator','on'=>'api_pwd_update'),
			array('phone,sms,_pwd,confirm_pwd', 'safe','on'=>'api_pwd_update'),
			array('login_unique,search_time_type,search_start_time,search_end_time,id, phone_type, password, is_organizer, audit, nickname, gender, icon, count, login_token, login_mac, last_mac, login_error, error_count, login_time, login_ip, login_address, last_time, last_ip, last_address, add_time, up_time, status', 'unsafe', 'on'=>'api_pwd_update'),
				
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('search_time_type,search_start_time,search_end_time,id, phone, phone_type, is_organizer, audit, nickname, gender, icon, count, login_token, login_mac, last_mac, login_error, error_count, login_time, login_ip, login_address, last_time, last_ip, last_address, add_time, up_time, status', 'safe', 'on'=>'search'),
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
				//用户（组织者）
				'User_Organizer'=>array(self::HAS_ONE,'Organizer','id'),
				//用户随行人员
				'User_Retinue'=>array(self::HAS_MANY,'Retinue','user_id'),
				//用户的属性标签（即标签分类）
				'User_TagsElement'=>array(self::HAS_MANY,'TagsElement','element_id'),
				//用户关联银行卡信息
				'User_UserBank'=>array(self::HAS_MANY,'UserBank','role_id'),
				//用户关联银行卡信息  2015-12-14
				'User_BankCard'=>array(self::HAS_MANY,'BankCard','card_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'phone' => '登录手机号',
			'phone_type' => '登录类型',//'手机类型1=ios2=android',
			'password' => '密码',
			'is_organizer' => '账户类型',//0p普通用户 1组织者',
			'audit' => '组织者审核',//'组织者提交申请（0 没有提交 1 提交未审核，2审核未通过 3通过审核）',
			'nickname' => '昵称',
			'gender' => '性别',//(0、未知，1、男 2、女)',
			'icon' => '头像',
			'count' => '登录次数',
			'login_token' => '推送TOKEN',
			'login_mac' => 'MAC',
			'last_mac' => '最近MAC',
			'login_error' => '登录错误',
			'error_count' => '错误统计',
			'login_time' => '登录时间',
			'login_ip' => '登录IP',
			'login_address' => '登录地址',
			'last_time' => '上次时间',
			'last_ip' => '上次IP',
			'last_address' => '上次地址',
			'add_time' => '创建时间',
			'up_time' => '更新时间',
			'status' => '状态',
			'search_time_type'=>'时间类型',
			'search_start_time'=>'开始时间',
			'search_end_time'=>'结束时间',
			'_pwd'=>'密码',
			'new_pwd'=>'新密码',
			'confirm_pwd'=>'确认密码',
			'sms'=>'短信'
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
			$criteria->compare('status','<>-1');
			if($this->search_time_type != '' && isset($this->__search_time_type[$this->search_time_type]))
			{
				if($this->search_start_time !='' && $this->search_end_time !='')
					$criteria->addBetweenCondition($this->__search_time_type[$this->search_time_type],strtotime($this->search_start_time),strtotime($this->search_end_time));
				elseif($this->search_start_time !='' && $this->search_end_time =='')
					$criteria->compare($this->__search_time_type[$this->search_time_type],'>='.strtotime($this->search_start_time));
				elseif($this->search_start_time =='' && $this->search_end_time !='')
					$criteria->compare($this->__search_time_type[$this->search_time_type],'<='.strtotime($this->search_end_time));
			}			
			$criteria->compare('id',$this->id,true);
			$criteria->compare('phone',$this->phone,true);
			$criteria->compare('phone_type',$this->phone_type);
			$criteria->compare('password',$this->password,true);
			$criteria->compare('is_organizer',$this->is_organizer);
			$criteria->compare('audit',$this->audit);
			$criteria->compare('nickname',$this->nickname,true);
			$criteria->compare('gender',$this->gender);
			$criteria->compare('icon',$this->icon,true);
			$criteria->compare('count',$this->count,true);
			$criteria->compare('login_token',$this->login_token,true);
			$criteria->compare('login_mac',$this->login_mac,true);
			$criteria->compare('last_mac',$this->last_mac,true);
			$criteria->compare('login_error',$this->login_error,true);
			$criteria->compare('error_count',$this->error_count,true);
			if($this->login_time != '')
				$criteria->addBetweenCondition('login_time',strtotime($this->login_time),(strtotime($this->login_time)+3600*24-1));
			$criteria->compare('login_ip',$this->login_ip,true);
			$criteria->compare('login_address',$this->login_address,true);
			if($this->last_time != '')
				$criteria->addBetweenCondition('last_time',strtotime($this->last_time),(strtotime($this->last_time)+3600*24-1));
			$criteria->compare('last_ip',$this->last_ip,true);
			$criteria->compare('last_address',$this->last_address,true);
			if($this->add_time != '')
				$criteria->addBetweenCondition('add_time',strtotime($this->add_time),(strtotime($this->add_time)+3600*24-1));
			if($this->up_time != '')
				$criteria->addBetweenCondition('up_time',strtotime($this->up_time),(strtotime($this->up_time)+3600*24-1));
			$criteria->compare('status',$this->status);
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
	 * @return User the static model class
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
				$this->login_time=$this->last_time=$this->up_time=$this->add_time=time();
			else
				$this->up_time=time();			
			return true;
		}else
			return false;
	}
	
	/**
	 * 验证密码是否对
	 * @param unknown $pwd
	 * @param unknown $password
	 * @return boolean
	 */
	public function validatePassword($pwd,$password){
		return self::pwdEncrypt($pwd) === $password;
	}
	
	/**
	 * 密码加密函数
	 * @param unknown $pwd
	 * @return string
	 */
	public static function pwdEncrypt($pwd){
		return md5(md5($pwd));
	}

	/**
	 * 登录后更新的数据
	 * @param unknown $time
	 * @return Ambigous <number, unknown>
	 */
	public function set_login($time,$login_unique){
		return $this->updateByPk($this->id,array(
			'last_ip'=>$this->login_ip,
			'last_time'=>$this->login_time,
			'login_ip'=>Yii::app()->request->userHostAddress,
			'login_time'=>$time,
			'login_error'=>0,
			'count'=>new CDbExpression('`count`+1'),
			'login_unique'=>$login_unique,
		));
	}

	/**
	 * 错误登录次数添加
	 * @param unknown $user
	 */
	public function login_error(){
		return $this->updateByPk($this->id,array(
			'login_error'=>new CDbExpression('`login_error`+1'),
			'error_count'=>new CDbExpression('`error_count`+1'),
		));
	}

	/**
	 * 登录状态
	 */
	public function login_status($_this,$is_sms=''){

		if(!$is_sms){
			if($this->status==-1)//删除，没有这个用户
				$_this->addError('password','用户名或密码错误');
			elseif($this->status==0)
				$_this->addError('password','用户名 被禁用了 请联系管理员');
			elseif($this->status !=1)
				$_this->addError('password','用户名或密码错误');
		}else{
			if($this->status==-1)//删除，没有这个用户
				$_this->addError('phone','用户名或验证码错误');
			elseif($this->status==0)
				$_this->addError('phone','用户名 被禁用了 请联系管理员');
			elseif($this->status !=1)
				$_this->addError('phone','用户名或验证码错误');
		}


	}

	/**
	 * 注册用户
	 * @param $phone
	 * @return bool|User
	 */
	public static function register($phone)
	{
		$model = new User();
		$model->phone = $phone;
		$model->nickname = $phone;
		$model->password='';
		$model->status = self::status_suc;
		return $model->save();
	}

	/**
	 * 限制登录错误次数
	 */
	public function login_error_confine($_this){
		if($this->login_error > Yii::app()->params['user_login_error']) 
		{
			$_this->addError('password', '登录错误次数过多，请用短信验证登录');
			return false;
		}
		return true;
	}

	/**
	 * 是否验证
	 */
	public function is_validator()
	{
		$this->is_validator=true;
	}

	/**
	 * 验证手机短信
	 * 修改新手机用 修改旧手机用
	 */
	public function verifycode()
	{
		if(! $this->is_validator)
			$this->validate();
		if(!$this->hasErrors())
		{
			Yii::import('ext.Send_sms.Send_sms');
			$params=array(
				'sms_id'=>Yii::app()->api->id,
				'sms_type'=>SmsLog::sms_user,
				'role_id'=>Yii::app()->api->id,
				'role_type'=>SmsLog::send_user,
				'sms_use'=>SmsLog::use_phone,
			);
			if (Send_sms::verifycode($this->phone, $params, $this->sms))
				return true;
		}
		$this->addError('sms', '手机验证码 错误');
		return false;
	}
	/**
	 * 验证手机短信
	 * 修改新手机用 修改旧手机用
	 */
	public function verifycode_pwd()
	{
		if(! $this->is_validator)
			$this->validate();
		if(!$this->hasErrors())
		{
			Yii::import('ext.Send_sms.Send_sms');
			$params=array(
				'sms_id'=>Yii::app()->api->id,
				'sms_type'=>SmsLog::sms_user,
				'role_id'=>Yii::app()->api->id,
				'role_type'=>SmsLog::send_user,
				'sms_use'=>SmsLog::use_password,
			);
			if (Send_sms::verifycode($this->phone, $params, $this->sms))
				return true;
		}
		$this->addError('sms', '手机验证码 错误');
		return false;
	}

	/**
	 * 验证手机短信
	 * 用户绑定银行账户
	 */
	public function verifycode_bank()
	{
		if(! $this->is_validator)
			$this->validate();
		if(!$this->hasErrors())
		{
			Yii::import('ext.Send_sms.Send_sms');
			$params=array(
				'sms_id'=>Yii::app()->api->id,
				'sms_type'=>SmsLog::sms_user,
				'role_id'=>Yii::app()->api->id,
				'role_type'=>SmsLog::send_user,
				'sms_use'=>SmsLog::use_bank,
			);
			if (Send_sms::verifycode($this->phone, $params, $this->sms))
				return true;
		}
		$this->addError('sms', '手机验证码 错误');
		return false;
	}
	/**
	 * 验证手机是不是原来的值
	 */
	public function is_old()
	{
		if(! User::model()->find('`phone`=:phone AND `id`=:id AND `status`=1',array(':phone'=>$this->phone,':id'=>Yii::app()->api->id)))
		{
			$this->addError('phone', '不是旧的手机号码');
		}
	}
	
	/**
	 * 验证手机是不是原来的值
	 */
	public function is_new()
	{
		if(User::model()->find('`phone`=:phone',array(':phone'=>$this->phone)))
		{
			$this->addError('phone', '新手机号不能与旧手机号一样');
		}
	}



}
