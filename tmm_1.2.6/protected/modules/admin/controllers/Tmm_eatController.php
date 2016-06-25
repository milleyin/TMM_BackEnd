<?php
/**
 * 
 * @author Changhai Zhan
 *	创建时间：2015-08-05 14:41:55 */
class Tmm_eatController extends MainController
{
	/**
	 * 默认操作数据模型
	 * @var string
	 */
	public $_class_model='Eat';

	/**
	 * 查看详情
	 * @param integer $id
	 */
	public function actionView($id)
	{
		$model=$this->loadModel($id,array('with'=>array(
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
			'Eat_Fare',
			'Eat_ItemsImg',
		),
	));

		$model->Eat_TagsElement=TagsElement::get_select_tags(TagsElement::tags_items_eat,$id);

		$this->render('view',array('model'=>$model,));
	}

	/**
	 * 创建项目（吃）
	 * @param $id
	 * @throws CHttpException
	 */
	public function actionCreate($id)
	{
        $this->addCss(Yii::app()->request->baseUrl.'/css/admin/ext/uploadify/uploadify.css');
        $this->addJs(Yii::app()->request->baseUrl.'/css/admin/ext/uploadify/jquery.uploadify.min.js');
        //实例化类
	    $model=new Eat;
        $model->Eat_Items=new Items; 
        $model->Eat_ItemsImg= new ItemsImg;

         //创建添加时验证
        $model->Eat_Items->scenario='create';

		if((isset($_POST['add']) || isset($_POST['cut'])) && isset($_POST['Items']) && isset($_POST['Fare']) && is_array($_POST['Fare']))
		{
			if(isset($_POST['Items']['free_status']) && $_POST['Items']['free_status'] != Items::free_status_yes)
			{
				$number=$this->set_number('Fare', Yii::app()->params['items_fare_number']);
				//创建多个model
				$model->Eat_Fare=$this->new_modes('Fare', 'create_eat',$number);
				$this->set_attributes($model->Eat_Fare);
			}
			else
			{
				$default=array('name' =>'免费套餐','price' =>'0.00');
				$model->Eat_Fare=$this->new_modes('Fare', 'create_eat',count(Fare::$__info));
				$array=array();
				foreach ($model->Eat_Fare as $key_free=>$info)
				{
					$default['info']=Fare::$__info[$key_free==0 ? 1 : 0];
					$info->attributes=$default;
					$array[]=$info;
				}
				$model->Eat_Fare=$array;
			}
			//赋值
			$model->Eat_Items->attributes=$_POST['Items'];
			
		}
		elseif(isset($_POST['Fare']) && is_array($_POST['Fare']))
		{
			if(isset($_POST['Items']['free_status']) && $_POST['Items']['free_status'] != Items::free_status_yes)
			{
				$number=count($_POST['Fare']);
				if($number > Yii::app()->params['items_fare_number'])
					$number=Yii::app()->params['items_fare_number'];
			
				$model->Eat_Fare=$this->new_modes('Fare', 'create_eat',$number);
			}
			else
			{
				$default=array('name' =>'免费套餐','price' =>'0.00');
				$model->Eat_Fare=$this->new_modes('Fare', 'create_eat',count(Fare::$__info));
				$array=array();
				foreach ($model->Eat_Fare as $key_free=>$info)
				{
					$default['info']=Fare::$__info[$key_free==0 ? 1 : 0];
					$info->attributes=$default;
					$array[]=$info;
				}
				$model->Eat_Fare=$array;
			}
		}else
			$model->Eat_Fare=$this->new_modes('Fare','create_eat');

        //供应商主账号
        $this->_class_model='StoreContent';
        $model->Eat_Items->Items_StoreContent=$this->loadModel($id,array(
            'condition'=>'Store_Agent.status=1',
            'with'=>array(
                'Content_Store'=>array('with'=>array('Store_Agent')),
                    'Content_Stoer_Son'
                ),
        ));
		//设置归属门店
        $model->Eat_Items->store_id=$model->Eat_Items->Items_StoreContent->id;
        //ajax 验证
        $this->_Ajax_Verify_Same(array_merge($model->Eat_Fare,array($model->Eat_Items)),'eat-form');

        if(!isset($_POST['add']) && !isset($_POST['cut']) && isset($_POST['Items']) && isset($_POST['Fare']) && is_array($_POST['Fare']) && count($_POST['Fare'])==count($model->Eat_Fare))
        {
       
            $model->Eat_Items->attributes=$_POST['Items'];
            //获取项目类型
            $this->_class_model='Eat';
            $model->Eat_Items->c_id=ItemsClassliy::getClass()->id;//吃

            $model->Eat_Items->agent_id=$model->Eat_Items->Items_StoreContent->Content_Store->agent_id;
            $model->Eat_Items->store_id=$model->Eat_Items->Items_StoreContent->id;
	
            $Items_validate=$model->Eat_Items->validate();//项目字段验证
            //项目价格验证
			$Fare_validate=$this->models_validate($model->Eat_Fare);
		   //提前验证都通过
		   if($Fare_validate && $Items_validate)
		   {
					$transaction=$model->dbConnection->beginTransaction();
					try
					{
						$model->Eat_Items->status=0;//创建的项目是下线状态
						$model->Eat_Items->audit=items::audit_draft;//创建的默认未提交
						//处理图片链接
						$model->Eat_Items->content = $this->admin_img_replace($model->Eat_Items->content);
						//项目图片地址
						$this->_upload=Yii::app()->params['uploads_items_map'];
						$model->Eat_Items->map=$this->getFilePath().'.png';
											
						if($model->Eat_Items->save(false))//保存项目主要表
						{
							//保存图片
							Items::saveAmapImage($model->Eat_Items->map, $model->Eat_Items->lng, $model->Eat_Items->lat);
			
							$model->id=$model->Eat_Items->id;
							$model->c_id=$model->Eat_Items->c_id;

							if(!$model->save(false))
								throw new Exception("添加项目(吃)记录错误");
							else{
								foreach ($model->Eat_Fare as $model_fare)
								{
									$model_fare->store_id=$model->Eat_Items->store_id;
									$model_fare->agent_id=$model->Eat_Items->agent_id;
									$model_fare->item_id=$model->Eat_Items->id;
									$model_fare->c_id=$model->Eat_Items->c_id;
									if(! $model_fare->save(false))
									{
										$Fare_is_error=true;
										throw new Exception("添加项目(吃)价格记录错误");
										break;
									}
								}
								if(!isset($Fare_is_error) && isset($_POST['ItemsImg']['tmp']) && is_array($_POST['ItemsImg']['tmp']))
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
												{
													$img_is_error=true;
													throw new Exception("添加项目(吃)图片记录错误");
													break;
												}else
													$img_is_error=false;
											}
										}
								}
								if(!isset($Fare_is_error) && ((isset($img_is_error) && $img_is_error==false) || !isset($img_is_error)))
								{									
										$return =$this->log('添加项目(吃)',ManageLog::admin,ManageLog::create);
								}else
									throw new Exception("添加项目(吃)中出现记录错误");
							}
						}else
							throw new Exception("添加项目(吃)主要记录错误");

						$transaction->commit();
					}
					catch(Exception $e)
					{
						$transaction->rollBack();
						$this->error_log($e->getMessage(),ErrorLog::admin,ErrorLog::create,ErrorLog::rollback,__METHOD__);
					}
					if(isset($return))
						$this->back();
				}
        }

        $this->render('create',array(
            'model'=>$model,
        ));

	}
	
   /**
     * 上传图成功 删除
     */
    public function actionUploads()
    {
        $this->_upload=Yii::app()->params['uploads_items_tmp_eat'];
        if(isset($_POST['file_name']))
        {
            $filename=$this->_upload.date('Ymd').'/'.$_POST['file_name'];
            if(file_exists($filename))
                echo unlink($filename);
            else
                echo 0;
            die;
        }
        $model=new ItemsImg;
        $model->scenario='uploads';
		$uploads=array('tmp');	
		$this->clear_tmp(Yii::app()->params['uploads_items_tmp_eat']);//清空图片缓存
		if($this->upload_images($model,$uploads,true))
			echo json_encode(array('img_name'=>basename($model->tmp),'litimg'=>$this->litimg_path($model->tmp, Yii::app()->params['litimg_pc'])));
		else 
            echo json_encode(array('img_name'=>'none'));
        die;
    }

	/**
	 * 更新
	 * @param integer $id
	 */
	public function actionUpdate($id)
	{
		$this->addCss(Yii::app()->request->baseUrl.'/css/admin/ext/uploadify/uploadify.css');
		$this->addJs(Yii::app()->request->baseUrl.'/css/admin/ext/uploadify/jquery.uploadify.min.js');
		
		$model=$this->loadModel($id,array(
			'condition'=>'Store_Agent.status=1 AND Eat_Items.status=0 AND t.c_id=:c_id AND Eat_Items.audit !=:audit',
			'with'=>array(
				'Eat_Items'=>array(
						'with'=>array(								
							'Items_StoreContent'=>array(
									'with'=>array(
										'Content_Store'=>array('with'=>'Store_Agent'),
										'Content_Stoer_Son',
									),
							)
						)),
				'Eat_ItemsImg',
				'Eat_Fare'
			),
			'params'=>array(':c_id'=>ItemsClassliy::getClass()->id,':audit'=>Items::audit_pending),
		));
		$fare_count=count($model->Eat_Fare);
		
		$fare_ids=$this->listData($model->Eat_Fare, 'id');
		$items_img_ids=$this->listData($model->Eat_ItemsImg, 'id');

		$this->set_scenarios($model->Eat_Fare, 'update_eat');		
		$model->Eat_Items->scenario='update';
		if((isset($_POST['add']) || isset($_POST['cut'])) && isset($_POST['Items']) && isset($_POST['Fare']) && is_array($_POST['Fare']))
		{			
			if(isset($_POST['Items']['free_status']) && $_POST['Items']['free_status'] != Items::free_status_yes)
			{
				//设置有多少个fare 	
				$number=$this->set_number('Fare', Yii::app()->params['items_fare_number']);	
				$array=array();
				$default=array('name' =>'', 'info' =>'', 'price' =>'');
				$count=count($model->Eat_Fare);
				$array=$this->set_attributes($model->Eat_Fare,$default,$number);//设置属性 保存数据
				if($number > $count )
				{
					$array=array_merge($array,$this->new_modes('Fare', 'update_eat',$number-$count));
					$array=$this->set_attributes($array,$default,$number);//设置属性 保存数据
				}
				$model->Eat_Fare=$array;
			}
			else
			{
				$default=array('name' =>'免费套餐','price' =>'0.00');		
				$model->Eat_Fare=$this->update_models($model->Eat_Fare, count(Fare::$__info), 'update_eat');		
				$array=array();
				foreach ($model->Eat_Fare as $key_free=>$info)
				{
					$default['info']=Fare::$__info[$key_free==0 ? 1 : 0];
					$info->attributes=$default;
					$array[]=$info;
				}
				$model->Eat_Fare=$array;
			}
			//保存项目的值
			$model->Eat_Items->attributes=$_POST['Items'];

			if(isset($_POST['ItemsImg']) && is_array($_POST['ItemsImg']))
			{
                $ids_img=$this->array_listData($_POST['ItemsImg'], 'id');
  				//过滤 id
				$models_img=ItemsImg::filter_id($model->id,$ids_img,false);
               	//赋值对象与数据
                $model->Eat_ItemsImg=$this->models_attributes($model->Eat_ItemsImg, $models_img, array('id','img'));
			}
		}
		elseif(isset($_POST['Fare']) && is_array($_POST['Fare']))
		{
			if(isset($_POST['Items']['free_status']) && $_POST['Items']['free_status'] != Items::free_status_yes)
			{
				$number=count($_POST['Fare']);
				if($number > Yii::app()->params['items_fare_number'])
					$number=Yii::app()->params['items_fare_number'];
				//更新models
				$model->Eat_Fare=$this->update_models($model->Eat_Fare, $number, 'update_eat');
			}
			else
			{
				$default=array('name' =>'免费套餐','price' =>'0.00');
				$model->Eat_Fare=$this->update_models($model->Eat_Fare, count(Fare::$__info), 'update_eat');	
				$array=array();
				foreach ($model->Eat_Fare as $key_free=>$info)
				{
					$default['info']=Fare::$__info[$key_free==0 ? 1 : 0];
					$info->attributes=$default;
					$array[]=$info;
				}
				$model->Eat_Fare=$array;
			}
		}
		
		$this->set_scenarios($model->Eat_Fare, 'update_eat');
		
		//ajax 验证
		$this->_Ajax_Verify_Same(array_merge($model->Eat_Fare,array($model->Eat_Items)),'eat-form');
		
		if(!isset($_POST['add']) && !isset($_POST['cut']) && isset($_POST['Items']) && isset($_POST['Fare']) && is_array($_POST['Fare']) && count($_POST['Fare'])==count($model->Eat_Fare))
		{
			//原来的图片
			$old_map=$model->Eat_Items->map;
			$old_lng=$model->Eat_Items->lng;
			$old_lat=$model->Eat_Items->lat;
			$model->Eat_Items->attributes=$_POST['Items'];//itmes	
			//项目字段验证
			$Items_validate=$model->Eat_Items->validate();				
			//项目价格验证
			$Fare_validate=$this->models_validate($model->Eat_Fare);
			//获取图片id	
			$img_ids=$this->array_listData($_POST['ItemsImg'], 'id');
			if(!empty($img_ids))
           		 $img_ids=ItemsImg::filter_id($model->id,$img_ids);//过滤 id 剩下的id        
            $img_path_array=ItemsImg::filter_id($model->id,'',false,array('id'=>'img'));//获取所有的返回属性组成的数组
			//提前验证都通过
			if($Fare_validate && $Items_validate){
				$transaction=$model->dbConnection->beginTransaction();
				try
				{
					$model->Eat_Items->audit=Items::audit_draft;//修改未提交
					//处理图片链接
					$model->Eat_Items->content = $this->admin_img_replace($model->Eat_Items->content);				
					//项目图片地址
					if((string)$old_lng != (string)$model->Eat_Items->lng || (string)$old_lat != (string)$model->Eat_Items->lat)
					{
						$this->_upload=Yii::app()->params['uploads_items_map'];
						$model->Eat_Items->map=$this->getFilePath().'.png';
					}else 
						$model->Eat_Items->map=$old_map;
					if($model->Eat_Items->save(false))//保存项目主要表
					{
						//保存图片
						if(((string)$old_lng != (string)$model->Eat_Items->lng || (string)$old_lat != (string)$model->Eat_Items->lat))
						{
							Items::saveAmapImage($model->Eat_Items->map, $model->Eat_Items->lng, $model->Eat_Items->lat);
							if(file_exists($old_map))
								unlink($old_map);
						}
							foreach ($model->Eat_Fare as $model_fare)
							{
								$model_fare->store_id=$model->Eat_Items->store_id;
								$model_fare->agent_id=$model->Eat_Items->agent_id;
								$model_fare->item_id=$model->Eat_Items->id;
								$model_fare->c_id=$model->Eat_Items->c_id;
								if(! $model_fare->save(false))
								{
									$Fare_is_error=true; 
									throw new Exception("添加项目(吃)价格记录错误");
									break;
								}	
								$fare_count--;
							}
							if($fare_count >0){
                                rsort($fare_ids);
								for($j=0;$j<$fare_count;$j++)
									Fare::model()->deleteByPk($fare_ids[$j]);			
							}
							if(!isset($Fare_is_error) && isset($_POST['ItemsImg']['tmp']) && is_array($_POST['ItemsImg']['tmp']))
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
										{
											$img_is_error=true;
											throw new Exception("添加项目(吃)图片记录错误");
											break;			
										}else 
											$img_is_error=false;							
									}
								}			
							}
							if(!isset($Fare_is_error) && ((isset($img_is_error) && $img_is_error==false) || !isset($img_is_error))){
								foreach ($items_img_ids as $items_img_id)
								{
										if(!in_array($items_img_id, $img_ids)) {
                                            ItemsImg::model()->deleteByPk($items_img_id);
                                           $this->upload_delete($img_path_array[$items_img_id]);
                                        }
								}
							}
							if(!isset($Fare_is_error) && ((isset($img_is_error) && $img_is_error==false) || !isset($img_is_error))){
								$return =$this->log('修改项目(吃)',ManageLog::admin,ManageLog::create);
							}else 
								throw new Exception("修改项目(吃)记录错误");	
					}else 
						throw new Exception("修改项目(吃)主要记录错误");
					$transaction->commit();
				}
				catch(Exception $e)
				{
					$transaction->rollBack();
					$this->error_log($e->getMessage(),ErrorLog::admin,ErrorLog::create,ErrorLog::rollback,__METHOD__);
				}	
				if(isset($return))	
					$this->back();
			}
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 *选择标签的显示
	 * @param unknown $id
	 */
	public function actionSelect($id){
		$model=new Tags('search');
		$model->unsetAttributes();  // 删除默认属性
		if(isset($_GET['Tags']))
			$model->attributes=$_GET['Tags'];
		
		$this->render('select',array(
				'model'=>$model,
				'select'=>$this->loadModel($id,array(
						'with'=>array('Eat_Items'),
						'condition'=>'Eat_Items.status=0 AND Eat_Items.audit !=:audit AND Eat_Items.c_id=:c_id',
						'params'=>array(':audit'=>Items::audit_pending,':c_id'=>ItemsClassliy::getClass()->id),
				)),
		));
	}

	/**
	 * 标签选中操作
	 * @param unknown $id
	 * @param string $type
	 */
	public function actionTags($id){
		if(isset($_POST['tag_ids']) && $_POST['tag_ids'] && isset($_POST['type']))
		{
			$type = $_POST['type'];
			if(!is_array($_POST['tag_ids']))
				$_POST['tag_ids']=array($_POST['tag_ids']);
			$model=$this->loadModel($id,array(
					'with'=>array('Eat_Items'),
					'condition'=>'Eat_Items.status=0 AND Eat_Items.audit !=:audit AND Eat_Items.c_id=:c_id',
					'params'=>array(':audit'=>Items::audit_pending,':c_id'=>ItemsClassliy::getClass()->id),
			));
			$tags_ids=Tags::filter_tags($_POST['tag_ids']);//安全过滤tags id	
			if($type=='yes'){
				//过滤之前有的
				$save_tags_id=TagsElement::not_select_tags_element($tags_ids,$id,TagsElement::tags_items_eat);							
				$return=TagsElement::select_tags_ids_save($save_tags_id, $id, TagsElement::tags_items_eat);
			}else
				$return=TagsElement::select_tags_ids_delete($tags_ids, $id, TagsElement::tags_items_eat);		
			if($return){
				if($type=='yes')
					$this->log('项目(吃)添加标签', ManageLog::admin,ManageLog::create);
				else
					$this->log('项目(吃)去除标签', ManageLog::admin,ManageLog::clear);
				echo 1;
			}else
				echo '操作过于频繁，请刷新页面从新选择！';
		}else
			echo '没有选中标签，请重新选择标签！';
	}
	
	/**
	 * 删除
	 * @param integer $id
	 */
	public function actionDelete($id)
	{
		//获取项目类型
		$c_id=ItemsClassliy::getClass()->id;//吃
		$this->_class_model='Items';
		if($this->loadModel($id,'`status`=0 AND `c_id`=:c_id',array(':c_id'=>$c_id))->updateByPk($id,array('status'=>-1)))
			$this->log('删除项目(吃)',ManageLog::admin,ManageLog::delete);
			
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
	}
	
	/**
	 * 还原
	 * @param integer $id
	 */
	public function actionRestore($id)
	{
		//获取项目类型
		$c_id=ItemsClassliy::getClass()->id;//吃
		$this->_class_model='Items';
		if($this->loadModel($id,'`status`=-1 AND `c_id`=:c_id',array(':c_id'=>$c_id))->updateByPk($id,array('status'=>0)))
			$this->log('还原项目(吃)',ManageLog::admin,ManageLog::update);
			
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
	}
	
	/**
	 * 审核通过
	 * @param integer $id
	 */
	public function actionPass($id){
		//改变布局
		$this->layout='/layouts/column_right_audit';
		//查看是否需要审核
		$model=$this->loadModel($id,array(
				'with'=>array('Eat_Items'=>array(
						'condition'=>'Eat_Items.status=0 AND Eat_Items.audit=:audit AND t.c_id=:c_id',
						'params'=>array(':audit'=>Items::audit_pending,':c_id'=>ItemsClassliy::getClass()->id),
				))
		));
		$model->Eat_Items->scenario='pass';
		$this->_Ajax_Verify($model->Eat_Items,'items-form');

		if(isset($_POST['Items']))
		{
			$model->Eat_Items->attributes=$_POST['Items'];
			$model->Eat_Items->pub_time=time();
			$model->Eat_Items->is_push=Items::push_init;//设置初始化
			$model->Eat_Items->audit=Items::audit_pass;// 审核通过
			$transaction=$model->dbConnection->beginTransaction();
			if($model->Eat_Items->validate()){
				try
				{
					if($model->Eat_Items->save(false)){
						$audit=new AuditLog;
						$audit->info=$model->Eat_Items->push.'%'.$model->Eat_Items->push_orgainzer.'%'.$model->Eat_Items->push_store.'%'.$model->Eat_Items->push_agent.'%';
						$audit->audit_who=AuditLog::admin;//记录 审核人 （审核人的id 在模型中）
						$audit->audit_element=AuditLog::items_eat;//记录 被审核的类型
						$audit->element_id=$model->id;//记录 被审核id
						$audit->audit=AuditLog::pass;//记录 审核通过
						if($audit->save(false))
							$return=$this->log('添加审核项目(吃)记录分成比例',ManageLog::admin,ManageLog::create);
						else
							throw new Exception("添加审核日志错误");
					}else
						throw new Exception("审核通过保存错误");
					$transaction->commit();
				}
				catch(Exception $e)
				{
					$transaction->rollBack();
					$this->error_log($e->getMessage(),ErrorLog::admin,ErrorLog::update,ErrorLog::rollback,__METHOD__);
				}
			}
			if(isset($return))
				$this->back();
		}
		$this->render('/tmm_items/_pass',array(
				'model'=>$model->Eat_Items,
		));
	}
	
	/**
	 * 审核不通过
	 * @param integer $id
	 */
	public function actionNopass($id){
		//改变布局
		$this->layout='/layouts/column_right_audit';
		$model=new AuditLog;
		
		$model->scenario='create';
		//查看是否需要审核
		$model->Audit_Eat=$this->loadModel($id,array(
				'with'=>array('Eat_Items'=>array(
						'condition'=>'Eat_Items.status=0 AND Eat_Items.audit=:audit AND t.c_id=:c_id',
						'params'=>array(':audit'=>Items::audit_pending,':c_id'=>ItemsClassliy::getClass()->id),
				))
		));
		$this->_Ajax_Verify($model,'audit-log-form');
	
		if(isset($_POST['AuditLog']))
		{
			$model->attributes=$_POST['AuditLog'];
			$model->audit_who=AuditLog::admin;//记录 审核人 （审核人的id 在模型中）
			$model->audit_element=AuditLog::items_eat;//记录 被审核的
			$model->element_id=$model->Audit_Eat->id;//记录 被审核id
			$model->audit=AuditLog::nopass;//记录 审核不通过
			if($model->validate()){
				$transaction=$model->dbConnection->beginTransaction();
				try
				{
					if($model->save(false))
					{
						$model->Audit_Eat->Eat_Items->audit=Items::audit_nopass;//审核不通过
						if($model->Audit_Eat->Eat_Items->save(false))
							$return=$this->log('项目(吃)审核不通过记录',ManageLog::admin,ManageLog::create);
					}else
						throw new Exception("添加项目(吃)审核不通过日志错误");
						
					$transaction->commit();
				}
				catch(Exception $e)
				{
					$transaction->rollBack();
					$this->error_log($e->getMessage(),ErrorLog::admin,ErrorLog::create,ErrorLog::rollback,__METHOD__);
				}
			}
			if(isset($return))
				$this->back();
		}
		$this->render('/tmm_auditLog/_nopass',array(
				'model'=>$model,
		));
	}
	
