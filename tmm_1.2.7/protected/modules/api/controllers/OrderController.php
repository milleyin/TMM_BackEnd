<?php
/**
 * 订单控制器
 * @author Changhai Zhan
 *	创建时间：2015-09-19 15:05:53 */
class OrderController extends ApiController
{
	/**
	 * 默认操作数据模型
	 * @var string
	 */
	public $_class_model='Order';
	
	public function actions()
	{
		//{actions}
		return array(
			'paywx'=>'api.controllers.actions.order.PaywxOrderAction',
		);
		//{actions}
	}

	/**
	 * 兼容1.1.1版
	 * @param unknown $id
	 */
	public function actionFare_dot($id)
	{
		$this->actionFare($id);
	}
	
	/**
	 * 兼容1.1.1版
	 * @param unknown $id
	 */
	public function actionFare_thrand($id)
	{
		$this->actionFare($id);
	}
	
	/**
	 * 订单数据
	 * @param unknown $id
	 */
	public function actionFare($id)
	{
		$this->_class_model='User';
		$this->loadModel(Yii::app()->api->id,'status=1');
		$data=Shops::fare_shops($id);
		if(empty($data))
			$this->send_error(DATA_NULL);
		else
			$this->send($data);
	}
	
	/**
	 * 查看详情
	 * @param integer $id
	 */
	public function actionView($id)
	{
		$this->_class_model = 'User';
		$this->loadModel(Yii::app()->api->id,'status=1');
		
		$criteria =new CDbCriteria;
		$criteria->with=array(
			'Order_OrderActives'=>array(
					'with'=>array('OrderActives_Actives'=>array('with'=>'Actives_Shops'))
			),
			'Order_User',
			'Order_OrderRetinue',
			'Order_OrderItems'=>array(
					'with'=>array(							
						'OrderItems_OrderItemsFare',
						'OrderItems_ItemsClassliy',
						'OrderItems_OrderItems',
					),
					'order'=>'Order_OrderItems.shops_day_sort,Order_OrderItems.shops_half_sort,Order_OrderItems.shops_sort',
			),
		);
		$criteria->addColumnCondition(array(
				'`t`.`user_id`'=>Yii::app()->api->id,													//用户的
				'`Order_OrderActives`.`organizer_id`'=>Yii::app()->api->id,			//组织者活动订单
		),'OR');
		
		$criteria->addCondition('`t`.`status`=:status_yes OR ( `t`.`order_type`=:actives_tour AND `Order_OrderActives`.`organizer_id`=:organizer_id)');
		$criteria->params[':status_yes']=Order::status_yes;								//有效的订单
		$criteria->params[':organizer_id']=Yii::app()->api->id;							//组织者
		$criteria->params[':actives_tour']=Order::order_type_actives_tour;		//订单类型
				
		$this->_class_model = 'Order';
		$model=$this->loadModel($id,$criteria);
		$return=array();
		if($model->order_type==Order::order_type_thrand)//线 的订单
		{
			$return['go_time']=date('Y-m-d',$model->go_time);	//出游时间
			$return['value']=$model->id;
			$return['order_no']=$model->order_no;
			$return['order_price']=array(
					'name'=>'订单总价',
					'value'=>$model->order_price,
			);//出游时间
			$return['user_price_fact']=array(
					'name'=>'服务费/人',
					'value'=>$model->user_price_fact,
			);//出游时间
			$return['user_go_count']=array(
					'name'=>'出游人数',
					'value'=>$model->user_go_count,
			);
			//判断数据是否存在
			if( !isset($model->Order_OrderItems) || empty($model->Order_OrderItems) || !is_array($model->Order_OrderItems))
				$this->send_error(DATA_NOT_SCUSSECS);
			if( !isset($model->Order_OrderRetinue) || empty($model->Order_OrderRetinue) || !is_array($model->Order_OrderRetinue))
				$this->send_error(DATA_NOT_SCUSSECS);
			//随行人员
			foreach ($model->Order_OrderRetinue as $Order_OrderRetinue)
			{
				$return['retinue'][]=array(
						'is_main'=>$Order_OrderRetinue->is_main,
						'name'=>CHtml::encode($Order_OrderRetinue->retinue_name),
						'value'=>$Order_OrderRetinue->retinue_id,
						'link'=>Yii::app()->params['app_api_domain'].Yii::app()->createUrl('/api/retinue/view',array('id'=>$Order_OrderRetinue->retinue_id)),
				);
			}
			//项目 价格
			foreach ($model->Order_OrderItems as $key=>$Order_OrderItems)
			{
				if(!isset($Order_OrderItems->OrderItems_ItemsClassliy) || is_array($Order_OrderItems->OrderItems_ItemsClassliy))
					$this->send_error(DATA_NOT_SCUSSECS);
				$return['items_fare'][$Order_OrderItems->shops_day_sort][$Order_OrderItems->shops_half_sort][$Order_OrderItems->shops_sort]['name']=CHtml::encode($Order_OrderItems->items_name);
				$return['items_fare'][$Order_OrderItems->shops_day_sort][$Order_OrderItems->shops_half_sort][$Order_OrderItems->shops_sort]['value']=$Order_OrderItems->items_id;
				//免费项目
				$return['items_fare'][$Order_OrderItems->shops_day_sort][$Order_OrderItems->shops_half_sort][$Order_OrderItems->shops_sort]['free_status']=array(
						'name'=>Items::$_free_status[$Order_OrderItems->items_free_status],
						'value'=>$Order_OrderItems->items_free_status,
				);		
				$img=$this->litimg_path($Order_OrderItems->items_img);
				$return['items_fare'][$Order_OrderItems->shops_day_sort][$Order_OrderItems->shops_half_sort][$Order_OrderItems->shops_sort]['image']=empty($img)?'':Yii::app()->params['admin_img_domain'].ltrim($img,'.');
				
				$return['items_fare'][$Order_OrderItems->shops_day_sort][$Order_OrderItems->shops_half_sort][$Order_OrderItems->shops_sort]['link']=Yii::app()->params['app_api_domain'].Yii::app()->createUrl('/api/'.$Order_OrderItems->OrderItems_ItemsClassliy->admin.'/view',array('id'=>$Order_OrderItems->items_id));
				$return['items_fare'][$Order_OrderItems->shops_day_sort][$Order_OrderItems->shops_half_sort][$Order_OrderItems->shops_sort]['barcode']=array(
						'is_barcode'=>$Order_OrderItems->is_barcode,
						'barcode_name'=>OrderItems::$_is_barcode[$Order_OrderItems->is_barcode],
						'employ_time'=>$Order_OrderItems->employ_time==0?'':date('Y-m-d H:i:s',$Order_OrderItems->employ_time),
						'value'=>$Order_OrderItems->id,
						'link'=>Yii::app()->params['app_api_domain'].Yii::app()->createUrl('/api/order/barcode',array('id'=>$Order_OrderItems->id)),
				);
				$return['items_fare'][$Order_OrderItems->shops_day_sort][$Order_OrderItems->shops_half_sort][$Order_OrderItems->shops_sort]['classliy']=array(
						'name'=>$Order_OrderItems->items_c_name,
						'value'=>$Order_OrderItems->items_c_id,
				);
				if( !isset($Order_OrderItems->OrderItems_OrderItemsFare) || empty($Order_OrderItems->OrderItems_OrderItemsFare) || !is_array($Order_OrderItems->OrderItems_OrderItemsFare))
					$this->send_error(DATA_NOT_SCUSSECS);
				foreach ($Order_OrderItems->OrderItems_OrderItemsFare as $OrderItems_OrderItemsFare)
				{
					// 价格信息
					$return['items_fare'][$Order_OrderItems->shops_day_sort][$Order_OrderItems->shops_half_sort][$Order_OrderItems->shops_sort]['fare'][]=array(
							'name' =>CHtml::encode($OrderItems_OrderItemsFare->fare_name),
							'info' => CHtml::encode($OrderItems_OrderItemsFare->fare_info),
							'room_number'=>$OrderItems_OrderItemsFare->fare_number,
							'number' => $OrderItems_OrderItemsFare->number,
							'price' => $OrderItems_OrderItemsFare->fare_price,
							'count'=>$OrderItems_OrderItemsFare->total,
					);
				}
			}
			//状态
			$return['status']=array(
					'pay_type'=>array(
							'name'=>Order::$_pay_type[$model->pay_type],
							'value'=>$model->pay_type,
					),
					'order_status'=>array(
							'name'=>Order::$_order_status[$model->order_status],
							'value'=>$model->order_status,
					),
					'status_go'=>array(
							'name'=>Order::$_status_go[$model->status_go],
							'value'=>$model->status_go,
					),
					'centre_status'=>array(
							'name'=>Order::$_centre_status[$model->centre_status],
							'value'=>$model->centre_status,
					),
					'pay_status'=>array(
							'name'=>Order::$_pay_status[$model->pay_status],
							'value'=>$model->pay_status,
					),
					'order_type'=>array(
							'name'=>Order::$_order_type[$model->order_type],
							'value'=>$model->order_type,
					),
			);
		}
		elseif ($model->order_type==Order::order_type_dot)		//点的下单
		{
			$return['go_time']=date('Y-m-d',$model->go_time);	//出游时间
			$return['value']=$model->id;
			$return['order_no']=$model->order_no;
			$return['order_price']=array(
					'name'=>'订单总计',
					'value'=>$model->order_price,
			);//出游时间
			$return['user_price_fact']=array(
					'name'=>'服务费/人',
					'value'=>$model->user_price_fact,
			);//出游时间
			$return['user_go_count']=array(
				'name'=>'出游人数',
				'value'=>$model->user_go_count,
			);
			//判断数据是否存在
			if( !isset($model->Order_OrderItems) || empty($model->Order_OrderItems) || !is_array($model->Order_OrderItems))			
				$this->send_error(DATA_NOT_SCUSSECS);			
			if( !isset($model->Order_OrderRetinue) || empty($model->Order_OrderRetinue) || !is_array($model->Order_OrderRetinue))
				$this->send_error(DATA_NOT_SCUSSECS);
			//随行人员
			foreach ($model->Order_OrderRetinue as $Order_OrderRetinue)
			{
				$return['retinue'][]=array(
					'is_main'=>$Order_OrderRetinue->is_main,
					'name'=>CHtml::encode($Order_OrderRetinue->retinue_name),
					'value'=>$Order_OrderRetinue->retinue_id,
					'link'=>Yii::app()->params['app_api_domain'].Yii::app()->createUrl('/api/retinue/view',array('id'=>$Order_OrderRetinue->retinue_id)),
				);
			}	
			//项目 价格 		
			foreach ($model->Order_OrderItems as $key=>$Order_OrderItems)
			{
				if(!isset($Order_OrderItems->OrderItems_ItemsClassliy) || is_array($Order_OrderItems->OrderItems_ItemsClassliy))
					$this->send_error(DATA_NOT_SCUSSECS);
				$return['items_fare'][$key]['name']=CHtml::encode($Order_OrderItems->items_name);
				$return['items_fare'][$key]['value']=$Order_OrderItems->items_id;
				//免费项目
				$return['items_fare'][$key]['free_status']=array(
						'name'=>Items::$_free_status[$Order_OrderItems->items_free_status],
						'value'=>$Order_OrderItems->items_free_status,
				);
				$return['items_fare'][$key]['link']=Yii::app()->params['app_api_domain'].Yii::app()->createUrl('/api/'.$Order_OrderItems->OrderItems_ItemsClassliy->admin.'/view',array('id'=>$Order_OrderItems->items_id));
				$return['items_fare'][$key]['barcode']=array(
						'is_barcode'=>$Order_OrderItems->is_barcode,
						'barcode_name'=>OrderItems::$_is_barcode[$Order_OrderItems->is_barcode],
						'value'=>$Order_OrderItems->id,
						'link'=>Yii::app()->params['app_api_domain'].Yii::app()->createUrl('/api/order/barcode',array('id'=>$Order_OrderItems->id)),
				);
				$return['items_fare'][$key]['classliy']=array(
						'name'=>$Order_OrderItems->items_c_name,
						'value'=>$Order_OrderItems->items_c_id,
				);				
				if( !isset($Order_OrderItems->OrderItems_OrderItemsFare) || empty($Order_OrderItems->OrderItems_OrderItemsFare) || !is_array($Order_OrderItems->OrderItems_OrderItemsFare))			
					$this->send_error(DATA_NOT_SCUSSECS);			
				foreach ($Order_OrderItems->OrderItems_OrderItemsFare as $OrderItems_OrderItemsFare)
				{
					if($Order_OrderItems->items_c_id == Items::items_hotel)
					{
						// 价格信息
						$return['items_fare'][$key]['fare'][]=array(
								'name' =>CHtml::encode($OrderItems_OrderItemsFare->fare_name),
								'info' =>CHtml::encode($OrderItems_OrderItemsFare->fare_info),
								'number' =>CHtml::encode($OrderItems_OrderItemsFare->number),
								'start_date'=>CHtml::encode($OrderItems_OrderItemsFare->start_date),
								'end_date'=>CHtml::encode($OrderItems_OrderItemsFare->end_date),
								'hotel_number'=>CHtml::encode($OrderItems_OrderItemsFare->hotel_number),
								'room_number'=>$OrderItems_OrderItemsFare->fare_number,
								'price' => $OrderItems_OrderItemsFare->fare_price,
								'count'=>$OrderItems_OrderItemsFare->total,
						);
					}
					else
					{
						// 价格信息
						$return['items_fare'][$key]['fare'][]=array(
								'name' =>CHtml::encode($OrderItems_OrderItemsFare->fare_name),
								'info' =>CHtml::encode($OrderItems_OrderItemsFare->fare_info),
								'number' =>CHtml::encode($OrderItems_OrderItemsFare->number),		
								'room_number'=>$OrderItems_OrderItemsFare->fare_number,
								'price' => $OrderItems_OrderItemsFare->fare_price,
								'count'=>$OrderItems_OrderItemsFare->total,
						);
					}
				}
			}
			//状态
			$return['status']=array(				
					'pay_type'=>array(
							'name'=>Order::$_pay_type[$model->pay_type],
							'value'=>$model->pay_type,
					),
					'order_status'=>array(
							'name'=>Order::$_order_status[$model->order_status],
							'value'=>$model->order_status,
					),					
					'status_go'=>array(
							'name'=>Order::$_status_go[$model->status_go],
							'value'=>$model->status_go,
					),
					'centre_status'=>array(
							'name'=>Order::$_centre_status[$model->centre_status],
							'value'=>$model->centre_status,
					),
					'pay_status'=>array(
							'name'=>Order::$_pay_status[$model->pay_status],
							'value'=>$model->pay_status,
					),	
					'order_type'=>array(
							'name'=>Order::$_order_type[$model->order_type],
							'value'=>$model->order_type,
					)
			);		
		}
		elseif ($model->order_type==Order::order_type_actives_tour)		//活动的下单
		{
			$Actives=$model->Order_OrderActives->OrderActives_Actives;
			$img=$this->litimg_path($Actives->Actives_Shops->list_img);
			$return['actives']=array(
				'list_img'=>empty($img)?'':Yii::app()->params['admin_img_domain'].ltrim($img,'.'),
				'list_info'=>CHtml::encode($Actives->Actives_Shops->list_info),
				'actives_status'=>array(
					'name'=>Actives::$_actives_status[$Actives->actives_status],
					'value'=>$Actives->actives_status,
				),
				//活动类型
				'actives'=>array(
						'name'=>Actives::$_actives_type[$Actives->actives_type],
						'value'=>$Actives->actives_type,
				),
				'number'=>$Actives->number,
				'order_number'=>$Actives->order_number,
				'value'=>$Actives->id,
				'link'=>Yii::app()->params['app_api_domain'].Yii::app()->createUrl('/api/actvies/view',array('id'=>$Actives->id)),
			);
			// 是否是组织者
			$return['is_organizer'] = array(
					'name'=>Actives::$_is_organizer[$Actives->is_organizer],
					'value'=>$Actives->is_organizer,
			);
			$return['go_time']=$Actives->go_time==0?'出游日期未定':date('Y-m-d',$Actives->go_time);	//出游时间
			$return['value']=$model->id;
			$return['order_no']=$model->order_no;
			$return['order_price']=array(
					'name'=>'订单总价',
					'value'=>$model->order_price,
			);//出游时间
			$return['user_price_fact']=array(
					'name'=>'服务费/人',
					'value'=>$model->user_price_fact,
			);//出游时间
			$return['user_go_count']=array(
					'name'=>'出游人数',
					'value'=>$model->user_go_count,
			);
			//活动时间
			$return['action_time']=array(
					'start_time'=>date('Y-m-d',$Actives->start_time),
					'end_time'=>date('Y-m-d',$Actives->end_time),
					'add_time'=>date('Y-m-d H:i:s',$model->add_time),
			);
			//判断数据是否存在
			if( !isset($model->Order_OrderItems) || empty($model->Order_OrderItems) || !is_array($model->Order_OrderItems))
				$this->send_error(DATA_NOT_SCUSSECS);
			if( !isset($model->Order_OrderRetinue) || empty($model->Order_OrderRetinue) || !is_array($model->Order_OrderRetinue))
				$this->send_error(DATA_NOT_SCUSSECS);
			//随行人员
			foreach ($model->Order_OrderRetinue as $Order_OrderRetinue)
			{
				$return['retinue'][]=array(
						'is_main'=>$Order_OrderRetinue->is_main,
						'name'=>$Order_OrderRetinue->retinue_name,
						'value'=>$Order_OrderRetinue->retinue_id,
						'link'=>Yii::app()->params['app_api_domain'].Yii::app()->createUrl('/api/retinue/view',array('id'=>$Order_OrderRetinue->retinue_id)),
				);
			}
			//项目 价格
			foreach ($model->Order_OrderItems as $key=>$Order_OrderItems)
			{
				if(!isset($Order_OrderItems->OrderItems_ItemsClassliy) || is_array($Order_OrderItems->OrderItems_ItemsClassliy))
					$this->send_error(DATA_NOT_SCUSSECS);
				$return['items_fare'][$Order_OrderItems->shops_day_sort][$Order_OrderItems->shops_half_sort][$Order_OrderItems->shops_sort]['name']=CHtml::encode($Order_OrderItems->items_name);
				$return['items_fare'][$Order_OrderItems->shops_day_sort][$Order_OrderItems->shops_half_sort][$Order_OrderItems->shops_sort]['value']=$Order_OrderItems->items_id;
				//免费项目
				$return['items_fare'][$Order_OrderItems->shops_day_sort][$Order_OrderItems->shops_half_sort][$Order_OrderItems->shops_sort]['free_status']=array(
						'name'=>Items::$_free_status[$Order_OrderItems->items_free_status],
						'value'=>$Order_OrderItems->items_free_status,
				);
				$return['items_fare'][$Order_OrderItems->shops_day_sort][$Order_OrderItems->shops_half_sort][$Order_OrderItems->shops_sort]['link']=Yii::app()->params['app_api_domain'].Yii::app()->createUrl('/api/'.$Order_OrderItems->OrderItems_ItemsClassliy->admin.'/view',array('id'=>$Order_OrderItems->items_id));			
				$return['items_fare'][$Order_OrderItems->shops_day_sort][$Order_OrderItems->shops_half_sort][$Order_OrderItems->shops_sort]['barcode']=array(
						'is_barcode'=>$Order_OrderItems->is_barcode,
						'barcode_name'=>OrderItems::$_is_barcode[$Order_OrderItems->is_barcode],
						'employ_time'=>'',
						'value'=>'',
						'link'=>'',
				);
				$return['items_fare'][$Order_OrderItems->shops_day_sort][$Order_OrderItems->shops_half_sort][$Order_OrderItems->shops_sort]['classliy']=array(
						'name'=>$Order_OrderItems->items_c_name,
						'value'=>$Order_OrderItems->items_c_id,
				);
				if( !isset($Order_OrderItems->OrderItems_OrderItemsFare) || empty($Order_OrderItems->OrderItems_OrderItemsFare) || !is_array($Order_OrderItems->OrderItems_OrderItemsFare))
					$this->send_error(DATA_NOT_SCUSSECS);
				foreach ($Order_OrderItems->OrderItems_OrderItemsFare as $OrderItems_OrderItemsFare)
				{
					// 价格信息
					$return['items_fare'][$Order_OrderItems->shops_day_sort][$Order_OrderItems->shops_half_sort][$Order_OrderItems->shops_sort]['fare'][]=array(
							'name' =>CHtml::encode($OrderItems_OrderItemsFare->fare_name),
							'info' =>CHtml::encode($OrderItems_OrderItemsFare->fare_info),
							'number' =>$OrderItems_OrderItemsFare->number,
							'room_number'=>CHtml::encode($OrderItems_OrderItemsFare->fare_number),
							'price' => $OrderItems_OrderItemsFare->fare_price,
							'count'=>$OrderItems_OrderItemsFare->total,
					);
				}
			}
			//状态
			$return['status']=array(
					'pay_type'=>array(
							'name'=>Order::$_pay_type[$model->pay_type],
							'value'=>$model->pay_type,
					),
					'order_status'=>array(
							'name'=>Order::$_order_status[$model->order_status],
							'value'=>$model->order_status,
					),
					'status_go'=>array(
							'name'=>Order::$_status_go[$model->status_go],
							'value'=>$model->status_go,
					),
					'centre_status'=>array(
							'name'=>Order::$_centre_status[$model->centre_status],
							'value'=>$model->centre_status,
					),
					'pay_status'=>array(
							'name'=>Order::$_pay_status[$model->pay_status],
							'value'=>$model->pay_status,
					),
					'order_type'=>array(
							'name'=>Order::$_order_type[$model->order_type],
							'value'=>$model->order_type,
					),
					'status'=>array(
							'name'=>Order::$_status[$model->status],
							'value'=>$model->status,
					),
			);
		}
		elseif($model->order_type==Order::order_type_actives_tour_full) //活动代付的订单详情
		{
			$Actives=$model->Order_OrderActives->OrderActives_Actives;
			$img=$this->litimg_path($Actives->Actives_Shops->list_img);
			$return['actives']=array(
					'list_img'=>empty($img)?'':Yii::app()->params['admin_img_domain'].ltrim($img,'.'),
					'list_info'=>CHtml::encode($Actives->Actives_Shops->list_info),
					'actives_status'=>array(
							'name'=>Actives::$_actives_status[$Actives->actives_status],
							'value'=>$Actives->actives_status,
					),
					//活动类型
					'actives'=>array(
							'name'=>Actives::$_actives_type[$Actives->actives_type],
							'value'=>$Actives->actives_type,
					),
					'pay_type'=>array(
							'name'=>Actives::$_pay_type[$Actives->pay_type],
							'value'=>$Actives->pay_type,
					),
					//活动类型
					'actives'=>array(
							'name'=>Actives::$_actives_type[$Actives->actives_type],
							'value'=>$Actives->actives_type,
					),
					'number'=>$Actives->number,
					'order_number'=>$Actives->order_number,
					'value'=>$Actives->id,
					'link'=>Yii::app()->params['app_api_domain'].Yii::app()->createUrl('/api/actvies/view',array('id'=>$Actives->id)),
			);
			// 是否是组织者
			$return['is_organizer'] = array(
					'name'=>Actives::$_is_organizer[$Actives->is_organizer],
					'value'=>$Actives->is_organizer,
			);
			$return['go_time'] = $Actives->go_time==0?'出游日期未定':date('Y-m-d',$Actives->go_time);	//出游时间
			$return['value'] = $model->id;
			$return['order_no']=$model->order_no;
			$return['order_price']=array(
					'name'=>'订单总价',
					'value'=>$model->order_price,
			);//出游时间
			$return['user_price_fact']=array(
					'name'=>'服务费/人',
					'value'=>$model->user_price_fact,
			);//出游时间
			$return['user_go_count']=array(
					'name'=>'出游人数',
					'value'=>$model->user_go_count,
			);
			//活动时间
			$return['action_time']=array(
					'start_time'=>date('Y-m-d',$Actives->start_time),
					'end_time'=>date('Y-m-d',$Actives->end_time),
					'add_time'=>date('Y-m-d H:i:s',$model->add_time),
			);
			//判断数据是否存在
			if( !isset($model->Order_OrderItems) || empty($model->Order_OrderItems) || !is_array($model->Order_OrderItems))
				$this->send_error(DATA_NOT_SCUSSECS);
			//报名人员
			$retinues = Attend::getActivesAttend($Actives->id);
			foreach ($retinues as $retinue)
			{
				$attend = array();
				foreach ($retinue->Attend_Attend as $Attend_Attend)
				{
					$attend[] = array(
						'name'=>CHtml::encode($Attend_Attend->name),
						'phone'=>$Attend_Attend->phone,
						'is_people'=>array(
								'name'=>Attend::$_is_people[$Attend_Attend->is_people],
								'value'=>$Attend_Attend->is_people,
						),
						'gender'=>array(
								'name'=>Attend::$_gender[$Attend_Attend->gender],
								'value'=>$Attend_Attend->gender,
						),
					);
				}
				$return['retinue'][] = array(
						'name'=>CHtml::encode($retinue->name),
						'is_main'=>$retinue->founder_id == $retinue->user_id ? 1 : 0,
						'number'=>$retinue->number,
						'people'=>$retinue->people,
						'children'=>$retinue->children,
						'phone'=>$retinue->phone,
						'is_people'=>array(
								'name'=>Attend::$_is_people[$retinue->is_people],
								'value'=>$retinue->is_people,
						),
						'gender'=>array(
								'name'=>Attend::$_gender[$retinue->gender],
								'value'=>$retinue->gender,
						),
						'son'=>$attend,
				);
			}
			//项目 价格
			foreach ($model->Order_OrderItems as $key=>$Order_OrderItems)
			{
				if(!isset($Order_OrderItems->OrderItems_ItemsClassliy) || is_array($Order_OrderItems->OrderItems_ItemsClassliy))
					$this->send_error(DATA_NOT_SCUSSECS);
				$return['items_fare'][$Order_OrderItems->shops_day_sort][$Order_OrderItems->shops_half_sort][$Order_OrderItems->shops_sort]['name']=CHtml::encode($Order_OrderItems->items_name);
				$return['items_fare'][$Order_OrderItems->shops_day_sort][$Order_OrderItems->shops_half_sort][$Order_OrderItems->shops_sort]['value']=$Order_OrderItems->items_id;
				//免费项目
				$return['items_fare'][$Order_OrderItems->shops_day_sort][$Order_OrderItems->shops_half_sort][$Order_OrderItems->shops_sort]['free_status']=array(
						'name'=>Items::$_free_status[$Order_OrderItems->items_free_status],
						'value'=>$Order_OrderItems->items_free_status,
				);
				$return['items_fare'][$Order_OrderItems->shops_day_sort][$Order_OrderItems->shops_half_sort][$Order_OrderItems->shops_sort]['link']=Yii::app()->params['app_api_domain'].Yii::app()->createUrl('/api/'.$Order_OrderItems->OrderItems_ItemsClassliy->admin.'/view',array('id'=>$Order_OrderItems->items_id));
				$return['items_fare'][$Order_OrderItems->shops_day_sort][$Order_OrderItems->shops_half_sort][$Order_OrderItems->shops_sort]['barcode']=array(
						'is_barcode'=>$Order_OrderItems->OrderItems_OrderItems->is_barcode,
						'barcode_name'=>OrderItems::$_is_barcode[$Order_OrderItems->OrderItems_OrderItems->is_barcode],
						'employ_time'=>$Order_OrderItems->OrderItems_OrderItems->employ_time != 0 ? date('Y-m-d H:i:s',$Order_OrderItems->OrderItems_OrderItems->employ_time) : '',
						'value'=>$Order_OrderItems->OrderItems_OrderItems->id,
						'link'=>Yii::app()->params['app_api_domain'].Yii::app()->createUrl('/api/order/barcode',array('id'=>$Order_OrderItems->OrderItems_OrderItems->id)),
				);
				$return['items_fare'][$Order_OrderItems->shops_day_sort][$Order_OrderItems->shops_half_sort][$Order_OrderItems->shops_sort]['classliy']=array(
						'name'=>$Order_OrderItems->items_c_name,
						'value'=>$Order_OrderItems->items_c_id,
				);
				if( !isset($Order_OrderItems->OrderItems_OrderItemsFare) || empty($Order_OrderItems->OrderItems_OrderItemsFare) || !is_array($Order_OrderItems->OrderItems_OrderItemsFare))
					$this->send_error(DATA_NOT_SCUSSECS);
				foreach ($Order_OrderItems->OrderItems_OrderItemsFare as $OrderItems_OrderItemsFare)
				{
					// 价格信息
					$return['items_fare'][$Order_OrderItems->shops_day_sort][$Order_OrderItems->shops_half_sort][$Order_OrderItems->shops_sort]['fare'][]=array(
							'name' =>CHtml::encode($OrderItems_OrderItemsFare->fare_name),
							'info' =>CHtml::encode($OrderItems_OrderItemsFare->fare_info),
							'number' =>$OrderItems_OrderItemsFare->number,
							'room_number'=>CHtml::encode($OrderItems_OrderItemsFare->fare_number),
							'price' => $OrderItems_OrderItemsFare->fare_price,
							'count'=>$OrderItems_OrderItemsFare->total,
					);
				}
			}
			//状态
			$return['status']=array(
					'pay_type'=>array(
							'name'=>Order::$_pay_type[$model->pay_type],
							'value'=>$model->pay_type,
					),
					'order_status'=>array(
							'name'=>Order::$_order_status[$model->order_status],
							'value'=>$model->order_status,
					),
					'status_go'=>array(
							'name'=>Order::$_status_go[$model->status_go],
							'value'=>$model->status_go,
					),
					'centre_status'=>array(
							'name'=>Order::$_centre_status[$model->centre_status],
							'value'=>$model->centre_status,
					),
					'pay_status'=>array(
							'name'=>Order::$_pay_status[$model->pay_status],
							'value'=>$model->pay_status,
					),
					'order_type'=>array(
							'name'=>Order::$_order_type[$model->order_type],
							'value'=>$model->order_type,
					),
					'status'=>array(
							'name'=>Order::$_status[$model->status],
							'value'=>$model->status,
					),
			);	
		}
		else 
			$this->send(DATA_NOT_SCUSSECS);
	
		$this->send($return);
	}

