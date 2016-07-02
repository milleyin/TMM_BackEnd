<?php
/**
 * 
 * @author Changhai Zhan
 *	创建时间：2015-08-07 14:48:10 */
class Tmm_tagsElementController extends MainController
{
	/**
	 * 默认操作数据模型
	 * @var string
	 */
	public $_class_model='TagsElement';

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
	 * 垃圾回收页
	 */
	public function actionIndex()
	{
		$criteria=new CDbCriteria;
		//$criteria->with=array();
		$criteria->addColumnCondition(array('status'=>-1));

		$model=new TagsElement;
		
		$this->render('index',array(
			'model'=>$model->search($criteria),
		));
	}
	
	/**
	 *管理页
	 */
	public function actionAdmin()
	{
		$model=new TagsElement('search');
		$model->unsetAttributes();  // 删除默认属性
		if(isset($_GET['TagsElement']))
			$model->attributes=$_GET['TagsElement'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}
}
