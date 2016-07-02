<?php
/**
 * 
 * @author Changhai Zhan
 *	创建时间：2016-04-01 10:41:56 */
class ShopsController extends OperatorMainController
{
	/**
	 * 默认操作数据模型
	 * @var string
	 */
	public $_class_model = 'Shops';

	/**
	 *管理页 觅境管理页
	 */
	public function actionAdmin()
	{
		$model = new Shops('operatorSearch');
		// 删除默认属性
		$model->unsetAttributes();
		if (isset($_GET['Shops']))
			$model->attributes = $_GET['Shops'];
	
		$this->render('admin', array(
				'model'=>$model,
		));
	}
	
	/**
	 * 加载条件
	 * @param unknown $status
	 * @param unknown $audit
	 * @return CDbCriteria
	 */
	public function loadCriteria($status = Shops::status_offline , $audit = Shops::audit_draft, $condition = '', $params = array())
	{
		$criteria = new CDbCriteria;
		$criteria->addColumnCondition(array(
				'agent_id'=>Yii::app()->operator->id,
				'status'=>$status,
				'audit'=>$audit,
		));
		$criteria->addCondition('c_id=:dot OR c_id=:thrand');
		$criteria->params[':dot'] = Shops::shops_dot;
		$criteria->params[':thrand'] = Shops::shops_thrand;
		if ($condition != '')
		{
			$criteria->addCondition($condition);
			$criteria->params = array_merge($criteria->params, $params);
		}
		return $criteria;
	}
	
	/**
	 * 设为可卖
	 * @param integer $id
	 */
	public function actionSale_yes($id)
	{
		if ($this->loadModel($id, $this->loadCriteria(
					Shops::status_offline,
					Shops::audit_draft,
					'is_sale=:is_sale',
					array(':is_sale'=>Shops::is_sale_not)
				))->updateByPk($id, array('is_sale'=>Shops::is_sale_yes, 'up_time'=>time()))
			)
			$this->log('设置商品为可卖品', ManageLog::operator, ManageLog::update);
	
		if ( !isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
	}
	
	/**
	 * 设为非卖品
	 * @param integer $id
	 */
	public function actionSale_no($id)
	{
		if ($this->loadModel($id, $this->loadCriteria(
					Shops::status_offline,
					Shops::audit_draft,
					'is_sale=:is_sale',
					array(':is_sale'=>Shops::is_sale_yes)
				))->updateByPk($id, array('is_sale'=>Shops::is_sale_not, 'up_time'=>time()))
			)
			$this->log('设置商品为可卖品', ManageLog::operator, ManageLog::update);
	
		if ( !isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
	}
	
	/**
	 * 提交审核
	 * @param unknown $id
	 */
	public function actionConfirm($id)
	{
		if ($this->loadModel($id, $this->loadCriteria(Shops::status_offline, Shops::audit_draft))->updateByPk($id, array('audit'=>Shops::audit_pending, 'up_time'=>time())))
			$this->log('提交审核', ManageLog::operator, ManageLog::update);
		
		if ( !isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
	}
	
	/**
	 * 下线
	 * @param integer $id
	 */
	public function actionDisable($id)
	{
		if ($this->loadModel($id, $this->loadCriteria(Shops::status_online, Shops::audit_pass))->updateByPk($id, array('status'=>Shops::status_offline, 'up_time'=>time())))
			$this->log('觅境下线', ManageLog::operator, ManageLog::update);
		
		if ( !isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
	}
	
	/**
	 * 激活
	 * @param integer $id
	 */
	public function actionStart($id)
	{
		if ($this->loadModel($id, $this->loadCriteria(Shops::status_offline, Shops::audit_pass, 'list_info!=""'))->updateByPk($id, array('status'=>Shops::status_online, 'up_time'=>time())))
	 		$this->log('觅境上线', ManageLog::operator, ManageLog::update);
	 	
		if ( !isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
	}
}
