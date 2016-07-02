<?php
/**
 * 
 * @author Changhai Zhan
 *	创建时间：2015-08-03 14:59:24 */
class Tmm_organizerController extends MainController
{
	/**
	 * 默认操作数据模型
	 * @var string
	 */
	public $_class_model='Organizer';

	/**
	 * 初始化
	 * @see MainController::init()
	 */
	public function init()
	{
		parent::init();
		$this->_upload=Yii::app()->params['uploads_organizer_image'];
	}
	
	/**
	 * 查看详情
	 * @param integer $id
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id,array('with'=>array(
					'Organizer_User',
					'Organizer_Bank',
					'Organizer_area_id_p_Area_id',
					'Organizer_area_id_m_Area_id',
					'Organizer_area_id_c_Area_id',
			))),
		));
	}

	/**
	 * 创建
	 */
	public function actionCreate($id)
	{
		$model=new Organizer;

		$this->_class_model='User';
		$model->Organizer_User=$this->loadModel($id,array(
			'with'=>array('User_Organizer'),
			'condition'=>'t.status=1',
		));
		if(isset($model->Organizer_User->User_Organizer->id))//检测是否已经申请
			$this->redirect(array('admin'));
	
		$model->scenario='create';
		$this->_Ajax_Verify($model,'organizer-form');


		if(isset($_POST['Organizer']))
		{

			$model->attributes=$_POST['Organizer'];
			$model->id=$model->Organizer_User->id;
			$model->Organizer_User->audit=User::audit_pending;//提交未审核
			$model->status=0;//默认没有生效
			//上传图片
			$uploads=	array('bl_img','identity_begin', 'identity_after', 'identity_hand', 'identity_job');
			$files=$this->upload($model,$uploads);

			if($this->upload_error($model, $files, $uploads) && $model->save() && $this->log('创建代理商',ManageLog::admin,ManageLog::create)){
				$this->upload_save($model,$files);
				$model->Organizer_User->save(false);
				$this->back();
			}
		}
	
		$this->render('create',array(
				'model'=>$model
		));
	}
	
	/**
	 * 更新
	 * @param integer $id
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id,array(
			'with'=>array('Organizer_User'),
			'condition'=>'Organizer_User.status=:status AND Organizer_User.audit != :audit',
			'params'=>array(':status'=>User::status_suc,':audit'=>User::audit_pending),
		));

		$model->scenario='update';
		$this->_Ajax_Verify($model,'organizer-form');

		if(isset($_POST['Organizer']))
		{
			//上传图片
			$uploads=	array('bl_img','identity_begin', 'identity_after', 'identity_hand', 'identity_job');
			$data=$this->upload_save_data($model, $uploads);//保存原来的
			
			$model->attributes=$_POST['Organizer'];	
				
			$files=$this->upload($model,$uploads);//获取上传的
			
			if($model->validate())//提前验证
			{
				$old_path=$this->upload_update_data($model, $data, $files);//还原值
				if($model->Organizer_User->is_organizer != User::organizer && $model->Organizer_User->audit == User::audit_nopass)
				{
					$model->Organizer_User->audit = User::audit_pending;
					$model->Organizer_User->save(false);
				}
				if($model->save(false) && $this->log('更新组织者',ManageLog::admin,ManageLog::update)){
					$this->upload_save($model,$files);//保存新上传的
					$this->upload_delete($old_path);//删除原来的
					$this->back();
				}		
			}
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * 选择 觅趣
	 * @param $id
	 * @throws CHttpException
	 */
	public function actionActives($id){
		$model=new Actives();
		$model->scenario='organizer_create';
		$this->_class_model='Organizer';
		$model->Actives_Organizer=$this->loadModel($id,'`status`=1');

		$this->_Ajax_Verify($model,'actives-form');

		if (isset($_POST['Actives']))
		{
			$model->attributes=$_POST['Actives'];

			$this->redirect(array('/admin/'.$this->prefix.'actives'.'/'.Actives::$__tour_type[$model->tour_type].'_create','id'=>$id));
		}
		$this->render('actives',array(
			'model'=>$model,
		));
	}
	/**
	 * 设置银行信息
	 * @param unknown $id
	 */
	public function actionBank($id)
	{
		$model=$this->loadModel($id,array(
			'with'=>array('Organizer_User'),
			'condition'=>'Organizer_User.is_organizer=:is_organizer AND Organizer_User.status=1 AND t.status=1',
			'params'=>array(':is_organizer'=>User::organizer),
		));
		
		$model->scenario='bank';
		$this->_Ajax_Verify($model,'organizer-form');

		if(isset($_POST['Organizer']))
		{
			$model->attributes=$_POST['Organizer'];
			if($model->save() && $this->log('更新/设置代理商的银行信息',ManageLog::admin,ManageLog::update))
				$this->back();
		}
		
		$this->render('bank',array(
				'model'=>$model,
		));
	}
	
