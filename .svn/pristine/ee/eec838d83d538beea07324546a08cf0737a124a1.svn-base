<?php 
/**
 * 活动退款 全部
 * @author Changhai Zhan
 *
 */
class RefundsOrderActivesAction extends CAction
{
	/**
	 * 每笔订单能退的钱
	 * @var unknown
	 */
	public $money = array();
	
	/**
	 * 执行的方法
	 * @param unknown $id
	 */
	public function run($id)
	{
		//查询
		$model = $this->controller->loadModel($id,RefundForm::getRefundsCriteria());
		//订单信息
		$models = $this->setRefunds($model->OrderActives_Order);
		//密码信息
		$password = new Password;
		$pwd_type = Password::password_type_pay;
		$password->scenario = isset(Password::$_password_type_validate[$pwd_type]) ? Password::$_password_type_validate[$pwd_type] : 'validate';
		
		$this->controller->_Ajax_Verify($models, 'refund-form-form', true);
		
		if (isset($_POST['Password'], $_POST['RefundForm'][0]) && is_array($_POST['RefundForm'][0]))
		{
			//项目价格验证
			$validate = $this->controller->models_validate($models);
			//验证密码
			if ($validate && $this->validatePwd($password))
			{
				//事物开始
				$transaction = $model->dbConnection->beginTransaction();
				try
				{	
					// 是否能退款
					if ( !!$model = OrderActives::model()->findByPk($id, RefundForm::getRefundsCriteria()))
					{
						//订单信息
						$models = $this->setRefunds($model->OrderActives_Order);
						//项目价格验证
						if ($this->controller->models_validate($models))
						{
							$ids = array();
							foreach ($models as $refund)
							{
								//退给用户的钱
								$this->userRefund($refund);
								//给平台的钱
								$this->paaSRefund($refund);
								$ids[] = $refund->orderModel->id;
							}
							//用户订单 										//执行活动
							if ($this->userOrder($ids)  && $this->executeActives($model))
							{
								$this->userOtherOrder($model->id);
								$return = $this->controller->log('觅趣（全）退款成功：(' . $model->actives_no .' ['.CHtml::encode($model->OrderActives_Shops->name) . ']）', ManageLog::admin, ManageLog::create);
							}
							else 
								throw new Exception("觅趣（全）退款事物订单改变状态错误或觅趣改表错误");
						}
						else
							throw new Exception("觅趣（全）退款事物验证未通过");
					}
					else 
						throw new Exception("觅趣（全）退款事物查询未通过");
					
					$transaction->commit();
				}
				catch(Exception $e)
				{
					$transaction->rollBack();
					$this->controller->error_log($e->getMessage(),ErrorLog::admin,ErrorLog::update,ErrorLog::rollback,__METHOD__);
				}
				if(isset($return) && $return)
					$this->controller->back();
			}
		}
		
		$this->controller->render('refunds',array(
			'model'=>$model,
			'models'=>$models,
			'password'=>$password,
		));
	}
	
	/**
	 * 用户退款执行的操作
	 * @param unknown $model
	 */
	public function userRefund($model)
	{
		$who_role = array(
				'account_id'=>$model->orderModel->user_id, 
				'account_type'=>Account::user,
		);
		if ($this->controller->floorComp($model->refund,0) == 1)
		{
			$infos = array(
					'退款订单号：' . $model->orderModel->order_no,				
					'退款受理人：' . Yii::app()->admin->name,
					'退款受理时间：'. date('Y-m-d H:i:s'),
					'订单支付金额：' . $model->orderModel->price . '元',
					'订单消费金额：' . Yii::app()->controller->floorSub($model->price, Yii::app()->controller->floorAdd($model->money, $model->fee)) . '元',
					'订单未消费金额：' . Yii::app()->controller->floorAdd($model->money, $model->fee) . '元',
					'退款金额：'.$model->refund . '元',
			);
			$info = array(
					'info_id'=>$model->orderModel->id,
					'info'=>implode("\n<br>", $infos),
					'name'=>$model->orderModel->order_no,
			);
			//退给用户的钱
			if (! Account::moneyEntryOrderRefundRmb($model->refund, $who_role, $info))
				throw new Exception("用户钱包入账错误");
		}
		if ($this->controller->floorComp($model->deduct,0) == 1)
		{
			$infos = array(
					'退款订单号：' . $model->orderModel->order_no,				
					'退款受理人：' . Yii::app()->admin->name,
					'退款受理时间：'. date('Y-m-d H:i:s'),
					'订单支付金额：' . $model->orderModel->price . '元',
					'订单消费金额：' . Yii::app()->controller->floorSub($model->price, Yii::app()->controller->floorAdd($model->money, $model->fee)) . '元',
					'订单未消费金额：' . Yii::app()->controller->floorAdd($model->money, $model->fee) . '元',
					'违约消费金额：'.$model->deduct . '元',
			);
			$info = array(
					'info_id'=>$model->orderModel->id,
					'info'=>implode("<br>\n", $infos),
					'name'=>$model->orderModel->order_no,
			);
			//扣除用户的钱
			if (! Account::moneyRecordOrderPenaltyRmb($model->deduct, $who_role, $info))
				throw new Exception("用户钱包消费错误");
		}
		//每笔订单可以退的钱
		$this->money[$model->orderModel->id] = $this->controller->floorAdd($model->fee,$model->money);
	}
	
