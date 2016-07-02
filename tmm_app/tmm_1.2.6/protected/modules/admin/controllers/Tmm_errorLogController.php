<?php
/**
 * 
 * @author Changhai Zhan
 *	创建时间：2015-08-11 10:57:55 */
class Tmm_errorLogController extends MainController
{
	/**
	 * 默认操作数据模型
	 * @var string
	 */
	public $_class_model='ErrorLog';

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
		$model=new ErrorLog('search');
		$model->unsetAttributes();  // 删除默认属性
		if(isset($_GET['ErrorLog']))
			$model->attributes=$_GET['ErrorLog'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}
}
