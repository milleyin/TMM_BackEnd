<?php

/**
 * This is the model class for table "{{ad}}".
 *
 * The followings are the available columns in table '{{ad}}':
 * @property string $id
 * @property string $p_id
 * @property string $admin_id
 * @property integer $type
 * @property string $link_type
 * @property string $link
 * @property string $options
 * @property string $name
 * @property string $info
 * @property string $img
 * @property string $litimg
 * @property integer $sort
 * @property string $add_time
 * @property string $up_time
 * @property integer $status
 */
class Ad extends CActiveRecord
{
	/*****************************************广告类型**************************************************************/
	/**
	 *	类型用途 APP横幅
	 * @var integer
	 */
	const type_banner = Type::type_banner;
	/**
	 *	类型用途 当季热销
	 * @var integer
	 */
	const type_hot = Type::type_hot;
	/**
	 *	类型用途 觅趣
	 * @var integer
	 */
	const type_actives = 2;
	/**
	 *	类型用途 觅境
	 * @var integer
	 */
	const type_nearby = 3;
	/**
	 * 解释字段 type 的含义
	 * @var array
	 */
	public static $_type = array(
			self::type_banner=>'APP横幅',
			self::type_hot=>'热销专题',
			self::type_actives=>'觅趣专题',
			self::type_nearby=>'周边专题',
	);
	
	/**
	 * 删除
	 */
	const status_del = -1;
	/**
	 * 上线
	 */
	const status_dis = 0;
	/**
	 * 下线
	 */
	const status_suc = 1;
	/**
	 * 解释字段 status 的含义
	 * @var array
	 */
	public static $_status=array(-1=>'删除','下线','上线');
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
		return '{{ad}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			//array('admin_id, link, name, img', 'required'),
			array('p_id, admin_id, link_type, type, sort, status, add_time, up_time', 'numerical', 'integerOnly'=>true),
			array('p_id, admin_id, link_type, sort', 'length', 'max'=>11),
			array('link, options, info, img, litimg', 'length', 'max'=>128),
			array('name', 'length', 'max'=>32),
			array('add_time, up_time', 'length', 'max'=>10),
			array('type, status', 'length', 'max'=>3),
			
			//广告类型
			array('type','in','range'=>array_keys(self::$_type)),
			//广告状态
			array('status','in','range'=>array_keys(self::$_status)),

			//栏目创建广告 修改
			array('type, name, info, sort', 'required', 'on'=>'create, update'),
			array(
					'img','file','allowEmpty'=>true,
					'types'=>'jpg,gif,png', 'maxSize'=>1024*1024*2,
					'tooLarge'=>'图片超过2M,请重新上传', 'wrongType'=>'图片格式错误',
					'on'=>'create,update'
			),
			array('type, name, info, sort', 'safe', 'on'=>'create, update'),
			array('search_time_type,search_start_time,search_end_time,id, p_id, admin_id, link_type, link, options, img, litimg, add_time, up_time, status', 'unsafe', 'on'=>'create, update'),
			
			//广告 归属类型
			array('type', 'in', 'range'=>array_keys(array_slice(self::$_type, 0, 2, true)), 'on'=>'add, modify, set'),
			//子类 创建广告 修改
			array('name, info, link_type, link, sort', 'required', 'on'=>'add, modify'),
			//链接类型
			array('link_type', 'link_type', 'on'=>'add, modify'),
			array('link', 'link', 'on'=>'add, modify'),
			array(
					'img', 'file', 'allowEmpty'=>true,
					'types'=>'jpg, gif, png', 'maxSize'=>1024*1024*2,
					'tooLarge'=>'图片超过2M，请重新上传', 'wrongType'=>'图片格式错误',
					'on'=>'add, modify'
			),
			array('link_type, link, name, info, sort', 'safe', 'on'=>'add, modify'),
			array('search_time_type,search_start_time,search_end_time,id, p_id, admin_id, type, options, img, litimg, add_time, up_time, status', 'unsafe', 'on'=>'add, modify'),
			
			//直接创建子类
			array('type, p_id, name, info, link_type, link, sort', 'required', 'on'=>'set'),
			array('p_id', 'p_id', 'on'=>'set'),
			//链接类型
			array('link_type', 'link_type', 'on'=>'set'),
			array('link', 'link', 'on'=>'set'),
			array(
					'img', 'file', 'allowEmpty'=>true,
					'types'=>'jpg, gif, png', 'maxSize'=>1024*1024*2,
					'tooLarge'=>'图片超过2M，请重新上传', 'wrongType'=>'图片格式错误',
					'on'=>'set'
			),
			array('type, p_id, link_type, link, name, info, sort', 'safe', 'on'=>'set'),
			array('search_time_type,search_start_time,search_end_time,id, admin_id, options, img, litimg, add_time, up_time, status', 'unsafe', 'on'=>'set'),
			
