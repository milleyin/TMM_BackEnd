<?php
/**
 * 
 * @author Changhai Zhan
 *	创建时间：2015-08-20 14:40:20 */
class Tmm_dotController extends MainController
{
	/**
	 * 默认操作数据模型
	 * @var string
	 */
	public $_class_model='Dot';

	/**
	 * 查看详情
	 * @param integer $id
	 */
	public function actionView($id)
	{
		$this->addCss(Yii::app()->baseUrl.'/css/admin/main/right/pack_items.css');
		$shops_classliy=ShopsClassliy::getClass();
		$model=$this->loadModel($id,array(
				'with'=>array(
				'Dot_Shops'=>array('with'=>array('Shops_Agent')),
				'Dot_Pro'=>array('with'=>array(
						'Pro_Items'=>array('with'=>array(
							'Items_area_id_p_Area_id',
							'Items_area_id_m_Area_id',
							'Items_area_id_c_Area_id',
							'Items_ItemsImg',
							'Items_StoreContent'=>array('with'=>array('Content_Store')),
							'Items_Store_Manager',
							'Items_Fare',
						)),				
						'Pro_ItemsClassliy',
					)),				
				),
				'condition'=>'t.c_id=:c_id',
				'params'=>array(':c_id'=>$shops_classliy->id),
				'order'=>'Dot_Pro.sort',
		));
		$model->Dot_TagsElement=TagsElement::get_select_tags(TagsElement::tags_shops_dot,$id);
		$this->render('view',array('model'=>$model));
	}

	/**
	 * 创建
	 * $id 运营商id
	 */
	public function actionCreate($id)
	{
		$this->addCss(Yii::app()->baseUrl.'/css/admin/main/right/dot.css');
		$this->addCss(Yii::app()->baseUrl.'/css/admin/main/right/items_list.css');
		$model=new Dot;
		$model->Dot_Shops=new Shops;
	
		$this->_class_model='Agent';
		$model->Dot_Shops->Shops_Agent=$this->loadModel($id,'status=1');
		//创建添加时验证
		$model->Dot_Shops->scenario='create_dot';
		$model->Dot_Pro=$this->new_modes('Pro','create_dot');//默认一个

		$this->_Ajax_Verify($model->Dot_Shops,'dot-form');
		
		if(isset($_POST['Shops']) && isset($_POST['Pro']) && is_array($_POST['Pro']))
		{

			$model->Dot_Shops->attributes=$_POST['Shops'];
			$items_ids=array();
			foreach ($_POST['Pro'] as $value)
			{
				if(isset($value['items_id']))
					$items_ids[]=$value['items_id'];
			}
			if(! empty($items_ids))						
				$items_models=Items::filter_items($items_ids,false);//过滤项目		
			
			if($model->Dot_Shops->validate() && isset($items_models) && $items_models){
				$transaction=$model->dbConnection->beginTransaction();
				try
				{
					//获取类型
					$this->_class_model='Dot';
					$shops_classliy=ShopsClassliy::getClass();
					
					$model->Dot_Shops->c_id=$shops_classliy->id;
					$model->Dot_Shops->agent_id=$model->Dot_Shops->Shops_Agent->id;		
					$model->Dot_Shops->status=0;//创建的没有上线
					$model->Dot_Shops->audit=Shops::audit_draft;//创建的默认未提交
					if(! $model->Dot_Shops->save(false))
						throw new Exception("添加商品(点)主要表记录错误");
					else{
						$model->id=$model->Dot_Shops->id;
						$model->c_id=$shops_classliy->id;
						if(! $model->save())
							throw new Exception("添加商品(点)附表记录错误");
						$new_ids=array();
						foreach ($items_models as $key=>$items_model){
							$pro_model=new Pro('create_dot');
							$pro_model->shops_id=$model->Dot_Shops->id;
							$pro_model->agent_id=$items_model->agent_id;
							$pro_model->store_id=$items_model->store_id;
							$pro_model->shops_c_id=$shops_classliy->id;
							$pro_model->c_id=$items_model->c_id;
							$pro_model->sort=$key;
							$pro_model->items_id=$items_model->id;
							if(! $pro_model->save())
								throw new Exception("添加商品(点)选中的项目记录错误");
							$new_ids[]=$items_model->id;
						}
						//继承项目的tags
						$models_class=Items::get_class($new_ids);
						foreach ($models_class as $items_model_class)
							$element_ids[]=array(TagsElement::$model_name[$items_model_class->Items_ItemsClassliy->append],$items_model_class->id);	
				
						TagsElement::select_tags_ids_save(TagsElement::select_tags_all($element_ids), $model->Dot_Shops->id, TagsElement::tags_shops_dot);
						$return = $this->log('创建商品(点)',ManageLog::admin,ManageLog::create);
					}
					$transaction->commit();
				}
				catch(Exception $e)
				{
					$transaction->rollBack();
					$this->error_log($e->getMessage(),ErrorLog::admin,ErrorLog::create,ErrorLog::rollback,__METHOD__);
				}
				if(isset($return))
					$this->back();		
			}elseif(! $items_models) 		
				$model->addError('select_items', $model->getAttributeLabel('Dot_Pro.items_id') . ' 不能空白');		

		}elseif(isset($_POST['Shops']) && ! isset($_POST['Pro'][0]['items_id'])){
			$model->Dot_Shops->attributes=$_POST['Shops'];
			$model->addError('select_items', $model->getAttributeLabel('Dot_Pro.items_id') . ' 不能空白');
		}
	
		$this->render('create',array_merge(array('model'=>$model),$this->search_items($id)));
	}
	
