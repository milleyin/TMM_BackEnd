<?php
/**
 * 
 * @author Changhai Zhan
 *	创建时间：2015-08-03 12:17:32 */
class Tmm_userController extends MainController
{
	/**
	 * 默认操作数据模型
	 * @var string
	 */
	public $_class_model='User';

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
		$model=new User;
	
		$model->scenario='create';
		$this->_Ajax_Verify($model,'user-form');
		
		if(isset($_POST['User']))
		{
			$model->attributes=$_POST['User'];
			$model->password=$model::pwdEncrypt($model->confirm_pwd);
			if($model->save() && $this->log('创建用户',ManageLog::admin,ManageLog::create))
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
		$this->_Ajax_Verify($model,'user-form');

		if(isset($_POST['User']))
		{
			$model->attributes=$_POST['User'];
			if($model->confirm_pwd !='')
				$model->password=$model::pwdEncrypt($model->confirm_pwd);
			if($model->save() && $this->log('更新用户',ManageLog::admin,ManageLog::update))
			{
				// Retinue随行人员模型
				Retinue::model()->updateAll(array('phone' => $model->phone),
					'user_id=:user_id AND is_main=1 AND status=1', 
					array(':user_id' =>$model->id)
				);
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
			$this->log('删除用户/代理商',ManageLog::admin,ManageLog::delete);
			
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
			$this->log('还原用户/代理商',ManageLog::admin,ManageLog::update);
			
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
	}
	
// 	/**
// 	 * 彻底删除
// 	 * @param integer $id
// 	 */
// 	public function actionClear($id)
// 	{
// 		if($this->loadModel($id,'`status`=-1')->delete())
// 			$this->log('彻底删除用户/代理商',ManageLog::admin,ManageLog::delete);
			
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
		$criteria->addColumnCondition(array('status'=>-1));

		$model=new User;
		
		$this->render('index',array(
			'model'=>$model->search($criteria),
		));
	}
	
	/**
	 * 用户标签分类选择
	 * @param unknown $id
	 */
	public function actionTree($id)
	{
		$this->addJs(Yii::app()->baseUrl.'/css/admin/ext/dynatree/jquery-ui.custom.min.js');
		$this->addCss(Yii::app()->baseUrl.'/css/admin/ext/dynatree/skin/ui.dynatree.css');
		$this->addJs(Yii::app()->baseUrl.'/css/admin/ext/dynatree/jquery.dynatree.min.js');
		
		$model=$this->loadModel($id,'`status`=1');
		$model->User_TagsElement=new TagsElement;
		
		$model->User_TagsElement->scenario='user_tags_type';
		$this->_Ajax_Verify($model->User_TagsElement,'user-form');	
		//用户原来选择的标签分类
		$select_tags_type=TagsElement::user_select_tages_type($model->id);
		$select=array();
		if($select_tags_type)
		{
			foreach ($select_tags_type as $type)
				$select[]=$type->type_id;
		}	
		if(isset($_POST['TagsElement']))
		{
			$model->User_TagsElement->attributes=$_POST['TagsElement'];
			$select_tags = array_unique(explode(',', $model->User_TagsElement->user_select_tags_type));
			$yes_select_tags=TagsType::user_tages_type(true);//可以选择的tag_type
			foreach ($select_tags as $k=>$v){
				if(! in_array($v, $yes_select_tags))
					unset($select_tags[$k]);
			}

			$deletes=$saves=array();
			foreach ($select as $type)
			{
				if(!in_array($type, $select_tags))
					$deletes[]=$type;			
			}
			foreach ($select_tags as $tags)
			{
				if(!in_array($tags, $select))
					$saves[]=$tags;
			}
			if(!empty($saves) || !empty($deletes))
			{
				$transaction = $model->dbConnection->beginTransaction();
				try {		
					if(!empty($saves)){
						foreach($saves as $save)
						{
							$user_type=clone $model->User_TagsElement;
							$user_type->select_id=Yii::app()->admin->id;
							$user_type->select_type=TagsElement::admin;
							$user_type->element_id=$id;
							$user_type->element_type=TagsElement::tags_user;	
							$user_type->type_id=$save;
							//创建
							if(!$user_type->save())
							{
								throw new Exception("添加用户属性标签错误");
								continue;
							}
						}
					}
					if(!empty($deletes)){
						$criteria = new CDbCriteria;
						$criteria->addInCondition('type_id', $deletes) ;
						$criteria->addColumnCondition(array(
								'element_type'=>TagsElement::tags_user,
								'element_id'=>$id,
						));
						// 删除
						TagsElement::model()->deleteAll($criteria);
					}
					$return=$this->log('更新用户属性标签',ManageLog::admin,ManageLog::update);		
					$transaction->commit();	
				} catch (Exception $e) {
					$transaction->rollBack();
					$this->error_log($e->getMessage(),ErrorLog::admin,ErrorLog::update,ErrorLog::rollback,__METHOD__);					
				}
			}
			if(isset($return))
				$this->back();		
		}
		
		$this->render('tree',array(
				'json'=>TagsType::dynatree(TagsType::user_tages_type(),$select),
				'model'=>$model,
		));	
	}
	
	/**
	 *管理页
	 */
	public function actionAdmin()
	{
		$model=new User('search');
		$model->unsetAttributes();  // 删除默认属性
		if(isset($_GET['User']))
			$model->attributes=$_GET['User'];

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
			$this->log('禁用用户/代理商',ManageLog::admin,ManageLog::update);			
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
	}
	
	/**
	 * 激活
	 * @param integer $id
	 */
	public function actionStart($id){
		if($this->loadModel($id,'`status`=0')->updateByPk($id,array('status'=>1)))
	 		$this->log('激活用户/代理商',ManageLog::admin,ManageLog::update);
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
	}
}
