<?php

/**
 * This is the model class for table "{{admin}}".
 *
 * The followings are the available columns in table '{{admin}}':
 * @property string $id
 * @property string $username
 * @property string $password
 * @property string $d_id
 * @property string $admin_id
 * @property string $name
 * @property string $phone
 * @property string $count
 * @property string $login_error
 * @property string $error_count
 * @property string $login_ip
 * @property string $login_time
 * @property string $last_ip
 * @property string $last_time
 * @property string $login_unique
 * @property string $add_time
 * @property string $up_time
 * @property integer $status
 *
 * The followings are the available model relations:
 * @property Admin $admin
 * @property Admin[] $admins
 */
class Admin extends CActiveRecord
{
	/**
	 * 登录唯一
	 * @var unknown
	 */
	const login_unique = 'login_unique';
	/**
	 * 登录消息
	 * @var unknown
	 */
	const login_unique_info = 'login_unique_info';
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
	public static $_status=array(-1=>'删除','冻结','正常');
	/**
	 * 搜索的时间类型
	 * @var array
	 */
	public $search_time_type;
	/**
	 *	解释搜索类型时间的含义 search_time_type
	 * @var string
	 */
	public static $_search_time_type=array('登录时间','最近登录','注册时间','更新时间'); 
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
	 * 原来密码
	 * @var string
	 */
	public $old_pwd;
	/**
	 * 归属部门
	 * @var array
	 */
	public static $_d_id=array('产品部','研发部','运营部','财务部','内容部','设计部',);
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{admin}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('username,d_id, name,phone', 'required'),
			array('status', 'numerical', 'integerOnly'=>true),		
			array('username','length','min'=>'8','max'=>20),
			array('username','unique'),
			array('password', 'length','min'=>32,'max'=>32),
			array('d_id, admin_id, phone, count, login_error, error_count', 'length', 'max'=>11),
			array('name, login_time, last_time, add_time, up_time', 'length', 'max'=>10),
			array('login_ip, last_ip', 'length', 'max'=>15),
			array('username','match','pattern'=>'/^[a-zA-Z]+\w*$/','message'=>'用户名只能字母开头；字母、数字和下划线组成！'),
			array('phone','match','pattern'=>Yii::app()->params['pattern']['phone']),
			array('d_id','in','range'=>array_keys(self::$_d_id)),
			array('status','in','range'=>array_keys(self::$_status)),
				
			//创建、修改管理员信息	
			array('_pwd, confirm_pwd','length','min'=>8,'max'=>20,'on'=>'create,update'),
			array('username,name,phone,d_id,_pwd,confirm_pwd', 'required','on'=>'create'),
			array('username,name,phone,d_id', 'required','on'=>'update'),
			array('_pwd','match','pattern'=>'/^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]+$/','message'=>'密码必须有数字和字母组合！','on'=>'create,update'),	
			array('confirm_pwd', 'compare', 'compareAttribute'=>'_pwd','on'=>'create,update'),
			array('username,name,phone,d_id,_pwd,confirm_pwd','safe','on'=>'create,update'),
			array('login_unique,search_time_type,search_start_time,password,search_end_time,id, admin_id, count, login_error, error_count, login_ip, login_time, last_ip, last_time, add_time, up_time, status','unsafe','on'=>'create,update'),
		
			//修改自己的信息
			array('name,phone,d_id','required','on'=>'modify'),
			array('name,phone,d_id','safe', 'on'=>'modify'),
			array('login_unique,search_time_type,search_start_time,search_end_time,id, username, password, admin_id, count, login_error, error_count, login_ip, login_time, last_ip, last_time, add_time, up_time, status','unsafe', 'on'=>'modify'),
			
