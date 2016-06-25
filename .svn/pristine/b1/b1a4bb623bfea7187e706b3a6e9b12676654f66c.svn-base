<?php
/**
 * 
 * @author Moore
 *	创建时间：2015-07-30 18:32:42 */
class Agent_adminController extends AgentController
{
	/**
	 * 默认操作数据模型
	 * @var string
	 */
	public $_class_model='Agent';

    /**
     * 查看个人详情
     * @param integer $id
     */
    public function actionOwn()
    {
        $this->render('view',array(
            'model'=>$this->loadModel(Yii::app()->agent->id),
        ));
    }

	/**
	 * 查看详情
	 * @param integer $id
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
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
			$model->attributes=$_POST['Agent'];
			if($model->save() && $this->log('创建Agent',ManageLog::agent,ManageLog::create))
				$this->back();
		}

		$this->render('create',array(
			'model'=>$model,
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
			// 取出公司营业执照扫描
			$bl_img = $model->bl_img;
			$model->attributes=$_POST['Agent'];

			// 上传图片
			if (!! $file = CUploadedFile::getInstance($model, 'bl_img')) {
				// 设置上传路径
				$uploadfile = './uploads/agent/' . $model->id . '/' . date('Ymd');
				// 设置文件名
				$model->bl_img = $uploadfile . '/' . uniqid() . '.' . $file->extensionName;
			} else {
				$model->scenario = 'update_img';
				$model->bl_img = $bl_img;
			}

			if($model->save() && $this->log('更新Agent',ManageLog::agent,ManageLog::update)) {
				if ($file) {
					if (! is_dir($uploadfile)) {
						// 创建上传目录
						mkdir($uploadfile, 0777, true);
					}
					$file->saveAs($model->bl_img);
					if (file_exists($bl_img)) {
						unlink($bl_img);
					}
				}
				$this->back();
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
		//$this->loadModel($id)->delete();
		if($this->loadModel($id)->updateByPk($id,array('status'=>-1)))
			$this->log('删除Agent',ManageLog::agent,ManageLog::delete);
			
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
			$this->log('还原Agent',ManageLog::agent,ManageLog::update);
			
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
	}
	
	/**
	 * 审核通过
	 * @param integer $id
	 */
	public function actionPass($id){
		
	}
	
	/**
	 * 审核不通过
	 * @param integer $id
	 */
	public function actionNopass($id){
	
	}
	
	/**
	 * 彻底删除
	 * @param integer $id
	 */
	public function actionClear($id)
	{
		if($this->loadModel($id,'`status`=-1')->delete())
			$this->log('彻底删除Agent',ManageLog::agent,ManageLog::delete);
			
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
	}

	/**
	 * 垃圾回收页
	 */
	public function actionIndex()
	{
		$criteria=new CDbCriteria;
		//$criteria->with=array();
		$criteria->addColumnCondition(array('status'=>-1));

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
			$this->log('禁用Agent',ManageLog::agent,ManageLog::update);
		$this->back();
	}
	
	/**
	 * 激活
	 * @param integer $id
	 */
	public function actionStart($id){
		if($this->loadModel($id,'`status`=0')->updateByPk($id,array('status'=>1)))
	 		$this->log('激活Agent',ManageLog::agent,ManageLog::update);
		$this->back();
	}
}