	/**
	 * 项目搜索
	 * @param unknown $id
	 */
	public function search_items($id)
	{
		$criteria=new CDbCriteria;
		$criteria->with=array(
				'Items_Fare',
				'Items_agent',
				'Items_ItemsImg',
				'Items_StoreContent'=>array('select'=>'name','with'=>array('Content_Store'=>array('select'=>'phone'))),
				'Items_Store_Manager'=>array('select'=>'phone'),
				'Items_ItemsClassliy',
				'Items_area_id_p_Area_id'=>array('select'=>'name'),
				'Items_area_id_m_Area_id'=>array('select'=>'name'),
				'Items_area_id_c_Area_id'=>array('select'=>'name'),
		);
		$model_search=new Items;
		$model_search->Items_StoreContent=new StoreContent;
		$model_search->unsetAttributes();  // 删除默认属性
		$model_search->Items_StoreContent->unsetAttributes();  // 删除默认属性
		if(isset($_GET['Items']))
		{
			if(isset($_GET['Items']['name']))
				$model_search->name=$_GET['Items']['name'];
			if(isset($_GET['Items']['area_id_p']))
				$model_search->area_id_p=$_GET['Items']['area_id_p'];
			if(isset($_GET['Items']['area_id_m']))
				$model_search->area_id_m=$_GET['Items']['area_id_m'];
			if(isset($_GET['Items']['area_id_c']))
				$model_search->area_id_c=$_GET['Items']['area_id_c'];
		}
		if(isset($_GET['StoreContent']['name']))
			$model_search->Items_StoreContent->name=$_GET['StoreContent']['name'];
		
		if(isset($_GET['Items']['agent_id'])){
			if($_GET['Items']['agent_id']==1)
				$criteria->addColumnCondition(array('t.agent_id'=>$id));
			if($_GET['Items']['agent_id']==2)
				$criteria->compare('t.agent_id','<>'.$id);
		}
		$criteria->addSearchCondition('t.name',$model_search->name);
		$criteria->addSearchCondition('Items_area_id_p_Area_id.name',$model_search->area_id_p);
		$criteria->addSearchCondition('Items_area_id_m_Area_id.name',$model_search->area_id_m);
		$criteria->addSearchCondition('Items_area_id_c_Area_id.name',$model_search->area_id_c);
		$criteria->addSearchCondition('Items_StoreContent.name',$model_search->Items_StoreContent->name);
		$criteria->addColumnCondition(array(
				't.status'=>1,
				't.audit'=>Items::audit_pass,
		));
		return array('dataProvider'=>$model_search->search($criteria),'model_search'=>$model_search);
	}