	/**
	 * 创建订单
	 */
	public function actionCreate()
	{		
		/**
		 * $_POST['OrderItemsFare'][0][info]=name 类型
		 * $_POST['OrderItemsFare'][0][number]= 数量
		 * $_POST['OrderItemsFare'][0][is_room]=0 是否是房间 （房间数量）
		 * 
		 * $_POST[OrderItems]['day_sort']
		 * $_POST[OrderItems]['day_sort'] //天 0表示没有天 1表示有天   点传0 其余非0
		 * $_POST[OrderItems]['day_sort'][half_sort] 商品排序
		 * $_POST[OrderItems]['day_sort'][half_sort][shops_id] 商品id
		 * $_POST[OrderItems]['day_sort'][half_sort][shops_id][sort] 项目排序
		 * $_POST[OrderItems]['day_sort'][half_sort][shops_id][sort][items_id][0] 价格排序
		 * $_POST[OrderItems]['day_sort'][half_sort][shops_id][sort][items_id][0][fare_id] 价格id
		 * $_POST[OrderItems]['day_sort'][half_sort][shops_id][sort][items_id][0][fare_id][price]=购买的单价
		 * $_POST[OrderItems]['day_sort'][half_sort][shops_id][sort][items_id][0][fare_id][number]=购买的数量
		 * $_POST[OrderItems]['day_sort'][half_sort][shops_id][sort][items_id][0][fare_id][count]=价格统计
		 * 
		 * $_POST[OrderRetinue][0] 随行人
		 * $_POST[OrderRetinue][0][is_main]=0 主要人员id
		 * $_POST[OrderRetinue][0][retinue_id] 随行人员排序
		 * 
		 * $_POST[Order][order_price]=2020 订单钱的统计
		 * $_POST[Order][son_order_count]=0 子订单的总费用
		 * $_POST[Order][user_go_count]=  用户出游人数
		 * $_POST[Order][go_time] =出游时间
		 * $_POST[Order][user_price]=服务费用/人
		 * $_POST[Order][order_type]=1  //订单类型 订单类型 0未知 1 点（多个点组合） 2 一条线 3一个结伴游
		 * 
		 * array('order'=>array(order_price=>'2020','))
		 */
		$model=new Order;

		if(isset($_POST['Order']) && isset($_POST['OrderItems']) && isset($_POST['OrderRetinue']) && is_array($_POST['Order']) && is_array($_POST['OrderItems']) && is_array($_POST['OrderRetinue']))
		{	
			$model=new Order;			
			$this->_class_model='User';
			$model->Order_User=$this->loadModel(Yii::app()->api->id,'`status`=1');
			
			//验证商品 价格 数据 随行人员 等 是否合法
			$validate_order=false;
			$validate_order=Order::validate_order($model);
			if($validate_order && !$model->hasErrors())
			{
				if(Order::create_order($model)) //创建订单 
				{
					$return=array(
							Yii::app()->params['app_api_domain'].Yii::app()->createUrl('/api/order/view',array('id'=>$model->id)),
							'id'=>array(
									'name'=>'订单ID',
									'value'=>$model->id,							
							),						
							'order_no'=>array(
									'value'=>$model->order_no,
									'name'=>'订单号',
							),
							'order_type'=>array(
									'value'=>$model->order_type,
									'name'=>Order::$_order_type[$model->order_type],
							),
							'pay_type'=>array(
									'name'=>Order::$_pay_type[$model->pay_type],
									'value'=>$model->pay_type,
							),
							'order_status'=>array(
									'name'=>Order::$_order_status[$model->order_status],
									'value'=>$model->order_status,
							),
							'status_go'=>array(
									'name'=>Order::$_status_go[$model->status_go],
									'value'=>$model->status_go,
							),
							'centre_status'=>array(
									'name'=>Order::$_centre_status[$model->centre_status],
									'value'=>$model->centre_status,
							),
							'pay_status'=>array(
									'name'=>Order::$_pay_status[$model->pay_status],
									'value'=>$model->pay_status,
							),
						);
						$return['link']=Yii::app()->params['app_api_domain'].Yii::app()->createUrl('/api/order/view',array('id'=>$model->id));
					$this->send($return); //创建后发送消息
				}else 
					$this->send_error_form(array(array('order_price'=>'事物错误')));
			}else
				$this->send_error_form($this->form_error(Order::get_error($model)));
		}else
			$this->send_csrf();
	}
	
