<?php

/**
 * This is the model class for table "{{order_shops}}".
 *
 * The followings are the available columns in table '{{order_shops}}':
 * @property string $id
 * @property string $order_id
 * @property string $user_id
 * @property string $shops_id
 * @property string $shops_c_id
 * @property string $shops_c_name
 * @property string $shops_agent_id
 * @property string $shops_name
 * @property string $shops_list_img
 * @property string $shops_page_img
 * @property string $shops_list_info
 * @property string $shops_page_info
 * @property string $shops_cost_info
 * @property string $shops_book_info
 * @property integer $shops_pub_time
 * @property string $shops_add_time
 * @property string $shops_up_time
 * @property integer $actives_type
 * @property string $actives_organizer_id
 * @property integer $actives_tour_type
 * @property string $tour_price
 * @property string $remark
 * @property string $start_time
 * @property string $end_time
 * @property string $pub_time
 * @property string $add_time
 * @property string $up_time
 * @property integer $status
 */
class OrderShops extends CActiveRecord
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
	public static $_search_time_type=array('商品发布时间','商品添加时间','商品更新时间','觅趣开始时间','觅趣结束时间','觅趣发布时间','创建时间','更新时间'); 
	/**
	 *	解释搜索类型时间 search_time_type 的字段搜索
	 * @var string
	 */
	public $__search_time_type=array('shops_pub_time','shops_add_time','shops_up_time','start_time','end_time','pub_time','add_time','up_time'); 
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
	 * 是否设置了出游时间
	 * @var unknown
	 */
	public $is_go_time = false;
	/**
	 * 活动服务费用
	 * @var unknown
	 */
	public $_user_price = 0.00;
	/**
	 *活动总订单 
	 * @var unknown
	 */
	public $_order_organizer_id;
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{order_shops}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			//array('order_id, shops_id, shops_c_id, shops_pub_time, remark', 'required'),
			array('shops_pub_time, actives_type, actives_tour_type, status', 'numerical', 'integerOnly'=>true),
			array('order_id, shops_id, shops_c_id, shops_agent_id, actives_organizer_id', 'length', 'max'=>11),
			array('user_id, shops_add_time, shops_up_time, start_time, end_time, pub_time, add_time, up_time', 'length', 'max'=>10),
			array('shops_c_name', 'length', 'max'=>20),
			array('shops_name, shops_list_img, shops_page_img, shops_list_info, shops_page_info', 'length', 'max'=>128),
			array('tour_price', 'length', 'max'=>13),
			
			//创建复制商品 
			array('user_id,shops_id,shops_c_id', 'required','on'=>'create'),	
			
			//活动代付下单
			array('shops_id', 'required', 'on'=>'actives_tour_full'),
			array('shops_id', 'actives_tour_full', 'on'=>'actives_tour_full'),
			array('shops_id', 'safe', 'on'=>'actives_tour_full'),
			array('shops_cost_info,shops_book_info,search_time_type,search_start_time,search_end_time,id, order_id, user_id, shops_c_id, shops_c_name, shops_agent_id, shops_name, shops_list_img, shops_page_img, shops_list_info, shops_page_info, shops_pub_time, shops_add_time, shops_up_time, actives_type, actives_organizer_id, actives_tour_type, tour_price, remark, start_time, end_time, pub_time, add_time, up_time, status', 'unsafe', 'on'=>'actives_tour_full'),
			
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('shops_cost_info,shops_book_info,search_time_type,search_start_time,search_end_time,id, order_id, user_id, shops_id, shops_c_id, shops_c_name, shops_agent_id, shops_name, shops_list_img, shops_page_img, shops_list_info, shops_page_info, shops_pub_time, shops_add_time, shops_up_time, actives_type, actives_organizer_id, actives_tour_type, tour_price, remark, start_time, end_time, pub_time, add_time, up_time, status', 'safe', 'on'=>'search'),
			//运营商订单搜索
			array('search_time_type,search_start_time,search_end_time, user_id, shops_id, shops_c_name, shops_name, shops_list_info, shops_page_info', 'safe', 'on'=>'operatorSearch'),		
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
			//订单项目详细情况(一对多)
			'OrderShops_OrderItems'=>array(self::HAS_MANY,'OrderItems','order_shops_id'),
			//订单项目详细情况(一对多)
			'OrderShops_ShopsClassliy'=>array(self::BELONGS_TO,'ShopsClassliy','shops_c_id'),
			// 订单商品归属订单总表
			'OrderShops_Order'=>array(self::BELONGS_TO,'Order','order_id'),
			//用户表
			'OrderShops_User'=>array(self::BELONGS_TO,'User','user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => '主键',
			'order_id' => '订单',
			'user_id' => '用户',
			'shops_id' => '内容来源',
			'shops_c_id' => '内容分类',
			'shops_c_name' => '分类名称',
			'shops_agent_id' => '运营商',
			'shops_name' => '内容名称',
			'shops_list_img' => '列表头图',
			'shops_page_img' => '详情头图',
			'shops_list_info' => '列表简介',
			'shops_page_info' => '详情简介',
			'shops_cost_info'=>'预定须知',
			'shops_book_info'=>'费用包含',
			'shops_pub_time' => '通过时间',
			'shops_add_time' => '创建时间',
			'shops_up_time' => '更新时间',
			'actives_type' => '觅趣分类',
			'actives_organizer_id' => '组织者',
			'actives_tour_type' => '觅趣旅游分类',
			'tour_price' => '服务费',
			'remark' => '备注',
			'start_time' => '报名开始时间',
			'end_time' => '报名结束时间',
			'pub_time' => '发布时间',
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
			$criteria->compare('order_id',$this->order_id,true);
			$criteria->compare('user_id',$this->user_id,true);
			$criteria->compare('shops_id',$this->shops_id,true);
			$criteria->compare('shops_c_id',$this->shops_c_id,true);
			$criteria->compare('shops_c_name',$this->shops_c_name,true);
			$criteria->compare('shops_agent_id',$this->shops_agent_id,true);
			$criteria->compare('shops_name',$this->shops_name,true);
			$criteria->compare('shops_list_img',$this->shops_list_img,true);
			$criteria->compare('shops_page_img',$this->shops_page_img,true);
			$criteria->compare('shops_list_info',$this->shops_list_info,true);
			$criteria->compare('shops_page_info',$this->shops_page_info,true);
			$criteria->compare('shops_cost_info',$this->shops_cost_info,true);
			$criteria->compare('shops_book_info',$this->shops_book_info,true);
			if($this->shops_pub_time != '')
				$criteria->addBetweenCondition('shops_pub_time',strtotime($this->shops_pub_time),(strtotime($this->shops_pub_time)+3600*24-1));
			if($this->shops_add_time != '')
				$criteria->addBetweenCondition('shops_add_time',strtotime($this->shops_add_time),(strtotime($this->shops_add_time)+3600*24-1));
			if($this->shops_up_time != '')
				$criteria->addBetweenCondition('shops_up_time',strtotime($this->shops_up_time),(strtotime($this->shops_up_time)+3600*24-1));
			$criteria->compare('actives_type',$this->actives_type);
			$criteria->compare('actives_organizer_id',$this->actives_organizer_id,true);
			$criteria->compare('actives_tour_type',$this->actives_tour_type);
			$criteria->compare('tour_price',$this->tour_price,true);
			$criteria->compare('remark',$this->remark,true);
			if($this->start_time != '')
				$criteria->addBetweenCondition('start_time',strtotime($this->start_time),(strtotime($this->start_time)+3600*24-1));
			if($this->end_time != '')
				$criteria->addBetweenCondition('end_time',strtotime($this->end_time),(strtotime($this->end_time)+3600*24-1));
			if($this->pub_time != '')
				$criteria->addBetweenCondition('pub_time',strtotime($this->pub_time),(strtotime($this->pub_time)+3600*24-1));
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
	 * 订单搜索
	 * @param string $criteria
	 * @return CActiveDataProvider
	 */
	public function operatorSearch($criteria='')
	{
		// @todo Please modify the following code to remove attributes that should not be searched.
		if($criteria ==='')
		{
			$criteria=new CDbCriteria;
			$criteria->with=array(
					'OrderShops_User',
					'OrderShops_Order',
			);
			$criteria->compare('`OrderShops_Order`.`status`', '<>-1');
			$criteria->addCondition('`OrderShops_Order`.`order_type`=:dot OR `OrderShops_Order`.`order_type`=:thrand');
			$criteria->params[':dot'] = Order::order_type_dot;
			$criteria->params[':thrand'] = Order::order_type_thrand;
			//归属运营商
			$criteria->addColumnCondition(array(
					'`t`.`shops_agent_id`'=>Yii::app()->operator->id
			));
			//订单分组
			$criteria->group = '`t`.`order_id`';
			//组合搜索
			if ($this->search_time_type != '' && isset($this->OrderShops_Order->__search_time_type[$this->search_time_type]))
			{
				if ($this->search_start_time !='' && $this->search_end_time !='')
					$criteria->addBetweenCondition('OrderShops_Order.' . $this->OrderShops_Order->__search_time_type[$this->search_time_type], strtotime($this->search_start_time),strtotime($this->search_end_time)+3600*24-1);
				elseif ($this->search_start_time !='' && $this->search_end_time =='')
					$criteria->compare('OrderShops_Order.' . $this->OrderShops_Order->__search_time_type[$this->search_time_type], '>=' . strtotime($this->search_start_time));
				elseif ($this->search_start_time =='' && $this->search_end_time !='')
					$criteria->compare('OrderShops_Order.' . $this->OrderShops_Order->__search_time_type[$this->search_time_type], '<=' . (strtotime($this->search_end_time)+3600*24-1));
			}
			//内容搜索
			$criteria->compare('t.shops_name', $this->shops_name, true);
			$criteria->compare('t.shops_list_info', $this->shops_list_info, true);
			$criteria->compare('t.shops_page_info', $this->shops_page_info, true);
			$criteria->compare('t.shops_id', $this->shops_id, true);
			//其他搜索
			$criteria->compare('OrderShops_Order.order_no', $this->OrderShops_Order->order_no, true);
			$criteria->compare('OrderShops_Order.order_type', $this->OrderShops_Order->order_type);
			$criteria->compare('OrderShops_User.phone', $this->user_id, true);
			$criteria->compare('OrderShops_Order.order_price', $this->OrderShops_Order->order_price, true);
			$criteria->compare('OrderShops_Order.price', $this->OrderShops_Order->price, true);
			$criteria->compare('OrderShops_Order.pay_type',$this->OrderShops_Order->pay_type);
			$criteria->compare('OrderShops_Order.user_go_count',$this->OrderShops_Order->user_go_count,true);
			$criteria->compare('OrderShops_Order.status_go', $this->OrderShops_Order->status_go);
			$criteria->compare('OrderShops_Order.order_status', $this->OrderShops_Order->order_status);
			//时间搜索
			if($this->OrderShops_Order->pay_time != '')
				$criteria->addBetweenCondition('OrderShops_Order.pay_time', strtotime($this->OrderShops_Order->pay_time), (strtotime($this->OrderShops_Order->pay_time)+3600*24-1));	
			if($this->OrderShops_Order->add_time != '')
				$criteria->addBetweenCondition('OrderShops_Order.add_time', strtotime($this->OrderShops_Order->add_time), (strtotime($this->OrderShops_Order->add_time)+3600*24-1));
			if($this->OrderShops_Order->up_time != '')
				$criteria->addBetweenCondition('OrderShops_Order.up_time', strtotime($this->OrderShops_Order->up_time), (strtotime($this->OrderShops_Order->up_time)+3600*24-1));
			if($this->OrderShops_Order->go_time != '')
				$criteria->addBetweenCondition('OrderShops_Order.go_time', strtotime($this->OrderShops_Order->go_time), (strtotime($this->OrderShops_Order->go_time)+3600*24-1));		
		}
		return new CActiveDataProvider($this, array(
				'criteria'=>$criteria,
				'pagination'=>array(
						'pageSize'=>Yii::app()->params['admin_pageSize'],
				),
				'sort'=>array(
						'defaultOrder'=>'OrderShops_Order.add_time desc', //设置默认排序
						'attributes'=>array(
								'OrderShops_Order.order_no'=>array(
										'desc'=>'OrderShops_Order.order_no desc',
								),
								'OrderShops_Order.order_type'=>array(
										'desc'=>'OrderShops_Order.order_type desc',
								),
								'user_id'=>array(
										'asc'=>'OrderShops_User.phone',
										'desc'=>'OrderShops_User.phone desc',
								),
								'OrderShops_Order.order_price'=>array(
										'desc'=>'OrderShops_Order.order_price desc',
								),
								'OrderShops_Order.price'=>array(
										'desc'=>'OrderShops_Order.price desc',
								),
								'OrderShops_Order.pay_type'=>array(
										'desc'=>'OrderShops_Order.pay_type desc',
								),
								'OrderShops_Order.user_go_count'=>array(
										'desc'=>'OrderShops_Order.user_go_count desc',
								),
								'OrderShops_Order.status_go'=>array(
										'desc'=>'OrderShops_Order.status_go desc',
								),
								'OrderShops_Order.pay_time'=>array(
										'desc'=>'OrderShops_Order.pay_time desc',
								),
								'OrderShops_Order.add_time'=>array(
										'desc'=>'OrderShops_Order.add_time desc',
								),
								'OrderShops_Order.up_time'=>array(
										'desc'=>'OrderShops_Order.up_time desc',
								),
								'OrderShops_Order.go_time'=>array(
										'desc'=>'OrderShops_Order.go_time desc',
								),
								'OrderShops_Order.order_status'=>array(
										'desc'=>'OrderShops_Order.order_status desc',
								),
						),
				),
		));
	}
	
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return OrderShops the static model class
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
				$this->up_time=$this->add_time=time();
			else
				$this->up_time=time();			
			return true;
		}else
			return false;
	}
	
	/**
	 * 商品验证
	 * @param unknown $attribute
	 */
	public function actives_tour_full($attribute)
	{
		$criteria = new CDbCriteria;
		$criteria->with = array(
			'Actives_Shops'=>array(
					'with'=>array(
							'Shops_ShopsClassliy',
							'Shops_OrderActives'=>array(
									'with'=>array(
											'OrderActives_OrderItems'=>array(
													'with'=>array('OrderItems_OrderItemsFare'),
													'order'=>'OrderActives_OrderItems.shops_day_sort,OrderActives_OrderItems.shops_half_sort,OrderActives_OrderItems.shops_sort',
											),
									),
							),
					),
			),
		);
		//活动开始时间
		$criteria->compare('`t`.`start_time`', '<='.time());		
		//出游时间							
		$criteria->addCondition('`t`.`go_time`=0 OR `t`.`go_time`>:time');
		$criteria->params[':time'] = time();
		//活动状态 开始 或者 结束
		$criteria->addCondition('`t`.`actives_status`=:start OR `t`.`actives_status`=:end');
		$criteria->params[':start']=Actives::actives_status_start;
		$criteria->params[':end']=Actives::actives_status_end;
		//活动代付 只能下一次单
		$criteria->addCondition('`Shops_OrderActives`.`user_order_count`=0');
		//活动剩余数量 
		$criteria->addCondition('`t`.`order_number`>=0');
		//商品 
		$criteria->addColumnCondition(array(
				'`t`.`organizer_id`'=>Yii::app()->api->id,							//自己的活动
				'`t`.`pay_type`'=>Actives::pay_type_full,							//代付
				'`t`.`actives_type`'=>Actives::actives_type_tour,				//活动分类（旅游）			
				'`Actives_Shops`.`status`'=>Shops::status_online,			//上线
				'`Actives_Shops`.`audit`'=>Shops::audit_pass,				//审核通过
				'`Actives_Shops`.`is_sale`'=>Shops::is_sale_yes,				//可卖
		));
		// 活动是否存在
		$model = Actives::model()->findByPk($this->shops_id,$criteria);
		if ($model && isset($model->Actives_Shops->Shops_OrderActives->OrderActives_OrderItems))
		{
			$floor = Yii::app()->controller;
			//是否设置了出游时间
			$this->is_go_time = $model->go_time == 0 ? false : $model->go_time;
			//服务费用
			$this->_user_price = $model->tour_price;
			//归属总定单
			$this->_order_organizer_id = $model->Actives_Shops->Shops_OrderActives->id;
			//统计成人的人数
			$people = Attend::getColumnCount($model->id,1);
			//统计儿童的人数
			$children = Attend::getColumnCount($model->id,2);
			$OrderShops_OrderItems = array();
			$unsetItems = array('id', 'order_organizer_id', 'order_id' ,'order_shops_id', 'employ_time', 'barcode', 'add_time', 'up_time');
			foreach ($model->Actives_Shops->Shops_OrderActives->OrderActives_OrderItems as $OrderItems)
			{
				$OrderItems_OrderItemsFare = array();
				$unsetFare = array('id', 'order_items_id', 'order_organizer_id', 'order_id' ,'order_shops_id', 'add_time', 'up_time');
				$total = 0.00;
				foreach ($OrderItems->OrderItems_OrderItemsFare as $OrderItemsFare)
				{
					$fare = new OrderItemsFare;
 					$orderItemsFareAttributes = $OrderItemsFare->attributes;
 					//归属总价格
 					$orderItemsFareAttributes['order_items_fare_id'] = $orderItemsFareAttributes['id'];
 					//删除不用的属性
 					foreach ($unsetFare as $unset)
 						unset($orderItemsFareAttributes[$unset]);
 					//活动代付的订单归属 举办者
 					$orderItemsFareAttributes['user_id'] = $orderItemsFareAttributes['organizer_id'];
 					//项目价格的类型 住 人数/几人间 
 					if ($orderItemsFareAttributes['items_c_id'] == Items::items_hotel)
 					{
 						$orderItemsFareAttributes['number'] = ceil( $people / $orderItemsFareAttributes['fare_number']);
 						$orderItemsFareAttributes['total'] = $floor->floorMul($orderItemsFareAttributes['fare_price'], $orderItemsFareAttributes['number']);		
 					}
 					//项目价格的类型  成人 
 					else if (isset(Fare::$info_number_room[$orderItemsFareAttributes['fare_info']]) && Fare::$info_number_room[$orderItemsFareAttributes['fare_info']] == Fare::info_adult)
 					{
 						$orderItemsFareAttributes['number'] = $people;
 						$orderItemsFareAttributes['total'] = $floor->floorMul($orderItemsFareAttributes['fare_price'], $orderItemsFareAttributes['number']);
 					} 	
 					//项目价格的类型  儿童
 					else if (isset(Fare::$info_number_room[$orderItemsFareAttributes['fare_info']]) && Fare::$info_number_room[$orderItemsFareAttributes['fare_info']] == Fare::info_children)
 					{
 						$orderItemsFareAttributes['number'] = $children;
 						$orderItemsFareAttributes['total'] = $floor->floorMul($orderItemsFareAttributes['fare_price'], $orderItemsFareAttributes['number']);
 					}
 					else
 					{
 						$this->addError($attribute, '觅趣（代付） 数据异常');
 						break;
 					}
 					//echo $OrderItemsFare->fare_name.'：'.$OrderItemsFare->fare_price.'*'.$orderItemsFareAttributes['number'].'='.$orderItemsFareAttributes['total']."\n";
 					//计算项目中价格总价
 					$total = $floor->floorAdd($total, $orderItemsFareAttributes['total']);
 					//设置属性值
					$fare->setAttributes($orderItemsFareAttributes,false); 
					$OrderItems_OrderItemsFare[] = $fare;
				}
				$items = new OrderItems;
				$orderItemsAttributes = $OrderItems->attributes;
				//归属总项目
				$orderItemsAttributes['order_items_id'] = $orderItemsAttributes['id'];
				//删除不用的属性		
				foreach ($unsetItems as $unset)
					unset($orderItemsAttributes[$unset]);
				//归属举办人
				$orderItemsAttributes['user_id'] = $orderItemsAttributes['organizer_id'];
				//项目总价
				$orderItemsAttributes['total'] = $total;
				//设置项目属性
				$items->setAttributes($orderItemsAttributes,false);
				$items->OrderItems_OrderItemsFare = $OrderItems_OrderItemsFare;
				$OrderShops_OrderItems[] = $items;
			}
			$this->OrderShops_OrderItems = $OrderShops_OrderItems;
			//商品赋值
			$orderShopsAttributes = array();
			$orderShopsAttributes['user_id'] = $model->organizer_id;
			$orderShopsAttributes['shops_c_id'] = $model->Actives_Shops->c_id;
			$orderShopsAttributes['shops_c_name'] = $model->Actives_Shops->Shops_ShopsClassliy->name;
			$orderShopsAttributes['shops_agent_id'] = $model->Actives_Shops->agent_id;
			$orderShopsAttributes['shops_name'] = $model->Actives_Shops->name;
			$orderShopsAttributes['shops_list_img'] = $model->Actives_Shops->list_img;
			$orderShopsAttributes['shops_page_img'] = $model->Actives_Shops->page_img;
			$orderShopsAttributes['shops_list_info'] = $model->Actives_Shops->list_info;
			$orderShopsAttributes['shops_page_info'] = $model->Actives_Shops->page_info;		
			$orderShopsAttributes['shops_cost_info'] = $model->Actives_Shops->cost_info;
			$orderShopsAttributes['shops_book_info'] = $model->Actives_Shops->book_info;		
			$orderShopsAttributes['shops_pub_time'] = $model->Actives_Shops->pub_time;
			$orderShopsAttributes['shops_add_time'] = $model->Actives_Shops->add_time;
			$orderShopsAttributes['shops_up_time'] = $model->Actives_Shops->up_time;				
			$orderShopsAttributes['actives_type'] = $model->actives_type;
			$orderShopsAttributes['actives_organizer_id'] = $model->organizer_id;
			$orderShopsAttributes['actives_tour_type'] = $model->tour_type;
			$orderShopsAttributes['tour_price'] = $model->tour_price;
			$orderShopsAttributes['remark'] = $model->remark;
			$orderShopsAttributes['start_time'] = $model->start_time;
			$orderShopsAttributes['end_time'] = $model->end_time;
			$orderShopsAttributes['pub_time'] = $model->pub_time;
			$this->setAttributes($orderShopsAttributes,false);
		}
		else
			$this->addError($attribute, '觅趣（代付） 不能创建订单');
	}
}
