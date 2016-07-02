<?php
/**
 * 
 * @author Changhai Zhan
 *	创建时间：2015-08-06 15:51:55 */
class Tmm_pushController extends MainController
{
	/**
	 * 默认操作数据模型
	 * @var string
	 */
	public $_class_model='Push';

	/**
	 * 查看详情
	 * @param integer $id
	 */
	public function actionView($id)
	{
		$model=$this->loadModel($id,array('with' => array(
			'Push_Items',
			'Push_Admin'
		)));

		$this->render('view',array(
			'model'=>$model,
		));
	}
	
	/**
	 *管理页
	 */
	public function actionAdmin($push_id='')
	{
		$model = new Push('search');
		$model->unsetAttributes();  // 删除默认属性
		$model->Push_Items = new Items('search');
		$model->Push_Items->unsetAttributes();  // 删除默认属性
		if($push_id != '')
			$model->push_id = $push_id;
		if(isset($_GET['Push']))
			$model->attributes=$_GET['Push'];
		if(isset($_GET['Items']))
			$model->Push_Items->attributes=$_GET['Items'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * 禁用
	 * @param integer $id
	 */
	public function actionDisable($id)
	{
		if($this->loadModel($id,'`status`=:status',array(':status'=>Push::status_start))->updateByPk($id,array('status'=>Push::status_disable)))
			$this->log('禁用项目的定时分成',ManageLog::admin,ManageLog::update);
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
	}
	
	/**
	 *	禁用该项目的全部定时分成
	 * @param integer $id
	 */
	public function actionDisables($id)
	{
		$model = $this->loadModel($id,'status=:status',array(':status'=>Push::status_start));
		$return = $model->updateAll(
				array(
						'status'=>Push::status_disable,
				),
				'status=:status AND push_id=:push_id AND push_element=:push_element',
				array(
						':status'=>Push::status_start,
						':push_id'=>$model->push_id,
						':push_element'=>$model->push_element,
		));
		if(isset($return) && $return)
			$this->log('禁用项目的全部定时分成',ManageLog::admin,ManageLog::update);
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
	}
}
