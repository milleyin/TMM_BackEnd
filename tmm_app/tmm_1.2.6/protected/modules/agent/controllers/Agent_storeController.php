<?php
/**
 * 
 * @author Changhai Zhan
 *	创建时间：2015-09-02 11:24:33 */
class Agent_storeController extends AgentController
{
	/**
	 * 默认操作数据模型
	 * @var string
	 */
	public $_class_model='StoreUser';

	/**
	 * 查看详情
	 * @param integer $id
	 */
	public function actionView($id)
	{
		$this->addCss(Yii::app()->request->baseUrl.'/css/agent/css/normalize.css');
		$this->addCss(Yii::app()->request->baseUrl.'/css/agent/css/business.css');
		$this->addCss(Yii::app()->request->baseUrl.'/css/agent/css/box.css');
		
		$model=$this->loadModel($id,array(
			'with'=>array(
				'Store_Content'=>array(
					'with'=>array(
						'Content_area_id_p_Area_id',
						'Content_area_id_m_Area_id',
						'Content_area_id_c_Area_id',
						'Content_Bank',
					),
				),
				'Store_Items_Count',
			),
			'condition'=>'`t`.`p_id`=0 AND `t`.`status`>=0 AND `t`.`create_status`=0 AND t.agent_id=:agent',	
			'params'=>array(':agent'=>Yii::app()->agent->id),
		));
		$model->Store_TagsElement=TagsElement::get_select_tags(TagsElement::tags_store_content,$id);
		$model->Store_Content->Content_Account=Account::get_account($id,Account::agent);
		$this->render('view',array(
			'model'=>$model,
		));
	}

	/**
	 * 查看详情====子账号
	 * @param integer $id
	 */
	public function actionViewSon($id)
	{
		$this->addCss(Yii::app()->request->baseUrl.'/css/agent/css/normalize.css');
		$this->addCss(Yii::app()->request->baseUrl.'/css/agent/css/business.css');
		$this->addCss(Yii::app()->request->baseUrl.'/css/agent/css/box.css');
		
		$this->render('viewson',array(
			'model'=>$this->loadModel($id,array(
					'with'=> array(
						'Store_Items_Manage_Count',
						'Store_Store'=>array('with'=>array('Store_Content')),
						'Store_Agent',
					),
					'condition'=>'`t`.`p_id`!=0 AND `t`.`status`>=0 AND `t`.`agent_id`=:agent',
					'params'=>array(':agent'=>Yii::app()->agent->id),
				)),
		));
	}

	/**
	 * 创建第一步
	 */
	public function actionCreate($id='')
	{
		$this->addCss(Yii::app()->baseUrl."/css/agent/css/step.css");
		$this->addCss(Yii::app()->baseUrl."/css/agent/css/form.css");
		$this->addCss(Yii::app()->baseUrl."/css/agent/css/box.css");
		$this->addCss(Yii::app()->baseUrl."/css/agent/css/business.css");


		if($id=='')
		{
			$model_old=StoreUser::model()->find('`status`=0 AND `p_id`=0 AND `agent_id`=:agent_id AND `create_status` !=0',array(':agent_id'=>Yii::app()->agent->id));
			if($model_old)
				$this->redirect(array('create_'.($model_old->create_status+1),'id'=>$model_old->id));
			$model=new StoreUser;
		}else
			$model=$this->loadModel($id,'`status`=0 AND `p_id`=0 AND `agent_id`=:agent_id AND `create_status` !=0',array(':agent_id'=>Yii::app()->agent->id));
		$model->scenario='agent_create_1';
		$this->_Ajax_Verify($model,'store-user-form');

		if(isset($_POST['StoreUser']))
		{
			$model->attributes=$_POST['StoreUser'];
			$model->create_status=1;
			$model->password=$model::pwdEncrypt($model->_pwd);
			$model->status=0;
			$model->agent_id=Yii::app()->agent->id;
			if($model->validate() && $model->verifycode() && $model->save(false) &&$this->log('创建商家主账号第一步',ManageLog::agent,ManageLog::create))
				$this->redirect(array('create_2','id'=>$model->id));
		}
		$this->render('create_1',array(
			'model'=>$model,
		));
	}
	