// 	/**
// 	 * 彻底删除
// 	 * @param integer $id
// 	 */
// 	public function actionClear($id)
// 	{		
// 		$model=$this->loadModel($id,array(
// 				'condition'=>'Eat_Items.status=-1',
// 				'with'=>array(
// 						'Eat_Items',
// 						'Eat_ItemsImg',
// 						'Eat_Fare'
// 				),
// 		));
// 		$transaction=$model->dbConnection->beginTransaction();
// 		try
// 		{
// 			if($model->delete() && $model->Eat_Items->delete())
// 			{
// 				$this->upload_delete($model->Eat_Items->map);
// 				foreach ($model->Eat_ItemsImg as $model_img)
// 				{
// 					if($model_img->delete())
// 						$this->upload_delete($model_img->img);
// 					else
// 						throw new Exception("删除项目(吃)图片记录出现错误");
// 				}
// 				foreach ($model->Eat_Fare as $model_fare){
// 					if(! $model_fare->delete())
// 						throw new Exception("删除项目(吃)价格记录出现错误");			
// 				}		
// 				$this->log('彻底删除项目(吃)',ManageLog::admin,ManageLog::delete);
// 			}else
// 				throw new Exception("添加项目(吃)中出现记录错误");
// 			$transaction->commit();
// 		}
// 		catch(Exception $e)
// 		{
// 			$transaction->rollBack();
// 			$this->error_log($e->getMessage(),ErrorLog::admin,ErrorLog::delete,ErrorLog::rollback,__METHOD__);
// 		}
// 		if(!isset($_GET['ajax']))
// 			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
// 	}

	/**
	 * 垃圾回收页
	 */
	public function actionIndex()
	{
		$criteria=new CDbCriteria;
		$criteria->with=array(
					'Eat_Items'=>array(
							'with'=>array(
									'Items_StoreContent'=>array('with'=>array('Content_Store')),
									'Items_Store_Manager',
									'Items_agent',
									'Items_area_id_p_Area_id'=>array('select'=>'name'),
									'Items_area_id_m_Area_id'=>array('select'=>'name'),
									'Items_area_id_c_Area_id'=>array('select'=>'name'),
							)),
					'Eat_ItemsClassliy',
			);
		$criteria->addColumnCondition(array('Eat_Items.status'=>-1));
		$model=new Eat;	
		$this->render('index',array(
			'model'=>$model->search($criteria),
		));
	}
	
	/**
	 *管理页
	 */
	public function actionAdmin()
	{
		$model=new Eat('search');
		$model->Eat_Items=new Items('search');

		$model->unsetAttributes();  // 删除默认属性
		$model->Eat_Items->unsetAttributes();  // 删除默认属性
                
		if(isset($_GET['Eat']))
			$model->attributes=$_GET['Eat'];
		if(isset($_GET['Items']))
			$model->Eat_Items->attributes=$_GET['Items'];

		$this->render('admin',array(
			'model'=>$model,
		));


	}
	
	/**
	 * 禁用
	 * @param integer $id
	 */
	public function actionDisable($id){
		//获取项目类型
		$c_id=ItemsClassliy::getClass()->id;//吃	
		$this->_class_model='Items';	
		if($this->loadModel($id,'`status`=1 AND `c_id`=:c_id',array(':c_id'=>$c_id))->updateByPk($id,array('status'=>0)))
			$this->log('禁用项目(吃)',ManageLog::admin,ManageLog::update);			
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
	}
	
	/**
	 * 激活
	 * @param integer $id
	 */
	public function actionStart($id){
		//获取项目类型
		$c_id=ItemsClassliy::getClass()->id;//吃
		$this->_class_model='Items';
		if($this->loadModel($id,'`status`=0 AND `c_id`=:c_id AND `audit`=:audit',array(':c_id'=>$c_id,':audit'=>Items::audit_pass))->updateByPk($id,array('status'=>1)))
	 		$this->log('激活项目(吃)',ManageLog::admin,ManageLog::update);
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
	}
	/**
	 * 提交审核
	 * @param unknown $id
	 */
	public function actionConfirm($id)
	{
		//获取项目类型
		$c_id=ItemsClassliy::getClass()->id;//吃
		$this->_class_model='Items';
		if($this->loadModel($id,'`status`=0 AND `c_id`=:c_id AND audit=:audit',array(':c_id'=>$c_id,':audit'=>Items::audit_draft))->updateByPk($id,array('audit'=>Items::audit_pending)))
			$this->log('提交项目(吃)审核',ManageLog::admin,ManageLog::update);
	
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
	
	}
}