	/**
	 * 获取消费码 
	 */
	public function actionBarcode($id)
	{
		$this->_class_model='User';
		$this->loadModel(Yii::app()->api->id,'status=1');
		
		$criteria=new CDbCriteria;
		$criteria->with=array(
			'OrderItems_Order',
			'OrderItems_OrderActives'=>array(
				'with'=>array(
					'OrderActives_User'=>array('with'=>'User_Organizer'),
				),
			),
		);
		
		$condition_o = $condition_u = array();															//用户的码 组织者的码
		$o=array(
				'`OrderActives_User`.`status`'=>User::status_suc,									//用户正常
				'`OrderItems_OrderActives`.`organizer_id`'=>Yii::app()->api->id,
		);
		$u=array(
				'OrderItems_Order.user_id'=>Yii::app()->api->id,									//用户的
				'OrderItems_Order.order_status'=>Order::order_status_user_pay,		//订单的状态 	付款
				'OrderItems_Order.status_go'=>Order::status_go_yes,							//是否出游      	是
				'OrderItems_Order.pay_status'=>Order::pay_status_yes,						//是否支付		是
		);
		$i=0;
		foreach ($o as $o_k=>$o_v)
		{
			$condition_o[]=$o_k.'=:o'.$i;
			$criteria->params[':o'.$i++]=$o_v;			
		}
		$j=0;
		foreach ($u as $u_k=>$u_v)
		{
			$condition_u[]=$u_k.'=:u'.$j;
			$criteria->params[':u'.$j++]=$u_v;
		}
		$criteria->addCondition('('.implode(' AND ',$condition_o).') OR ('.implode(' AND ',$condition_u).')');
		//标准条件
		$criteria->addColumnCondition(array(
				'`t`.`is_shops`'=>OrderItems::is_shops_store_yes,										//商家接单		接单
				'`t`.`is_barcode`'=>OrderItems::is_barcode_valid,										//有效的
		));
		$this->_class_model = 'OrderItems';
		$model=$this->loadModel($id, $criteria);
		
		$this->log('获取消费码 消费码 :'.$model->barcode,ManageLog::user,ManageLog::select);
		$return=array(
			'barcode'=>array(
				'name'=>'消费码',
				'value'=>$model->barcode,
			),
			'barcode_status'=>array(
				'name'=>OrderItems::$_is_barcode[$model->is_barcode],
				'value'=>$model->is_barcode,
			),
		);
		$this->send($return);
	}
	
