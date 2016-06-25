<?php
/**
 * 
 * @author Changhai Zhan
 *	创建时间：2015-12-16 15:18:36 */
class Tmm_cashController extends MainController
{
	/**
	 * 默认操作数据模型
	 * @var string
	 */
	public $_class_model='Cash';

	/**
	 * 查看详情
	 * @param integer $id
	 */
	public function actionView($id)
	{
		$model = $this->loadModel($id,array(
			'with'=>array(
				'CashFirst_Admin'=>array('select'=>'id,name') ,
				'CashDouble_Admin'=>array('select'=>'id,name'),
				'CashSubmit_Admin'=>array('select'=>'id,name'),
			)
		));
		$this->render('view',array(
			'model'=>$model,
		));
	}


	/**
	 * 审核 model
	 * @param $id
	 * @return unknown
	 * @throws CHttpException
	 */
	public function audit_model($id){
		$model = $this->loadModel($id,array(
			'with'=>array(
				'CashFirst_Admin'=>array('select'=>'id,name') ,
				'CashDouble_Admin'=>array('select'=>'id,name'),
				'CashSubmit_Admin'=>array('select'=>'id,name'),
			)
		));
		return $model;
	}

	/**
	 * 初审=======view
	 * @param $id
	 */
	public function actionFirets($id){
		$model = $this->audit_model($id);
		$this->render('firets',array(
			'model'=>$model,
		));
	}

	/**
	 * 初审=======审核通过
	 * @param $id
	 */
	public function actionFirets_pass($id){
		//改变布局
		$this->layout='/layouts/column_right_audit';
		//查看是否需要审核
		$model=$this->loadModel($id,array(
			'condition'=>'t.status=1 AND t.audit_status=:audit_status',
			'params'=>array(':audit_status'=>Cash::audit_status_first),
		));
		$model->scenario='pass_firets';
		$this->_Ajax_Verify($model,'cash-form');

		if(isset($_POST['Cash']))
		{
			$model->attributes=$_POST['Cash'];
			$model->first_time=time();
			$model->admin_id_first=Yii::app()->admin->id;
			$model->audit_status=Cash::audit_status_double;// 审核通过
			$transaction=$model->dbConnection->beginTransaction();
			if($model->validate()){
				try
				{
					if($model->save(false)){
						$audit=new AuditLog;
						$audit->info=$model->remark_first;
						$audit->audit_who=AuditLog::admin;//记录 审核人 （审核人的id 在模型中）
						$audit->audit_element=Cash::audit_element($model->cash_type);//记录 被审核的类型
						$audit->element_id=$model->id;//记录 被审核id
						$audit->audit=AuditLog::pass;//记录 审核通过
						if($audit->save(false))
							$return=$this->log('添加审核结算申请（初审）记录',ManageLog::admin,ManageLog::create);
						else
							throw new Exception("添加审核结算申请（初审）日志错误");
					}else
						throw new Exception("审核通过结算申请（初审）保存错误");
					$transaction->commit();
				}
				catch(Exception $e)
				{
					$transaction->rollBack();
					$this->error_log($e->getMessage(),ErrorLog::admin,ErrorLog::update,ErrorLog::rollback,__METHOD__);
				}
			}
			if(isset($return))
				$this->back();
		}
		$this->render('_pass_firet',array(
			'model'=>$model,
		));
	}