	/**
	 * 创建第二部
	 */
	public function actionCreate_2($id)
	{
		$this->addCss(Yii::app()->baseUrl."/css/agent/css/step.css");
		$this->addCss(Yii::app()->baseUrl."/css/agent/css/form.css");
		$this->addCss(Yii::app()->baseUrl."/css/agent/css/box.css");
		$this->addCss(Yii::app()->baseUrl."/css/agent/css/business.css");
			
		$model=$this->loadModel($id,array(
			'with'=>array('Store_Content'),
			'condition'=>'`t`.`create_status`>=1 AND `t`.`status`=0 AND `t`.`p_id`=0 AND `t`.`agent_id`=:agent_id',
			'params'=>array(':agent_id'=>Yii::app()->agent->id),
		));
		if($model->Store_Content ==null)
			$model->Store_Content=new StoreContent;
		$model->Store_Content->scenario='agent_create_2';
		$this->_Ajax_Verify($model->Store_Content,'store-user-form');
		if(isset($_POST['StoreContent']))
		{
			$model->Store_Content->attributes=$_POST['StoreContent'];
			$model->create_status=2;
			$model->Store_Content->id=$model->id;
			if($model->Store_Content->validate())
			{
				$transaction = $model->dbConnection->beginTransaction();
				try {
					if($model->Store_Content->save(false) && $model->save(false))
						$return=$this->log('创建商家主账号第二步',ManageLog::agent,ManageLog::create);
					else
						throw new Exception("添加商家主账号第三步失败");
					//成功事务提交
					$transaction->commit();
				} catch (Exception $e) {
					//失败事务回滚
					$transaction->rollBack();
					$this->error_log($e->getMessage(), ErrorLog::agent, ErrorLog::create,ErrorLog::rollback,__METHOD__);
				}
				if(isset($return))
					$this->redirect(array('create_3','id'=>$model->id));
			}
		}
		$this->render('create_2',array(
				'model'=>$model,
		));
	}
	
	/**
	 * 创建第三步
	 */
	public function actionCreate_3($id)
	{
		$this->addCss(Yii::app()->baseUrl."/css/agent/css/step.css");
		$this->addCss(Yii::app()->baseUrl."/css/agent/css/form.css");
		$this->addCss(Yii::app()->baseUrl."/css/agent/css/box.css");
		$this->addCss(Yii::app()->baseUrl."/css/agent/css/business.css");

		$model=$this->loadModel($id,array(
				'with'=>array('Store_Content'),
				'condition'=>'`t`.`create_status`>=2 AND `t`.`status`=0 AND `t`.`p_id`=0 AND `t`.`agent_id`=:agent_id',
				'params'=>array(':agent_id'=>Yii::app()->agent->id),
		));
		$model->Store_Content->scenario='agent_create_3';
	
		if(isset($_POST['StoreContent']))
		{
			$this->_upload=Yii::app()->params['uploads_store_image'];
			//需要上传图片
			$uploads = array('bl_img','identity_before', 'identity_after', 'identity_hand');
			//保存原来的
			$data = $this->upload_save_data($model->Store_Content, $uploads);
			//过滤空白的值
			$data=array_filter($data);
			//获取上传的图片
			$files   = $this->upload($model->Store_Content,$uploads);
			//看看是修改 还是创建
			if(!empty($data))
				$validate_img=true;
			else
				$validate_img=$this->upload_error($model->Store_Content, $files, $uploads);
			if($validate_img)
				$validate=$model->Store_Content->validate();
			else 
				$validate=false;
			if($validate_img && $validate)
			{
				if(!empty($data))
					//还原没有上传的值
					$old_path=$this->upload_update_data($model->Store_Content, $data, $files);	
				$model->create_status=3;
				$transaction = $model->dbConnection->beginTransaction();
				try {
					if($model->Store_Content->save(false) && $model->save(false))
					{
						$this->upload_save($model->Store_Content, $files);//保存新的
						if(!empty($data))
							$this->upload_delete($old_path);//删除原来的
						$return=$this->log('创建商家主账号第三步',ManageLog::agent,ManageLog::create);
					}else 
						throw new Exception("添加商家主账号第三步失败");	
					//成功事务提交
					$transaction->commit();
				} catch (Exception $e) {
					//失败事务回滚
					$transaction->rollBack();
					$this->error_log($e->getMessage(), ErrorLog::agent, ErrorLog::create,ErrorLog::rollback,__METHOD__);
				}
				if(isset($return))
					$this->redirect(array('create_4','id'=>$model->id));
			}
		}
		$this->render('create_3',array(
				'model'=>$model,
		));
	}
	

