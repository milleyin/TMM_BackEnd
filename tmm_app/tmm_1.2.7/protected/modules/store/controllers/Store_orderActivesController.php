<?php
/**
 * 活动订单
 * @author Changhai Zhan
 *
 */
class Store_orderActivesController extends StoreMainController
{
	public $_class_model = 'OrderActives';
	/**
	 * 查看活动订单详情表
	 */
	public function actionView($id)
	{
		$this->_class_model = 'StoreUser';
		$this->loadModel(Yii::app()->store->id,'status=1');
		
		$criteria =new CDbCriteria;
		$criteria->with=array(
				'OrderActives_User'=>array('with'=>array('User_Organizer')),
				'OrderActives_OrderItems'=>array(
					'with'=>array(
							'OrderItems_ItemsClassliy',
							'OrderItems_OrderItemsFare'
					),
				),
				'OrderActives_Actives'=>array(
					'with'=>array(
						'Actives_Shops',
					),
				),
		);
		//活动的支付数量
		$criteria->compare('`t`.`user_order_count`', '>0');
		//权限限制
		$criteria->addCondition('Actives_Shops.status !=:del AND (`OrderActives_OrderItems`.`store_id`=:store_id OR `OrderActives_OrderItems`.`manager_id`=:manager_id)');
		$criteria->params[':store_id'] = Yii::app()->store->id;
		$criteria->params[':manager_id'] = Yii::app()->store->id;
		$criteria->params[':del']=Shops::status_del;
	
		$this->_class_model='OrderActives';
		$model=$this->loadModel($id,$criteria);
		
		$img=$this->litimg_path($model->OrderActives_Actives->Actives_Shops->list_img);
		$return['actives']=array(
				'list_img'=>empty($img)?'':Yii::app()->params['admin_img_domain'].ltrim($img,'.'),
				'list_info'=>CHtml::encode($model->OrderActives_Actives->Actives_Shops->list_info),
				'page_info'=>CHtml::encode($model->OrderActives_Actives->Actives_Shops->page_info),
				'actives_status'=>array(
						'name'=>Actives::$_actives_status[$model->OrderActives_Actives->actives_status],
						'value'=>$model->OrderActives_Actives->actives_status,
				),
				'number'=>$model->OrderActives_Actives->number,
				'order_number'=>$model->OrderActives_Actives->order_number,
		);
		$return['go_time']=date('Y-m-d',$model->OrderActives_Actives->go_time);	//出游时间
		//活动简介
		$return['actives_info']=array(
				'remark'=>$model->OrderActives_Actives->remark,//CHtml::encode($model->OrderActives_Actives->remark),
				'actives_no'=>$model->actives_no,
				'number'=>array(
					'name'=>'活动人数',
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
		$return['value']=$model->id;
		//活动时间
		$return['action_time']=array(
				'start_time'=>date('Y-m-d',$model->OrderActives_Actives->start_time),
				'end_time'=>date('Y-m-d',$model->OrderActives_Actives->end_time),
				'add_time'=>date('Y-m-d H:i:s',$model->add_time),
		);
		//判断数据是否存在
		if( !isset($model->OrderActives_OrderItems) || empty($model->OrderActives_OrderItems) || !is_array($model->OrderActives_OrderItems))
			$this->send_error(DATA_NOT_SCUSSECS);

		//活动组织者
		if($model->OrderActives_Actives->is_organizer == Actives::is_organizer_yes)
		{
			$return['actives_organizer']=array(
					'name'=>CHtml::encode($model->OrderActives_User->User_Organizer->manage_name),
					'gender'=>User::$_gender[$model->OrderActives_User->gender],
					'phone'=>$model->OrderActives_User->phone,
			);
		}
		else
		{
			$return['actives_organizer']=array(
					'name'=>CHtml::encode($model->OrderActives_User->nickname),
					'gender'=>User::$_gender[$model->OrderActives_User->gender],
					'phone'=>$model->OrderActives_User->phone,
			);
		}
		//项目 价格
		foreach ($model->OrderActives_OrderItems as $key=>$items)
		{
			if(!isset($items->OrderItems_ItemsClassliy) || is_array($items->OrderItems_ItemsClassliy))
				$this->send_error(DATA_NOT_SCUSSECS);
			$return['items_fare'][$items->shops_day_sort][$items->shops_half_sort][$items->shops_sort]['name']=CHtml::encode($items->items_name);
			$return['items_fare'][$items->shops_day_sort][$items->shops_half_sort][$items->shops_sort]['value']=$items->items_id;
			//项目免费
			$return['items_fare'][$items->shops_day_sort][$items->shops_half_sort][$items->shops_sort]['free_status']=array(
					'name'=>Items::$_free_status[$items->items_free_status],
					'value'=>$items->items_free_status,
			);
			
			$return['items_fare'][$items->shops_day_sort][$items->shops_half_sort][$items->shops_sort]['link']=Yii::app()->params['app_api_domain'].Yii::app()->createUrl('/store/store_'.$items->OrderItems_ItemsClassliy->admin.'/view',array('id'=>$items->items_id));
			$return['items_fare'][$items->shops_day_sort][$items->shops_half_sort][$items->shops_sort]['classliy']=array(
					'name'=>$items->items_c_name,
					'value'=>$items->items_c_id,
			);
			// 消费码
			$return['items_fare'][$items->shops_day_sort][$items->shops_half_sort][$items->shops_sort]['barcode']=array(
				'is_barcode'=>$items->is_barcode,
				'barcode_name'=>OrderItems::$_is_barcode[$items->is_barcode],
				'employ_time'=>$items->employ_time==0 ? '' : date('Y-m-d H:i:s',$items->employ_time),
				'barcode'=>$items->barcode,
				'value'=>$items->id,
			);
			if( !isset($items->OrderItems_OrderItemsFare) || empty($items->OrderItems_OrderItemsFare) || !is_array($items->OrderItems_OrderItemsFare))
				$this->send_error(DATA_NOT_SCUSSECS);
			foreach ($items->OrderItems_OrderItemsFare as $fare)
			{
				// 价格信息
				$return['items_fare'][$items->shops_day_sort][$items->shops_half_sort][$items->shops_sort]['fare'][]=array(
						'name' =>CHtml::encode($fare->fare_name),
						'info' =>CHtml::encode($fare->fare_info),
						'number' => $fare->number,
						'price' => $fare->fare_price,
						'count'=>$fare->total,
						'room_number'=>CHtml::encode($fare->fare_number),
				);
			}
		}
		$this->send($return);
	}
	
// 	/**
// 	 * 查看活动订单中的订单列表
// 	 */
// 	public function actionIndex($id)
// 	{
		
// 	}
	
// 	/**
// 	 * 列表数据
// 	 */
// 	public function list_data($models,$domain)
// 	{
		
// 	}
}