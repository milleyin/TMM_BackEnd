<?php
/**
 * 
 * @author Changhai Zhan
 *	创建时间：2015-09-29 05:46:05 */
class Tmm_softwareController extends MainController
{
	/**
	 * 默认操作数据模型
	 * @var string
	 */
	public $_class_model='Software';

	/**
	 * 创建
	 */
	public function actionCreate()
	{
		$model=new Software;
	
		$model->scenario='create';
		$this->_Ajax_Verify($model,'software-form');
		
		if(isset($_POST['Software']))
		{
			$model->attributes=$_POST['Software'];
			if(!! $file=CUploadedFile::getInstance($model,'file_path'))
			{			
				$upload=Yii::app()->params['uploads_software'].$model->type . '/' . $model->use . '/';
				$model->file_path=$upload.$model->version.'.'.$file->extensionName;
				$model->dow_url=$model->version.'.'.$file->extensionName;
				$_use = array('ZIP', 'APK', 'IPA', 'APK');
				if(strtoupper($file->extensionName) == $_use[$model->use])
				{
					$model->status=Software::status_update_not;//禁止更新
					if($model->save() && $this->log('创建app段软件更新包',ManageLog::admin,ManageLog::create))
					{
						if(!is_dir($upload))
							mkdir($upload, 0777, true);
						$file->saveAs($model->file_path);
						$this->back();
					}
				}else 
					$model->addError('use', '软件格式 不匹配');
			}else 
				$model->addError('file_path', '上传软件 不可空白');
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}
	
	/**
	 * 查询接口返回json
	 */
	public function actionQuery()
	{
		$query=array(
			'user'=>Yii::app()->request->getQuery('user'),
			'store'=>Yii::app()->request->getQuery('store'),
			'zip'=>Yii::app()->request->getQuery('zip'),
			'apk'=>Yii::app()->request->getQuery('apk'),
			'ios'=>Yii::app()->request->getQuery('ios'),
			'pad'=>Yii::app()->request->getQuery('pad'),
		    'dpr'=>Yii::app()->request->getQuery('dpr'),
		);
		$criteria=new CDbCriteria;
		$criteria->addColumnCondition(array(
			'status'=>Software::status_update_yes,
		));
		$criteria->order='version desc';
		if($query['user']=='user')
		{
			if($query['ios']=='ios')
			{
				if($query['zip']=='zip')
				{
					$criteria->addColumnCondition(array(
							'type'=>Software::type_user_ios,
							'`use`'=>Software::use_zip,
					));
					$this->get_model($criteria);
				}
				elseif($query['apk']=='apk')
				{
					$criteria->addColumnCondition(array(
							'type'=>Software::type_user_ios,
							'`use`'=>Software::use_ios,
					));
					$this->get_model($criteria);			
				}
			}
			else
			{
				if($query['zip']=='zip')
				{
					$criteria->addColumnCondition(array(
							'type'=>Software::type_user,
							'`use`'=>Software::use_zip,
					));
					$this->get_model($criteria);
				}
				elseif($query['apk']=='apk')
				{
					$criteria->addColumnCondition(array(
							'type'=>Software::type_user,
							'`use`'=>Software::use_apk,
					));
					$this->get_model($criteria);
				}
			}
		}
		elseif($query['store']=='store')
		{
			if($query['ios']=='ios')
			{
				if($query['zip']=='zip')
				{
					$criteria->addColumnCondition(array(
							'type'=>Software::type_store_ios,
							'`use`'=>Software::use_zip,
					));
					$this->get_model($criteria);
				}
				elseif($query['apk']=='apk')
				{
					$criteria->addColumnCondition(array(
							'type'=>Software::type_store_ios,
							'`use`'=>Software::use_ios,
					));
					$this->get_model($criteria);			
				}
			}
			else
			{
				if($query['zip']=='zip')
				{
					$criteria->addColumnCondition(array(
							'type'=>Software::type_store,
							'`use`'=>Software::use_zip,
					));
					$this->get_model($criteria);
				}
				elseif($query['apk']=='apk')
				{
					$criteria->addColumnCondition(array(
							'type'=>Software::type_store,
							'`use`'=>Software::use_apk,
					));
					$this->get_model($criteria);
				}
			}
		}
		elseif($query['pad']=='pad')
		{
			if($query['zip']=='zip')
			{
				$criteria->addColumnCondition(array(
						'type'=>Software::type_pad,
						'`use`'=>Software::use_zip,
				));
				$this->get_model($criteria);
			}
			elseif($query['apk']=='apk')
			{
				$criteria->addColumnCondition(array(
						'type'=>Software::type_pad,
						'`use`'=>Software::use_apk,
				));
				$this->get_model($criteria);
			} elseif ($query['dpr']=='dpr') {
			    $criteria->addColumnCondition(array(
			        'type'=>Software::type_pad,
			        '`use`'=>Software::use_dpr,
			    ));
			    $this->get_model($criteria);
			}
		}
		$this->json_end();
	}
	
	/**
	 * 加载数据
	 * @param unknown $criteria
	 * @return Ambigous <static, mixed, NULL, multitype:static , multitype:unknown Ambigous <static, NULL> , multitype:, multitype:unknown >
	 */
	public function get_model($criteria)
	{
		$model=Software::model()->find($criteria);
		if($model)
		{
			$return=array();
			$return['version']=$model->version;
			$return['down_url']=Yii::app()->request->hostInfo.Yii::app()->createUrl('/admin/tmm_software/download',array('id'=>$model->id));
			$this->json_end($return);
		}else
			$this->json_end();
	}
	
	/**
	 * json 输出
	 * @param unknown $return
	 */
	public function json_end($return=array())
	{
		header("Content-type:application/json;");
		echo json_encode($return);
		Yii::app()->end();
	}
	
	/**
	 * 下载
	 * @param unknown $id
	 */
	public function actionDownload($id)
	{
		$model=$this->loadModel($id);
		if($this->file_exists_uploads($model->file_path))
		{
			Yii::app()->request->sendFile($model->version_name.$model->dow_url,$model->file_path,null,false,true);
			Software::model()->updateByPk($id, array(
				'dow_count'=>new CDbExpression('`dow_count`+1'),
			));
		}
	}
	
	/**
	 *管理页
	 */
	public function actionAdmin()
	{
		$model=new Software('search');
		$model->unsetAttributes();  // 删除默认属性
		if(isset($_GET['Software']))
			$model->attributes=$_GET['Software'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}
	
	/**
	 * 禁止更新
	 * @param integer $id
	 */
	public function actionDisable($id)
	{
		if($this->loadModel($id,'`status`=1')->updateByPk($id,array('status'=>0)))
			$this->log('禁止软件更新',ManageLog::admin,ManageLog::update);			
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
	}
	
	/**
	 * 开启更新
	 * @param integer $id
	 */
	public function actionStart($id)
	{
		if($this->loadModel($id,'`status`=0')->updateByPk($id,array('status'=>1)))
	 		$this->log('开启软件更新',ManageLog::admin,ManageLog::update);
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
	}
}