	/**
	 * 创建第四步
	 * @param unknown $id
	 */
	public function actionCreate_4($id)
	{
		$this->addCss(Yii::app()->baseUrl."/css/agent/css/step.css");
		$this->addCss(Yii::app()->baseUrl."/css/agent/css/form.css");
		$this->addCss(Yii::app()->baseUrl."/css/agent/css/box.css");

		$model=$this->loadModel($id,array(
				'with'=>array('Store_Content'=>array(
						'with'=>array(
								'Content_area_id_p_Area_id',
								'Content_area_id_m_Area_id',
								'Content_area_id_c_Area_id',
						),
				)),
				'condition'=>'`t`.`create_status`=3 AND `t`.`status`=0 AND `t`.`p_id`=0 AND `t`.`agent_id`=:agent_id',
				'params'=>array(':agent_id'=>Yii::app()->agent->id),
		));
		if(isset($_POST['StoreUser']['create_status']) && $_POST['StoreUser']['create_status']==3)
		{
			$model->create_status=0;
			$transaction = $model->dbConnection->beginTransaction();
			try {
					if($model->save(false)) 
					{
						if(Agent::model()->updateByPk(Yii::app()->agent->id,array(
							'merchant_count'=>new CDbExpression('`merchant_count`+1'))))
							$return =$this->log('创建商家主账号完成',ManageLog::agent,ManageLog::create);
						else
							throw new Exception("更新代理商数量加一失败");			
					}else 
						throw new Exception("添加商家主账号失败");
						//成功事务提交
						$transaction->commit();
			} catch (Exception $e) {
				//失败事务回滚
				$transaction->rollBack();
				$this->error_log($e->getMessage(), ErrorLog::agent, ErrorLog::create,ErrorLog::rollback,__METHOD__);
			}
			if(isset($return))
				$this->redirect(array('create_5','id'=>$model->id));
		}		
		$model->Store_TagsElement=TagsElement::get_select_tags(TagsElement::tags_store_content,$id);	
		$tags_model=new Tags;
		$this->render('create_4',array(
				'model'=>$model,
				'tags_model'=>$tags_model->select_tags_element(),
		));
	}
	
	/**
	 * 创建第五步
	 * @param unknown $id
	 */
	public function actionCreate_5($id)
	{
		$this->addCss(Yii::app()->baseUrl."/css/agent/css/step.css");
		$this->addCss(Yii::app()->baseUrl."/css/agent/css/form.css");
		$this->addCss(Yii::app()->baseUrl."/css/agent/css/box.css");
		$model=$this->loadModel($id,array(
				'with'=>array('Store_Content'),
				'condition'=>'`t`.`create_status`=0 AND `t`.`status`=0 AND `t`.`p_id`=0 AND `t`.`agent_id`=:agent_id',
				'params'=>array(':agent_id'=>Yii::app()->agent->id),
		));	
		$this->render('create_5',array(
				'model'=>$model,
		));
	}
	
	/**
	 * 标签选中
	 * @param unknown $id
	 * @param string $type
	 */
	public function actionTags($id,$type='yes')
	{		
		if(isset($_POST['tag_ids']) && !is_array($_POST['tag_ids']) && $_POST['tag_ids'] !=='')
		{
			$model= $this->loadModel($id,
				'`create_status`=3 AND `status`=0 AND `p_id`=0 AND `agent_id`=:agent_id',
				array(':agent_id'=>Yii::app()->agent->id)
			);

			$tags_id=Tags::filter_tags($_POST['tag_ids']);//安全过滤tags id
			if($type=='yes'){

				$model->Store_TagsElement=TagsElement::get_select_tags(TagsElement::tags_store_content,$id);
				if(count($model->Store_TagsElement)>=Yii::app()->params['tags']['store']['select'])
				{
					echo Yii::app()->params['tags']['store']['error'];
					Yii::app()->end();
				}
				//过滤之前有的
				$save_tags_id=TagsElement::not_select_tags_element($tags_id,$id,TagsElement::tags_store_content);
				$return=TagsElement::select_tags_ids_save($save_tags_id, $id, TagsElement::tags_store_content,TagsElement::agent);
			}else
				$return=TagsElement::select_tags_ids_delete($tags_id, $id, TagsElement::tags_store_content);
			if($return)
			{
				if($type=='yes')
					$this->log('商家(主)添加一个标签', ManageLog::agent,ManageLog::create);
				else
					$this->log('商家(主)去除一个标签', ManageLog::agent,ManageLog::clear);
				echo 1;
			}else
				echo '操作过于频繁，请刷新页面从新选择！';
		}else
			echo '没有选中标签，请重新选择标签！';
	}
	
