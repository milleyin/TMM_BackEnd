<?php
/**
 * 
 * @author Changhai Zhan
 *	创建时间：2015-09-14 13:32:52 */
class Agent_orderController extends AgentController
{
	/**
	 * 默认操作数据模型
	 * @var string
	 */
	public $_class_model='Order';

	/**
	 * 查看详情
	 * @param integer $id
	 */
	public function actionView($id)
	{
		$this->addCss(Yii::app()->request->baseUrl.'/css/agent/css/details.css');
		$this->addCss(Yii::app()->request->baseUrl.'/css/agent/css/breadcrumbs.css');

		$model=$this->loadModel($id,array(
			'with'=>array(
				'Order_OrderRetinue',
				'Order_User',
				'Order_OrderShops'=>array(
					'with'=>array(
						'OrderShops_OrderItems'=>array(
							'with'=>array(
								'OrderItems_OrderItemsFare',
								'OrderItems_StoreUser'=>array(
									'with'=>'Store_Content',
									)
							),
					),)
				)
			),
			'condition'=>' `t`.`status`>=0 AND t.order_organizer_id =0   AND Order_OrderShops.shops_agent_id=:agent',
			'params'=>array(':agent'=>Yii::app()->agent->id),
		));
		// 统计我的收益
		$item_total_money = 0.00;
		foreach ($model->Order_OrderShops as $shops) {
			foreach ($shops->OrderShops_OrderItems as  $items) {
				$item_total_money += $items->total * $items->items_push_agent / 100;
			}
		}

		$this->render('view',array(
			'model'=>$model,
			'item_total_money'=> $this->money_floor($item_total_money,2,'floor'),
		));
	}

	/**
	 *管理页
	 */
	public function actionAdmin()
	{
		$this->addCss(Yii::app()->request->baseUrl.'/css/agent/css/normalize.css');
		$this->addCss(Yii::app()->request->baseUrl.'/css/agent/css/subbusiness.css');

		$model=new Order('search');
		$model->unsetAttributes();  // 删除默认属性
		if(isset($_GET['Order']))
			$model->attributes=$_GET['Order'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * 结伴游=====列表
	 */
	public function actionGroup(){
		$this->addCss(Yii::app()->request->baseUrl.'/css/agent/css/normalize.css');
		$this->addCss(Yii::app()->request->baseUrl.'/css/agent/css/business.css');

		$model=new Order('search');
		$model->unsetAttributes();  // 删除默认属性
		if(isset($_GET['Order']))
			$model->attributes=$_GET['Order'];

		$this->render('group',array(
			'model'=>$model,
		));

	}

	/**
	 * 查看详情=====结伴游
	 * @param integer $id
	 */
	public function actionGroup_view($id)
	{
		$this->addCss(Yii::app()->request->baseUrl.'/css/agent/css/details.css');
		$this->addCss(Yii::app()->request->baseUrl.'/css/agent/css/breadcrumbs.css');

		$model=$this->loadModel($id,array(
			'with'=>array(
				'Order_OrderOrganizer',
				'Order_OrderRetinue',
				'Order_User',
				'Order_OrderShops'=>array(
					'with'=>array(
						'OrderShops_OrderItems'=>array(
							'with'=>array(
								'OrderItems_OrderItemsFare',
								'OrderItems_StoreUser'=>array(
									'with'=>'Store_Content',
								)
							),
						),)
				)
			),
			'condition'=>' `t`.`status`>=0  AND t.order_organizer_id !=0  AND Order_OrderShops.shops_agent_id=:agent',
			'params'=>array(':agent'=>Yii::app()->agent->id),
		));

//		$this->p_r($model);exit;
		$this->render('group_view',array(
			'model'=>$model,
		));
	}
	


}
