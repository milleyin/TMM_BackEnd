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
	 * 单个方法
	 * @see CController::actions()
	 */
	public function actions()
	{
		//{actions}
		return array(
				//活动退款（全）
				'refunds'=>array(
						'class'=>'admin.controllers.actions.orderActives.RefundsOrderActivesAction',
				),
				//活动退款（单）
				'refund'=>array(
						'class'=>'admin.controllers.actions.orderActives.RefundOrderActivesAction',
				),
		);
		//{actions}
	}
	
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
		$model = new OrderActives('search');
		$model->OrderActives_Actives = new Actives('search');
		$model->OrderActives_Shops = new Shops('search');
	
		// 删除默认属性
		$model->unsetAttributes();
		$model->OrderActives_Actives->unsetAttributes();
		$model->OrderActives_Shops->unsetAttributes();

		if (isset($_GET['OrderActives']))
			$model->attributes = $_GET['OrderActives'];
		if (isset($_GET['Actives']))
			$model->OrderActives_Actives->attributes = $_GET['Actives'];
		if (isset($_GET['Shops']))
			$model->OrderActives_Shops->attributes = $_GET['Shops'];

		$this->render('admin', array(
			'model'=>$model,
		));
	}
}