			//修改自己的密码
			array('new_pwd, confirm_pwd', 'length','min'=>8,'max'=>20,'on'=>'pwd'),
			array('new_pwd','match','pattern'=>'/^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]+$/','message'=>'密码必须有数字和字母组合！','on'=>'pwd'),
			array('confirm_pwd', 'compare', 'compareAttribute'=>'new_pwd','on'=>'pwd'),
			array('old_pwd','is_old_pwd','on'=>'pwd'),
			array('old_pwd, new_pwd, confirm_pwd', 'required','on'=>'pwd'),	
			array('old_pwd, new_pwd, confirm_pwd','safe','on'=>'pwd'),
			array('login_unique,search_time_type,search_start_time,search_end_time,id, username, password, d_id, admin_id, name, phone, count, login_error, error_count, login_ip, login_time, last_ip, last_time, add_time, up_time, status','unsafe','on'=>'pwd'),
				
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('search_time_type,search_start_time,search_end_time,id, username, password, d_id, admin_id, name, phone, count, login_error, error_count, login_ip, login_time, last_ip, last_time, add_time, up_time, status', 'safe', 'on'=>'search'),
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
			/**
			 * 自己归属创建人
			 */
			'Admin_Admin' => array(self::BELONGS_TO, 'Admin', 'admin_id'),
			/**
			 * 创建了多少人
			 */
			'AdminS_AdminS' => array(self::HAS_MANY, 'Admin', 'admin_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'username' => '用户名',
			'password' => '密码',
			'd_id' => '部门',
			'admin_id' => '创建人',
			'name' => '名字',
			'phone' => '手机号',
			'count' => '登录统计',
			'login_error' => '单次错误',
			'error_count' => '错误统计',
			'login_ip' => '登录IP',
			'login_time' => '登录时间',
			'last_ip' => '最近登录IP',
			'last_time' => '最近登录时间',
			'add_time' => '创建时间',
			'up_time' => '更新时间',
			'status' => '状态',
			'search_time_type'=>'时间类型',
			'search_start_time'=>'开始时间',
			'search_end_time'=>'结束时间',
			'old_pwd'=>'原来密码',
			'_pwd'=>'密码',
			'new_pwd'=>'新密码',
			'confirm_pwd'=>'确认密码',
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
			
			$criteria->with=array('Admin_Admin'=>array('select'=>'username,name'));
			$criteria->compare('t.status','<>-1');
			
			if($this->search_time_type != '' && isset($this->__search_time_type[$this->search_time_type])){
				if($this->search_start_time !='' && $this->search_end_time !='')
					$criteria->addBetweenCondition('t.'.$this->__search_time_type[$this->search_time_type],strtotime($this->search_start_time),strtotime($this->search_end_time)+3600*24-1);
				elseif($this->search_start_time !='' && $this->search_end_time =='')
					$criteria->compare('t.'.$this->__search_time_type[$this->search_time_type],'>='.strtotime($this->search_start_time));
				elseif($this->search_start_time =='' && $this->search_end_time !='')
					$criteria->compare('t.'.$this->__search_time_type[$this->search_time_type],'<=' . (strtotime($this->search_end_time)+3600*24-1));
			}			
			
			$criteria->compare('t.id',$this->id,true);
			$criteria->compare('t.username',$this->username,true);
			$criteria->compare('t.d_id',$this->d_id,true);
			if($this->admin_id != '')
			{
				$conditions = array(				
					'Admin_Admin.username LIKE :admin_id_like',
					'Admin_Admin.name LIKE :admin_id_like',
					't.id=:admin_id'
				);
				$criteria->params[':admin_id_like'] = '%'.strtr($this->admin_id,array('%'=>'\%', '_'=>'\_', '\\'=>'\\\\')).'%';
				$criteria->params[':admin_id'] = $this->admin_id;
				$criteria->addCondition( implode(' OR ', $conditions));
			}
			$criteria->compare('t.name',$this->name,true);
			$criteria->compare('t.phone',$this->phone,true);
			$criteria->compare('t.count',$this->count,true);
			$criteria->compare('t.login_error',$this->login_error,true);
			$criteria->compare('t.error_count',$this->error_count,true);
			$criteria->compare('t.login_ip',$this->login_ip,true);
			$criteria->compare('t.last_ip',$this->last_ip,true);
			
			if($this->login_time != '')
				$criteria->addBetweenCondition('t.login_time',strtotime($this->login_time),(strtotime($this->login_time)+3600*24-1));
			if($this->last_time != '')
				$criteria->addBetweenCondition('t.last_time',strtotime($this->last_time),(strtotime($this->last_time)+3600*24-1));
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
	 * @return SAdmin the static model class
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
				$this->login_time=$this->last_time=$this->up_time=$this->add_time=time();
			else
				$this->up_time=time();			
			return true;
		}else
			return false;
	}
	
	/**
	 * 错误登录次数添加
	 * @param unknown $user
	 */
	public function login_error()
	{
		return $this->updateByPk($this->id,array(
				'login_error'=>new CDbExpression('`login_error`+1'),
				'error_count'=>new CDbExpression('`error_count`+1'),
		));
	}
	
	/**
	 * 登录后更新的数据
	 * @param unknown $time
	 * @return Ambigous <number, unknown>
	 */
	public function set_login($time,$login_unique)
	{
		return $this->updateByPk($this->id,array(
				'last_ip'=>$this->login_ip,
				'last_time'=>$this->login_time,
				'login_ip'=>Yii::app()->request->userHostAddress,
				'login_time'=>$time,
				'login_error'=>0,
				'login_unique'=>$login_unique,
				'count'=>new CDbExpression('`count`+1'),
		));
	}
	
	/**
	 * 登录状态
	 */
	public function login_status($_this)
	{
		if($this->status==-1)//删除，没有这个用户
			$_this->addError('password','用户名或密码错误');
		elseif($this->status==0)
			$_this->addError('password','用户名 被禁用了 请联系管理员');
		elseif($this->status !=1)
			$_this->addError('password','用户名或密码错误');
	}
	
	/**
	 * 验证密码是否对
	 * @param unknown $pwd
	 * @param unknown $password
	 * @return boolean
	 */
	public function validatePassword($pwd,$password)
	{
		return self::pwdEncrypt($pwd) === $password;
	}
	
	/**
	 * 密码加密函数
	 * @param unknown $pwd
	 * @return string
	 */
	public static function pwdEncrypt($pwd)
	{
		return md5(md5($pwd));
	}
	
	/**
	 * 查看原来的密码对不对
	 */
	public function is_old_pwd()
	{
		$admin=self::model()->findByPk(Yii::app()->admin->id,array('select'=>'password'));
		if(!$this->validatePassword($this->old_pwd, $admin->password))
			$this->addError('old_pwd', '原密码错误');
	}
}