	/**
	 * 查看保证金
	 * @param unknown $id
	 */	
	 public function actionDeposit($id)
	{
		$this->addCss(Yii::app()->baseUrl."/css/agent/css/business.css");
		$this->_class_model='StoreContent';
		$store_model=$this->loadModel($id,array(
			'with'=>array('Content_Store'),
			'condition'=>'`Content_Store`.`agent_id`=:agent_id AND `Content_Store`.`status`>=0',
			'params'=>array(':agent_id'=>Yii::app()->agent->id),
		));
		$model=new DepositLog;
		$criteria=new CDbCriteria;
		$criteria->addColumnCondition(array(
			'deposit_who'=>DepositLog::deposit_store,
			'deposit_id'=>$store_model->id,
			'status'=>1,
		));
		$this->render('deposit',array(
				'dataProvider'=>$model->search($criteria),
				'model'=>$store_model,
		));
	}
	
	/**
	 *短信发送
	 */
	public function actionCaptcha_sms()
	{
		if(isset($_POST['phone']))
		{
			$model=new SmsLoginForm;
			$model->scenario='no';
			$model->attributes=array('phone'=>$_POST['phone']);
			if($model->validate())
			{
				if($model->agent_create_store_send())
				{
					echo json_encode(array());
				}else
					echo json_encode(array('verifyCode'=>'发送短信次数过于频繁或手机号已经注册'));
			}else
				echo json_encode($model->get_error());
		}
	}

	/**
	 * 选择商家主账号
	 * @throws CException
	 * @throws CHttpException
	 */
	public function actionStore_info()
	{
		if (isset($_POST['id'])) {
			$id = $_POST['id'];
			$model=$this->loadModel($id,
				array(
					'with'=>array(
						'Store_Content'=>array(
							'with'=>array(
								'Content_area_id_p_Area_id',
								'Content_area_id_m_Area_id',
								'Content_area_id_c_Area_id'
							)
						)
					),
					'condition'=>'`t`.`p_id`=0 AND `t`.`status`=1 AND `t`.`agent_id`=:agent_id',
					'params'=>array(':agent_id'=>Yii::app()->agent->id),
				)
			);
			$returnHtml = $this->renderPartial('_info',array(
				'model'=>$model
			), true);
			echo json_encode($returnHtml);
			Yii::app()->end();
		}
	}

	/**
	 * 创建====子账号 第一步
	 */
	public function actionCreateSon() 
	{
		$this->addCss(Yii::app()->baseUrl."/css/agent/css/step.css");
		$this->addCss(Yii::app()->baseUrl."/css/agent/css/form.css");
		$this->addCss(Yii::app()->baseUrl."/css/agent/css/box.css");
		$this->addCss(Yii::app()->baseUrl."/css/agent/css/subbusiness.css");
		$model = new StoreContent;
		if (isset($_POST['StoreContent']['id'])) 
			$this->redirect(array('createSon_2','id'=>$_POST['StoreContent']['id']));
		
		$this->render('createson',array(
			'model'=>$model
		));
	}

