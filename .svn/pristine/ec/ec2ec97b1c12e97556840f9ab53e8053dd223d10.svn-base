<?php
/**
 * 
 * @author Changhai Zhan
 *	创建时间：2015-09-26 19:15:07 */
class Store_orderItemsController extends StoreMainController
{
	/**
	 * 消费失败，此码不在消费时间段
	 * @var Int
	 */
	const status_store_timeout=-4;
	/**
	 * 消费失败，此码不是您的
	 * @var Int
	 */
	const status_store_not=-3;
	/**
	 * 消费失败，非法二维码
	 * @var Int
	 */
	const status_barcode_invalid=-2;
	/**
	 * 消费失败，此码已消费
	 * @var Int
	 */
	const status_barcode_scan=-1;
	/**
	 * 消费失败
	 * @var Int
	 */
	const status_not=0;
	/**
	 * 消费成功
	 * @var Int
	 */
	const status_yes=1;
	
	/**
	 * 解释消费码返回状态
	 * @var array
	 */
	public static $_status=array(
		-4=>'消费失败，此码不在消费时间段',
		-3=>'消费失败，此码不是您的。',
		-2=>'消费失败，非法二维码。',
		-1=>'消费失败，此码已消费。',
		0=>'消费失败。',
		1=>'消费成功。',
	);
		
	/**
	 * 默认操作数据模型
	 * @var string
	 */
	public $_class_model='OrderItems';