	/**
	 * 用户退款订单 更新成 退款
	 * @param unknown $ids
	 * @return boolean
	 */
	public function userOrder($ids=array())
	{
		if (! empty($ids))
		{
			$criteria = new CDbCriteria;
			$criteria->addInCondition('id',  $ids);
			$return = Order::model()->updateAll(array(
					'order_status'=>Order::order_status_store_refund,										//退款
					'up_time'=>time(),
			), $criteria);
			return $return == count($ids);
		}
		return false;
	}
	
	/**
	 * 其他订单取消
	 * @param unknown $order_organizer_id
	 */
	public function userOtherOrder($order_organizer_id)
	{
		$criteria = new CDbCriteria;
		//待出游 已经确认的
		$criteria->addCondition('status_go=:query OR status_go=:yes');
		$criteria->params[':query'] = Order::status_go_query;
		$criteria->params[':yes'] = Order::status_go_yes;
		//标准条件
		$criteria->addColumnCondition(array(
				'order_organizer_id'=>$order_organizer_id,								//归属活动单号
				'pay_status'=>Order::pay_status_not,										//没有支付  未支付
				'status'=>Order::status_yes,														//有效的订单
		));
		return Order::model()->updateAll(array(
				'order_status'=>Order::order_status_store_undo,	//订单的状态 取消订单
				'status_go'=>Order::status_go_no,						//是否出游 取消出游
				'centre_status'=>Order::centre_status_not,			//是否可以支付 不可支付
				'pay_status'=>Order::pay_status_past,					//没有支付 取消支付
				'up_time'=>time(),
			), $criteria);
	}
	
	/**
	 * 平台退款 执行的操作
	 * @param unknown $model
	 */
	public function paaSRefund($model)
	{
		if ($this->controller->floorComp($model->deduct,0) == 1)
		{
			$to_role = array(
					'to_account_id'=>$model->orderModel->user_id,
					'to_account_type'=>Account::user,
			);
			$infos = array(
					'退款订单号：' . $model->orderModel->order_no,
					'订单归属人：' . $model->orderModel->Order_User->phone . ' [ID:' .  $model->orderModel->user_id . ']',
					'退款受理人：' . Yii::app()->admin->name,
					'订单支付金额：' . $model->orderModel->price . '元',
					'订单消费金额：' . Yii::app()->controller->floorSub($model->price, Yii::app()->controller->floorAdd($model->money, $model->fee)) . '元',
					'订单未消费金额：' . Yii::app()->controller->floorAdd($model->money, $model->fee) . '元',
					'违约消费金额：'.$model->deduct . '元',
					'退款金额：'.$model->refund . '元',
			);
			$info = array(
					'info_id'=>$model->orderModel->id,
					'info'=>implode("<br>\n", $infos),
					'name'=>$model->orderModel->order_no,
			);
			// 平台入账
			if (! Account::moneyEntryOrderRefundPenaltyRmb($model->deduct, $to_role, $info))
				throw new Exception("扣除钱给平台 失败");
		}
	}
	
	/**
	 * 执行觅趣的一些操作 支付数量减少 支付金额减少 消费码 标志已退款
	 * @param unknown $model
	 * @throws Exception
	 * @return boolean
	 */
	public function executeActives($model)
	{
		$moneyCount = $this->controller->floorAddArray($this->money);
		$orderCount = count($this->money);
		if ($this->controller->floorComp($model->total, $moneyCount) == -1)
			return false;
		if ($model->user_pay_count != $orderCount)
			return false;
		$actives = true;
		if ($model->OrderActives_Actives->actives_status == Actives::actives_status_start )
		{
			$actives = Actives::model()->updateByPk($model->OrderActives_Actives->id, array(
					'actives_status'=>Actives::actives_status_end,
					'status'=>Actives::status_published,
			));
		}	
		if ($actives && OrderActives::model()->updateByPk($model->id, array(
					'user_pay_count'=>0,
					'total'=>new CDbExpression('total-:total', array(':total'=>$moneyCount)),
					'up_time'=>time(),
				))
			)
		{
			$ids = array();
			foreach ($model->OrderActives_OrderItems as $items)
				$ids[] = $items->id;
			$criteria = new CDbCriteria;
			$criteria->addColumnCondition(array(
					'`is_barcode`'=>OrderItems::is_barcode_valid,
					'`order_organizer_id`'=>$model->id
			));
			return count($ids) == OrderItems::model()->updateAll(array(
					'is_barcode'=>OrderItems::is_barcode_refund,
					'up_time'=>time()), $criteria);
		}
		return false;
	}
	
	/**
	 * 设置表单属性
	 * @param unknown $models
	 * @return multitype:RefundForm
	 */
	public function setRefunds($models)
	{
		$returnModels = array();
		foreach ($models as $model)
		{
			$refund = new RefundForm;
			$refund->scenario = 'all';
			$refund->order_id = $model->id;		
			$refund->deduct = '0.00';
			$refund->money = RefundForm::getOrderValidMoney($model->Order_OrderItems);
			$refund->fee = RefundForm::getOrderFee($model);
			$refund->refund = $this->controller->floorAdd($refund->fee,$refund->money);
			$refund->price = $model->price;
			$refund->orderModel = $model;
			$returnModels[] = $refund;
		}
		return $returnModels;
	}
	
	/**
	 * 验证密码
	 * @param unknown $model
	 * @return boolean
	 */
	public function validatePwd($model)
	{			
		$result = Password::validatePwd(
							Yii::app()->admin->id,
							Password::role_type_admin,
							Password::password_type_pay,
							$_POST['Password']
					);
		if(isset($result['value']) && $result['value'] == Password::password_pass)
			return true;
		else
		{
			$return = Password::getValidatePwdResult($result);
			$model->addError('_pwd', $return['name']);
		}
		return false;
	}
}