	/**
	 * 支付接口
	 */
	public function actionPayment()
	{
		if(isset($_POST['id']) && !empty($_POST['id']) && isset($_POST['pay_type']) && !empty($_POST['pay_type']))
		{
			$pay_type=Yii::app()->params['pay_type_value'];
			if(empty($pay_type))
				$pay_type=array(1=>'alipay');
			if(isset($pay_type[$_POST['pay_type']]) && !empty($pay_type[$_POST['pay_type']]) && !is_array($_POST['id']))
			{				
				$this->_class_model = 'User';
				$this->loadModel(Yii::app()->api->id,'status=1');
				
				$criteria =new CDbCriteria;
				$criteria->with=array(
					'Order_User'=>array(
							'condition'=>'`Order_User`.`status`=1 AND `Order_User`.`id`=:id',
							'params'=>array(':id'=>Yii::app()->api->id),
					),
				);		
				$criteria->addCondition('`t`.`order_price` > 0');										//订单价格大于零
				$criteria->addCondition('`t`.`pay_status`=:pay_status_not OR `t`.`pay_status`=:pay_status_paying');
				$criteria->params[':pay_status_not']=Order::pay_status_not;				//没有支付
				$criteria->params[':pay_status_paying']=Order::pay_status_paying;	//支付中
				$criteria->addCondition('`t`.`pay_type`=:pay_type_none OR `t`.`pay_type`=:pay_type_alipay');
				$criteria->params[':pay_type_none']=Order::pay_type_none;				//没有支付
				$criteria->params[':pay_type_alipay']=Order::pay_type_alipay;			//支付宝
				$criteria->addColumnCondition(array(
	 				't.user_id'=>Yii::app()->api->id,															//用户的
	 				't.order_status'=>Order::order_status_store_yes,								//订单的状态
	 				't.status_go'=>Order::status_go_yes,													//是否出游
	 				't.centre_status'=>Order::centre_status_yes,										//是否可以支付
					't.status'=>Order::status_yes,																//有效的订单
				));
				$this->_class_model = 'Order';
				$model=$this->loadModel($_POST['id'],$criteria);
				if(isset($model->Order_User) && !empty($model->Order_User))
				{
					$return=array();
					//开启事物
					$transaction = $model->dbConnection->beginTransaction();
					try
					{
						if (Order::model()->findByPk($model->id, $criteria))
						{
							$payment = $pay_type[$_POST['pay_type']];
							$return['alipay']=Order::$payment($model);			//支付数据
							$return['phone']=$model->Order_User->phone;
							$return['nickname']=$model->Order_User->nickname;
							
							if( !Order::pay_status_paying($model->id))//更新为支付中……
								throw new Exception("支付订单 保存改变订单状态失败");
							$this->log('支付订单', ManageLog::user, ManageLog::update);
						}
						else
							throw new Exception("支付订单 没有找到订单");
						$transaction->commit();
					}
					catch (Exception $e)
					{
						$transaction->rollBack();
						$return = array();
						$this->error_log($e->getMessage(), ErrorLog::user, ErrorLog::update, ErrorLog::rollback, __METHOD__);
					}
					if ( !empty($return))
						$this->send($return);
					else 
						$this->send_error(ALIPAY_USER_ERROR);
				}
			}
		}
		$this->send_csrf();
	}