	/**
	 * 更新
	 * @param integer $id
	 */
	public function actionUpdate($id)
	{
		$this->addCss(Yii::app()->baseUrl.'/css/admin/main/right/dot.css');
		$this->addCss(Yii::app()->baseUrl.'/css/admin/main/right/items_list.css');
		$shops_classliy=ShopsClassliy::getClass();	
		$model=$this->loadModel($id,array(
			'with'=>array(
				'Dot_Shops'=>array('with'=>array('Shops_Agent')),
				'Dot_Pro'=>array('with'=>array(
						'Pro_Items'=>array('with'=>array(
							'Items_area_id_p_Area_id',
							'Items_area_id_m_Area_id',
							'Items_area_id_c_Area_id',
							'Items_ItemsImg',
						)),				
						'Pro_ItemsClassliy'
				)),				
			),
			'condition'=>'t.c_id=:c_id AND Dot_Shops.status=0 AND Dot_Shops.audit !=:audit',
			'params'=>array(':c_id'=>$shops_classliy->id,':audit'=>Shops::audit_pending),
			'order'=>'Dot_Pro.sort',
		));

		$model->Dot_Shops->scenario='update_dot';
		$this->_Ajax_Verify($model->Dot_Shops,'dot-form');

		if(isset($_POST['Shops']) && isset($_POST['Pro'][0]['items_id']) && is_array($_POST['Pro']))
		{
			$model->Dot_Shops->attributes=$_POST['Shops'];
			$items_ids=array();
			foreach ($_POST['Pro'] as $value)
			{
				if(isset($value['items_id']))
					$items_ids[]=$value['items_id'];
			}
			if(! empty($items_ids))						
				$items_models=Items::filter_items($items_ids,false);//过滤项目		
			$old_count=count($model->Dot_Pro);//原来的数量
			$new_count=count($items_models);//现在的数量
			
			if($model->Dot_Shops->validate() && $items_models){
				$transaction=$model->dbConnection->beginTransaction();
				try
				{
					//获取类型
					$this->_class_model='Dot';
					$shops_classliy=ShopsClassliy::getClass();
					$model->Dot_Shops->audit=Shops::audit_draft;//修改未提交
					if(! $model->Dot_Shops->save(false))
						throw new Exception("添加商品(点)主要表记录错误");
					else{
						foreach ($items_models as $key=>$items_model)
						{
							if(isset($model->Dot_Pro[$key]))
								$pro_model=$model->Dot_Pro[$key];
							else
								$pro_model=new Pro('update_dot');										
							$pro_model->shops_id=$model->Dot_Shops->id;
							$pro_model->agent_id=$items_model->agent_id;
							$pro_model->store_id=$items_model->store_id;
							$pro_model->shops_c_id=$shops_classliy->id;
							$pro_model->c_id=$items_model->c_id;
							$pro_model->sort=$key;
							$pro_model->items_id=$items_model->id;
							if(! $pro_model->save())
								throw new Exception("修改商品(点)选中的项目记录错误");
						}	
						if($new_count < $old_count)
						{
							$delete_models=array_slice($model->Dot_Pro,$new_count);
							foreach ($delete_models as $delete_model)
							{
								if(! $delete_model->delete())
									throw new Exception("删除商品(点)选中的项目记录错误");
							}
						}		
						$return = $this->log('更新商品(点)',ManageLog::admin,ManageLog::update);
					}
					$transaction->commit();
				}
				catch(Exception $e)
				{
					$transaction->rollBack();
					$this->error_log($e->getMessage(),ErrorLog::admin,ErrorLog::create,ErrorLog::rollback,__METHOD__);
				}
				if(isset($return))
					$this->back();		
			}elseif(! $items_models) 		
				$model->addError('select_items', $model->getAttributeLabel('Dot_Pro.items_id') . ' 不能空白');
		}elseif(isset($_POST['Shops']) && ! isset($_POST['Pro'][0]['items_id'])){
			$model->Dot_Shops->attributes=$_POST['Shops'];
			$model->addError('select_items', $model->getAttributeLabel('Dot_Pro.items_id') . ' 不能空白');
		}
				
		$this->render('update',array_merge(
				array('model'=>$model),
				$this->search_items($model->Dot_Shops->Shops_Agent->id)
		));
	}

