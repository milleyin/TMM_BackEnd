<?php
/**
 * 
 * @author Changhai Zhan
 *	创建时间：2016-04-14 11:51:30 */
class Tmm_selectController extends MainController
{
	/**
	 * 默认操作数据模型
	 * @var string
	 */
	public $_class_model = 'Select';

	/**
	 * 查看详情
	 * @param integer $id
	 */
	public function actionView($id)
	{		
		$this->render('view', array(
			'model'=>$this->loadModel($id, array(
				'with'=>array(
						'Select_Ad',
						'Select_Shops',
						'Select_Admin',
				))
			)
		));
	}

	/**
	 * 创建
	 */
	public function actionCreate($id, $type=Select::type_actives)
	{
		if (isset($_POST['Select']))
		{
			if ($this->loadSelect($id, $type) && Select::saveSelected($_POST['Select'], $id, $type) && $this->log('选择添加' . Select::$_type[$type], ManageLog::admin, ManageLog::create))
				echo 1;
		}
	}

	/**
	 * 更新
	 * @param integer $id
	 */
	public function actionUpdate($id, $type=Select::type_actives)
	{
		if (isset($_POST['Select']))
		{
			if (Select::deleteSelected($_POST['Select'], $id, $type) && $this->log('删除选择' . Select::$_type[$type], ManageLog::admin, ManageLog::delete))
				echo 1;
		}
	}
	
	/**
	 * 更新排序
	 */
	public function actionSort()
	{
		if (isset($_POST['namename']) && is_array($_POST['namename']))
		{
			$model = new Select;
			$model->scenario = 'sort';
			foreach ($_POST['namename'] as $id=>$sort)
			{
				$attributes = array('sort'=>$sort);
				$model->attributes = $attributes;
				$model->validate() && $model->updateByPk($id, $attributes);
			}
		}
		$this->back();
	}
	
	/**
	 * 删除
	 * @param integer $id
	 */
	public function actionDelete($id)
	{
		if ($this->loadModel($id, '`status`=:status', array(':status'=>Select::status_suc))->delete())
			$this->log('删除选择', ManageLog::admin, ManageLog::delete);
			
		if ( !isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
	}

	/**
	 *管理页
	 */
	public function actionAdmin($id='')
	{
		$model = new Select('search');
		// 删除默认属性
		$model->unsetAttributes();
		if (isset($_GET['Select']))
			$model->attributes = $_GET['Select'];
		if ($id != '')
		{
			$model->Select_Ad = Ad::model()->findByPk($id, 'p_id=0 AND (type=:actives OR type=:nearby)', array(
					':actives'=>Ad::type_actives,
					':nearby'=>Ad::type_nearby,
			));
			$model->to_id = '=' . $id;
			if ($model->Select_Ad)
			$ad = array_flip(Select::$__ad);
			$model->type = $ad[$model->Select_Ad->type];
		}
		$this->render('admin', array(
			'model'=>$model,
		));
	}
	
	/**
	 * 选择页面
	 * @param unknown $id
	 */
	public function actionActives($id)
	{
		$model = new Actives('search');
		$model->Actives_Shops = new Shops('search');
		// 删除默认属性
		$model->unsetAttributes();
		$model->Actives_Shops->unsetAttributes();
		//赋值 搜索
		if (isset($_GET['Actives']))
			$model->attributes = $_GET['Actives'];
		if (isset($_GET['Shops']))
			$model->Actives_Shops->attributes = $_GET['Shops'];
		
		$this->render('actives', array(
				'model'=>$model,
				'select'=>$this->loadSelect($id, Select::type_actives)
		));
	}
	
	/**
	 *管理页
	 */
	public function actionNearby($id)
	{
		$model = new Shops('search');
		// 删除默认属性
		$model->unsetAttributes();
		if (isset($_GET['Shops']))
			$model->attributes = $_GET['Shops'];
			
		$this->render('nearby', array(
				'model'=>$model,
				'select'=>$this->loadSelect($id, Select::type_nearby)
		));
	}
	
	/**
	 * 加载归属选择类型
	 * @param unknown $id
	 * @param unknown $type
	 * @return unknown
	 */
	public function loadSelect($id, $type)
	{
		if (isset(Select::$__ad[$type]))
		{
			$this->_class_model = 'Ad';
			$criteria = new CDbCriteria;
			$criteria->addColumnCondition(array(
				'type'=>Select::$__ad[$type],
				'p_id'=>0,
				'status'=>Ad::status_suc,
			));
			return $this->loadModel($id, $criteria);
		}
		throw new CHttpException(404,'没有找到相关的数据！.');
	}
}
