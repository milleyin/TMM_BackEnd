<?php
/**
 * ContactForm class.
 */
class RefundForm extends CFormModel
{
	public $order_id;				//订单ID	
	public $price;					//订单总价
	public $money;				//未消费金额
	public $fee;						//服务费用
	public $refund;				//退款费用
	public $deduct;				//扣除费用
	
	public $orderModel;		// 订单数据模型

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			array('order_id', 'numerical', 'integerOnly'=>true),	
			array('order_id', 'length', 'max'=>11),
			//验证钱
			array('refund, deduct', 'ext.Validator.Validator_money'),
			//全部退款
			array('order_id, refund, deduct', 'required', 'on'=>'all'),
			array('order_id, refund, deduct', 'safe', 'on'=>'all'),
			array('order_id, refund, deduct', 'validatorMoney', 'on'=>'all'),
			array('price, fee, money, orderModel', 'unsafe', 'on'=>'all'),
		);
	}

	/**
	 * Declares customized attribute labels.
	 * If not declared here, an attribute would have a label that is
	 * the same as its name with the first letter in upper case.
	 */
	public function attributeLabels()
	{
		return array(
			'order_id'=>'订单',
			'price'=>'支付金额',
			'money'=>'未消费金额',
			'fee'=>'服务费用',
			'refund'=>'退款金额',
			'deduct'=>'扣除金额',
		);
	}
	
	/**
	 * 全款的条件
	 * @return CDbCriteria
	 */
	public static function getRefundsCriteria()
	{
		$criteria =  new  CDbCriteria;
		$criteria->with = array(
				'OrderActives_Actives',
				'OrderActives_Shops',
				'OrderActives_OrderItems',
				'OrderActives_User'=>array('with'=>array('User_Organizer')),
				'OrderActives_Order'=>array(
					'with'=>array(
						'Order_User',
						'Order_OrderItems'=>array(
							'with'=>'OrderItems_OrderItems',
						)
					),
				),
		);
		$criteria->addColumnCondition(array(
				'`OrderActives_Actives`.`actives_type`'=>Actives::actives_type_tour,				//觅趣
				'`OrderActives_Actives`.`pay_type`'=>Actives::pay_type_AA,							//觅趣自费
				'`t`.`actives_type`'=>OrderActives::actives_type_tour,										//觅趣
				'`OrderActives_Order`.`order_type`'=>Order::order_type_actives_tour,			//觅趣自费订单
				'`OrderActives_Order`.`status`'=>Order::status_yes,											//有效订单
				'`OrderActives_Order`.`status_go`'=>Order::status_go_yes,								//确认出游
				'`OrderActives_Order`.`pay_status`'=>Order::pay_status_yes,							//已支付
				'`OrderActives_Order`.`order_status`'=>Order::order_status_user_pay,			//已付款
				'`OrderItems_OrderItems`.`is_barcode`'=>OrderItems::is_barcode_valid,	 		//有效的码
				'`OrderActives_OrderItems`.`is_barcode`'=>OrderItems::is_barcode_valid, 		//有效的码
		));
		return $criteria;
	}
	
	/**
	 * 获取全款 觅趣相关信息
	 * @param unknown $id
	 */
	public static function getRefunds($id)
	{
		return OrderActives::model()->findByPk($id, self::getRefundsCriteria());
	}
	
	/**
	 * 获取订单的有效金额
	 * @param unknown $model
	 */
	public static function getOrderValidMoney($models)
	{
		$money = 0.00;
		foreach ($models as $orderItmes)
		{
			if ($orderItmes->OrderItems_OrderItems->is_barcode == OrderItems::is_barcode_valid)
				$money = Yii::app()->controller->floorAdd($money, $orderItmes->total);
		}
		return $money;
	}
	
	/**
	 * 获取服务费用
	 * @param unknown $model
	 * @return number
	 */
	public static function getOrderFee($model)
	{
		return Yii::app()->controller->floorMul($model->user_price_fact, $model->user_go_count);
	}
	
	/**
	 * 验证钱
	 * @param unknown $attribute
	 */
	public function validatorMoney($attribute)
	{
		if (! $this->hasErrors())
		{
			if ($this->order_id != $this->orderModel->id)
				$this->addError('order_id', '订单 不是有效值');
			$floor = Yii::app()->controller;			
			if ( $floor->floorComp($this->refund, $floor->floorAdd($this->money, $this->fee)) == 1)
				$this->addError('refund', '退款金额 不能大于未消费金额与服务费之和');
			if ( $floor->floorComp($this->deduct, $floor->floorAdd($this->money, $this->fee)) == 1)
				$this->addError('deduct', '扣除金额 不能大于未消费金额与服务费之和');
			if ( $floor->floorComp($floor->floorAdd($this->money, $this->fee), $this->price) == 1)
			{
				$this->addError('refund', '未消费金额与服务费之和 不能大于订单支付总价');
				$this->addError('deduct', '未消费金额与服务费之和 不能大于订单支付总价');
			}
			if (! $this->hasErrors('deduct'))
			{
				if ($floor->floorComp($this->refund, $floor->floorSub($floor->floorAdd($this->money, $this->fee), $this->deduct)) != 0 )
					$this->addError('refund', '退款金额 不是有效值 '. $floor->floorSub($floor->floorAdd($this->money, $this->fee), $this->deduct));
			}
			else if (! $this->hasErrors('refund'))
			{
				if ($floor->floorComp($this->deduct, $floor->floorSub($floor->floorAdd($this->money, $this->fee), $this->refund)) != 0)
					$this->addError('deduct', '扣除金额 不是有效值 ' . $floor->floorSub($floor->floorAdd($this->money, $this->fee), $this->refund));
			}
			if ($floor->floorComp($floor->floorAdd($this->deduct, $this->refund), $floor->floorAdd($this->money, $this->fee)) != 0)
			{
				$this->addError('deduct', '退款金额 不是有效值 ' . $floor->floorSub($floor->floorAdd($this->money, $this->fee), $this->deduct));
				$this->addError('refund', '扣除金额 不是有效值 ' . $floor->floorSub($floor->floorAdd($this->money, $this->fee), $this->refund));
			}
		}
	}
}