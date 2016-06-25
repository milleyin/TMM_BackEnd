<?php

/**
 * This is the model class for table "{{order_items_fare}}".
 *
 * The followings are the available columns in table '{{order_items_fare}}':
 * @property string $id 
 * @property string $order_items_fare_id
 * @property string $order_items_id
 * @property string $organizer_id
 * @property string $order_organizer_id
 * @property string $user_id
 * @property string $order_id
 * @property string $order_shops_id
 * @property string $store_id
 * @property string $manager_id
 * @property string $agent_id
 * @property string $shops_id
 * @property string $shops_c_id
 * @property string $items_id
 * @property string $items_c_id
 * @property string $fare_id
 * @property string $fare_name
 * @property string $fare_info
 * @property string $fare_number
 * @property string $fare_price
 * @property string $fare_up_time
 * @property string $price
 * @property string $number
 * @property string $start_date
 * @property string $end_date
 * @property integer $hotel_number
 * @property string $total
 * @property string $add_time
 * @property string $up_time
 * @property integer $status
 */
class OrderItemsFare extends CActiveRecord
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
	public static $_search_time_type=array('fare_up_time','add_time','up_time'); 
	/**
	 *	解释搜索类型时间 search_time_type 的字段搜索
	 * @var string
	 */
	public $__search_time_type=array('fare_up_time','add_time','up_time'); 
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
	
	public $number_count;
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{order_items_fare}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			//array('order_shops_id, store_id, manager_id, agent_id, shops_id, shops_c_id, items_id, items_c_id, fare_id, fare_up_time, add_time, up_time', 'required'),
			array('status,number', 'numerical', 'integerOnly'=>true),
			array('hotel_number,order_items_fare_id,order_items_id, organizer_id, order_organizer_id, user_id, order_id, order_shops_id, store_id, manager_id, agent_id, shops_id, shops_c_id, items_id, items_c_id, fare_id, fare_number, number', 'length', 'max'=>11),
			array('fare_name', 'length', 'max'=>24),
			array('fare_info', 'length', 'max'=>64),
			array('fare_price, price, total', 'length', 'max'=>13),
			array('fare_up_time, add_time, up_time', 'length', 'max'=>10),
				
			//array('hotel_number','length','max'=>2),	
			//array('start_date,end_date','type','dateFormat'=>'yyyy-MM-dd','type'=>'date','on'=>'create_dot'),
			
			//验证钱
			array('fare_price, price,total','ext.Validator.Validator_money'),
			array('fare_price, price,total', 'length', 'max'=>11),
			array('number', 'length', 'max'=>2), //购买的数量
			//创建订单 复制价格表
			array(
					'user_id,store_id,manager_id,agent_id,shops_id,shops_c_id,
					items_id,items_c_id,fare_id,fare_name,fare_info,fare_price,
					fare_up_time,price,number,total',
					'required','on'=>'create_dot'),
			array(
					'user_id,store_id,manager_id,agent_id,shops_id,shops_c_id,
					items_id,items_c_id,fare_id,fare_name,fare_info,fare_number,fare_price,
					fare_up_time,price,number,total'
					,'safe','on'=>'create_dot'),
			array('search_time_type,search_start_time,search_end_time,id, order_items_id, organizer_id, order_organizer_id, order_id, order_shops_id, add_time, up_time, status', 'unsafe', 'on'=>'create_dot'),
			
			array('price,number,total,fare_price,start_date,end_date,hotel_number','required','on'=>'validate_dot'),
			array('hotel_number','length','max'=>2,'on'=>'validate_dot'),
			array('fare_price', 'compare', 'compareAttribute'=>'price','on'=>'validate_dot'),
			array('start_date,end_date','type','dateFormat'=>'yyyy-MM-dd','type'=>'date','on'=>'validate_dot'),
			array('hotel_number','validate_dot_hotel_number','on'=>'validate_dot'),
			array('total','validate_dot_total','on'=>'validate_dot'),
			
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('start_date,end_date,hotel_number,search_time_type,search_start_time,search_end_time,id, order_items_fare_id,order_items_id, organizer_id, order_organizer_id, user_id, order_id, order_shops_id, store_id, manager_id, agent_id, shops_id, shops_c_id, items_id, items_c_id, fare_id, fare_name, fare_info, fare_number, fare_price, fare_up_time, price, number, total, add_time, up_time, status', 'safe', 'on'=>'search'),
		);
	}
	
	/**
	 *	验证时间
	 */
	public function validate_dot_hotel_number()
	{
		if(! $this->hasErrors())
		{
			$start=strtotime($this->start_date);
			$end=strtotime($this->end_date);		
			if($end <= $start)
				$this->addError('start_date', '入住日期 必须小于离店日期');
			elseif($this->hotel_number != ($end-$start)/(24*3600) || $this->hotel_number > 11)
				$this->addError('hotel_number', '入住天数 不是有效值');
		}		
	}
	
	/**
	 * 计算价格
	 */
	public function validate_dot_total()
	{
		if(! $this->hasErrors())
		{
			$floor = Yii::app()->controller;
			if($floor->floorComp($floor->floorMul($this->hotel_number, $floor->floorMul($this->number, $this->price)),$this->total) != 0)
				$this->addError('total', '总计金额 不是有效值');
		}
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
				'OrderItemsFare_OrderActives'=>array(self::BELONGS_TO,'OrderActives','order_organizer_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => '主键',
			'order_items_fare_id'=>'归属觅趣价格详细',
			'order_items_id' => '归属订单项目详细',
			'organizer_id' => '归属组织者',
			'order_organizer_id' => '归属组织者订单详情表',
			'user_id' => '归属用户',
			'order_id' => '归属订单表',
			'order_shops_id' => '归属订单商品表（复制表）',
			'store_id' => '归属商家',
			'manager_id' => '商家管理者',
			'agent_id' => '项目归属代理商',
			'shops_id' => '商品来源',
			'shops_c_id' => '归属商品分类',
			'items_id' => '项目来源',
			'items_c_id' => '归属项目分类',
			'fare_id' => '归属价格fare表',
			'fare_name' => '名称',
			'fare_info' => '类型',
			'fare_number' => '平方',
			'fare_price' => '商品价格',
			'fare_up_time' => '创建时间',
			'price' => '付款价格',
			'number' => '购买数量',
			'hotel_number'=>'入住天数',
			'start_date'=>'入住日期',
			'end_date'=>'退房日期',
			'total' => '总计总额',
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
			$criteria->compare('status','<>-1');
			if($this->search_time_type != '' && isset($this->__search_time_type[$this->search_time_type]))
			{
				if($this->search_start_time !='' && $this->search_end_time !='')
					$criteria->addBetweenCondition($this->__search_time_type[$this->search_time_type],strtotime($this->search_start_time),strtotime($this->search_end_time)+3600*24-1);
				elseif($this->search_start_time !='' && $this->search_end_time =='')
					$criteria->compare($this->__search_time_type[$this->search_time_type],'>='.strtotime($this->search_start_time));
				elseif($this->search_start_time =='' && $this->search_end_time !='')
					$criteria->compare($this->__search_time_type[$this->search_time_type],'<=' . (strtotime($this->search_end_time)+3600*24-1));
			}			
			$criteria->compare('id',$this->id,true);
			$criteria->compare('order_items_fare_id',$this->order_items_fare_id,true);	
			$criteria->compare('order_items_id',$this->order_items_id,true);
			$criteria->compare('organizer_id',$this->organizer_id,true);
			$criteria->compare('order_organizer_id',$this->order_organizer_id,true);
			$criteria->compare('user_id',$this->user_id,true);
			$criteria->compare('order_id',$this->order_id,true);
			$criteria->compare('order_shops_id',$this->order_shops_id,true);
			$criteria->compare('store_id',$this->store_id,true);
			$criteria->compare('manager_id',$this->manager_id,true);
			$criteria->compare('agent_id',$this->agent_id,true);
			$criteria->compare('shops_id',$this->shops_id,true);
			$criteria->compare('shops_c_id',$this->shops_c_id,true);
			$criteria->compare('items_id',$this->items_id,true);
			$criteria->compare('items_c_id',$this->items_c_id,true);
			$criteria->compare('fare_id',$this->fare_id,true);
			$criteria->compare('fare_name',$this->fare_name,true);
			$criteria->compare('fare_info',$this->fare_info,true);
			$criteria->compare('fare_number',$this->fare_number,true);
			$criteria->compare('fare_price',$this->fare_price,true);
			$criteria->compare('hotel_number',$this->hotel_number,true);
			$criteria->compare('start_date',$this->start_date,true);
			$criteria->compare('end_date',$this->end_date,true);
			
			if($this->fare_up_time != '')
				$criteria->addBetweenCondition('fare_up_time',strtotime($this->fare_up_time),(strtotime($this->fare_up_time)+3600*24-1));
			$criteria->compare('price',$this->price,true);
			$criteria->compare('number',$this->number,true);
			$criteria->compare('total',$this->total,true);
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
	 * @return OrderItemsFare the static model class
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
	 * 排除 商品价格没有购买的项目id
	 * @param unknown $order_id
	 */
	public static function exclude_items_code($order_id,$order_organizer_id=0,$type=false)
	{
		$criteria = new CDbCriteria;
		$criteria->select = '*,sum(`number`) as `number_count`';
		$criteria->addColumnCondition(array(
				'order_id'=>$order_id,
				'order_organizer_id'=>$order_organizer_id,
		));
		$criteria->group = 'order_items_id';
		$models = self::model()->findAll($criteria);
		$return = array('yes'=>array(), 'not'=>array());
			
		foreach ($models as $model)
		{
			if($model->number_count > 0)
				$return['yes'][]=$model->order_items_id;
			else
				$return['not'][]=$model->order_items_id;
		}
		if ($type)
			return $return;
		return $return['not'];
	}
}
