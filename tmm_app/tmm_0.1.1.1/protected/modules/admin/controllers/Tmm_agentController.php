<?php
/**
 * 
 * @author Changhai Zhan
 *	创建时间：2015-08-03 11:46:00 */
class Tmm_agentController extends MainController
{
	/**
	 * 默认操作数据模型
	 * @var string
	 */
	public $_class_model='Agent';

	/**
	 * 初始化
	 * @see MainController::init()
	 */
	public function init()
	{
		parent::init();
		$this->_upload = Yii::app()->params['uploads_agent_image'];
	}

	/**
	 * 查看详情
	 * @param integer $id
	 */
	public function actionView($id)
	{
		$this->render('view',array(
				'model'=>$this->loadModel($id,array('with'=>array(		
						'Agent_area_id_p_Area_id',
						'Agent_area_id_m_Area_id',
						'Agent_area_id_c_Area_id',
						'Agent_Bank',
						'Agent_Admin',
				),
			)),
		));
	}

	/**
	 * 创建
	 */
	public function actionCreate()
	{
		$model=new Agent;

		$model->scenario='create';
		$this->_Ajax_Verify($model,'agent-form');

		if(isset($_POST['Agent']))
		{

			$model->attributes = $_POST['Agent'];
			$model->admin_id = Yii::app()->admin->id;
			$model->password = $model::pwdEncrypt($model->_pwd);
			$model->status = 0; // 默认没有生效

			// 上传图片
			$uploads=	array('bl_img','identity_before', 'identity_after', 'identity_hand');
			$files = $this->upload($model, $uploads);

			if ($this->upload_error($model, $files, $uploads) && $model->save() && $this->log('创建运营商',ManageLog::admin,ManageLog::create)) {
				$this->upload_save($model, $files);
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
		$model=$this->loadModel($id);

		$model->scenario='update';
		$this->_Ajax_Verify($model,'agent-form');

		if(isset($_POST['Agent']))
		{
			// 上传图片
			$uploads=	array('bl_img','identity_before', 'identity_after', 'identity_hand');
			// 保存原来的值
			$data = $this->upload_save_data($model, $uploads);
			// 获取表单的值
			$model->attributes = $_POST['Agent'];
			// 修改密码
			if (trim($model->_pwd) != '') {
				$model->password = $model::pwdEncrypt($model->_pwd);
			}
			// 获取上传的值
			$files = $this->upload($model, $uploads);
			// 提前验证
			if ($model->validate()) {
				// 没有上传的赋回原来的值
				$old_path = $this->upload_update_data($model, $data, $files);
				if($model->save(false) && $this->log('更新运营商',ManageLog::admin,ManageLog::update))  {
					// 保存新上传的图片
					$this->upload_save($model, $files);
					// 删除原来的图片
					$this->upload_delete($old_path);
					$this->back();
				}
			}
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * 删除
	 * @param integer $id
	 */
	public function actionDelete($id)
	{
		if($this->loadModel($id,'`status`=0')->updateByPk($id,array('status'=>-1)))
			$this->log('删除运营商',ManageLog::admin,ManageLog::delete);
			
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
	}
	
	/**
	 * 还原
	 * @param integer $id
	 */
	public function actionRestore($id)
	{
		if($this->loadModel($id,'`status`=-1')->updateByPk($id,array('status'=>1)))
			$this->log('还原运营商',ManageLog::admin,ManageLog::update);
			
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
	}

	/**
	 * 设置银行信息
	 * @param unknown $id
	 */
	public function actionBank($id) {
		$model=$this->loadModel($id, '`status`=1');

		$model->scenario='bank';
		$this->_Ajax_Verify($model,'agent-form');
		if(isset($_POST['Agent']))
		{
			$model->attributes=$_POST['Agent'];
			if($model->save() && $this->log('更新/设置运营商的银行信息',ManageLog::admin,ManageLog::update))
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
	public function actionDeposit($id)
	{
		$model=new DepositLog;

		$model->scenario='create';

		$model->DepositLog_Agent = $this->loadModel($id, '`status`=1');

		$this->_Ajax_Verify($model,'agent-form');

		if(isset($_POST['DepositLog']) && Yii::app()->request->unsetCsrfToken())
		{
			$model->attributes=$_POST['DepositLog'];
			$model->admin_id=Yii::app()->admin->id;//操作的人
			$model->deposit_id=$model->DepositLog_Agent->id;//被设置角色的id
			$model->deposit_who=DepositLog::deposit_agent;//被设置的角色
			$model->deposit_old=$model->DepositLog_Agent->deposit;
			if ($model->validate()) {
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
						else
							throw new Exception("设置运营商的保证金计算类型错误");
						if($model->DepositLog_Agent->updateByPk($model->DepositLog_Agent->id,array(
							'deposit'=>new CDbExpression('`deposit`'.$action.':deposit',array(':deposit'=>$model->deposit)),
						)))
						{
							if(!! $agent=Agent::model()->findByPk(
								$model->DepositLog_Agent->id,array('select'=>'deposit')))
							{
								if($agent->deposit < 0)
									throw new Exception("扣除运营商的保证金小于零错误");
								else
									$return=$this->log('创建保证金记录/设置运营商的保证金',ManageLog::admin,ManageLog::create);
							}else
								throw new Exception("运营商的保证金查账错误");
						}else
							throw new Exception("更新运营商的保证金失败");
					}else
						throw new Exception("添加运营商的保证金日志错误");
					$transaction->commit();
				}
				catch(Exception $e)
				{
					$transaction->rollBack();
					$this->error_log($e->getMessage(), ErrorLog::admin, ErrorLog::create,ErrorLog::rollback,__METHOD__);
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


// 	/**
// 	 * 彻底删除
// 	 * @param integer $id
// 	 */
// 	public function actionClear($id)
// 	{
// 		if($this->loadModel($id,'`status`=-1')->delete())
// 			$this->log('彻底删除Agent',ManageLog::admin,ManageLog::delete);
			
// 		if(!isset($_GET['ajax']))
// 			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
// 	}

	/**
	 * 垃圾回收页
	 */
	public function actionIndex()
	{
		$criteria=new CDbCriteria;
		$criteria->with = array(
				'Agent_Admin'=>array('select'=>'name'),
				'Agent_area_id_p_Area_id'=>array('select'=>'name'),
				'Agent_area_id_m_Area_id'=>array('select'=>'name'),
				'Agent_area_id_c_Area_id'=>array('select'=>'name'),
				'Agent_Bank'=>array('select'=>'id,name'),
		);
		$criteria->addColumnCondition(array('t.status'=>-1));

		$model=new Agent;
		
		$this->render('index',array(
			'model'=>$model->search($criteria),
		));
	}
	
	/**
	 *管理页
	 */
	public function actionAdmin()
	{
		$model=new Agent('search');
		$model->unsetAttributes();  // 删除默认属性
		if(isset($_GET['Agent']))
			$model->attributes=$_GET['Agent'];

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
			$this->log('禁用运营商',ManageLog::admin,ManageLog::update);			
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
	}
	
	/**
	 * 激活
	 * @param integer $id
	 */
	public function actionStart($id){
		if($this->loadModel($id,'`status`=0')->updateByPk($id,array('status'=>1)))
	 		$this->log('激活运营商',ManageLog::admin,ManageLog::update);
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
	}

	/**
	 * 显示地区列表树 选择区域权限
	 */
	public function actionTree($id) {
		$this->addJs(Yii::app()->baseUrl.'/css/admin/ext/dynatree/jquery-ui.custom.min.js');
		$this->addCss(Yii::app()->baseUrl.'/css/admin/ext/dynatree/skin/ui.dynatree.css');
		$this->addJs(Yii::app()->baseUrl.'/css/admin/ext/dynatree/jquery.dynatree.min.js');

		$agentModel = $this->loadModel($id, '`status`=1');

		$agentModel->scenario = 'area';
		$this->_Ajax_Verify($agentModel,'agent-form');

		if (isset($_POST['Agent'])) {
			$agentModel->attributes = $_POST['Agent'];
			// 取到所区域地址的id
			$areaArr = explode(',', $agentModel->area_select);
			if (empty($areaArr)) {
				$agentModel->addError('area_select','您还未选择区域！');
			}else{
				// 拼成数组
				$areaArr=Area::all_area($areaArr);//验证与去掉省、市
				if (! empty($areaArr)) {
					// 开启事务
					$transaction = $agentModel->dbConnection->beginTransaction();
					try {
						//重置原来的区域
						Area::model()->updateAll(array('agent_id'=>0), '`agent_id`=:agent_id',array(':agent_id'=>$id));
						// 批量更新
						$criteria = new CDbCriteria;
						$criteria->addInCondition( "id" , $areaArr) ;
						$criteria->addCondition('`agent_id`=0');
						//设置运营商区域
						if (! Area::model()->updateAll(array('agent_id'=>$id), $criteria)) {
							throw new Exception("设置运营商的区域权限错误");
						}
						$transaction->commit();
						// 成功后
						$return=$this->log('设置运营商的区域权限',ManageLog::admin,ManageLog::update);
					} catch (Exception $e) {
						$transaction->rollBack();
						$this->error_log($e->getMessage(), ErrorLog::admin, ErrorLog::update,ErrorLog::rollback,__METHOD__);
						$this->refresh();
					}
					if(isset($return))
						$this->redirect(array('admin'));
				}
			}
		}
		$model = Area::model()->findAll(array(
			'condition'=>'t.pid=0',
			'select'=>'id,name,pid,agent_id',
			'with'=>array(
				'Area_Area'=>array(
					'select'=>'id,name,pid,agent_id',
					'with'=>array(
						'Area_Area_Area'=>array('select'=>'id,name,pid,agent_id')
					)
				)
			),
		));
		// 地区数据
		$arr = array();
		$key = 0;
		foreach($model as $ancestor){
			// 第一维
			$arr[$key] = array(
				'title' => $ancestor->name,
				'key' => $ancestor->id,
				'icon' => false,
			);
			$parent_key=0;
			foreach($ancestor->Area_Area as  $parent) {
				// 第二维
				$arr[$key]['children'][$parent_key] = array(
					'title' => $parent->name,
					'key' => $parent->id,
					'icon' => false,
				);
				foreach($parent->Area_Area_Area as $child_key => $child) {
					// 判断是否已经有人选了 unselectable hideCheckbox select
					$unSelect = false;
					$hideCheckbox= false;
					$select = false;
					if ($child->agent_id != 0 && $child->agent_id != $id) {
						$unSelect = true;
						$hideCheckbox = true;
					}
					if ($child->agent_id == $id) {
						$select = true;
					}
					// 第三维
					$arr[$key]['children'][$parent_key]['children'][$child_key] = array(
						'title' => $child->name,
						'key' => $child->id,
						'icon' => false,
						'select' => $select,
						'unselectable' => $unSelect,
						'hideCheckbox' => $hideCheckbox,
					);
				}
				$parent_key++;
			}
			$key++;
		}
		$this->render('tree',array(
			'model' => $agentModel,
			'html'=> json_encode($arr),
		));
	}

}