	/**
	 * 设置保证金
	 * @param unknown $id
	 */
	public function actionDeposit($id){
		$model=new DepositLog;
	
		$model->scenario='create';
		//查看是否需要设置保证金 注意（直接关联关系）
		$model->DepositLog_Organizer=$this->loadModel($id,array(
				'with'=>array('Organizer_User'),
				'condition'=>'Organizer_User.is_organizer=:is_organizer AND Organizer_User.status=1 AND t.status=1',
				'params'=>array(':is_organizer'=>User::organizer),
		));
		//验证用到
		$model->deposit_id=$model->DepositLog_Organizer->id;//被设置角色的id
		$model->deposit_who=DepositLog::deposit_organizer;//被设置的角色
		
		$this->_Ajax_Verify($model,'organizer-form');
		
		if(isset($_POST['DepositLog']) && Yii::app()->request->unsetCsrfToken())
		{
			$model->attributes=$_POST['DepositLog'];
			$model->admin_id=Yii::app()->admin->id;//操作的人
			$model->deposit_id=$model->DepositLog_Organizer->id;//被设置角色的id
			$model->deposit_who=DepositLog::deposit_organizer;//被设置的角色
			$model->deposit_old=$model->DepositLog_Organizer->deposit;
			
			if($model->validate()){
				$transaction=$model->dbConnection->beginTransaction();
				try
				{
					if($model->save(false)){
						//保证金类型
						if($model->deposit_status==DepositLog::type_deduct)
							$action = '-';
						elseif($model->deposit_status==DepositLog::type_add)
							$action = '+';
						else 
							throw new Exception("保证金计算类型错误");
						if($model->DepositLog_Organizer->updateByPk($model->DepositLog_Organizer->id,array(
							'deposit'=>new CDbExpression('`deposit`'.$action.':deposit',array(':deposit'=>$model->deposit)),
						)))
						{
							if(!! $organizer=Organizer::model()->findByPk(
									$model->DepositLog_Organizer->id,array('select'=>'deposit')))
							{
								if($organizer->deposit < 0)
									throw new Exception("扣除保证金小于零错误");
								else 
									$return=$this->log('创建保证金记录/设置代理商的保证金',ManageLog::admin,ManageLog::create);	
							}else 
								throw new Exception("保证金查账代理商用户错误");
						}else 
								throw new Exception("更新保证金错误");
					}else 
							throw new Exception("添加保证金日志错误");
					$transaction->commit();
				}
				catch(Exception $e)
				{
					$transaction->rollBack();			
					$this->error_log($e->getMessage(),ErrorLog::admin,ErrorLog::create,ErrorLog::rollback,__METHOD__);
					$this->refresh();
				}
			}
			if(isset($return))	
				$this->back();
		}

		$this->render('deposit',array(
			'model'=>$model,
		));	
	}

	/**
	 * 删除
	 * @param integer $id
	 */
	public function actionDelete($id)
	{
		//$this->loadModel($id)->delete();
		if($this->loadModel($id)->updateByPk($id,array('status'=>-1)))
			$this->log('删除代理商',ManageLog::admin,ManageLog::delete);
			
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
	}
	
	/**
	 * 还原
	 * @param integer $id
	 */
	public function actionRestore($id)
	{
		if($this->loadModel($id,'`status`=-1')->updateByPk($id,array('status'=>0)))
			$this->log('还原代理商',ManageLog::admin,ManageLog::update);
			
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
	}
	
	/**
	 * 审核通过
	 * @param integer $id
	 */
	public function actionPass($id)
	{
		//查看是否需要审核
		$model=$this->loadModel($id,array(
				'with'=>array('Organizer_User'=>array(	
					'condition'=>'Organizer_User.audit=:audit AND Organizer_User.status=1 AND t.status=0',
					'params'=>array(':audit'=>User::audit_pending),
				))
		));
		$model->scenario='pass';
		$model->push=0;//无效值
		$model->pass_time=time();
		$model->is_push=Organizer::yes_push; // 设置默认分成比例
		$model->Organizer_User->audit=User::audit_pass;// 审核通过
		$model->Organizer_User->is_organizer=User::organizer;//成为代理商			
		$transaction=$model->dbConnection->beginTransaction();
		if($model->validate())
		{
			try
			{
				if($model->save(false) && $model->Organizer_User->save(false))
				{
					$audit=new AuditLog;		
					$audit->info=$model->push;
					$audit->audit_who=AuditLog::admin;//记录 审核人 （审核人的id 在模型中）
					$audit->audit_element=AuditLog::organizer;//记录 被审核的类型
					$audit->element_id=$model->id;//记录 被审核id
					$audit->audit=AuditLog::pass;//记录 审核通过
					if($audit->save(false))
						$return=$this->log('审核用户成为代理商',ManageLog::admin,ManageLog::update);
					else 
						throw new Exception("添加审核日志错误");
				}else
					throw new Exception("审核通过保存错误");
				$transaction->commit();
			}
			catch(Exception $e)
			{
				$transaction->rollBack();
				$this->error_log($e->getMessage(),ErrorLog::admin,ErrorLog::update,ErrorLog::rollback,__METHOD__);
			}
		}
		if(isset($return))
			echo 1;
		else 
			echo '审核用户通过，成为代理商失败！';
	}
	