	/**
	 * 取消订单
	 * @param $id
	 */
	public function actionCancel($id)
	{
		$this->_class_model = 'User';
		$this->loadModel(Yii::app()->api->id,'status=1');
		
		$criteria_order = new CDbCriteria;
		//订单状态
		$criteria_order->addCondition('`t`.`order_status`=:query OR `t`.`order_status`=:yes');
		$criteria_order->params[':query'] = Order::order_status_store_query;				//商家没有接单
		$criteria_order->params[':yes'] = Order::order_status_store_yes;						//商家接单
		//订单出游状态
		$criteria_order->addCondition('`t`.`status_go`=:status_go_set OR `t`.`status_go`=:status_go_query OR `t`.`status_go`=:status_go_yes');
		$criteria_order->params[':status_go_set'] = Order::status_go_set;					//出游未定
		$criteria_order->params[':status_go_query']	= Order::status_go_query;			//已定出游时间 是否出游
		$criteria_order->params[':status_go_yes']	= Order::status_go_yes;					//确认出游
		//核心状态
		$criteria_order->addCondition('`t`.`centre_status`=:centre_status_not OR `t`.`centre_status`=:centre_status_yes');
		$criteria_order->params[':centre_status_not'] = Order::centre_status_not;		//不可支付 
		$criteria_order->params[':centre_status_yes'] = Order::centre_status_yes;		//可支付
		//标准条件
		$criteria_order->addColumnCondition(array(
				'`t`.`pay_status`' => Order::pay_status_not,                        						//是否支付 没有支付
				'`t`.`status`' => Order::status_yes,																//有效订单	
				'`t`.`user_id`' => Yii::app()->api->id,															//谁的订单
		));
		//订单类型
		$criteria_order->addCondition('(`t`.`order_type`=:dot OR `t`.`order_type`=:thrand OR `t`.`order_type`=:actives_tour OR `t`.`order_type`=:full)');
		$criteria_order->params[':dot'] = Order::order_type_dot;									//点 自助游
		$criteria_order->params[':thrand'] = Order::order_type_thrand;						//线 自助游
		$criteria_order->params[':actives_tour'] = Order::order_type_actives_tour;		//活动(旅游)
		$criteria_order->params[':full'] = Order::order_type_actives_tour_full;				//活动(旅游) 代付
		//关联
		$criteria_order->with=array(
				'Order_OrderItems',																					//订单中项目
				'Order_OrderActives'																					//活动订单的归属活动总订单
		);
		$this->_class_model = 'Order';
		$order=$this->loadModel($id,$criteria_order);
		//开启事物
		$transaction = $order->dbConnection->beginTransaction();
		try{
			$model=Order::model()->findByPk($id,$criteria_order);
			if(! $model)
				throw new Exception("用户取消的点订单不是有效的订单");
			//订单是活动的 没有出游 或 等待确认出游的 设置订单无效 （取消报名）
			if($model->order_type == Order::order_type_actives_tour && ($model->status_go == Order::status_go_query || $model->status_go == Order::status_go_set))
			{
				$model->status = Order::status_not;															//取消报名是无效的订单
			}
			//取消订单
			$model->order_status = Order::order_status_store_undo;							//已取消
			//核心状态
			$model->centre_status = Order::centre_status_not;										//不支付
			//取消出游
			$model->status_go = Order::status_go_no;													//取消出游
			//支付状态
			$model->pay_status = Order::pay_status_past;												//已取消
			//保存
			if($model->save(false))
			{
				if(isset($model->Order_OrderItems) && !empty($model->Order_OrderItems))
				{
					$criteria =new CDbCriteria;
					$criteria->addColumnCondition(array(
						'order_id'=>$model->id,
						'user_id'=>Yii::app()->api->id,
					));
					if(! OrderItems::model()->updateAll(array(
						'is_shops'=>OrderItems::is_shops_order_not										//用户已取消
					),$criteria))
					{
						throw new Exception("用户取消订单 更新订单详情表错误");
					}
				}
				//如果是活动订单
				if($model->order_type == Order::order_type_actives_tour)
				{
					if(! Actives::restore_order_number($model->Order_OrderActives->actives_id,$model->user_go_count))
					{
						throw new Exception("用户取消订单 归还觅趣数量失败");
					}
					if(! Actives::actives_tour_count_out($model->Order_OrderActives->actives_id))
					{
						throw new Exception("用户取消订单 减少报名人数失败");
					}
				}
				else if ($model->order_type == Order::order_type_actives_tour_full)
				{
					if(! OrderActives::actives_order_count($model->order_organizer_id,true))
						throw new Exception("用户取消订单 减少报名人数失败");
					if (! OrderActives::actives_confirm_count($model->order_organizer_id,true))
						throw new Exception("用户取消订单 减少确认出游失败");
				}
			}else 
				throw new Exception("用户取消订单 更新订单表错误");		
			if($model->order_type == Order::order_type_actives_tour && ($model->status_go == Order::status_go_query || $model->status_go == Order::status_go_set))
				$this->log('用户取消报名',ManageLog::user,ManageLog::update);
			else
				$this->log('用户取消订单',ManageLog::user,ManageLog::update);
			$this->_class_model='Order';
			$model= $this->loadModel($order->id);
			$transaction->commit();
		}
		catch (Exception $e)
		{
			$transaction->rollBack();
			$this->error_log($e->getMessage(),ErrorLog::user,ErrorLog::create,ErrorLog::rollback,__METHOD__);
		}
		if(isset($model))
		{
			$return=array(
				'order'=>array(
					'order_no'=>$model->order_no,   	//订单号
					'value'=>$model->id,							//订单id
					//订单详情链接
					'link'=>Yii::app()->params['app_api_domain'].Yii::app()->createUrl('/api/order/view',array('id'=>$model->id)),
				),
				'order_status'=>array(
					'name'=>Order::$_order_status[$model->order_status],
					'value'=>$model->order_status
				),
			);
			$this->send($return);
		} else {
			$this->send_error(DATA_NOT_SCUSSECS);
		}
	}
	
