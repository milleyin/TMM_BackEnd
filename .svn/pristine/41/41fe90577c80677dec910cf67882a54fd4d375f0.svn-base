<?php

/**
 * This is the model class for table "{{select}}".
 *
 * The followings are the available columns in table '{{select}}':
 * @property string $id
 * @property string $admin_id
 * @property integer $type
 * @property string $to_id
 * @property string $select_id
 * @property string $sort
 * @property string $add_time
 * @property string $up_time
 * @property integer $status
 */
class Select extends CActiveRecord
{
	/**
	 * 删除
	 */
	const status_del = -1;
	/**
	 * 正常
	 */
	const status_dis = 0;
	/**
	 * 禁用
	 */
	const status_suc = 1;
	/**
	 * 解释字段 status 的含义
	 * @var array
	 */
	public static $_status = array(-1=>'删除', '禁用', '正常');
	
	/**
	 *	觅趣专题
	 * @var integer
	 */
	const type_actives = 0;
	/**
	 * 周边专题
	 * @var integer
	 */
	const type_nearby = 1;
	/**
	 * 解释字段 type 的含义
	 * @var array
	 */
	public static $_type = array(
		self::type_actives=>'觅趣专题',
		self::type_nearby=>'周边专题'
	);
	/**
	 * 解释其关联的名字 来自
	 * @var array
	 */
	public static $__type_to = array(
		self::type_actives=>'Select_Ad',
		self::type_nearby=>'Select_Ad'
	);
	/**
	 * 解释其关联的名字 来自
	 * @var array
	 */
	public static $__type_to_name = array(
			self::type_actives=>'name',
			self::type_nearby=>'name'
	);
	/**
	 * 解释其关联的名字 选中的
	 * @var array
	 */
	public static $__type_select = array(
			self::type_actives=>'Select_Shops',
			self::type_nearby=>'Select_Shops'
	);
	/**
	 * 解释其关联的名字 选中的
	 * @var array
	 */
	public static $__type_select_name = array(
			self::type_actives=>'name',
			self::type_nearby=>'name'
	);
	/**
	 * 解释 类型 其他表的关系
	 * @var unknown
	 */
	public static $__ad = array(
		self::type_actives =>Ad::type_actives,
		self::type_nearby => Ad::type_nearby,
	);
	
	/**
	 * 搜索的时间类型
	 * @var array
	 */
	public $search_time_type;
	/**
	 *	解释搜索类型时间的含义 search_time_type
	 * @var string
	 */
	public static $_search_time_type = array('add_time', 'up_time'); 
	/**
	 *	解释搜索类型时间 search_time_type 的字段搜索
	 * @var string
	 */
	public $__search_time_type = array('add_time', 'up_time'); 
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
	 * 
	 * @var unknown
	 */	
	public $distance;
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{select}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			//array('admin_id, to_id, select_id', 'required'),
			array('admin_id, to_id, select_id, sort, type, add_time, up_time, status', 'numerical', 'integerOnly'=>true),
			array('admin_id, to_id, select_id, sort', 'length', 'max'=>11),
			array('add_time, up_time', 'length', 'max'=>10),
			
			//选择添加
			array('to_id, select_id, type', 'required', 'on'=>'create'),
			array('to_id, select_id, type', 'safe', 'on'=>'create'),
			array('search_time_type, search_start_time, search_end_time, id, admin_id, sort, add_time, up_time, status', 'unsafe', 'on'=>'create'),
			
