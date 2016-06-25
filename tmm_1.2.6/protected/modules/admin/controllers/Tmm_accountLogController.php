<?php
/**
 * 资金流水
 * @author Changhai Zhan
 *	创建时间：2015-12-11 17:59:39 */
class Tmm_accountLogController extends MainController
{
	/**
	 * 默认操作数据模型
	 * @var string
	 */
	public $_class_model='AccountLog';

	/**
	 * 查看详情
	 * @param integer $id
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id,array(
				'with'=>array(
					//账户
					'AccountLog_Agent'=>array('select'=>'phone'),
					'AccountLog_User'=>array('select'=>'phone'),
					'AccountLog_StoreUser'=>array('select'=>'phone'),
					//To
					'AccountLog_Agent_TO'=>array('select'=>'phone'),
					'AccountLog_User_TO'=>array('select'=>'phone'),
					'AccountLog_StoreUser_TO'=>array('select'=>'phone'),
					//manage
					'AccountLog_Agent_Manage'=>array('select'=>'phone'),
					'AccountLog_User_Manage'=>array('select'=>'phone'),
					'AccountLog_StoreUser_Manage'=>array('select'=>'phone'),
					'AccountLog_Admin_Manage'=>array('select'=>'username'),
						
					// 关联 订单项目详情
					'AccountLog_OrderItems'=>array('select'=>'items_name'),
					//关联 活动总订单
					'AccountLog_OrderActives'=>array('select'=>'actives_no'),
					//关联 订单
					'AccountLog_Order'=>array('select'=>'order_no'),
				),
			)),
		));
	}
	
	/**
	 *管理页
	 */
	public function actionAdmin($account_id='',$account_type='')
	{
		//防止表格溢出
		//$this->addCss(Yii::app()->baseUrl.'/css/admin/main/right/table.css');
		$model=new AccountLog('search');
		$model->unsetAttributes();  // 删除默认属性
		
		if($account_id != '')
			$model->accurate_id = $account_id;
		if($account_type != '')
			$model->accurate_type = $account_type;
		
		if(isset($_GET['AccountLog']))
			$model->attributes = $_GET['AccountLog'];
				
		$this->render('admin',array(
			'model'=>$model,
		));
	}
}
