<?php

/**
 * This is the model class for table "{{reason}}".
 *
 * The followings are the available columns in table '{{reason}}':
 * @property string $id
 * @property string $admin_id
 * @property integer $order_type
 * @property integer $sale_type
 * @property integer $cargo_type
 * @property integer $role_type
 * @property string $name
 * @property string $reason
 * @property string $remark
 * @property integer $sort
 * @property string $add_time
 * @property string $up_time
 * @property integer $status
 */
class Reason extends CActiveRecord
{
	/**
	 *  售后类型 （退款 )
	 * @var integer
	 */
	const sale_type_refund = 1;
	/**
	 *  售后类型 （换货)
	 * @var integer
	 */
	const sale_type_cargo = 2;
	/**
	 * 解释字段 sale_type 的含义 售后类型
	 * @var array
	 */
	public static $_sale_type = array(1=>'退款', 2=>'换货');
	
	/**
	 *  货物类型 （无货物)
	 * @var integer
	 */
	const cargo_type_none = 0;
	/**
	 *  货物类型 （未收到货物)
	 * @var integer
	 */
	const cargo_type_receive_none = 1;
	/**
	 *  货物类型 （已收到货物)
	 * @var integer
	 */
	const cargo_type_receive = 2;
	/**
	 *  货物类型 （已寄回货物)
	 * @var integer
	 */
	const cargo_type_back = 3;
	/**
	 * 解释字段 cargo_type 的含义 货物类型
	 * @var array
	 */
	public static $_cargo_type = array(0=>'无货物', 1=>'未收到货物', 2=>'已收到货物' ,3=>'已寄回货物');
	
	/**
	 * 归属角色 管理员
	 * @var integer
	 */
	const role_type_admin = 0;
	/**
	 * 归属角色 商家
	 * @var integer
	 */
	const role_type_store = 2;
	/**
	 *	归属角色 用户(组织者)
	 * @var integer
	 */
	const role_type_user = 4;
	/**
	 * 解释字段 account_type 的含义
	 * @var array
	 */
	public static $_role_type = array(0=>'管理员', 2=>'商家', 4=>'用户');
	
	/**
	 * 解释字段 order_type 的含义 订单类型
	 * @var array
	 */
	public static $_order_type=array(1=>'自助游(点)',2=>'自助游(线)',3=>'活动(旅游)');
	
	/**
	 * 记录状态 删除
	 * @var integer
	 */
	const status_del=-1;
	/**
	 * 记录状态 禁用
	 * @var integer
	 */
	const status_disable=0;
	/**
	 * 记录状态 正常
	 * @var integer
	 */
	const status_normal=1;
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
		return '{{reason}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			//array('remark, add_time, up_time', 'required'),
			array('order_type, sale_type, cargo_type, role_type, sort, status', 'numerical', 'integerOnly'=>true),
			array('admin_id', 'length', 'max'=>11),
// 			array('name', 'length', 'max'=>64),
// 			array('reason', 'length', 'max'=>128),
			array('add_time, up_time', 'length', 'max'=>10),
			
			array('name', 'length', 'max'=>16),
			array('reason', 'length', 'max'=>24),
			array('remark', 'length', 'max'=>128),
			array('sort', 'length', 'max'=>4),
			//角色类型
			array('role_type','in','range'=>array_keys(self::$_role_type)),
			//订单类型
			array('order_type','in','range'=>array_keys(self::$_order_type)),
			//售后类型
			array('sale_type','in','range'=>array_keys(self::$_sale_type)),
			//货物类型
			array('cargo_type','in','range'=>array_keys(self::$_cargo_type)),
				
			//创建、修改		
			array('order_type, sale_type, cargo_type, role_type, name, reason', 'required', 'on'=>'create,update'),
			array('sale_type','saleType', 'on'=>'create,update'),
			array('cargo_type','cargoType', 'on'=>'create,update'),
			array('order_type, sale_type, cargo_type, role_type, name, reason, remark, sort','safe', 'on'=>'create,update'),
			array('search_time_type,search_start_time,search_end_time,id, admin_id, add_time, up_time, status','unsafe','on'=>'create,update'),
			
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('search_time_type,search_start_time,search_end_time,id, admin_id, order_type, sale_type, cargo_type, role_type, name, reason, remark, sort, add_time, up_time, status', 'safe', 'on'=>'search'),
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
				'Reason_Admin'=> array(self::BELONGS_TO, 'Admin', 'admin_id'),
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
			'order_type' => '订单类型',
			'sale_type' => '售后类型',
			'cargo_type' => '货物类型',
			'role_type' => '角色类型',
			'name' => '名称',
			'reason' => '原因',
			'remark' => '备注',
			'sort' => '排序',
			'add_time' => '添加时间',
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
			$criteria->compare('t.status','<>-1');
			
			$criteria->with = array(
				'Reason_Admin'=>array('select'=> 'id, username, name, phone'),
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
			$criteria->compare('t.id',$this->id,true);
			
			if (preg_match('/^(?:\s*(<>|<=|>=|<|>|=))[0-9]*$/',$this->admin_id))
				$criteria->compare('t.admin_id',$this->admin_id,true);
			else if ($this->admin_id != null)
			{
				$conditions = array('Reason_Admin.username LIKE :admin_id_like');
				$conditions[] = 'Reason_Admin.name LIKE :admin_id_like';
				$conditions[] = 'Reason_Admin.phone LIKE :admin_id_like';
				$criteria->addCondition( implode(' OR ', $conditions));
				$criteria->params['admin_id_like'] = '%'.strtr($this->admin_id,array('%'=>'\%', '_'=>'\_', '\\'=>'\\\\')).'%';
			}
			$criteria->compare('t.order_type',$this->order_type);
			$criteria->compare('t.sale_type',$this->sale_type);
			$criteria->compare('t.cargo_type',$this->cargo_type);
			$criteria->compare('t.role_type',$this->role_type);
			$criteria->compare('t.name',$this->name,true);
			$criteria->compare('t.reason',$this->reason,true);
			$criteria->compare('t.remark',$this->remark,true);
			$criteria->compare('t.sort',$this->sort);
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
	 * @return Reason the static model class
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
			{
				$this->up_time = $this->add_time = time();
				$this->status = self::status_normal;
			}
			else
				$this->up_time = time();
			$this->admin_id = Yii::app()->admin->id;
			return true;
		}else
			return false;
	}
	
	/**
	 * 售后类型
	 */
	public function saleType()
	{	
		if ($this->sale_type != '' && in_array($this->order_type, array(Order::order_type_dot, Order::order_type_dot, Order::order_type_thrand, Order::order_type_actives_tour,)))
		{
			if ($this->sale_type == self::sale_type_cargo)
				$this->addError('sale_type', '该订单 货物不存在');
		}
	}
	
	/**
	 * 货物类型
	 */
	public function cargoType()
	{
		if ($this->cargo_type != '' && in_array($this->cargo_type, array(Order::order_type_dot, Order::order_type_dot, Order::order_type_thrand, Order::order_type_actives_tour,)))
		{
			if ($this->cargo_type != self::cargo_type_none)
				$this->addError('cargo_type', '该订单 货物不存在');
		}
	}
}
