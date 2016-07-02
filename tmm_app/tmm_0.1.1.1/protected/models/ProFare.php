<?php

/**
 * This is the model class for table "{{pro_fare}}".
 *
 * The followings are the available columns in table '{{pro_fare}}':
 * @property string $id
 * @property string $pro_id
 * @property string $fare_id
 * @property string $group_id
 * @property string $items_id
 * @property string $thrand_id
 * @property string $add_time
 * @property string $up_time
 * @property integer $status
 */
class ProFare extends CActiveRecord
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
	public static $_search_time_type=array('add_time','up_time'); 
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
		return '{{pro_fare}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			//array('pro_id, fare_id, group_id, items_id', 'required'),
			array('status', 'numerical', 'integerOnly'=>true),
			array('pro_id, fare_id, group_id, items_id, thrand_id', 'length', 'max'=>11),
			array('add_time, up_time', 'length', 'max'=>10),
			
			//array('','safe','on'=>'create,update'),
			//array('','unsafe','on'=>'create,update'),
			array('fare_id', 'required','on'=>'create_thrand,update_thrand,create_actives,update_actives'),
			array('fare_id', 'safe','on'=>'create_thrand,update_thrand,create_actives,update_actives'),
			array('search_time_type,search_start_time,search_end_time,id, pro_id, group_id, items_id, thrand_id, add_time, up_time, status', 'unsafe', 'on'=>'create_thrand,update_thrand,create_actives,update_actives'),
				
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('search_time_type,search_start_time,search_end_time,id, pro_id, fare_id, group_id, items_id, thrand_id, add_time, up_time, status', 'safe', 'on'=>'search'),
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
			//选中的价格
			'ProFare_Fare'=>array(self::HAS_ONE,'Fare',array('id'=>'fare_id')),
			//归属选中项目的价格
			'ProFare_Pro'=>array(self::BELONGS_TO,'Pro','pro_id'),
			//归属结伴游选中的价格
			'ProFare_Group'=>array(self::BELONGS_TO,'Group','group_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => '自增ID',
			'pro_id' => '选中项目id',
			'fare_id' => '项目价格id',
			'group_id' => '结伴游商品id',
			'items_id' => '关联项目主表（items）主键id',
			'thrand_id' => '当前项目关联线商品id',
			'add_time' => '创建时间',
			'up_time' => '更新时间',
			'status' => '状态0禁用1启用',
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
			$criteria->compare('pro_id',$this->pro_id,true);
			$criteria->compare('fare_id',$this->fare_id,true);
			$criteria->compare('group_id',$this->group_id,true);
			$criteria->compare('items_id',$this->items_id,true);
			$criteria->compare('thrand_id',$this->thrand_id,true);
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
	 * @return ProFare the static model class
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
				$this->up_time=$this->add_time=time();
			else
				$this->up_time=time();			
			return true;
		}else
			return false;
	}
}
