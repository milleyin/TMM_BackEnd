<?php
/**
 * 
 * @author Changhai Zhan
 *	创建时间：2015-08-20 14:40:52 */
class Tmm_thrandController extends MainController
{
	/**
	 * 默认操作数据模型
	 * @var string
	 */
	public $_class_model='Thrand';

	/**
	 * 新的统计数量
	 * @var unknown
	 */
	public $_new_number=array();

	/**
	 * 查看详情
	 * @param integer $id
	 */
	public function actionView($id)
	{
		$shops_classliy=ShopsClassliy::getClass();
		$model=$this->loadModel($id,array(
				'with'=>array(
					'Thrand_ShopsClassliy',
					'Thrand_Shops'=>array('with'=>array('Shops_Agent')),
					'Thrand_Pro'=>array('with'=>array(
							'Pro_Thrand_Dot'=>array('with'=>'Dot_Shops'),
							'Pro_Items'=>array('with'=>array(
								'Items_area_id_p_Area_id',
								'Items_area_id_m_Area_id',
								'Items_area_id_c_Area_id',
								'Items_ItemsImg',
								'Items_StoreContent'=>array('with'=>array('Content_Store')),
								'Items_Store_Manager',
								'Items_ItemsClassliy',
							)),
						'Pro_ItemsClassliy',
						'Pro_ProFare'=>array('with'=>array('ProFare_Fare')),
						)),
				),
				'condition'=>'t.c_id=:c_id',
				'params'=>array(':c_id'=>$shops_classliy->id),
				'order'=>'Thrand_Pro.day_sort,Thrand_Pro.half_sort,Thrand_Pro.sort',
		));

		$model->Thrand_TagsElement=TagsElement::get_select_tags(TagsElement::tags_shops_thrand,$id);

		$this->render('view',array('model'=>$model,));
	}

// 	/**
// 	 * 创建
// 	 */
// 	public function actionCreate()
// 	{
// 		$model=new Thrand;
	
// 		$model->scenario='create';
// 		$this->_Ajax_Verify($model,'thrand-form');
		
// 		if(isset($_POST['Thrand']))
// 		{
// 			$model->attributes=$_POST['Thrand'];
// 			if($model->save() && $this->log('创建Thrand',ManageLog::admin,ManageLog::create))
// 				$this->back();
// 		}

// 		$this->render('create',array(
// 			'model'=>$model,
// 		));
// 	}

// 	/**
// 	 * 更新
// 	 * @param integer $id
// 	 */
// 	public function actionUpdate($id)
// 	{
// 		$model=$this->loadModel($id);
//
// 		$model->scenario='update';
// 		$this->_Ajax_Verify($model,'thrand-form');
//
// 		if(isset($_POST['Thrand']))
// 		{
// 			$model->attributes=$_POST['Thrand'];
// 			if($model->save() && $this->log('更新Thrand',ManageLog::admin,ManageLog::update))
// 				$this->back();
// 		}
//
// 		$this->render('update',array(
// 			'model'=>$model,
// 		));
// 	}

