<?php
/**
 * 
 * @author Changhai Zhan
 *	创建时间：2015-08-05 16:50:47 */
class Tmm_fareController extends MainController
{
	/**
	 * 默认操作数据模型
	 * @var string
	 */
	public $_class_model='Fare';

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
		$model=new Fare('search');
		$model->unsetAttributes();  // 删除默认属性
		if(isset($_GET['Fare']))
			$model->attributes=$_GET['Fare'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}
}