	/**
	 * 扫码处理
	 */
	public function actionScancode()
	{
		$this->_class_model = 'StoreUser';
		$store_model = $this->loadModel(Yii::app()->store->id,'status=1');

		if(isset($_POST['code']) && $_POST['code'])
		{
			$code = $_POST['code'];																		//获取二维码
			//判断主账号 id
			$store_id = ($store_model->p_id==0) ? Yii::app()->store->id : $store_model->p_id;

			$criteria=new CDbCriteria;
			$criteria->with=array(
					'OrderItems_StoreUser',
					'OrderItems_Order',
					'OrderItems_OrderItemsFare',
					'OrderItems_OrderActives'=>array(
						'with'=>array(
							'OrderActives_Order',
							'OrderActives_Actives',
						),
					),
			);
			//活动扫描
			$condition_o=$condition_u=array();
			//组织者的码
			$o=array(
				'`OrderActives_Order`.`status`'=>Order::status_yes,									//订单是有效的
				'`OrderActives_Order`.`pay_status`'=>Order::pay_status_yes,					//已经支付了
				'`OrderActives_Order`.`order_status`'=>Order::order_status_user_pay,	//用户订单状态 已经支付了
				'`OrderActives_Order`.`status_go`'=>Order::status_go_yes,						//用户订单状态 确认出游				
				//实际支付人数大于0
				'`OrderItems_OrderActives`.`user_pay_count` > 0',
				'(`OrderActives_Actives`.`actives_status`=:start OR `OrderActives_Actives`.`actives_status`=:end)',
				//出游时间 小于 当前时间
				'`OrderActives_Actives`.`go_time`<=:time',
			);
			$criteria->params[':start']=Actives::actives_status_start;								//开始
			$criteria->params[':end']=Actives::actives_status_end;									//结束
			$criteria->params[':time']=time();																	//时间
			//用户的码
			$u=array(
				'`OrderItems_Order`.`status`'=>Order::status_yes,									//订单是有效的
				'`OrderItems_Order`.`pay_status`'=>Order::pay_status_yes,						//已经支付了
				'`OrderItems_Order`.`order_status`'=>Order::order_status_user_pay,		//用户订单状态 已经支付了
				'`OrderItems_Order`.`status_go`'=>Order::status_go_yes,							//用户订单状态 确认出游
					//出游时间 小于 当前时间
				'`OrderItems_Order`.`go_time`<=:time',
			);
			$i=0;
			foreach ($o as $o_k=>$o_v)
			{
				if(is_numeric($o_k))
					$condition_o[]=$o_v;			
				else
				{
					$condition_o[]=$o_k.'=:o'.$i;
					$criteria->params[':o'.$i++]=$o_v;
				}
			}
			$j=0;
			foreach ($u as $u_k=>$u_v)
			{
				if(is_numeric($u_k))
					$condition_u[]=$u_v;
				else
				{
					$condition_u[]=$u_k.'=:u'.$j;
					$criteria->params[':u'.$j++]=$u_v;
				}
			}

			$criteria->addCondition('('.implode(' AND ',$condition_o).') OR ('.implode(' AND ',$condition_u).')');
			$criteria->addColumnCondition(array(
					'`t`.`barcode`'=>$code,															//获取码的值
			));
			$criteria->order='`t`.`is_barcode` desc';											//防止很久之前的 已消费 重复消费码
			$order_items = OrderItems::model()->find($criteria);

			$return=array();
			$time  = time();
			$start_date = '';//开始时间
			$end_date = '';//结束时间
			$items_hotel_type = true;
			if(! $order_items)
			{		
				$return['status']=array(
					'name'=>self::$_status[self::status_barcode_invalid],
					'value'=>self::status_barcode_invalid
				);
				$this->send($return);
			}
			else
			{			
				if($order_items->is_barcode == OrderItems::is_barcode_yes)
				{
					$return['status']=array(
							'name'=>self::$_status[self::status_barcode_scan],
							'value'=>self::status_barcode_scan
					);
					$this->send($return);
				}
				elseif($order_items->is_barcode != OrderItems::is_barcode_valid)
				{
					$return['status']=array(
							'name'=>self::$_status[self::status_barcode_invalid],
							'value'=>self::status_barcode_invalid
					);
					$this->send($return);
				}
				elseif($order_items->store_id != $store_id )  //商家配对
				{
					$return['status']=array(
							'name'=>self::$_status[self::status_store_not],
							'value'=>self::status_store_not
					);
					$this->send($return);
				}																	//归属商家  权限判断 子账号 只能消费管理的项目
				elseif($store_model->p_id != 0 && $order_items->manager_id != Yii::app()->store->id)
				{
					$return['status']=array(
							'name'=>self::$_status[self::status_store_not],
							'value'=>self::status_store_not
					);
					$this->send($return);
				}
				elseif($order_items->items_c_id==Items::items_hotel)
				{
					//当前项目是住（有入住限制）====开始时间
					$start_arr = $end_arr = array();
					//设置的时间集合
					foreach($order_items->OrderItems_OrderItemsFare as $items)
					{
						if($items->start_date != '0' )
							$start_arr[] = strtotime($items->start_date);
						if($items->end_date != '0')
							$end_date[]   = strtotime($items->end_date);
					}
					//开始结束 数组 都 为 真
					if($start_arr && $end_date) 
					{
						//倒序  取最大
						sort($start_arr);
						rsort($end_date);
						$start_date = $start_arr[0];
						$end_date   = $end_date[0];
						//开始时间 > 当前时间    结束时间 < 当前时间
						if ($start_date > $time ) 
						{
							//扫描时间 小于 入住开始时间   或  大于 入住结束时间（一天缓冲期）
							$return['status']=array(
								'name'=>self::$_status[self::status_not],
								'value'=>self::status_not,
							);
							$this->send($return);
						}
					}
				}
			}
			//组织者的扫描 (活动消费)
			if($order_items && isset($order_items->OrderItems_OrderActives->OrderActives_Actives) && $order_items->OrderItems_OrderActives->OrderActives_Actives->actives_type==Actives::actives_type_tour)
				$this->tour($order_items,$code,$store_id,$store_model);
			else
			{
				$transaction=$order_items->dbConnection->beginTransaction();
				try
				{
					$criteria_transaction=new CDbCriteria;
					$criteria_transaction->with=array(
							'OrderItems_StoreUser',
							'OrderItems_Order',
							'OrderItems_OrderItemsFare',
					);
					$criteria_transaction->addColumnCondition(array(
							'`t`.`store_id`'=>$store_id,
							'`t`.`barcode`'=>$code,
							'`t`.`is_barcode`'=>OrderItems::is_barcode_valid,									//码有效
							'`OrderItems_Order`.`status`'=>Order::status_yes,								//订单是有效的
							'`OrderItems_Order`.`pay_status`'=>Order::pay_status_yes,					//已经支付了
							'`OrderItems_Order`.`status_go`'=>Order::status_go_yes,						//用户订单状态 确认出游
							'`OrderItems_Order`.`order_status`'=>Order::order_status_user_pay,	//用户订单状态 已经支付了
					));
					if($store_model->p_id != 0)     																		//权限限制
						$criteria_transaction->addColumnCondition(array(
							't.manager_id'=>Yii::app()->store->id,
						));
					$model = OrderItems::model()->find($criteria_transaction);
					//查不到 ====当前码不能消费
					if(! $model)
						throw new Exception("非法消费码或消费码错误");
					//消费总数====可消费数model
					$barcode_valid_count = $this->count_barcode_valid($model->order_id,$model->user_id);
					if($barcode_valid_count == 0)
						throw new Exception("可消费码总数不可用 为零");

					//当前项目是住（有入住限制）====结束时间
					if ($end_date && (($end_date+3600*24-1) < $time)) {
						//扫描时间 小于 入住开始时间   或  大于 入住结束时间（一天缓冲期）
						$items_hotel_type = false;
					}

					//更新当前表
					$model->is_barcode = OrderItems::is_barcode_yes;
					$model->scan_barcode = $code;
					$model->employ_time = time();
					if(! $model->save(false))
						throw new Exception("更新消费码 失败");
					//更新订单表
					if($barcode_valid_count == 1)
					{				
						$order_update_return = Order::model()->findByPk($model->order_id,'`order_status`=:order_status',array('order_status'=>Order::order_status_user_pay))->updateByPk($model->order_id,array('up_time'=>time(),'order_status'=>Order::order_status_user_use));
						if(! $order_update_return)
							throw new Exception("更新订单状态（已消费） 失败");
					}	
					if(! isset($model->OrderItems_OrderItemsFare) || empty($model->OrderItems_OrderItemsFare))
						throw new Exception("扫描消费项目的价格记录不存在");
					//添加资金交易记录 和钱包操作
					if(! OrderItems::scancodeDotThrand($model,true))
						throw new Exception("扫描消费项目 钱包操作错误".json_encode(array(Account::$create_error,AccountLog::$create_error)));
						
					$scan_barcode=$this->log('商家扫描消费码订单号 消费码 :'.$model->scan_barcode,ManageLog::store,ManageLog::update);
					$transaction->commit();
				}
				catch(Exception $e)
				{
					$transaction->rollBack();
					$this->error_log($e->getMessage(),ErrorLog::store,ErrorLog::create,ErrorLog::rollback,__METHOD__);
				}
				if(isset($scan_barcode) && $items_hotel_type)
				{
					//成功
					$return['status']=array(
							'name'=>self::$_status[self::status_yes],
							'value'=>self::status_yes,
							'classliy'=>array(
									'name'=>CHtml::encode(Order::$_order_type[$model->OrderItems_Order->order_type]),
									'value'=>$model->OrderItems_Order->order_type,
							),
							'order_type'=>array(
									'name'=>CHtml::encode(Order::$_order_type[$model->OrderItems_Order->order_type]),
									'value'=>$model->OrderItems_Order->order_type,
							),
							'link'=>Yii::app()->params['app_api_domain'].Yii::app()->createUrl('/store/store_order/view',array('id'=>$model->OrderItems_Order->id)),
							'link_value'=>$model->OrderItems_Order->id,
					);
					$this->send($return);
				}
				else
				{
					$return['status']=array(
							'name'=>self::$_status[self::status_not],
							'value'=>self::status_not,
					);
					$this->send($return);
				}
			}
		}
		$this->send_csrf();
	}

