<?php
/**
 * 
 * @author Changhai Zhan
 *	创建时间：2015-10-08 16:11:56 */
class Tmm_orderController extends MainController
{
	/**
	 * 默认操作数据模型
	 * @var string
	 */
	public $_class_model='Order';

	/**
	 * 查看详情====自助游
	 * @param integer $id
	 */
	public function actionView($id)
	{
		$this->addCss(Yii::app()->baseUrl.'/css/admin/main/right/group_items.css');
		//$this->addCss(Yii::app()->baseUrl.'/css/admin/main/right/pack_items.css');

		$model=$this->loadModel($id,array(
			'with'=>array(
				'Order_OrderRetinue',
				'Order_User',
				'Order_OrderShops'=>array(
					'with'=>array(
						'OrderShops_OrderItems'=>array(
							'with'=>array(
								'OrderItems_OrderItemsFare',
								'OrderItems_StoreUser'=>array('with'=>array('Store_Content')),
								'OrderItems_ItemsClassliy',
							),
							'order'=>'OrderShops_OrderItems.shops_day_sort,OrderShops_OrderItems.shops_half_sort,OrderShops_OrderItems.shops_sort',
						)
					),
				),
			),
		));

		$this->render('view',array(
			'model'=>$model,
		));
	}

	/**
	 * 查看详情====活动
	 * @param integer $id
	 */
	public function actionActives_view($id){

//		$model = $this->loadModel($id,array(
//			'with'=>array(
//				'Order_OrderRetinue',
//				'Order_User',
//				'Order_OrderActives'=>array(
//					'with'=>array(
//						'OrderActives_Actives',
//						'OrderActives_OrderItems'=>array(
//							'with'=>array(
//								'OrderItems_OrderItemsFare',
//								'OrderItems_StoreUser'=>array('with'=>array('Store_Content')),
//								'OrderItems_ItemsClassliy',
//							),
//							'order'=>'OrderActives_OrderItems.shops_day_sort,OrderActives_OrderItems.shops_half_sort,OrderActives_OrderItems.shops_sort',
//						),
//					),
//				),
//			),
//		));

		$model = $this->loadModel($id,array(
			'with'=>array(
				'Order_OrderRetinue',
				'Order_User',
				'Order_OrderActives'=>array(
					'with'=>array(
						'OrderActives_Actives',
					),
				),
				'Order_OrderItems'=>array(
					'with'=>array(
						'OrderItems_OrderItemsFare',
						'OrderItems_StoreUser'=>array('with'=>array('Store_Content')),
						'OrderItems_ItemsClassliy',
						'OrderItems_OrderItems',
					),
					'order'=>'Order_OrderItems.shops_day_sort,Order_OrderItems.shops_half_sort,Order_OrderItems.shops_sort',
				),
			),
		));
		$this->render('actives_view',array(
			'model'=>$model,
		));
	}
	/**
	 *管理页======自助游
	 */
	public function actionAdmin()
	{
		$model=new Order('search');
		$model->unsetAttributes();  // 删除默认属性
		if(isset($_GET['Order']))
			$model->attributes=$_GET['Order'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}
	/**
	 * 管理而=====活动
	 */
	public function actionActives(){
		$model=new Order('search');
		$model->unsetAttributes();  // 删除默认属性
		if(isset($_GET['Order']))
			$model->attributes=$_GET['Order'];


		$order_actives_id = '';
		if(isset($_GET['order_actives_id']) &&  $_GET['order_actives_id']) {
			$order_actives_id = $_GET['order_actives_id'];
		}

		$this->render('actives',array(
			'model'=>$model,
			'order_actives_id'=>$order_actives_id,
		));
	}

	/**
	 * 随行人员
	 * @param $id
	 * @throws CHttpException
	 */
	public function actionRetinue($id){
		$model=new Retinue('search');
		$model->unsetAttributes();  // 删除默认属性
		if(isset($_GET['Retinue']))
			$model->attributes=$_GET['Retinue'];

		$this->render('retinue',array(
			'model'=>$model,
			'select'=>$this->loadModel($id,'`status`=1'),
		));
	}

	/**
	 * 申请退款
	 * @param $id
	 */
	public function actionRefund($id){

		//当前ID 是否可以退款
		$model = $this->loadModel($id,
			'order_status=:order_status AND status_go=:status_go AND centre_status=:centre_status
			 AND pay_status=:pay_status AND status=:status ',
			array(
				':order_status'=>Order::order_status_user_pay,	//订单的状态 已付款
				':status_go'=>Order::status_go_yes,					//是否出游	确认出游
				':centre_status'=>Order::centre_status_not,		//是否可以支付 不可支付
				':pay_status'=>Order::pay_status_yes,				//没有支付 已支付
				':status'=>Order::status_yes,						//有效的订单
			)
		);

		$refund = RefundLog::model()->find(
			array(
			'select'=>array('id'),
			'order' => 'id DESC',
			'condition' => 'order_id=:order_id AND audit_status >=:audit_status',
			'params' => array(':order_id'=>$id,':audit_status'=>RefundLog::audit_status_first,)
		));

		if($refund)
			return true;

		//更新 退款记录
		//开启事务
		$transaction=$model->dbConnection->beginTransaction();
		try {
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

			if( !$model_refund->save(false))
				throw new Exception("申请自助游订单退款 更新退款订单失败");
			// 更新订单状态为正在退款中....
			//		$model->
				$return = $this->log('申请自助游订单退款',ManageLog::admin,ManageLog::create);
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
			$this->render('admin');



	}



//	/**
//	 * 删除
//	 * @param integer $id
//	 */
//	public function actionDelete($id)
//	{
//		//$this->loadModel($id)->delete();
//		if($this->loadModel($id,'`status`=0')->updateByPk($id,array('status'=>-1)))
//			$this->log('删除Order',ManageLog::admin,ManageLog::delete);
//
//		if(!isset($_GET['ajax']))
//			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
//	}
//
//	/**
//	 * 还原
//	 * @param integer $id
//	 */
//	public function actionRestore($id)
//	{
//		if($this->loadModel($id,'`status`=-1')->updateByPk($id,array('status'=>1)))
//			$this->log('还原Order',ManageLog::admin,ManageLog::update);
//
//		if(!isset($_GET['ajax']))
//			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
//	}
//
//	/**
//	 * 审核通过
//	 * @param integer $id
//	 */
//	public function actionPass($id){
//
//	}
//
//	/**
//	 * 审核不通过
//	 * @param integer $id
//	 */
//	public function actionNopass($id){
//
//	}
//
//
//	/**
//	 * 垃圾回收页
//	 */
//	public function actionIndex()
//	{
//		$criteria=new CDbCriteria;
//		//$criteria->with=array();
//		$criteria->addColumnCondition(array('status'=>-1));
//
//		$model=new Order;
//
//		$this->render('index',array(
//			'model'=>$model->search($criteria),
//		));
//	}
//
//
//
//	/**
//	 * 禁用
//	 * @param integer $id
//	 */
//	public function actionDisable($id){
//		if($this->loadModel($id,'`status`=1')->updateByPk($id,array('status'=>0)))
//			$this->log('禁用Order',ManageLog::admin,ManageLog::update);
//		if(!isset($_GET['ajax']))
//			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
//
//	}
//
//	/**
//	 * 激活
//	 * @param integer $id
//	 */
//	public function actionStart($id){
//		if($this->loadModel($id,'`status`=0')->updateByPk($id,array('status'=>1)))
//	 		$this->log('激活Order',ManageLog::admin,ManageLog::update);
//		if(!isset($_GET['ajax']))
//			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
//	}
}
