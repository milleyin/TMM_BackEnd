<?php
/**
 * 
 * @author Changhai Zhan
 *	创建时间：2015-08-04 18:58:46 */
class Tmm_auditLogController extends MainController
{
	/**
	 * 默认操作数据模型
	 * @var string
	 */
	public $_class_model='AuditLog';

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
		$model=new AuditLog('search');
		$model->unsetAttributes();  // 删除默认属性
		if(isset($_GET['AuditLog']))
			$model->attributes=$_GET['AuditLog'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}
}