	/**
	 * 提交审核
	 * @param unknown $id
	 */
	public function actionConfirm($id)
	{
		//获取项目类型
		$c_id=ShopsClassliy::getClass()->id;//点
		$this->_class_model='Shops';
		if($this->loadModel($id,'`status`=0 AND `c_id`=:c_id AND audit=:audit',array(':c_id'=>$c_id,':audit'=>Shops::audit_draft))->updateByPk($id,array('audit'=>Shops::audit_pending)))
			$this->log('提交线路(点)审核',ManageLog::admin,ManageLog::update);
		
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
		
	}
	
	/**
	 * 删除
	 * @param integer $id
	 */
	public function actionDelete($id)
	{
		//获取项目类型
		$c_id=ShopsClassliy::getClass()->id;//点
		$this->_class_model='Shops';
		if($this->loadModel($id,'`status`=0 AND `c_id`=:c_id',array(':c_id'=>$c_id))->updateByPk($id,array('status'=>-1)))
			$this->log('删除线路(点)',ManageLog::admin,ManageLog::delete);

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
		$c_id=ShopsClassliy::getClass()->id;//点
		$this->_class_model='Shops';
		if($this->loadModel($id,'`status`=-1 AND `c_id`=:c_id',array(':c_id'=>$c_id))->updateByPk($id,array('status'=>0)))
			$this->log('还原线路(点)',ManageLog::admin,ManageLog::update);

		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
	}
	
