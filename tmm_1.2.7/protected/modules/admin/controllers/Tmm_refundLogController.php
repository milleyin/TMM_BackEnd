<?php
/**
 * 
 * @author Changhai Zhan
 *	创建时间：2015-11-06 14:30:08 */
class Tmm_refundLogController extends MainController
{
	/**
	 * 默认操作数据模型
	 * @var string
	 */
	public $_class_model='RefundLog';

	/**
	 * 查看详情
	 * @param integer $id
	 */
	public function actionView($id)
	{
		$model = $this->loadModel($id,array(
			'with'=>array(
				'RefundLogFirst_Admin'=>array('select'=>'id,name') ,
				'RefundLogDouble_Admin'=>array('select'=>'id,name') ,
				'RefundLogSubmit_Admin'=>array('select'=>'id,name') ,
				'RefundLog_Admin'=>array('select'=>'id,name') ,
				'RefundLog_User',
				'RefundLog_Order',
				'RefundLog_Refund',
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
				'RefundLogFirst_Admin'=>array('select'=>'id,name') ,
				'RefundLogDouble_Admin'=>array('select'=>'id,name') ,
				'RefundLogSubmit_Admin'=>array('select'=>'id,name') ,
				'RefundLog_Admin'=>array('select'=>'id,name') ,
				'RefundLog_User',
				'RefundLog_Order',
				'RefundLog_Refund',
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
			'params'=>array(':audit_status'=>RefundLog::audit_status_first),
		));

		$model->scenario='pass_firets';
		$this->_Ajax_Verify($model,'refundlog-form');

		if(isset($_POST['RefundLog']))
		{
			$model->attributes=$_POST['RefundLog'];
			$model->first_time=time();
			$model->admin_id_first=Yii::app()->admin->id;
			$model->audit_status=RefundLog::audit_status_double;// 审核通过
			$transaction=$model->dbConnection->beginTransaction();
			if($model->validate()){
				try
				{
					if($model->save(false)){
						$audit=new AuditLog;
						$audit->info=$model->remark_first;
						$audit->audit_who=AuditLog::admin;//记录 审核人 （审核人的id 在模型中）
						$audit->audit_element=AuditLog::organizer;//记录 被审核的类型
						$audit->element_id=$model->id;//记录 被审核id
						$audit->audit=AuditLog::pass;//记录 审核通过
						if($audit->save(false))
							$return=$this->log('添加审核退款申请（初审）记录',ManageLog::admin,ManageLog::create);
						else
							throw new Exception("添加审核退款申请（初审）日志错误");
					}else
						throw new Exception("审核通过退款申请（初审）保存错误");
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
		$model->Audit_RefundLogFiret=$this->loadModel($id,array(
			'condition'=>'t.status=1 AND t.audit_status=:audit_status',
			'params'=>array(':audit_status'=>RefundLog::audit_status_first),
		));
		$this->_Ajax_Verify($model,'audit-log-form');

		if(isset($_POST['AuditLog']))
		{
			$model->attributes=$_POST['AuditLog'];
			$model->audit_who=AuditLog::admin;//记录 审核人 （审核人的id 在模型中）
			$model->audit_element=AuditLog::organizer;//记录 被审核的类型
			$model->element_id=$model->Audit_RefundLogFiret->id;//记录 被审核id
			$model->audit=AuditLog::nopass;//记录 审核不通过
			if($model->validate()){
				$transaction=$model->dbConnection->beginTransaction();
				try
				{
					// 更新审核日志
					if($model->save(false))
					{
						$model->Audit_RefundLogFiret->audit_status=RefundLog::audit_status_first_not_pass;//审核不通过
						$model->Audit_RefundLogFiret->refund_status=RefundLog::refund_status_cash_not;//设置失败
						$model->Audit_RefundLogFiret->admin_id_first=Yii::app()->admin->id;   //审核人ID
						$model->Audit_RefundLogFiret->first_time=time();
						//更新申请记录表
						if($model->Audit_RefundLogFiret->save(false))
							$return = $this->log('添加退款申请（初审）审核不通过记录', ManageLog::admin, ManageLog::create);
						else
							throw new Exception("添加退款申请（初审）更新申请记录表失败");
					}else
						throw new Exception("添加退款申请（初审）审核不通过日志错误");

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
		$this->_Ajax_Verify($model,'refundlog-form');

		if(isset($_POST['RefundLog']))
		{
			$model->attributes=$_POST['RefundLog'];
			$model->double_time=time();
			$model->admin_id_double=Yii::app()->admin->id;
			$model->audit_status=RefundLog::audit_status_submit;// 审核通过 待确认
			$transaction=$model->dbConnection->beginTransaction();

			if($model->validate()){
				try
				{
					if($model->save(false)){


						$audit=new AuditLog;
						$audit->info=$model->remark_double;
						$audit->audit_who=AuditLog::admin;//记录 审核人 （审核人的id 在模型中）
						$audit->audit_element=AuditLog::organizer;//记录 被审核的类型
						$audit->element_id=$model->id;//记录 被审核id
						$audit->audit=AuditLog::pass;//记录 审核通过

						if($audit->save(false))
							$return=$this->log('添加审核退款申请（复审）记录',ManageLog::admin,ManageLog::create);
						else
							throw new Exception("添加审核退款申请（复审）日志错误");
					}else
						throw new Exception("审核通过退款申请（复审）保存错误");
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
		$model->Audit_RefundLogFiret=$this->loadModel($id,array(
			'condition'=>'t.status=1 AND t.audit_status=:audit_status',
			'params'=>array(':audit_status'=>RefundLog::audit_status_double),
		));
		$this->_Ajax_Verify($model,'audit-log-form');

		if(isset($_POST['AuditLog']))
		{
			$model->attributes=$_POST['AuditLog'];
			$model->audit_who=AuditLog::admin;//记录 审核人 （审核人的id 在模型中）
			$model->audit_element=AuditLog::organizer;//记录 被审核的类型
			$model->element_id=$model->Audit_RefundLogFiret->id;//记录 被审核id
			$model->audit=AuditLog::nopass;//记录 审核不通过

			if($model->validate()){
				$transaction=$model->dbConnection->beginTransaction();
				try
				{
					// 更新审核日志
					if($model->save(false))
					{
						$model->Audit_RefundLogFiret->audit_status=RefundLog::audit_status_double_not_pass;//审核不通过
						$model->Audit_RefundLogFiret->refund_status=RefundLog::refund_status_cash_not;//设置失败
						$model->Audit_RefundLogFiret->admin_id_double=Yii::app()->admin->id;   //审核人ID
						$model->Audit_RefundLogFiret->double_time=time();
						//更新申请记录表
						if($model->Audit_RefundLogFiret->save(false))
							$return = $this->log('添加退款申请（复审）审核不通过记录', ManageLog::admin, ManageLog::create);
						else
							throw new Exception("添加退款申请（复审）更新申请记录表失败");
					}else
						throw new Exception("添加退款申请（复审）审核不通过日志错误");

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
		$this->_Ajax_Verify($model,'refundlog-form');

		if(isset($_POST['RefundLog']))
		{
			$model->attributes  = $_POST['RefundLog'];
			$model->submit_time = time();
			$model->admin_id_submit=Yii::app()->admin->id;
			$model->audit_status = RefundLog::audit_status_finish;// 审核通过 待确认
			$model->refund_time 	   = time();// 到账时间
			$model->refund_status	   = RefundLog::refund_status_cash;

			if($model->validate()){
				$transaction=$model->dbConnection->beginTransaction();
				try
				{
					if($model->save(false)){

						//判断是否是活动订单=====判断代理商是否存在
						if($model->is_organizer==1){
							//执行活动相关
							if(! $this->actives_refund($model))
								throw new Exception("添加审核结算申请（确认）执行活动相应方法出错");
						}else{
							//更新订单详情 tmm_orderitems==== 更新消费码无效
							$valid=OrderItems::model()->updateAll(array('is_barcode'=>OrderItems::is_barcode_invalid),'order_id=:order_id',array(':order_id'=>$model->order_id));
							if(!$valid)
								throw new Exception("添加审核结算申请（确认）更新自助游消费码无效出错");
						}

						//角色钱包====退款成功
						$money = Account::entry_money($model->user_id,Account::user,$model->refund_price);

						if(!$money)
							throw new Exception("添加审核结算申请（确认）更新角色钱包出错");

						//更新订单状态====已退款
						if( ! Order::model()->updateByPk($model->order_id,array('status_go'=>Order::status_go_no,'order_status'=>Order::order_status_store_refund,'up_time'=>time())))
							throw new Exception("添加审核结算申请（确认）更新订单出错");

						//写入审核日志
						$audit=new AuditLog;
						$audit->info=$model->remark_submit;
						$audit->audit_who=AuditLog::admin;//记录 审核人 （审核人的id 在模型中）
						$audit->audit_element=AuditLog::organizer;//记录 被审核的类型
						$audit->element_id=$model->id;//记录 被审核id
						$audit->audit=AuditLog::pass;//记录 审核通过
						if($audit->save(false))
							$return=$this->log('添加审核退款申请（确认）记录',ManageLog::admin,ManageLog::create);
						else
							throw new Exception("添加审核退款申请（确认）日志错误");
					}else
						throw new Exception("审核通过退款申请（确认）保存错误");
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
		$model->Audit_RefundLogFiret=$this->loadModel($id,array(
			'condition'=>'t.status=1 AND t.audit_status=:audit_status',
			'params'=>array(':audit_status'=>Cash::audit_status_submit),
		));
		$this->_Ajax_Verify($model,'audit-log-form');

		if(isset($_POST['AuditLog']))
		{
			$model->attributes=$_POST['AuditLog'];
			$model->audit_who=AuditLog::admin;//记录 审核人 （审核人的id 在模型中）
			$model->audit_element=AuditLog::organizer;//记录 被审核的类型
			$model->element_id=$model->Audit_RefundLogFiret->id;//记录 被审核id
			$model->audit=AuditLog::nopass;//记录 审核不通过

			if($model->validate()){
				$transaction=$model->dbConnection->beginTransaction();
				try
				{

					// 更新审核日志
					if($model->save(false))
					{
						$model->Audit_RefundLogFiret->audit_status=RefundLog::audit_status_submit_not_pass;//审核不通过
						$model->Audit_RefundLogFiret->refund_status=RefundLog::refund_status_cash_not;//设置失败
						$model->Audit_RefundLogFiret->admin_id_submit=Yii::app()->admin->id;   //审核人ID
						$model->Audit_RefundLogFiret->submit_time=time();

						//更新申请记录表
						if($model->Audit_RefundLogFiret->save(false))
							$return = $this->log('添加退款申请（确认）审核不通过记录', ManageLog::admin, ManageLog::create);
						else
							throw new Exception("添加退款申请（确认）更新申请记录表失败");
					}else
						throw new Exception("添加退款申请（确认）审核不通过日志错误");

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


	public function actionAb(){
		$id = 10;
		$model=$this->loadModel($id);
		$this->actives_refund($model);
		exit;
	}

	/**
	 * 活动退款=======相应执行方法
	 */
	private function actives_refund($model){

		$criteria =new CDbCriteria;
		$criteria->with=array(
			'OrderActives_Actives',
			'OrderActives_Order'
		);
		$criteria->addColumnCondition(array(
			'OrderActives_Order.id'=>$model->order_id,	//订单ID
		));

		$actives = OrderActives::model()->find($criteria);

		// 不存在直接返回 false
		if( ! ($actives && $actives->OrderActives_Order && is_array($actives->OrderActives_Order)) )
			return false;

		// 还原购买数量
		if( ! (Actives::restore_order_number($actives->id,$actives->OrderActives_Order->user_go_count)))
			throw new Exception("添加审核结算申请（确认）活动（旅游）调用 支付订单还原购买数量保存出错");

		//支付订单数量
		if(! OrderActives::actives_pay_count_restore($actives->id))
			throw new Exception("添加审核结算申请（确认）活动（旅游）调用 支付订单数量保存出错");

		//实际出游数量
		if(! OrderActives::actives_go_count_restore($actives->id,$actives->OrderActives_Order[0]->user_go_count))
			throw new Exception("添加审核结算申请（确认）活动（旅游）调用 实际出游数量保存出错");


		//实际支付总额
		if(! OrderActives::actives_total_restore($actives->id,$actives->OrderActives_Order[0]->price))
			throw new Exception("添加审核结算申请（确认）活动（旅游）调用 实际支付总额保存出错");

		//添加购买的数量 价格
		if(! Order::update_actives_tour_itmes_fare($model->order_id,false))
			throw new Exception("添加审核结算申请（确认）活动（旅游）调用 用户购买价格的数据价格总计出错");



		return true;
	}


	/**
	 *管理页
	 */
	public function actionAdmin()
	{
		$model=new RefundLog('search');
		$model->unsetAttributes();  // 删除默认属性
		if(isset($_GET['RefundLog']))
			$model->attributes=$_GET['RefundLog'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}
	
	/**
	 * 禁用
	 * @param integer $id
	 */
	public function actionDisable($id){
		if($this->loadModel($id,'`status`=1')->updateByPk($id,array('status'=>0)))
			$this->log('禁用RefundLog',ManageLog::admin,ManageLog::update);			
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
			
	}
	
	/**
	 * 激活
	 * @param integer $id
	 */
	public function actionStart($id){
		if($this->loadModel($id,'`status`=0')->updateByPk($id,array('status'=>1)))
	 		$this->log('激活RefundLog',ManageLog::admin,ManageLog::update);
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));	
	}
}