	/**
	 * 审核不通过
	 * @param integer $id
	 */
	public function actionNopass($id)
	{

		//改变布局
		$this->layout='/layouts/column_right_audit';
	  	$model=new AuditLog;
	  	
		$model->scenario='create';	
		//查看是否需要审核
		$model->Audit_Organizer=$this->loadModel($id,array(
				'with'=>array('Organizer_User'=>array(		
					'condition'=>'Organizer_User.audit=:audit AND Organizer_User.status=1 AND t.status=0',
					'params'=>array(':audit'=>User::audit_pending),
				))
		));
		$this->_Ajax_Verify($model,'audit-log-form');
		
		if(isset($_POST['AuditLog']))
		{
			$model->attributes=$_POST['AuditLog'];
			$model->audit_who=AuditLog::admin;//记录 审核人 （审核人的id 在模型中）
			$model->audit_element=AuditLog::organizer;//记录 被审核的
			$model->element_id=$model->Audit_Organizer->id;//记录 被审核id
			$model->audit=AuditLog::nopass;//记录 审核不通过
			if($model->validate()){
				$transaction=$model->dbConnection->beginTransaction();
				try
				{
					if($model->save(false))
					{
						$model->Audit_Organizer->Organizer_User->audit=User::audit_nopass;//审核不通过
						if($model->Audit_Organizer->Organizer_User->save(false))
							$return=$this->log('创建审核不通过记录',ManageLog::admin,ManageLog::create);
					}else
						throw new Exception("添加审核不通过日志错误");
					
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
	
// 	/**
// 	 * 彻底删除
// 	 * @param integer $id
// 	 */
// 	public function actionClear($id)
// 	{
// 		if($this->loadModel($id,'`status`=-1')->delete())
// 			$this->log('彻底删除代理商',ManageLog::admin,ManageLog::delete);
			
// 		if(!isset($_GET['ajax']))
// 			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
// 	}

	/**
	 * 垃圾回收页
	 */
	public function actionIndex()
	{
		$criteria=new CDbCriteria;
		$criteria->with=array(		
				'Organizer_User'=>array(
						'select'=>'id,phone,nickname',
						'condition'=>'is_organizer='.User::organizer
				),
				'Organizer_Bank'=>array('select'=>'id,name'),
				// 关联地址表
				'Organizer_area_id_p_Area_id'=>array('select'=>'id,name'),
				'Organizer_area_id_m_Area_id'=>array('select'=>'id,name'),
				'Organizer_area_id_c_Area_id'=>array('select'=>'id,name'),
		);
		$criteria->addColumnCondition(array('t.status'=>-1));

		$model=new Organizer;
		
		$this->render('index',array(
			'model'=>$model->search($criteria),
		));
	}
	
	/**
	 *管理页
	 */
	public function actionAdmin()
	{
		$model=new Organizer('search');
		$model->unsetAttributes();  // 删除默认属性
		$model->Organizer_User=new User('search');
		$model->Organizer_User->unsetAttributes();// 删除默认属性
		
		if(isset($_GET['Organizer']))
			$model->attributes=$_GET['Organizer'];
		if(isset($_GET['User']))
			$model->Organizer_User->attributes=$_GET['User'];
			
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
			$this->log('禁用代理商',ManageLog::admin,ManageLog::update);			
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
	}
	
	/**
	 * 激活
	 * @param integer $id
	 */
	public function actionStart($id)
	{
		if($this->loadModel($id,array('with'=>'Organizer_User','condition'=>'`t`.`status`=0 AND Organizer_User.is_organizer=:is_organizer AND Organizer_User.status=1','params'=>array(':is_organizer'=>User::organizer)))->updateByPk($id,array('status'=>1)))
	 		$this->log('激活代理商',ManageLog::admin,ManageLog::update);
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
	}
}
