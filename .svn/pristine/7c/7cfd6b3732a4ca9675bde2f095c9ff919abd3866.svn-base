<?php

/**
 * This is the model class for table "{{items_classliy}}".
 *
 * The followings are the available columns in table '{{items_classliy}}':
 * @property string $id
 * @property string $name
 * @property string $info
 * @property string $admin
 * @property string $main
 * @property string $append
 * @property string $nexus
 * @property string $add_time
 * @property string $up_time
 * @property integer $status
 */
class ItemsClassliy extends CActiveRecord
{
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
	 *类型的模型数据
	 */
	public static  $_class;
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{items_classliy}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
		//	array('name, admin, append, nexus, add_time, up_time', 'required'),
			array('status', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>20),
			array('info', 'length', 'max'=>200),
			array('admin, main, append, nexus', 'length', 'max'=>45),
			array('add_time, up_time', 'length', 'max'=>10),
			
			//创建、修改
			array('name, info, admin, main, append, nexus', 'required','on'=>'create,update'),
			array('name, info, admin, main, append, nexus','safe','on'=>'create,update'),
			array('search_time_type,search_start_time,search_end_time,id, add_time, up_time, status','unsafe','on'=>'create,update'),
			
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('search_time_type,search_start_time,search_end_time,id, name, info, admin, main, append, nexus, add_time, up_time, status', 'safe', 'on'=>'search'),
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
				'ItemsClass_Items'=>array(self::HAS_MANY,'Items','c_id'),//项目主要表
				'ItemsClass_Eat'=>array(self::HAS_MANY,'Eat','c_id'),//项目 吃
				'ItemsClass_Hotel'=>array(self::HAS_MANY,'Hotel','c_id'),//项目 住
				'ItemsClass_Play'=>array(self::HAS_MANY,'Play','c_id'),//项目 玩 
				'ItemsClass_Fare'=>array(self::HAS_MANY,'Fare','c_id'),   //票价
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => '项目类型名称',
			'info' => '简介说明',
			'admin' => '后端控制器',
			'main' => '主要表模型',
			'append' => '附加表模型',
			'nexus' => '主附关系名',
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
			$criteria->compare('status','<>-1');
			if($this->search_time_type != '' && isset($this->__search_time_type[$this->search_time_type]))
			{
				if($this->search_start_time !='' && $this->search_end_time !='')
					$criteria->addBetweenCondition($this->__search_time_type[$this->search_time_type],strtotime($this->search_start_time),strtotime($this->search_end_time)+3600*24-1);
				elseif($this->search_start_time !='' && $this->search_end_time =='')
					$criteria->compare($this->__search_time_type[$this->search_time_type],'>='.strtotime($this->search_start_time));
				elseif($this->search_start_time =='' && $this->search_end_time !='')
					$criteria->compare($this->__search_time_type[$this->search_time_type],'<='.strtotime($this->search_end_time)+3600*24-1);
			}			
			$criteria->compare('id',$this->id,true);
			$criteria->compare('name',$this->name,true);
			$criteria->compare('info',$this->info,true);
			$criteria->compare('admin',$this->admin,true);
			$criteria->compare('main',$this->main,true);
			$criteria->compare('append',$this->append,true);
			$criteria->compare('nexus',$this->nexus,true);
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
	 * @return ItemsClassliy the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * 返回选择的数据
	 * @param string $array
	 * @param unknown $params
	 * @return number|Ambigous <multitype:static , mixed, static, NULL, multitype:unknown Ambigous <static, NULL> , multitype:, multitype:unknown >
	 */
	public static function data($array=true,$params=array(''=>'--请选择--'))
	{
		if(!! $models=self::model()->findAll('status=1'))
		{
			if($array==true)
				return $params+CHtml::listData($models, 'id', 'name');
			elseif($array != false)
				return $params+CHtml::listData($models, $array, 'name');
			else 
				return $models;	
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
	
	/**
	 * 获取类型分类
	 */
	public static function getClass()
	{
		if(isset(self::$_class[Yii::app()->controller->_class_model]))
			return  self::$_class[Yii::app()->controller->_class_model];
		else
			return self::$_class[Yii::app()->controller->_class_model]=self::model()->find('append=:append',array(':append'=>Yii::app()->controller->_class_model));
	}
}
