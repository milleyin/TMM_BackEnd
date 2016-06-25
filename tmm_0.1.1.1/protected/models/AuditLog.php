<?php

/**
 * This is the model class for table "{{audit_log}}".
 *
 * The followings are the available columns in table '{{audit_log}}':
 * @property string $id
 * @property string $audit_id
 * @property integer $audit_who
 * @property integer $audit_element
 * @property string $element_id
 * @property integer $audit
 * @property string $info
 * @property string $url
 * @property string $ip
 * @property string $address
 * @property string $add_time
 * @property string $up_time
 * @property integer $status
 */
class AuditLog extends CActiveRecord
{
	/*******************************审核类型**********************************/
	/**
	 * 审核通过
	 * @var integer
	 */
	const pass=1;
	/**
	 * 审核没通过
	 * @var integer
	 */
	const nopass=-1;
	
	/*******************************要审核的元素**********************************/
	/**
	 * 组织者  ===  用户
	 * @var integer
	 */
	const organizer=1;
	/**
	 * 项目（吃）
	 * @var integer
	 */
	const items_eat=2;
	/**
	 * 项目（住）
	 * @var integer
	 */
	const items_hotel=3;
	/**
	 * 项目（玩）
	 * @var integer
	 */
	const items_play=4;
	/**
	 * 商品（点）
	 * @var integer
	 */
	const shops_dot=5;
	/**
	 * 商品（ 线 ）
	 * @var integer
	 */
	const shops_thrand=6;
	/**
	 * 商品（结伴游）
	 * @var integer
	 */
	const shops_group=7;
	/**
	 * 账单提现（组织者）
	 * @var integer
	 */
	const cash_organizer=8;
	/**
	 * 账单提现（商家）
	 * @var integer
	 */
	const cash_store=9;
	/**
	 * 账单提现（代理）
	 * @var integer
	 */
	const cash_agent=10;
	/**
	 * 账单提现（代理）
	 * @var integer
	 */
	const cash_user=11;
	/**
	 *  商品（活动）
	 * @var integer
	 */
	const shops_actives=12;
	
	/*********************************审核者***********************************/
	/**
	 * 管理员
	 * @var integer
	 */
	const admin=1;
	/*******************************我是分隔线**********************************/
	
	/*******************************审核类型**********************************/
	/**
	 * 解释字段 audit 的含义
	 * @var array
	 */
	public static $_audit=array(-1=>'没通过',1=>'通过');
	
	/*********************************审核者***********************************/
	/**
	 * 解释字段 manage_who 的 模块名 用来获取当前登录ID
	 * @var array
	 */
	public static $_audit_modules=array(1=>'admin');
	/**
	 * 解释字段 manage_who 的含义
	 * @var array
	 */
	public static $_audit_who=array(1=>'管理员');

	/**
	 * 解释字段 manage_who 的关联关系名 的含义
	 * @var array
	 */
	public static $_audit_who_type=array(1=>'Audit_Admin');
	
	/*******************************要审核的元素**********************************/
	/**
	 * 解释字段 audit_element 的含义
	 * @var array
	 */
	public static $_audit_element=array(
			1=>'账号 组织者',
			2=>'项目 吃',
			3=>'项目 住',
			4=>'项目 玩',
			5=>'商品 点',
			6=>'商品 线 ',
			7=>'商品 结伴游',
			8=>'提现 组织者',
			9=>'提现 商家',
			10=>'提现 代理商',
			11=>'提现 用户',
			12=>'商品 活动',
	);