	/**
	 * 初审=======审核不通过
	 * @param $id
	 */
	public function actionFirets_nopass($id){
		//改变布局
		$this->layout='/layouts/column_right_audit';
		$model=new AuditLog;

		$model->scenario='create';
		//查看是否需要审核
		$model->Audit_CashFiret=$this->loadModel($id,array(
			'condition'=>'t.status=1 AND t.audit_status=:audit_status',
			'params'=>array(':audit_status'=>Cash::audit_status_first),
		));
		$this->_Ajax_Verify($model,'audit-log-form');

		if(isset($_POST['AuditLog']))
		{
			$model->attributes=$_POST['AuditLog'];
			$model->audit_who=AuditLog::admin;//记录 审核人 （审核人的id 在模型中）
			$model->audit_element=Cash::audit_element($model->Audit_CashFiret->cash_type);//记录 被审核的
			$model->element_id=$model->Audit_CashFiret->id;//记录 被审核id
			$model->audit=AuditLog::nopass;//记录 审核不通过
			if($model->validate()){
				$transaction=$model->dbConnection->beginTransaction();
				try
				{
					// 更新审核日志
					if($model->save(false))
					{
						//角色钱包====解冻
						$return=Account::moneyEntryCashFailRmb(
							$model->Audit_CashFiret->price,
							array('account_id'=>$model->Audit_CashFiret->cash_id,'account_type'=>Cash::$_cash_type_account[$model->Audit_CashFiret->cash_type]),
							array('info_id'=>$model->Audit_CashFiret->id,'info'=>'提现（初审）失败','name'=>'提现（初审）失败','address'=>'')
						);
						if(! $return)
							throw new Exception("添加审核结算申请（初审）更新角色钱包出错");

//						$money = Account::unfreeze_money($model->Audit_CashFiret->cash_id,Cash::$_cash_type_account[$model->Audit_CashFiret->cash_type],$model->Audit_CashFiret->price);
//						if(!$money)
//							throw new Exception("添加审核结算申请（初审）更新角色钱包出错");

//						//项目账单详情
//						$bills = Bills::bills_account_status($model->Audit_CashFiret->id,Bills::cash_status_not_apply,true);
//						if(!$bills)
//							throw new Exception("添加审核结算申请（初审）更新项目账单详情出错");

						$model->Audit_CashFiret->audit_status=Cash::audit_status_first_not_pass;//审核不通过
						$model->Audit_CashFiret->cash_status=Cash::cash_status_cash_not;//设置失败
						$model->Audit_CashFiret->admin_id_first=Yii::app()->admin->id;   //审核人ID
						$model->Audit_CashFiret->remark_first=$model->info;
						$model->Audit_CashFiret->first_time=time();
						//更新申请记录表
						if($model->Audit_CashFiret->save(false))
							$return = $this->log('添加结算申请（初审）审核不通过记录', ManageLog::admin, ManageLog::create);
						else
							throw new Exception("添加结算申请（初审）更新申请记录表失败");
					}else
						throw new Exception("添加结算申请（初审）审核不通过日志错误");

					$transaction->commit();
				}
				catch(Exception $e)
				{
					$transaction->rollBack();
					$this->error_log($e->getMessage(),ErrorLog::admin,ErrorLog::create,ErrorLog::rollback,__METHOD__);
				}
			}
			if(isset($return))
				$this->back();
		}
		$this->render('/tmm_auditLog/_nopass',array(
			'model'=>$model,
		));
	}

	/**
	 * 复审=======view
	 * @param $id
	 */
	public function actionDoubles($id){
		$model = $this->audit_model($id);
		$this->render('doubles',array(
			'model'=>$model,
		));
	}

