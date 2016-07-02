<?php 
/**
 * 活动定时任务
 * @author Changhai Zhan
 *	创建时间：2015-10-26 13:41:03
 *	protected php yiic.php actives 
 * */
class ActivesCommand  extends ConsoleCommand
{
	/**
	 * 活动定时主要的定时任务
	 * 活动是否开始了
	 */
	public function actionIndex()
	{
		$this->logText[]='START';
		
		$criteria = new CDbCriteria;
		$criteria->select = '`t`.`id`';
		$criteria->with=array('Actives_Shops');
		$criteria->compare('`t`.`order_number`', '>=0');//活动剩余数量
		$criteria->compare('`t`.`start_time`', '<='.time());//活动开始时间
				
		$criteria->addColumnCondition(array(
				'`t`.`actives_status`'=>Actives::actives_status_not_start,	//活动没开始
				'`Actives_Shops`.audit'=>Shops::audit_pass,						//审核通过
				'`t`.status'=>Actives::status_publishing,								//活动是否有效
		));
		$actives = Actives::model()->find($criteria);
		if($actives)
		{
			//开启事物
			$transaction = $actives->dbConnection->beginTransaction();
			try{
				$count = Actives::model()->count($criteria);
				if($count > 0)
				{		
					$return = 0;											//更新的条数
					$countPage = $this->getPageCount($count);
					for ($i=1; $i<=$countPage; $i++)
					{
						$this->getPageCriteria($i, $criteria);
						$datas = Actives::model()->findAll($criteria);
						if($datas)
						{
							$ids = array();
							foreach ($datas as $data)
								$ids[] = $data->id;

							$criteria_update = new CDbCriteria;
							$criteria_update->addInCondition('id', $ids);
							$return += Actives::model()->updateAll(array(
								'actives_status'=>Actives::actives_status_start,
							),$criteria_update);
							$this->logText[] = implode(' , ', $ids);
						}else
							break;
					}
					if($count != $return)
						throw new Exception("定时任务 保存活动开始状态失败");
					$this->logText[]='定时任务 改变活动开始了 NO. '.$return;
				}
				$transaction->commit();
			}
			catch (Exception $e)
			{
				$transaction->rollBack();
				$this->logText[]=$e->getMessage();
				$this->logText[]='REEOE_END';
				return self::return_error;
			}
		}
		$this->logText[]='RIGHT_END';
		return self::correct;
	}

	/**
	 * 活动定时主要的定时任务
	 * 活动是否结束了
	 */
	public function actionEnd()
	{
		//开始
		$this->logText[]='START';	
		$criteria = new CDbCriteria;
		$criteria->select='`t`.`id`';
		$criteria->with=array('Actives_Shops');
		$criteria->compare('`t`.`start_time`', '<='.time());					//活动开始时间
		$criteria->addCondition('`t`.`end_time` <= :end_time OR (`t`.`order_number`=0) OR (  `t`.`go_time` != 0 AND `t`.`go_time` <= :go_time) ');
		$criteria->params[':end_time'] = time()-3600*24;					//动活结束时间
		$criteria->params[':go_time'] = time();									//动活出游时间
		
		$criteria->addColumnCondition(array(
				'`t`.`actives_status`'=>Actives::actives_status_start,		//活动开始
				'`Actives_Shops`.`audit`'=>Shops::audit_pass,				//审核通过
				'`t`.status'=>Actives::status_publishing,							//活动是否有效
		));
		$actives = Actives::model()->find($criteria);
		if ($actives)
		{
			//开启事物
			$transaction = $actives->dbConnection->beginTransaction();
			try
			{
				$count = Actives::model()->count($criteria);
				if($count > 0)
				{	
					$return = 0;//更新的条数
					$countPage = $this->getPageCount($count);
					for($i=1; $i<=$countPage; $i++)
					{
						$this->getPageCriteria($i, $criteria);
						$datas = Actives::model()->findAll($criteria);
						if($datas)
						{
							$ids = array();
							foreach ($datas as $data)
								$ids[]=$data->id;
							
							$criteria_update = new CDbCriteria;
							$criteria_update->addInCondition('id', $ids);
							$return += Actives::model()->updateAll(array(
									'actives_status'=>Actives::actives_status_end,
									'status'=>Actives::status_published,
							), $criteria_update);
							$this->logText[] = implode(' , ', $ids);
						}else 
							break;
					}
					if($count != $return)
						throw new Exception("定时任务 保存活动结束状态失败");
					$this->logText[] = '定时任务 改变活动结束了 NO. '.$return;
				}
				$transaction->commit();
			}
			catch (Exception $e)
			{
				$transaction->rollBack();
				$this->logText[] = $e->getMessage();
				$this->logText[] = 'REEOE_END';
				return self::return_error;
			}
		}
		$this->logText[] = 'RIGHT_END';
		return self::correct;
	}

