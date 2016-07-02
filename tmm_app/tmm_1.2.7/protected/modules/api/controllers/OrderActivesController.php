<?php
/**
 * 
 * @author Changhai Zhan
 *	创建时间：2015-10-26 13:41:03 */
class OrderActivesController extends ApiController
{
	/**
	 * 默认操作数据模型
	 * @var string
	 */
	public $_class_model='OrderActives';
	
	/**
	 * 查看详情 上线的详情页
	 * @param integer $id
	 */
	public function actionView($id)
	{
		$criteria = new CDbCriteria;
		$criteria->with=array(
			'OrderActives_User'=>array('with'=>'User_Organizer'),
			'OrderActives_Actives'=>array('with'=>array('Actives_Shops')),
			'OrderActives_OrderItems'=>array(
					'with'=>array(
						'OrderItems_ItemsClassliy',
						'OrderItems_OrderItemsFare',
					),
			),
		);
		$criteria->order = 'OrderActives_OrderItems.shops_day_sort,OrderActives_OrderItems.shops_half_sort,OrderActives_OrderItems.shops_sort';
		$criteria->addCondition('`Actives_Shops`.`status` != :del');						//删除的
		$criteria->params[':del'] = Shops::status_del;	
		$criteria->addColumnCondition(array(
				'`OrderActives_User`.`status`'=>User::status_suc,								//用户正常
				'`t`.`organizer_id`'=>Yii::app()->api->id,
				'`OrderActives_Actives`.`actives_type`'=>Actives::actives_type_tour,//活动分类（旅游）
				'`Actives_Shops`.`audit`'=>Shops::audit_pass,									//审核通过
		));
		$model=$this->loadModel($id,$criteria);
		if(! $model->OrderActives_Actives ||  !$model->OrderActives_OrderItems || !isset($model->OrderActives_OrderItems[0]->OrderItems_OrderItemsFare))
		{
			$this->send_error(DATA_NULL);
		}
		
		$criteria_order = new CDbCriteria;
		$criteria_order->select='*,sum(`user_go_count`) as `money_count`';
		$criteria_order->group='order_status';
		$criteria_order->addColumnCondition(array(
			'order_organizer_id'=>$model->id,
			'status'=>Order::status_yes,
		));
		$model->OrderActives_Order = Order::model()->findAll($criteria_order);
		
		$return  = array();
		// 2016-02-29  前端要活动ID
		$return['actives_id'] = $model->OrderActives_Actives->id;
		$return['name']=CHtml::encode($model->OrderActives_Actives->Actives_Shops->name);
		$return['list_info']=CHtml::encode($model->OrderActives_Actives->Actives_Shops->list_info);
		$return['page_info']=CHtml::encode($model->OrderActives_Actives->Actives_Shops->page_info);
		$return['price']=Shops::get_price_num($model->OrderActives_Actives->id,'Actives',true);
		$return['down']=Shops::get_down($model->OrderActives_Actives->id,'Actives');
		if ($model->OrderActives_Actives->is_open == Actives::is_open_yes)
		{
			$return['shops']=array(
				'value'=>$model->OrderActives_Actives->id,
				'link'=>Yii::app()->params['app_api_domain'].Yii::app()->createUrl('/api/actives/view',array('id'=>$model->OrderActives_Actives->id)),
			);
		}
		else if ($model->OrderActives_Actives->is_open == Actives::is_open_no)
		{
			$return['shops']=array(
					'value'=>$model->OrderActives_Actives->barcode,
					'link'=>Yii::app()->params['app_api_domain'].Yii::app()->createUrl('/api/actives/view',array('id'=>$model->OrderActives_Actives->barcode)),
			);
		}
		$img=$this->litimg_path($model->OrderActives_Actives->Actives_Shops->list_img);
		$return['image']=empty($img)?'':Yii::app()->params['admin_img_domain'].ltrim($img,'.');
		//扫描 码
		$return['actives_barcode']=$model->OrderActives_Actives->barcode;
		//上 下线
		$return['shops_status']=array(
			'name'=>Shops::$_status[$model->OrderActives_Actives->Actives_Shops->status],
			'value'=>$model->OrderActives_Actives->Actives_Shops->status,
		);
		//支付方式
		$return['actives_pay_type'] = array(
			'name'=>Actives::$_pay_type[$model->OrderActives_Actives->pay_type],
			'value'=>$model->OrderActives_Actives->pay_type,
		);
		// 显示方式
		$return['actives_is_open'] = array(
			'name'=>Actives::$_is_open[$model->OrderActives_Actives->is_open],
			'value'=>$model->OrderActives_Actives->is_open,
		);
		//活动类型
		$return['actives'] = array(
				'name'=>Actives::$_actives_type[$model->OrderActives_Actives->actives_type],
				'value'=>$model->OrderActives_Actives->actives_type,
		);
		// 是否是组织者
		$return['is_organizer'] = array(
				'name'=>Actives::$_is_organizer[$model->OrderActives_Actives->is_organizer],
				'value'=>$model->OrderActives_Actives->is_organizer,
		);
		$return['tour_type'] = array(
				'name'=>Actives::$_tour_type[$model->OrderActives_Actives->tour_type],
				'value'=>$model->OrderActives_Actives->tour_type,
		);
		//设置出游时间
		$return['set_go_time'] = array(
				'value'=>$model->OrderActives_Actives->go_time == 0 ? $model->id:'',
				'link'=>$model->OrderActives_Actives->go_time == 0 ? Yii::app()->params['app_api_domain'].Yii::app()->createUrl('/api/orderActives/gotime',array('id'=>$model->id)):'',
		);
		//活动简介
		$return['actives_info'] = array(
			'remark'=>$model->OrderActives_Actives->remark,//CHtml::encode($model->OrderActives_Actives->remark),
			'actives_no'=>$model->actives_no,
			'number'=>array(
				'name'=>'觅趣人数',
				'value'=>$model->OrderActives_Actives->number,
			),
			'order_number'=>array(
				'name'=>'剩余报名人数',
				'value'=>$model->OrderActives_Actives->order_number,
			),
			'tour_price'=>array(
				'name'=>'服务费/人',
				'value'=>$model->OrderActives_Actives->tour_price,
				'count'=>$this->money_floor($model->OrderActives_Actives->tour_price*$model->user_go_count),
			),
			'tour_count'=>array(
				'name'=>'报名统计',
				'value'=>$model->OrderActives_Actives->tour_count,
			),
			'order_count'=>array(
				'name'=>'付款统计',
				'value'=>$model->OrderActives_Actives->order_count,
			),
			'user_order_count'=>array(
				'name'=>$model->getAttributeLabel('user_order_count'),
				'value'=>$model->user_order_count,
			),
			'user_pay_count'=>array(
				'name'=>$model->getAttributeLabel('user_pay_count'),
				'value'=>$model->user_pay_count,
			),
			'user_submit_count'=>array(
				'name'=>$model->getAttributeLabel('user_submit_count'),
				'value'=>$model->user_submit_count,
			),
			'user_go_count'=>array(
				'name'=>$model->getAttributeLabel('user_go_count'),
				'value'=>$model->user_go_count,
			),
			'user_price_count'=>array(
				'name'=>$model->getAttributeLabel('user_price_count'),
				'value'=>$model->user_price_count,
			),
			'total'=>array(
				'name'=>$model->getAttributeLabel('total'),
				'value'=>$model->total,
			),
		);
		//AA
		if ($model->OrderActives_Actives->pay_type == Actives::pay_type_AA)
		{
			$return['actives_info']['order_list'] = Yii::app()->params['app_api_domain'].Yii::app()->createUrl('/api/orderActives/list',array('id'=>$model->id));
		}
		else if ($model->OrderActives_Actives->pay_type == Actives::pay_type_full)
		{
			$return['actives_info']['order'] = array();
			if ($model->user_order_count == 0) 		//没有创建订单
			{
				$return['actives_info']['order'] = array(
						'type'=>0,
						'link'=>$model->OrderActives_Actives->go_time == 0 ||  date('Y-m-d', $model->OrderActives_Actives->go_time) > date('Y-m-d') ? Yii::app()->params['app_api_domain'].Yii::app()->createUrl('/api/attend/order') : '',
						'value'=>$model->OrderActives_Actives->go_time == 0 ||  date('Y-m-d', $model->OrderActives_Actives->go_time) > date('Y-m-d') ? $model->OrderActives_Actives->id : '',
				);
			}
			else if ($model->user_pay_count == 0)	//创建未支付
			{
				$orderModel = OrderActives::getOrderActivesFull($model->id);
				if ($orderModel)
				{
					$return['actives_info']['order'] = array(
							'type'=>1,
							'link'=>$model->OrderActives_Actives->go_time == 0 ||  date('Y-m-d', $model->OrderActives_Actives->go_time) > date('Y-m-d') ? Yii::app()->params['app_api_domain'].Yii::app()->createUrl('/api/order/payment') : '',
							'value'=>$model->OrderActives_Actives->go_time == 0 ||  date('Y-m-d', $model->OrderActives_Actives->go_time) > date('Y-m-d') ? $orderModel->id : '',
					);
				}
			}
			$attend = Attend::getMainAttend($model->OrderActives_Actives->id);
			if ($attend)
			{
				$return['actives_info']['attend']['main'] = array(
					'name' => $attend->name,
					'phone' => $attend->phone,
					'link' => Yii::app()->params['app_api_domain'].Yii::app()->createUrl('/api/attend/name',array('id'=>$model->OrderActives_Actives->id)),
				);
			}
			$return['actives_info']['attend']['number'] = Attend::getColumnCount($model->OrderActives_Actives->id);
			$return['actives_info']['attend']['people'] = Attend::getColumnCount($model->OrderActives_Actives->id,1);
			$return['actives_info']['attend']['children'] = Attend::getColumnCount($model->OrderActives_Actives->id,2);
			$return['actives_info']['attend_list'] = Yii::app()->params['app_api_domain'].Yii::app()->createUrl('/api/attend/list',array('id'=>$model->OrderActives_Actives->id));
		}
		//活动时间
		$return['actives_time']=array(
				'start_time'=>date('Y-m-d',$model->OrderActives_Actives->start_time),
				'end_time'=>date('Y-m-d',$model->OrderActives_Actives->end_time),
				'go_time'=>$model->OrderActives_Actives->go_time==0 ? '出游日期未定' : date('Y-m-d',$model->OrderActives_Actives->go_time),
				'go_time_value'=>$model->OrderActives_Actives->go_time==0 ? 0 : 1,
				'add_time'=>date('Y-m-d H:i:s',$model->OrderActives_Actives->Actives_Shops->add_time),
				'audit_time'=>date('Y-m-d H:i:s',$model->add_time),
		);
		//活动状态
		$return['actives_status']=array(
			'name'=>Actives::$_actives_status[$model->OrderActives_Actives->actives_status],
			'value'=>$model->OrderActives_Actives->actives_status,
		);
		$return['audit_status']=array(
				'name'=>Shops::$_audit[$model->OrderActives_Actives->Actives_Shops->audit],
				'value'=>$model->OrderActives_Actives->Actives_Shops->audit,
		);
		//活动报名情况
		foreach ($model->OrderActives_Order as $order)
		{			
			$return['actives_number'][$order->order_status]=array(
				'name'=>Order::$_order_status[$order->order_status],
				'value'=>$order->order_status,
				'count'=>$order->money_count,
			);
		}
		//活动详情
		foreach ($model->OrderActives_OrderItems as $items)
		{
			if(!isset($items->OrderItems_ItemsClassliy))
				$this->send_error(DATA_NULL);
			//项目图片
			$img = $this->litimg_path($items->items_img);
			$return['items_fare'][$items->shops_day_sort][$items->shops_half_sort][$items->shops_sort]=array(
					'name'=>CHtml::encode($items->items_name),
					'value'=>$items->items_id,
					'count'=>$items->total,
					'image'=>empty($img)?'':Yii::app()->params['admin_img_domain'].ltrim($img,'.'),
					'link'=>Yii::app()->params['app_api_domain'].Yii::app()->createUrl('/api/'.$items->OrderItems_ItemsClassliy->admin.'/view',array('id'=>$items->items_id)),
					'classliy'=>array(
							'name'=>$items->items_c_name,
							'value'=>$items->items_c_id,
					),
					'address'=>CHtml::encode($items->items_address),
					'barcode'=>array(
							'is_barcode'=>$items->is_barcode,
							'barcode_name'=>OrderItems::$_is_barcode[$items->is_barcode],
							'employ_time'=>$items->employ_time==0?'':date('Y-m-d H:i:s',$items->employ_time),
							'value'=>$items->id,
							'link'=>Yii::app()->params['app_api_domain'].Yii::app()->createUrl('/api/order/barcode',array('id'=>$items->id)),
					),
			);
			if($items->shops_half_sort==0 && $items->shops_sort==0)
				$return['day_info'][$items->shops_day_sort]=$items->shops_info;
			
			if( !isset($items->OrderItems_OrderItemsFare) || empty($items->OrderItems_OrderItemsFare) || !is_array($items->OrderItems_OrderItemsFare))
				$this->send_error(DATA_NULL);
			foreach ($items->OrderItems_OrderItemsFare as $fare)
			{
				// 价格信息
				$return['items_fare'][$items->shops_day_sort][$items->shops_half_sort][$items->shops_sort]['fare'][]=array(
						'name' => CHtml::encode($fare->fare_name),
						'info' =>$fare->fare_info,
						'info_value'=>isset(Fare::$__info[$fare->fare_info]) ? Fare::$__info[$fare->fare_info] : 1,
						'room_number'=>$fare->fare_number,
						'number' => $fare->number,
						'price' => $fare->fare_price,
						'count'=>$fare->total,
				);
			}
		}
		if(isset($return['actives_number']))
			$return['actives_number']=array_values($return['actives_number']);
		$this->send($return);
	}
	
