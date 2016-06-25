<?php

/**
 * This is the model class for table "{{dot}}".
 *
 * The followings are the available columns in table '{{dot}}':
 * @property string $id
 * @property string $c_id
 */
class Dot extends CActiveRecord
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
		return '{{dot}}';
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
			//线路(点)关联主表 一对一
			'Dot_Shops'=>array(self::HAS_ONE,'Shops','id'),
			//线路(点)关联类型表 归属（多对一）
			'Dot_ShopsClassliy'=>array(self::BELONGS_TO,'ShopsClassliy','c_id'),
			//线路(点)关联 选中项目表 (一对多)
			'Dot_Pro'=>array(self::HAS_MANY,'Pro','shops_id'),
			//线路(点)关联 选中标签
			'Dot_TagsElement'=>array(self::HAS_MANY,'TagsElement','element_id'),
			//线路(点)关联 外链
			'Dot_FarmOuter'=>array(self::HAS_MANY,'FarmOuter','id'),
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

			$criteria->compare('Dot_Shops.status','<>-1');
			$criteria->with=array(
				'Dot_ShopsClassliy',
				'Dot_Shops'=>array('with'=>array('Shops_Agent')),
			);

			if($this->search_time_type != '' && isset($this->__search_time_type[$this->search_time_type]))
			{
				if($this->search_start_time !='' && $this->search_end_time !='')
					$criteria->addBetweenCondition('Dot_Shops.'.$this->__search_time_type[$this->search_time_type],strtotime($this->search_start_time),strtotime($this->search_end_time)+3600*24-1);
				elseif($this->search_start_time !='' && $this->search_end_time =='')
					$criteria->compare('Dot_Shops.'.$this->__search_time_type[$this->search_time_type],'>='.strtotime($this->search_start_time));
				elseif($this->search_start_time =='' && $this->search_end_time !='')
					$criteria->compare('Dot_Shops.'.$this->__search_time_type[$this->search_time_type],'<='.strtotime($this->search_end_time)+3600*24-1);
			}
			$criteria->compare('t.id',$this->id,true);
			$criteria->compare('Dot_ShopsClassliy.name',$this->c_id,true);
				
			$criteria->compare('Dot_Shops.name',$this->Dot_Shops->name,true);
			$criteria->compare('Shops_Agent.phone',$this->Dot_Shops->agent_id,true);
			$criteria->compare('Dot_Shops.brow',$this->Dot_Shops->brow,true);
			$criteria->compare('Dot_Shops.share',$this->Dot_Shops->share,true);
			$criteria->compare('Dot_Shops.praise',$this->Dot_Shops->praise,true);
			$criteria->compare('Dot_Shops.audit',$this->Dot_Shops->audit,true);
			$criteria->compare('Dot_Shops.status',$this->Dot_Shops->status,true);
			$criteria->compare('Shops_Agent.phone',$this->Dot_Shops->agent_id,true);
			$criteria->compare('Dot_Shops.name',$this->Dot_Shops->name,true);
			$criteria->compare('Dot_Shops.list_info',$this->Dot_Shops->list_info,true);
			$criteria->compare('Dot_Shops.page_info',$this->Dot_Shops->page_info,true);
			$criteria->compare('Dot_Shops.brow',$this->Dot_Shops->brow,true);
			$criteria->compare('Dot_Shops.share',$this->Dot_Shops->share,true);
			$criteria->compare('Dot_Shops.praise',$this->Dot_Shops->praise,true);
			$criteria->compare('Dot_Shops.audit',$this->Dot_Shops->audit);
			$criteria->compare('Dot_Shops.is_sale',$this->Dot_Shops->is_sale);
			$criteria->compare('Dot_Shops.selected',$this->Dot_Shops->selected);
			$criteria->compare('Dot_Shops.tops',$this->Dot_Shops->tops);
			$criteria->compare('Dot_Shops.selected_tops',$this->Dot_Shops->selected_tops);
			if($this->Dot_Shops->pub_time != '')
				$criteria->addBetweenCondition('Dot_Shops.pub_time',strtotime($this->Dot_Shops->pub_time),(strtotime($this->Dot_Shops->pub_time)+3600*24-1));	
			if($this->Dot_Shops->add_time != '')
				$criteria->addBetweenCondition('Dot_Shops.add_time',strtotime($this->Dot_Shops->add_time),(strtotime($this->Dot_Shops->add_time)+3600*24-1));
			if($this->Dot_Shops->up_time != '')
				$criteria->addBetweenCondition('Dot_Shops.up_time',strtotime($this->Dot_Shops->up_time),(strtotime($this->Dot_Shops->up_time)+3600*24-1));			
		}
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
					'pageSize'=>Yii::app()->params['admin_pageSize'],
			),
			'sort'=>array(
					'defaultOrder'=>'Dot_Shops.add_time desc', //设置默认排序
					'attributes'=>array(
							'id',
							'Dot_Shops.name'=>array(
									'desc'=>'Dot_Shops.name desc',
							),
							'Dot_Shops.agent_id'=>array(
									'desc'=>'Shops_Agent.phone desc',
							),
							'Dot_Shops.brow'=>array(
									'desc'=>'Dot_Shops.brow desc',
							),
							'Dot_Shops.share'=>array(
									'desc'=>'Dot_Shops.share desc',
							),
							'Dot_Shops.praise'=>array(
									'desc'=>'Dot_Shops.praise desc',
							),
							'Dot_Shops.pub_time'=>array(
									'desc'=>'Dot_Shops.pub_time desc',
							),
							'Dot_Shops.add_time'=>array(
									'desc'=>'Dot_Shops.add_time desc',
							),
							'Dot_Shops.up_time'=>array(
									'desc'=>'Dot_Shops.up_time desc',
							),
							'Dot_Shops.audit'=>array(
									'desc'=>'Dot_Shops.audit desc',
							),
							'Dot_Shops.status'=>array(
									'desc'=>'Dot_Shops.status desc',
							),	
					)
			),
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Dot the static model class
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
	 * 显示审核状态
	 * @param int $audit  审核状态
	 * @param int $status
	 */
	public static function  agent_show_status($audit,$status,$id)
	{
		$arr_audit = '';
		$arr_status= '';
		if($audit==Items::audit_draft && $status==Items::status_offline ) {
			$arr_audit  = '<div class="pull-right mark"><span>未提交</span></div>';
			$arr_status = '<div class="row-fluid btn_group"><a href="'.Yii::app()->createUrl("/agent/agent_dot/delete",array("id"=>$id)).'" class="delete">删除</a> <a href="'.Yii::app()->createUrl("/agent/agent_dot/update",array("id"=>$id)).'" class="reedit">重新编辑</a></div>';
		}elseif($audit==Items::audit_nopass  && $status==Items::status_offline ){
			$arr_audit  = '<div class="pull-right mark"><span>审核未通过</span></div>';
			$arr_status = '<div class="row-fluid btn_group"><a href="'.Yii::app()->createUrl("/agent/agent_dot/delete",array("id"=>$id)).'" class="delete">删除</a><a href="'.Yii::app()->createUrl("/agent/agent_dot/update",array("id"=>$id)).'" class="reedit">重新编辑</a></div>';
		}elseif($audit==Items::audit_pending && $status==Items::status_offline ){
			$arr_audit = '<div class="pull-right mark"><span>未审核</span></div>';
			$arr_status = '<div class="row-fluid btn_group"><a href="'.Yii::app()->createUrl("/agent/agent_dot/delete",array("id"=>$id)).'" class="delete">删除</a></div>';
		}elseif($audit==Items::audit_pass && $status == Items::status_offline ){
			$arr_audit = '<div class="pull-right mark"><span>未上线</span></div>';
			$arr_status = '<div class="row-fluid btn_group"> <a href="'.Yii::app()->createUrl("/agent/agent_dot/delete",array("id"=>$id)).'" class="delete">删除</a><a href="'.Yii::app()->createUrl("/agent/agent_dot/update",array("id"=>$id)).'" class="reedit">重新编辑</a><a href="'.Yii::app()->createUrl("/agent/agent_dot/start",array("id"=>$id)).'" class="upline">上线</a> </div>';
		}else{
			$arr_audit = '<div class="pull-right mark"><span>已上线</span></div>';
			$arr_status = '<div class="row-fluid btn_group"><a href="'.Yii::app()->createUrl("/agent/agent_dot/disable",array("id"=>$id)).'" class="downline">下线</a></div>';
		}

		$arr = array('arr_audit'=>$arr_audit,'arr_status'=>$arr_status);

		return $arr;
	}
	
	/**
	 * 获取下单数据
	 */
	public static function get_fare($model)
	{
		$criteria = new CDbCriteria;
		$criteria->with=array(
				'Shops_ShopsClassliy',
				'Shops_Pro'=>array(
						'with'=>array(
								'Pro_Items'=>array(
										'with'=>array(
												'Items_ItemsClassliy',
												'Items_Fare',
										),
								),
						)
				)
		);
		$criteria->addColumnCondition(array(
				't.status'=>Shops::status_online,//上线
				't.audit'=>Shops::audit_pass,//审核通过
				't.is_sale'=>Shops::is_sale_yes,//可卖
				't.c_id'=>$model->Shops_ShopsClassliy->id,
		));
		$criteria->order='Shops_Pro.sort';
		return self::data_json(Shops::data($model->id,$criteria));
	}
	
	/**
	 * 数据 转化成 json
	 */
	public static function data_json($model)
	{
		if(! $model)
			return array();
		$return = array();
		if(!isset($model->Shops_Pro) || empty($model->Shops_Pro) || !is_array($model->Shops_Pro))
			return array();	
		if(!isset($model->Shops_ShopsClassliy) || empty($model->Shops_ShopsClassliy))
			return array();
		// 点名称
		$return['name'] = $model->name;
		// 点id
		$return['value'] = $model->id;
		// 商品类型（点）
		$return['classliy'] = array(
				'name' => $model->Shops_ShopsClassliy->name,
				'value' => $model->Shops_ShopsClassliy->id,
		);
		$key_number=0;
		foreach($model->Shops_Pro as $key=>$pro)
		{
			if(!isset($pro->Pro_Items) || empty($pro->Pro_Items))
				return array();
			if(!isset($pro->Pro_Items->Items_ItemsClassliy) || empty($pro->Pro_Items->Items_ItemsClassliy))
				return array();	
			if ($pro->Pro_Items->audit == Items::audit_pass && $pro->Pro_Items->status == Items::status_online)
			{
				// 项目名称
				$return['items_fare'][$key_number]['name'] = $pro->Pro_Items->name;
				// 项目id
				$return['items_fare'][$key_number]['value'] = $pro->Pro_Items->id;
				// 免费项目
				$return['items_fare'][$key_number]['free_status'] = array(
					'name'=>Items::$_free_status[$pro->Pro_Items->free_status],
					'value'=>$pro->Pro_Items->free_status,
				);
				// 项目类型
				$return['items_fare'][$key_number]['classliy'] = array(
						'name' => $pro->Pro_Items->Items_ItemsClassliy->name,
						'value' => $pro->Pro_Items->Items_ItemsClassliy->id,
				);
				if(!isset($pro->Pro_Items->Items_Fare) || empty($pro->Pro_Items->Items_Fare) || !is_array($pro->Pro_Items->Items_Fare))
					return array();	
				foreach ($pro->Pro_Items->Items_Fare as $key_items=>$fare)
				{
					if ($pro->Pro_Items->Items_ItemsClassliy->append == 'Hotel') //住需要入住几晚
					{
						$fare_array= array(
							'price' => $fare->price,
							'number' => 0,
							'count' => 0,
							'start_date'=>'0',
							'end_date'=>'0',
							'hotel_number'=>1						//默认一晚
						);
						// 价格信息
						$return['items_fare'][$key_number]['fare'][] = array(
								'value'=> $fare->id,
								'name' => $fare->name,
								'info' => $fare->info,
								'room_number'=>$fare->number,
								'number' => 0,
								'price' => $fare->price,
								'start_date'=>'0',
								'end_date'=>'0',
								'hotel_number'=>1						//默认一晚
						);
					}
					else
					{
						$fare_array= array(
								'price' => $fare->price,
								'number' => 0,
								'count' => 0
						);
						// 价格信息
						$return['items_fare'][$key_number]['fare'][] = array(
								'value'=> $fare->id,
								'name' => $fare->name,
								'info' => $fare->info,
								'room_number'=>$fare->number,
								'number' => 0,
								'price' => $fare->price,
						);
					}				
					// 拼接的约定的规范格式
					$return['OrderItems'][$pro->day_sort][$pro->half_sort][$model->id][$key_number][$pro->Pro_Items->id][] = array(
							$fare->id =>$fare_array
					);
				}
				$key_number++;
			}
		}
		
		if(isset($return['items_fare']) && count($return['items_fare'])>0)
			return $return;		
		return array();
	}
	
	/**
	 * 验证点
	 * @param unknown $model
	 * @return boolean
	 */
	public static function validate_dot($model)
	{
		if(isset($_POST['OrderItems'][0]) && is_array($_POST['OrderItems'][0]))
		{
			$day=0;//天的排序
			foreach ($_POST['OrderItems'] as $day_key=>$dot_key_dot_id)//
			{
				if($day_key==0)
				{
					if(empty($dot_key_dot_id) || !is_array($dot_key_dot_id))
					{
						$model->addError('order_price','订单中商品 不是有效值');
						$model->addError('status','D02');
						return false;
					}
					$shops_number=0;//商品的排序
					$OrderShops=array();//商品的数据模型
					foreach ($dot_key_dot_id as $dot_key=>$dot_id_items_key)//
					{
						if($shops_number != $dot_key)
						{
							$model->addError('order_price','订单中商品 不是有效值');
							$model->addError('status','D03');
							return false;
						}
						if(empty($dot_id_items_key) || ! is_array($dot_id_items_key))
						{
							$model->addError('order_price','订单中商品 不是有效值');
							$model->addError('status','D04');
							return false;
						}
						//点 的复制
						foreach ($dot_id_items_key as $dot_id=>$items_key_items_id)//
						{
							if(empty($items_key_items_id) || ! is_array($items_key_items_id))
							{
								$model->addError('order_price','订单中商品 不是有效值');
								$model->addError('status','D05');
								return false;
							}
							$shops_data=array();
							$shops_data=Dot::get_dot($dot_id);//请求点数据
								
							if(empty($shops_data)  || !isset($shops_data['shops']))
							{
								$model->addError('order_price','订单中商品 不是有效值');
								$model->addError('status','D06');
								return false;
							}
							//复制商品表
							$shops_model=$shops_data['shops'];
							//复制
							$order_shops_model=Shops::set_order_shops($shops_model);
								
							$items_number=0;//项目的排序
							//遍历点中项目
							$OrderItems=array();
							foreach ($items_key_items_id as $items_key=>$items_id_fare_key)//
							{
								if($items_number != $items_key)
								{
									$model->addError('order_price','订单中商品 不是有效值');
									$model->addError('status','D07');
									return false;
								}
								if(empty($items_id_fare_key) || ! is_array($items_id_fare_key))
								{
									$model->addError('order_price','订单中商品 不是有效值');
									$model->addError('status','D08');
									return false;
								}
								$order_shops_items='';
								foreach ($items_id_fare_key as $items_id=>$fare_key_fare_id)//
								{
									if(empty($fare_key_fare_id) || ! is_array($fare_key_fare_id))
									{
										$model->addError('order_price','订单中商品 不是有效值');
										$model->addError('status','D09');
										return false;
									}
									if(! isset($shops_data['items'][$items_id]['data']))
									{
										$model->addError('order_price','订单中商品 不是有效值');
										$model->addError('status','D10');
										return false;
									}
									if(! $shops_data['items'][$items_id]['is_validate'])
									{
										$model->addError('order_price','订单中商品 不是有效值');
										$model->addError('status','D11');
										return false;
									}
									$pro_items_model=$shops_data['items'][$items_id]['data'];//项目的对象
										
									$order_shops_items=Items::set_order_items($pro_items_model,array('shops_model'=>$shops_model));
										
									$fare_number=0;//价格的排序
									//遍历项目中的价格
									$OrderItemsFare=array();
									foreach ($fare_key_fare_id as $fare_key=>$fare_id_price_number)
									{
										if($fare_number != $fare_key)
										{
											$model->addError('order_price','订单中商品 不是有效值');
											$model->addError('status','D12');
											return false;
										}
										if(empty($fare_id_price_number) || ! is_array($fare_id_price_number))
										{
											$model->addError('order_price','订单中商品 不是有效值');
											$model->addError('status','D13');
											return false;
										}
										$order_items_fare='';
										foreach ($fare_id_price_number as $fare_id=>$price_number)
										{
											if(!isset($price_number['price']) || !isset($price_number['number'])  || !isset($price_number['count']))
											{
												$model->addError('order_price','订单中商品 不是有效值');
												$model->addError('status','D14');
												return false;
											}
											if(! isset($shops_data['fares'][$items_id][$fare_id]['data']))
											{
												$model->addError('order_price','订单中商品 不是有效值');
												$model->addError('status','D15');
												return false;
											}
											if(! $shops_data['fares'][$items_id][$fare_id]['is_validate'])
											{
												$model->addError('order_price','订单中商品 不是有效值');
												$model->addError('status','D16');
												return false;
											}
											$fare_model=$shops_data['fares'][$items_id][$fare_id]['data'];//项目价格的对象
											
											if(!is_numeric($price_number['number']) || $price_number['number'] <= 0 || $price_number['price'] !== $fare_model->price)
											{
												$model->addError('order_price','订单中商品 不是有效值');
												$model->addError('status','D17');
												return false;
											}
											//新增功能
											if(isset($price_number['start_date'],$price_number['end_date'],$price_number['hotel_number']) && $price_number['hotel_number'] != 1 && $price_number['start_date'] !=0 && $price_number['end_date'] !=0 )
											{
 												if(strtotime($model->go_time) > strtotime($price_number['start_date']))
 												{
													$model->addError('order_price', '订单中商品 不是有效值');
													$model->addError('status','D18');
													return false;
												}
												if((strtotime($price_number['end_date'])-strtotime($model->go_time)) > 240*3600)
												{
													$model->addError('order_price', '订单中商品 不是有效值');
													$model->addError('status','D18');
													return false;
												}
												$fare_validate_dot = new OrderItemsFare;
												$fare_validate_dot->scenario='validate_dot';
												$fare_validate_dot->price=$price_number['price'];
												$fare_validate_dot->number=$price_number['number'];
												$fare_validate_dot->total=$price_number['count'];
												$fare_validate_dot->fare_price=$fare_model->price;
												$fare_validate_dot->start_date=$price_number['start_date'];
												$fare_validate_dot->end_date=$price_number['end_date'];
												$fare_validate_dot->hotel_number=$price_number['hotel_number'];
												if(! $fare_validate_dot->validate())
												{
													$error=$fare_validate_dot->getErrors();
													if(empty($error))
														$model->addError('order_price','订单中商品 不是有效值');
													else 
														$model->addError('order_price', reset(reset($error)));
													$model->addError('status','D18');
													return false;
												}									
											}
											else
											{						
												if(bccomp($price_number['number'] * $price_number['price'],$price_number['count'],2) != 0)
												{
													$model->addError('order_price','订单中商品 不是有效值');
													$model->addError('status','D18');
													return false;
												}
											}
											$order_items_fare=Fare::set_order_items_fare($fare_model,array(
													'pro_items_model'=>$pro_items_model,
													'price_number'=>$price_number,
													'shops_model'=>$shops_model,
											));						
											Order::$order_items_money += $price_number['count']; //记录项目的总价
											$shops_data['fares'][$items_id][$fare_id]['is_validate']=false;//避免重复
											continue;
										}
										if($order_items_fare == '')
										{
											$model->addError('order_price','订单中商品 不是有效值');
											$model->addError('status','D18');
											return false;
										}
										$OrderItemsFare[]=$order_items_fare;
										$fare_number++;//项目的价格排序
									}
									$shops_data['items'][$items_id]['is_validate']=false;//避免重复
									if(! isset($order_shops_items) || empty($order_shops_items))
									{
										$model->addError('order_price','订单中商品 不是有效值');
										$model->addError('status','D10');
										return false;
									}
									if(! isset($OrderItemsFare) || empty($OrderItemsFare))
									{
										$model->addError('order_price','订单中商品 不是有效值');
										$model->addError('status','D15');
										return false;
									}
									$order_shops_items->total=Order::$order_items_money;//项目的价格赋值
									Order::$order_items_money=0.00; //清除项目统计价格
									continue;
								}
								$order_shops_items->OrderItems_OrderItemsFare=$OrderItemsFare;
								$OrderItems[]=$order_shops_items;
								$items_number++;//项目的排序
							}
							if(!isset($OrderItems) || empty($OrderItems))
							{
								$model->addError('order_price','订单中商品 不是有效值');
								$model->addError('status','D10');
								return false;
							}
							$order_shops_model->OrderShops_OrderItems=$OrderItems;
							continue;
						}
						$OrderShops[]=$order_shops_model;
						$shops_number++;//商品的排序
					}
				}
				else
				{
					$model->addError('order_price','订单中商品 不是有效值');
					$model->addError('status','D01');
					return false;
				}
			}
			if(!isset($OrderShops) || empty($OrderShops))
			{
				$model->addError('order_price','订单中商品 不是有效值');
				$model->addError('status','D03');
				return false;
			}
			$model->Order_OrderShops=$OrderShops;
			return true;
		}
		else
		{
			$model->addError('order_price','订单中商品 不是有效值');
			$model->addError('status','D01');
			return false;
		}
	}
	
	/**
	 * 获取点的信息 订单
	 * @param unknown $shops_id
	 * @return multitype:|multitype:NULL Ambigous <static, mixed, NULL, multitype:static , multitype:unknown Ambigous <static, NULL> , multitype:, multitype:unknown >
	 */
	public static function get_dot($shops_id)
	{
		Yii::app()->controller->_class_model='Dot';
		$shops_classliy=ShopsClassliy::getClass();
		
		if(isset(Order::$_shops_data[$shops_id]))
			return Order::$_shops_data[$shops_id];
		$return=array();
		
		$model=Shops::model()->findByPk($shops_id,array(
				'with'=>array(
						'Shops_Agent',
						'Shops_Dot',
						'Shops_Pro'=>array(
								'with'=>array(
										'Pro_Items'=>array(
												'with'=>array(
														'Items_ItemsClassliy',
														'Items_area_id_p_Area_id',
														'Items_area_id_m_Area_id',
														'Items_area_id_c_Area_id',
														'Items_ItemsImg'=>array('order'=>'rand()'),
														'Items_StoreContent'=>array('with'=>array('Content_Store')),
														'Items_Store_Manager',
														'Items_Fare',
												),
										),
								),
						),
				),
				'condition'=>'t.c_id=:c_id AND t.status=:status AND t.audit=:audit AND is_sale=:is_sale',
				'params'=>array(':is_sale'=>1,':c_id'=>$shops_classliy->id,':audit'=>Shops::audit_pass,':status'=>Shops::status_online),
				'order'=>'Shops_Pro.sort',
		));
		if(! $model)
			return $return;
		if(!isset($model->Shops_Dot) || empty($model->Shops_Dot))
			return $return;
		if(!isset($model->Shops_Agent) || empty($model->Shops_Agent))
			return $return;
		if(!isset($model->Shops_Pro) || empty($model->Shops_Pro) || !is_array($model->Shops_Pro))
			return $return;
		foreach ($model->Shops_Pro as $pro)
		{
			if($pro->Pro_Items->status==Items::status_online && $pro->Pro_Items->audit==Items::audit_pass)
			{
				if( !isset($pro->Pro_Items) || empty($pro->Pro_Items) || !isset($pro->Pro_Items->Items_Fare) || empty($pro->Pro_Items->Items_Fare) || !is_array($pro->Pro_Items->Items_Fare))
					return $return;
				if( !isset($pro->Pro_Items->Items_StoreContent) || empty($pro->Pro_Items->Items_StoreContent) || !isset($pro->Pro_Items->Items_StoreContent->Content_Store) || empty($pro->Pro_Items->Items_StoreContent->Content_Store))
					return $return;
				if( !isset($pro->Pro_Items->Items_Store_Manager) || empty($pro->Pro_Items->Items_Store_Manager))
					return $return;
				if( !isset($pro->Pro_Items->Items_ItemsClassliy) || empty($pro->Pro_Items->Items_ItemsClassliy))
					return $return;
				$return['items'][$pro->Pro_Items->id]['data']=$pro;
				$return['items'][$pro->Pro_Items->id]['is_validate']=true;
				foreach ($pro->Pro_Items->Items_Fare as $items_fare)
				{
					if(! empty($items_fare))
						$return['fares'][$pro->Pro_Items->id][$items_fare->id]=array('data'=>$items_fare,'is_validate'=>true);
				}
			}
		}
		if(empty($return))
			return array();
		$model->Shops_ShopsClassliy=$shops_classliy;
		$return['agent']=$model->Shops_Agent;
		$return['shops']=$model;
		Order::$_shops_data[$shops_id]=$return;
		return $return;
	}

	/**
	 * 计算点的成人 总价
	 * @param $id
	 * @return int
	 * 2015-12-09
	 * 1 成年最低 （住）
	 * 2 儿童最低
	 */
	public static function shops_price_num($id)
	{	
		$criteria = new CDbCriteria;
		$criteria->select='`t`.`id`';
		$criteria->with=array(
				'Pro_Items'=>array(
						'select'=>'id',
						'with'=>array(
								'Items_Fare'=>array('select'=>'price')
						),
				),
		);
		$criteria->addColumnCondition(array(
				'`t`.`shops_id`'=>$id,
				'`Pro_Items`.`status`'=>Items::status_online,
				'`Pro_Items`.`audit`'=>Items::audit_pass,
		));
		$criteria->addColumnCondition(array(
				'`Items_Fare`.`c_id`'=>Items::items_hotel, 						//住
				'`Items_Fare`.`info`'=>Fare::$__info[Fare::info_adult] 		//成人
		),'OR');
		$criteria->order='`Items_Fare`.`price`';
		
		$models=Pro::model()->findAll($criteria);
		if(!empty($models))
		{
			foreach ($models as $model)
			{
				foreach ($model->Pro_Items->Items_Fare as $fare)
					return $fare->price;
			}
		}
		else
		{
			$criteria = new CDbCriteria;
			$criteria->select='`t`.`id`';
			$criteria->with=array(
					'Pro_Items'=>array(
							'select'=>'id',
							'with'=>array(
									'Items_Fare'=>array('select'=>'price')
							),
					),
			);
			$criteria->addColumnCondition(array(
				'`t`.`shops_id`'=>$id,
				'`Pro_Items`.`status`'=>Items::status_online,
				'`Pro_Items`.`audit`'=>Items::audit_pass,
			));
			$criteria->addColumnCondition(array(
				'`Items_Fare`.`info`'=>Fare::$__info[Fare::info_children] 		//儿童
			));
			$criteria->order='`Items_Fare`.`price`';			
			$models=Pro::model()->findAll($criteria);
			if(!empty($models))
			{
				foreach ($models as $model)
				{
					foreach ($model->Pro_Items->Items_Fare as $fare)
						return $fare->price;
				}
			}
		}
		return 0.00;
	}

	/**
	 * 获取下单量
	 */
	public static function get_down($id)
	{
		$criteria =new CDbCriteria;
		$criteria->select='SUM(`Pro_Items`.`down`) AS select_items';
		$criteria->with=array(
				'Dot_Pro'=>array(
						'select'=>'`id`',
						'with'=>array(
								'Pro_Items'=>array(
										'select'=>'`id`'
								)
						)
				),
		);
		$model=self::model()->findByPk($id,$criteria);
		if($model)
			return $model->select_items;
		return 0;
	}
}
