<?php
/**
 * 
 * @author Changhai Zhan
 *	创建时间：2015-08-20 14:29:20 */
class Tmm_proController extends MainController
{
	/**
	 * 默认操作数据模型
	 * @var string
	 */
	public $_class_model='Pro';

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
		$model=new Pro('search');
		$model->unsetAttributes();  // 删除默认属性
		if(isset($_GET['Pro']))
			$model->attributes=$_GET['Pro'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}
}
