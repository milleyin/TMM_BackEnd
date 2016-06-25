<?php

/**
 * This is the model class for table "{{fare}}".
 *
 * The followings are the available columns in table '{{fare}}':
 * @property string $id
 * @property string $store_id
 * @property string $agent_id
 * @property string $item_id
 * @property string $c_id
 * @property string $name
 * @property string $info
 * @property string $number
 * @property string $price
 * @property string $add_time
 * @property string $up_time
 * @property integer $status
 */
class Fare extends CActiveRecord
{
	/**
	 *  对应需要房间人数
	 * @var unknown
	 */
	public static $info_number_room=array('儿童'=>0,'成人'=>1);
	/**
	 * 儿童
	 * @var unknown
	 */
	const info_children=0;
	/**
	 * 成人
	 * @var unknown
	 */
	const info_adult=1;
	/**
	 * 解释 _info 的含义
	 * @var array
	 */
	public static $__info=array('儿童','成人');
	
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
		return '{{fare}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			//array('store_id, agent_id, item_id, c_id, add_time, up_time', 'required'),
			array('status', 'numerical', 'integerOnly'=>true),
			array('store_id, agent_id, item_id, c_id, number', 'length', 'max'=>11),
			array('name', 'length', 'max'=>15),
			array('info', 'length', 'max'=>5),
			array('price', 'length', 'max'=>10),
			array('add_time, up_time', 'length', 'max'=>10),
			array('price','numerical'),
			array('price','ext.Validator.Validator_money'),

			array('info','in','range'=>array('成人','儿童'),'message'=>'价格类型（成人、儿童）','on'=>'create_play,update_play,create_eat,update_eat'),
			//项目住 创建、修改
			array('name, info, price,number', 'required','on'=>'create_hotel,update_hotel'),	
			array('name, info, price,number','safe','on'=>'create_hotel,update_hotel'),
			array('info,number', 'numerical', 'integerOnly'=>true,'on'=>'create_hotel,update_hotel'),		
			array('number','length','max'=>1,'on'=>'create_hotel,update_hotel'),
			array('info','length','max'=>4,'on'=>'create_hotel,update_hotel'),
			array('number','number_count','on'=>'create_hotel,update_hotel'),
			array('info','info_count','on'=>'create_hotel,update_hotel'),
			array('search_time_type,search_start_time,search_end_time,id, store_id, agent_id, item_id, c_id, add_time, up_time, status','unsafe','on'=>'create_hotel,update_hotel'),
			array('price','verify_fare','create_hotel,update_hotel'),

			//项目玩 创建、修改
			array('name, info, price', 'required','on'=>'create_play,update_play'),
			array('name, info, price','safe','on'=>'create_play,update_play'),
			array('search_time_type,search_start_time,search_end_time,id, store_id, agent_id, item_id, c_id, number, add_time, up_time, status','unsafe','on'=>'create_play,update_play'),
			array('price','verify_fare','create_play,update_play'),

