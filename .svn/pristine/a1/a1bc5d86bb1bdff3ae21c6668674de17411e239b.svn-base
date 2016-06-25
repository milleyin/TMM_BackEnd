<?php
/**
 * 标签分类
 * @author Changhai Zhan
 *	创建时间：2015-07-31 12:08:25 */
class Tmm_tagsTypeController extends MainController
{
	/**
	 * 默认操作数据模型
	 * @var string
	 */
	public $_class_model='TagsType';

	/**
	 * 查看详情
	 * @param integer $id
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id,array(
				'with'=>array('TagsType_TagsSelect'=>array(
					'with'=>array('TagsSelect_Tags'),
				)),
			)),
		));
	}

	/**
	 * 创建
	 */
	public function actionCreate($id=0)
	{
		$model=new TagsType;
		$model->p_id=$id;	
		$model->scenario='create';
		$this->_Ajax_Verify($model,'tags-type-form');
		if(isset($_POST['TagsType']))
		{
			$model->attributes=$_POST['TagsType'];
			if($model->save() && $this->log('创建标签分类',ManageLog::admin,ManageLog::create))
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
		$this->_Ajax_Verify($model,'tags-type-form');

		if(isset($_POST['TagsType']))
		{
			$model->attributes=$_POST['TagsType'];
			if($model->save() && $this->log('更新标签分类',ManageLog::admin,ManageLog::update))
				$this->back();
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
		$model=$this->loadModel($id);
		if($model->p_id == 0){
			$transaction=$model->dbConnection->beginTransaction();
			try
			{
				TagsType::model()->updateAll(array('status'=>-1),'`p_id`=:p_id',array(':p_id'=>$model->id));
				if(!$model->updateByPk($id,array('status'=>-1)))
					throw new Exception("删除标签定级分类错误");
				else
					$this->log('删除顶级标签分类',ManageLog::admin,ManageLog::delete);
				$transaction->commit();
			}
			catch(Exception $e)
			{
				$transaction->rollBack();
				$this->error_log($e->getMessage(),ErrorLog::admin,ErrorLog::delete,ErrorLog::rollback,__METHOD__);		
			}
		}elseif($model->updateByPk($id,array('status'=>-1)))
			$this->log('删除标签分类',ManageLog::admin,ManageLog::delete);
			
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
	}
	
	/**
	 * 还原
	 * @param integer $id
	 */
	public function actionRestore($id)
	{
		$model=$this->loadModel($id,'`status`=-1');
		if($model->p_id != 0){
			$transaction=$model->dbConnection->beginTransaction();
			try
			{
				TagsType::model()->updateByPk($model->p_id,array('status'=>1));
				if(!$model->updateByPk($id,array('status'=>1)))
					throw new Exception("还原标签分类错误");
				else
					$this->log('还原标签分类',ManageLog::admin,ManageLog::update);
				$transaction->commit();
			}
			catch(Exception $e)
			{
				$transaction->rollBack();
				$this->error_log($e->getMessage(),ErrorLog::admin,ErrorLog::update,ErrorLog::rollback,__METHOD__);			
			}
		}elseif($model->updateByPk($id,array('status'=>1)))
			$this->log('还原标签顶级分类',ManageLog::admin,ManageLog::update);
			
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
	}
	
// 	/**
// 	 * 彻底删除
// 	 * @param integer $id
// 	 */
// 	public function actionClear($id)
// 	{
// 		$model=$this->loadModel($id,'`status`=-1');
// 		if($model->p_id == 0){
// 			$transaction=$model->dbConnection->beginTransaction();
// 			try
// 			{
// 				TagsType::model()->deleteAll('`p_id`=:p_id',array(':p_id'=>$model->id));
// 				if($model->delete())
// 					throw new Exception("彻底删除标签顶级分类错误");
// 				else
// 					$this->log('彻底删除标签顶级分类',ManageLog::admin,ManageLog::delete);
// 				$transaction->commit();
// 			}
// 			catch(Exception $e)
// 			{
// 				$transaction->rollBack();
// 				$this->error_log($e->getMessage(),ErrorLog::admin,ErrorLog::delete,ErrorLog::rollback,__METHOD__);			
// 			}
// 		}elseif($model->delete())	
// 				$this->log('彻底删除标签分类',ManageLog::admin,ManageLog::delete);
	
// 		if(!isset($_GET['ajax']))
// 			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
// 	}

	/**
	 * 垃圾回收页
	 */
	public function actionIndex()
	{
		$criteria=new CDbCriteria;
		//$criteria->with=array();
		$criteria->addColumnCondition(array('t.status'=>-1));
		$criteria->with=array('TagsType_TagsType'=>array('select'=>'name'));
		$model=new TagsType;
		
		$this->render('index',array(
			'model'=>$model->search($criteria),
		));
	}
	
	/**
	 * 树形显示分类
	 * @param number $id
	 */
	public function actionTree($id=0)
	{
		$this->addJs(Yii::app()->baseUrl.'/css/admin/ext/checktree/jquery.checktree.js');
		$this->addCss(Yii::app()->baseUrl.'/css/admin/ext/checktree/checktree.css');
		$this->addCss(Yii::app()->baseUrl.'/css/admin/ext/tree.css');
	
		$criteria=new CDbCriteria;	
		$criteria->with=array(
				'TagsType_TagsSelect_Count'
		);
		$criteria->order='t.sort';
		$criteria->addCondition('t.status !=-1');
	
		$model=TagsType::model()->findAll($criteria);
		
		$this->render('tree',array(
				'html'=>TagsType::tree($model,$id),
		));
	}
	
	/**
	 * 更新排序
	 */
	public function actionSort(){
		if(isset($_POST['namename'])){
			foreach ($_POST['namename'] as $k=>$v){
				if(is_numeric($v) && $v >= 0 && $v<=255){
					if($this->loadModel($k)->updateByPk($k,array('sort'=>$v)))
						$return=1;
				}
			}
		}
		if(isset($return))
			$this->log('修改分类排序',ManageLog::admin,ManageLog::update);
		$this->back();
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
				'select'=>$this->loadModel($id,'`p_id` !=0'),
		));
	}
	