	/**
	 * @param $order_id
	 * @param $user_id
	 * @return int
	 */
	public function count_criteria($order_id,$user_id)
	{
		//查询总数
		$criteria=new CDbCriteria;
		$criteria->with=array(
			'Order_OrderItems',
		);
		$criteria->addColumnCondition(array(
			'`t`.`id`'=>$order_id,
			'`t`.`user_id`'=>$user_id,
			'`t`.`status`'=>Order::status_yes,
			'`t`.`pay_status`'=>Order::pay_status_yes,
			'`t`.`order_status`'=>Order::order_status_user_pay,
			'`Order_OrderItems`.`status`'=>1
		));
		$models = Order::model()->find($criteria);
		return $models ? count($models->Order_OrderItems) : 0;
	}

	/**
	 * 统计剩下的消费码的数量
	 * @param $order_id
	 * @param $user_id
	 * @return static
	 */
	public function count_barcode_valid($order_id,$user_id,$organizer=0)
	{
		if($organizer == 0)
		{
			//查询总数
			$criteria=new CDbCriteria;
			$criteria->with=array(
				'OrderItems_Order',
			);
			$criteria->addColumnCondition(array(
				'OrderItems_Order.id'=>$order_id,													//订单号
				'OrderItems_Order.user_id'=>$user_id,												//归属用户id
				'OrderItems_Order.status'=>Order::status_yes,								//订单状态
				'OrderItems_Order.pay_status'=>Order::pay_status_yes,					//已支付
				'OrderItems_Order.status'=>Order::status_yes,								//有效订单
				'OrderItems_Order.order_status'=>Order::order_status_user_pay,	// 订单状态 已付款
				't.is_barcode'=>OrderItems::is_barcode_valid,									//有效的消费码
			));
			return OrderItems::model()->count($criteria);
		}
		else if ($user_id==0)
		{
			//查询总数
			$criteria=new CDbCriteria;
			$criteria->with=array(
					'OrderItems_OrderActives'=>array(
						'with'=>array(
							'OrderActives_Order',
							'OrderActives_Actives'
						)),
			);
			$criteria->addCondition('`OrderActives_Actives`.`go_time`<=:time');
			$criteria->addCondition('`OrderItems_OrderActives`.`user_pay_count` > 0');
			$criteria->addCondition('(`OrderActives_Actives`.`actives_status`=:status_start OR `OrderActives_Actives`.`actives_status`=:status_end)');
			$criteria->params[':status_start']=Actives::actives_status_start;//开始
			$criteria->params[':status_end']=Actives::actives_status_end;//结束
			$criteria->params[':time']=time();//时间	
			$criteria->addColumnCondition(array(
				'`OrderItems_OrderActives`.id'=>$order_id,											//活动单号ID
				'`OrderItems_OrderActives`.`organizer_id`'=>$organizer,						//归属组织者id
				'`t`.`is_barcode`'=>OrderItems::is_barcode_valid,									//有效的消费码			
				'`OrderActives_Order`.`status`'=>Order::status_yes,									//订单是有效的
				'`OrderActives_Order`.`pay_status`'=>Order::pay_status_yes,					//已经支付了
				'`OrderActives_Order`.`order_status`'=>Order::order_status_user_pay,	//用户订单状态 已经支付了
				'`OrderActives_Order`.`status_go`'=>Order::status_go_yes,						//用户订单状态 确认出游
			));
			return OrderItems::model()->count($criteria);
		}
		return 0;
	}
	