	/**
	 * 复审=======审核通过
	 * @param $id
	 */
	public function actionDoubles_pass($id){
		//改变布局
		$this->layout='/layouts/column_right_audit';
		//查看是否需要审核
		$model=$this->loadModel($id,array(
			'condition'=>'t.status=1 AND t.audit_status=:audit_status',
			'params'=>array(':audit_status'=>Cash::audit_status_double),
		));
		$model->scenario='pass_doubles';
		$this->_Ajax_Verify($model,'cash-form');

		if(isset($_POST['Cash']))
		{
			$model->attributes=$_POST['Cash'];
			$model->double_time=time();
			$model->admin_id_double=Yii::app()->admin->id;
			$model->audit_status=Cash::audit_status_submit;// 审核通过 待确认
			$transaction=$model->dbConnection->beginTransaction();
			if($model->validate()){
				try
				{
					if($model->save(false)){

//						//项目账单详情
//						$bills = Bills::bills_account_status($model->id,Bills::cash_status_cashing);
//						if(!$bills)
//							throw new Exception("添加审核结算申请（确认）更新项目账单详情出错");

						$audit=new AuditLog;
						$audit->info=$model->remark_double;
						$audit->audit_who=AuditLog::admin;//记录 审核人 （审核人的id 在模型中）
						$audit->audit_element=Cash::audit_element($model->cash_type);//记录 被审核的类型
						$audit->element_id=$model->id;//记录 被审核id
						$audit->audit=AuditLog::pass;//记录 审核通过
						if($audit->save(false))
							$return=$this->log('添加审核结算申请（复审）记录',ManageLog::admin,ManageLog::create);
						else
							throw new Exception("添加审核结算申请（复审）日志错误");
					}else
						throw new Exception("审核通过结算申请（复审）保存错误");
					$transaction->commit();
				}
				catch(Exception $e)
				{
					$transaction->rollBack();
					$this->error_log($e->getMessage(),ErrorLog::admin,ErrorLog::update,ErrorLog::rollback,__METHOD__);
				}
			}
			if(isset($return))
				$this->back();
		}
		$this->render('_pass_doubles',array(
			'model'=>$model,
		));
	}

	/**
	 * 复审=======审核不通过
	 * @param $id
	 */
	public function actionDoubles_nopass($id){
		//改变布局
		$this->layout='/layouts/column_right_audit';
		$model=new AuditLog;

		$model->scenario='create';
		//查看是否需要审核
		$model->Audit_CashFiret=$this->loadModel($id,array(
			'condition'=>'t.status=1 AND t.audit_status=:audit_status',
			'params'=>array(':audit_status'=>Cash::audit_status_double),
		));
		$this->_Ajax_Verify($model,'audit-log-form');

		if(isset($_POST['AuditLog']))
		{
			$model->attributes=$_POST['AuditLog'];
			$model->audit_who=AuditLog::admin;//记录 审核人 （审核人的id 在模型中）
			$model->audit_element=Cash::audit_element($model->Audit_CashFiret->cash_type);//记录 被审核的
			$model->element_id=$model->Audit_CashFiret->id;//记录 被审核id
			$model->audit=AuditLog::nopass;//记录 审核不通过
			if($model->validate()){
				$transaction=$model->dbConnection->beginTransaction();
				try
				{
					// 更新审核日志
					if($model->save(false))
					{
						//角色钱包====解冻
						$return=Account::moneyEntryCashFailRmb(
							$model->Audit_CashFiret->price,
							array('account_id'=>$model->Audit_CashFiret->cash_id,'account_type'=>Cash::$_cash_type_account[$model->Audit_CashFiret->cash_type]),
							array('info_id'=>$model->Audit_CashFiret->id,'info'=>'提现（复审）失败','name'=>'提现（复审）失败','address'=>'')
						);
						if(!$return)
							throw new Exception("添加审核结算申请（复审）更新角色钱包出错");

//						$money = Account::unfreeze_money($model->Audit_CashFiret->cash_id,Cash::$_cash_type_account[$model->Audit_CashFiret->cash_type],$model->Audit_CashFiret->price);
//						if(!$money)
//							throw new Exception("添加审核结算申请（复审）更新角色钱包出错");

//						//项目账单详情
//						$bills = Bills::bills_account_status($model->Audit_CashFiret->id,Bills::cash_status_not_apply,true);
//						if(!$bills)
//							throw new Exception("添加审核结算申请（复审）更新项目账单详情出错");

						$model->Audit_CashFiret->audit_status=Cash::audit_status_double_not_pass;//审核不通过
						$model->Audit_CashFiret->cash_status=Cash::cash_status_cash_not;//设置失败
						$model->Audit_CashFiret->admin_id_double=Yii::app()->admin->id;   //审核人ID
						$model->Audit_CashFiret->remark_double=$model->info;
						$model->Audit_CashFiret->double_time=time();
						//更新申请记录表
						if($model->Audit_CashFiret->save(false))
							$return = $this->log('添加结算申请（复审）审核不通过记录', ManageLog::admin, ManageLog::create);
						else
							throw new Exception("添加结算申请（复审）更新申请记录表失败");
					}else
						throw new Exception("添加结算申请（复审）审核不通过日志错误");

					$transaction->commit();
				}
				catch(Exception $e)
				{
					$transaction->rollBack();
					$this->error_log($e->getMessage(),ErrorLog::admin,ErrorLog::create,ErrorLog::rollback,__METHOD__);
				}
			}
			if(isset($return))
				$this->back();
		}
		$this->render('/tmm_auditLog/_nopass',array(
			'model'=>$model,
		));
	}