			//更新排序
			array('sort', 'required', 'on'=>'sort'),
			array('sort', 'length', 'max'=>10),
			array('sort', 'safe', 'on'=>'sort'),
			array('search_time_type, search_start_time, search_end_time, id, admin_id, type, to_id, select_id, add_time, up_time, status', 'unsafe', 'on'=>'sort'),
			
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('search_time_type, search_start_time, search_end_time, id, admin_id, type, to_id, select_id, sort, add_time, up_time, status', 'safe', 'on'=>'search'),
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
				//归属给广告
				'Select_Ad'=>array(self::BELONGS_TO, 'Ad', 'to_id'),
				//归属给广告
				'Select_Actives'=>array(self::BELONGS_TO, 'Actives', 'select_id'),
				//归属给广告
				'Select_Shops'=>array(self::BELONGS_TO, 'Shops', 'select_id'),
				//归属给管理员
				'Select_Admin'=>array(self::BELONGS_TO, 'Admin', 'admin_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'admin_id' => '管理员账号',
			'type' => '类型',
			'to_id' => '归属者',
			'select_id' => '选中者',
			'sort' => '排序',
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
		if ($criteria === '')
		{
			$criteria = new CDbCriteria;
			$criteria->with = array(
					'Select_Ad',
					'Select_Shops',
					'Select_Admin',
			);
			if ($this->status != self::status_del)
				$criteria->compare('t.status', '<>-1');
			if ($this->search_time_type != '' && isset($this->__search_time_type[$this->search_time_type]))
			{
				if ($this->search_start_time != '' && $this->search_end_time !='')
					$criteria->addBetweenCondition('t.'.$this->__search_time_type[$this->search_time_type], strtotime($this->search_start_time), strtotime($this->search_end_time)+3600*24-1);
				else if ($this->search_start_time != '' && $this->search_end_time =='')
					$criteria->compare('t.'.$this->__search_time_type[$this->search_time_type], '>='.strtotime($this->search_start_time));
				else if ($this->search_start_time == '' && $this->search_end_time !='')
					$criteria->compare('t.'.$this->__search_time_type[$this->search_time_type], '<=' . (strtotime($this->search_end_time)+3600*24-1));
			}
			$criteria->compare('t.id', $this->id, true);
			if ($this->admin_id != '')
			{
				$criteria->addCondition('`Select_Admin`.`username` LIKE :admin_id OR `Select_Admin`.`name` LIKE :admin_id');
				$criteria->params[':admin_id'] = '%' . strtr($this->admin_id, array('%'=>'\%', '_'=>'\_', '\\'=>'\\\\')) . '%';
			}
			$criteria->compare('t.type', $this->type);
			// 搜索
			$criteria_to = $criteria_select = false;
			if ($this->to_id != null && isset(self::$_type[$this->type], self::$__type_to[$this->type], self::$__type_to_name[$this->type]))
			{
				if (strpos($this->to_id, '=') === 0)
				{
					$criteria->compare('t.to_id', $this->to_id);
					$criteria_to = true;
				}
				else
				{
					$relation = self::$__type_to[$this->type];
					$couditions = array();
					$name = self::$__type_to_name[$this->type];
					$couditions[] = '`t`.`to_id`=:to_id';
					$criteria->params[':to_id'] = $this->to_id;
					$couditions[] = $relation . '.' . $name . ' LIKE :like_to_id';
					$criteria->addCondition( implode(' OR ', $couditions));
					$criteria->params[':like_to_id'] = '%' . strtr($this->to_id, array('%'=>'\%', '_'=>'\_', '\\'=>'\\\\')) . '%';
					$criteria_to = true;
				}
			}
			if ( !$criteria_to && $this->to_id != null)
			{
				$relations = self::$__type_to;
				$couditions = array();
				$couditions[] = '`t`.`to_id`=:to_id';
				foreach ($relations as $type=>$relation)
				{
					if(isset(self::$_type[$type], self::$__type_to_name[$type]))
						$couditions[]='(`t`.`type`=' . $type . ' AND `' . $relation . '`.`' . self::$__type_to_name[$type] . '` LIKE :to_id_like)';
				}
				$criteria->addCondition(implode(' OR ', $couditions));
				$criteria->params[':to_id'] = $this->to_id;
				$criteria->params[':to_id_like'] = '%'.strtr($this->to_id, array('%'=>'\%', '_'=>'\_', '\\'=>'\\\\')).'%';
			}
			if ($this->select_id != null && isset(self::$_type[$this->type], self::$__type_select[$this->type], self::$__type_select_name[$this->type]))
			{
				$relation = self::$__type_select[$this->type];
				$couditions = array();
				$name = self::$__type_select_name[$this->type];
				$couditions[] = '`t`.`select_id`=:select_id';
				$criteria->params[':select_id'] = $this->select_id;
				$couditions[] = $relation . '.' . $name . ' LIKE :like_select_id';
				$criteria->addCondition( implode(' OR ', $couditions));
				$criteria->params[':like_select_id'] = '%' . strtr($this->select_id, array('%'=>'\%', '_'=>'\_', '\\'=>'\\\\')) . '%';
				$criteria_select = true;
			}
			if ( !$criteria_select && $this->select_id != null)
			{
				$relations = self::$__type_select;
				$couditions = array();
				$couditions[] = '`t`.`select_id`=:select_id';
				foreach ($relations as $type=>$relation)
				{
					if(isset(self::$_type[$type], self::$__type_select_name[$type]))
						$couditions[]='(`t`.`type`=' . $type . ' AND `' . $relation . '`.`' . self::$__type_select_name[$type] . '` LIKE :select_id_like)';
				}
				$criteria->addCondition(implode(' OR ', $couditions));
				$criteria->params[':select_id'] = $this->select_id;
				$criteria->params[':select_id_like'] = '%' . strtr($this->select_id, array('%'=>'\%', '_'=>'\_', '\\'=>'\\\\')).'%';
			}
			
			$criteria->compare('t.sort', $this->sort, true);
			if ($this->add_time != '')
				$criteria->addBetweenCondition('t.add_time', strtotime($this->add_time), (strtotime($this->add_time)+3600*24-1));
			if ($this->up_time != '')
				$criteria->addBetweenCondition('t.up_time', strtotime($this->up_time), (strtotime($this->up_time)+3600*24-1));
			$criteria->compare('t.status', $this->status);
		}
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
					'pageSize'=>Yii::app()->params['admin_pageSize'],
			),
			'sort'=>array(
					'defaultOrder'=>'`t`.`add_time` desc', //设置默认排序
			),
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Select the static model class
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
		if (parent::beforeSave())
		{
			if ($this->isNewRecord)
				$this->up_time = $this->add_time = time();
			else
				$this->up_time = time();
			$this->admin_id = Yii::app()->admin->id;
			return true;
		}
		else
			return false;
	}
	
	/**
	 * 获取来自的模型
	 * @param molde $model
	 * @param integer $type
	 * @return NULL|model
	 */
	public function getToName($model, $isModel = false)
	{
		if (isset(self::$_type[$model->type], self::$__type_to[$model->type], self::$__type_to_name[$model->type]))
		{
			$relation = self::$__type_to[$model->type];		
			if ($isModel)
				return isset($model->$relation) ? $model->$relation : null;
			$name = self::$__type_to_name[$model->type];
			return isset($model->$relation, $model->$relation->$name) ? $model->$relation->$name : null;
		}
		return null;
	}
	
	/**
	 * 获取 选择的数据模型
	 * @param molde $model
	 * @param integer $type
	 * @return NULL|model
	 */
	public function getSelectName($model, $isModel = false)
	{
		if (isset(self::$_type[$model->type], self::$__type_select[$model->type], self::$__type_select_name[$model->type]))
		{
			$relation = self::$__type_select[$model->type];		
			if ($isModel)
				return isset($model->$relation) ? $model->$relation : null;
			$name = self::$__type_select_name[$model->type];
			return isset($model->$relation, $model->$relation->$name) ? $model->$relation->$name : null;
		}
		return null;
	}
	
	/**
	 * 检测是否已经选中了
	 * @param integer $select_id
	 * @param integer $to_id
	 * @param integer $type
	 * @return model
	 */
	public static function checkedSelected($select_id, $to_id, $type=self::type_actives)
	{
		$criteria = new CDbCriteria;
		$criteria->select = 'id';
		$criteria->addColumnCondition(array(
				'select_id'=>$select_id,
				'type'=>$type,
				'to_id'=>$to_id,
		));
		return self::model()->find($criteria);
	}
	
	/**
	 * 获取已经选中的 或者过滤数据
	 * @param integer $to_id
	 * @param integer $type
	 * @param array $select_ids
	 * @return array
	 */
	public static function getSelected($to_id, $type=self::type_actives, $select_ids=array())
	{
		$criteria = new CDbCriteria;
		if ( !empty($select_ids))
			$criteria->addInCondition('select_id', $select_ids);
		$criteria->addColumnCondition(array(
			'type'=>$type,
			'to_id'=>$to_id,
		));
		$models = self::model()->findAll($criteria);
		if ($models)
			return CHtml::listData($models, 'select_id', 'select_id');
		return array();
	}
	
	/**
	 * 
	 * @param array|integer $select_ids
	 * @param integer $to_id
	 * @param integer $type
	 * @return boolean
	 */
	public static function saveSelected($select_ids, $to_id, $type=self::type_actives)
	{
		if ( !is_array($select_ids))
			$select_ids = array($select_ids);
		if ( !empty($select_ids) && isset(self::$_type[$type]))
		{
			$select_ids = array_diff($select_ids, self::getSelected($to_id, $type, $select_ids));
			if ( !empty($select_ids))
			{
				foreach ($select_ids as $select_id)
				{
					$model = new Select;
					$model->scenario = 'create';
					$model->attributes = array(
							'type'=>$type,
							'select_id'=>$select_id,
							'to_id'=>$to_id,
					);
					if ( !$model->save())
						return false;
				}
				return true;
			}
		}
		return false;
	}
	
	/**
	 * 删除选中的
	 * @param array|integer $select_ids
	 * @param integer $to_id
	 * @param integer $type
	 * @return boolean
	 */
	public static function deleteSelected($select_ids, $to_id, $type=self::type_actives)
	{
		if ( !is_array($select_ids))
			$select_ids = array($select_ids);
		if ( !empty($select_ids))
		{
			$criteria = new CDbCriteria;
			$criteria->addInCondition('select_id', $select_ids);
			$criteria->addColumnCondition(array(
					'type'=>$type,
					'to_id'=>$to_id,
			));
			return self::model()->deleteAll($criteria);
		}
		return false;
	}	
}