	/**
	 * 提交审核
	 * @param unknown $id
	 */
	public function actionConfirm($id)
	{
		//获取项目类型
		$c_id=ShopsClassliy::getClass()->id;//线
		$this->_class_model='Shops';
		if($this->loadModel($id,'`status`=0 AND `c_id`=:c_id AND audit=:audit',array(':c_id'=>$c_id,':audit'=>Shops::audit_draft))->updateByPk($id,array('audit'=>Shops::audit_pending)))
			$this->log('提交线路(线)审核',ManageLog::admin,ManageLog::update);
		
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
		
	}
	/**
	 * 删除
	 * @param integer $id
	 */
	public function actionDelete($id)
	{
		//获取线路类型
		$c_id=ShopsClassliy::getClass()->id;//线
		$this->_class_model='Shops';
		if($this->loadModel($id,'`status`=0 AND `c_id`=:c_id',array(':c_id'=>$c_id))->updateByPk($id,array('status'=>-1)))
			$this->log('删除线路(线)',ManageLog::admin,ManageLog::delete);

		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));

	}
	
	/**
	 * 还原
	 * @param integer $id
	 */
	public function actionRestore($id)
	{
		//获取线路类型
		$c_id=ShopsClassliy::getClass()->id;//线
		$this->_class_model='Shops';
		if($this->loadModel($id,'`status`=-1 AND `c_id`=:c_id',array(':c_id'=>$c_id))->updateByPk($id,array('status'=>0)))
			$this->log('还原线路(线)',ManageLog::admin,ManageLog::update);

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
		$shops_classliy=ShopsClassliy::getClass();
		//查看是否需要审核
		$model=$this->loadModel($id,array(
			'with'=>array('Thrand_Shops'),
			'condition'=>'t.c_id=:c_id AND Thrand_Shops.status=0 AND Thrand_Shops.audit=:audit',
			'params'=>array(':c_id'=>$shops_classliy->id,':audit'=>Shops::audit_pending),
		));

		$model->Thrand_Shops->pub_time=time();
		$model->Thrand_Shops->audit=Shops::audit_pass;// 审核通过
		$transaction=$model->dbConnection->beginTransaction();
		try{
			if($model->Thrand_Shops->save(false)){
				$audit=new AuditLog;
				$audit->info=$model->Thrand_Shops->name;
				$audit->audit_who=AuditLog::admin;//记录 审核人 （审核人的id 在模型中）
				$audit->audit_element=AuditLog::shops_thrand;//记录 被审核的类型
				$audit->element_id=$model->id;//记录 被审核id
				$audit->audit=AuditLog::pass;//记录 审核通过
				if($audit->save(false))
					$return=$this->log('添加审核线路(线)记录',ManageLog::admin,ManageLog::create);
				else
					throw new Exception("添加审核日志错误");
			}else
				throw new Exception("审核通过保存错误");
			$transaction->commit();
		}
		catch(Exception $e)
		{
			$transaction->rollBack();
			$this->error_log($e->getMessage(),ErrorLog::admin,ErrorLog::create,ErrorLog::rollback,__METHOD__);
		}
		if(isset($return))
			echo 1;
		else
			echo '审核通过失败！';
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
		$shops_classliy=ShopsClassliy::getClass();
		//查看是否需要审核
		$model->Audit_Thrand=$this->loadModel($id,array(
			'with'=>array('Thrand_Shops'),
			'condition'=>'t.c_id=:c_id AND Thrand_Shops.status=0 AND Thrand_Shops.audit=:audit',
			'params'=>array(':c_id'=>$shops_classliy->id,':audit'=>Shops::audit_pending),
		));
		$this->_Ajax_Verify($model,'audit-log-form');

		if(isset($_POST['AuditLog']))
		{
			$model->attributes=$_POST['AuditLog'];
			$model->audit_who=AuditLog::admin;//记录 审核人 （审核人的id 在模型中）
			$model->audit_element=AuditLog::shops_thrand;//记录 被审核的
			$model->element_id=$model->Audit_Thrand->id;//记录 被审核id
			$model->audit=AuditLog::nopass;//记录 审核不通过
			if($model->validate()){
				$transaction=$model->dbConnection->beginTransaction();
				try
				{
					if($model->save(false))
					{
						$model->Audit_Thrand->Thrand_Shops->audit=Shops::audit_nopass;//审核不通过
						if($model->Audit_Thrand->Thrand_Shops->save(false))
							$return=$this->log('线路(线)审核不通过记录',ManageLog::admin,ManageLog::create);
					}else
						throw new Exception("添加线路(线)审核不通过日志错误");

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
	
//	/**
//	 * 彻底删除
//	 * @param integer $id
//	 */
//	public function actionClear($id)
//	{
//		if($this->loadModel($id,'`status`=-1')->delete())
//			$this->log('彻底删除Thrand',ManageLog::admin,ManageLog::delete);
//
//		if(!isset($_GET['ajax']))
//			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
//	}

	/**
	 * 垃圾回收页
	 */
	public function actionIndex()
	{
		$criteria=new CDbCriteria;
		$criteria->with=array(
			'Thrand_ShopsClassliy',
			'Thrand_Shops'=>array(
				'with'=>array(
					'Shops_Agent',
				)),
		);
		$criteria->addColumnCondition(array('Thrand_Shops.status'=>-1));

		$model=new Thrand;
		$this->render('index',array(
			'model'=>$model->search($criteria),
		));
	}
	
	/**
	 *管理页
	 */
	public function actionAdmin()
	{
		$model=new Thrand('search');
		$model->Thrand_Shops=new Shops('search');

		$model->unsetAttributes();  // 删除默认属性
		$model->Thrand_Shops->unsetAttributes();  // 删除默认属性

		if(isset($_GET['Thrand']))
			$model->attributes=$_GET['Thrand'];
		if(isset($_GET['Shops']))
			$model->Thrand_Shops->attributes=$_GET['Shops'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}
	
	/**
	 * 禁用
	 * @param integer $id
	 */
	public function actionDisable($id)
	{
		//获取线路类型
		$c_id=ShopsClassliy::getClass()->id;//线
		$this->_class_model='Shops';
		if($this->loadModel($id,'`status`=1 AND `c_id`=:c_id',array(':c_id'=>$c_id))->updateByPk($id,array('status'=>0)))
			$this->log('下线线路(线)',ManageLog::admin,ManageLog::update);
		if(!isset($_GET['ajax']))
 			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
	}
	
	/**
	 * 激活
	 * @param integer $id
	 */
	public function actionStart($id)
	{
		//获取线路类型
		$c_id=ShopsClassliy::getClass()->id;//线
	    $this->_class_model='Shops';
		if($this->loadModel($id,'list_info !=\'\' AND `status`=0 AND `c_id`=:c_id AND `audit`=:audit',array(':c_id'=>$c_id,':audit'=>Shops::audit_pass))->updateByPk($id,array('status'=>1)))
			$this->log('上线线路(线)',ManageLog::admin,ManageLog::update);
		 if(!isset($_GET['ajax']))
 			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));

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
						'with'=>array('Thrand_Shops'),
						'condition'=>'Thrand_Shops.status=0 AND Thrand_Shops.audit !=:audit AND Thrand_Shops.c_id=:c_id',
						'params'=>array(':audit'=>Shops::audit_pending,':c_id'=>ShopsClassliy::getClass()->id),
				)),
		));
	}

	/**
	 * 标签选中操作
	 * @param unknown $id
	 * @param string $type
	 */
	public function actionTags($id)
	{
		if(isset($_POST['tag_ids']) && $_POST['tag_ids'] && isset($_POST['type']))
		{
			$type = $_POST['type'];
			if(!is_array($_POST['tag_ids']))
				$_POST['tag_ids']=array($_POST['tag_ids']);
			$model=$this->loadModel($id,array(
				'with'=>array('Thrand_Shops'),
						'condition'=>'Thrand_Shops.status=0 AND Thrand_Shops.audit !=:audit AND Thrand_Shops.c_id=:c_id',
						'params'=>array(':audit'=>Shops::audit_pending,':c_id'=>ShopsClassliy::getClass()->id),	
			));
			$tags_ids=Tags::filter_tags($_POST['tag_ids']);//安全过滤tags id
			if($type=='yes'){
				//过滤之前有的
				$save_tags_id=TagsElement::not_select_tags_element($tags_ids,$id,TagsElement::tags_shops_thrand);
				$return=TagsElement::select_tags_ids_save($save_tags_id, $id, TagsElement::tags_shops_thrand);
			}else
				$return=TagsElement::select_tags_ids_delete($tags_ids, $id, TagsElement::tags_shops_thrand);
			if($return){
				if($type=='yes')
					$this->log('线路(线)添加标签', ManageLog::admin,ManageLog::create);
				else
					$this->log('线路(线)去除标签', ManageLog::admin,ManageLog::clear);
				echo 1;
			}else
				echo '操作过于频繁，请刷新页面从新选择！';
		}else
			echo '没有选中标签，请重新选择标签！';
	}

	/**
	 * 包装
	 * @param unknown $id
	 */
	public function actionPack($id)
	{
		$this->addCss(Yii::app()->baseUrl.'/css/admin/main/right/group_items.css');

		$shops_classliy=ShopsClassliy::getClass();
		$model=$this->loadModel($id,array(
			'with'=>array(
				'Thrand_ShopsClassliy',
				'Thrand_Shops'=>array('with'=>array('Shops_Agent')),
				'Thrand_Pro'=>array('with'=>array(
					'Pro_Thrand_Dot'=>array('with'=>'Dot_Shops'),
					'Pro_Items'=>array('with'=>array(
						'Items_area_id_p_Area_id',
						'Items_area_id_m_Area_id',
						'Items_area_id_c_Area_id',
						'Items_ItemsImg',
						'Items_StoreContent'=>array('with'=>array('Content_Store')),
						'Items_Store_Manager',
						'Items_ItemsClassliy',
					)),
					'Pro_ItemsClassliy',
					'Pro_ProFare'=>array('with'=>array('ProFare_Fare')),
				)),
			),
			'condition'=>'t.c_id=:c_id',
			'params'=>array(':c_id'=>$shops_classliy->id),
			'order'=>'Thrand_Pro.day_sort,Thrand_Pro.half_sort,Thrand_Pro.sort',
		));

		$model->Thrand_Shops->scenario='pack_thrand';
		$this->set_scenarios($model->Thrand_Pro, 'pack_thrand');
		$Verify_array=array();
		$array_day_sort=array();
		foreach($model->Thrand_Pro as $Pro)
		{
			if(! isset($array[$Pro->day_sort])  && $Pro->half_sort==0 && $Pro->sort==0)
			{
				$array[$Pro->day_sort]=$Pro->half_sort;
				$Verify_array[]=$Pro;
			}
		}
		//ajax验证
		$this->_Ajax_Verify_Same(array_merge(array($model->Thrand_Shops),$Verify_array),'thrand-pack-form');

		if(isset($_POST['Shops']) && isset($_POST['Pro']) && count($Verify_array)==count($_POST['Pro']))
		{
			$this->_upload=Yii::app()->params['uploads_shops_thrand'];
			//获得需要的上传图片
			$uploads = array('list_img','page_img');
			//保存原来的
			$data    = $this->upload_save_data($model->Thrand_Shops, $uploads);
			//过滤空白的值
			$data=array_filter($data);
			//获得参数
			$model->Thrand_Shops->attributes=$_POST['Shops'];
			//过滤 数据id
			$ids=$this->array_listData($_POST['Pro'],'id');

			if(!empty($ids))
				$ids=Pro::filter_pro($ids,$id,true,'t.day_sort,t.half_sort,t.sort');
			//过滤 数据info
			$infos=array_filter($this->array_listData($_POST['Pro'],'info'));//过滤空白的值
			//验证是否为合法参数
			if(count($ids) == count($infos) && count($infos) <= count($model->Thrand_Pro))
				$pro_validate=true;
			else
				$pro_validate=false;
			//获取上传的
			$files   = $this->upload($model->Thrand_Shops,$uploads);
			//看看是修改 还是创建
			if(!empty($data))
				$shop_validate_img=true;
			else
				$shop_validate_img=$this->upload_error($model->Thrand_Shops, $files, $uploads);
			//验证是否为合法参数
			if($shop_validate_img)
				$shop_validate = $model->Thrand_Shops->validate();
			else
				$shop_validate = false;
			//提前验证
			if($pro_validate && $shop_validate && $shop_validate_img)
			{
				if(!empty($data))
					//没有上传的赋值
					$old_path=$this->upload_update_data($model->Thrand_Shops, $data, $files);

				$transaction=$model->dbConnection->beginTransaction();
				try{
					if($model->Thrand_Shops->save(false)){
						if(!empty($data))
						{
							//保存新的
							$this->upload_save($model->Thrand_Shops, $files,true,4,array('pc','app','share'));
							//删除原来的
							$this->upload_delete($old_path);
						}else
							$this->upload_save($model->Thrand_Shops, $files,true,4,array('pc','app','share'));
						
						$pro_array=array();

						foreach ($ids as $key=>$id)
							$pro_array[$id]=array('info'=>$infos[$key]);
						$save=0;
						foreach ($model->Thrand_Pro as $Thrand_Pro)
						{
							if(isset($pro_array[$Thrand_Pro->id]) && $Thrand_Pro->half_sort==0 &&  $Thrand_Pro->sort==0)
							{
								$Thrand_Pro->info = $pro_array[$Thrand_Pro->id]['info'];
								if(! $Thrand_Pro->save())
									throw new Exception("包装线路(线)保存选择项目简介错误");
								else 
									$save++;
							}
						}
						if(count($Verify_array) != $save)
							throw new Exception("包装线路(线)保存选择项目简介个数错误");
					}else
						throw new Exception("包装线路(线)保存主记录错误");
					$return=$this->log('包装线路(线)',ManageLog::admin,ManageLog::update);
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
		$this->render('pack',array('model'=>$model));
	}



	/**
	 * 点的搜索
	 * @param unknown $id
	 * @return multitype:Items NULL
	 */
	public function search_dot()
	{
		$old_model=$this->_class_model;
		$this->_class_model='Dot';
		$shops_classliy = ShopsClassliy::getClass();
		$criteria=new CDbCriteria;
		$criteria->with=array(
			'Dot_ShopsClassliy',
			'Dot_Shops',
			'Dot_Pro'=>array(
				'with'=>array(
					'Pro_Items'=>array(
						'with'=>array(
							'Items_area_id_p_Area_id',
							'Items_area_id_m_Area_id',
							'Items_area_id_c_Area_id',
							'Items_StoreContent',
						),
						'condition'=>'`Pro_Items`.`status`=1 AND `Pro_Items`.`audit`=:audit',
						'params'=>array(':audit'=>Items::audit_pass),
					),
				),
				'order'=>'Dot_Pro.sort',
			),
		);
		$criteria->addColumnCondition(array(
			't.c_id'=>$shops_classliy->id,
			'`Dot_Shops`.`status`'=>Shops::status_online,
			'`Dot_Shops`.`audit`'=>Shops::audit_pass,
		));
		if (isset($_GET['search_info']) && !empty($_GET['search_info']))
		{
			$criteria->params[':search_info']='%'.strtr(trim($_GET['search_info']),array('%'=>'\%', '_'=>'\_', '\\'=>'\\\\')).'%';
			$criteria->addCondition('`Dot_Shops`.`name` LIKE :search_info');
		}
		if(isset($_GET['create']) && $_GET['create'] != '')
		{
			if($_GET['create']==1)
			{
				//$criteria->addColumnCondition(array(
			//		'`Dot_Shops`.`agent_id`'=>Yii::app()->agent->id,
			//	));
			}elseif($_GET['create']==-1){
				//$criteria->addCondition('`Dot_Shops`.`agent_id` !=:agent_id');
				//$criteria->params[':agent_id']=Yii::app()->agent->id;
			}
		}
		$model =new Dot;
		$this->_class_model=$old_model;
		return array('dataProvider'=>$model->search($criteria),'model_search'=>$model);
	}

	/**
	 *	选择查看点
	 * @param unknown $id
	 */
	public function actionView_dot($id)
	{
		$this->addCss(Yii::app()->request->baseUrl . '/css/agent/dist/css/bootstrap.min.css');
		$this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/step.css');
		$this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/form.css');
		$this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/box.css');
		$this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/style.css');
		$this->_class_model='Dot';
		$shops_classliy=ShopsClassliy::getClass();
		$model=$this->loadModel($id,array(
			'with'=>array(
				'Dot_Shops'=>array(
					'condition'=>'Dot_Shops.status=:status && `Dot_Shops`.`audit`=:audit',
					'params'=>array(':status'=>Shops::status_online,':audit'=>Shops::audit_pass),
				),
				'Dot_Pro'=>array(
					'with'=>array(
						'Pro_Items'=>array(
							'with'=>array(
								'Items_area_id_p_Area_id',
								'Items_area_id_m_Area_id',
								'Items_area_id_c_Area_id',
								'Items_ItemsImg',
								'Items_StoreContent'=>array('with'=>array('Content_Store')),
								'Items_Fare',
							),
// 												'condition'=>'`Pro_Items`.`status`=1 AND `Pro_Items`.`audit`=:audit',
// 												'params'=>array(':audit'=>Items::audit_pass),
						),
						'Pro_ItemsClassliy',
					)),
			),
			'condition'=>'t.c_id=:c_id',
			'params'=>array(':c_id'=>$shops_classliy->id),
			'order'=>'Dot_Pro.sort',
		));
		$this->renderPartial('view_dot',array('model'=>$model));
	}

	/**
	 * 创建
	 */
	public function actionCreate($id)
	{

		$this->addCss(Yii::app()->request->baseUrl . '/css/admin/thrand/bootstrap.min.css');
		$this->addCss(Yii::app()->request->baseUrl . '/css/admin/main/right/screen.css');
		$this->addCss(Yii::app()->request->baseUrl . '/css/admin/thrand/step.css');
		$this->addCss(Yii::app()->request->baseUrl . '/css/admin/thrand/form.css');
		$this->addCss(Yii::app()->request->baseUrl . '/css/admin/thrand/box.css');

		$shops_classliy = ShopsClassliy::getClass();
		$model=new Thrand;//线的主要表
		$model->Thrand_Shops=new Shops;//商品表
		$model->Thrand_Shops->scenario='create_thrand';

		$this->_class_model='Agent';
		$model->Thrand_Shops->Shops_Agent=$this->loadModel($id,'status=1');
		//$this->p_r($model->Thrand_Shops->Shops_Agent);

		$Pro=new Pro;//选中点的选中项目表
		$Pro->scenario='create_thrand';
		$ProFare=new ProFare;//选中项目选中的价格表
		$ProFare->scenario='create_thrand';

		$Pro->Pro_ProFare=array($ProFare);//关系赋值 一个项目可以选多个价格 默认一个
		$model->Thrand_Pro=array($Pro);//一条线可以选择多个点中的项目 默认一个

		$this->_Ajax_Verify($model->Thrand_Shops,'thrand-form');
		/************************************************************************************
		 * $_POST['Shops'] =array('name'=>'线的名字');
		 * $_POST['Pro']     [day_sort]//天数
		 * $_POST['Pro']     ['day_sort'][half_sort]//点的排序;
		 * $_POST['Pro']     ['day_sort'][half_sort][dot_id]//点
		 * $_POST['Pro']     ['day_sort'][half_sort][dot_id][sort]//选中项目的排序;
		 * $_POST['Pro']     ['day_sort'][half_sort][dot_id][sort][items_id];//选中项目
		 * $_POST[ProFare]['day_sort'][half_sort][dot_id][sort][items_id][0][fare_id]//选中项目的价格
		 *$array['ProFare']['day_sort']['half_sort']['dot_id']['sort']['item']=array('fare');
		 * ************************************************************************************/

		if(isset($_POST['Shops']) && isset($_POST['Pro']) && is_array($_POST['Pro']) && isset($_POST['ProFare']) && is_array($_POST['ProFare']) && count($_POST['ProFare'])==count($_POST['Pro']))
		{
			//$this->p_r($_POST);
			//exit;
			if($this->validate_thrand($model))
			{

				//提前验证
				$validate_shops=$model->Thrand_Shops->validate();
				$validate_pros_fares=true;
				foreach ($model->Thrand_Pro as $pro)
				{
					if(! $pro->validate())
						$validate_pros_fares=false;
					foreach ($pro->Pro_ProFare as $fare)
					{
						if(! $fare->validate())
							$validate_pros_fares=false;
					}
				}
				if($validate_shops && $validate_pros_fares)
				{

					//事物
					$transaction=$model->dbConnection->beginTransaction();
					try{
						$model->Thrand_Shops->c_id=$shops_classliy->id;
						$model->Thrand_Shops->agent_id=$model->Thrand_Shops->Shops_Agent->id;
						$model->Thrand_Shops->status=Shops::status_offline;
						$model->Thrand_Shops->audit=Shops::audit_draft;
						if($model->Thrand_Shops->save(false))
						{

							$model->id=$model->Thrand_Shops->id;
							$model->c_id=$shops_classliy->id;
							if(! $model->save(false))
								throw new Exception("创建线路(线) 保存线路附表错误");
							$dot_ids=array();
							foreach ($model->Thrand_Pro as $pro_save)
							{
								$dot_ids[]=$pro_save->dot_id;
								$pro_save->shops_id=$model->id;
								if(! $pro_save->save(false))
									throw new Exception("创建线路(线) 保存选中项目表错误");
								foreach ($pro_save->Pro_ProFare as $fare_save)
								{
									$fare_save->pro_id=$pro_save->id;
									$fare_save->thrand_id=$model->id;
									if(! $fare_save->save(false))
										throw new Exception("创建线路(线) 保存选中项目的选中价格表错误");
								}
							}
							//echo 1;exit;
							//继承点的tags
							foreach ($dot_ids as $dot_id)
								$element_ids[]=array(TagsElement::tags_shops_dot,$dot_id);


							TagsElement::select_tags_ids_save(TagsElement::select_tags_all($element_ids), $model->Thrand_Shops->id, TagsElement::tags_shops_thrand,TagsElement::admin);
							//日志
							$return=$this->log('创建线路(线)',ManageLog::admin,ManageLog::create);
						}else
							throw new Exception("审核通过保存错误");
						$transaction->commit();
					}
					catch(Exception $e)
					{
						//事务回滚
						$transaction->rollBack();
						$this->error_log($e->getMessage(),ErrorLog::admin,ErrorLog::create,ErrorLog::rollback,__METHOD__);
					}
				}
			}
			if(isset($return))
				$this->back();
		}

		$this->render('create',array(
			'model'=>$model,
			'search_model'=>$this->search_dot(),
		));
	}

	/**
	 * 验证
	 * @param unknown $model
	 * @return boolean
	 */
	public function validate_thrand($model)
	{
		$this->_class_model='Thrand';
		$shops_classliy = ShopsClassliy::getClass();

		//$validate_array=array();//需要验证的数据
		if(! (isset($_POST['Shops']) && isset($_POST['Pro']) && isset($_POST['ProFare'])))
		{
			$model->Thrand_Shops->addError('name', '选择点或选择的项目或选择的价格 不可空白');
			return false;
		}
		$model->Thrand_Shops->attributes=$_POST['Shops'];
		$day_number=count($_POST['Pro']);//天数
		if($day_number != count($_POST['ProFare'])) //比较是否为给每一天都选了价格
		{
			$model->Thrand_Shops->addError('name', '选择点或选择线无选择价格');
			return false;
		}
		//线路中的天数至少一天 最多不超过 设置的天数
		if($day_number >=1 && $day_number<=Yii::app()->params['shops_thrand_day_number'])
		{
			$i=0;//项目数
			foreach ($_POST['Pro'] as $day_sort=>$day_dots)
			{
				if(!is_int($day_sort) || $day_sort<1 || ceil($day_sort/2)>Yii::app()->params['shops_thrand_day_number'])
				{
					$model->Thrand_Shops->addError('name', '日程天数不是有效值');
					return false;
				}
				if(!is_array($day_dots) || empty($day_dots))
				{
					$model->Thrand_Shops->addError('name', '线路中的选择点存在未上线的项目');
					return false;
				}
				$dot_sort=0; //点的排序
				$j=0;
				foreach ($day_dots as $half_sort=>$dot_items_ids)
				{
					if(!is_array($dot_items_ids) || empty($dot_items_ids))
					{
						$model->Thrand_Shops->addError('name', '线路中的选择点存在未上线的项目');
						return false;
					}

					if($half_sort !=$dot_sort || $half_sort > Yii::app()->params['shops_thrand_one_day_dot_number'])
					{
						$model->Thrand_Shops->addError('name', '线路中的选择点存在未上线的项目');
						return false;
					}

					foreach ($dot_items_ids as $dot_id=>$items)
					{
						if(!is_array($items) || empty($items))
						{
							$model->Thrand_Shops->addError('name', '线路中的选择点存在未上线的项目');
							return false;
						}
						//获取id 点所有的信息
						$dot_items_fares_array=$this->get_dot($dot_id);

						if(empty($dot_items_fares_array))
						{
							$model->Thrand_Shops->addError('name', '线路 选择点或项目或价格 不是有效值');
							return false;
						}

						$items_sort=0;//项目排序
						foreach ($items as $sort=>$item)
						{
							if($items_sort != $sort)
							{
								$model->Thrand_Shops->addError('name', '线路中的选择点存在未上线的项目');
								return false;
							}
							//判断点中是否有项目的id
							if(isset($dot_items_fares_array['items'][$item]) && $dot_items_fares_array['items'][$item]['is_validate'])
								$dot_items_fares_array['items'][$item]['is_validate']=false;//一个点不能有重复的项目
							else{
								$model->Thrand_Shops->addError('name', '线路中或点存在已经选择的项目');
								return false;
							}

							//项目中的数据
							$item_data=$dot_items_fares_array['items'][$item]['data'];

							//赋值
							$Thrand_Pro=isset($model->Thrand_Pro[$i])?$model->Thrand_Pro[$i]:new Pro;
							$Thrand_Pro->scenario='create_thrand';
							$Thrand_Pro->shops_c_id=$shops_classliy->id;
							$Thrand_Pro->sort=$sort;
							$Thrand_Pro->day_sort=$day_sort;
							$Thrand_Pro->half_sort=$half_sort;
							$Thrand_Pro->items_id=$item;
							$Thrand_Pro->dot_id=$dot_id;

							$Thrand_Pro->agent_id=$item_data->agent_id;
							$Thrand_Pro->store_id=$item_data->store_id;
							$Thrand_Pro->c_id=$item_data->c_id;

							if(! isset($_POST['ProFare'][$day_sort][$half_sort][$dot_id][$sort][$item]))
							{
								$model->Thrand_Shops->addError('name', '线路中或点中的项目无选中的价格');
								return false;
							}

							$item_select_fares=$_POST['ProFare'][$day_sort][$half_sort][$dot_id][$sort][$item];
							if(!is_array($item_select_fares) || empty($item_select_fares))
							{
								$model->Thrand_Shops->addError('name', '线路中或点中的项目选中的价格无效');
								return false;
							}

							$Pro_ProFares=array();
							$j=0;//价格数
							$fares=array();
							foreach ($item_select_fares as $fare)
							{
								if(isset($dot_items_fares_array['fares'][$item][$fare]) && $dot_items_fares_array['fares'][$item][$fare]['is_validate'])
									$dot_items_fares_array['fares'][$item][$fare]['is_validate']=false;
								else{
									$model->Thrand_Shops->addError('name', '线路中或点中的项目选中的价格存在重复');
									return false;
								}
								$Pro_ProFare=isset($model->Thrand_Pro[$i]->Pro_ProFare[$j])?$model->Thrand_Pro[$i]->Pro_ProFare[$j]:new ProFare;
								$Pro_ProFare->scenario='create_thrand';
								$Pro_ProFare->fare_id=$fare;
								$Pro_ProFare->items_id=$item;
								$Pro_ProFares[]=$Pro_ProFare;
								if(in_array($fare, $fares))
								{
									$model->Thrand_Shops->addError('name', '线路中或点中的项目选中的价格存在重复');
									return false;
								}
								$fares[]=$fare;
								$j++;//价格数
							}

							$Thrand_Pro->Pro_ProFare=$Pro_ProFares;
							$Thrand_Pros[]=$Thrand_Pro;
							$this->_new_number[$i]=$j;
							$items_sort++;
							$i++;//项目数
						}
					}
					$dot_sort++;
				}
			}
		}

		if(! isset($Thrand_Pros))
		{
			$model->Thrand_Shops->addError('name', '线路中的选择点中存在未上线的项目');
			return false;
		}
		$model->Thrand_Pro=$Thrand_Pros;
		return true;
	}


	public function get_dot($dot_id)
	{
		$model=Dot::model()->findByPk($dot_id,array(
			'with'=>array(
				'Dot_Shops'=>array(
					'condition'=>'`Dot_Shops`.`status`=:status && `Dot_Shops`.`audit`=:audit',
					'params'=>array(':status'=>Shops::status_online,':audit'=>Shops::audit_pass),
				),
				'Dot_Pro'=>array(
					'with'=>array(
						'Pro_ItemsClassliy',
						'Pro_Items'=>array(
							'with'=>array(
								'Items_StoreContent'=>array('with'=>array('Content_Store')),
								'Items_Fare',
								'Items_area_id_p_Area_id',
								'Items_area_id_m_Area_id',
								'Items_area_id_c_Area_id',
							),
							'condition'=>'`Pro_Items`.`status`=1 AND `Pro_Items`.`audit`=:audit',
							'params'=>array(':audit'=>Items::audit_pass),
						),
					),
					'order'=>'Dot_Pro.sort',
				),
			),
		));
		if($model)
		{
			if($model->Dot_Shops)
				$shops=$model->Dot_Shops;
			else
				return array();
			if(! $model->Dot_Pro)
				return array();

			foreach ($model->Dot_Pro as $Dot_Pro)
			{
				if(! $Dot_Pro->Pro_Items)
					return array();
				$items[$Dot_Pro->items_id]=array('data'=>$Dot_Pro->Pro_Items,'is_validate'=>true);
				if(! $Dot_Pro->Pro_Items->Items_Fare)
					return array();
				foreach ($Dot_Pro->Pro_Items->Items_Fare as $Items_Fare)
					$fares[$Dot_Pro->items_id][$Items_Fare->id]=array('data'=>$Items_Fare,'is_validate'=>true);
			}
			return array('shops'=>$shops,'items'=>$items,'fares'=>$fares);
		}
		return array();
	}

	/**
	 * 获取新的线路对象
	 * @param unknown $id
	 * @param string $c_id
	 * @return unknown
	 */
	public function new_model($id,$c_id='')
	{
		if($c_id=='')
			$c_id=ShopsClassliy::getClass()->id;
		return $this->loadModel($id,array(
			'with'=>array(
				'Thrand_ShopsClassliy',
				'Thrand_Shops'=>array('with'=>array('Shops_Agent')),
				'Thrand_Pro'=>array('with'=>array(
					'Pro_Thrand_Dot'=>array(
						'with'=>array(
							'Dot_Shops'=>array(
// 												'condition'=>'`Dot_Shops`.`status`=1 AND `Dot_Shops`.`audit`=:audit_dot',
// 												'params'=>array(':audit_dot'=>Shops::audit_pass),
							),
						),
					),
					'Pro_Items'=>array(
						'with'=>array(
							'Items_area_id_p_Area_id',
							'Items_area_id_m_Area_id',
							'Items_area_id_c_Area_id',
							'Items_ItemsImg',
							'Items_StoreContent'=>array('with'=>array('Content_Store')),
							'Items_Store_Manager',
							'Items_ItemsClassliy',
						),
// 										'condition'=>'`Pro_Items`.`status`=1 AND `Pro_Items`.`audit`=:audit_pro',
// 										'params'=>array(':audit_pro'=>Items::audit_pass),
					),
					'Pro_ItemsClassliy',
					'Pro_ProFare'=>array('with'=>array('ProFare_Fare')),
				)),
			),
			'condition'=>'`t`.`c_id`=:c_id  AND `Thrand_Shops`.`status`=:status AND `Thrand_Shops`.`audit` != :audit',
			'params'=>array(':c_id'=>$c_id,':status'=>Shops::status_offline,':audit'=>Shops::audit_pending),
//			'condition'=>'`t`.`c_id`=:c_id AND `Thrand_Shops`.`agent_id`=:agent_id AND `Thrand_Shops`.`status`=:status AND `Thrand_Shops`.`audit` != :audit',
//			'params'=>array(':c_id'=>$c_id,':agent_id'=>Yii::app()->agent->id,':status'=>Shops::status_offline,':audit'=>Shops::audit_pending),
			'order'=>'Thrand_Pro.day_sort,Thrand_Pro.half_sort,Thrand_Pro.sort',
		));
	}

	/**
	 * 更新
	 * @param integer $id
	 */
	public function actionUpdate($id)
	{
		$this->addCss(Yii::app()->request->baseUrl . '/css/admin/thrand/bootstrap.min.css');
		$this->addCss(Yii::app()->request->baseUrl . '/css/admin/main/right/screen.css');
		$this->addCss(Yii::app()->request->baseUrl . '/css/admin/thrand/step.css');
		$this->addCss(Yii::app()->request->baseUrl . '/css/admin/thrand/form.css');
		$this->addCss(Yii::app()->request->baseUrl . '/css/admin/thrand/box.css');

		$shops_classliy=ShopsClassliy::getClass();
		$model=$this->new_model($id,$shops_classliy->id);

		$model->Thrand_Shops->scenario='create_thrand';
		//设置验证场景;
		foreach ($model->Thrand_Pro as $Pro)
		{
			$Pro->scenario='create_thrand';
			foreach ($Pro->Pro_ProFare as $ProFare)
				$ProFare->scenario='create_thrand';
		}

		$this->_Ajax_Verify($model->Thrand_Shops,'thrand-form');

		if(isset($_POST['Shops']) && isset($_POST['Pro']) && is_array($_POST['Pro']) && isset($_POST['ProFare']) && is_array($_POST['ProFare']) && count($_POST['ProFare'])==count($_POST['Pro']))
		{
			if($this->validate_thrand($model))
			{
				//提前验证
				$validate_shops=$model->Thrand_Shops->validate();
				$validate_pros_fares=true;
				foreach ($model->Thrand_Pro as $pro)
				{
					if(! $pro->validate())
						$validate_pros_fares=false;
					foreach ($pro->Pro_ProFare as $fare)
					{
						if(! $fare->validate())
							$validate_pros_fares=false;
					}
				}

				if($validate_shops && $validate_pros_fares)
				{
					$delete_pro_number=count($this->_new_number);
					$delete_models=array();
					$old_model=$this->new_model($id,$shops_classliy->id);
					foreach ($old_model->Thrand_Pro as $key_pro=>$delete_pro)
					{
						if(($key_pro+1) <= $delete_pro_number)
						{
							$delete_fare_number=$this->_new_number[$key_pro];
							foreach ($delete_pro->Pro_ProFare as $key_fare=>$delete_fare)
							{
								if(($key_fare+1) > $delete_fare_number)
									$delete_models[]=$delete_fare;
							}
						}else
							$delete_models[]=$delete_pro;
					}
					//事物
					$transaction=$model->dbConnection->beginTransaction();
					try{
						$model->Thrand_Shops->c_id=$shops_classliy->id;
						//$model->Thrand_Shops->agent_id=Yii::app()->agent->id;
						$model->Thrand_Shops->status=Shops::status_offline;
						$model->Thrand_Shops->audit=Shops::audit_draft;
						if($model->Thrand_Shops->save(false))
						{
							$model->id=$model->Thrand_Shops->id;
							$model->c_id=$shops_classliy->id;
							if(! $model->save(false))
								throw new Exception("修改线路(线) 保存线路附表错误");
							foreach ($model->Thrand_Pro as $pro_save)
							{
								$pro_save->shops_id=$model->id;
								if(! $pro_save->save(false))
									throw new Exception("修改线路(线) 保存选中项目表错误");
								foreach ($pro_save->Pro_ProFare as $fare_save)
								{
									$fare_save->pro_id=$pro_save->id;
									$fare_save->thrand_id=$model->id;
									if(! $fare_save->save(false))
										throw new Exception("修改线路(线) 保存选中项目的选中价格表错误");
								}
							}
							foreach ($delete_models as $delete_model)
							{
								if(isset($delete_model->Pro_ProFare))
								{
									foreach ($delete_model->Pro_ProFare as $fare_delete)
									{
										if(! $fare_delete->delete())
											throw new Exception("修改线路选中项的选中价格错误");
									}
								}
								if(! $delete_model->delete())
									throw new Exception("修改线路选中项错误");
							}
							$return=$this->log('修改线路(线)',ManageLog::admin,ManageLog::update);
						}else
							throw new Exception("修改线路主要记录错误");
						$transaction->commit();
					}
					catch(Exception $e)
					{
						$transaction->rollBack();
						$this->error_log($e->getMessage(),ErrorLog::admin,ErrorLog::create,ErrorLog::rollback,__METHOD__);
					}
				}
			}
			if(isset($return))
				$this->back();
		}elseif(isset($_POST['Shops'])){
			$model->Thrand_Shops->addError('name','选中点 不能空白');
		}

		$this->render('update',array(
			'model'=>$model,
			'search_model'=>$this->search_dot(),
		));
	}

	/**
	 * 公共条件
	 * @return CDbCriteria
	 */
	public function criteria()
	{
		$shops_classliy=ShopsClassliy::getClass();
		$criteria=new CDbCriteria;
		$criteria->with=array(
			'Thrand_ShopsClassliy',
			'Thrand_Shops'=>array('with'=>array('Shops_Agent')),
			'Thrand_Pro'=>array(
				'with'=>array(
					'Pro_Thrand_Dot'=>array(
						'with'=>array(
							'Dot_Shops'=>array(
// 													'condition'=>'Dot_Shops.status=:status && `Dot_Shops`.`audit`=:audit',
// 													'params'=>array(':status'=>Shops::status_online,':audit'=>Shops::audit_pass),
							),
						),
					),
					'Pro_Items'=>array('with'=>array(
						'Items_area_id_p_Area_id',
						'Items_area_id_m_Area_id',
						'Items_area_id_c_Area_id',
						'Items_ItemsImg',
						'Items_StoreContent'=>array('with'=>array('Content_Store')),
						'Items_Store_Manager',
						'Items_ItemsClassliy',
					)),
					'Pro_ItemsClassliy',
					'Pro_ProFare'=>array('with'=>array('ProFare_Fare')),
				),
				'order'=>'`Thrand_Pro`.`day_sort`,`Thrand_Pro`.`half_sort`,Thrand_Pro.sort',
			),
		);
		$criteria->addColumnCondition(array(
//			'`t`.`c_id`'=>$shops_classliy->id,
//			'`Thrand_Shops`.`agent_id`'=>Yii::app()->agent->id,
		));

		return $criteria;
	}

	/**
	 * 搜索线路名称
	 * @param unknown $criteria
	 */
	public function search_info($criteria)
	{
		if (isset($_GET['search_info']) && !empty($_GET['search_info']))
		{
			$criteria->params[':search_info']='%'.strtr(trim($_GET['search_info']),array('%'=>'\%', '_'=>'\_', '\\'=>'\\\\')).'%';
			$criteria->addCondition('(`Thrand_Shops`.`name` LIKE :search_info)');
		}
	}


}