	/**
	 * 确认=======view
	 * @param $id
	 */
	public function actionSubmits($id){
		$model = $this->audit_model($id);
		$this->render('submits',array(
			'model'=>$model,
		));
	}

	/**
	 * 确认=======审核通过
	 * @param $id
	 */
	public function actionSubmits_pass($id){
		//改变布局
		$this->layout='/layouts/column_right_audit';
		//查看是否需要审核
		$model=$this->loadModel($id,array(
			'condition'=>'t.status=1 AND t.audit_status=:audit_status',
			'params'=>array(':audit_status'=>Cash::audit_status_submit),
		));
		$model->scenario='pass_submits';
		$this->_Ajax_Verify($model,'cash-form');

		if(isset($_POST['Cash']))
		{
			$model->attributes  = $_POST['Cash'];
			$model->submit_time = time();
			$model->admin_id_submit=Yii::app()->admin->id;
			$model->audit_status = Cash::audit_status_finish;// 审核通过 待确认
			$model->fact_price   = $model->price  - $model->fee_price; //实际到账金额
			$model->cash_status  = Cash::cash_status_cash;// 改成已提现
			$model->pay_time 	   = time();// 到账时间

			if($model->validate()){
				$transaction=$model->dbConnection->beginTransaction();
				try
				{
					if($model->save(false)){

						//角色钱包====提现成功Cash::$_cash_type_account[$model->Audit_CashFiret->cash_type]
						$return=Account::moneyDeductCashSuccessRmb(
							$model->price,
							array('account_id'=>$model->cash_id,'account_type'=>Cash::$_cash_type_account[$model->cash_type]),
							array('info_id'=>$model->id,'info'=>'提现成功','name'=>'提现成功','address'=>'')
						);
						if(! $return)
							throw new Exception("添加审核结算申请（确认）更新角色钱包出错");


//						//项目账单详情
//						$bills = Bills::bills_account_status($model->id,Bills::cash_status_close);
//						if(!$bills)
//							throw new Exception("添加审核结算申请（确认）更新项目账单详情出错");

						$audit=new AuditLog;
						$audit->info=$model->remark_submit;
						$audit->audit_who=AuditLog::admin;//记录 审核人 （审核人的id 在模型中）
						$audit->audit_element=Cash::audit_element($model->cash_type);//记录 被审核的类型
						$audit->element_id=$model->id;//记录 被审核id
						$audit->audit=AuditLog::pass;//记录 审核通过
						if($audit->save(false))
							$return=$this->log('添加审核结算申请（确认）记录',ManageLog::admin,ManageLog::create);
						else
							throw new Exception("添加审核结算申请（确认）日志错误");
					}else
						throw new Exception("审核通过结算申请（确认）保存错误");
					$transaction->commit();
				}
				catch(Exception $e)
				{
					$transaction->rollBack();
					$this->error_log($e->getMessage(),ErrorLog::admin,ErrorLog::update,ErrorLog::rollback,__METHOD__);
				}
			}
			if(isset($return))
				$this->back();
		}
		$this->render('_pass_submits',array(
			'model'=>$model,
		));
	}

