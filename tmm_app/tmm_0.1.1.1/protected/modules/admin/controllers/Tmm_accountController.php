<?php
/**
 * 
 * @author Changhai Zhan
 *	创建时间：2015-10-12 16:29:52 */
class Tmm_accountController extends MainController
{
	/**
	 * 默认操作数据模型
	 * @var string
	 */
	public $_class_model='Account';

	/**
	 * 查看详情
	 * @param integer $id
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id,array(
				'with'=>array(
					'Account_Agent'=>array('select'=>'phone'),
					'Account_User'=>array('select'=>'phone'),
					'Account_StoreUser'=>array('select'=>'phone'),
				),
			)),
		));
	}

	/**
	 *管理页
	 */
	public function actionAdmin()
	{
		$model=new Account('search');
		$model->unsetAttributes();  // 删除默认属性
		if(isset($_GET['Account']))
			$model->attributes=$_GET['Account'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}
}