	/**
	 * AA 付款的活动
	 * 确认出游
	 */
	public function actionConfirm($id)
	{
		$this->_class_model = 'User';
		$this->loadModel(Yii::app()->api->id,'status=1');
		
		$criteria =new CDbCriteria;
		$criteria->with=array(
				'Order_User',
				'Order_OrderActives'=>array(
					'with'=>array(
							'OrderActives_Actives'
					),
				)
		);
		//出游时间
		$criteria->addCondition('`OrderActives_Actives`.`go_time` > :new_time');
		$criteria->params[':new_time']=time()-3600*24;
		//报名时间
		$criteria->addCondition('`OrderActives_Actives`.`actives_status`=:actives_status_start OR `OrderActives_Actives`.`actives_status`=:actives_status_end');
		$criteria->params[':actives_status_start']=Actives::actives_status_start;			//开始进行中
		$criteria->params[':actives_status_end']=Actives::actives_status_end;				//结束
		//标准条件
		$criteria->addColumnCondition(array(
				'`OrderActives_Actives`.`pay_type`'=>Actives::pay_type_AA,					//活动 AA付款
				'`t`.`order_type`'=>Order::order_type_actives_tour,									//订单类型
				'`t`.`status_go`'=>Order::status_go_query,													//出游状态 待确认出游
				'`t`.`order_status`'=>Order::order_status_store_yes,									//订单状态 商家默认接单
				'`t`.`pay_status`'=>Order::pay_status_not,													//支付状态 待支付
				'`t`.`centre_status`'=>Order::centre_status_not,											//核心状态 不可支付
				'`t`.`user_id`'=>Yii::app()->api->id,																//用户的
				'`t`.`status`'=>Order::status_yes,																	//有效的订单
		));
		$this->_class_model = 'Order';
		$order=$this->loadModel($id,$criteria);

		$transaction = $order->dbConnection->beginTransaction();
		try{
			$model=Order::model()->findByPk($id,$criteria);
			if(! $model)
				throw new Exception("用户确认出游觅趣(旅游)订单失败");
			//订单
			$model->order_status=Order::order_status_store_yes;			//待支付
			//核心状态
			$model->centre_status=Order::centre_status_yes;					//可支付
			//支付状态
			$model->pay_status=Order::pay_status_not;							//待支付
			//出游状态
			$model->status_go= Order::status_go_yes;								//确认出游
			if(! $model->save(false))
				throw new Exception("用户取消订单 更新订单表错误");				
			if (! OrderActives::actives_confirm_count($model->Order_OrderActives->id))
				throw new Exception("确认出游数量的订单统计错误");
			$this->log('觅趣确认出游',ManageLog::user,ManageLog::update);
			$this->_class_model='Order';
			$model_return= $this->loadModel($order->id);
			$transaction->commit();
		}
		catch (Exception $e)
		{
			$transaction->rollBack();
			$this->error_log($e->getMessage(),ErrorLog::user,ErrorLog::create,ErrorLog::rollback,__METHOD__);
		}
		if(isset($model_return))
		{
			$return=array(
					'order'=>array(
							'order_no'=>$model_return->order_no,    	//订单号
							'value'=>$model_return->id,							//订单id
							//订单详情链接
							'link'=>Yii::app()->params['app_api_domain'].Yii::app()->createUrl('/api/order/view',array('id'=>$model_return->id)),
					),
					'pay_type'=>array(
							'name'=>Order::$_pay_type[$model_return->pay_type],
							'value'=>$model_return->pay_type,
					),
					'order_status'=>array(
							'name'=>Order::$_order_status[$model_return->order_status],
							'value'=>$model_return->order_status,
					),
					'status_go'=>array(
							'name'=>Order::$_status_go[$model_return->status_go],
							'value'=>$model_return->status_go,
					),
					'centre_status'=>array(
							'name'=>Order::$_centre_status[$model_return->centre_status],
							'value'=>$model_return->centre_status,
					),
					'pay_status'=>array(
							'name'=>Order::$_pay_status[$model_return->pay_status],
							'value'=>$model_return->pay_status,
					),
					'order_type'=>array(
							'name'=>Order::$_order_type[$model_return->order_type],
							'value'=>$model_return->order_type,
					),
			);
			$this->send($return);
		} else {
			$this->send_error(DATA_NOT_SCUSSECS);
		}
	}