	/**
	 * 没有审核通过的详情页
	 * @param integer $id
	 */
	public function actionOldview($id)
	{
		$criteria = new CDbCriteria;
		$criteria->with=array(
				'Actives_User'=>array('with'=>'User_Organizer'),
				'Actives_Shops',
				'Actives_ShopsClassliy',
				'Actives_ShopsInfo',
				'Actives_Pro'=>array(
					'with'=>array(
						'Pro_Actives_Thrand',
						'Pro_Actives_Dot'=>array('with'=>array('Dot_Shops')),
						'Pro_ItemsClassliy',
						'Pro_ProFare'=>array('with'=>array('ProFare_Fare')),
						'Pro_Items'=>array(
								'with'=>array(
										'Items_area_id_p_Area_id',
										'Items_area_id_m_Area_id',
										'Items_area_id_c_Area_id',
										'Items_ItemsImg',
								),
						),
					)
				)
		);
		$criteria->order = 'Actives_Pro.day_sort,Actives_Pro.half_sort,Actives_Pro.sort';

		//商品审核
		$column='`Actives_Shops`.`audit`';
		$criteria->addCondition($column.'=:audit_pass OR '.$column.'=:audit_pending OR '.$column.'=:audit_nopass OR '.$column.'=:audit_store_pending OR '.$column.'=:audit_store_nopass');
		$criteria->params[':audit_pending']=Shops::audit_pending;							//审核中
		$criteria->params[':audit_nopass']=Shops::audit_nopass;								//审核失败
		$criteria->params[':audit_store_pending']=Shops::audit_store_pending;		//商家审核中
		$criteria->params[':audit_store_nopass']=Shops::audit_store_nopass;			//商家审核失败
		$criteria->params[':audit_pass'] = Shops::audit_pass;									//审核通过
	
		$criteria->addColumnCondition(array(
			'`Actives_User`.`status`'=>User::status_suc,												//用户正常
			'`t`.`organizer_id`'=>Yii::app()->api->id,														//归属组织者
			'`Actives_Shops`.`status`'=>Shops::status_offline,										//商品下线
			'`t`.`status`'=>Actives::status_not_publish,													//商品没有发布
		));	
		
		$this->_class_model='Actives';
		$model=$this->loadModel($id,$criteria);

		if(! $model->Actives_User ||  !$model->Actives_Shops || !isset($model->Actives_Pro[0]->Pro_Items))
		{
			$this->send_error(DATA_NULL);
		}
		//返回数据
		$return  = array();
		// 2016-02-29  前端要活动ID
		$return['actives_id'] = $id;
		$return['thrand_id'] = isset($model->Actives_Pro[0]->thrand_id) ? CHtml::encode($model->Actives_Pro[0]->thrand_id) : '';
		$return['name'] = CHtml::encode($model->Actives_Shops->name);
		$return['list_info']	= CHtml::encode($model->Actives_Shops->list_info);
		$return['page_info']  = CHtml::encode($model->Actives_Shops->page_info);
		$return['shops_info'] = isset($model->Actives_ShopsInfo->info) && $model->Actives_ShopsInfo->info ? CHtml::encode($model->Actives_ShopsInfo->info) : '';
		//公开的
		if ($model->is_open == Actives::is_open_yes)
		{
			$return['shops']=array(
				'value'=>$model->id,
				'link'=>Yii::app()->params['app_api_domain'].Yii::app()->createUrl('/api/actives/view',array('id'=>$model->id)),
			);
		}
		else if ($model->is_open == Actives::is_open_no)
		{
			$return['shops']=array(
					'value'=>$model->barcode,
					'link'=>Yii::app()->params['app_api_domain'].Yii::app()->createUrl('/api/actives/view',array('id'=>$model->barcode)),
			);
		}
		$return['price']=Shops::get_price_num($model->id,'Actives');//多少起
		$return['down']=Shops::get_down($model->id,'Actives',false);//下单量
		
		$img=$this->litimg_path($model->Actives_Shops->list_img);
		$return['image']=empty($img)?'':Yii::app()->params['admin_img_domain'].ltrim($img,'.');
		//扫描 码
		$return['actives_barcode']=$model->barcode;
		//上 下线
		$return['shops_status']=array(
			'name'=>Shops::$_status[$model->Actives_Shops->status],
			'value'=>$model->Actives_Shops->status,
		);
		//支付方式
		$return['actives_pay_type'] = array(
			'name'=>Actives::$_pay_type[$model->pay_type],
			'value'=>$model->pay_type,
		);
		// 显示方式
		$return['actives_is_open'] = array(
			'name'=>Actives::$_is_open[$model->is_open],
			'value'=>$model->is_open,
		);
		//活动类型
		$return['actives']=array(
				'name'=>Actives::$_actives_type[$model->actives_type],
				'value'=>$model->actives_type,
		);
		// 是否是组织者
		$return['is_organizer'] = array(
				'name'=>Actives::$_is_organizer[$model->is_organizer],
				'value'=>$model->is_organizer,
		);
		$return['tour_type']=array(
				'name'=>Actives::$_tour_type[$model->tour_type],
				'value'=>$model->tour_type,
		);
		$return['audit_status']=array(
				'name'=>Shops::$_audit[$model->Actives_Shops->audit],
				'value'=>$model->Actives_Shops->audit,
		);
		if($model->Actives_Shops->audit == Shops::audit_nopass || $model->Actives_Shops->audit == Shops::audit_store_nopass)
		{
			$return['audit_info']=AuditLog::get_audit_log(AuditLog::shops_actives,$model->id)->info;
		}
		
		//设置出游时间
		$return['set_go_time']=array(
				'value'=>'',
				'link'=>'',
		);
		
		//活动简介
		$return['actives_info']=array(
			'remark'=>$model->remark,//CHtml::encode($model->remark),
			'actives_no'=>'',
			'order_list'=>'',
			'number'=>array(
				'name'=>'觅趣人数',
				'value'=>$model->number,
			),
			'order_number'=>array(
				'name'=>'剩余报名人数',
				'value'=>$model->order_number,
			),
			'tour_price'=>array(
				'name'=>'服务费/人',
				'value'=>$model->tour_price,
				'count'=>'0.00',
			),
			'tour_count'=>array(
				'name'=>'报名统计',
				'value'=>$model->tour_count,
			),
			'order_count'=>array(
				'name'=>'付款统计',
				'value'=>$model->order_count,
			)
		);
		//活动时间
		$return['actives_time']=array(
				'start_time'=>date('Y-m-d',$model->start_time),
				'end_time'=>date('Y-m-d',$model->end_time),
				'go_time'=>$model->go_time==0 ? '出游日期未定' : date('Y-m-d',$model->go_time),
				'go_time_value'=>$model->go_time==0 ? 0 : 1,
				'add_time'=>date('Y-m-d H:i:s',$model->Actives_Shops->add_time),
		);
		$return['classliy']=array(
				'name'=>$model->Actives_ShopsClassliy->name,
				'value'=>$model->Actives_ShopsClassliy->id,
		);
		//活动状态
		$return['actives_status']=array(
			'name'=>Actives::$_actives_status[$model->actives_status],
			'value'=>$model->actives_status,
		);
		//活动详情
		foreach ($model->Actives_Pro as $pro)
		{
			if(!isset($pro->Pro_ItemsClassliy))
				$this->send_error(DATA_NULL);
			//项目图片
			if(isset($pro->Pro_Items->Items_ItemsImg[0]->img))
				$img=$this->litimg_path($pro->Pro_Items->Items_ItemsImg[0]->img);
			else 
				$img='';
			$return['items_fare'][$pro->day_sort][$pro->half_sort][$pro->sort]=array(
					'name'=>CHtml::encode($pro->Pro_Items->name),
					'value'=>$pro->Pro_Items->id,
					//地址状态
					'address_arr' => array(
						'province'=> CHtml::encode($pro->Pro_Items->Items_area_id_p_Area_id->name),
						'city'=> CHtml::encode($pro->Pro_Items->Items_area_id_m_Area_id->name),
						'area'=> CHtml::encode($pro->Pro_Items->Items_area_id_c_Area_id->name),
						'address'=> CHtml::encode($pro->Pro_Items->address),
					),
					'address'=>CHtml::encode($pro->Pro_Items->Items_area_id_p_Area_id->name.$pro->Pro_Items->Items_area_id_m_Area_id->name.$pro->Pro_Items->Items_area_id_c_Area_id->name.$pro->Pro_Items->address),
					'image'=>empty($img)?'':Yii::app()->params['admin_img_domain'].ltrim($img,'.'),
					'link'=>Yii::app()->params['app_api_domain'].Yii::app()->createUrl('/api/'.$pro->Pro_ItemsClassliy->admin.'/view',array('id'=>$pro->Pro_Items->id)),
					'classliy'=>array(
							'name'=>$pro->Pro_ItemsClassliy->name,
							'value'=>$pro->Pro_ItemsClassliy->id,
					),
			);
			
			$return['update_data'][$pro->day_sort][$pro->half_sort]['name']    = CHtml::encode($pro->Pro_Actives_Dot->Dot_Shops->name);
			$return['update_data'][$pro->day_sort][$pro->half_sort]['dot_id']    = CHtml::encode($pro->Pro_Actives_Dot->id);
			$return['update_data'][$pro->day_sort][$pro->half_sort]['address'] = CHtml::encode($pro->Pro_Items->Items_area_id_p_Area_id->name.$pro->Pro_Items->Items_area_id_m_Area_id->name.$pro->Pro_Items->Items_area_id_c_Area_id->name.$pro->Pro_Items->address);

			$return['update_data'][$pro->day_sort][$pro->half_sort]['list'][$pro->sort]=array(
					'item_name'=>CHtml::encode($pro->Pro_Items->name),
					'item_id'=>CHtml::encode($pro->Pro_Items->id),
					'item_link'=>Yii::app()->params['app_api_domain'].Yii::app()->createUrl('/api/'.$pro->Pro_ItemsClassliy->admin.'/view',array('id'=>$pro->Pro_Items->id)),
					'item_type'=>array(
							'name'=>$pro->Pro_ItemsClassliy->name,
							'value'=>$pro->Pro_ItemsClassliy->id,
					),
			);

			if( !isset($pro->Pro_ProFare) || empty($pro->Pro_ProFare) || !is_array($pro->Pro_ProFare))
				$this->send_error(DATA_NULL);
			foreach ($pro->Pro_ProFare as $fare)
			{
				// 价格信息
				$return['items_fare'][$pro->day_sort][$pro->half_sort][$pro->sort]['fare'][]=array(
						'name' =>CHtml::encode($fare->ProFare_Fare->name),
						'info' =>CHtml::encode($fare->ProFare_Fare->info),
						'room_number'=>CHtml::encode($fare->ProFare_Fare->number),
						'price' =>CHtml::encode($fare->ProFare_Fare->price),
				);			
		
				if($pro->Pro_ItemsClassliy->id == Items::items_hotel)
				{
					$return['update_data'][$pro->day_sort][$pro->half_sort]['list'][$pro->sort]['fare'][]=array(
							'id'=>CHtml::encode($fare->ProFare_Fare->id),
							'name' =>CHtml::encode($fare->ProFare_Fare->name),
							'info' =>CHtml::encode($fare->ProFare_Fare->info).' 平方',
							'number'=>CHtml::encode($fare->ProFare_Fare->number==1?'单':($fare->ProFare_Fare->number==2?'双':$fare->ProFare_Fare->number)).' 人间',
							'price' =>CHtml::encode($fare->ProFare_Fare->price).' 元',
					);
				}
				else
				{
					$return['update_data'][$pro->day_sort][$pro->half_sort]['list'][$pro->sort]['fare'][]=array(
							'id'=>CHtml::encode($fare->ProFare_Fare->id),
							'name' =>CHtml::encode($fare->ProFare_Fare->name),
							'info' =>CHtml::encode($fare->ProFare_Fare->info),
						//	'number'=>CHtml::encode($fare->ProFare_Fare->number),
							'price' =>CHtml::encode($fare->ProFare_Fare->price).' 元',
					);
				}
			}
		}
		$this->send($return);
	}
	