	/**
	 * 到了活动报名结束时间后，超过7天未给出游日期，活动状态为已取消；
	 */
	public function actionGo_time()
	{
		$this->logText[]='START';

		$criteria = new CDbCriteria;
		$criteria->select='`t`.`id`';
		$criteria->with=array(	
			'Actives_Shops'=>array('select'=>'id'),
			'Actives_OrderActives'=>array('select'=>'id'),
		);
		$criteria->compare('`t`.`order_number`', '>=0');								//活动剩余数量
		$criteria->compare('`t`.`start_time`', '<='.time());								//活动开始时间
		
		//时间
		$criteria->addCondition('`t`.`end_time` <= :end_time AND `t`.`go_time` =0');	
		$day = Yii::app()->params['command_time']['actives']['end_time'];	//超时时间
		$cal_time = $this->calculate_out_time($day,self::time_day);
		$criteria->params[':end_time'] = $cal_time;										//动活结束时间
		$criteria->addColumnCondition(array(
			'`t`.`actives_status`'=>Actives::actives_status_end,						//活动结束了
			'`Actives_Shops`.`audit`'=>Shops::audit_pass,								//审核通过
			'`t`.`status`'=>Actives::status_published,										//活动结束了
		));
		$actives = Actives::model()->find($criteria);
		if ($actives)
		{
			//开启事物
			$transaction = $actives->dbConnection->beginTransaction();
			try
			{
				$count = Actives::model()->count($criteria);
				if ($count > 0)
				{
					$return = 0;//更新的条数
					$countPage = $this->getPageCount($count);
					for ($i=1; $i<=$countPage; $i++)
					{
						$this->getPageCriteria($i, $criteria);
						$datas = Actives::model()->findAll($criteria);
						if($datas)
						{
							$ids = $actives_ids = $order_ids =array();
							foreach ($datas as $data)
							{
								$ids[] = $data->id;
								$actives_ids[] = $data->Actives_OrderActives->id;
							}
							//取消活动
							$criteria_update = new CDbCriteria;
							$criteria_update->addInCondition('id', $ids);
							$return += Actives::model()->updateAll(array(
								'actives_status'=>Actives::actives_status_cancel,
								'up_time'=>time(),
							), $criteria_update);
							$this->logText[] = implode(' , ', $ids);
							//取消订单
							$criteria_actives=new CDbCriteria;
							$criteria_actives->addInCondition('order_organizer_id', $actives_ids);
							Order::model()->updateAll(array(
								'status'=>Order::status_not,										//取消报名
								//取消订单
								'order_status'=>Order::order_status_store_undo,		//已取消			
								//核心状态
								'centre_status'=>Order::centre_status_not,				//不支付
								//取消出游
								'status_go'=>Order::status_go_no,							//取消出游
								//支付状态
								'pay_status'=>Order::pay_status_past,						//已取消
								'up_time'=>time(),
							),$criteria_actives);
							//取消接单
							$criteria_actives->select = 'id,user_go_count';
							$orders = Order::model()->findAll($criteria_actives);
							$number=array();															//购买数量
							$actives_count=array();
							foreach ($orders as $order)
							{
								$order_ids[] = $order->id;
								isset($number[$order->order_organizer_id]) ? '' : $number[$order->order_organizer_id] = 0;
								isset($actives_count[$order->order_organizer_id]) ? '' : $actives_count[$order->order_organizer_id] = 0;
								$number[$order->order_organizer_id] += $order->user_go_count;
								$actives_count[$order->order_organizer_id] += 1;
							}
							$criteria_order = new CDbCriteria;
							$criteria_order->addInCondition('order_id', $order_ids);
							OrderItems::model()->updateAll(array(
								'is_shops'=>OrderItems::is_shops_order_not				//用户已取消
							),$criteria_order);
							foreach ($datas as $avtives)
							{
								if ($avtives->pay_type == Actives::pay_type_AA)
								{
									//还原购买数量
									isset($number[$avtives->Actives_OrderActives->id]) ? Actives::restore_order_number($avtives->Actives_OrderActives->actives_id,$number[$avtives->Actives_OrderActives->id]) : '';
									//还原报名名统计
									isset($actives_count[$avtives->Actives_OrderActives->id]) ? Actives::actives_tour_count_out($avtives->Actives_OrderActives->actives_id,$actives_count[$avtives->Actives_OrderActives->id]) : '';
								}
								else if ($avtives->pay_type == Actives::pay_type_full)
								{
									//还原订单
									OrderActives::actives_order_count($avtives->Actives_OrderActives->id,true);
									//还原
									OrderActives::actives_confirm_count($avtives->Actives_OrderActives->id,true);
								}
							}
						}else
							break;
					}
					if($count != $return)
						throw new Exception("定时任务 保存活动结束状态失败");
					$this->logText[]='定时任务 改变活动结束了 NO. '.$return;
				}
				$transaction->commit();
			}
			catch (Exception $e)
			{
				$transaction->rollBack();
				$this->logText[]=$e->getMessage();
				$this->logText[]='REEOE_END';
				return self::return_error;
			}
		}
		$this->logText[]='RIGHT_END';
		return self::correct;
	}