	/**
	 * 解释字段 audit_element 的关联关系名 含义
	 * @var array
	 */
	public static $_audit_element_type=array(
		1=>'Audit_User',
		2=>'Audit_Items',
		3=>'Audit_Items',
		4=>'Audit_Items',
		5=>'Audit_Shops',
		6=>'Audit_Shops',
		7=>'Audit_Shops',
		8=>'Audit_User',
		9=>'Audit_StoreUser',
		10=>'Audit_Agent',
		12=>'Audit_Actives',
	);

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
		return '{{audit_log}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			//array('audit_id, audit_who, audit_element, element_id, audit, info, url, add_time, up_time', 'required'),
			array('audit_who, audit_element, audit, status', 'numerical', 'integerOnly'=>true),
			array('audit_id, element_id', 'length', 'max'=>11),
			array('url', 'length', 'max'=>200),
			array('ip', 'length', 'max'=>15),
			array('address', 'length', 'max'=>100),
			array('add_time, up_time', 'length', 'max'=>10),
			//添加记录
			array('info', 'length','min'=>10,'max'=>25),
			array('info', 'required','on'=>'create'),
			array('info','safe','on'=>'create'),
			array('search_time_type,search_start_time,search_end_time,id, audit_id, audit_who, audit_element, element_id, audit, url, ip, address, add_time, up_time, status','unsafe','on'=>'create'),
			
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('search_time_type,search_start_time,search_end_time,id, audit_id, audit_who, audit_element, element_id, audit, info, url, ip, address, add_time, up_time, status', 'safe', 'on'=>'search'),
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
			 * 审核组织者
			 */
			'Audit_Organizer'=>array(self::BELONGS_TO,'Organizer','element_id'),
			/**
			 * 审核人
			 */
			'Audit_Admin'=>array(self::BELONGS_TO,'Admin','audit_id'),
			/**
			 * 审核元素（账号 组织者，提现 组织者）
			 */
			'Audit_User'=>array(self::BELONGS_TO,'User','element_id'),
			/**
			 * 审核元素（项目吃、住、玩）
			 */
			'Audit_Items'=>array(self::BELONGS_TO,'Items','element_id'),
			//项目吃	
			'Audit_Eat'=>array(self::BELONGS_TO,'Eat','element_id'),
			//项目住
			'Audit_Hotel'=>array(self::BELONGS_TO,'Hotel','element_id'),
			//项目玩
			'Audit_Play'=>array(self::BELONGS_TO,'Play','element_id'),
			/**
			 * 审核元素（商品点、线、结伴游）
			 */
			'Audit_Shops'=>array(self::BELONGS_TO,'Shops','element_id'),
			/**
			 * 审核元素（提现 商家）
			 */
			'Audit_StoreUser'=>array(self::BELONGS_TO,'StoreUser','element_id'),
			/**
			 * 审核元素（提现 代理商）
			 */
			'Audit_Agent'=>array(self::BELONGS_TO,'Agent','element_id'),
			//线路(点)
			'Audit_Dot'=>array(self::BELONGS_TO,'Dot','element_id'),
			//线路(线)
			'Audit_Thrand'=>array(self::BELONGS_TO,'Thrand','element_id'),
			//线路(结伴游)
			'Audit_Group'=>array(self::BELONGS_TO,'Group','element_id'),
			//线路(结伴游)
			'Audit_Actives'=>array(self::BELONGS_TO,'Actives','element_id'),
			//结算申请（初审） =  审核
			'Audit_CashFiret'=>array(self::BELONGS_TO,'Cash','element_id'),
			//退款申请（初审） =  审核
			'Audit_RefundLogFiret'=>array(self::BELONGS_TO,'RefundLog','element_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'audit_id' => '审核人',
			'audit_who' => '审核人类型',
			'audit_element' => '审核元素类型',
			'element_id' => '元素名称',
			'audit' => '审核状态',
			'info' => '未通过原因',
			'url' => '操作链接',
			'ip' => '操作ip',
			'address' => '操作地址',
			'add_time' => '创建时间',
			'up_time' => '更新时间',
			'status' => '记录状态1正常0禁用-1删除',
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
				'Audit_Admin'=>array('select'=>'name,username'),
				'Audit_User'=>array('select'=>'phone,nickname'),
				'Audit_Items'=>array('select'=>'name'),
				'Audit_Shops'=>array('select'=>'name'),
				'Audit_StoreUser'=>array('select'=>'phone'),
				'Audit_Agent'=>array('select'=>'phone'),
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

			$criteria->compare('t.audit_who',$this->audit_who);
			if (isset( self::$_audit_who_type[$this->audit_who])) {
				$_type = self::$_audit_who_type[$this->audit_who];
				if($_type === 'Audit_Admin')
					$criteria->compare('Audit_Admin.username',$this->audit_id,true);
			} else {
				$criteria->compare('t.audit_id',$this->audit_id,true);
			}

			$criteria->compare('t.audit_element',$this->audit_element);
			if (isset( self::$_audit_element_type[$this->audit_element])) {
				$_type = self::$_audit_element_type[$this->audit_element];
				if($_type === 'Audit_User')
					$criteria->compare('Audit_User.phone',$this->element_id,true);
				if($_type === 'Audit_Items')
					$criteria->compare('Audit_Items.name',$this->element_id,true);
				if($_type === 'Audit_Shops')
					$criteria->compare('Audit_Shops.name',$this->element_id,true);
				if($_type === 'Audit_StoreUser')
					$criteria->compare('Audit_StoreUser.phone',$this->element_id,true);
				if($_type === 'Audit_Agent')
					$criteria->compare('Audit_Agent.phone',$this->element_id,true);
			} else {
				$criteria->compare('t.element_id',$this->element_id,true);
			}
			$criteria->compare('t.audit',$this->audit);
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
	 * @return AuditLog the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


	/**
	 * 根据审核人的用户类型获取用户名或手机号
	 * @param $type
	 * @param $data
	 * @return string
	 */
	public static function audit_who_type($type, $data)
	{
		if (isset( self::$_audit_who_type[$type])) 
			return isset($data->{self::$_audit_who_type[$type]}->username,$data->{self::$_audit_who_type[$type]}->name) ? $data->{self::$_audit_who_type[$type]}->username . '['. $data->{self::$_audit_who_type[$type]}->name . ']' : '未知角色';
		 else 
			return '未知角色';
	}

	/**
	 * 根据审核人的用户类型获取用户名或手机号，显示
	 * @param $mode
	 * @param $type
	 * @return string
	 */
	public static function show_who_type($model, $type)
	{
		return self::audit_who_type( $type,$model);
	}
	
	/**
	 * 根据审核元素的类型获取名称
	 * @param $type
	 * @param $data
	 * @return string
	 */
	public static function audit_element_type($type, $data) 
	{
		if (isset( self::$_audit_element_type[$type])) 
		{
			$_type = self::$_audit_element_type[$type];
			if($_type === 'Audit_User')
				return isset($data->$_type->phone,$data->$_type->nickname) ? $data->$_type->phone . '['. $data->$_type->nickname . ']' : '未知角色';
			if($_type === 'Audit_Items' || $_type === 'Audit_Shops')
				return isset($data->$_type->name) ? $data->$_type->name : '未知角色';
			if($_type === 'Audit_StoreUser' || $_type === 'Audit_Agent')
				return isset($data->$_type->phone) ? $data->$_type->phone : '未知角色';
		} else 
			return '未知角色';
	}

	/**
	 * 根据审核元素的类型获取名称，显示
	 * @param $mode
	 * @param $type
	 * @return string
	 */
	public static function show_element_type($model, $type) 
	{
		return self::audit_element_type($type,$model);
	}

	/**
	 * 查询审核未通过原因
	 */
	public static function select_audit_nopass($audit_element,$element_id,$audit=self::nopass){
		return self::model()->find(array(
			'select'=>'info',
			'condition'=>'`audit_element`=:audit_element AND `element_id`=:element_id AND `audit`=:audit',
			'params'=>array(
				':audit_element'=>$audit_element,
				':element_id'=>$element_id,
				':audit'=>$audit,
			),
			'order'=>' id desc '
		));
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
				$this->up_time=$this->add_time=time();
				if(isset(self::$_audit_modules[$this->audit_who]))
				{
					$modules=self::$_audit_modules[$this->audit_who];
					$this->audit_id=Yii::app()->$modules->id;		
					$this->url = Yii::app()->request->hostInfo.Yii::app()->request->getUrl();//$this->route;
					$this->ip =Yii::app()->request->userHostAddress;
				}else
					return false;
			}else
				$this->up_time=time();			
			return true;
		}else
			return false;
	}
	
	/**
	 * 获取审核日志 没有找到 返回默认对象
	 * @param unknown $audit_element
	 * @param unknown $element_id
	 * @param unknown $audit_who
	 * @param string $audit_id
	 * @return Ambigous <static, mixed, NULL, multitype:static , multitype:unknown Ambigous <static, NULL> , multitype:, multitype:unknown >|AuditLog
	 */
	public static function get_audit_log($audit_element,$element_id,$audit=self::nopass,$audit_who=self::admin,$audit_id='')
	{
		$criteria=new CDbCriteria;
		$criteria->addColumnCondition(array(
			'audit_element'=>$audit_element,
			'element_id'=>$element_id,
			'audit_who'=>$audit_who,
			'audit'=>$audit
		));
		if($audit_id !='')
			$criteria->addColumnCondition(array(
					'audit_id'=>$audit_id,
			));
			$criteria->order='add_time desc';		
		$model=self::model()->find($criteria);
		if($model)
			return $model;
		else
			return new AuditLog;
	}
}