	/**
	 * 订单列表
	 */
	public function actionIndex($type='')
	{
		$this->_class_model='User';
		$this->loadModel(Yii::app()->api->id,'status=1');
		$criteria=new CDbCriteria;
		$criteria->with=array(
				'Order_OrderShops',
				'Order_OrderItems',
				'Order_OrderActives'=>array(
						'with'=>array('OrderActives_Actives'),
				),
		);
		$criteria->addColumnCondition(array(
				'`t`.`user_id`'=>Yii::app()->api->id,
				'`t`.`status`'=>Order::status_yes,
		));
		//活动列表
		if($type == 'actives_tour')
		{
			$criteria->addCondition('`t`.`order_type`=:actives_tour AND  (`t`.`status_go` =:no OR `t`.`status_go` =:yes OR `t`.`status_go` =:set OR `t`.`status_go` =:query)');
			$criteria->params[':actives_tour'] = Order::order_type_actives_tour;	//活动（旅游）
			$criteria->params[':set'] = Order::status_go_set;									//出游时间没有给
			$criteria->params[':query'] = Order::status_go_query;							//出游时间给了 待确认
			$criteria->params[':yes'] = Order::status_go_yes;									//活动（旅游）确认出游的算订单
			$criteria->params[':no'] = Order::status_go_no;									//活动（旅游）确认出游的算订单
		}
		elseif($type == 'order_tour')																		//觅趣
		{
			$criteria->addCondition('(`t`.`order_type`=:full OR `t`.`order_type`=:actives_tour) AND (`t`.`status_go`=:yes OR `t`.`status_go`=:no)');
			$criteria->params[':full'] = Order::order_type_actives_tour_full;			//活动（旅游）
			$criteria->params[':actives_tour'] = Order::order_type_actives_tour;	//活动（旅游）
			$criteria->params[':yes'] = Order::status_go_yes;									//活动（旅游）确认出游的算订单
			$criteria->params[':no'] = Order::status_go_no;									//活动（旅游）取消的
		}
		elseif($type == 'order_dot_thrand')															//秘境
		{
			$criteria->addCondition('`t`.`order_type`=:dot  OR `t`.`order_type`=:thrand');
			$criteria->params[':dot']=Order::order_type_dot;									//点
			$criteria->params[':thrand']=Order::order_type_thrand;						//线
		}
		else
		{
			$criteria->addCondition('`t`.`order_type`=:dot  OR `t`.`order_type`=:thrand');
			$criteria->params[':dot']=Order::order_type_dot;									//点
			$criteria->params[':thrand']=Order::order_type_thrand;						//线
		}
		$criteria->order='t.up_time desc';																//订单排序
		$count = Order::model()->count($criteria);
		
		$return=array();
		//分页设置
		$return['page']=$this->page($criteria, $count, Yii::app()->params['api_pageSize']['order_user_list'], Yii::app()->params['app_api_domain']);
		//根据条件查询
		$models = Order::model()->findAll($criteria);
		//分页数据
		$return['list_data']=$this->list_data($models, Yii::app()->params['app_api_domain']);
		
		if(empty($return['list_data']))
		{
			$return['list_data']=array();
			$return['null']='小觅已经很努力了！';
		}
		$this->send($return);
	}
	