	/**
	 * 组织者活动列表
	 */
	public function actionIndex()
	{
		$criteria=new CDbCriteria;
		$criteria->with=array(
				'Actives_User'=>array('with'=>'User_Organizer'),
				'Actives_Shops',																					//商品主表
				'Actives_OrderActives',																		//活动总单
		);	
		//活动的状态 上线 下线 状态
		$column_status='`Actives_Shops`.`status`';
		$criteria->addCondition($column_status.'=:status_online OR '.$column_status.'=:status_offline');
		$criteria->params[':status_online'] = Shops::status_online;						//上线
		$criteria->params[':status_offline'] = Shops::status_offline;						//下线
		//审核状态
		$column_audit='`Actives_Shops`.`audit`';
		$criteria->addCondition($column_audit.'=:audit_nopass OR '.$column_audit.'=:audit_pass OR '.$column_audit.'=:audit_pending OR '.$column_audit.'=:audit_store_nopass OR '.$column_audit.'=:audit_store_pending');
		$criteria->params[':audit_nopass']=Shops::audit_nopass;							//平台审核未通过
		$criteria->params[':audit_pass']=Shops::audit_pass;									//平台审核通过
		$criteria->params[':audit_pending']=Shops::audit_pending;						//平台审核中（商家审核通过）
		$criteria->params[':audit_store_nopass']=Shops::audit_store_nopass;		//商家审核未通过
		$criteria->params[':audit_store_pending']=Shops::audit_store_pending;//商家审核中
		//搜索条件
		$this->search_info($criteria);
		//标准条件
		$criteria->addColumnCondition(array(
			'`Actives_User`.`status`'=>User::status_suc,			//用户正常
			'`t`.`organizer_id`'=>Yii::app()->api->id,					//归属用户 或 组织者
		));
		$criteria->order='`Actives_Shops`.`up_time` desc,`t`.`start_time`,`t`.`go_time`';
		//统计
		$count = Actives::model()->count($criteria);
		//输出数据
		$return=array();
		//分页设置
		$return['page']=$this->page($criteria, $count, Yii::app()->params['api_pageSize']['actives'], Yii::app()->params['app_api_domain']);
		//根据条件查询
		$models = Actives::model()->findAll($criteria);
		//分页数据
		$return['list_data']=$this->index_data($models, Yii::app()->params['app_api_domain']);
		//没有找到数据
		if(empty($return['list_data']))
		{
			$return['list_data']=array();
			$return['null']='小觅已经很努力了！';
		}
		$return['search_name']='search_info';
		$this->send($return);
	}
	
