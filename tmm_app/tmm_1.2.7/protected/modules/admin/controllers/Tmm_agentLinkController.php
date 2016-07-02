<?php
/**
 * 运营商链接控制器
 * @author Changhai Zhan
 */
class Tmm_agentLinkController extends MainController
{
	/**
	 * 操作模型
	 * @var unknown
	 */
	public $_class_model = 'AgentLink';
	
	/**
	 * 显示
	 * @param unknown $id
	 */
	public function actionView($id)
	{
		$this->render('view', array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * 管理页 
	 */
	public function actionIndex()
	{
		$this->addJs(Yii::app()->baseUrl.'/css/admin/ext/checktree/jquery.checktree.js');
		$this->addCss(Yii::app()->baseUrl.'/css/admin/ext/checktree/checktree.css');
		$this->addCss(Yii::app()->baseUrl.'/css/admin/ext/tree.css');
		
		$criteria = new CDbCriteria;
		$criteria->addColumnCondition(array('t.p_id'=>0));
		$criteria->order = 't.sort';
		$criteria->with = array(
				'Link_Link'=>array(
						'with'=>array(
								'Link_Link_Link'=>array('order'=>'Link_Link_Link.sort')
						),
						'order'=>'Link_Link.sort',
		));
		$this->render('index', array(
				'model'=>AgentLink::model()->findAll($criteria)
		));
	}

	/**
	 * 添加分组
	 * @param unknown $id
	 */
	public function actionGroup($id)
	{
		$model = new AgentLink;
		
		$model->scenario = 'group';
		$this->_Ajax_Verify($model, 'agent-link-form');
		
		$group = $this->loadModel($id, '`p_id`=0');
		if (isset($_POST['AgentLink']))
		{
			$model->attributes = $_POST['AgentLink'];
			$model->p_id = $id;
			if ($model->save() && $this->log('创建运营商分组',ManageLog::admin,ManageLog::create))
				$this->back();
		}
		$this->render('group', array(
				'model'=>$model,
				'group'=>$group,
		));
	}
	
	/**
	 * 修改分组
	 * @param unknown $id
	 */
	public function actionUpgroup($id)
	{
		$model = $this->loadModel($id);
		
		$model->scenario = 'group';
		$this->_Ajax_Verify($model, 'agent-link-form');
		
		$group = $this->loadModel($model->p_id);
		if (isset($_POST['AgentLink']))
		{
			$model->attributes = $_POST['AgentLink'];
			if ($model->save() && $this->log('修改运营商分组',ManageLog::admin,ManageLog::update))
				$this->back();			
		}
		$this->render('group', array(
				'model'=>$model,
				'group'=>$group,
		));
	}
	
	/**
	 *移动分组
	 * @param unknown $id
	 */
	public function actionMovegroup($id)
	{
		$model = $this->loadModel($id);
		
		$model->scenario = 'mgroup';
		$model->nav = $model->p_id;
		$this->_Ajax_Verify($model, 'agent-link-form');
		
		$group=$this->loadModel($model->p_id);
		if(isset($_POST['AgentLink']))
		{
			$model->attributes=$_POST['AgentLink'];
			$model->p_id=$model->nav;
			if($model->save() && $this->log('移动运营商分组',ManageLog::admin,ManageLog::update))
				$this->back();
		}
		$nav=AgentLink::model()->findAll(array(
				'condition'=>'`p_id`=0 AND `status`=1',
				'order'=>'sort',
		));
		$this->render('group',array(
				'model'=>$model,
				'group'=>$group,
				'nav'=>$nav,
		));
	}
	
	/**
	 * 添加链接
	 * @param unknown $id
	 */
	public function actionMenu($id)
	{
		$model=new AgentLink;
		$model->scenario='menu';
		$this->_Ajax_Verify($model,'agent-link-form');
		$menu=$this->loadModel($id,'`url`=:url AND status=1',array(':url'=>'#'));
		if(isset($_POST['AgentLink']))
		{
			$model->attributes=$_POST['AgentLink'];
			$model->p_id=$id;
			if($model->save() && $this->log('创建运营商链接',ManageLog::admin,ManageLog::create))
				$this->back();
		}
		$this->render('menu',array(
				'model'=>$model,'menu'=>$menu,
		));
	}
	
	/**
	 * 修改链接
	 * @param unknown $id
	 */
	public function actionUpaction($id)
	{
		$model=$this->loadModel($id);
		$model->scenario='menu';
		$this->_Ajax_Verify($model,'agent-link-form');
		$menu=$this->loadModel($model->p_id);
		if(isset($_POST['AgentLink']))
		{
			$model->attributes=$_POST['AgentLink'];
			if($model->save() && $this->log('修改运营商链接',ManageLog::admin,ManageLog::update))
				$this->back();
		}
		$this->render('menu',array(
				'model'=>$model,'menu'=>$menu,
		));
	}
	
	/**
	 * 移动链接
	 * @param unknown $id
	 */
	public function actionMoveaction($id)
	{
		$model=$this->loadModel($id);
		$model->scenario='mmenu';
		if(isset($_POST['p_id']) && $_POST['p_id'] != '')
		{
			$action=AgentLink::model()->findAll(array(
					'condition'=>'`p_id`=:p_id and `url`=:url and `status`=1',
					'params'=>array(':url'=>'#',':p_id'=>$_POST['p_id'])
			));
			foreach($action as $v)
				echo CHtml::tag('option', array('value'=>$v->id),CHtml::encode($v->name), true);
			exit;
		}
		$this->_Ajax_Verify($model,'agent-link-form');
		$model->group=$model->p_id;
		$menu=$this->loadModel($model->p_id,'`url`=:url and `status`=1',array(':url'=>'#'));
		$model->nav=$menu->p_id;
		if(isset($_POST['AgentLink']))
		{
			$model->attributes=$_POST['AgentLink'];
			$model->p_id=$model->group;
			if($model->save() && $this->log('运营商移动系统链接',ManageLog::admin,ManageLog::update))
				$this->back();		
		}
		$nav=AgentLink::model()->findAll(array(
				'condition'=>'p_id=0',
				'order'=>'sort',
		));
		$group=AgentLink::model()->findAll(array(
				'condition'=>'p_id=:p_id and url=:url and status=1',
				'params'=>array(':url'=>'#',':p_id'=>$menu->p_id),
				'order'=>'sort',
		));
		$this->render('menu',array(
				'model'=>$model,
				'menu'=>$menu,
				'nav'=>$nav,
				'group'=>$group,
		));
	}
	
	/**
	 * 更新导航
	 * @param unknown $id
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id,'`p_id`=0');
		$model->scenario='update';
		$this->_Ajax_Verify($model,'agent-link-form');
		if(isset($_POST['AgentLink']))
		{
			$model->attributes = $_POST['AgentLink'];
			if($model->save() && $this->log('更新运营商导航',ManageLog::admin,ManageLog::update))
				$this->back();
		}
		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 *创建导航
	 */
	public function actionCreate()
	{
		$model=new AgentLink;
		$model->scenario='create';
		$this->_Ajax_Verify($model,'agent-link-form');

		if(isset($_POST['AgentLink']))
		{
			$model->attributes = $_POST['AgentLink'];
			if($model->save() && $this->log('创建运营商导航',ManageLog::admin,ManageLog::create))
				$this->back();
		}
		$this->render('create',array(
				'model'=>$model,
		));
	}
	
	/**
	 * 更新排序
	 */
	public function actionSort()
	{
		if(isset($_POST['namename'])){
			foreach ($_POST['namename'] as $k=>$v)
			{
				if(is_numeric($v) && $v >= 0 && $v<=255)
				{
					if($this->loadModel($k)->updateByPk($k,array('sort'=>$v)))
						$return=1;
				}
			}
		}
		if(isset($return))
			$this->log('运营商修改导航排序',ManageLog::admin,ManageLog::update);
		$this->back();
	}
	
	/**
	 * 删除记录 
	 * @param unknown $id
	 */
	public function actionDelete($id)
	{
		if($this->loadModel($id)->updateByPk($id,array('status'=>-1)))
			$this->log('运营商删除导航/分组/链接',ManageLog::admin,ManageLog::delete);
		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
	}
	
	/**
	 * 隐藏导航
	 * @param unknown $id
	 */
	public function actionHide($id)
	{
		$model=$this->loadModel($id,'`show`=1 AND `p_id`=0')->updateByPk($id,array('show'=>0));
		if($model)
			$this->log('运营商隐藏导航',ManageLog::admin,ManageLog::update);
		$this->back();
	}
	
	/**
	 * 显示导航
	 * @param unknown $id
	 */
	public function actionShow($id)
	{
		$model=$this->loadModel($id,'`show`=0 AND `p_id`=0')->updateByPk($id,array('show'=>1));
		if($model)
			$this->log('运营商显示导航',ManageLog::admin,ManageLog::update);
		$this->back();
	}
	
	/**
	 * 禁用
	 * @param unknown $id
	 */
	public function actionDisable($id)
	{
		$model=$this->loadModel($id,'status=1')->updateByPk($id,array('status'=>0));
		if($model)
			$this->log('禁用导航运营商/分组/链接',ManageLog::admin,ManageLog::update);			
		$this->back();
	}
	
	/**
	 * 激活
	 * @param unknown $id
	 */
	public function actionStart($id)
	{
		$model=$this->loadModel($id,'status=0')->updateByPk($id,array('status'=>1));
		if($model)
	 		$this->log('激活运营商导航/分组/链接',ManageLog::admin,ManageLog::update);
		$this->back();
	}
}
