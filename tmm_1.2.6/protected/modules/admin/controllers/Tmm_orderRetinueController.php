<?php
/**
 * 
 * @author Changhai Zhan
 *	创建时间：2015-10-10 09:39:25 */
class Tmm_orderRetinueController extends MainController
{
	/**
	 * 默认操作数据模型
	 * @var string
	 */
	public $_class_model='OrderRetinue';

	/**
	 * 查看详情
	 * @param integer $id
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}
	
	/**
	 *管理页
	 */
	public function actionAdmin()
	{
		$model=new OrderRetinue('search');
		$model->unsetAttributes();  // 删除默认属性
		if(isset($_GET['OrderRetinue']))
			$model->attributes=$_GET['OrderRetinue'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * 订单随行人员查看
	 */
	public function actionRetinues($order_id)
	{
		$model=new OrderRetinue('search');
		$model->unsetAttributes();  // 删除默认属性
		if(isset($_GET['OrderRetinue']))
			$model->attributes=$_GET['OrderRetinue'];

		$this->render('retinues',array(
			'model'=>$model,
			'order_id'=>$order_id
		));	
	}
}
