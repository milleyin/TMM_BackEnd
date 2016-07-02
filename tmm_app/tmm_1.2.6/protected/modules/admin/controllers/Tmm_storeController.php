<?php
/**
 * 
 * @author Changhai Zhan
 *	创建时间：2015-08-03 11:51:58 */
class Tmm_storeController extends MainController
{
	/**
	 * 默认操作数据模型
	 * @var string
	 */
	public $_class_model='StoreUser';
        
	/**
	 * 初始化
	 * @see MainController::init()
	 */
	public function init()
	{
		parent::init();
		$this->_upload=Yii::app()->params['uploads_store_image'];
	}
	/**
	 * 查看详情
	 * @param integer $id
	 */
	public function actionView($id)
	{
        $model=$this->loadModel($id,array('with'=>array(
		'Store_Content'=>array(
			'with'=>array(
				'Content_area_id_p_Area_id',
				'Content_area_id_m_Area_id',
				'Content_area_id_c_Area_id',
				'Content_Bank',
			),
		),
		'Store_Agent')));
		$model->Store_TagsElement=TagsElement::get_select_tags(TagsElement::tags_store_content,$id);
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
		$this->render('viewson',array(
			'model'=>$this->loadModel($id,array('with'=> array(
					'Store_Store'=>array('with'=>array('Store_Content')),
					'Store_Agent',
			))),
		));
	}

