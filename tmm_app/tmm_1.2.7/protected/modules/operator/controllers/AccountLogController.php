<?php
/**
 * 
 * @author Changhai Zhan
 *	创建时间：2016-04-01 10:47:00 */
class AccountLogController extends OperatorMainController
{
	/**
	 * 默认操作数据模型
	 * @var string
	 */
	public $_class_model = 'AccountLog';
	
	/**
	 *管理页
	 */
	public function actionAdmin()
	{
		$model = new AccountLog('operatorSearch');
		$model->unsetAttributes();  // 删除默认属性
		if (isset($_GET['AccountLog']))
			$model->attributes = $_GET['AccountLog'];

		$this->render('admin', array(
			'model'=>$model,
		));
	}
}