	/**
	 * 创建====子账号 第二步
	 */
	public function actionCreateSon_2($id) {
		$this->addCss(Yii::app()->baseUrl."/css/agent/css/step.css");
		$this->addCss(Yii::app()->baseUrl."/css/agent/css/form.css");
		$this->addCss(Yii::app()->baseUrl."/css/agent/css/box.css");
		$this->addCss(Yii::app()->baseUrl."/css/agent/css/subbusiness.css");

		$model = new StoreUser;
		$model->scenario='agent_create_son';
		$this->_Ajax_Verify($model,'store-user-form');
		//查询父类是否存在，子账号是否能创建
		$model->Store_Store=$this->loadModel($id,array(
				'with'=>array('Store_Content'=>array(
					'condition'=>'`Store_Content`.`son_limit`>`Store_Content`.`son_count` AND `t`.`status`=1 AND `t`.`p_id`=0 AND `t`.`agent_id`=:agent_id',
					'params'=>array(':agent_id'=>Yii::app()->agent->id),
				),
			)));

		if(isset($_POST['StoreUser']))
		{
			$model->attributes=$_POST['StoreUser'];
			$model->p_id      =$model->Store_Store->id;
			$model->agent_id  =Yii::app()->agent->id;
			$model->password  =$model::pwdEncrypt($model->_pwd);
			$model->status=0;
			if($model->validate() && $model->verifycode_create_son())
			{
				//开启事务
				$transaction=$model->dbConnection->beginTransaction();
				try
				{
					if($model->save(false))
					{
						if(StoreContent::model()->updateByPk($model->Store_Store->Store_Content->id,array(
							'son_count'=>new CDbExpression('`son_count`+1'))))
						{
							if(!! $store_user =StoreContent::model()->findByPk($model->Store_Store->Store_Content->id)) 
							{
								if ($store_user->son_count > $store_user->son_limit) 
									throw new Exception("子账号数量不能小于规定上限");
								else
									$return = $this->log('代理商添加商家子账号', ManageLog::agent, ManageLog::create);
							}else
								throw new Exception("查询商家主账号信息失败");
						}else
							throw new Exception("商家主账号数量加一失败");			
					}else 
						throw new Exception("保存商家子账号失败");			
					//成功事务提交
					$transaction->commit();
				}
				catch(Exception $e)
				{
					//失败事务回滚
					$transaction->rollBack();
					$this->error_log($e->getMessage(), ErrorLog::agent, ErrorLog::create,ErrorLog::rollback,__METHOD__);
				}
			}
			if(isset($return))
				$this->redirect(array('createSon_3','id'=>$model->id));
		}

		$this->render('createson_2',array(
			'model'=>$model
		));
	}


	/**
	 * 创建====子账号 第三步(完成)
	 */
	public function actionCreateSon_3($id) {
		$this->addCss(Yii::app()->baseUrl."/css/agent/css/step.css");
		$this->addCss(Yii::app()->baseUrl."/css/agent/css/form.css");
		$this->addCss(Yii::app()->baseUrl."/css/agent/css/box.css");
		$this->addCss(Yii::app()->baseUrl."/css/agent/css/subbusiness.css");
		$model=$this->loadModel($id,'`status`=0 AND `p_id`!=0 AND `agent_id`=:agent_id',array(':agent_id'=>Yii::app()->agent->id));
		
		$this->render('createson_3',array('model'=>$model));
	}

	/**
	 * 更新====子账号
	 * @param integer $id
	 */
	public function actionUpdateSon($id)
	{
		$this->addCss(Yii::app()->request->baseUrl.'/css/agent/css/normalize.css');
		$this->addCss(Yii::app()->request->baseUrl.'/css/agent/css/query.css');
		$this->addCss(Yii::app()->request->baseUrl.'/css/agent/css/form.css');
		$this->addCss(Yii::app()->request->baseUrl.'/css/agent/css/subbusiness.css');

		$model=$this->loadModel($id,'`p_id`!=0 AND `agent_id`=:agent_id',array(':agent_id'=>Yii::app()->agent->id));

		$model->scenario='agent_update_son';
		$this->_Ajax_Verify($model,'store-user-form');

		if(isset($_POST['StoreUser']))
		{
			$model->attributes=$_POST['StoreUser'];
			if($model->validate() && $model->verifycode_update_son() && $model->save(false) && $this->log('更新商家子账号手机号',ManageLog::agent,ManageLog::update))
				$this->back();
		}

		$this->render('updateson',array(
			'model'=>$model,
		));
	}
	
	/**
	 *短信发送
	 */
	public function actionStore_update_son_sms(){
		if(isset($_POST['phone']) && $_POST['phone']!='' && isset($_POST['old_phone']) && $_POST['old_phone']!='')
		{
			$model=new SmsLoginForm;
			$model->scenario='change_phone';
			$model->attributes=array('phone'=>$_POST['phone'],'old_phone'=>$_POST['old_phone']);
			if($model->validate())
			{
				if($model->agent_update_store_son_send())
				{
					echo json_encode(array());
				}else
					echo json_encode(array('verifyCode'=>'发送短信次数过于频繁或没有修改手机号'));
			}else
				echo json_encode($model->get_error());
		}
	}

