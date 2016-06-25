<?php
/**
 * 
 * @author Changhai Zhan
 *	创建时间：2015-09-25 11:30:47 */
class Tmm_orderOrganizerController extends MainController
{
	/**
	 * 默认操作数据模型
	 * @var string
	 */
	public $_class_model='OrderOrganizer';

	/**
	 * 查看详情
	 * @param integer $id
	 */
	public function actionView($id)
	{
		$this->addCss(Yii::app()->baseUrl.'/css/admin/main/right/group_items.css');

		$model = $this->loadModel($id,array(
			'with'=>array(
				'OrderOrganizer_OrderItems'=>array(
					'with'=>array(
						'OrderItems_OrderItemsFare',
						'OrderItems_StoreUser'=>array('with'=>array('Store_Content')),
						'OrderItems_ItemsClassliy',
					)
				),

			),
			'condition'=>'t.shops_c_id=:shops_c_id',
			'params'=>array(':shops_c_id'=>3),
			'order'=>'OrderOrganizer_OrderItems.shops_day_sort,OrderOrganizer_OrderItems.shops_half_sort,OrderOrganizer_OrderItems.shops_sort',
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
		$model=new OrderOrganizer('search');
		$model->unsetAttributes();  // 删除默认属性
		if(isset($_GET['OrderOrganizer']))
			$model->attributes=$_GET['OrderOrganizer'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}
	
	/**
	 * 禁用
	 * @param integer $id
	 */
	public function actionDisable($id){
		if($this->loadModel($id,'`status`=1 AND ( group_group='.OrderOrganizer::group_group_cancel.' OR group_group='.OrderOrganizer::group_group_already_peer.')')->updateByPk($id,array('status'=>0)))
			$this->log('禁用结伴游(订单)',ManageLog::admin,ManageLog::update);
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
			
	}
	
	/**
	 * 激活
	 * @param integer $id
	 */
	public function actionStart($id){
		if($this->loadModel($id,'`status`=0 AND ( group_group='.OrderOrganizer::group_group_cancel.' OR group_group='.OrderOrganizer::group_group_already_peer.')')->updateByPk($id,array('status'=>1)))
	 		$this->log('激活结伴游(订单)',ManageLog::admin,ManageLog::update);
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));	
	}

	/**
	 * 取消
	 * @param $id\
	 */
	public function actionCancel($id){
		if($this->loadModel($id,' ( group_group='.OrderOrganizer::group_group_peering.' OR group_group='.OrderOrganizer::group_group_already_peer.')')->updateByPk($id,array('group_group'=>OrderOrganizer::group_group_cancel)))
			$this->log('禁用结伴游(订单)',ManageLog::admin,ManageLog::update);
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
	}
}