	/**
	 * 创建
	 */
	public function actionCreate($id)
	{
        //实例化模型
		$model=new StoreUser;
		$model->Store_Content=new StoreContent;
        //设置场景
		$model->scenario='create';
        $model->Store_Content->scenario='create';

		$this->_class_model='Agent';
       $model->Store_Agent=$this->loadModel($id,'`status`=1');
       $model->Store_Content->agent_id= $model->Store_Agent->id;//权限验证
		$this->_Ajax_Verify(array($model,$model->Store_Content),'store-user-form');

         //是否POST传值
		if(isset($_POST['StoreUser'])  && isset($_POST['StoreContent']))
		{
			//获得所有POST值
			$model->attributes=$_POST['StoreUser'];
			$model->Store_Content->attributes=$_POST['StoreContent'];
			$model->password=$model::pwdEncrypt($model->_pwd);
			//验证上传的图片
			$uploads = array('bl_img','identity_before', 'identity_after', 'identity_hand');
			$files   = $this->upload($model->Store_Content,$uploads);
			if(!! $file_val= $this->upload_error($model->Store_Content, $files, $uploads))
			{
				//验证是否为合法参数
				$con_val = $model->Store_Content->validate();
				$use_val = $model->validate();
				if ($con_val && $use_val) {
					$model->password = $model::pwdEncrypt($model->_pwd);
					$model->agent_id = $model->Store_Agent->id;
					$model->create_status=0;
					//开启事务
					$transaction = $model->dbConnection->beginTransaction();
					try {
						//保存主表
						if ($model->save(false)) {
							//获得主表ID
							$model->Store_Content->id = $model->id;
							if ($model->Store_Content->save(false)) {
								$this->upload_save($model->Store_Content, $files);
								if(Agent::model()->updateByPk($model->Store_Agent->id,array(
									'merchant_count'=>new CDbExpression('`merchant_count`+1'))))
								{
									$return = $this->log('添加供应商主账号', ManageLog::admin, ManageLog::create);
								}else
									throw new Exception("更新运营商数量加一失败");
							} else 
								throw new Exception("添加供应商子账号失败");				
						} else 
							throw new Exception("添加供应商主账号失败");
						//成功事务提交
						$transaction->commit();
					} catch (Exception $e) {
						//失败事务回滚
						$transaction->rollBack();
						$this->error_log($e->getMessage(), ErrorLog::admin, ErrorLog::create,ErrorLog::rollback,__METHOD__);
					}
					if (isset($return))
						$this->back();
				}
			}
		}

		$this->render('create',array(
			'model'=>$model,
		));
                
	}
	/**
	 * 创建====子账号
	 */
	public function actionCreateSon($id)
	{      
		$model=new StoreUser;
		$model->scenario='create';
		$this->_Ajax_Verify($model,'store-user-form');
		 //查询父类是否存在，子账号是否能创建
		$model->Store_Store=$this->loadModel($id,array(
			'with'=>array('Store_Content'=>array(
				'condition'=>'`Store_Content`.`son_limit`>`Store_Content`.`son_count`',
			),
		)));
		if(isset($_POST['StoreUser']))
		{
            $model->attributes=$_POST['StoreUser'];
			$model->p_id      =$model->Store_Store->id;
			$model->agent_id  =$model->Store_Store->agent_id;
			$model->password  =$model::pwdEncrypt($model->_pwd);
			$model->status=0;
			if($model->validate())
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
							if(!! $store_user =StoreContent::model()->findByPk($model->Store_Store->Store_Content->id)) {
								if ($store_user->son_count > $store_user->son_limit)
									throw new Exception("子账号数量不能小于规定上限");
								else
									$return = $this->log('添加供应商子账号', ManageLog::admin, ManageLog::create);
							} else 
								throw new Exception("查询供应商主账号信息失败");					
						}else
							throw new Exception("供应商主账号数量加一失败");					
					}else 
						throw new Exception("保存供应商子账号失败");	
					//成功事务提交
					$transaction->commit();
				}
				catch(Exception $e)
				{
					//失败事务回滚
					$transaction->rollBack();
					$this->error_log($e->getMessage(), ErrorLog::admin, ErrorLog::create,ErrorLog::rollback,__METHOD__);
				}
			}
			if(isset($return))
				$this->back();
		}
                
          $this->render('createson',array(
			'model'=>$model
		));
	}
        
        

	/**
	 * 更新
	 * @param integer $id
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id,array('with'=>'Store_Content'));
                
		$model->scenario='update';
		$model->Store_Content->scenario='update';
		$model->Store_Content->agent_id= $model->agent_id;//权限验证	
		$this->_Ajax_Verify(array($model,$model->Store_Content),'store-user-form');

		if(isset($_POST['StoreUser']) && isset($_POST['StoreContent']))
		{
           //获得需要的上传图片
			$uploads = array('bl_img','identity_before', 'identity_after', 'identity_hand');
			//保存原来的
			$data    = $this->upload_save_data($model->Store_Content, $uploads);
			//获得参数
			$model->attributes=$_POST['StoreUser'];
			$model->Store_Content->attributes=$_POST['StoreContent'];
			//获取上传的
			$files   = $this->upload($model->Store_Content,$uploads);
			 //更新密码
            if($model->_pwd !='')
				$model->password=$model::pwdEncrypt($model->_pwd);

			//验证是否为合法参数
			$con_val = $model->Store_Content->validate();
			$use_val = $model->validate();
			//提前验证
			if($con_val && $use_val ){//提前验证
				$old_path=$this->upload_update_data($model->Store_Content, $data, $files);//没有上传的负值

				if($model->save(false) && $model->Store_Content->save(false) &&  $this->log('更新供应商主账号',ManageLog::admin,ManageLog::update))
				{
					 $this->upload_save($model->Store_Content,$files);//保存新上传的
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
	 * 更新====子账号
	 * @param integer $id
	 */
	public function actionUpdateSon($id)
	{
		$model=$this->loadModel($id);

		$model->scenario='update';
		$this->_Ajax_Verify($model,'store-user-form');

		if(isset($_POST['StoreUser']))
		{
			$model->attributes=$_POST['StoreUser'];
			if($model->_pwd !='')
				$model->password=$model::pwdEncrypt($model->_pwd);

             if($model->save() && $this->log('更新供应商子账号',ManageLog::admin,ManageLog::update))
                 $this->back();
        }

		$this->render('updateson',array(
			'model'=>$model,
		));
	}

	/**
	 * 删除
	 * @param integer $id
	 */
	public function actionDelete($id)
	{
		if($this->loadModel($id,'`p_id`=0 AND `status`=0')->updateByPk($id,array('status'=>-1)))
		{
			//子账号 全部删除
			StoreUser::model()->updateAll(array('status'=>-1),'`p_id`=:id',array(':id'=>$id));
			$this->log('删除供应商主账号',ManageLog::admin,ManageLog::delete);
		}
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
	}
  
  /**
	 * 删除====子账号
	 * @param integer $id
	 */
	public function actionDeleteSon($id)
	{
		if($this->loadModel($id,'`p_id`!=0 AND `status`=0')->updateByPk($id,array('status'=>-1)))
			$this->log('删除供应商子账号',ManageLog::admin,ManageLog::delete);		
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
	}
	
	/**
	 * 还原
	 * @param integer $id
	 */
	public function actionRestore($id)
	{
		if($this->loadModel($id,'`status`=-1 AND `p_id`=0')->updateByPk($id,array('status'=>0)))
			$this->log('还原供应商主账号',ManageLog::admin,ManageLog::update);

		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
	}
        
	/**
	 * 还原====子账号
	 * @param integer $id
	 */
	public function actionRestoreSon($id)
	{
		//要求：主要账号不是删除账号
		$model=$this->loadModel($id,'`status`=-1 AND `p_id`!=0');
		if($this->loadModel($model->p_id,'`status` !=-1 AND `p_id`=0'))
		{
			if($model->updateByPk($id,array('status'=>0)))
				$this->log('还原供应商子账号',ManageLog::admin,ManageLog::update);
		}
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
	}