	/**
	 *代理商(组织者)确认出游时间后，如果到了出游时间用户还未确认出游，自动取消出游；
	 */
	public function actionOrganizer()
	{
		$this->logText[] = 'START';

		$criteria = new CDbCriteria;
		$criteria->with = array(
			'Order_OrderActives'=>array(
				'with'=>array(
					'OrderActives_Actives'=>array(
						'with'=>'Actives_Shops',
					),
				),
			),
		);
		$criteria->compare('`OrderActives_Actives`.`order_number`', '>=0');			//活动剩余数量
		$criteria->compare('`OrderActives_Actives`.`start_time`', '<='.time());			//活动开始时间
		
		$criteria->addCondition('`OrderActives_Actives`.`go_time` !=0 AND `OrderActives_Actives`.`go_time` <= :go_time');
		$criteria->params[':go_time']=time();															//动活出游时间
		
		$criteria->addColumnCondition(array(
			'`Actives_Shops`.`audit`'=>Shops::audit_pass,											//审核通过
			'`t`.`order_type`'=>Order::order_type_actives_tour,									//活动（旅游）
			'`t`.`status_go`'=>Order::status_go_query,													//出游时间没有给
			'`t`.`order_status`'=>Order::order_status_store_yes,									//待支付
			'`t`.`pay_status`'=>Order::pay_status_not,													//待支付
		));
		$actives = Order::model()->find($criteria);
		if($actives)
		{
			//开启事物
			$transaction = $actives->dbConnection->beginTransaction();
			try
			{
				$count = Order::model()->count($criteria);
				if($count > 0)
				{
					$return = 0;//更新的条数
					$countPage = $this->getPageCount($count);
					for ($i=1; $i<=$countPage; $i++)
					{
						$this->getPageCriteria($i, $criteria);
						$datas=Order::model()->findAll($criteria);

						if($datas)
						{
							$order_ids = array();
							$number = array();							//购买数量
							$actives_count = array();
							$actives_models = array();
							//记录所有需要ID
							foreach ($datas as $data)
							{
								$order_ids[] = $data->id;
								isset($number[$data->order_organizer_id]) ? '' : $number[$data->order_organizer_id] = 0;
								isset($actives_count[$data->order_organizer_id])? '' : $actives_count[$data->order_organizer_id] = 0;
								$number[$data->order_organizer_id] += $data->user_go_count;
								$actives_count[$data->order_organizer_id] +=1;
								$actives_models[] = $data->Order_OrderActives;
							}		
							//更新订单状态
							$criteria_update = new CDbCriteria;
							$criteria_update->addInCondition('id', $order_ids);
							$return += Order::model()->updateAll(array(
								'order_status'=>Order::order_status_store_undo,		//订单的状态 已取消
								'centre_status'=>Order::centre_status_not,				//订单的状态 不支付
								'status_go'=>Order::status_go_no,							//订单的状态 取消出游
								'pay_status'=>Order::pay_status_past,						//订单的状态 已取消
								'up_time'=>time(),
							),$criteria_update);
						
							$criteria_order = new CDbCriteria;
							$criteria_order->addInCondition('order_id', $order_ids);
							OrderItems::model()->updateAll(array(
								'is_shops'=>OrderItems::is_shops_order_not//用户已取消
							), $criteria_order);					

							foreach ($actives_models as $actives_model)
							{
								//还原购买数量
								isset($number[$actives_model->id])?Actives::restore_order_number($actives_model->actives_id,$number[$actives_model->id]):'';
								//还原报名名统计
								isset($actives_count[$actives_model->id])?Actives::actives_tour_count_out($actives_model->actives_id,$actives_count[$actives_model->id]):'';
							}						
							$this->logText[] = implode(' , ', $order_ids);
						}else
							break;
					}
					if($count != $return)
						throw new Exception("定时任务 改变订单状态保存失败");
					$this->logText[] = '定时任务 改变订单状态保存成功 NO. '.$return;
				}
				$transaction->commit();
			}
			catch (Exception $e)
			{
				$transaction->rollBack();
				$this->logText[] = $e->getMessage();
				$this->logText[] = 'REEOE_END';
				return self::return_error;
			}
		}
		
		$this->logText[] = 'RIGHT_END';
		return self::correct;
	}
}