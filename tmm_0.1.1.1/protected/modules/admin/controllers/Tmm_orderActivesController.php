<?php
/**
 * 
 * @author Changhai Zhan
 *	创建时间：2015-10-23 10:31:10 */
class Tmm_orderActivesController extends MainController
{
	/**
	 * 默认操作数据模型
	 * @var string
	 */
	public $_class_model='OrderActives';

	/**
	 * 查看详情
	 * @param integer $id
	 */
	public function actionView($id)
	{
		$model = $this->loadModel($id,array(
			'with'=>array(
				'OrderActives_Order',
				'OrderActives_Actives',
				'OrderActives_OrderItems'=>array(
					'with'=>array(
						'OrderItems_OrderItemsFare',
						'OrderItems_StoreUser'=>array('with'=>array('Store_Content')),
						'OrderItems_ItemsClassliy',
					),
					'order'=>'OrderActives_OrderItems.shops_day_sort,OrderActives_OrderItems.shops_half_sort,OrderActives_OrderItems.shops_sort',
				),
			),
		));

		$this->render('view',array(
			'model'=>$model,
		));

	}


	
	/**
	 *管理页
	 */
	public function actionAdmin()
	{
		$model=new OrderActives('search');
		$model->unsetAttributes();  // 删除默认属性
		if(isset($_GET['OrderActives']))
			$model->attributes=$_GET['OrderActives'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * 退款处理
	 */
	public function actionRefund($id){

		$model = $this->loadModel($id,array(
			'with'=>array(
				'OrderActives_Order'
			),
			'condition'=>'OrderActives_Order.order_status=:order_status AND OrderActives_Order.status_go=:status_go AND OrderActives_Order.centre_status=:centre_status
			 AND OrderActives_Order.pay_status=:pay_status AND OrderActives_Order.status=:status',
			'params'=>	array(
				':order_status'=>Order::order_status_user_pay,	//订单的状态 已付款
				':status_go'=>Order::status_go_yes,					//是否出游	确认出游
				':centre_status'=>Order::centre_status_not,		//是否可以支付 不可支付
				':pay_status'=>Order::pay_status_yes,				//没有支付 已支付
				':status'=>Order::status_yes,						//有效的订单
			),
		));

		//开启事务
		$transaction=$model->dbConnection->beginTransaction();
		try {
			//循环所有下单用户
			foreach($model->OrderActives_Order as $v){
				//是否有用户已经在退款
				if( !$this->is_order_refund($v->id)){
					//更新退款记录表
					if( !$this->save_refund($v)->save(false))
						throw new Exception("申请旅游活动订单退款 更新退款订单失败".$v->id);
				}
			}
			$return=$this->log('申请旅游活动订单退款成功',ManageLog::admin,ManageLog::create);
			//事务提交
			$transaction->commit();
		}
		catch(Exception $e)
		{
			//事务回滚
			$transaction->rollBack();
			$this->error_log($e->getMessage(),ErrorLog::admin,ErrorLog::create,ErrorLog::rollback,__METHOD__);
		}

		if(isset($return) && $return)
			$this->redirect(array('/admin/tmm_refundLog/admin','id'=>$model->id));
		else
			$this->redirect(array('/admin/tmm_orderActives/admin'));
	}

	/**
	 * 当前订单是否正在退款
	 */
	public function is_order_refund($order_id){
		$refund = RefundLog::model()->find(
			array(
				'select'=>array('id'),
				'order' => 'id DESC',
				'condition' => 'order_id=:order_id AND audit_status >=:audit_status',
				'params' => array(':order_id'=>$order_id,':audit_status'=>RefundLog::audit_status_first,)
			));
		if($refund)
			return true;
		else
			return false;
	}

	/**
	 * 更新退款记录表
	 */
	private function save_refund($model){
		$model_refund = new RefundLog();

		$model_refund->admin_id	 	= Yii::app()->admin->id;
		$model_refund->user_id 		= $model->user_id;
		$model_refund->order_id 		= $model->id;
		$model_refund->order_no 		= $model->order_no;
		$model_refund->refund_id 		= 1;
		$model_refund->reason 			= '简版，系统生成';
		$model_refund->is_organizer   = isset($model->order_organizer_id) && $model->order_organizer_id ? RefundLog::is_organizer_yes:RefundLog::is_organizer_no;
		$model_refund->refund_price 	= $model->price;
		$model_refund->refund_type 	= RefundLog::refund_type_money;
		$model_refund->refund_status	= RefundLog::refund_status_cashing;
		$model_refund->audit_status	= RefundLog::audit_status_first;

		return $model_refund;
	}

}