	/**
	 * 标签选中
	 * @param unknown $id
	 * @param string $type
	 */
	public function actionTags($id){
		if(isset($_POST['tag_ids']) && !empty($_POST['tag_ids']) && isset($_POST['type']))
		{
			$type=$_POST['type'];
			if(!is_array($_POST['tag_ids']))
				$_POST['tag_ids']=array($_POST['tag_ids']);
			$model=$this->loadModel($id,'`p_id` !=0 && `status`=1');
			$tags_ids=Tags::filter_tags($_POST['tag_ids']);//过滤tags id
			if($type=='yes'){
				//过滤已经有的
				$tags_ids_save=TagsSelect::not_select_tags_type($tags_ids, $model->id);
				//保存
				$return=TagsSelect::select_tags_ids_save($tags_ids_save, $model->id);
			}else
				$return=TagsSelect::select_tags_ids_delete($tags_ids, $model->id);
			if(isset($return)){
				if($type=='yes')
					$this->log('给分类标签赋给标签', ManageLog::admin,ManageLog::create);
				else
					$this->log('给分类标签去除标签', ManageLog::admin,ManageLog::clear);
				echo 1;
			}else
				echo '操作过于频繁，请刷新页面从新选择！';
		}else 
			echo '没有选中标签，请重新选择标签！';
	}
	
	/**
	 *管理页
	 */
	public function actionAdmin()
	{
		$model=new TagsType('search');
		$model->unsetAttributes();  // 删除默认属性
		if(isset($_GET['TagsType']))
			$model->attributes=$_GET['TagsType'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}
	
	/**
	 * 禁用
	 * @param integer $id
	 */
	public function actionDisable($id){
		$model=$this->loadModel($id,'`status`=1');
		if($model->p_id == 0){
			$transaction=$model->dbConnection->beginTransaction();
			try
			{
				TagsType::model()->updateAll(array('status'=>0),'`p_id`=:p_id',array(':p_id'=>$model->id));
				if(!$model->updateByPk($id,array('status'=>0)))
					throw new Exception("禁用标签顶级分类错误");
				else
					$this->log('禁用标签顶级分类',ManageLog::admin,ManageLog::update);
				$transaction->commit();
			}
			catch(Exception $e)
			{
				$transaction->rollBack();
				$this->error_log($e->getMessage(),ErrorLog::admin,ErrorLog::update,ErrorLog::rollback,__METHOD__);
			}
		}elseif($model->updateByPk($id,array('status'=>0)))
			$this->log('禁用标签分类',ManageLog::admin,ManageLog::update);	
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
	}
	
	/**
	 * 激活
	 * @param integer $id
	 */
	public function actionStart($id){
		$model=$this->loadModel($id,'`status`=0');
		if($model->p_id != 0){
			$transaction=$model->dbConnection->beginTransaction();
			try
			{
				TagsType::model()->updateByPk($model->p_id,array('status'=>1));
				if(!$model->updateByPk($id,array('status'=>1)))
					throw new Exception("激活标签分类错误");
				else
					$this->log('激活标签分类',ManageLog::admin,ManageLog::update);
				$transaction->commit();
			}
			catch(Exception $e)
			{
				$transaction->rollBack();
				$this->error_log($e->getMessage(),ErrorLog::admin,ErrorLog::update,ErrorLog::rollback,__METHOD__);
			}
		}elseif($model->updateByPk($id,array('status'=>1)))
			$this->log('激活标签分类',ManageLog::admin,ManageLog::update);
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
	}
}