	/**
	 * 审核通过
	 * @param integer $id
	 */
	public function actionPass($id)
	{
		$shops_classliy=ShopsClassliy::getClass();
		//查看是否需要审核
		$model=$this->loadModel($id,array(
			'with'=>array('Dot_Shops'),
			'condition'=>'t.c_id=:c_id AND Dot_Shops.status=0 AND Dot_Shops.audit=:audit',
			'params'=>array(':c_id'=>$shops_classliy->id,':audit'=>Shops::audit_pending),
		));
	
		$model->Dot_Shops->pub_time=time();
		$model->Dot_Shops->audit=Shops::audit_pass;// 审核通过
		$transaction=$model->dbConnection->beginTransaction();
		try{
				if($model->Dot_Shops->save(false)){
					$audit=new AuditLog;
					$audit->info=$model->Dot_Shops->name;
					$audit->audit_who=AuditLog::admin;//记录 审核人 （审核人的id 在模型中）
					$audit->audit_element=AuditLog::shops_dot;//记录 被审核的类型
					$audit->element_id=$model->id;//记录 被审核id
					$audit->audit=AuditLog::pass;//记录 审核通过
					if($audit->save(false))
						$return=$this->log('添加审核线路(点)记录',ManageLog::admin,ManageLog::create);
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
		$model->Audit_Dot=$this->loadModel($id,array(
				'with'=>array('Dot_Shops'),
				'condition'=>'t.c_id=:c_id AND Dot_Shops.status=0 AND Dot_Shops.audit=:audit',
				'params'=>array(':c_id'=>$shops_classliy->id,':audit'=>Shops::audit_pending),
		));
		$this->_Ajax_Verify($model,'audit-log-form');
		
		if(isset($_POST['AuditLog']))
		{
			$model->attributes=$_POST['AuditLog'];
			$model->audit_who=AuditLog::admin;//记录 审核人 （审核人的id 在模型中）
			$model->audit_element=AuditLog::shops_dot;//记录 被审核的
			$model->element_id=$model->Audit_Dot->id;//记录 被审核id
			$model->audit=AuditLog::nopass;//记录 审核不通过
			if($model->validate()){
				$transaction=$model->dbConnection->beginTransaction();
				try
				{
					if($model->save(false))
					{
						$model->Audit_Dot->Dot_Shops->audit=Shops::audit_nopass;//审核不通过
						if($model->Audit_Dot->Dot_Shops->save(false))
							$return=$this->log('线路(点)审核不通过记录',ManageLog::admin,ManageLog::create);
					}else
						throw new Exception("添加线路(点)审核不通过日志错误");
		
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
// 		//获取项目类型
// 		$c_id=ShopsClassliy::getClass()->id;//点
// 		$this->_class_model='Shops';
// 		if($this->loadModel($id,'`status`=-1 AND c_id=:c_id',array(':c_id'=>$c_id))->delete())
// 			$this->log('彻底删除Dot',ManageLog::admin,ManageLog::delete);
			
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
				'Dot_ShopsClassliy',
				'Dot_Shops'=>array('with'=>array('Shops_Agent')),
		);
		$criteria->addColumnCondition(array('Dot_Shops.status'=>-1));
		$model=new Dot;
		$this->render('index',array(
			'model'=>$model->search($criteria),
		));
	}
	
	/**
	 *管理页
	 */
	public function actionAdmin()
	{
		$model=new Dot('search');
		$model->Dot_Shops=new Shops('search');

		$model->unsetAttributes();  // 删除默认属性
		$model->Dot_Shops->unsetAttributes();  // 删除默认属性

		if(isset($_GET['Dot']))
			$model->attributes=$_GET['Dot'];
		if(isset($_GET['Shops']))
			$model->Dot_Shops->attributes=$_GET['Shops'];

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
		//获取项目类型
		$c_id=ShopsClassliy::getClass()->id;//点
		$this->_class_model='Shops';
		if($this->loadModel($id,'`status`=1 AND `c_id`=:c_id',array(':c_id'=>$c_id))->updateByPk($id,array('status'=>0)))
			$this->log('下线线路(点)',ManageLog::admin,ManageLog::update);
		if(!isset($_GET['ajax']))
 			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
	}
	
	/**
	 * 激活
	 * @param integer $id
	 */
	public function actionStart($id)
	{
		//获取项目类型
		$c_id=ShopsClassliy::getClass()->id;//点
		$this->_class_model='Shops';
		if($this->loadModel($id,'`status`=0 AND `c_id`=:c_id AND `audit`=:audit AND `list_info`!=\'\'',array(':c_id'=>$c_id,':audit'=>Shops::audit_pass))->updateByPk($id,array('status'=>1)))
			$this->log('上线线路(点)',ManageLog::admin,ManageLog::update);
		 if(!isset($_GET['ajax']))
 			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
	}
	
	/**
	 *选择标签的显示
	 * @param unknown $id
	 */
	public function actionSelect($id)
	{
		$model=new Tags('search');
		$model->unsetAttributes();  // 删除默认属性
		if(isset($_GET['Tags']))
			$model->attributes=$_GET['Tags'];
		
		$this->render('select',array(
				'model'=>$model,
				'select'=>$this->loadModel($id,array(
						'with'=>array('Dot_Shops'),
						'condition'=>'Dot_Shops.status=0 AND Dot_Shops.audit !=:audit AND Dot_Shops.c_id=:c_id',
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
			if(! is_array($_POST['tag_ids']))
				$_POST['tag_ids']=array($_POST['tag_ids']);
			$model=$this->loadModel($id,array(
				'with'=>array('Dot_Shops'),
						'condition'=>'Dot_Shops.status=0 AND Dot_Shops.audit !=:audit AND Dot_Shops.c_id=:c_id',
						'params'=>array(':audit'=>Shops::audit_pending,':c_id'=>ShopsClassliy::getClass()->id),	
			));
			$tags_ids=Tags::filter_tags($_POST['tag_ids']);//安全过滤tags id
			if($type=='yes'){
				//过滤之前有的
				$save_tags_id=TagsElement::not_select_tags_element($tags_ids,$id,TagsElement::tags_shops_dot);
				$return=TagsElement::select_tags_ids_save($save_tags_id, $id, TagsElement::tags_shops_dot);
			}else
				$return=TagsElement::select_tags_ids_delete($tags_ids, $id, TagsElement::tags_shops_dot);
			if($return)
			{
				if($type=='yes')
					$this->log('线路(点)添加标签', ManageLog::admin,ManageLog::create);
				else
					$this->log('线路(点)去除标签', ManageLog::admin,ManageLog::clear);
				echo 1;
			}else
				echo '操作过于频繁，请刷新页面从新选择！';
		}else
			echo '没有选中标签，请重新选择标签！';
	}
	
	/**
	 * 包装点
	 * @param unknown $id
	 */
	public function actionPack($id)
	{
		$this->addCss(Yii::app()->baseUrl.'/css/admin/main/right/pack_items.css');
		$shops_classliy=ShopsClassliy::getClass();
		$model=$this->loadModel($id,array(
			'with'=>array(
				'Dot_Shops'=>array('with'=>array('Shops_Agent')),
				'Dot_Pro'=>array('with'=>array(
						'Pro_Items'=>array('with'=>array(
							'Items_area_id_p_Area_id',
							'Items_area_id_m_Area_id',
							'Items_area_id_c_Area_id',
							'Items_ItemsImg',
							'Items_StoreContent'=>array('with'=>array('Content_Store')),
							'Items_Store_Manager',
							'Items_Fare',
						)),				
						'Pro_ItemsClassliy',
				)),				
			),
			'condition'=>'t.c_id=:c_id AND Dot_Shops.status=0 AND Dot_Shops.audit=:audit',
			'params'=>array(':c_id'=>$shops_classliy->id,':audit'=>Shops::audit_pass),
			'order'=>'Dot_Pro.sort',
		));
		$model->Dot_Shops->scenario='pack_dot';
		$this->set_scenarios($model->Dot_Pro, 'pack_dot');
		//ajax验证
		$this->_Ajax_Verify_Same(array_merge(array($model->Dot_Shops),$model->Dot_Pro),'dot-pack-form');
		
		if(isset($_POST['Shops']) && isset($_POST['Pro']) && count($_POST['Pro'])==count($model->Dot_Pro))
		{
			$this->_upload=Yii::app()->params['uploads_shops_dot'];
			//获得需要的上传图片
			$uploads = array('list_img','page_img');
			//保存原来的
			$data    = $this->upload_save_data($model->Dot_Shops, $uploads);
			//过滤空白的值
			$data=array_filter($data);
			//获得参数
			$model->Dot_Shops->attributes=$_POST['Shops'];
			//过滤 数据id
			$ids=$this->array_listData($_POST['Pro'],'id');
			if(!empty($ids))
				$ids=Pro::filter_pro($ids,$id);
			//过滤 数据info
			$infos=array_filter($this->array_listData($_POST['Pro'],'info'));//过滤空白的值
			//验证是否为合法参数
			if(count($model->Dot_Pro)==count($ids) && count($infos)==count($model->Dot_Pro))				
				$pro_validate=true;
			else
				$pro_validate=false;		
			//获取上传的
			$files   = $this->upload($model->Dot_Shops,$uploads);
			//看看是修改 还是创建
			if(!empty($data))
				$shop_validate_img=true;
			else
				$shop_validate_img=$this->upload_error($model->Dot_Shops, $files, $uploads);
			//验证是否为合法参数
			if($shop_validate_img)
				$shop_validate = $model->Dot_Shops->validate();	
			else 
				$shop_validate=false;
			//提前验证
			if($pro_validate && $shop_validate && $shop_validate_img)
			{
				if(!empty($data))
					//没有上传的赋值
					$old_path=$this->upload_update_data($model->Dot_Shops, $data, $files);
				
				$transaction=$model->dbConnection->beginTransaction();
				try{
					if($model->Dot_Shops->save(false)){
						if(!empty($data))
						{
							//保存新的
							$this->upload_save($model->Dot_Shops, $files,true,4,array('pc','app','share'));
							//删除原来的
							$this->upload_delete($old_path);
						}else
							$this->upload_save($model->Dot_Shops, $files,true,4,array('pc','app','share'));
						
						$pro_array=array();
						foreach ($ids as $key=>$id)
							$pro_array[$id]=array('info'=>$infos[$key],'sort'=>$key);
						foreach ($model->Dot_Pro as $Dot_Pro)
						{
							$pro=$pro_array[$Dot_Pro->id];
							$Dot_Pro->info=$pro['info'];
							$Dot_Pro->sort=$pro['sort'];
							if(! $Dot_Pro->save())
								throw new Exception("包装线路(点)保存选择项目记录错误");
						}
					}else
						throw new Exception("包装线路(点)保存主记录错误");
					$return=$this->log('包装线路(点)',ManageLog::admin,ManageLog::update);
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

		$this->render('pack',array('model'=>$model,));
	}
}