	/**
	 * 组织者活动列表 数据处理
	 * @param unknown $models
	 */
	public function index_data($models,$domain)
	{
		$price = 0.00;
		$down = 0;
		$return = array();
		foreach ($models as $model)
		{
			$order = $set_go_time = $actives_status = $attend = array();
			$img = $this->litimg_path($model->Actives_Shops->list_img);
			$type = '0';//活动的模板
			//是否有活动
			if(isset($model->Actives_OrderActives) && $model->Actives_OrderActives)
			{
				$value = $model->Actives_OrderActives->id;
				$type = '1';
				$link = $domain.Yii::app()->createUrl('/api/orderActives/view',array('id'=>$value));
				$update=array(
						'link'=>'',
						'value'=>'',
				);
				$price = Shops::get_price_num($model->Actives_Shops->id,'Actives',true);
				$down = Shops::get_down($value,'Actives');
				$actives_status = array(
						'name'=>Actives::$_actives_status[$model->actives_status],
						'value'=>$model->actives_status,
				);
				//活动内容
				$actives_info = array(
					'number'=>array(
							'name'=>'觅趣人数',
							'value'=>$model->number,
					),
					'order_number'=>array(
							'name'=>'剩余报名人数',
							'value'=>$model->order_number,
					),
					'tour_price'=>array(
							'name'=>'服务费/人',
							'value'=>$model->tour_price,
							'count'=>$this->money_floor($model->tour_price*$model->Actives_OrderActives->user_go_count),
					),
					'tour_count'=>array(
							'name'=>'报名统计',
							'value'=>$model->tour_count,
					),
					'order_count'=>array(
							'name'=>'付款统计',
							'value'=>$model->order_count,
					),
					'user_order_count'=>array(
							'name'=>$model->getAttributeLabel('Actives_OrderActives.user_order_count'),
							'value'=>$model->Actives_OrderActives->user_order_count,
					),
					'user_pay_count'=>array(
							'name'=>$model->getAttributeLabel('Actives_OrderActives.user_pay_count'),
							'value'=>$model->Actives_OrderActives->user_pay_count,
					),
					'user_submit_count'=>array(
							'name'=>$model->getAttributeLabel('Actives_OrderActives.user_submit_count'),
							'value'=>$model->Actives_OrderActives->user_submit_count,
					),
					'user_go_count'=>array(
							'name'=>$model->getAttributeLabel('Actives_OrderActives.user_go_count'),
							'value'=>$model->Actives_OrderActives->user_go_count,
					),
					'user_price_count'=>array(
							'name'=>$model->getAttributeLabel('Actives_OrderActives.user_price_count'),
							'value'=>$model->Actives_OrderActives->user_price_count,
					),
					'total'=>array(
							'name'=>$model->getAttributeLabel('Actives_OrderActives.total'),
							'value'=>$model->Actives_OrderActives->total,
					),
				);
				//设置出游时间
				$set_go_time = array(
						'value'=>$model->go_time == 0 ? $value:'',
						'link'=>$model->go_time == 0 ? $domain.Yii::app()->createUrl('/api/orderActives/gotime',array('id'=>$value)):'',
				);
				//订单列表
				if($model->actives_status==Actives::actives_status_start || $model->actives_status==Actives::actives_status_end)
				{
					if ($model->pay_type == Actives::pay_type_AA)
					{
						$list =array(
								'value'=>$value,
								'link'=>$domain.Yii::app()->createUrl('/api/orderActives/list',array('id'=>$value)),
						);
					}
					else if($model->pay_type == Actives::pay_type_full)
					{
						if ($model->Actives_OrderActives->user_order_count == 0) 		//没有创建订单
						{
							$order = array(
									'type'=>0,		
									'link'=>$model->go_time == 0 ||  date('Y-m-d', $model->go_time) > date('Y-m-d') ? $domain.Yii::app()->createUrl('/api/attend/order') : '',
									'value'=>$model->go_time == 0 ||  date('Y-m-d', $model->go_time) > date('Y-m-d') ? $model->id : '',
							);
						}
						else if ($model->Actives_OrderActives->user_pay_count == 0)	//创建未支付
						{
							$orderModel = OrderActives::getOrderActivesFull($model->Actives_OrderActives->id);
							if ($orderModel)
							{
								$order = array(
										'type'=>1,
										'link'=>$model->go_time == 0 ||  date('Y-m-d', $model->go_time) > date('Y-m-d') ? $domain.Yii::app()->createUrl('/api/order/payment') : '',
										'value'=>$model->go_time == 0 ||  date('Y-m-d', $model->go_time) > date('Y-m-d') ? $orderModel->id : '',
								);
							}
						}
						$attend['number'] = Attend::getColumnCount($model->id);
						$attend['people'] = Attend::getColumnCount($model->id,1);
						$attend['children'] = Attend::getColumnCount($model->id,2);
						$list = array(
								'value'=>$model->id,
								'link'=>$domain.Yii::app()->createUrl('/api/attend/list',array('id'=>$model->id)),
						);
					}
				}
				else
				{
					$list = array(
							'value'=>'',
							'link'=>'',
					);
				}
			}
			else
			{
				$value = $model->Actives_Shops->id;			
				$price = Shops::get_price_num($value,'Actives');
				$down = Shops::get_down($value,'Actives',false);
				//活动修改
				if($model->Actives_Shops->audit==Shops::audit_nopass || $model->Actives_Shops->audit==Shops::audit_store_nopass)
				{
					$update=array(
							'link'=>$domain.Yii::app()->createUrl('/api/actives/update',array('id'=>$value)),
							'value'=>$value,
					);
				}
				else
				{
					$update = array(
							'link'=>'',
							'value'=>'',
					);
				}
				$actives_status = array(
					'name'=>Actives::$_actives_status[$model->actives_status],
					'value'=>$model->actives_status,
				);
				//活动内容
				$actives_info = array(
						'number'=>array(
								'name'=>'觅趣人数',
								'value'=>$model->number,
						),
						'order_number'=>array(
								'name'=>'剩余报名人数',
								'value'=>$model->order_number,
						),
						'tour_price'=>array(
								'name'=>'服务费/人',
								'value'=>$model->tour_price,
								'count'=>'0.00',
						),
						'tour_count'=>array(
								'name'=>'报名统计',
								'value'=>$model->tour_count,
						),
						'order_count'=>array(
								'name'=>'付款统计',
								'value'=>$model->order_count,
						),
					);
				$link=$domain.Yii::app()->createUrl('/api/orderActives/oldview',array('id'=>$value));
				$set_go_time=array(
						'link'=>'',
						'value'=>'',
				);
				$list=array(
						'value'=>'',
						'link'=>'',
				);
			}
			//公开的
			if ($model->is_open == Actives::is_open_yes)
			{
				$shops = array(
						'value'=>$model->id,
						'link'=>Yii::app()->params['app_api_domain'].Yii::app()->createUrl('/api/actives/view',array('id'=>$model->id)),
				);
			}
			else if ($model->is_open == Actives::is_open_no)
			{
				$shops = array(
						'value'=>$model->barcode,
						'link'=>Yii::app()->params['app_api_domain'].Yii::app()->createUrl('/api/actives/view',array('id'=>$model->barcode)),
				);
			}
			$return[] = array(
					'collect_count'=> Collect::get_collect_count(Collect::collect_type_praise,$model->Actives_Shops->id),
					'brow'=>$model->Actives_Shops->brow,
					'share'=>$model->Actives_Shops->share,
					'praise'=>$model->Actives_Shops->praise,
					'value'=>$value,
					'link'=>$link,
					'list'=>$list,
					'attend'=>$attend,
					'type'=>$type,
					'price'=>$price,
					'down'=>$down,
					'update'=>$update,
					'barcode'=>$model->barcode,
					'shops'=>$shops,
					'name'=>CHtml::encode($model->Actives_Shops->name),
					'info'=>CHtml::encode($model->Actives_Shops->list_info),
					'image'=>empty($img)?'':Yii::app()->params['admin_img_domain'].ltrim($img,'.'),
					'actives_type'=>array(
							'name'=>$model->actives_type==Actives::actives_type_tour ? '觅趣' : '觅鲜',
							'value'=>$model->actives_type,
					),
					// 是否是组织者
					'is_organizer' => array(
							'name'=>Actives::$_is_organizer[$model->is_organizer],
							'value'=>$model->is_organizer,
					),
					'pay_type'=>array(
							'name'=>Actives::$_pay_type[$model->pay_type],
							'value'=>$model->pay_type,
					),
					'tour_type' =>array(
							'name'=>Actives::$_tour_type[$model->tour_type],
							'value'=>$model->tour_type,
					),
					'order'=>$order,
					'actives_status'=>$actives_status,
					'audit_status'=>array(
							'name'=>Shops::$_audit[$model->Actives_Shops->audit],
							'value'=>$model->Actives_Shops->audit,
							'info'=>($model->Actives_Shops->audit == Shops::audit_nopass || $model->Actives_Shops->audit == Shops::audit_store_nopass) ? AuditLog::get_audit_log(AuditLog::shops_actives,$model->id)->info:'',
					),
				   'shops_status'=>array(
					   'name'=>Shops::$_status[$model->Actives_Shops->status],
					   'value'=>$model->Actives_Shops->status
				   ),
					'actives_info'=>$actives_info,					
					'go_time'=>$model->go_time==0 ? '出游日期未定' : date('Y-m-d',$model->go_time),
					'go_time_value'=>$model->go_time==0?0:1,
					'set_go_time'=>$set_go_time,
					'start_time'=>date('Y-m-d',$model->start_time),
					'end_time'=>date('Y-m-d',$model->end_time),
			);
		}
		return $return;
	}
	
