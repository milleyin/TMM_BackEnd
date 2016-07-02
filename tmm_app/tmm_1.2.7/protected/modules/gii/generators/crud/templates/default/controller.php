<?php
/**
 * This is the template for generating a controller class file for CRUD feature.
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
?>
<?php echo "<?php\n"; ?>
/**
 * @author Changhai Zhan
 *	创建时间：<?php echo date('Y-m-d H:i:s');?>
 */
class <?php echo $this->controllerClass; ?> extends <?php echo $this->baseControllerClass."\n"; ?>
{
	/**
	 * 默认操作数据模型
	 * @var string
	 */
	public $_class_model = '<?php echo $this->modelClass; ?>';

	/**
	 * 查看详情
	 * @param integer $id
	 */
	public function actionView($id)
	{
		$this->render('view', array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * 创建
	 */
	public function actionCreate()
	{
		$model = new <?php echo $this->modelClass; ?>;
	
		$model->scenario = 'create';
		$this->_Ajax_Verify($model, '<?php echo $this->class2id($this->modelClass);?>-form');
		
		if (isset($_POST['<?php echo $this->modelClass; ?>']))
		{
			$model->attributes = $_POST['<?php echo $this->modelClass; ?>'];
			if ($model->save() && $this->log('创建<?php echo $this->modelClass; ?>', ManageLog::admin, ManageLog::create))
				$this->back();
		}

		$this->render('create', array(
			'model'=>$model,
		));
	}

	/**
	 * 更新
	 * @param integer $id
	 */
	public function actionUpdate($id)
	{
		$model = $this->loadModel($id);

		$model->scenario = 'update';
		$this->_Ajax_Verify($model, '<?php echo $this->class2id($this->modelClass);?>-form');

		if (isset($_POST['<?php echo $this->modelClass; ?>']))
		{
			$model->attributes = $_POST['<?php echo $this->modelClass; ?>'];
			if ($model->save() && $this->log('更新<?php echo $this->modelClass;?>', ManageLog::admin, ManageLog::update))
				$this->back();
		}

		$this->render('update', array(
			'model'=>$model,
		));
	}

	/**
	 * 删除
	 * @param integer $id
	 */
	public function actionDelete($id)
	{
		if ($this->loadModel($id, '`status`=0')->updateByPk($id, array('status'=>-1)))
			$this->log('删除<?php echo $this->modelClass;?>', ManageLog::admin, ManageLog::delete);
			
		if (!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
	}
	
	/**
	 * 还原
	 * @param integer $id
	 */
	public function actionRestore($id)
	{
		if ($this->loadModel($id, '`status`=-1')->updateByPk($id, array('status'=>1)))
			$this->log('还原<?php echo $this->modelClass;?>', ManageLog::admin, ManageLog::update);
			
		if ( !isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
	}
	
	/**
	 * 审核通过
	 * @param integer $id
	 */
	public function actionPass($id)
	{
		
	}
	
	/**
	 * 审核不通过
	 * @param integer $id
	 */
	public function actionNopass($id)
	{
	
	}
	
	/**
	 * 彻底删除
	 * @param integer $id
	 */
	public function actionClear($id)
	{
		if ($this->loadModel($id, '`status`=-1')->delete())
			$this->log('彻底删除<?php echo $this->modelClass;?>', ManageLog::admin, ManageLog::delete);
			
		if ( !isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
	}

	/**
	 * 垃圾回收页
	 */
	public function actionIndex()
	{
		$criteria = new CDbCriteria;
		//$criteria->with = array();
		$criteria->addColumnCondition(array('status'=>-1));

		$model = new <?php echo $this->modelClass; ?>;
		
		$this->render('index', array(
			'model'=>$model->search($criteria),
		));
	}
	
	/**
	 *管理页
	 */
	public function actionAdmin()
	{
		$model = new <?php echo $this->modelClass; ?>('search');
		// 删除默认属性
		$model->unsetAttributes();
		if (isset($_GET['<?php echo $this->modelClass; ?>']))
			$model->attributes = $_GET['<?php echo $this->modelClass; ?>'];

		$this->render('admin', array(
			'model'=>$model,
		));
	}
	
	/**
	 * 禁用
	 * @param integer $id
	 */
	public function actionDisable($id)
	{
		if ($this->loadModel($id, '`status`=1')->updateByPk($id, array('status'=>0)))
			$this->log('禁用<?php echo $this->modelClass; ?>', ManageLog::admin, ManageLog::update);
		
		if (!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
	}
	
	/**
	 * 激活
	 * @param integer $id
	 */
	public function actionStart($id)
	{
		if ($this->loadModel($id, '`status`=0')->updateByPk($id, array('status'=>1)))
	 		$this->log('激活<?php echo $this->modelClass; ?>', ManageLog::admin, ManageLog::update);
	 		
		if ( !isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
	}
}
