<?php

/**
 * This is the model class for table "{{manage_log}}".
 *
 * The followings are the available columns in table '{{manage_log}}':
 * @property string $id
 * @property string $manage_id
 * @property integer $manage_who
 * @property integer $manage_type
 * @property string $info
 * @property string $url
 * @property string $ip
 * @property string $address
 * @property string $add_time
 * @property string $up_time
 * @property integer $status
 */
class ManageLog extends CActiveRecord
{
	/**
	 * 查询
	 * @var integer
	 */
	const select=1;
	/**
	 * 创建
	 * @var integer
	 */
	const create=2;
	/**
	 * 更新
	 * @var integer
	 */
	const update=3;
	/**
	 * 删除
	 * @var integer
	 */
	const delete=4;
	/**
	 * 彻底删除
	 * @var integer
	 */
	const clear=5;

	/**
	 * 管理员
	 * @var integer
	 */
	const admin=1;
	/**
	 * 代理商
	 * @var integer
	 */
	const agent=2;
	/**
	 * 商家
	 * @var integer
	 */
	const store=3;
	/**
	 * 用户
	 * @var integer
	 */
	const user=4;

	/**
	 * 解释字段 manage_who 的 模块名 用来获取当前登录ID
	 * @var array
	 */
	public static $_manage_modules=array(1=>'admin',2=>'agent',3=>'store',4=>'api');
	/**
	 * 解释字段 manage_type 的含义
	 * @var array
	 */
	public static $_manage_type=array(1=>'查询',2=>'增加',3=>'修改',4=>'删除',5=>'彻底删除');
	/**
	 * 解释字段 manage_who 的含义
	 * @var array
	 */
	public static $_manage_who=array(1=>'管理员',2=>'代理商',3=>'商家',4=>'用户');
	/**
	 * 解释字段 manage_type 的关联关系名 含义
	 * @var array
	 */
	public static $__type=array(1=>'Log_Admin',2=>'Log_Agent',3=>'Log_StoreUser',4=>'Log_User');
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
		return '{{manage_log}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, manage_id, manage_type, info, url, add_time, up_time', 'required'),
			array('manage_who, manage_type, status', 'numerical', 'integerOnly'=>true),
			array('id, manage_id', 'length', 'max'=>11),
			array('url', 'length', 'max'=>200),
			array('ip', 'length', 'max'=>15),
			array('address', 'length', 'max'=>100),
			array('add_time, up_time', 'length', 'max'=>10),
			
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('search_time_type,search_start_time,search_end_time,id, manage_id, manage_who, manage_type, info, url, ip, address, add_time, up_time, status', 'safe', 'on'=>'search'),
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
			//管理员的操作日志
			'Log_Admin'=>array(self::BELONGS_TO,'Admin','manage_id'),
			//代理商的操作日志
			'Log_Agent'=>array(self::BELONGS_TO,'Agent','manage_id'),
			//商家操作日志
			'Log_StoreUser'=>array(self::BELONGS_TO,'StoreUser','manage_id'),
			//用户操作日志
			'Log_User'=>array(self::BELONGS_TO,'User','manage_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'manage_id' => '用户名称',
			'manage_who' => '用户类型',
			'manage_type' => '操作类型',
			'info' => '详情',
			'url' => '操作链接',
			'ip' => '操作ip',
			'address' => '操作地址',
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
				'Log_Admin'=>array('select'=>'username,name'),
				'Log_Agent'=>array('select'=>'phone'),
				'Log_StoreUser'=>array('select'=>'phone'),
				'Log_User'=>array('select'=>'phone'),
			);
			$criteria->compare('t.status','<>-1');
			if($this->search_time_type != '' && isset($this->__search_time_type[$this->search_time_type]))
			{
				if($this->search_start_time !='' && $this->search_end_time !='')
					$criteria->addBetweenCondition('t.'.$this->__search_time_type[$this->search_time_type],strtotime($this->search_start_time),strtotime($this->search_end_time));
				elseif($this->search_start_time !='' && $this->search_end_time =='')
					$criteria->compare('t.'.$this->__search_time_type[$this->search_time_type],'>='.strtotime($this->search_start_time));
				elseif($this->search_start_time =='' && $this->search_end_time !='')
					$criteria->compare('t.'.$this->__search_time_type[$this->search_time_type],'<='.strtotime($this->search_end_time));
			}			
			$criteria->compare('t.id',$this->id,true);
			$criteria->compare('t.manage_who',$this->manage_who);
			if (isset( self::$__type[$this->manage_who])) 
			{
				$_type = self::$__type[$this->manage_who];
				if($_type === 'Log_Admin')
					$criteria->compare('Log_Admin.name',$this->manage_id,true);
				elseif($_type === 'Log_Agent')
					$criteria->compare('Log_Agent.phone',$this->manage_id,true);
				elseif($_type === 'Log_StoreUser')
					$criteria->compare('Log_StoreUser.phone',$this->manage_id,true);
				elseif($_type === 'Log_User')
					$criteria->compare('Log_User.phone',$this->manage_id,true);
			}	
			$criteria->compare('t.manage_type',$this->manage_type);
			$criteria->compare('t.info',$this->info,true);
			$criteria->compare('t.url',$this->url,true);
			$criteria->compare('t.ip',$this->ip,true);
			$criteria->compare('t.address',$this->address,true);
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
	 * @return ManageLog the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


	/**
	 * 根据操作用户的类型获取用户名或手机号
	 * @param $type
	 * @param $data
	 * @return string
	 */
	public static function manage_type($type, $data) {
		if (isset( self::$__type[$type])) {
			$_type = self::$__type[$type];
			if($_type == 'Log_Admin')
				return $data->$_type->username . '[' . $data->$_type->name . ']';
			elseif($_type ==='Log_Agent' || $_type == 'Log_StoreUser' || $_type == 'Log_User')
				return isset($data->$_type->phone) ? $data->$_type->phone:'';
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
			if($_type == 'Log_Admin')
				return $mode->$_type->username . '[' . $mode->$_type->name . ']';
			elseif($_type == 'Log_Agent' || $_type == 'Log_StoreUser' || $_type == 'Log_User')
				return isset($mode->$_type->phone) ? $mode->$_type->phone : '';
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
}