	/**
	 * 确认=======审核不通过
	 * @param $id
	 */
	public function actionSubmits_nopass($id){
		//改变布局
		$this->layout='/layouts/column_right_audit';
		$model=new AuditLog;

		$model->scenario='create';
		//查看是否需要审核
		$model->Audit_CashFiret=$this->loadModel($id,array(
			'condition'=>'t.status=1 AND t.audit_status=:audit_status',
			'params'=>array(':audit_status'=>Cash::audit_status_submit),
		));
		$this->_Ajax_Verify($model,'audit-log-form');

		if(isset($_POST['AuditLog']))
		{

			$model->attributes=$_POST['AuditLog'];
			$model->audit_who=AuditLog::admin;//记录 审核人 （审核人的id 在模型中）
			$model->audit_element=Cash::audit_element($model->Audit_CashFiret->cash_type);//记录 被审核的
			$model->element_id=$model->Audit_CashFiret->id;//记录 被审核id
			$model->audit=AuditLog::nopass;//记录 审核不通过
			if($model->validate()){
				$transaction=$model->dbConnection->beginTransaction();
				try
				{
					// 更新审核日志
					if($model->save(false))
					{
						/**
						$id 	   =  $model->Audit_CashFiret->id;
						$price     =  $model->Audit_CashFiret->price;
						$cash_type = $model->Audit_CashFiret->cash_type;
						$cash_id   = $model->Audit_CashFiret->id;
						 */
						//角色钱包====解冻

						$return=Account::moneyEntryCashFailRmb(
							$model->Audit_CashFiret->price,
							array('account_id'=>$model->Audit_CashFiret->cash_id,'account_type'=>Cash::$_cash_type_account[$model->Audit_CashFiret->cash_type]),
							array('info_id'=>$model->Audit_CashFiret->id,'info'=>'提现（确认）失败','name'=>'提现（确认）失败','address'=>'')
						);
						if(!$return)
							throw new Exception("添加审核结算申请（确认）更新角色钱包出错");
//						$money = Account::unfreeze_money($model->Audit_CashFiret->cash_id,Cash::$_cash_type_account[$model->Audit_CashFiret->cash_type],$model->Audit_CashFiret->price);
//						if(!$money)
//							throw new Exception("添加审核结算申请（确认）更新角色钱包出错");

//						//项目账单详情
//						$bills = Bills::bills_account_status($model->Audit_CashFiret->id,Bills::cash_status_not_apply,true);
//						if(!$bills)
//							throw new Exception("添加审核结算申请（确认）更新项目账单详情出错");

						$model->Audit_CashFiret->audit_status=Cash::audit_status_submit_not_pass;//审核不通过
						$model->Audit_CashFiret->cash_status=Cash::cash_status_cash_not;//设置失败
						$model->Audit_CashFiret->admin_id_submit=Yii::app()->admin->id;   //审核人ID
						$model->Audit_CashFiret->remark_submit=$model->info;
						$model->Audit_CashFiret->submit_time=time();
						//更新申请记录表
						if($model->Audit_CashFiret->save(false))
							$return = $this->log('添加结算申请（确认）审核不通过记录', ManageLog::admin, ManageLog::create);
						else
							throw new Exception("添加结算申请（确认）更新申请记录表失败");
					}else
						throw new Exception("添加结算申请（确认）审核不通过日志错误");

					$transaction->commit();
				}
				catch(Exception $e)
				{
					$transaction->rollBack();
					$this->error_log($e->getMessage(),ErrorLog::admin,ErrorLog::create,ErrorLog::rollback,__METHOD__);
				}
			}
			if(isset($return))
				$this->back();
		}
		$this->render('/tmm_auditLog/_nopass',array(
			'model'=>$model,
		));
	}

	/**
	 *管理页
	 */
	public function actionAdmin()
	{
		$model=new Cash('search');
		$model->unsetAttributes();  // 删除默认属性
		if(isset($_GET['Cash']))
			$model->attributes=$_GET['Cash'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}
}