	/**
	 *短信发送
	 */
	public function actionStore_create_son_sms(){
		if(isset($_POST['phone']) && $_POST['phone']!='')
		{
			$model=new SmsLoginForm;
			$model->scenario='no';
			$model->attributes=array('phone'=>$_POST['phone']);
			if($model->validate())
			{
				if($model->agent_create_store_son_send())
				{
					echo json_encode(array());
				}else
					echo json_encode(array('verifyCode'=>'发送短信次数过于频繁或手机号已经注册'));
			}else
				echo json_encode($model->get_error());
		}
	}
	
	/**
	 *管理页
	 */
	public function actionAdmin()
	{	
		$this->addCss(Yii::app()->request->baseUrl.'/css/agent/css/normalize.css');
		$this->addCss(Yii::app()->request->baseUrl.'/css/agent/css/business.css');
		
		$model=new StoreUser('agent_store_search');
		$model->Store_Content=new StoreContent('agent_store_search');
		$model->unsetAttributes();  // 删除默认属性
		$model->Store_Content->unsetAttributes();  // 删除默认属性
		if(isset($_GET['StoreUser']))
			$model->attributes=$_GET['StoreUser'];
		if(isset($_GET['StoreContent']))
			$model->Store_Content->attributes=$_GET['StoreContent'];

		$this->render('admin',array(
			'model'=>$model,
		));

	}

	/**
	 *管理页==商家子账号
	 */
	public function actionAdminSon()
	{
		$this->addCss(Yii::app()->request->baseUrl.'/css/agent/css/normalize.css');
		$this->addCss(Yii::app()->request->baseUrl.'/css/agent/css/subbusiness.css');

		$model=new StoreUser('agent_store_son_search');
		$model->Store_Store=new StoreUser('agent_store_son_search');
		$model->Store_Store->Store_Content=new StoreContent('agent_store_son_search');

		$model->unsetAttributes();  // 删除默认属性
		$model->Store_Store->unsetAttributes();  // 删除默认属性
		$model->Store_Store->Store_Content->unsetAttributes();  // 删除默认属性
		if(isset($_GET['StoreUser']))
			$model->attributes=$_GET['StoreUser'];
		if(isset($_GET['StoreContent']))
			$model->Store_Store->Store_Content->attributes=$_GET['StoreContent'];
		
		$this->render('adminson',array(
			'model'=>$model,
		));
	}

	/**
	 * 禁用
	 * @param integer $id
	 */
	public function actionDisable($id)
	{
		if($this->loadModel($id,'`status`=1 AND `p_id`=0 AND `agent_id`=:agent_id',array(':agent_id'=>Yii::app()->agent->id))->updateByPk($id,array('status'=>0)))
		{
			StoreUser::model()->updateAll(array('status'=>0),'`p_id`=:id AND `agent_id`=:agent_id',array(':id'=>$id,':agent_id'=>Yii::app()->agent->id));
			$this->log('禁用商家主账号及子账号',ManageLog::agent,ManageLog::update);
		}
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
	}

	/**
	 * 禁用====子账号
	 * @param integer $id
	 */
	public function actionDisableSon($id){
		if($this->loadModel($id,'`status`=1 AND `p_id`!=0 AND `agent_id`=:agent_id',array(':agent_id'=>Yii::app()->agent->id))->updateByPk($id,array('status'=>0)))
			$this->log('禁用商家子账号',ManageLog::agent,ManageLog::update);
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
	}
	
	/**
	 * 激活
	 * @param integer $id
	 */
	public function actionStart($id)
	{
		if($this->loadModel($id,'`p_id`=0 AND `status`=0 AND `create_status`=0 AND `agent_id`=:agent_id',array(':agent_id'=>Yii::app()->agent->id))->updateByPk($id,array('status'=>1)))
	 		$this->log('代理商激活商家主账号',ManageLog::agent,ManageLog::update);
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));	
	}

	/**
	 * 激活====子账号
	 * @param integer $id
	 */
	public function actionStartSon($id)
	{
		$model=$this->loadModel($id,'`status`=0 AND `p_id`!=0 AND `agent_id`=:agent_id',array(':agent_id'=>Yii::app()->agent->id));
		$this->loadModel($model->p_id,'`status`=1 AND `p_id`=0 AND `agent_id`=:agent_id',array(':agent_id'=>Yii::app()->agent->id));
		if($model->updateByPk($id,array('status'=>1)))
			$this->log('激活商家子账号',ManageLog::agent,ManageLog::update);
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
	}
}
