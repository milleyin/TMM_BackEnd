<?php
/**
 * 广告管理
 * @author Changhai Zhan
 *	创建时间：2016-03-16 11:12:13 */
class Tmm_adController extends MainController
{
	/**
	 * 默认操作数据模型
	 * @var string
	 */
	public $_class_model = 'Ad';
		
	/**
	 * 初始化
	 * @see MainController::init()
	 */
	public function init()
	{
		parent::init();
		$this->_upload = Yii::app()->params['uploads_ad'];
	}

	/**
	 * 查看详情
	 * @param integer $id
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id, array(
				'with'=>array('Ad_Type', 'Ad_Admin', 'Ad_Ad'),
			)),
		));
	}
	
	/**
	 * 联动链接
	 * @param string $type
	 */
	public function ajaxType($type='type')
	{
		if (isset($_POST[$type]))
		{
			$models = Type::model()->getTypeModels($_POST[$type]);
			if ($models)
				echo CHtml::tag('option', array('value'=>''), '--请选择--');
			foreach($models as $model)
				echo CHtml::tag('option', array('value'=>$model->id),CHtml::encode($model->name));
			exit;
		}		
	}

	/**
	 * 创建 定级分类
	 */
	public function actionCreate()
	{
		$model = new Ad;
		
		$model->scenario = 'create';
		$this->_Ajax_Verify($model, 'ad-form');
		
		if (isset($_POST['Ad']))
		{
			$model->attributes = $_POST['Ad'];
			//定级栏目
			$model->p_id = 0;
			//定级栏目 自己的链接
			$model->link_type = 0;
			//没有链接
			$model->link = '';
			//上传图片
			$uploads = array('img');
			//获取图片
			$files = $this->upload($model, $uploads);
			//验证
			if($this->upload_error($model, $files, $uploads) && $model->save() && $this->log('创建' . $model::$_type[$model->type] . ' 广告专题', ManageLog::admin, ManageLog::create))
			{
				//保存图片
				$this->upload_save($model, $files);
				$this->back();
			}
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
		$model = $this->loadModel($id);
		
		$model->scenario = 'update';
		$this->_Ajax_Verify($model, 'ad-form');
	
		if (isset($_POST['Ad']))
		{
			//上传图片
			$uploads = array('img');
			//保存原来的
			$data = $this->upload_save_data($model, $uploads);
			//赋值
			$model->attributes = $_POST['Ad'];
			//获取图片
			$files = $this->upload($model, $uploads);
			//提前验证
			if($model->validate())
			{
				//没有上传的重新赋值
				$old_path = $this->upload_update_data($model, $data, $files);
				//保存
				if($model->save(false) && $this->log('更新' . $model::$_type[$model->type] . ' 广告专题', ManageLog::admin, ManageLog::update))
				{
					//保存新上传的
					$this->upload_save($model, $files, false);
					//删除原来的
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
	 * 更新排序
	 */
	public function actionSort()
	{
		if (isset($_POST['namename']) && is_array($_POST['namename']))
		{
			$model = new Ad;
			$model->scenario = 'sort';
			foreach ($_POST['namename'] as $id=>$sort)
			{
				$attributes = array('sort'=>$sort);
				$model->attributes = $attributes;
				$model->validate() && $model->updateByPk($id, $attributes, 'status=:status', array(':status'=>Ad::status_suc));
			}
		}
		$this->back();
	}
	
	/**
	 *管理页
	 */
	public function actionAdmin()
	{
		$this->ajaxType();
	
		$model=new Ad('search');
		$model->unsetAttributes();  // 删除默认属性
	
		if(isset($_GET['Ad']))
			$model->attributes=$_GET['Ad'];
	
		$this->render('admin',array(
				'model'=>$model,
		));
	}
	
	/**
	 * 获取广告的信息
	 */
	public function ajaxSet()
	{
		if (isset($_POST['type']))
		{
			$p_id_coment = '';
			$models = Ad::model()->findAll('status=:status AND p_id=0 AND type=:type AND (type=:banner OR type=:hot)', array(
					':banner'=>Ad::type_banner,
					':hot'=>Ad::type_hot,
					':type'=>$_POST['type'],
					':status'=>Ad::status_suc
			));
			if ($models)
				$p_id_coment .= CHtml::tag('option', array('value'=>''), '--请选择--');
			foreach($models as $model)
				$p_id_coment .= CHtml::tag('option', array('value'=>$model->id), CHtml::encode($model->name));
			$link_type_coment = '';
			$models = Type::model()->getTypeModels($_POST['type']);
			if ($models)
				$link_type_coment .= CHtml::tag('option', array('value'=>''), '--请选择--');
			foreach($models as $model)
				$link_type_coment .= CHtml::tag('option', array('value'=>$model->id),CHtml::encode($model->name));
			echo json_encode(array($p_id_coment, $link_type_coment));
			exit;
		}
	}
	
	/**
	 * 直接创建子类
	 */
	public function actionSet()
	{
		$model = new Ad;		
		$this->ajaxSet();		
		$model->scenario = 'set';
		$this->_Ajax_Verify($model, 'ad-form');
		
		$model->type = '';
		if (isset($_POST['Ad']))
		{
			//赋值
			$model->attributes = $_POST['Ad'];
			//上传图片
			$uploads = array('img');
			//获取图片
			$files = $this->upload($model, $uploads);
			//验证
			if($this->upload_error($model, $files, $uploads) && $model->save() && $this->log('创建' . $model::$_type[$model->type]. ' 广告', ManageLog::admin, ManageLog::create))
			{
				//保存图片
				$this->upload_save($model, $files);
				$this->back();
			}
		}
		
		$this->render('set',array(
				'model'=>$model,
		));
	}
	
	/**
	 * 创建 子分类
	 */
	public function actionAdd($id)
	{
		$model = new Ad;	
		// 加载专题栏目
		$model->Ad_Ad = $this->loadModel($id, 'status=:status AND p_id=0 AND (type=:banner OR type=:hot)', array(
				':banner'=>Ad::type_banner,
				':hot'=>Ad::type_hot,
				':status'=>Ad::status_suc
		));
		//父类ID
		$model->p_id = $model->Ad_Ad->id;
		//继承父类类型
		$model->type = $model->Ad_Ad->type;
		
		$model->scenario = 'add';
		$this->_Ajax_Verify($model, 'ad-form');
		
		if (isset($_POST['Ad']))
		{
			//赋值
			$model->attributes = $_POST['Ad'];
			//上传图片
			$uploads = array('img');
			//获取图片
			$files = $this->upload($model, $uploads);
			//验证
			if($this->upload_error($model, $files, $uploads) && $model->save() && $this->log('创建' . $model::$_type[$model->Ad_Ad->type]. ' 广告', ManageLog::admin, ManageLog::create))
			{
				//保存图片
				$this->upload_save($model, $files);
				$this->back();
			}
		}
		
		$this->render('add',array(
				'model'=>$model,
		));
	}

	/**
	 * 更新
	 * @param integer $id
	 */
	public function actionModify($id)
	{
		$model = $this->loadModel($id, array(
			'with'=>array('Ad_Ad'),
		));
		
		$model->scenario = 'modify';
		$this->_Ajax_Verify($model, 'ad-form');
	
		if (isset($_POST['Ad']))
		{
			//上传图片
			$uploads = array('img');
			//保存原来的
			$data = $this->upload_save_data($model, $uploads);
			//赋值
			$model->attributes = $_POST['Ad'];
			//获取图片
			$files = $this->upload($model, $uploads);
			//提前验证
			if($model->validate())
			{
				//没有上传的重新赋值
				$old_path = $this->upload_update_data($model, $data, $files);
				//保存
				if($model->save(false) && $this->log('更新' . $model::$_type[$model->Ad_Ad->type] . '广告',ManageLog::admin,ManageLog::update))
				{
					//保存新上传的
					$this->upload_save($model, $files, false);
					//删除原来的
					$this->upload_delete($old_path);
					$this->back();
				}
			}
		}
	
		$this->render('modify',array(
				'model'=>$model,
		));
	}
	
	/**
	 * 删除
	 * @param integer $id
	 */
	public function actionDelete($id)
	{
		if($this->loadModel($id,'`status`=:status',array(':status'=>Ad::status_dis))->updateByPk($id,array('status'=>Ad::status_del)))
			$this->log('删除广告/专题',ManageLog::admin,ManageLog::delete);
			
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
	}
	
	/**
	 * 还原
	 * @param integer $id
	 */
	public function actionRestore($id)
	{
		if($this->loadModel($id,'`status`=:status', array(':status'=>Ad::status_del))->updateByPk($id,array('status'=>Ad::status_dis)))
			$this->log('还原广告/专题',ManageLog::admin,ManageLog::update);
			
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
	}

	/**
	 * 垃圾回收页
	 */
	public function actionIndex()
	{		
		$criteria = new CDbCriteria;
		$criteria->with = array(
				'Ad_Admin',
				'Ad_Type',
				'Ad_Ad',
		);
		$criteria->addColumnCondition(array('t.status'=>Ad::status_del));

		$model = new Ad;	
		$this->render('index',array(
			'model'=>$model->search($criteria),
		));
	}
	
	/**
	 *管理页
	 */
	public function actionManage($id='')
	{
		$this->ajaxType();
		$model = new Ad('search');
		// 删除默认属性
		$model->unsetAttributes(); 
	
		if (isset($_GET['Ad']))
			$model->attributes = $_GET['Ad'];
		if ($id != '')
		{
			$model->Ad_Ad = Ad::model()->findByPk($id, 'p_id=0 AND (type=:banner OR type=:hot)', array(
				':banner'=>Ad::type_banner,
				':hot'=>Ad::type_hot,
			));
			$model->p_id = '=' . $id;
		}
		$this->render('manage',array(
				'model'=>$model,
		));
	}
	
	/**
	 * 禁用
	 * @param integer $id
	 */
	public function actionDisable($id)
	{
		if ($this->loadModel($id,'`status`=:status', array(':status'=>Ad::status_suc))->updateByPk($id,array('status'=>Ad::status_dis)))
			$this->log('禁用广告/专题',ManageLog::admin,ManageLog::update);	
		
		if (!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));				
	}
	
	/**
	 * 激活
	 * @param integer $id
	 */
	public function actionStart($id)
	{
		if($this->loadModel($id,'`status`=:status', array(':status'=>Ad::status_dis))->updateByPk($id,array('status'=>Ad::status_suc)))
	 		$this->log('激活广告/专题',ManageLog::admin,ManageLog::update);
	 		
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));			
	}
}