			//项目吃 创建、修改
            array('name, info, price', 'required','on'=>'create_eat,update_eat'),
            array('name, info, price','safe','on'=>'create_eat,update_eat'),
            array('search_time_type,search_start_time,search_end_time,id, store_id, agent_id, item_id, c_id, number, add_time, up_time, status','unsafe','on'=>'create_eat,update_eat'),
            array('price','verify_fare','create_eat,update_eat'),
			
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('search_time_type,search_start_time,search_end_time,id, store_id, agent_id, item_id, c_id, name, info, number, price, add_time, up_time, status', 'safe', 'on'=>'search'),
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
                'Fare_ItemClass'=>array(self::BELONGS_TO,'ItemClassliy','c_id'),
				'Fare_Items'=>array(self::BELONGS_TO, 'Items', 'item_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'store_id' => '商家ID',
			'agent_id' => '代理商ID',
			'item_id' => '项目ID',
			'c_id' => '项目分类',
			'name' => '名称',
			'info' => '类型',
			'number' => '入住人数',
			'price' => '价格(元)',
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
					$criteria->compare($this->__search_time_type[$this->search_time_type],'<=' . (strtotime($this->search_end_time)+3600*24-1));
			}			
			$criteria->compare('id',$this->id,true);
			$criteria->compare('store_id',$this->store_id,true);
			$criteria->compare('agent_id',$this->agent_id,true);
			$criteria->compare('item_id',$this->item_id,true);
			$criteria->compare('c_id',$this->c_id,true);
			$criteria->compare('name',$this->name,true);
			$criteria->compare('info',$this->info,true);
			$criteria->compare('number',$this->number,true);
			$criteria->compare('price',$this->price,true);
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
	 * @return Fare the static model class
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
			return true;
		}else
			return false;
	}
	
	/**
	 *验证
	 */
	public function verify_fare($attribute)
	{
		if ($this->$attribute<0)
			$this->addError($attribute,$this->getAttributeLabel($attribute).' 不能小于0');
		else if (!preg_match('/^[0-9]{1,10}(.[0-9]{1,2})?$/', $this->$attribute))
			$this->addError($attribute,$this->getAttributeLabel($attribute).' 只能有两位小数且最大12位');
	}
	
	/**
	 * 显示价格
	 * @param unknown $models
	 * @param string $all
	 * @return string
	 */
	public static  function show_fare($models,$all=false,$type=false){
		if($all==false)
			$attributes=array('name'=>'','info'=>'','price'=>'元');
		else 
			$attributes=array('name'=>'','info'=>'平方','number'=>'人间','price'=>'元');
		
		$html='<table border="1" style="width:400px;text-align:center;margin-bottom:0">';

		if($type)
		{
			foreach ($models as $model)
			{
				$html .='<tr>';
				foreach ($attributes as $attribute=>$unit)
					$html .='<td>'.CHtml::encode($model->ProFare_Fare->$attribute).' '.$unit.'</td>';
				$html .='</tr>';
			}
		}else{
			foreach ($models as $model)
			{
				$html .='<tr>';
				foreach ($attributes as $attribute=>$unit)
					$html .='<td>'.CHtml::encode($model->$attribute).' '.$unit.'</td>';
				$html .='</tr>';
			}
		}

		return $html.'</table>';
	}

	/**
	 * 显示价格
	 * @param unknown $models
	 * @param string $all
	 * @return string
	 */
	public static  function show_fare_ul($models,$all=false,$type=false){
		if($all==false)
			$attributes=array('name'=>'','info'=>'','price'=>'元');
		else
			$attributes=array('name'=>'','info'=>'平方','number'=>'人间','price'=>'元');

		$html='<ul>';

		if($type)
		{
			foreach ($models as $model)
			{
				$html .='<li>';
				foreach ($attributes as $attribute=>$unit)
					$html .='<span>'.CHtml::encode($model->ProFare_Fare->$attribute).' '.$unit.'</span>';
				$html .='</li>';
			}

		}else{
			foreach ($models as $model)
			{
				$html .='<li>';
				foreach ($attributes as $attribute=>$unit)
					$html .='<span>'.CHtml::encode($model->$attribute).' '.$unit.'</span>';
				$html .='</li>';
			}
		}

		return $html.'</ul>';
	}

	/**
	 * 显示价格====api
	 * @param unknown $models
	 * @param string $all
	 * @return string
	 */
	public static  function show_fare_api($models,$all=false,$type=false){
		if($all==false)
			$attributes=array('id'=>'','name'=>'','info'=>'','price'=>' 元');
		else
			$attributes=array('id'=>'','name'=>'','info'=>' 平方','number'=>' 人间','price'=>' 元');

		$html=array();


		if($type)
		{
			foreach ($models as $k=>$model)
			{
				foreach ($attributes as $attribute=>$unit)
					$html[$k][$attribute] =$model->ProFare_Fare->$attribute.$unit;
			}

		}else{
			foreach ($models as  $k=>$model)
			{
				foreach ($attributes as $attribute=>$unit)
					$html[$k][$attribute] =$model->$attribute.$unit;
			}
		}


		return $html;
	}
	/**
	 * 显示价格
	 * @param unknown $models
	 * @param string $all
	 * @return string
	 */
	public static  function show_order_organizer_fare($models,$all=false){
		if($all==false)
			$attributes=array('name'=>'','info'=>'','price'=>'');
		else
			$attributes=array('name'=>'','info'=>'平方','number'=>'人间','price'=>'');

		$html='<table border="1" style="width:400px;text-align:center;margin-bottom:0">';

		foreach ($models->OrderItems_OrderItemsFare as $model)
		{
			$html .='<tr>';
			foreach ($attributes as $attribute=>$unit) {
				$attribute = 'fare_'.$attribute;
				if($attribute=='fare_name') {
					$total =  '  <span style="color:red;">&yen; '.$model->total.'</span>';
					$html .= '<td>' . CHtml::encode($model->$attribute) . $total . ' ' . $unit . '</td>';
				}elseif($attribute=='fare_price'){
					$number =  '<span style="color:green;">'.CHtml::encode($model->$attribute) .' &times; ' .$model->number.'</span>';
					$html .= '<td>'.$number  . $unit . '</td>';
				}
				else
					$html .= '<td>' . CHtml::encode($model->$attribute) . ' ' . $unit . '</td>';
			}
			$html .='</tr>';
		}

		return $html.'</table>';
	}
	/**
	 * 显示价格(结伴游)====api
	 * @param unknown $models
	 * @param string $all
	 * @return string
	 */
	public static  function show_order_organizer_fare_api($models,$all=false,$fare=false){
		if($all==false)
			$attributes=array('name'=>'','info'=>'','price'=>'元');
		else
			$attributes=array('name'=>'','info'=>'平方','number'=>'人间','price'=>'元');

		$html=array();

		foreach ($models->OrderItems_OrderItemsFare as  $k=>$model)
		{
			foreach ($attributes as $attribute=>$unit){
				$attribute = 'fare_'.$attribute;
				$attribute_fare = $fare==false?'':substr($attribute,5);
				$html[$k][$attribute_fare] =$model->$attribute.' '.$unit;

			}
		}
		return $html;
	}
	/**
	 * 计算 几人间
	 */
	function number_count()
	{
		if($this->number<0 || $this->number>4)
			$this->addError('number', '不在范围内');
	}
	
	/**
	 * 计算 平方
	 */
	function info_count()
	{
		if($this->info<=0)
			$this->addError('info', '不是有效数字');
	}
	
	/**
	 * 设置订单项目价格数据
	 * @param unknown $data
	 * @param unknown $params
	 * @return OrderItemsFare
	 */
	public static function set_order_items_fare($data,$params=array())
	{
		$shops_model=$params['shops_model'];
		$pro_items_model=$params['pro_items_model'];
		$price_number=$params['price_number'];
		$model=new OrderItemsFare;
		$model->scenario='create';
		$model->user_id=Yii::app()->api->id;
		$floor = Yii::app()->controller;
		if(isset($shops_model->Shops_Actives))
		{
			$model->order_items_fare_id=$data->id;//归属父
			$model->organizer_id=$shops_model->Shops_Actives->organizer_id;
			$model->store_id=$data->store_id;
			$model->manager_id=$data->manager_id;
			$model->agent_id=$data->agent_id;
			$model->shops_id=$data->shops_id;
			$model->shops_c_id=$data->shops_c_id;
			$model->items_id=$data->items_id;
			$model->items_c_id=$data->items_c_id;
			$model->fare_id=$data->fare_id;
			$model->fare_name=$data->fare_name;
			$model->fare_info=$data->fare_info;
			$model->fare_number=$data->fare_number;
			$model->fare_price=$data->fare_price;
			$model->fare_up_time=$data->fare_up_time;
			$model->price=$price_number['price'];
			$model->number=$price_number['number'];
			$model->total=$price_number['count'];		
			//新增
			$model->start_date=isset($price_number['start_date'])?$price_number['start_date']:0;
			$model->end_date=isset($price_number['end_date'])?$price_number['end_date']:0;
			$model->hotel_number=isset($price_number['hotel_number'])?$price_number['hotel_number']:1;
		}
		else
		{
			$model->store_id=$data->store_id;
			$model->manager_id=$pro_items_model->Pro_Items->manager_id;
			$model->agent_id=$data->agent_id;
			$model->shops_id=$shops_model->id;
			$model->shops_c_id=$shops_model->c_id;
			$model->items_id=$pro_items_model->Pro_Items->id;
			$model->items_c_id=$pro_items_model->Pro_Items->Items_ItemsClassliy->id;
			$model->fare_id=$data->id;
			$model->fare_name=$data->name;
			$model->fare_info=$data->info;
			$model->fare_number=$data->number;
			$model->fare_price=$data->price;
			$model->fare_up_time=$data->up_time;	
			$model->price=$price_number['price'];
			$model->number=$price_number['number'];
			$model->total=$price_number['count'];		
			//新增
			$model->start_date=isset($price_number['start_date'])?$price_number['start_date']:0;
			$model->end_date=isset($price_number['end_date'])?$price_number['end_date']:0;
			$model->hotel_number=isset($price_number['hotel_number'])?$price_number['hotel_number']:1;
		}
		Order::$_money[]=array(
				'price'=>$floor->floorMul($price_number['price'], $model->hotel_number),
				'number'=>$price_number['number'],
				'total'=>$price_number['count'],
		);
		Order::$_money_count = $floor->floorAdd(Order::$_money_count, $price_number['count']);
		Order::$order_fare_count++;
		return $model;
	}
	
	/**
	 * 验证价格
	 * @param unknown $pro_items_model
	 * @param unknown $fare_model
	 * @param unknown $price_number
	 * @return boolean
	 */
	public static function validate_fare($pro_items_model,$fare_model,$price_number)
	{
		if($pro_items_model->Pro_Items->Items_ItemsClassliy->append == 'Hotel')
		{
			if($fare_model->number == 0)
			{
				return false;
			}
			return $price_number['number']==ceil(Order::$adult_number/$fare_model->number);
		}
		else
		{
			if(! isset(Fare::$info_number_room[$fare_model->info]))
			{
				return false;
			}
			if(Fare::$info_number_room[$fare_model->info]==Fare::info_adult)
			{
				return $price_number['number']==Order::$adult_number;
			}
			elseif(Fare::$info_number_room[$fare_model->info]==Fare::info_children)
			{
				return $price_number['number']==Order::$children_number;
			}
		}
		return false;
	}
	
	/**
	 * 验证价格
	 * @param unknown $pro_items_model
	 * @param unknown $fare_model
	 * @param unknown $price_number
	 * @return boolean
	 */
	public static function validate_fare_actives_tour($items_model,$fare_model,$price_number)
	{
		if($items_model->items_c_id == Items::items_hotel)
		{
			if($fare_model->fare_number == 0)
			{
				return false;
			}
			return $price_number['number']==ceil(Order::$adult_number/$fare_model->fare_number);
		}
		else
		{
			if(! isset(Fare::$info_number_room[$fare_model->fare_info]))
			{
				return false;
			}
			if(Fare::$info_number_room[$fare_model->fare_info]==Fare::info_adult)
			{
				return $price_number['number']==Order::$adult_number;
			}
			elseif(Fare::$info_number_room[$fare_model->fare_info]==Fare::info_children)
			{
				return $price_number['number']==Order::$children_number;
			}
		}
		return false;
	}
}