	/**
	 * 活动中的订单列表
	 */
	public function actionList($id,$type='')
	{
		$criteria=new CDbCriteria;
		$criteria->with=array(
				'Order_OrderRetinue'=>array(
					'condition'=>'Order_OrderRetinue.is_main=:is_main',
					'params'=>array(':is_main'=>Retinue::is_main),
				),
				'Order_OrderActives'=>array(
					'with'=>array(
							//'OrderActives_Organizer'=>array('with'=>array('Organizer_User'=>array('select'=>'id'))),
							'OrderActives_User'=>array('with'=>'User_Organizer'),
							'OrderActives_Actives'=>array('with'=>'Actives_Shops'),
					),
				),
		);
		//上线状态
		$column_status='`Actives_Shops`.`status`';
		$criteria->addCondition($column_status.'=:status_online OR '.$column_status.'=:status_offline');
		$criteria->params[':status_online']=Shops::status_online;
		$criteria->params[':status_offline']=Shops::status_offline;
		//审核状态
		$criteria->addCondition('`Actives_Shops`.`audit`=:audit_pass');
		$criteria->params[':audit_pass']=Shops::audit_pass;//平台审核通过
		//活动状态 不限制
		//订单状态
		$criteria->addCondition('`t`.`order_organizer_id` !=0');
		//已支付
		if($type=='pay')
		{
			$criteria->addCondition('`t`.`order_status`=:pay OR `t`.`order_status`=:yes');
			$criteria->params[':pay']=Order::order_status_user_pay;//支付
			$criteria->params[':yes']=Order::order_status_user_use;//消费	
			$criteria->addColumnCondition(array(
					'`t`.`status`'=>Order::status_yes,//有效的
			));
		}
		//确认出游
		elseif($type=='go_yes')
		{
			$criteria->addColumnCondition(array(
					'`t`.`status`'=>Order::status_yes,//有效的
					'`t`.`status_go`'=>Order::status_go_yes,//确认出游
					'`t`.`order_status`'=>Order::order_status_store_yes//待支付
			));
		}	
		elseif($type=='cancel')
		{
			$criteria->addColumnCondition(array(
					'`t`.`status`'=>Order::status_not,//取消报名
					'`t`.`order_status`'=>Order::order_status_store_undo//取消订单
			),'OR');
		}
		$criteria->addColumnCondition(array(
				'`t`.`order_organizer_id`'=>$id,
				'`OrderActives_Actives`.`organizer_id`'=>Yii::app()->api->id,  		//归属组织者
				'`OrderActives_User`.`status`'=>1,													//用户正常
				//'`OrderActives_Organizer`.`status`'=>1,										//组织者正常
				//'`Organizer_User`.`is_organizer`'=>User::organizer,					//组织者
				//'`Organizer_User`.`audit`'=>User::audit_pass,								//审核通过
		));
		
		$criteria->order='`t`.`add_time` desc,`t`.`pay_time` desc';
		$count = Order::model()->count($criteria);
		
		$return=array();
		//分页设置
		$return['page']=$this->page($criteria, $count, Yii::app()->params['api_pageSize']['actives_order'], Yii::app()->params['app_api_domain']);
		//根据条件查询
		$models = Order::model()->findAll($criteria);
		//分页数据
		$return['list_data']=$this->list_data($models, Yii::app()->params['app_api_domain']);
		
		if(empty($return['list_data']))
		{
			$return['list_data']=array();
			$return['null']='小觅已经很努力了！';
		}
		$return['type_link']=array(
			'all'=>Yii::app()->params['app_api_domain'].Yii::app()->createUrl('/api/orderActives/list',array('id'=>$id)),
			'pay'=>Yii::app()->params['app_api_domain'].Yii::app()->createUrl('/api/orderActives/list',array('id'=>$id,'type'=>'pay')),
			'go_yes'=>Yii::app()->params['app_api_domain'].Yii::app()->createUrl('/api/orderActives/list',array('id'=>$id,'type'=>'go_yes')),
			'cancel'=>Yii::app()->params['app_api_domain'].Yii::app()->createUrl('/api/orderActives/list',array('id'=>$id,'type'=>'cancel')),	
		);
		$this->send($return);
	}
	
