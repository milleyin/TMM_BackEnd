<?php

/**
 * This is the model class for table "{{thrand}}".
 *
 * The followings are the available columns in table '{{thrand}}':
 * @property string $id
 * @property string $c_id
 */
class Thrand extends CActiveRecord
{
	/**
	 * 搜索的时间类型
	 * @var array
	 */
	public $search_time_type;
	/**
	 *	解释搜索类型时间的含义 search_time_type
	 * @var string
	 */
	public static $_search_time_type=array('发布时间','创建时间','更新时间');
	/**
	 *	解释搜索类型时间 search_time_type 的字段搜索
	 * @var string
	 */
	public $__search_time_type=array('pub_time','add_time','up_time');

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
	 * 选中项目
	 */
	public $select_items;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{thrand}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, c_id', 'required'),
			array('id, c_id', 'length', 'max'=>11),
			
			//array('','safe','on'=>'create,update'),
			//array('','unsafe','on'=>'create,update'),
			
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('search_time_type,search_start_time,search_end_time,id, c_id', 'safe', 'on'=>'search'),
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
			//线路(线)关联主表 一对一
			'Thrand_Shops'=>array(self::BELONGS_TO,'Shops','id'),
			//线路(线)关联类型表 归属（多对一）
			'Thrand_ShopsClassliy'=>array(self::BELONGS_TO,'ShopsClassliy','c_id'),
			//线路(线)关联 选中项目表 (一对多)
			'Thrand_Pro'=>array(self::HAS_MANY,'Pro','shops_id'),
			//线路(线)关联的标签        一对多
			'Thrand_TagsElement'=>array(self::HAS_MANY,'TagsElement','element_id'),
			
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'c_id' => '关联项目数据模型表（items_classliy）主键id',
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

			$criteria->with=array(
				'Thrand_ShopsClassliy',
				'Thrand_Shops'=>array(
					'with'=>array(
						'Shops_Agent',
					)),
			);

			if($this->search_time_type != '' && isset($this->__search_time_type[$this->search_time_type]))
			{
				if($this->search_start_time !='' && $this->search_end_time !='')
					$criteria->addBetweenCondition('Thrand_Shops.'.$this->__search_time_type[$this->search_time_type],strtotime($this->search_start_time),strtotime($this->search_end_time)+3600*24-1);
				elseif($this->search_start_time !='' && $this->search_end_time =='')
					$criteria->compare('Thrand_Shops.'.$this->__search_time_type[$this->search_time_type],'>='.strtotime($this->search_start_time));
				elseif($this->search_start_time =='' && $this->search_end_time !='')
					$criteria->compare('Thrand_Shops.'.$this->__search_time_type[$this->search_time_type],'<='.strtotime($this->search_end_time)+3600*24-1);
			}

			$criteria->compare('Thrand_Shops.name',$this->Thrand_Shops->name,true);
			$criteria->compare('Shops_Agent.phone',$this->Thrand_Shops->agent_id,true);
			$criteria->compare('Thrand_Shops.brow',$this->Thrand_Shops->brow,true);

			$criteria->compare('Thrand_Shops.share',$this->Thrand_Shops->share,true);
			$criteria->compare('Thrand_Shops.praise',$this->Thrand_Shops->praise,true);
			$criteria->compare('Thrand_Shops.audit',$this->Thrand_Shops->audit,true);
			$criteria->compare('Thrand_Shops.status',$this->Thrand_Shops->status,true);
			$criteria->compare('Thrand_Shops.is_sale',$this->Thrand_Shops->is_sale,true);
			$criteria->compare('Thrand_Shops.list_info',$this->Thrand_Shops->list_info,true);
			$criteria->compare('Thrand_Shops.page_info',$this->Thrand_Shops->page_info,true);
			$criteria->compare('Thrand_Shops.selected',$this->Thrand_Shops->selected);
			$criteria->compare('Thrand_Shops.tops',$this->Thrand_Shops->tops);
			$criteria->compare('Thrand_Shops.selected_tops',$this->Thrand_Shops->selected_tops);
			$criteria->compare('t.id',$this->id,true);
			$criteria->compare('t.c_id',$this->c_id,true);