			//更新排序
			array('sort', 'required', 'on'=>'sort'),
			array('sort', 'safe', 'on'=>'sort'),
			array('sort', 'length', 'max'=>8, 'on'=>'sort'),
			array('search_time_type,search_start_time,search_end_time,id, p_id, admin_id, type, link_type, link, options, name, info, img, litimg, add_time, up_time, status', 'safe', 'on'=>'search'),
				
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('search_time_type,search_start_time,search_end_time,id, p_id, admin_id, type, link_type, link, options, name, info, img, litimg, sort, add_time, up_time, status', 'safe', 'on'=>'search'),
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
				'Ad_Admin'=>array(self::BELONGS_TO, 'Admin', 'admin_id'),
				'Ad_Type'=>array(self::BELONGS_TO, 'Type', 'link_type'),
 				'Ad_Ad'=>array(self::BELONGS_TO, 'Ad', 'p_id'),
				'Ad_Ad_Count'=>array(self::STAT, 'Ad', 'p_id'),
				//统计选择的商品个数
				'Ad_Select_Count'=>array(self::STAT, 'Select', 'to_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'p_id'=>'归属栏目',
			'admin_id' => '管理员',
			'type' => '广告类型',
			'link_type' => '链接类型',
			'link' => '链接',
			'options' => '链接属性',
			'name' => '名字',
			'info' => '说明',
			'img' => '图片',
			'litimg' => '缩略图',
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
		if ($criteria ==='')
		{
			$criteria = new CDbCriteria;
			$criteria->with = array(
					'Ad_Admin',
					'Ad_Type',
					'Ad_Ad',
			);
			if ($this->status != self::status_del )
				$criteria->compare('t.status', '<>-1');
			//标准条件 不能是父
			$criteria->addCondition('t.p_id != 0');
			if($this->search_time_type != '' && isset($this->__search_time_type[$this->search_time_type]))
			{
				if($this->search_start_time !='' && $this->search_end_time !='')
					$criteria->addBetweenCondition('t.'.$this->__search_time_type[$this->search_time_type], strtotime($this->search_start_time),strtotime($this->search_end_time)+3600*24-1);
				elseif($this->search_start_time !='' && $this->search_end_time =='')
					$criteria->compare('t.'.$this->__search_time_type[$this->search_time_type], '>='.strtotime($this->search_start_time));
				elseif($this->search_start_time =='' && $this->search_end_time !='')
					$criteria->compare('t.'.$this->__search_time_type[$this->search_time_type], '<=' . (strtotime($this->search_end_time)+3600*24-1));
			}
			$criteria->compare('t.id', $this->id,true);
			
			if ($this->admin_id != '')
			{
				$criteria->addCondition('`Ad_Admin`.`username` LIKE :admin_id OR `Ad_Admin`.`name` LIKE :admin_id');
				$criteria->params[':admin_id'] = '%' . strtr($this->admin_id, array('%'=>'\%', '_'=>'\_', '\\'=>'\\\\')) . '%';
			}
			if ($this->p_id != '')
			{
				if (strpos($this->p_id, '=') === 0)
					$criteria->compare('`Ad_Ad`.`id`', $this->p_id);
				else
				{
					$criteria->addCondition('`Ad_Ad`.`name` LIKE :p_id_like OR `Ad_Ad`.`info` LIKE :p_id_like OR `Ad_Ad`.`id`=:p_id');
					$criteria->params[':p_id_like'] = '%' . strtr($this->p_id, array('%'=>'\%', '_'=>'\_', '\\'=>'\\\\')) . '%';
					$criteria->params[':p_id'] = $this->p_id;
				}
			}
			$criteria->compare('t.type', $this->type);
			$criteria->compare('t.link_type', $this->link_type);
			$criteria->compare('t.link', $this->link,true);
			$criteria->compare('t.options', $this->options,true);
			$criteria->compare('t.name', $this->name,true);
			$criteria->compare('t.info', $this->info,true);
			$criteria->compare('t.img', $this->img,true);
			$criteria->compare('t.sort', $this->sort);
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
					'defaultOrder'=>'t.add_time desc', //设置默认排序
			),
		));
	}
	
	/**
	 * 栏目搜索
	 * @param string $criteria
	 * @return CActiveDataProvider
	 */
	public function searchP($criteria='')
	{
		// @todo Please modify the following code to remove attributes that should not be searched.
		if ($criteria ==='')
		{
			$criteria = new CDbCriteria;
			$criteria->with = array(
					'Ad_Admin',
					'Ad_Type'
			);
			if ($this->status != self::status_del )
				$criteria->compare('t.status','<>-1');
			//标准条件 栏目
			$criteria->addColumnCondition(array(
				't.p_id'=>0
			));
			if ($this->search_time_type != '' && isset($this->__search_time_type[$this->search_time_type]))
			{
				if ($this->search_start_time !='' && $this->search_end_time !='')
					$criteria->addBetweenCondition('t.'.$this->__search_time_type[$this->search_time_type], strtotime($this->search_start_time),strtotime($this->search_end_time)+3600*24-1);
				elseif ($this->search_start_time !='' && $this->search_end_time =='')
					$criteria->compare('t.'.$this->__search_time_type[$this->search_time_type], '>='.strtotime($this->search_start_time));
				elseif ($this->search_start_time =='' && $this->search_end_time !='')
					$criteria->compare('t.'.$this->__search_time_type[$this->search_time_type], '<=' . (strtotime($this->search_end_time)+3600*24-1));
			}
			$criteria->compare('t.id', $this->id, true);	
			$criteria->addCondition('`Ad_Admin`.`username` LIKE :admin_id OR `Ad_Admin`.`name` LIKE :admin_id');
			$criteria->params[':admin_id'] = '%' . strtr($this->admin_id, array('%'=>'\%', '_'=>'\_', '\\'=>'\\\\')) . '%';
			$criteria->compare('t.type', $this->type);
			$criteria->compare('t.name', $this->name, true);
			$criteria->compare('t.info', $this->info, true);
			$criteria->compare('t.img', $this->img, true);
			$criteria->compare('t.sort', $this->sort);
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
						'defaultOrder'=>'t.add_time desc', //设置默认排序
				),
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Ad the static model class
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
				$this->up_time = $this->add_time = time();
			else
				$this->up_time = time();
			$this->admin_id = Yii::app()->admin->id;
			$this->status = self::status_dis;
			return true;
		}else
			return false;
	}
		
	/**
	 * 验证 链接类型 字段值
	 */
	public function link_type()
	{
		if(! $this->hasErrors('type'))
		{
			if (! Type::model()->getTypeModel($this->link_type,$this->type))
				$this->addError('link_type', '链接类型 不是有效值');
		}
	}
	
	/**
	 * 验证 归属栏目
	 */
	public function p_id()
	{
		if(! $this->hasErrors('type') && $this->p_id)
		{
			if (! self::model()->findByPk($this->p_id, 'type=:type AND status=:status AND p_id=0', array(':type'=>$this->type, ':status'=>self::status_suc,)))
				$this->addError('p_id', '归属栏目 不是有效值');
		}
	}
	
	/**
	 * 验证链接
	 */
	public function link()
	{		
		if(! $this->hasErrors('link_type') && $this->link)
		{
			$model = Type::model()->getTypeModel($this->link_type, $this->type);
			if ($model)
			{
				$preg_match = $model->value;
				$preg_match = str_replace('.', '\.', $preg_match);
				$preg_match = str_replace('/', '\/', $preg_match);
				$preg_match = str_replace('?', '\?', $preg_match);
				if (! preg_match('/^' . $preg_match . '.*$/', $this->link))
					$this->addError('link', '链接 不是有效值 例：' . $model->value);
			}else 
				$this->addError('link_type', '链接类型 不是有效值');
		}
	}
	
	/**
	 * 获取链接类型的数据模型
	 * @param unknown $link_type
	 * @param unknown $type
	 * @return Ambigous <Type, Ambigous, static, mixed, NULL, multitype:static , multitype:unknown Ambigous <static, NULL> , multitype:, multitype:unknown >
	 */
	public function getLinkTypeModel($link_type, $type, $status = Type::status_suc)
	{
		$model = Type::model()->getTypeModel($link_type, $type, $status);
		return $model ? $model : new Type;
	}
	
	/**
	 * 获取第一个专题
	 * @param unknown $type
	 * @param unknown $status
	 * @return NULL
	 */
	public function getFirstPId($type=self::type_banner, $status=self::status_suc)
	{
		//条件
		$criteria = new CDbCriteria;
		$criteria->addColumnCondition(array(
			'p_id'=>0,
			'type'=>$type,
			'status'=>$status,
		));
		$criteria->order = 'sort';
		$model = self::model()->find($criteria);
		if ($model)
			return $model->id;
		else 
			return null;
	}

}