	/**
	 * 活动中的订单 数据处理
	 */
	public function list_data($models,$domain)
	{
		$return=array();
		foreach ($models as $model)
		{
			$return[]=array(
				'order_no'=>$model->order_no,
				'value'=>$model->id,
				'link'=>$domain.Yii::app()->createUrl('/api/order/view',array('id'=>$model->id)),
				'main_retinue'=>array(
					'name'=>isset($model->Order_OrderRetinue[0]->retinue_name)?$model->Order_OrderRetinue[0]->retinue_name:'',
					'phone'=>isset($model->Order_OrderRetinue[0]->retinue_phone)?$model->Order_OrderRetinue[0]->retinue_phone:'',
				),
				'order_price'=>$model->order_price,
				'user_go_count'=>$model->user_go_count,
				'add_time'=>date('Y-m-d H:i:s',$model->add_time),
				//状态
				'status'=>array(
					'order_status'=>array(
							'name'=>Order::$_order_status[$model->order_status],
							'value'=>$model->order_status,
					),
					'status_go'=>array(
							'name'=>Order::$_status_go[$model->status_go],
							'value'=>$model->status_go,
					),
					'status'=>array(
							'name'=>Order::$_status[$model->status],
							'value'=>$model->status,
					),
					'order_type'=>array(
							'name'=>Order::$_order_type[$model->order_type],
							'value'=>$model->order_type,
					),
				)
			);
		}
		return $return;
	}
	