	/**
	 * 列表数据
	 * @param unknown $models
	 * @param unknown $domain
	 */
	public function list_data($models,$domain)
	{
		$return = array();
		foreach ($models as $model)
		{
			$return_list = array();
			if(!isset($model->Order_OrderShops,$model->Order_OrderItems) || empty($model->Order_OrderShops) && empty($model->Order_OrderItems))
			{
				return $return_list;
			}
			$return_list['shops_img'] = '';
			$return_list['shops_name'] = '';
			$return_list['order_price'] = $model->order_price;
			foreach ($model->Order_OrderShops as $Order_OrderShops)
			{
				if ($return_list['shops_name'] == '')
					$return_list['shops_name'] = CHtml::encode($Order_OrderShops->shops_name);
				$img1 = $this->litimg_path($Order_OrderShops->shops_list_img);
				$img2 = $this->litimg_path($Order_OrderShops->shops_page_img);	
				if ($img1 != '')
				{
					$return_list['shops_img'] = Yii::app()->params['admin_img_domain'] . ltrim($img1, '.');
					break;
				}
				else if ($img2 != '')
				{
					$return_list['shops_img'] = Yii::app()->params['admin_img_domain'] . ltrim($img2, '.');
					break;
				}
			}			
			if ($return_list['shops_img'] == '')
			{
				foreach ($model->Order_OrderItems as $Order_OrderItems)
				{
					$img = $this->litimg_path($Order_OrderItems->items_img);
					if ($img != '')
					{
						$return_list['shops_img'] = Yii::app()->params['admin_img_domain'] . ltrim($img, '.');
						break;
					}
				}
			}
			if(($model->order_type == Order::order_type_actives_tour || $model->order_type == Order::order_type_actives_tour_full) && isset($model->Order_OrderActives->OrderActives_Actives->actives_status))
			{
				$return_list['actives_status'] = array(
					'name'=>Actives::$_actives_status[$model->Order_OrderActives->OrderActives_Actives->actives_status],
					'value'=>$model->Order_OrderActives->OrderActives_Actives->actives_status,
				);
				// 是否是组织者
				$return_list['is_organizer'] = array(
						'name'=>Actives::$_is_organizer[$model->Order_OrderActives->OrderActives_Actives->is_organizer],
						'value'=>$model->Order_OrderActives->OrderActives_Actives->is_organizer,
				);
				$return_list['go_time'] = $model->Order_OrderActives->OrderActives_Actives->go_time==0?'出游日期未定':date('Y-m-d',$model->Order_OrderActives->OrderActives_Actives->go_time);
			}
			$return_list['order_no'] = $model->order_no;
			$return_list['value'] = $model->id;
			$return_list['link'] = Yii::app()->params['app_api_domain'].Yii::app()->createUrl('/api/order/view',array('id'=>$model->id));
			//支付类型
			$return_list['pay_type']=Yii::app()->params['pay_type'];
			//状态
			$return_list['status']=array(
					'pay_type'=>array(
							'name'=>Order::$_pay_type[$model->pay_type],
							'value'=>$model->pay_type,
					),
					'order_status'=>array(
							'name'=>Order::$_order_status[$model->order_status],
							'value'=>$model->order_status,
					),
					'status_go'=>array(
							'name'=>Order::$_status_go[$model->status_go],
							'value'=>$model->status_go,
					),
					'centre_status'=>array(
							'name'=>Order::$_centre_status[$model->centre_status],
							'value'=>$model->centre_status,
					),
					'pay_status'=>array(
							'name'=>Order::$_pay_status[$model->pay_status],
							'value'=>$model->pay_status,
					),
					'order_type'=>array(
							'name'=>Order::$_order_type[$model->order_type],
							'value'=>$model->order_type,
					),
					'status'=>array(
							'name'=>Order::$_status[$model->status],
							'value'=>$model->status,
					)
				);
			$return[] = $return_list;
		}
		return $return;
	}
	
}
