<?php
/**
 *
 * @author Changhai Zhan
 *	创建时间：2015-09-07 17:27:05 */
class Agent_eatController extends AgentController
{
	/**
	 * 默认操作数据模型
	 * @var string
	 */
	public $_class_model='Eat';
	
	/**
	 * 创建第二部
	 * @param unknown $id
	 */
	public function actionCreate($id)
	{
		$this->addCss(Yii::app()->request->baseUrl . '/css/agent/dist/css/bootstrap.min.css');
		$this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/normalize.css');
		$this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/step.css');
		$this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/form.css');
		$this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/box.css');
		$this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/style.css');
		$this->addCss(Yii::app()->request->baseUrl.'/css/admin/ext/uploadify/uploadify.css');
		$this->addJs(Yii::app()->request->baseUrl.'/css/admin/ext/uploadify/jquery.uploadify.min.js');
		 
		$model= new Eat;
		$model->Eat_Items=new Items;
		$model->Eat_ItemsImg= new ItemsImg;

		$model->Eat_Items->scenario='agent_create_eat';
		if(isset($_POST['Fare']) && is_array($_POST['Fare']))
		{
			$number=count($_POST['Fare']);
			if($number > Yii::app()->params['items_fare_number'])
				$number=Yii::app()->params['items_fare_number'];	
			$model->Eat_Fare=$this->new_modes('Fare', 'create_eat',$number);
		}else
			$model->Eat_Fare=$this->new_modes('Fare','create_eat');	
		//ajax 验证
		$this->_Ajax_Verify_Same(array_merge($model->Eat_Fare,array($model->Eat_Items)),'eat-form');

		$this->_class_model='StoreUser';
		$model->Eat_Items->Items_Store_Manager=$this->loadModel($id,array(
			'condition'=>'`status`=1 AND `agent_id`=:agent_id',
			'params'=>array(':agent_id'=>Yii::app()->agent->id),
		));
		//获取项目类型
		$this->_class_model='Eat';
		$model->Eat_Items->c_id=ItemsClassliy::getClass()->id;//吃
		
		if(isset($_POST['Items']) && isset($_POST['Fare']) && is_array($_POST['Fare']) && count($_POST['Fare'])==count($model->Eat_Fare))
		{
			$model->Eat_Items->attributes=$_POST['Items'];
			//赋值
			$model->Eat_Items->agent_id=Yii::app()->agent->id;
			if($model->Eat_Items->Items_Store_Manager->p_id==0)
				$model->Eat_Items->store_id=$model->Eat_Items->Items_Store_Manager->id;
			else 
				$model->Eat_Items->store_id=$model->Eat_Items->Items_Store_Manager->p_id;
			$model->Eat_Items->manager_id=$model->Eat_Items->Items_Store_Manager->id;		
			//上传图片
			$this->_upload=Yii::app()->params['uploads_items_map'];
			$uploads=	array('map');
			$files=$this->upload($model->Eat_Items,$uploads);
			//图片验证
			$Items_img_validate=$this->upload_error($model->Eat_Items, $files, $uploads);
			if($Items_img_validate)
				$Items_validate=$model->Eat_Items->validate();	//项目字段验证
			else
				$Items_validate=false;		
			//项目价格验证
			$Fare_validate=$this->models_validate($model->Eat_Fare);
			
			if(isset($_POST['ItemsImg']['tmp']) && is_array($_POST['ItemsImg']['tmp']))
			{
				//验证图片
				$this->_upload=Yii::app()->params['uploads_items_tmp_eat'];
				$filename=$this->_upload.date('Ymd').'/'.current($_POST['ItemsImg']['tmp']);
				if(! file_exists($filename))
				{
					$model->Eat_ItemsImg('tmp', '概况图 不可空白');
					$img_validate=false;
				}elseif(count($_POST['ItemsImg']['tmp'])>Yii::app()->params['items_image_number']){
					$model->Eat_ItemsImg->addError('tmp', '概况图 不可超过'.Yii::app()->params['items_image_number'].'张');
					$img_validate=false;
				}else 
					$img_validate=true;
			}else{
				$model->Eat_ItemsImg->addError('tmp', '概况图 不可空白');
				$img_validate=false;
			}
	
			//提前验证都通过
			if($Fare_validate && $Items_validate && $Items_img_validate && $img_validate)
			{
				$transaction=$model->dbConnection->beginTransaction();
				try
				{
					$model->Eat_Items->status=items::status_offline;//创建的项目是下线状态
					$model->Eat_Items->audit=items::audit_draft;//创建的默认未提交
					//处理图片链接
					$model->Eat_Items->content = $this->admin_img_replace($model->Eat_Items->content);
					if($model->Eat_Items->save(false))//保存项目主要表
					{
						$model->id=$model->Eat_Items->id;
						$model->c_id=$model->Eat_Items->c_id;
						if(!$model->save(false))						//保存项目附表
							throw new Exception("添加项目(吃)记录错误");
						else{
							//价格
							foreach ($model->Eat_Fare as $model_fare)
							{
								$model_fare->store_id=$model->Eat_Items->store_id;
								$model_fare->agent_id=Yii::app()->agent->id;
								$model_fare->item_id=$model->Eat_Items->id;
								$model_fare->c_id=$model->Eat_Items->c_id;
								if(! $model_fare->save(false))
									throw new Exception("添加项目(吃)价格记录错误");				
							}
							//图片
							foreach ($_POST['ItemsImg']['tmp'] as $name)
							{
								//保存上传的项目图片多张
								$this->_upload=Yii::app()->params['uploads_items_tmp_eat'];
								$filename=$this->_upload.date('Ymd').'/'.$name;
								if(file_exists($filename))
								{
									$this->_upload=Yii::app()->params['uploads_items_eat'];
									if(!$this->items_img_save($model->Eat_Items,$filename))//保存图片
										throw new Exception("添加项目(吃)图片记录错误");
								}
							}
							$this->upload_save($model->Eat_Items,$files);
							$return =$this->log('添加项目(吃)',ManageLog::agent,ManageLog::create);
						}
					}else
						throw new Exception("添加项目(吃)主要记录错误");
					$transaction->commit();
				}
				catch(Exception $e)
				{
					$transaction->rollBack();
					$this->error_log($e->getMessage(),ErrorLog::agent,ErrorLog::create,ErrorLog::rollback,__METHOD__);
				}
				if(isset($return))
					$this->redirect(array('/agent/agent_items/create_3','id'=>$model->id));
			}
		}
		
		$this->render('create',array(
				'model'=>$model,
		));
	}
	