	/**
	 * 组织者的扫描接口
	 * @param unknown $model
	 */
	public function tour($order_items,$code,$store_id,$store_model)
	{
		$transaction=$order_items->dbConnection->beginTransaction();
		try
		{
			$criteria=new CDbCriteria;
			$criteria->with=array(
				'OrderItems_StoreUser',
				'OrderItems_OrderActives'=>array(
					'with'=>array(
						'OrderActives_Order',
						'OrderActives_Actives',
					),
				),
			);
			//出游时间
			$criteria->addCondition('`OrderActives_Actives`.`go_time`<=:time');
			$criteria->addCondition('`OrderItems_OrderActives`.`user_pay_count` > 0');
			$criteria->addCondition('(`OrderActives_Actives`.`actives_status`=:start OR `OrderActives_Actives`.`actives_status`=:end)');		
			$criteria->params[':start']=Actives::actives_status_start;									//开始
			$criteria->params[':end']=Actives::actives_status_end;										//结束
			$criteria->params[':time']=time();																		//时间
			//标准条件
			$criteria->addColumnCondition(array(
					'`t`.`store_id`'=>$store_id,
					'`t`.`barcode`'=>$code,
					'`t`.`is_barcode`'=>OrderItems::is_barcode_valid,										//码有效				
					'`OrderActives_Order`.`status`'=>Order::status_yes,									//订单是有效的
					'`OrderActives_Order`.`pay_status`'=>Order::pay_status_yes,					//已经支付了
					'`OrderActives_Order`.`order_status`'=>Order::order_status_user_pay,	//用户订单状态 已经支付了
					'`OrderActives_Order`.`status_go`'=>Order::status_go_yes,						//用户订单状态 确认出游	
			));

			if($store_model->p_id != 0)
			{																															//权限限制
				$criteria->addColumnCondition(array(
					'`t`.`manager_id`'=>Yii::app()->store->id,
				));	
			}
			// 查询需要消费的项目
			$model = OrderItems::model()->find($criteria);
			//查不到 ====当前码不能消费
			if(! $model && isset($model->OrderItems_OrderActives->OrderActives_Actives) && $model->OrderItems_OrderActives->OrderActives_Order)
				throw new Exception("非法消费码或消费码错误");
			
			if(! isset($model->OrderItems_OrderActives->OrderActives_Order) || empty($model->OrderItems_OrderActives->OrderActives_Order))
				throw new Exception("扫描消费项目的价格记录不存在");
			
			//消费总数====可消费数model
			$barcode_valid_count = $this->count_barcode_valid($model->OrderItems_OrderActives->id,0,$model->OrderItems_OrderActives->organizer_id);
			if($barcode_valid_count == 0)
				throw new Exception("可消费码总数不可用 为零");
			
			$criteria_items=new CDbCriteria;
			$criteria_items->with=array(
					'OrderItems_OrderItems',
					'OrderItems_OrderItemsFare',
					'OrderItems_Order'=>array(
							'with'=>array(
									'Order_OrderActives'=>array(
											'with'=>array(
													'OrderActives_Actives'=>array('with'=>'Actives_Shops')
											),
									),
							),
					),
			);
			//标准条件
			$criteria_items->addColumnCondition(array(
				'`OrderItems_OrderItems`.`id`'=>$model->id,												//需要消费的项目
				'`OrderItems_OrderItems`.`is_barcode`'=>OrderItems::is_barcode_valid,		//消费码是有消费
				'`OrderItems_Order`.`status`'=>Order::status_yes,										//有效
				'`OrderItems_Order`.`pay_status`'=>Order::pay_status_yes,							//已支付
				'`OrderItems_Order`.`order_status`'=>Order::order_status_user_pay,			//已付款
				'`OrderItems_Order`.`status_go`'=>Order::status_go_yes,								//确认出游
			));
			$items_models = OrderItems::model()->findAll($criteria_items);
			if(empty($items_models))
				throw new Exception("活动扫描 活有效的订单没有有效的项目");
			//更新当前表
			$model->is_barcode = OrderItems::is_barcode_yes;
			$model->scan_barcode=$code;
			$model->employ_time=time();
			if(! $model->save(false))
				throw new Exception("更新消费码 失败");
		
			$charge_orders=array();
			foreach ($items_models as $items_model)
			{
				if(! OrderItems::scancodeActivesTour($items_model,true,$model->OrderItems_OrderActives->OrderActives_Actives->is_organizer == Actives::is_organizer_yes))
					throw new Exception("扫描消费活动项目 钱包操作错误".json_encode(array(Account::$create_error,AccountLog::$create_error)));
				$charge_orders[$items_model->OrderItems_Order->id]=$items_model->OrderItems_Order;
			}
			//更新订单表
			if($barcode_valid_count == 1)
			{			
				$order_ids=array();
				foreach ($model->OrderItems_OrderActives->OrderActives_Order as $order)
					$order_ids[]=$order->id;
				$order_ids = array_flip(array_flip($order_ids));
				//最后一个消费吗 修改订单状态 添加服务费
				$order_update=new CDbCriteria;
				$order_update->addInCondition('id', $order_ids);
				$order_update_return = Order::model()->updateAll(array(
					'order_status'=>Order::order_status_user_use,										//订单已消费
					'up_time'=>time(),
				),$order_update);
				if($order_update_return != count($order_ids))
					throw new Exception("更新订单状态（已消费） 失败");					

				//普通用户没有服务费用
				if( $model->OrderItems_OrderActives->OrderActives_Actives->is_organizer == Actives::is_organizer_yes)
				{
					if(! OrderItems::scancodeActivesTourCharge($charge_orders,true))
						throw new Exception("扫描消费活动服务费用 钱包操作错误".json_encode(array(Account::$create_error,AccountLog::$create_error)));										
				}
			}
			$scan_barcode=$this->log('商家扫描消费码 组织者消费码 :'.$model->scan_barcode,ManageLog::store,ManageLog::update);
			$transaction->commit();
		}
		catch(Exception $e)
		{
			$transaction->rollBack();
			$this->error_log($e->getMessage(),ErrorLog::store,ErrorLog::create,ErrorLog::rollback,__METHOD__);
		}
		if(isset($scan_barcode))
		{
			//成功
			$return['status']=array(
					'name'=>self::$_status[self::status_yes],
					'value'=>self::status_yes,
					'classliy'=>array(
							'name'=>'活动总单',
							'value'=>0,
					),
					'order_type'=>array(
							'name'=>'活动总单',
							'value'=>0,
					),
					'link'=>Yii::app()->params['app_api_domain'].Yii::app()->createUrl('/store/store_orderActives/view',array('id'=>$model->OrderItems_OrderActives->id)),
					'link_value'=>$model->OrderItems_OrderActives->id,
			);
			$this->send($return);
		}
		else
		{
			$return['status']=array(
					'name'=>self::$_status[self::status_not],
					'value'=>self::status_not,
					'error'=>$e->getMessage(),
			);
			$this->send($return);
		}
	}

}
