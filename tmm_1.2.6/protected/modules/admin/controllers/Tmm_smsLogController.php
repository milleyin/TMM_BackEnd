<?php
/**
 * 
 * @author Changhai Zhan
 *	创建时间：2015-09-01 14:35:40 */
class Tmm_smsLogController extends MainController
{
	/**
	 * 默认操作数据模型
	 * @var string
	 */
	public $_class_model='SmsLog';

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
		$model=new SmsLog('search');
		$model->unsetAttributes();  // 删除默认属性
		if(isset($_GET['SmsLog']))
			$model->attributes=$_GET['SmsLog'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}
	
}