	/**
	 * 更新项目吃
	 */
	public function actionUpdate($id)
	{	
		$this->addCss(Yii::app()->request->baseUrl . '/css/agent/dist/css/bootstrap.min.css');
		$this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/normalize.css');
		$this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/step.css');
		$this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/form.css');
		$this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/box.css');
		$this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/style.css');
		$this->addCss(Yii::app()->request->baseUrl.'/css/admin/ext/uploadify/uploadify.css');
		$this->addJs(Yii::app()->request->baseUrl.'/css/admin/ext/uploadify/jquery.uploadify.min.js');
		 
		$model=$this->loadModel($id,array(
			'with'=>array(
					'Eat_Items'=>array(
						'with'=>array(								
							'Items_StoreContent'=>array('with'=>'Content_Store'),
						)
					),
					'Eat_ItemsImg',
					'Eat_Fare',
			),
			'condition'=>'`Content_Store`.status=1 AND `Eat_Items`.`status`=0 AND `t`.`c_id`=:c_id AND `Eat_Items`.`audit` !=:audit AND `Eat_Items`.`agent_id`=:agent_id',
			'params'=>array(':c_id'=>ItemsClassliy::getClass()->id,':audit'=>Items::audit_pending,':agent_id'=>Yii::app()->agent->id),
		));
		
		$fare_count=count($model->Eat_Fare);
		$fare_ids=$this->listData($model->Eat_Fare, 'id');
		$items_img_ids=$this->listData($model->Eat_ItemsImg, 'id');
		
		if(isset($_POST['Fare']) && is_array($_POST['Fare']))
		{
			$number=count($_POST['Fare']);
			if($number > Yii::app()->params['items_fare_number'])
				$number=Yii::app()->params['items_fare_number'];
			//更新models
			$model->Eat_Fare=$this->update_models($model->Eat_Fare, $number, 'update_eat');
		}
		$this->set_scenarios($model->Eat_Fare, 'update_eat');
		$model->Eat_Items->scenario='agent_update_eat';
		
		//ajax 验证
		$this->_Ajax_Verify_Same(array_merge($model->Eat_Fare,array($model->Eat_Items)),'eat-form');
		//获取项目类型
		$this->_class_model='Eat';
		$model->Eat_Items->c_id=ItemsClassliy::getClass()->id;//吃
		
		if(isset($_POST['Items']) && isset($_POST['Fare']) && is_array($_POST['Fare'])  && count($_POST['Fare'])==count($model->Eat_Fare))
		{
			//上传图片
			$this->_upload=Yii::app()->params['uploads_items_map'];
			$uploads=array('map');
			$data=$this->upload_save_data($model->Eat_Items, $uploads);//保存原来的
			
			$files=$this->upload($model->Eat_Items,$uploads);
		
			$model->Eat_Items->attributes=$_POST['Items'];
			//项目字段验证
			$Items_validate=$model->Eat_Items->validate();
			$old_path=$this->upload_update_data($model->Eat_Items, $data, $files);//还原原来的值
			//项目价格验证
			$Fare_validate=$this->models_validate($model->Eat_Fare);
				
			//获取图片id
			$img_ids=$this->array_listData($_POST['ItemsImg'], 'id');
			if(!empty($img_ids))
				$img_ids=ItemsImg::filter_id($model->id,$img_ids);//过滤 id 剩下的id
			$img_path_array=ItemsImg::filter_id($model->id,'',false,array('id'=>'img'));//获取所有的返回属性组成的数组
			if(!empty($model->Eat_ItemsImg) && isset($model->Eat_ItemsImg[0]))
				$Eat_ItemsImg=$model->Eat_ItemsImg[0];
			else{
				$model->Eat_ItemsImg=array(new ItemsImg);
				$Eat_ItemsImg=$model->Eat_ItemsImg[0];
			}
			if(isset($_POST['ItemsImg']['tmp']) && is_array($_POST['ItemsImg']['tmp']))
			{	
				if((count($img_ids)+count($_POST['ItemsImg']['tmp']))>Yii::app()->params['items_image_number'])
				{
					$Eat_ItemsImg->addError('tmp', '概况图 不可超过'.Yii::app()->params['items_image_number'].'张');
					$items_img_validate=false;//false;
				}elseif((count($img_ids)+count($_POST['ItemsImg']['tmp']))==0){
					$Eat_ItemsImg->addError('tmp', '概况图 不可空白');
					$items_img_validate=false;//false;
				}else 
					$items_img_validate=true;
			}elseif(count($img_ids)>Yii::app()->params['items_image_number']){
				$Eat_ItemsImg->addError('tmp', '概况图 不可超过'.Yii::app()->params['items_image_number'].'张');
				$items_img_validate=false;//false;
			}elseif(count($img_ids)==0){
				$Eat_ItemsImg->addError('tmp', '概况图 不可空白');	
				$items_img_validate=false;//false;
			}else 
				$items_img_validate=true;

			//提前验证都通过
			if($Fare_validate && $Items_validate && $items_img_validate)
			{
				$transaction=$model->dbConnection->beginTransaction();
				try
				{
					$model->Eat_Items->audit=Items::audit_draft;//修改未提交
					//处理图片链接
					$model->Eat_Items->content = $this->admin_img_replace($model->Eat_Items->content);
					if($model->Eat_Items->save(false))//保存项目主要表
					{		
						foreach ($model->Eat_Fare as $model_fare)
						{
							$model_fare->store_id=$model->Eat_Items->store_id;
							$model_fare->agent_id=Yii::app()->agent->id;
							$model_fare->item_id=$model->id;
							$model_fare->c_id=$model->Eat_Items->c_id;
							if(! $model_fare->save(false))
								throw new Exception("添加项目(吃)价格记录错误");	
							$fare_count--;
						}
						if($fare_count >0)
						{
							rsort($fare_ids);
							for($j=0;$j<$fare_count;$j++)
							{
								Fare::model()->deleteByPk($fare_ids[$j]);
							}
						}
						if(isset($_POST['ItemsImg']['tmp']) && is_array($_POST['ItemsImg']['tmp']))
						{
							foreach ($_POST['ItemsImg']['tmp'] as $name)
							{
								//保存上传的项目图片多张
								$this->_upload=Yii::app()->params['uploads_items_tmp_eat'];
								$filename=$this->_upload.date('Ymd').'/'.$name;
								if(file_exists($filename))
								{
									$this->_upload=Yii::app()->params['uploads_items_eat'];
									if(!$this->items_img_save($model->Eat_Items,$filename))//保存图片
										throw new Exception("修改项目(吃)图片记录错误");
								}
							}
						}
						foreach ($items_img_ids as $items_img_id)
						{
							if(!in_array($items_img_id, $img_ids))
							{
								ItemsImg::model()->deleteByPk($items_img_id);
								$this->upload_delete($img_path_array[$items_img_id]);
							}
						}
						$this->upload_save($model->Eat_Items,$files);
						$this->upload_delete($old_path);//删除原来的
						$return =$this->log('修改项目(吃)',ManageLog::agent,ManageLog::update);
					}else
						throw new Exception("修改项目(吃)主要记录错误");
					$transaction->commit();
				}
				catch(Exception $e)
				{
					$transaction->rollBack();
					$this->error_log($e->getMessage(),ErrorLog::agent,ErrorLog::update,ErrorLog::rollback,__METHOD__);
				}
				if(isset($return))
					$this->redirect(array('/agent/agent_items/create_3','id'=>$model->id));
			}	
		}
		
		$this->render('update',array(
				'model'=>$model,
		));
	}
	
