<?php

/**
 * This is the model class for table "{{type}}".
 *
 * The followings are the available columns in table '{{type}}':
 * @property string $id
 * @property integer $role_type
 * @property string $role_id
 * @property string $type
 * @property string $name
 * @property string $value
 * @property string $info
 * @property string $options
 * @property string $sort
 * @property string $add_time
 * @property string $up_time
 * @property integer $status
 */
class Type extends CActiveRecord
{	
	/*****************************************角色类型**************************************************************/
	/**
	 * role_type 管理员
	 * @var integer
	 */
	const role_type_admin = 0;
// 	/**
// 	 * 代理商
// 	 * @var integer
// 	 */
// 	const role_type_agent = 1;
// 	/**
// 	 * 商家
// 	 * @var integer
// 	 */
// 	const role_type_store = 2;
// 	/**
// 	 *	用户(组织者)
// 	 * @var integer
// 	 */
// 	const role_type_user = 4;
	/**
	 * 解释字段 role_type 的含义
	 * @var array
	 */
	public static $_role_type = array(
			self::role_type_admin=>'管理员',
// 			self::role_type_agent=>'代理商', 
// 			self::role_type_store=>'商家', 
// 			self::role_type_user=>'用户', 
	);
	/**
	 * 解释字段 role_type 的模块含义
	 * @var array
	 */
	public static $_role_type_modules= array(
			self::role_type_admin=>'admin',
// 			self::role_type_agent=>'agent',
// 			self::role_type_store=>'store',
// 			self::role_type_user=>'api',
	);
	
	/*****************************************类型用途**************************************************************/
	
	/**
	 *	类型用途 APP横幅
	 * @var integer
	 */
	const type_banner = 0;
	/**
	 *	类型用途 当季热销
	 * @var integer
	 */
	const type_hot = 1;
	/**
	 * 解释字段 type 的含义
	 * @var array
	 */
	public static $_type = array(
			self::type_banner=>'APP横幅', 
			self::type_hot=>'热销专题', 
	);
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
	public static $_status = array(-1=>'删除', '禁用', '正常');
	
	/**
	 * 搜索的时间类型
	 * @var array
	 */
	public $search_time_type;
	/**
	 *	解释搜索类型时间的含义 search_time_type
	 * @var string
	 */
	public static $_search_time_type = array('创建时间', '更新时间'); 
	/**
	 *	解释搜索类型时间 search_time_type 的字段搜索
	 * @var string
	 */
	public $__search_time_type = array('add_time','up_time');
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
		return '{{type}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			//array('role_type, role_id, type, name, value', 'required'),
			array('role_type, status, add_time, up_time, role_id, type, sort', 'numerical', 'integerOnly'=>true),
			array('role_id, type, sort', 'length', 'max'=>11),
			array('name', 'length', 'max'=>32),
			array('value, info, options', 'length', 'max'=>128),
			array('add_time, up_time', 'length', 'max'=>10),
			array('role_type, status', 'length', 'max'=>3),
			
			//角色类型
			array('role_type','in','range'=>array_keys(self::$_role_type)),
			//类型用途
			array('type','in','range'=>array_keys(self::$_type)),
			//状态
			array('status','in','range'=>array_keys(self::$_status)),
			
			//创建修改
			array('type, name, value, sort', 'required', 'on'=>'create, update'),
			array('type, name, value, sort, info, options', 'safe', 'on'=>'create, update'),
			array('search_time_type, search_start_time, search_end_time, id, role_type, role_id, add_time, up_time, status', 'unsafe', 'on'=>'create, update'),
			
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('search_time_type,search_start_time,search_end_time,id, role_type, role_id, type, name, value, info, options, sort, add_time, up_time, status', 'safe', 'on'=>'search'),
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
				'Type_Admin'=>array(self::BELONGS_TO, 'Admin', 'role_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'role_type' =>'角色',
			'role_id' => '管理员',
			'type' => '类型用途',
			'name' => '类型名',
			'value' => '类型值',
			'info' => '类型说明',
			'options' => '类型属性',
			'sort' => '排序',
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
		if($criteria ==='')
		{
			$criteria=new CDbCriteria;			
			$criteria->with = array(
				'Type_Admin'
			);
			if ($this->status != self::status_del )
			{
				$criteria->compare('t.status','<>-1');	
			}
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
			$criteria->compare('t.role_type',$this->role_type);
			
			$criteria->addCondition('`Type_Admin`.`username` LIKE :role_id OR `Type_Admin`.`name` LIKE :role_id');
			$criteria->params[':role_id'] = '%'.strtr($this->role_id,array('%'=>'\%', '_'=>'\_', '\\'=>'\\\\')).'%';
			
			$criteria->compare('t.type',$this->type,true);
			$criteria->compare('t.name',$this->name,true);
			$criteria->compare('t.value',$this->value,true);
			$criteria->compare('t.info',$this->info,true);
			$criteria->compare('t.options',$this->options,true);
			$criteria->compare('t.sort',$this->sort,true);
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
	 * @return Type the static model class
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
				$this->status = self::status_suc;
			}
			else
				$this->up_time=time();
			$this->role_id = Yii::app()->{self::$_role_type_modules[$this->role_type]}->id;
			return true;
		}else
			return false;
	}
	
	/**
	 * 获取 类型的模型数据
	 * @param unknown $type
	 * @param unknown $status
	 * @return Ambigous <multitype:static , mixed, static, NULL, multitype:unknown Ambigous <static, NULL> , multitype:, multitype:unknown >
	 */
	public function getTypeModels($type, $status = self::status_suc)
	{
		$criteria = new CDbCriteria;
		$criteria->addColumnCondition(array(
			'type'=>$type,					//类型
			'status'=>$status				//状态
		));
		$criteria->order = 'sort';
		return $this->findAll($criteria);
	}
	
	/**
	 * 获取当前类型的 类型模型
	 * @param unknown $id
	 * @param unknown $type
	 * @param unknown $status
	 * @return Ambigous <static, mixed, NULL, multitype:static , multitype:unknown Ambigous <static, NULL> , multitype:, multitype:unknown >
	 */
	public function getTypeModel($id, $type, $status = self::status_suc)
	{
		$criteria = new CDbCriteria;
		$criteria->addColumnCondition(array(
				'type'=>$type,					//类型
				'status'=>$status				//状态
		));
		return $this->findByPk($id, $criteria);
	}
}
