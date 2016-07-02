<?php
/**
 * 
 * @author Changhai Zhan
 *	创建时间：2015-08-06 18:25:44 */
class Tmm_depositLogController extends MainController
{
	/**
	 * 默认操作数据模型
	 * @var string
	 */
	public $_class_model='DepositLog';

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
		$model=new DepositLog('search');
		$model->unsetAttributes();  // 删除默认属性
		if(isset($_GET['DepositLog']))
			$model->attributes=$_GET['DepositLog'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}
}