	/**
	 * 上传图成功 删除
	 */
	public function actionUploads()
	{
		$this->_upload=Yii::app()->params['uploads_items_tmp_eat'];
		if(isset($_POST['file_name'])){
			$filename=$this->_upload.date('Ymd').'/'.$_POST['file_name'];
			if(file_exists($filename))
				echo unlink($filename);
			else
				echo 0;
			Yii::app()->end();
		}
		$model=new ItemsImg;
		$model->scenario='uploads';
		$uploads=array('tmp');
		if($this->upload_images($model,$uploads,true))
			echo json_encode(array('img_name'=>basename($model->tmp),'litimg'=>$this->litimg_path($model->tmp, Yii::app()->params['litimg_pc'])));
		else
			echo json_encode(array('img_name'=>'none'));
		Yii::app()->end();
	}

	/**
	 * 查看项目（吃）详情
	 * @param $id
	 * @throws CHttpException
	 */
	public function actionView($id)
	{
		$this->addCss(Yii::app()->request->baseUrl.'/css/agent/dist/css/bootstrap.min.css');
		$this->addCss(Yii::app()->request->baseUrl.'/css/agent/css/form.css');
		$this->addCss(Yii::app()->request->baseUrl.'/css/agent/css/style.css');

		$model = $this->loadModel($id,array(
			'with'=>array(
				'Eat_Items'=>array(
					'with'=>array(
						'Items_agent',
						'Items_StoreContent'=>array('with'=>array('Content_Store')),
						'Items_Store_Manager',
						'Items_area_id_p_Area_id'=>array('select'=>'name'),
						'Items_area_id_m_Area_id'=>array('select'=>'name'),
						'Items_area_id_c_Area_id'=>array('select'=>'name'),
					)),
				'Eat_ItemsClassliy',
				'Eat_ItemsWifi'=>array('with'=>array('ItemsWifi_Wifi')),
				'Eat_Fare',
				'Eat_ItemsImg',
			),
			// 查看自己 除删除  查看别人 (上线，审核通过)
			'condition' => '(`Eat_Items`.`status`>=0 AND `Eat_Items`.`agent_id`=:agent_id) OR (`Eat_Items`.`status`=1 AND `Eat_Items`.`audit`=:audit AND `Eat_Items`.`agent_id`!=:agent_id)',
			'params'=>array(
					':agent_id'=>Yii::app()->agent->id,
					':audit'=>Items::audit_pass,
			),
		));
		// 标签
		$model->Eat_TagsElement = TagsElement::get_select_tags(TagsElement::tags_items_eat,$id);

		$this->render('view',array('model'=>$model,));
	}


}