	/**
	 * 代理商 确认出游 活动id
	 * @param unknown $id
	 */
	public function actionGotime($id)
	{
  		if(isset($_POST['Actives']['go_time']))
  		{
			$this->_class_model='User';
			$user = $this->loadModel(Yii::app()->api->id,array(
					'with'=>array('User_Organizer'),
					'condition'=>'`t`.`status`=:status',
					'params'=>array(':status'=>User::status_suc)
			));
			
			$this->_class_model='OrderActives';
			$criteria=new CDbCriteria;
			$criteria->with=array(
				'OrderActives_Actives'=>array(
						'with'=>array('Actives_Shops'),
				),
			);
			//活动出游时间
			$criteria->addCondition('`OrderActives_Actives`.`go_time` =0');
			//限制的天数
			$day=Yii::app()->params['order_limit']['actives_tour']['set_go_time'];
			//最迟给出出游时间
			$criteria->compare('`OrderActives_Actives`.`end_time`', '>'.(time()-($day+1)*3600*24));	//动活结束时间
			//活动状态 不能是取消的状态
			$criteria->addCondition('`OrderActives_Actives`.`actives_status` !=:actives_status');
			$criteria->params[':actives_status']=Actives::actives_status_cancel;										//活动取消了
			$criteria->addColumnCondition(array(
					'Actives_Shops.audit'=>Shops::audit_pass,																	//审核通过
			));
			$model=$this->loadModel($id,$criteria);
			//场景 赋值
			$model->OrderActives_Actives->scenario='go_time';
			$model->OrderActives_Actives->attributes=$_POST['Actives'];
			//提前验证
			if($model->OrderActives_Actives->validate())
			{	
				//事物
				$transaction=$model->dbConnection->beginTransaction();
				try
				{
					$model->OrderActives_Actives->go_time = strtotime($model->OrderActives_Actives->go_time);
					
					if ($model->OrderActives_Actives->save(false))
					{
						if ($model->OrderActives_Actives->pay_type == Actives::pay_type_AA)
						{
							//已经报名下单的
							$criteria_order=new CDbCriteria;						
							$criteria_order->addColumnCondition(array(
								'order_type'=>Order::order_type_actives_tour,				//订单类型
								'status_go'=>Order::status_go_set,								//出游状态 待确认出游
								'order_status'=>Order::order_status_store_yes,			//订单状态 商家默认接单
								'pay_status'=>Order::pay_status_not,							//支付状态 待支付
								'centre_status'=>Order::centre_status_not,					//核心状态 不可支付
								'status'=>Order::status_yes,											//有效的订单
							));
							$count = Order::model()->count($criteria_order);
							$order = Order::model()->updateAll(array(
								'status_go'=>Order::status_go_query,
							),$criteria_order);
							if ($order != $count)
								throw new Exception("更新订单出游状态 记录错误");					
						}
					}else 
						throw new Exception("保存觅趣(旅游)出游时间 记录错误");
					
					$result = $this->log('觅趣(旅游)确认出游时间',ManageLog::user,ManageLog::update);
					$transaction->commit();
				}
				catch(Exception $e)
				{
					$transaction->rollBack();
					Yii::app()->controller->error_log($e->getMessage(),ErrorLog::user,ErrorLog::create,ErrorLog::rollback,__METHOD__);
				}
				if(isset($result))
				{
					$actives_criteria = new CDbCriteria;
					$actives_criteria->with=array(
						'OrderActives_Actives'=>array(
								'with'=>array('Actives_Shops'),
						),
					);
					//活动出游时间	
					$actives_criteria->compare('`OrderActives_Actives`.`go_time`', '='.$model->OrderActives_Actives->go_time);		
					$actives = OrderActives::model()->findByPk($id,$actives_criteria);
					if ($actives)
					{
						$return['actives']=array(
							'value'=>$actives->id,
							'name'=>$actives->OrderActives_Actives->Actives_Shops->name,
							'list_info'=>$actives->OrderActives_Actives->Actives_Shops->list_info,
							'go_time'=>date('Y-m-d',$actives->OrderActives_Actives->go_time),
							'link'=>Yii::app()->params['app_api_domain'].Yii::app()->createUrl('/api/orderAcitves/view',array('id'=>$actives->id)),
						);
						$this->send($return);
					}else 
						$this->send_error(DATA_NULL);
				}
			}else 
				$this->send_error_form($model->OrderActives_Actives->getErrors());
 		}
  		$this->send_csrf();
	}
	
	/**
	 * 搜索条件设置
	 * @param unknown $criteria
	 */
	public function search_info($criteria)
	{
		if(isset($_GET['search_info']) && !empty($_GET['search_info']) && !is_array($_GET['search_info']))
		{
			$criteria->params[':search_info']='%'.strtr($_GET['search_info'],array('%'=>'\%', '_'=>'\_', '\\'=>'\\\\')).'%';
			$condition='`Actives_Shops`.`name` LIKE :search_info OR `Actives_Shops`.`list_info` LIKE :search_info OR `Actives_Shops`.`page_info` LIKE :search_info';
			$criteria->addCondition($condition);
		}
	}
}