// 	/**
// 	 * 彻底删除
// 	 * @param integer $id
// 	 */
// 	public function actionClear($id)
// 	{
// 		if($this->loadModel($id,'`status`=-1 AND `p_id`=0')->delete())
// 		{	
// 			$this->_class_model='StoreContent';
// 			$this->loadModel($id)->delete();//删除信息表
// 			StoreUser::model()->deleteAll('p_id=:id',array(':id'=>$id));//删除所有的子账号
// 			$this->log('彻底删除供应商主账号',ManageLog::admin,ManageLog::delete);
// 		}
// 		if(!isset($_GET['ajax']))
// 			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
// 	}
        
//    /**
// 	 * 彻底删除====子账号
// 	 * @param integer $id
// 	 */
// 	public function actionClearSon($id)
// 	{
// 		if($this->loadModel($id,'`status`=-1 AND `p_id`!=0')->delete())
// 			$this->log('彻底删除供应商子账号',ManageLog::admin,ManageLog::delete);
			
// 		if(!isset($_GET['ajax']))
// 			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
// 	}

	/**
	 * 垃圾回收页
	 */
	public function actionIndex()
	{
		$criteria=new CDbCriteria;
		$criteria->addColumnCondition(array('t.status'=>-1,'t.p_id'=>'0'));
		$criteria->with=array(
			'Store_Content'=>array(
				'with'=>array('Content_area_id_p_Area_id','Content_area_id_m_Area_id','Content_area_id_c_Area_id')
			),
			'Store_Agent'=>array('select'=>'phone,firm_name'),);

		$model=new StoreUser;
		$model->Store_Content=new StoreContent;

		$this->render('index',array(
			'model'=>$model->search($criteria),
		));
	}
        
	/**
	 * 垃圾回收页====子账号
	 */
	public function actionIndexSon()
	{
		$criteria=new CDbCriteria;
        $criteria->compare('t.p_id','>0');
		$criteria->addColumnCondition(array('t.status'=>-1));
		$criteria->with=array('Store_Store'=>array('with'=>array('Store_Content')));

		$model=new StoreUser('search');
		$model->Store_Store=new StoreUser('search');
		$model->Store_Store->Store_Content=new StoreContent('search');
                
		$this->render('indexson',array(
			'model'=>$model->search_son($criteria),
		));
	}
	
	/**
	 *管理页
	 */
	public function actionAdmin()
	{

		$model=new StoreUser('search');
		$model->Store_Content=new StoreContent('search');
		//$model->Store_Agent=new Store_Agent('search');
		$model->unsetAttributes();  // 删除默认属性
		$model->Store_Content->unsetAttributes();  // 删除默认属性
		//$model->Store_Agent->unsetAttributes();  // 删除默认属性
                
		if(isset($_GET['StoreUser']))
			$model->attributes=$_GET['StoreUser'];
		if(isset($_GET['StoreContent']))
			$model->Store_Content->attributes=$_GET['StoreContent'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}
        /**
	 *管理页==供应商子账号
	 */
	public function actionSon($id='')
	{
		$model=new StoreUser('search');
		$model->Store_Store=new StoreUser('search');
		$model->Store_Store->Store_Content=new StoreContent('search');
                
		$model->unsetAttributes();  // 删除默认属性
		$model->Store_Store->unsetAttributes();  // 删除默认属性
		$model->Store_Store->Store_Content->unsetAttributes();  // 删除默认属性
		if($id != '')
			$model->p_id=$id;
		if(isset($_GET['StoreUser']))
			$model->attributes=$_GET['StoreUser'];
        if(isset($_GET['StoreContent']))
			$model->Store_Store->Store_Content->attributes=$_GET['StoreContent'];

		$this->render('son',array(
			'model'=>$model,
		));
	}
	
	/**
	 * 禁用
	 * @param integer $id
	 */
	public function actionDisable($id)
	{	
		if($this->loadModel($id,'status=1 AND p_id=0')->updateByPk($id,array('status'=>0)))
		{
			StoreUser::model()->updateAll(array('status'=>0),'`p_id`=:id',array(':id'=>$id));
			$this->log('禁用供应商主账号及子账号',ManageLog::admin,ManageLog::update);
		}
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
	}
        
	/**
	 * 禁用====子账号
	 * @param integer $id
	 */
	public function actionDisableSon($id){
		if($this->loadModel($id,'`status`=1 AND `p_id`!=0')->updateByPk($id,array('status'=>0)))
			$this->log('禁用供应商子账号',ManageLog::admin,ManageLog::update);
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
	}
	
	/**
	 * 激活
	 * @param integer $id
	 */
	public function actionStart($id){
		if($this->loadModel($id,'`status`=0 AND `p_id`=0')->updateByPk($id,array('status'=>1)))
	 		$this->log('激活供应商主账号',ManageLog::admin,ManageLog::update);
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
	}
        
    /**
	 * 激活====子账号
	 * @param integer $id
	 */
	public function actionStartSon($id)
	{
		$model=$this->loadModel($id,'`status`=0 AND `p_id`!=0');	
		$model_father=$this->loadModel($model->p_id,array(
			'condition'=>'`t`.`status`=1 AND `t`.`p_id`=0',
			'with'=>array('Store_Content'),
		));
		if((StoreContent::getSonNumber($model_father->id) +1 <= $model_father->Store_Content->son_limit))
		{
			if($model->updateByPk($id,array('status'=>1)))
				$this->log('激活供应商子账号',ManageLog::admin,ManageLog::update);
		}else
			throw new CHttpException(404, '不可超过子账号数量上限 ：'.$model_father->Store_Content->son_limit);
				
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
	}
               
	/**
	 * 设置银行信息
	 * @param unknown $id
	 */
	public function actionBank($id)
	{
		$model=$this->loadModel($id,array('with'=>'Store_Content','condition'=>'`t`.`p_id`=0'));

		$model->Store_Content->scenario='bank';
		$this->_Ajax_Verify($model->Store_Content,'store-form');
                
		if( isset($_POST['StoreContent']))
		{
            $model->Store_Content->attributes=$_POST['StoreContent'];
			if($model->Store_Content->save() && $this->log('更新/设置供应商主账号的银行信息',ManageLog::admin,ManageLog::update))
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
        $this->_class_model='StoreContent';
		//查看是否需要设置保证金 注意（直接关联关系）
		$model->DepositLog_StoreContent=$this->loadModel($id,array('with'=>'Content_Store','condition'=>'`Content_Store`.`p_id`=0 AND `Content_Store`.`status`=1'));
                               
		//验证用到
		$model->deposit_id=$model->DepositLog_StoreContent->id;//被设置角色的id
		$model->deposit_who=DepositLog::deposit_store;//被设置的角色
		
		$this->_Ajax_Verify($model,'store-user-form');
		
		if(isset($_POST['DepositLog']) && Yii::app()->request->unsetCsrfToken())
		{
			$model->attributes=$_POST['DepositLog'];
			$model->admin_id=Yii::app()->admin->id;//操作的人
			$model->deposit_id=$model->DepositLog_StoreContent->id;//被设置角色的id
			$model->deposit_who=DepositLog::deposit_store;//被设置的角色
            $model->deposit_old=$model->DepositLog_StoreContent->deposit;//保存改之前的保证金
            if($model->validate())
            {
				$transaction=$model->dbConnection->beginTransaction();
				try
				{
					if($model->save(false))
					{
						//保证金类型
						if($model->deposit_status==DepositLog::type_deduct)
							$action = '-';
						elseif($model->deposit_status==DepositLog::type_add)
							$action = '+';
						else {
							Yii::log('保证金计算类型错误', 'error', __METHOD__);
							throw new Exception("保证金计算类型错误");
						}
						if($model->DepositLog_StoreContent->updateByPk($model->DepositLog_StoreContent->id,array(
							'deposit'=>new CDbExpression('`deposit`'.$action.':deposit',array(':deposit'=>$model->deposit)),
						)))
						{
							if(!! $StoreContent=StoreContent::model()->findByPk(
									$model->DepositLog_StoreContent->id,array('select'=>'deposit')))
							{
								if($StoreContent->deposit < 0) {
									Yii::log('扣除保证金小于零错误', 'error', __METHOD__);
									throw new Exception("扣除保证金小于零错误");
								}
								else 
									$return=$this->log('创建保证金记录/设置供应商的保证金',ManageLog::admin,ManageLog::create);	
							}else {
								Yii::log('保证金查账供应商用户错误', 'error', __METHOD__);
								throw new Exception("保证金查账供应商用户错误");
							}
						}else {
							Yii::log('更新保证金错误', 'error', __METHOD__);
							throw new Exception("更新保证金错误");
						}
					}else {
						Yii::log('添加保证金日志错误', 'error', __METHOD__);
						throw new Exception("添加保证金日志错误");
					}
					$transaction->commit();
				}
				catch(Exception $e)
				{
					$transaction->rollBack();
					$this->error_log($e->getMessage(), ErrorLog::admin, ErrorLog::update,ErrorLog::rollback,__METHOD__);
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
	 *标签选择页
	 */
	public function actionSelect($id)
	{
		$model=new Tags('search');
		$model->unsetAttributes();  // 删除默认属性
		if(isset($_GET['Tags']))
			$model->attributes=$_GET['Tags'];

		$this->render('select',array(
			'model'=>$model,
			'select'=>$this->loadModel($id,'`p_id`=0'),
		));
	}

	/**
	 * 标签选中
	 * @param unknown $id
	 * @param string $type
	 */
	public function actionTags($id)
	{
		if(isset($_POST['tag_ids']) && $_POST['tag_ids'] && isset($_POST['type']))
		{
			$type = $_POST['type'];
			if(! is_array($_POST['tag_ids']))
				$_POST['tag_ids']=array($_POST['tag_ids']);
			$model= $this->loadModel($id,'`p_id`=0');
			$tags_ids=Tags::filter_tags($_POST['tag_ids']);//安全过滤tags id
			if($type=='yes'){
				//过滤之前有的
				$save_tags_id=TagsElement::not_select_tags_element($tags_ids,$id,TagsElement::tags_store_content);
				$return=TagsElement::select_tags_ids_save($save_tags_id, $id, TagsElement::tags_store_content);
			}else
				$return=TagsElement::select_tags_ids_delete($tags_ids, $id, TagsElement::tags_store_content);
			if($return)
			{
				if($type=='yes')
					$this->log('供应商(主)添加标签', ManageLog::admin,ManageLog::create);
				else
					$this->log('供应商(主)去除标签', ManageLog::admin,ManageLog::clear);
				echo 1;
			}else
				echo '操作过于频繁，请刷新页面从新选择！';
		}else
			echo '没有选中标签，请重新选择标签！';
	}
}
