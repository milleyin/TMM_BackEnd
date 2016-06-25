<?php

/**
 * This is the model class for table "{{items_wifi}}".
 *
 * The followings are the available columns in table '{{items_wifi}}':
 * @property string $id
 * @property string $agent_id
 * @property string $item_id
 * @property string $wifi_id
 * @property integer $sort
 * @property string $add_time
 * @property string $up_time
 * @property integer $status
 */
class ItemsWifi extends CActiveRecord
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
		return '{{items_wifi}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			//array('agent_id, item_id, wifi_id, add_time, up_time', 'required'),
			array('sort, status', 'numerical', 'integerOnly'=>true),
			array('agent_id, item_id, wifi_id', 'length', 'max'=>11),
			array('add_time, up_time', 'length', 'max'=>10),
			
			//array('','safe','on'=>'create,update'),
			//array('','unsafe','on'=>'create,update'),
			
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('search_time_type,search_start_time,search_end_time,id, agent_id, item_id, wifi_id, sort, add_time, up_time, status', 'safe', 'on'=>'search'),
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
				//选择中的酒店服务
				'ItemsWifi_Wifi'=>array(self::HAS_ONE,'Wifi',array('id'=>'wifi_id')),
				//归属项目住
				'ItemsWifi_Hotel'=>array(self::BELONGS_TO,'Hotel','item_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => '主键',
			'agent_id' => '关联代理商用户表（agent）主键id',
			'item_id' => '项目id',
			'wifi_id' => '服务id',
			'sort' => '排序',
			'add_time' => '创建时间',
			'up_time' => '更新时间',
			'status' => '状态0禁用1正常-1删除',
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
					$criteria->addBetweenCondition($this->__search_time_type[$this->search_time_type],strtotime($this->search_start_time),strtotime($this->search_end_time));
				elseif($this->search_start_time !='' && $this->search_end_time =='')
					$criteria->compare($this->__search_time_type[$this->search_time_type],'>='.strtotime($this->search_start_time));
				elseif($this->search_start_time =='' && $this->search_end_time !='')
					$criteria->compare($this->__search_time_type[$this->search_time_type],'<='.strtotime($this->search_end_time));
			}			
			$criteria->compare('id',$this->id,true);
			$criteria->compare('agent_id',$this->agent_id,true);
			$criteria->compare('item_id',$this->item_id,true);
			$criteria->compare('wifi_id',$this->wifi_id,true);
			$criteria->compare('sort',$this->sort);
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
	 * @return ItemsWifi the static model class
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
	
	/**
	 * 显示wifi图片
	 * @param unknown $models
	 * @return string
	 */
	public static function show_wifi($models)
	{
		$html='';
		foreach ($models as $model)
		{
			if($model->ItemsWifi_Wifi->status==1)
				$html .= Yii::app()->controller->show_img($model->ItemsWifi_Wifi->icon,'','',array('title'=>$model->ItemsWifi_Wifi->name));
		}
		return $html;
	}
	
	/**
	 * 
	 * @param unknown $wifi_id
	 * @param unknown $item_id
	 * @return boolean
	 */
	public static function checked($wifi_id,$item_id)
	{
		if(self::model()->find('item_id=:item_id AND wifi_id=:wifi_id',array(':item_id'=>$item_id,':wifi_id'=>$wifi_id)))
			return true;
		return false;
	}
	
	/**
	 * 返回没有选择的id
	 * @param unknown $wifi_ids
	 * @param unknown $item_id
	 * @return multitype:
	 */
	public static function not_select_wifi($wifi_ids,$item_id)
	{
		$return=array();
		if(! is_array($wifi_ids))
			$wifi_ids=array($wifi_ids);
		if(empty($wifi_ids))
			return $return;
		$criteria=new CDbCriteria;
		$criteria->select='wifi_id';
		$criteria->addInCondition('wifi_id',$wifi_ids);
		$criteria->addColumnCondition(array(
				'item_id'=>$item_id,
		));
		$models=self::model()->findAll($criteria);
		$new_wifi_ids=array();
		foreach ($models as $model)
			$new_wifi_ids[]=$model->wifi_id;
		foreach ($wifi_ids as $wifi_id)
		{
			if(! in_array($wifi_id, $new_wifi_ids))
				$return[]=$wifi_id;
		}		
		return $return;
	}
	
	/**
	 * 保存选择的
	 * @param unknown $wifi_ids
	 * @param unknown $wifi
	 * @return boolean
	 */
	public static function select_wifi_save($wifi_ids,$wifi)
	{
		$return=array();
		foreach ($wifi_ids as $wifi_id)
		{
			$model=new ItemsWifi;
			$model->agent_id=$wifi->agent_id;
			$model->item_id=$wifi->id;
			$model->wifi_id=$wifi_id;
			if($model->save())
				$return[] =true;
		}
		return count($return)==0?false:(count($return)==count($wifi_ids));
	}
	
	/**
	 * 删除
	 * @param unknown $wifi_ids
	 * @param unknown $item_id
	 * @return Ambigous <number, unknown>
	 */
	public static function select_wifi_delete($wifi_ids,$item_id)
	{
		$criteria=new CDbCriteria;
		$criteria->addColumnCondition(array(
				'item_id'=>$item_id,
		));
		$criteria->addInCondition('wifi_id',$wifi_ids);
		return self::model()->deleteAll($criteria);
	}

	/**
	 * 获取选择的wifi
	 * @param $element_type
	 * @param $element_id
	 * @return array|mixed|null
	 */
	public static function get_select_wifi($hotel_id)
	{
		return self::model()->findAll(array(
			'with'=>array('ItemsWifi_Wifi'),
			'condition'=>'`t`.`item_id`=:item_id AND `ItemsWifi_Wifi`.`status`=1',
			'params'=>array(':item_id'=>$hotel_id),
		));
	}
}