			if($this->Thrand_Shops->pub_time != '')
				$criteria->addBetweenCondition('Thrand_Shops.pub_time',strtotime($this->Thrand_Shops->pub_time),(strtotime($this->Thrand_Shops->pub_time)+3600*24-1));
			if($this->Thrand_Shops->add_time != '')
				$criteria->addBetweenCondition('Thrand_Shops.add_time',strtotime($this->Thrand_Shops->add_time),(strtotime($this->Thrand_Shops->add_time)+3600*24-1));
			if($this->Thrand_Shops->up_time != '')
				$criteria->addBetweenCondition('Thrand_Shops.up_time',strtotime($this->Thrand_Shops->up_time),(strtotime($this->Thrand_Shops->up_time)+3600*24-1));

			$criteria->compare('Thrand_Shops.status','<>-1');
		}
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
					'pageSize'=>Yii::app()->params['admin_pageSize'],
			),	
			'sort'=>array(
				'defaultOrder'=>'Thrand_Shops.add_time desc', //设置默认排序
				'attributes'=>array(
					'id',
					'Thrand_Shops.name'=>array(
						'desc'=>'Thrand_Shops.name desc',
					),
					'Thrand_Shops.agent_id'=>array(
						'desc'=>'Shops_Agent.phone desc',
					),
					'Thrand_Shops.brow'=>array(
						'desc'=>'Thrand_Shops.brow desc',
					),
					'Thrand_Shops.share'=>array(
						'desc'=>'Thrand_Shops.share desc',
					),
					'Thrand_Shops.praise'=>array(
						'desc'=>'Thrand_Shops.praise desc',
					),
					'Thrand_Shops.pub_time'=>array(
						'desc'=>'Thrand_Shops.pub_time desc',
					),
					'Thrand_Shops.add_time'=>array(
						'desc'=>'Thrand_Shops.add_time desc',
					),
					'Thrand_Shops.audit'=>array(
						'desc'=>'Thrand_Shops.up_time desc',
					),
					'Thrand_Shops.status'=>array(
						'desc'=>'Thrand_Shops.status desc',
					),
					'Thrand_Shops.status'=>array(
							'desc'=>'Thrand_Shops.status desc',
					),
				)
			),

		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Thrand the static model class
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
			return true;
		}else
			return false;
	}
	
	/**
	 * 订单中数据
	 */
	public static function get_fare($model)
	{
		$criteria = new CDbCriteria;
		$criteria->with=array(
				'Shops_ShopsClassliy',
				'Shops_Pro'=>array(
						'with'=>array(
								'Pro_ProFare'=>array('with'=>array('ProFare_Fare')),
								'Pro_Items'=>array('with'=>array('Items_ItemsClassliy')),
						),
				),
		);
		$criteria->addColumnCondition(array(
				't.status'=>Shops::status_online,//上线
				't.audit'=>Shops::audit_pass,//审核通过
				't.is_sale'=>Shops::is_sale_yes,//可卖
				't.c_id'=>$model->Shops_ShopsClassliy->id,
		));
		$criteria->order='Shops_Pro.day_sort,Shops_Pro.half_sort,Shops_Pro.sort';
		return self::data_json(Shops::data($model->id,$criteria));
	}
	
	/**
	 * 数据 转化成 json
	 */
	public static function data_json($model)
	{		
		$return = array();
		if(! $model)
			return array();	
		if(!isset($model->Shops_Pro) || empty($model->Shops_Pro) || !is_array($model->Shops_Pro))
			return array();
		if(!isset($model->Shops_ShopsClassliy) || empty($model->Shops_ShopsClassliy))
			return array();
		
		// 线名称
		$return['name'] = $model->name;
		// 线id
		$return['value'] = $model->id;
		// 商品类型（线）
		$return['classliy'] = array(
				'name' => $model->Shops_ShopsClassliy->name,
				'value' => $model->Shops_ShopsClassliy->id,
		);
		foreach($model->Shops_Pro as $key=>$pro)
		{
			if(!isset($pro->Pro_Items) || empty($pro->Pro_Items))
				return array();
			if(!isset($pro->Pro_Items->Items_ItemsClassliy) || empty($pro->Pro_Items->Items_ItemsClassliy))
				return array();
			if(!isset($pro->Pro_ProFare) || empty($pro->Pro_ProFare) || !is_array($pro->Pro_ProFare))
				return array();
			//项目是否通过审核 上线 否则 返回空数据
			if ($pro->Pro_Items->audit == Items::audit_pass && $pro->Pro_Items->status == Items::status_online )
			{
				foreach ($pro->Pro_ProFare as $fare)
				{
					if(! isset($fare->ProFare_Fare->id))
						return array();
					// 项目名称
					$return['dot_list'][$pro->day_sort][$pro->half_sort][$pro->dot_id][$pro->sort]['name'] = $pro->Pro_Items->name;
					// 项目id
					$return['dot_list'][$pro->day_sort][$pro->half_sort][$pro->dot_id][$pro->sort]['value'] = $pro->Pro_Items->id;
					// 免费项目
					$return['dot_list'][$pro->day_sort][$pro->half_sort][$pro->dot_id][$pro->sort]['free_status'] = array(
							'name'=>Items::$_free_status[$pro->Pro_Items->free_status],
							'value'=>$pro->Pro_Items->free_status,
					);
					// 项目类型
					$return['dot_list'][$pro->day_sort][$pro->half_sort][$pro->dot_id][$pro->sort]['classliy'] = array(
							'name' => $pro->Pro_Items->Items_ItemsClassliy->name,
							'value' => $pro->Pro_Items->Items_ItemsClassliy->id,
					);
					// 拼接的约定的规范格式
					$return['OrderItems'][$pro->day_sort][$pro->half_sort][$pro->dot_id]
					[$pro->sort][$pro->Pro_Items->id][][$fare->ProFare_Fare->id]= array(
							'price' =>$fare->ProFare_Fare->price,
							'number' => '0',
							'count' => '0.00',
					);
					// 价格信息统计
					if ($pro->Pro_Items->Items_ItemsClassliy->append != 'Hotel')
					{
						$return['OrderItemsFare'][$fare->ProFare_Fare->info]=array(
								'info'=>$fare->ProFare_Fare->info,
								'number'=>0,
								'is_room'=>isset(Fare::$info_number_room[$fare->ProFare_Fare->info])?Fare::$info_number_room[$fare->ProFare_Fare->info]:Fare::info_adult,
						);
					}
					// 价格信息
					$return['dot_list'][$pro->day_sort][$pro->half_sort][$pro->dot_id][$pro->sort]['fare'][] = array(
							'value'=> $fare->ProFare_Fare->id,
							'name' => $fare->ProFare_Fare->name,
							'info' => $fare->ProFare_Fare->info,
							'number' => 0,
							'room_number'=>$fare->ProFare_Fare->number,
							'price' => $fare->ProFare_Fare->price,
					);
				}
			}else
				return array();
		}
		if(isset($return['OrderItemsFare']))
			$return['OrderItemsFare']=array_values($return['OrderItemsFare']);	
		$return['OrderItems']['value']=$model->id;			//线路id	
		return $return;
	}
	
	/**
	 * 验证线路
	 */
	public static function validate_thrand($model)
	{
		//获取线路id
		if(!isset($_POST['OrderItems']['value']))
		{
			$model->addError('order_price','订单中商品 不是有效值');
			$model->addError('status','T04');
			return false;
		}
		$shops_id=$_POST['OrderItems']['value'];
		unset($_POST['OrderItems']['value']);
		//获取线路数据array(model,data)
		$shops_data=Thrand::get_thrand($shops_id);
		if(empty($shops_data) || !isset($shops_data['data']['OrderItemsFare']) || empty($shops_data['data']['OrderItemsFare']) || ! is_array($shops_data['data']['OrderItemsFare']))
		{
			$model->addError('order_price','订单中商品 不是有效值');
			$model->addError('status','T05');
			return false;
		}
		if(!isset($shops_data['thrand_model']))
		{
			$model->addError('order_price','订单中商品 不是有效值');
			$model->addError('status','T05');
			return false;
		}
		//线路商品id
		$shops_model=$shops_data['thrand_model'];
		//复制
		$order_shops_model=Shops::set_order_shops($shops_model);
		//价格数据信息
		$fare_data=$shops_data['data']['OrderItemsFare'];
		
		if(count($fare_data) != count($_POST['OrderItemsFare']))
		{
			$model->addError('order_price','订单中商品 不是有效值');
			$model->addError('status','T06');
			return false;
		}
		//遍历价格选择
		$room_or_info=0;//类型排序
		foreach ($_POST['OrderItemsFare'] as $main_key=>$info)
		{
			if($main_key != $room_or_info || $room_or_info > count(Fare::$info_number_room))
			{
				$model->addError('order_price','订单中商品 不是有效值');
				$model->addError('status','T07');
				return false;
			}
			if(empty($info) || !is_array($info) || !isset($info['info'],$info['number'],$info['is_room']) || count($info) != 3)
			{
				$model->addError('order_price','订单中商品 不是有效值');
				$model->addError('status','T08');
				return false;
			}
			if(!isset($fare_data[$main_key]['info'],$fare_data[$main_key]['number'],$fare_data[$main_key]['is_room']))
			{
				$model->addError('order_price','订单中商品 不是有效值');
				$model->addError('status','T09');
				return false;
			}
			if(! isset(Fare::$info_number_room[$info['info']]))
			{
				$model->addError('order_price','订单中商品 不是有效值');
				$model->addError('status','T10');
				return false;
			}
			if($fare_data[$main_key]['info'] != $info['info'] || $fare_data[$main_key]['is_room'] != $info['is_room'])
			{
				$model->addError('order_price','订单中商品 不是有效值');
				$model->addError('status','T11');
				return false;
			}
			if(! is_int($info['number']))
			{
				$model->addError('order_price','订单中商品 不是有效值');
				$model->addError('status','T12');
				return false;
			}
			if(0 > $info['number'] || $info['number'] > Yii::app()->params['order_limit']['thrand']['number'])
			{
				$model->addError('order_price','订单中商品 不是有效值');
				$model->addError('status','T13');
				return false;
			}
			//成人
			if(Fare::info_adult==Fare::$info_number_room[$info['info']])
			{
				Order::$adult_number=$info['number'];
			}
			elseif(Fare::info_children==Fare::$info_number_room[$info['info']])
			{
				Order::$children_number=$info['number'];
			}
			else
			{
				$model->addError('order_price','订单中商品 不是有效值');
				$model->addError('status','T14');
				return false;
			}
			$room_or_info++;
		}
		//统计随行人员数量
		//$this->retinue_count=$this->children_number+$this->adult_number;
		
		if(!isset($shops_data['data']['OrderItems']) || empty($shops_data['data']['OrderItems']) || ! is_array($shops_data['data']['OrderItems']))
		{
			$model->addError('order_price','订单中商品 不是有效值');
			$model->addError('status','T15');
			return false;
		}
		//查看数据是否非法
		$items_data=$shops_data['data']['OrderItems'];
		
		//遍历价格选择
		if(isset($_POST['OrderItems']) && is_array($_POST['OrderItems']))
		{
			$OrderShops=array();//商品的数据模型
			//遍历选中点的项目
			$OrderItems=array();
			$items_number=0;//项目
			//天
			foreach ($_POST['OrderItems'] as $day_key=>$dot_key_dot_id)//
			{
				if(empty($dot_key_dot_id) || !is_array($dot_key_dot_id))
				{
					$model->addError('order_price','订单中商品 不是有效值');
					$model->addError('status','T16');
					return false;
				}
				//点的排序
				foreach ($dot_key_dot_id as $dot_key=>$dot_id_items_key)//
				{
					if(empty($dot_id_items_key) || ! is_array($dot_id_items_key))
					{
						$model->addError('order_price','订单中商品 不是有效值');
						$model->addError('status','T17');
						return false;
					}
					//点
					foreach ($dot_id_items_key as $dot_id=>$items_key_items_id)//
					{
						if(empty($items_key_items_id) || ! is_array($items_key_items_id))
						{
							$model->addError('order_price','订单中商品 不是有效值');
							$model->addError('status','T18');
							return false;
						}
						//项目排序
						foreach ($items_key_items_id as $items_key=>$items_id_fare_key)//
						{
							if(empty($items_id_fare_key) || ! is_array($items_id_fare_key))
							{
								$model->addError('order_price','订单中商品 不是有效值');
								$model->addError('status','T19');
								return false;
							}
							foreach ($items_id_fare_key as $items_id=>$fare_key_fare_id)//
							{
								if(empty($fare_key_fare_id) || ! is_array($fare_key_fare_id))
								{
									$model->addError('order_price','订单中商品 不是有效值');
									$model->addError('status','T20');
									return false;
								}
								//判断项目是否存在
								if(! isset($items_data[$day_key][$dot_key][$dot_id][$items_key][$items_id]))
								{
									$model->addError('order_price','订单中商品 不是有效值');
									$model->addError('status','T21');
									return false;
								}
								//验证项目
								if(! isset($shops_data['items_list'][$items_number][$items_id]['item_model']))
								{
									$model->addError('order_price','订单中商品 不是有效值');
									$model->addError('status','T22');
									return false;
								}
								//项目的对象
								$pro_items_model=$shops_data['items_list'][$items_number][$items_id]['item_model'];
								//复制项目
								$order_shops_items=Items::set_order_items($pro_items_model,array('shops_model'=>$shops_model));
								//遍历项目中的价格
								$OrderItemsFare=array();
								foreach ($fare_key_fare_id as $fare_key=>$fare_id_price_number)
								{
									if(empty($fare_id_price_number) || ! is_array($fare_id_price_number))
									{
										$model->addError('order_price','订单中商品 不是有效值');
										$model->addError('status','T23');
										return false;
									}
									foreach ($fare_id_price_number as $fare_id=>$price_number)
									{
										//判断价格是否存在
										if(! isset($items_data[$day_key][$dot_key][$dot_id][$items_key][$items_id][$fare_key][$fare_id]))
										{
											$model->addError('order_price','订单中商品 不是有效值');
											$model->addError('status','T24');
											return false;
										}
										if(!isset($price_number['price'],$price_number['number'],$price_number['count']))
										{
											$model->addError('order_price','订单中商品 不是有效值');
											$model->addError('status','T25');
											return false;
										}
										//价格信息验证
										$price_info=$items_data[$day_key][$dot_key][$dot_id][$items_key][$items_id][$fare_key][$fare_id];
										if(!isset($price_info['price'],$price_info['number'],$price_info['count']))
										{
											$model->addError('order_price','订单中商品 不是有效值');
											$model->addError('status','T26');
											return false;
										}
										//价格信息验证
										if(!is_numeric($price_number['number']) || $price_number['number'] < 0 || $price_number['price'] !== $price_info['price'])
										{
											$model->addError('order_price','订单中商品 不是有效值');
											$model->addError('status','T27');
											return false;
										}
										if( $price_number['number'] * $price_number['price'] != $price_number['count'])
										{
											$model->addError('order_price','订单中商品 不是有效值');
											$model->addError('status','T28');
											return false;
										}
										//获取价格数据
										if(! isset($shops_data['items_list'][$items_number][$items_id]['fare_model'][$fare_key][$fare_id]))
										{
											$model->addError('order_price','订单中商品 不是有效值');
											$model->addError('status','T29');
											return false;
										}
										$fare_model=$shops_data['items_list'][$items_number][$items_id]['fare_model'][$fare_key][$fare_id];//项目价格的对象
											
										$order_items_fare=Fare::set_order_items_fare($fare_model,array(
												'pro_items_model'=>$pro_items_model,
												'price_number'=>$price_number,
												'shops_model'=>$shops_model,
										));
										//线路
										if(! Fare::validate_fare($pro_items_model,$fare_model,$price_number))
										{
											$model->addError('order_price','订单中商品 不是有效值');
											$model->addError('status','T29');
											return false;
										}
										Order::$order_items_money += $price_number['count']; //记录项目的总价
										$OrderItemsFare[]=$order_items_fare;
										continue;
									}
								}
								if(! isset($order_shops_items) || empty($order_shops_items))
								{
									$model->addError('order_price','订单中商品 不是有效值');
									$model->addError('status','T30');
									return false;
								}
								if(! isset($OrderItemsFare) || empty($OrderItemsFare))
								{
									$model->addError('order_price','订单中商品 不是有效值');
									$model->addError('status','T30');
									return false;
								}
								$order_shops_items->total=Order::$order_items_money;//项目的价格赋值
								Order::$order_items_money=0.00; //清除项目统计价格
								$order_shops_items->OrderItems_OrderItemsFare=$OrderItemsFare;
								$OrderItems[]=$order_shops_items;
								continue;
							}
							$items_number++;//项目的排序
						}
						if(!isset($OrderItems) || empty($OrderItems))
						{
							$model->addError('order_price','订单中商品 不是有效值');
							$model->addError('status','T31');
							return false;
						}
						$order_shops_model->OrderShops_OrderItems=$OrderItems;
						continue;
					}
				}
			}
		}
		$OrderShops[]=$order_shops_model;
		if(!isset($OrderShops) || empty($OrderShops) || count($OrderShops) !=1 )
		{
			$model->addError('order_price','订单中商品 不是有效值');
			$model->addError('status','T32');
			return false;
		}
		$model->Order_OrderShops=$OrderShops;
		return true;
	}
	
	/**
	 * 获取线路的数据
	 * @return multitype:
	 */
	public static function get_thrand($shops_id)
	{
		Yii::app()->controller->_class_model='Thrand';
		$shops_classliy=ShopsClassliy::getClass();
		$return=array();
		$model=Shops::model()->findByPk($shops_id,array(
				'with'=>array(
						'Shops_Agent',
						'Shops_Thrand',
						'Shops_Pro'=>array(
								'with'=>array(
										'Pro_ProFare'=>array(
												'with'=>array('ProFare_Fare'),
										),
										'Pro_Items'=>array(
												'with'=>array(
														'Items_ItemsClassliy',
														'Items_area_id_p_Area_id',
														'Items_area_id_m_Area_id',
														'Items_area_id_c_Area_id',
														'Items_ItemsImg',
														'Items_StoreContent'=>array('with'=>array('Content_Store')),
														'Items_Store_Manager',
												),
										),
								),
						),
				),
				'condition'=>'t.c_id=:c_id AND t.status=:status AND t.audit=:audit AND `t`.`is_sale`=:is_sale',
				'params'=>array(':is_sale'=>Shops::is_sale_yes,':c_id'=>$shops_classliy->id,':audit'=>Shops::audit_pass,':status'=>Shops::status_online),
				'order'=>'Shops_Pro.day_sort,Shops_Pro.half_sort,Shops_Pro.sort',
		));
		if(! $model)
			return array();
		if(! isset($model->Shops_Pro) || empty($model->Shops_Pro))
			return array();
		$return['thrand_model']=$model;
		//遍历数据
		foreach($model->Shops_Pro as $key=>$pro)
		{
			if(! isset($pro->Pro_Items) || empty($pro->Pro_Items))
				return array();
			if(! isset($pro->Pro_Items->Items_ItemsClassliy) || empty($pro->Pro_Items->Items_ItemsClassliy))
				return array();
			if(!isset($pro->Pro_ProFare) || empty($pro->Pro_ProFare) || !is_array($pro->Pro_ProFare))
				return array();
			//项目是否通过审核 上线 否则 返回空数据
			if(! ($pro->Pro_Items->audit == Items::audit_pass && $pro->Pro_Items->status == Items::status_online))
			{
				return array();
			}
			$return['items_list'][$key][$pro->Pro_Items->id]['item_model']=$pro;
		
			foreach ($pro->Pro_ProFare as $fare)
			{
				$return['items_list'][$key][$pro->Pro_Items->id]['fare_model'][][$fare->ProFare_Fare->id]=$fare->ProFare_Fare;
				// 拼接的约定的规范格式
				$return['data']['OrderItems'][$pro->day_sort][$pro->half_sort][$pro->dot_id]
				[$pro->sort][$pro->Pro_Items->id][][$fare->ProFare_Fare->id]= array(
						'price' => $fare->ProFare_Fare->price,
						'number' => 0,
						'count' => '0.00',
				);
				// 价格信息统计
				if ($pro->Pro_Items->Items_ItemsClassliy->append != 'Hotel')
				{
					$return['data']['OrderItemsFare'][$fare->ProFare_Fare->info]=array(
							'info'=>$fare->ProFare_Fare->info,
							'number'=>0,
							'is_room'=>isset(Fare::$info_number_room[$fare->ProFare_Fare->info])?Fare::$info_number_room[$fare->ProFare_Fare->info]:Fare::info_adult,
					);
				}
			}
		}
		if(isset($return['data']['OrderItemsFare']))
			$return['data']['OrderItemsFare']=array_values($return['data']['OrderItemsFare']);
		else 
			return array();
		return $return;
	}

	/**
	 * 计算线的成人 总价
	 * @param $id
	 * @return int
	 */
	public static function shops_price_num($id)
	{
		$criteria = new CDbCriteria;
		$criteria->select='`t`.`id`';
		$criteria->with=array(
				'Pro_ProFare'=>array(
						'select'=>'`id`',
						'with'=>array(
								'ProFare_Fare'=>array('select'=>'`price`')
						),
				),
		);
		$criteria->addColumnCondition(array(
				'`t`.`shops_id`'=>$id,
		));
		$criteria->addColumnCondition(array(
				'`ProFare_Fare`.`c_id`'=>Items::items_hotel, 						//住
				'`ProFare_Fare`.`info`'=>Fare::$__info[Fare::info_adult] 		//成人
		),'OR');
		$models=Pro::model()->findAll($criteria);
		
		$num = 0.00;
		foreach ($models as $model)
		{
			foreach ($model->Pro_ProFare as $fare)
				$num += $fare->ProFare_Fare->price;
		}
		return $num;
	}

	
	/**
	 * 获取下单量
	 */
	public static function get_down($id)
	{
		$criteria =new CDbCriteria;
		$criteria->select='SUM(`Pro_Items`.`down`) AS select_items';
		$criteria->with=array(
			'Thrand_Pro'=>array(
				'select'=>'`id`',
				'with'=>array(						
					'Pro_Items'=>array(
							'select'=>'`id`'
					)
				)
			),
		);
		$model=self::model()->findByPk($id,$criteria);
		if($model && $model->select_items)
			return $model->select_items;
		return 0;
	}
}
