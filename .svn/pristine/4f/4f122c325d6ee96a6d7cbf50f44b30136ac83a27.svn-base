<?php
/**
 * 
 * @author Changhai Zhan
 *	创建时间：2016-04-01 10:43:14 */
class OrderController extends OperatorMainController
{
	/**
	 * 默认操作数据模型
	 * @var string
	 */
	public $_class_model = 'Order';

	/**
	 * 查看详情
	 * @param integer $id
	 */
	public function actionView($id)
	{
		$this->addCss(Yii::app()->baseUrl.'/css/operator/main/right/dot/view.css');
		$this->addCss(Yii::app()->baseUrl.'/css/operator/main/right/thrand/view.css');
		//条件
		$criteria = new CDbCriteria;
		//关系
		$criteria->with = array(
			'Order_User',
			'Order_OrderShops'=>array(
				'with'=>array(
					'OrderShops_OrderItems'=>array(
						'with'=>array(
							'OrderItems_OrderItemsFare',
							'OrderItems_Agent',
							'OrderItems_StoreUser'=>array('with'=>array('Store_Content')),
							'OrderItems_ItemsClassliy',
						),
					),
				),
			),
		);
		//排序
		$criteria->order = 'OrderShops_OrderItems.shops_day_sort,OrderShops_OrderItems.shops_half_sort,OrderShops_OrderItems.shops_sort';
		//标准条件
		$criteria->addColumnCondition(array(
			'`Order_OrderShops`.`shops_agent_id`'=>Yii::app()->operator->id,
		));
		//觅境订单
		$criteria->addCondition('t.order_type=:dot OR t.order_type=:thrand');
		$criteria->params[':dot'] = Order::order_type_dot;
		$criteria->params[':thrand'] = Order::order_type_thrand;
		//渲染视图 加载数据
		$this->render('view', array(
			'model'=>$this->loadModel($id, $criteria),
		));
	}

	/**
	 *管理页
	 */
	public function actionAdmin()
	{
		$model = new OrderShops('operatorSearch');
		// 删除默认属性
		$model->unsetAttributes();
		$model->OrderShops_Order = new Order('operatorSearch');
		// 删除默认属性
		$model->OrderShops_Order->unsetAttributes();
		if (isset($_GET['OrderShops']))
			$model->attributes = $_GET['OrderShops'];
		if (isset($_GET['Order']))
			$model->OrderShops_Order->attributes = $_GET['Order'];
		
		$this->render('admin', array(
			'model'=>$model,
		));
	}
}
