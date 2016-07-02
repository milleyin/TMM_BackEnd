<?php
/**
 * 
 * @author Changhai Zhan
 *	创建时间：2015-10-23 14:17:44 */
class Tmm_activesController extends MainController
{
	/**
	 * 默认操作数据模型
	 * @var string
	 */
	public $_class_model='Actives';

	/**
	 * 新的统计数量
	 * @var unknown
	 */
	public $_new_number=array();

	public $_organizer_id='';
	/**
	 * 活动服务费
	 * @var float
	 */
	public $_tour_price   = 0.00;
	/**
	 * 是否组织者 默认是组织者
	 * @var int
	 * @update 2016-01-28
	 */
	public $_is_organizer = Actives::is_organizer_yes;
	/**
	 * 查看详情
	 * @param integer $id
	 */
	public function actionView($id)
	{
		$this->addCss(Yii::app()->baseUrl.'/css/admin/main/right/group_items.css');
		$c_id=ShopsClassliy::getClass()->id;
		$model = $this->loadModel($id,array(
			'with'=>$this->actives_model_with(),
			'condition'=>'`t`.`c_id`=:c_id ',
			'params'=>array(':c_id'=>$c_id),
			'order'=>'Actives_Pro.day_sort,Actives_Pro.half_sort,Actives_Pro.sort',
		));
		//$this->p_r($model);exit;
		$model->Actives_TagsElement=TagsElement::get_select_tags(TagsElement::tags_shops_actives,$id);
		$this->render('view',array(
			'model'=>$model,
		));
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
			'with'=>array('Actives_Shops'),
			'condition'=>'t.c_id=:c_id AND Actives_Shops.status=:status AND Actives_Shops.audit=:audit',
			'params'=>array(':c_id'=>$shops_classliy->id,':status'=>Shops::status_offline,':audit'=>Shops::audit_pending),
		));
		$model->scenario='pass';
		$this->_Ajax_Verify($model,'actives-form');

		if(isset($_POST['Actives'])) {
			$model->attributes=$_POST['Actives'];

			$model->Actives_Shops->pub_time = time();
			$model->Actives_Shops->audit = Shops::audit_pass;// 审核通过
			$transaction = $model->dbConnection->beginTransaction();
			if($model->validate()) {
				try {
					$model->barcode = OrderItems::get_barcode($model->id);
					if(!$model->save(false))
						throw new Exception("线路(活动)审核通过保存分成错误");

					if ($model->Actives_Shops->save(false)) {
						$audit = new AuditLog;
						$audit->info = $model->Actives_Shops->name;
						$audit->audit_who = AuditLog::admin;//记录 审核人 （审核人的id 在模型中）
						$audit->audit_element = AuditLog::shops_actives;//记录 被审核的类型
						$audit->element_id = $model->id;//记录 被审核id
						$audit->audit = AuditLog::pass;//记录 审核通过
						if ($audit->save(false))
							$return = $this->log('添加审核线路(活动)记录', ManageLog::admin, ManageLog::create);
						else
							throw new Exception("线路(活动)添加审核日志错误");
					} else
						throw new Exception("线路(活动)审核通过保存错误");
					$transaction->commit();
				} catch (Exception $e) {
					$transaction->rollBack();
					$this->error_log($e->getMessage(), ErrorLog::admin, ErrorLog::create, ErrorLog::rollback, __METHOD__);
				}
			}
			if(isset($return))
				$this->back();
//
//			if (isset($return))
//				echo 1;
//			else
//				echo '审核通过失败！';
		}

		$this->render('/tmm_actives/_pass',array(
			'model'=>$model,
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
		$shops_classliy=ShopsClassliy::getClass();
		//查看是否需要审核
		$model->Audit_Actives=$this->loadModel($id,array(
			'with'=>array('Actives_Shops'),
			'condition'=>'t.c_id=:c_id AND Actives_Shops.status=:status AND Actives_Shops.audit=:audit',
			'params'=>array(':c_id'=>$shops_classliy->id,':status'=>Shops::status_offline,':audit'=>Shops::audit_pending),
		));
		$this->_Ajax_Verify($model,'audit-log-form');

		if(isset($_POST['AuditLog']))
		{
			$model->attributes=$_POST['AuditLog'];
			$model->audit_who=AuditLog::admin;//记录 审核人 （审核人的id 在模型中）
			$model->audit_element=AuditLog::shops_actives;//记录 被审核的
			$model->element_id=$model->Audit_Actives->id;//记录 被审核id
			$model->audit=AuditLog::nopass;//记录 审核不通过
			if($model->validate()){
				$transaction=$model->dbConnection->beginTransaction();
				try
				{
					if($model->save(false))
					{
						$model->Audit_Actives->Actives_Shops->audit=Shops::audit_nopass;//审核不通过
						if($model->Audit_Actives->Actives_Shops->save(false))
							$return=$this->log('线路(活动)审核不通过记录',ManageLog::admin,ManageLog::create);
					}else
						throw new Exception("添加线路(活动)审核不通过日志错误");

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
				'with'=>array('Actives_Shops'),
				'condition'=>'Actives_Shops.status=0 AND Actives_Shops.audit !=:audit AND Actives_Shops.c_id=:c_id',
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
				'with'=>array('Actives_Shops'),
				'condition'=>'Actives_Shops.status=0 AND Actives_Shops.audit !=:audit AND Actives_Shops.c_id=:c_id',
				'params'=>array(':audit'=>Shops::audit_pending,':c_id'=>ShopsClassliy::getClass()->id),
			));
			$tags_ids=Tags::filter_tags($_POST['tag_ids']);//安全过滤tags id
			if($type=='yes'){
				//过滤之前有的
				$save_tags_id=TagsElement::not_select_tags_element($tags_ids,$id,TagsElement::tags_shops_actives);
				$return=TagsElement::select_tags_ids_save($save_tags_id, $id, TagsElement::tags_shops_actives);
			}else
				$return=TagsElement::select_tags_ids_delete($tags_ids, $id, TagsElement::tags_shops_actives);
			if($return){
				if($type=='yes')
					$this->log('线路(活动)添加标签', ManageLog::admin,ManageLog::create);
				else
					$this->log('线路(活动)去除标签', ManageLog::admin,ManageLog::clear);
				echo 1;
			}else
				echo '操作过于频繁，请刷新页面从新选择！';
		}else
			echo '没有选中标签，请重新选择标签！';
	}


	/**
	 * 垃圾回收页
	 */
	public function actionIndex()
	{
		$criteria=new CDbCriteria;
		$criteria->with=array(
			'Actives_ShopsClassliy',
			'Actives_Shops'=>array('with'=>array('Shops_Agent')),
		);
		$criteria->addColumnCondition(array('Actives_Shops.status'=>-1));

		$model=new Actives;
		
		$this->render('index',array(
			'model'=>$model->search($criteria),
		));
	}
	
	/**
	 *管理页
	 */
	public function actionAdmin()
	{
		$model=new Actives('search');
		$model->Actives_Shops=new Shops('search');

		$model->unsetAttributes();  // 删除默认属性
		$model->Actives_Shops->unsetAttributes();  // 删除默认属性

		if(isset($_GET['Actives']))
			$model->attributes=$_GET['Actives'];

		if(isset($_GET['Shops']))
			$model->Actives_Shops->attributes=$_GET['Shops'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}
	
	/**
	 * 下线
	 * @param integer $id
	 */
	public function actionDisable($id){

		//获取项目类型
		$c_id=ShopsClassliy::getClass()->id;
		$this->_class_model='Shops';
		if($this->loadModel($id,'`status`=1 AND `c_id`=:c_id',array(':c_id'=>$c_id))->updateByPk($id,array('status'=>0)))
			$this->log('下线线路(旅游活动)',ManageLog::admin,ManageLog::update);
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));


//
//
//
//		$transaction = Yii::app ()->db->beginTransaction ();
//		try {
//			if($this->loadModel($id,'`status`=1')->updateByPk($id,array('status'=>0)))
//				$this->log('下线线路(旅游活动) 活动表更新成功',ManageLog::admin,ManageLog::update);
//			else
//				throw new Exception ( '下线线路(旅游活动) 活动表更新失败' );
//
//			//获取旅游活动商品类型
//			$c_id=ShopsClassliy::getClass()->id;
//			$this->_class_model='Shops';
//
//			if($this->loadModel($id,'`status`=1 AND `c_id`=:c_id',array(':c_id'=>$c_id))->updateByPk($id,array('status'=>0)))
//				$this->log('下线线路(旅游活动) 活动商品表更新成功',ManageLog::admin,ManageLog::update);
//			else
//				throw new Exception ( '下线线路(旅游活动) 活动商品表更新失败' );
//
//			$transaction->commit ();
//		} catch ( Exception $e ) {
//			//事务回滚
//			$transaction->rollBack();
//			$this->error_log($e->getMessage(),ErrorLog::admin,ErrorLog::create,ErrorLog::rollback,__METHOD__);
//		}
//
//		if(!isset($_GET['ajax']))
//			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
			
	}
	
	/**
	 * 上线
	 * @param integer $id
	 */
	public function actionStart($id)
	{
		exec(Yii::app()->basePath.'/./yiic actives index');

// 		$exec=exec(Yii::app()->basePath.'/./yiic actives index',$output,$status);
// 		if(isset($exec))
// 			$this->error_log($exec,ErrorLog::admin,ErrorLog::create,ErrorLog::debugs,__METHOD__);
// 		if(isset($output) && $output)
// 			$this->error_log(implode(',', $output),ErrorLog::admin,ErrorLog::create,ErrorLog::debugs,__METHOD__);
// 		if(isset($status))
// 			$this->error_log($status,ErrorLog::admin,ErrorLog::create,ErrorLog::debugs,__METHOD__);
		
		//获取项目类型
		$c_id=ShopsClassliy::getClass()->id;//点
		$this->_class_model='Shops';
		if($this->loadModel($id,'`status`=0 AND `c_id`=:c_id AND `audit`=:audit AND `list_info`!=\'\'',array(':c_id'=>$c_id,':audit'=>Shops::audit_pass))->updateByPk($id,array('status'=>1)))
			$this->log('上线线路(旅游活动)',ManageLog::admin,ManageLog::update);
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));


//		$transaction = Yii::app ()->db->beginTransaction ();
//		try {
//			if($this->loadModel($id,'`status`=0')->updateByPk($id,array('status'=>1)))
//				$this->log('上线线路(旅游活动) 活动表更新成功',ManageLog::admin,ManageLog::update);
//			else
//				throw new Exception ( '上线线路(旅游活动) 活动表更新失败' );
//
//			//获取旅游活动商品类型
//			$c_id=ShopsClassliy::getClass()->id;
//			$this->_class_model='Shops';
//
//			if($this->loadModel($id,'list_info !=\'\' AND `status`=0 AND `c_id`=:c_id AND `audit`=:audit',array(':c_id'=>$c_id,':audit'=>Shops::audit_pass))->updateByPk($id,array('status'=>1)))
//				$this->log('上线线路(旅游活动) 活动商品表更新成功',ManageLog::admin,ManageLog::update);
//			else
//				throw new Exception ( '上线线路(旅游活动) 活动商品表更新失败' );
//
//			$transaction->commit ();
//		} catch ( Exception $e ) {
//			//事务回滚
//			$transaction->rollBack();
//			$this->error_log($e->getMessage(),ErrorLog::admin,ErrorLog::create,ErrorLog::rollback,__METHOD__);
//		}
//
//		if(!isset($_GET['ajax']))
//			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));

	}

// 	/**
// 	 * 删除
// 	 * @param integer $id
// 	 */
// 	public function actionDelete($id)
// 	{

// 		//获取项目类型
// 		$c_id=ShopsClassliy::getClass()->id;//点
// 		$this->_class_model='Shops';
// 		if($this->loadModel($id,'`status`=0 AND `c_id`=:c_id',array(':c_id'=>$c_id))->updateByPk($id,array('status'=>-1)))
// 			$this->log('删除线路(旅游活动)',ManageLog::admin,ManageLog::delete);

// 		if(!isset($_GET['ajax']))
// 			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));



// //		$transaction = Yii::app ()->db->beginTransaction ();
// //		try {
// //			if($this->loadModel($id,'`status`=0')->updateByPk($id,array('status'=>-1)))
// //				$this->log('删除线路(旅游活动) 活动表更新成功',ManageLog::admin,ManageLog::update);
// //			else
// //				throw new Exception ( '删除线路(旅游活动) 活动表更新失败' );
// //
// //			//获取旅游活动商品类型
// //			$c_id=ShopsClassliy::getClass()->id;
// //			$this->_class_model='Shops';
// //
// //			if($this->loadModel($id,'`status`=0 AND `c_id`=:c_id',array(':c_id'=>$c_id))->updateByPk($id,array('status'=>-1)))
// //				$this->log('删除线路(旅游活动) 活动商品表更新成功',ManageLog::admin,ManageLog::update);
// //			else
// //				throw new Exception ( '删除线路(旅游活动) 活动商品表更新失败' );
// //
// //			$transaction->commit ();
// //		} catch ( Exception $e ) {
// //			//事务回滚
// //			$transaction->rollBack();
// //			$this->error_log($e->getMessage(),ErrorLog::admin,ErrorLog::create,ErrorLog::rollback,__METHOD__);
// //		}
// //
// //		if(!isset($_GET['ajax']))
// //			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));

// 	}

// 	/**
// 	 * 还原
// 	 * @param integer $id
// 	 */
// 	public function actionRestore($id)
// 	{
// 		//获取项目类型
// 		$c_id=ShopsClassliy::getClass()->id;//点
// 		$this->_class_model='Shops';
// 		if($this->loadModel($id,'`status`=-1 AND `c_id`=:c_id',array(':c_id'=>$c_id))->updateByPk($id,array('status'=>0)))
// 			$this->log('还原线路(旅游活动)',ManageLog::admin,ManageLog::update);

// 		if(!isset($_GET['ajax']))
// 			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
// //
// //
// //		$transaction = Yii::app ()->db->beginTransaction ();
// //		try {
// //			if($this->loadModel($id,'`status`=-1')->updateByPk($id,array('status'=>0)))
// //				$this->log('还原线路(旅游活动) 活动表更新成功',ManageLog::admin,ManageLog::update);
// //			else
// //				throw new Exception ( '还原线路(旅游活动) 活动表更新失败' );
// //
// //			//获取旅游活动商品类型
// //			$c_id=ShopsClassliy::getClass()->id;
// //			$this->_class_model='Shops';
// //
// //			if($this->loadModel($id,'`status`=-1 AND `c_id`=:c_id',array(':c_id'=>$c_id))->updateByPk($id,array('status'=>0)))
// //				$this->log('还原线路(旅游活动) 活动商品表更新成功',ManageLog::admin,ManageLog::update);
// //			else
// //				throw new Exception ( '还原线路(旅游活动) 活动商品表更新失败' );
// //
// //			$transaction->commit ();
// //		} catch ( Exception $e ) {
// //			//事务回滚
// //			$transaction->rollBack();
// //			$this->error_log($e->getMessage(),ErrorLog::admin,ErrorLog::create,ErrorLog::rollback,__METHOD__);
// //		}
// //
// //		if(!isset($_GET['ajax']))
// //			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));

// 	}

	/**
	 * 包装活动
	 * @param $id
	 * @throws CHttpException
	 */
	public function actionPack($id){

		$this->addCss(Yii::app()->baseUrl.'/css/admin/main/right/group_items.css');

		$shops_classliy=ShopsClassliy::getClass();
		$model=$this->loadModel($id,array(
			'with'=>$this->actives_model_with(),
			'condition'=>'t.c_id=:c_id AND Actives_Shops.status=:status AND Actives_Shops.audit=:audit',
			'params'=>array(':c_id'=>$shops_classliy->id,':status'=>Shops::status_offline,':audit'=>Shops::audit_pass),
			'order'=>'Actives_Pro.day_sort,Actives_Pro.half_sort,Actives_Pro.sort',
		));

		$model->Actives_Shops->scenario='pack_actives';
		$this->set_scenarios($model->Actives_Pro, 'pack_actives');
		$Verify_array=array();
		foreach($model->Actives_Pro as $Pro)
		{
			if(! isset($array[$Pro->day_sort]) && $Pro->half_sort==0 && $Pro->sort==0)
			{
				$array[$Pro->day_sort]=$Pro->half_sort;
				$Verify_array[]=$Pro;
			}
		}

		//ajax验证
		$this->_Ajax_Verify_Same(array_merge(array($model->Actives_Shops),$Verify_array),'actives-pack-form');

		if(isset($_POST['Shops']) && isset($_POST['Pro']) && count($Verify_array)==count($_POST['Pro']))
		{
			$this->_upload=Yii::app()->params['uploads_shops_actives'];
			//获得需要的上传图片
			$uploads = array('list_img','page_img');
			//保存原来的
			$data    = $this->upload_save_data($model->Actives_Shops, $uploads);
			//过滤空白的值
			$data=array_filter($data);
			//获得参数
			$model->Actives_Shops->attributes=$_POST['Shops'];
			//过滤 数据id
			$ids=$this->array_listData($_POST['Pro'],'id');
			if(!empty($ids))
				$ids=Pro::filter_pro($ids,$id,true,'t.day_sort,t.half_sort,t.sort');
			//过滤 数据info
			$infos=array_filter($this->array_listData($_POST['Pro'],'info'));//过滤空白的值
			//验证是否为合法参数
			if(count($infos)==count($ids) && count($infos)<=count($model->Actives_Pro))
				$pro_validate=true;
			else
				$pro_validate=false;
			//获取上传的
			$files   = $this->upload($model->Actives_Shops,$uploads);
			//看看是修改 还是创建
			if(!empty($data))
				$shop_validate_img=true;
			else
				$shop_validate_img=$this->upload_error($model->Actives_Shops, $files, $uploads);
			//验证是否为合法参数
			if($shop_validate_img)
				$shop_validate = $model->Actives_Shops->validate();
			else
				$shop_validate = false;

			//提前验证
			if($pro_validate && $shop_validate && $shop_validate_img)
			{
				if(!empty($data))
					//还原赋值
					$old_path=$this->upload_update_data($model->Actives_Shops, $data, $files);

				$transaction=$model->dbConnection->beginTransaction();
				try{
					if($model->Actives_Shops->save(false))
					{
						if(!empty($data))
						{
							//保存新的
							$this->upload_save($model->Actives_Shops, $files,true,4,array('pc','app','share'));
							//删除原来的
							$this->upload_delete($old_path);
						}else
							$this->upload_save($model->Actives_Shops, $files,true,4,array('pc','app','share'));

						$pro_array=array();
						foreach ($ids as $key=>$id)
							$pro_array[$id]=array('info'=>$infos[$key]);
						$save=0;
						foreach ($model->Actives_Pro as $Actives_Pro)
						{
							if(isset($pro_array[$Actives_Pro->id]) && $Actives_Pro->half_sort==0 &&  $Actives_Pro->sort==0) {
								$Actives_Pro->info = $pro_array[$Actives_Pro->id]['info'];
								if (!$Actives_Pro->save())
									throw new Exception("包装线路(活动)保存选择项目记录错误");
								else
									$save++;
							}
						}
						if(count($Verify_array) != $save)
							throw new Exception("包装线路(活动)保存选择项目简介个数错误");
					}else
						throw new Exception("包装线路(活动)保存主记录错误");

					if(! $this->order_organizer($model->id) )
						throw new Exception("包装线路(活动)迁移数据记录错误");

					$return=$this->log('包装线路(活动)',ManageLog::admin,ManageLog::update);

					$transaction->commit();
				}
				catch(Exception $e)
				{
					$transaction->rollBack();
					$this->error_log($e->getMessage(),ErrorLog::admin,ErrorLog::create,ErrorLog::rollback,__METHOD__);
				}

				if(isset($return))
					$this->back();
				else
					$model->Actives_Shops->addError('page_info', '包装保存错误');
			}
		}

		$this->render('pack',array('model'=>$model,));
	}

	/**
	 * 包装通过后处理
	 * @param $id
	 * @return bool
	 */
	public function order_organizer($id){

		//判断包装完后是否已搬数据
		$order_actives_status = OrderActives::model()->find('actives_id = '.$id);

		if($order_actives_status)
			return true;
		//获得c_id
		$shops_classliy=ShopsClassliy::getClass();
		//活动为点
		$model=$this->loadModel($id,array(
			'with'=>$this->actives_model_with(),
			'condition'=>'t.c_id=:c_id  AND `Dot_Shops`.`audit`=:audit ',
			'params'=>array(':c_id'=>$shops_classliy->id,':audit'=>Shops::audit_pass),
			'order'=>'`Actives_Pro`.`day_sort`,`Actives_Pro`.`half_sort`,`Actives_Pro`.`sort`',
		));

		//$transaction=$model->dbConnection->beginTransaction();
		//try{
			//更新结伴游表团状态
			$actives_model = Actives::model()->findByPk($id);
			$actives_model->actives_status  = Actives::actives_status_not_start;
			$actives_model->status			  = Actives::status_publishing;
			if(! $actives_model->save(false))
				throw new Exception("线路(活动) 更改(活动状态失败)");
			//订单====代理商表
			$model_order_actives = $this->set_order_actives($model);
			if(! $model_order_actives->save(false))
				throw new Exception("线路(活动) 保存(订单活动详情表失败)");
			//活动单号
			$model_order_actives->actives_no=OrderActives::get_actives_no($model_order_actives->id,$model->actives_type);		
			if(! $model_order_actives->save(false))
				throw new Exception("线路(活动) 保存(订单活动详情表失败)");
			
			//订单活动表  ID
			$order_actives_id = $model_order_actives->id;
			//复制商品表    ID
			$order_shops_id=$model->id;
			//需要ID集合
			$arr_id['order_actives_id'] 	 = $order_actives_id;
			$arr_id['order_shops_id']     = $order_shops_id;
			//复制项目表
			foreach($model->Actives_Pro as $k=>$val) {
				$model_order_items = $this->set_order_items($model,$val,$arr_id);
				if(! $model_order_items->save(false))
					throw new Exception("线路(活动) 保存(复制项目表失败)".$k );

				$model_order_items->barcode			= OrderItems::get_barcode($model_order_items->id);
				if(! $model_order_items->save(false))
					throw new Exception("线路(活动) 保存(复制项目表失败barcode)".$k );

				//复制项目表   ID
				$order_items_id=$model_order_items->id;
				$arr_id['order_items_id'] = $order_items_id;
				//复制价格表
				foreach($val->Pro_ProFare as $ke=>$fare) {
					$model_order_fare = $this->set_order_items_fare($model,$val,$fare,$arr_id);
					if(! $model_order_fare->save(false))
						throw new Exception("线路(活动) 保存(复制价格表失败)".$ke );
					else
						$return = 1;
				}
			}
		//	$transaction->commit();
	//		$return = 1;
	//	}catch(Exception $e){
			//事务回滚
	//		$transaction->rollBack();
	//		$this->error_log($e->getMessage(),ErrorLog::admin,ErrorLog::create,ErrorLog::rollback,__METHOD__);
	//	}
		if(isset($return) && $return==1)
			return true;
		else
			return false;
	}

	/**
	 * 复制订单活动表
	 * tmm_order_actives
	 * @param $model
	 * @return Actives
	 */

	public function set_order_actives($model){
		$OrderActives = new OrderActives();

		$OrderActives->actives_no 				='';// '活动单号',
		$OrderActives->organizer_id			= $model->organizer_id;// '代理商id',
		$OrderActives->actives_id				= $model->id;//'活动商品id',
		$OrderActives->actives_type			= $model->actives_type;//'0、1 活动分类',
		$OrderActives->user_price				= $model->tour_price;//'实际服务费用',

//		$OrderActives->user_order_count		= '';// '用户下单数量',
//		$OrderActives->user_pay_count			= '';//'用户支付数量',
//		$OrderActives->user_submit_count		= '';//'用户确认出游数量',
//		$OrderActives->user_price				= '';// '实际服务费用',
//		$OrderActives->user_go_count			= '';// '用户出游人数量',
//		$OrderActives->user_price_count		= '';// '下单总额',
//		$OrderActives->total					= '';// '总计总额',
//		$OrderActives->add_time				= '';// '创建时间(下单时间)',
//		$OrderActives->up_time					= '';//'更新时间',
		$OrderActives->status					= 1;// '状态0禁用1启用-1删除',

		return $OrderActives;

	}
	/**
	 * 复制项目表
	 * @param unknown $data
	 * @param unknown $type
	 * @param unknown $params
	 */
	public function set_order_items($model,$val,$arr_id)
	{
		$OrderItems=new OrderItems;

		$OrderItems->organizer_id 	= $model->organizer_id;
		$OrderItems->order_organizer_id = $arr_id['order_actives_id'];
		//$OrderItems->user_id 			= $model->organizer_id;
		$OrderItems->order_shops_id 	= $arr_id['order_shops_id'];
		$OrderItems->store_id        	= $val->Pro_Items->store_id;
		$OrderItems->manager_id      	= $val->Pro_Items->store_id;
		$OrderItems->agent_id      	= $val->Pro_Items->agent_id;

		$OrderItems->shops_id    	 	= $model->Actives_Shops->id;
		$OrderItems->shops_name   	 	= $val->Pro_Actives_Dot->Dot_Shops->name;
		$OrderItems->shops_c_id   	 	=  $model->Actives_ShopsClassliy->id;
		$OrderItems->shops_c_name  	= $model->Actives_ShopsClassliy->name;

		$OrderItems->items_id 	    	= $val->Pro_Items->id;
		$OrderItems->items_c_id	   	= $val->Pro_Items->Items_ItemsClassliy->id;
		$OrderItems->items_c_name 	= $val->Pro_Items->Items_ItemsClassliy->name;
		$OrderItems->items_name   		= $val->Pro_Items->name;
		$OrderItems->items_address	= $val->Pro_Items->Items_area_id_p_Area_id->name.$val->Pro_Items->Items_area_id_m_Area_id->name.$val->Pro_Items->Items_area_id_c_Area_id->name.$val->Pro_Items->address;

		$OrderItems->items_push         	= Push::executed($val->Pro_Items->id,'push');
		$OrderItems->items_push_orgainzer= $val->Pro_Items->store_id;
		$OrderItems->items_push_orgainzer= Push::executed($val->Pro_Items->id,'push_orgainzer');
		$OrderItems->items_push_store     = Push::executed($val->Pro_Items->id,'push_store');
		$OrderItems->items_push_agent     = Push::executed($val->Pro_Items->id,'push_agent');

		$OrderItems->items_map			= $val->Pro_Items->map;
		$OrderItems->items_phone		= $val->Pro_Items->phone;
		$OrderItems->items_weixin		= $val->Pro_Items->weixin;
		$OrderItems->items_content	= $val->Pro_Items->content;
		$OrderItems->items_img        	= isset($val->Pro_Items->Items_ItemsImg[0]->img) && $val->Pro_Items->Items_ItemsImg[0]->img ? $val->Pro_Items->Items_ItemsImg[0]->img:'';
		$OrderItems->items_start_work	= $val->Pro_Items->start_work;
		$OrderItems->items_end_work 	= $val->Pro_Items->end_work;
		$OrderItems->items_up_time   	= $val->Pro_Items->up_time;
		$OrderItems->items_pub_time  	= $val->Pro_Items->pub_time;

		$OrderItems->shops_sort		= $val->sort;
		$OrderItems->shops_day_sort	= $val->day_sort;
		$OrderItems->shops_half_sort	= $val->half_sort;
		$OrderItems->shops_dot_id		= $val->dot_id;
		$OrderItems->shops_thrand_id	= $val->thrand_id;
		$OrderItems->shops_info		= $val->info;
		$OrderItems->shops_up_time	= $val->up_time;
		$OrderItems->shops_pub_time	= $val->up_time;

		$OrderItems->is_shops			= $OrderItems::is_shops_store_yes;
		$OrderItems->is_barcode		= $OrderItems::is_barcode_invalid;

		$OrderItems->items_lng			= $val->Pro_Items->lng;
		$OrderItems->items_lat			= $val->Pro_Items->lat;
		$OrderItems->items_free_status= $val->Pro_Items->free_status;

		return $OrderItems;
	}

	/**
	 * 复制项目价格表
	 * @param unknown $data
	 * @param unknown $params
	 */
	public function set_order_items_fare($model,$val,$fare,$arr_id)
	{
		$OrderItemsFare =new OrderItemsFare;

		$OrderItemsFare->order_items_id 		= $arr_id['order_items_id'];
		$OrderItemsFare->organizer_id  		= $model->organizer_id;
		$OrderItemsFare->order_organizer_id 	= $arr_id['order_actives_id'];
		//$OrderItemsFare->user_id  			= $model->organizer_id;
		//$OrderItemsFare->order_shops_id   = $arr_id['order_shops_id'];

		$OrderItemsFare->store_id   	= $val->Pro_Items->store_id;
		$OrderItemsFare->manager_id   	= $val->Pro_Items->manager_id;

		$OrderItemsFare->shops_id     	= $model->Actives_Shops->id;
		$OrderItemsFare->shops_c_id   	= $model->Actives_ShopsClassliy->id;
		$OrderItemsFare->agent_id     	= $val->Pro_Items->agent_id;

		$OrderItemsFare->items_id     	= $val->Pro_Items->id;
		$OrderItemsFare->items_c_id   	= $val->Pro_Items->Items_ItemsClassliy->id;

		$OrderItemsFare->fare_id		= $fare->ProFare_Fare->id;
		$OrderItemsFare->fare_name		= $fare->ProFare_Fare->name;
		$OrderItemsFare->fare_info		= $fare->ProFare_Fare->info;
		$OrderItemsFare->fare_number	= $fare->ProFare_Fare->number;
		$OrderItemsFare->fare_price	= $fare->ProFare_Fare->price;
		$OrderItemsFare->fare_up_time	= $fare->ProFare_Fare->up_time;
		$OrderItemsFare->price			= $fare->ProFare_Fare->price;

		return $OrderItemsFare;

	}
	/**
	 * 活动公用 model   with
	 * @return array
	 */
	private function actives_model_with(){
		return array(
			'Actives_ShopsClassliy',
			'Actives_Shops'=>array('with'=>array('Shops_Agent')),
			'Actives_User',
			'Actives_Pro'=>array('with'=>array(
				'Pro_Actives_Dot'=>array('with'=>'Dot_Shops'),
				'Pro_Actives_Thrand'=>array('with'=>'Thrand_Shops'),
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
		);
	}

	//==================================2015-12-23 觅趣 创建 编辑==================================================================================
	/**
	 * 添加========农产品
	 * @param $id
	 */
	public function actionFarm_create($id){

		echo '添加农产品'."</br>";
		echo $id.'farm';
		exit;
	}

	/**
	 * 添加=======多个点
	 * @param $id
	 */
	public function actionDot_create_bak($id){
		echo $id.'dot';
		exit;
	}

	/**
	 * 添加=========完整的线
	 * @param $id
	 */
	public function actionThrand_create_bak($id){
		echo $id.'thrand';
		exit;
	}
	//==================================2015-12-23 觅趣 多个点 创建 编辑==================================================================================
	/**
	 * 创建
	 */
	public function actionDot_create($id)
	{
		$this->addCss(Yii::app()->request->baseUrl . '/css/admin/thrand/bootstrap.min.css');
		$this->addCss(Yii::app()->request->baseUrl . '/css/admin/main/right/screen.css');
		$this->addCss(Yii::app()->request->baseUrl . '/css/admin/thrand/step.css');
		$this->addCss(Yii::app()->request->baseUrl . '/css/admin/thrand/form.css');
		$this->addCss(Yii::app()->request->baseUrl . '/css/admin/thrand/box.css');

		$shops_classliy = ShopsClassliy::getClass();

		$model=new Actives();//觅趣的主要表
		$model->scenario='create';
		$model->Actives_Shops=new Shops;//商品表
		$model->Actives_Shops->scenario='create_actives';

		$this->_class_model='User';
		//$model->Actives_Organizer=$this->loadModel($id,'status=1');
		$model->Actives_User = $this->loadModel($id,'is_organizer=1 AND audit=:audit AND  status=1 AND  id='.$id,array(':audit'=>User::audit_pass));

		//$model->Actives_User = $this->loadModel($id,'  status=1 AND  id='.$id);

		$Pro=new Pro;//选中点的选中项目表
		$Pro->scenario='create_actives';
		$ProFare=new ProFare;//选中项目选中的价格表
		$ProFare->scenario='create_actives';

		$Pro->Pro_ProFare=array($ProFare);//关系赋值 一个项目可以选多个价格 默认一个
		$model->Actives_Pro=array($Pro);//一条线可以选择多个点中的项目 默认一个

		$this->_Ajax_Verify(array($model,$model->Actives_Shops),'actives-form');
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

		if(isset($_POST['Actives']) && isset($_POST['Shops']) && isset($_POST['Pro']) && is_array($_POST['Pro']) && isset($_POST['ProFare']) && is_array($_POST['ProFare']) && count($_POST['ProFare'])==count($_POST['Pro']))
		{
			//$this->p_r($_POST);
			//exit;
			if($this->validate_actives($model, $shops_classliy->id))
			{
				$model->scenario='create';
				$model->attributes=$_POST['Actives'];
				//提前验证
				$validate_shops=$model->Actives_Shops->validate();
				$validate_pros_fares=true;
				foreach ($model->Actives_Pro as $pro)
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
						$model->Actives_Shops->c_id		= $shops_classliy->id;
						$model->Actives_Shops->status	= Shops::status_offline;
						$model->Actives_Shops->audit		= Shops::audit_pending;
						if($model->Actives_Shops->save(false))
						{
							$model->id					= $model->Actives_Shops->id;
							$model->c_id				= $shops_classliy->id;
							$model->organizer_id    	= $id;           //组织者
							$model->actives_status  	= Actives::actives_status_not_start;   //活动状态
							$model->start_time 	  	= isset($model->start_time)?strtotime($model->start_time):time();              		//开始时间
							$model->end_time    	  	= strtotime($model->end_time); //结止时间
							$model->go_time    	  	= isset($model->go_time) && $model->go_time ?strtotime($model->go_time):0; 			//活动时间
							$model->status     	 	= Actives::status_not_publish;//表示旅游活动没有成订单 下线状态
							$model->order_number 	 	= $model->number; //剩余数量
							$model->remark       		= $this->admin_img_replace($model->remark);//处理图片链接
							if(! $model->save(false))
								throw new Exception("创建线路(旅游活动多点) 保存线路附表错误");

							$dot_ids=array();
							foreach ($model->Actives_Pro as $pro_save)
							{
								$dot_ids[]=$pro_save->dot_id;
								$pro_save->shops_id=$model->id;
								if(! $pro_save->save(false))
									throw new Exception("创建线路(旅游活动多点) 保存选中项目表错误");
								foreach ($pro_save->Pro_ProFare as $fare_save)
								{
									$fare_save->pro_id=$pro_save->id;
									$fare_save->thrand_id=$model->id;
									if(! $fare_save->save(false))
										throw new Exception("创建线路(旅游活动多点) 保存选中项目的选中价格表错误");
								}
							}
							//echo 1;exit;
							//继承点的tags
							foreach ($dot_ids as $dot_id)
								$element_ids[]=array(TagsElement::tags_shops_dot,$dot_id);

							TagsElement::select_tags_ids_save(TagsElement::select_tags_all($element_ids), $model->Actives_Shops->id, TagsElement::tags_shops_actives,TagsElement::admin);

							//日志
							$return=$this->log('创建线路(旅游活动多点)',ManageLog::admin,ManageLog::create);
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

		$this->render('dot_create',array(
			'model'=>$model,
			'search_model'=>$this->search_dot(),
		));
	}

	/**
	 * 更新
	 * @param integer $id
	 */
	public function actionDot_update($id)
	{
		$this->addCss(Yii::app()->request->baseUrl . '/css/admin/thrand/bootstrap.min.css');
		$this->addCss(Yii::app()->request->baseUrl . '/css/admin/main/right/screen.css');
		$this->addCss(Yii::app()->request->baseUrl . '/css/admin/thrand/step.css');
		$this->addCss(Yii::app()->request->baseUrl . '/css/admin/thrand/form.css');
		$this->addCss(Yii::app()->request->baseUrl . '/css/admin/thrand/box.css');

		$shops_classliy=ShopsClassliy::getClass();
		$model=$this->new_model($id,$shops_classliy->id);

		$model->scenario='create';
		$model->Actives_Shops->scenario='create_actives';
		//设置验证场景;
		foreach ($model->Actives_Pro as $Pro)
		{
			$Pro->scenario='create_actives';
			foreach ($Pro->Pro_ProFare as $ProFare)
				$ProFare->scenario='create_actives';
		}

		$this->_Ajax_Verify(array($model,$model->Actives_Shops),'actives-form');
//		$this->p_r($_POST);
//exit;
		if(isset($_POST['Actives']) && isset($_POST['Shops']) && isset($_POST['Pro']) && is_array($_POST['Pro']) && isset($_POST['ProFare']) && is_array($_POST['ProFare']) && count($_POST['ProFare'])==count($_POST['Pro']))
		{
			if($this->validate_actives($model,$shops_classliy->id))
			{
				$model->scenario='create';
				$model->attributes=$_POST['Actives'];
//
//
//				//提前验证
//				$validate_pros_fares = true;
//				$validate_pros_fares=$model->validate();
//
//				$model->Actives_Shops->scenario = 'create_thrand';
//				if( ! $model->Actives_Shops->validate())
//					$validate_pros_fares = false;

				//提前验证
				$validate_shops=$model->Actives_Shops->validate();
				$validate_pros_fares=true;
				foreach ($model->Actives_Pro as $pro)
				{
					if(! $pro->validate())
						$validate_pros_fares=false;
					foreach ($pro->Pro_ProFare as $fare)
					{
						if(! $fare->validate())
							$validate_pros_fares=false;
					}
				}

				if( $validate_shops && $validate_pros_fares)
				{
					$delete_pro_number=count($this->_new_number);
					$delete_models=array();
					$old_model=$this->new_model($id,$shops_classliy->id);
					foreach ($old_model->Actives_Pro as $key_pro=>$delete_pro)
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
						$model->Actives_Shops->status	= Shops::status_offline;
						$model->Actives_Shops->audit		= Shops::audit_pending;

						if($model->Actives_Shops->save(false))
						{
							$model->actives_status  	= Actives::actives_status_not_start;   //活动状态
							$model->start_time 	  	= isset($model->start_time)?strtotime($model->start_time):time();              		//开始时间
							$model->end_time    	  	= strtotime($model->end_time); //结止时间
							$model->go_time    	  	= isset($model->go_time) && $model->go_time ?strtotime($model->go_time):0; 			//活动时间
							$model->status     	 	 = Actives::status_not_publish;//表示旅游活动没有成订单 下线状态
							$model->order_number 	 	 = $model->number; //剩余数量
							$model->remark       		= $this->admin_img_replace($model->remark);//处理图片链接
							if(! $model->save(false))
								throw new Exception("修改线路(旅游活动多点)保存线路附表错误");
							foreach ($model->Actives_Pro as $pro_save)
							{
								$pro_save->shops_id=$model->id;
								if(! $pro_save->save(false))
									throw new Exception("修改线路(旅游活动多点) 保存选中项目表错误");
								foreach ($pro_save->Pro_ProFare as $fare_save)
								{
									$fare_save->pro_id=$pro_save->id;
									$fare_save->thrand_id=$model->id;
									if(! $fare_save->save(false))
										throw new Exception("修改线路(旅游活动多点) 保存选中项目的选中价格表错误");
								}
							}
							foreach ($delete_models as $delete_model)
							{
								if(isset($delete_model->Pro_ProFare))
								{
									foreach ($delete_model->Pro_ProFare as $fare_delete)
									{
										if(! $fare_delete->delete())
											throw new Exception("修改线路(旅游活动多点)选中项的选中价格错误");
									}
								}
								if(! $delete_model->delete())
									throw new Exception("修改线路(旅游活动多点)选中项错误");
							}
							$return=$this->log('修改线路(旅游活动多点)',ManageLog::admin,ManageLog::update);
						}else
							throw new Exception("修改线路(旅游活动多点)主要记录错误");
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
			//$this->redirect(array('/admin/tmm_actives/admin'));

		}elseif(isset($_POST['Shops'])){
			$model->Actives_Shops->addError('name','选中点 不能空白');
		}

		$this->render('dot_update',array(
			'model'=>$model,
			'search_model'=>$this->search_dot(),
		));
	}

	/**
	 * 验证
	 * @param unknown $model
	 * @return boolean
	 */
	public function validate_actives($model,$c_id)
	{
		//$validate_array=array();//需要验证的数据
		if(! ( isset($_POST['Pro']) && isset($_POST['ProFare'])))
		{
			$model->Actives_Shops->addError('name', '选择点或选择的项目或选择的价格 不可空白');

			return false;
		}
		$model->Actives_Shops->attributes=$_POST['Shops'];


		$day_number=count($_POST['Pro']);//天数
		if($day_number != count($_POST['ProFare'])) //比较是否为给每一天都选了价格
		{
			$model->Actives_Shops->addError('name', '选择点或选择线无选择价格');
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
					$model->Actives_Shops->addError('name', '日程天数不是有效值');
					return false;
				}
				if(!is_array($day_dots) || empty($day_dots))
				{
					$model->Actives_Shops->addError('name', '旅游活动中的选择点存在未上线的项目');
					return false;
				}
				$dot_sort=0; //点的排序
				$j=0;
				foreach ($day_dots as $half_sort=>$dot_items_ids)
				{
					if(!is_array($dot_items_ids) || empty($dot_items_ids))
					{
						$model->Actives_Shops->addError('name', '旅游活动中的选择点存在未上线的项目');
						return false;
					}

					if($half_sort !=$dot_sort || $half_sort > Yii::app()->params['shops_thrand_one_day_dot_number'])
					{
						$model->Actives_Shops->addError('name', '旅游活动中的选择点存在未上线的项目');
						return false;
					}

					foreach ($dot_items_ids as $dot_id=>$items)
					{
						if(!is_array($items) || empty($items))
						{
							$model->Actives_Shops->addError('name', '旅游活动中的选择点存在未上线的项目');
							return false;
						}

						//获取id 点所有的信息
						$dot_items_fares_array=$this->get_dot($dot_id);

						if(empty($dot_items_fares_array))
						{
							$model->Actives_Shops->addError('name', '旅游活动 选择点或项目或价格 不是有效值');
							return false;
						}

						$items_sort=0;//项目排序
						foreach ($items as $sort=>$item)
						{
							if($items_sort != $sort)
							{
								$model->Actives_Shops->addError('name', '旅游活动中的选择点存在未上线的项目');
								return false;
							}
							//$this->p_r($dot_items_fares_array['items']);
							//判断点中是否有项目的id
							if(isset($dot_items_fares_array['items'][$item]) && $dot_items_fares_array['items'][$item]['is_validate'])
								$dot_items_fares_array['items'][$item]['is_validate']=false;//一个点不能有重复的项目
							else{
								$model->Actives_Shops->addError('name', '旅游活动中或点存在已经选择的项目');
								return false;
							}

							//项目中的数据
							$item_data=$dot_items_fares_array['items'][$item]['data'];
							//赋值
							$Thrand_Pro=isset($model->Actives_Pro[$i])?$model->Actives_Pro[$i]:new Pro;
							$Thrand_Pro->scenario='create_thrand';
							$Thrand_Pro->shops_c_id=$c_id;
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
								$model->Actives_Shops->addError('name', '旅游活动中或点中的项目无选中的价格');
								return false;
							}

							$item_select_fares=$_POST['ProFare'][$day_sort][$half_sort][$dot_id][$sort][$item];
							if(!is_array($item_select_fares) || empty($item_select_fares))
							{
								$model->Actives_Shops->addError('name', '旅游活动中或点中的项目选中的价格无效');
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
									$model->Actives_Shops->addError('name', '旅游活动中或点中的项目选中的价格存在重复');
									return false;
								}
								$Pro_ProFare=isset($model->Actives_Pro[$i]->Pro_ProFare[$j])?$model->Actives_Pro[$i]->Pro_ProFare[$j]:new ProFare;
								$Pro_ProFare->scenario='create_thrand';
								$Pro_ProFare->fare_id=$fare;
								$Pro_ProFare->items_id=$item;
								$Pro_ProFares[]=$Pro_ProFare;
								if(in_array($fare, $fares))
								{
									$model->Actives_Shops->addError('name', '旅游活动中或点中的项目选中的价格存在重复2');
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
			$model->Actives_Shops->addError('name', '旅游活动中的选择点中存在未上线的项目');
			return false;
		}

		$model->Actives_Pro=$Thrand_Pros;
		return true;
	}

	/**
	 * 获得 点
	 * @param $dot_id
	 * @return array
	 */
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
	public function actionDot_view_dot($id)
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
		$this->renderPartial('dot_view_dot',array('model'=>$model));
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
			'with'=>$this->actives_model_with(),
//			'condition'=>'t.c_id=:c_id AND Actives_Shops.status=:status AND Actives_Shops.audit=:audit',
//			'params'=>array(':c_id'=>$c_id,':status'=>Shops::status_offline,':audit'=>Shops::audit_pending),
			'condition'=>'t.c_id=:c_id AND Actives_Shops.status=:status ',
			'params'=>array(':c_id'=>$c_id,':status'=>Shops::status_offline),
			'order'=>'Actives_Pro.day_sort,Actives_Pro.half_sort,Actives_Pro.sort',
		));
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

	//==================================2016-01-06 觅趣 一条线 创建 编辑==================================================================================

	/**
	 * 添加=========完整的线
	 * @param $id======组织者ID
	 */
	public function actionThrand_create($id){


		$this->addCss(Yii::app()->request->baseUrl . '/css/admin/thrand/bootstrap.min.css');
		$this->addCss(Yii::app()->request->baseUrl . '/css/admin/main/right/screen.css');
		$this->addCss(Yii::app()->request->baseUrl . '/css/admin/thrand/step.css');
		$this->addCss(Yii::app()->request->baseUrl . '/css/admin/thrand/form.css');
		$this->addCss(Yii::app()->request->baseUrl . '/css/admin/thrand/box.css');

		$this->_organizer_id = $id;

		$shops_classliy = ShopsClassliy::getClass();

		$model=new Actives();//觅趣的主要表
		$model->scenario='create_thrand';
		$model->Actives_Shops=new Shops;//商品表
		$model->Actives_Shops->scenario='create_actives';

		$this->_class_model='User';
		//$model->Actives_Organizer=$this->loadModel($id,'status=1');
		$model->Actives_User = $this->loadModel($id,'is_organizer=1 AND audit=:audit AND  status=1 AND  id='.$id,array(':audit'=>User::audit_pass));


		$Pro=new Pro;//选中点的选中项目表
		$Pro->scenario='create_actives';
		$ProFare=new ProFare;//选中项目选中的价格表
		$ProFare->scenario='create_actives';

		$Pro->Pro_ProFare=array($ProFare);//关系赋值 一个项目可以选多个价格 默认一个
		$model->Actives_Pro=array($Pro);//一条线可以选择多个点中的项目 默认一个

		$this->_Ajax_Verify(array($model,$model->Actives_Shops),'actives-form');

		if (isset($_POST['Actives']) && $_POST['Actives']['tour_type'] == Actives::tour_type_thrand && $_POST['Actives']['actives_type'] == Actives::actives_type_tour && isset($_POST['Actives']['thrand_id']) && is_numeric($_POST['Actives']['thrand_id']) && $_POST['Actives']['thrand_id'] > 0) {

			$status = $this->thrand_save($model,$shops_classliy->id,$_POST['Actives']['thrand_id']);

			if(isset($status))
				$this->back();
		}
		$this->render('thrand_create',array(
			'model'=>$model,
			'search_model'=>$this->search_thrand(),
		));

	}


	/**
	 * 线的搜索
	 * @param unknown $id
	 * @return multitype:Items NULL
	 */
	public function search_thrand()
	{
		$old_model=$this->_class_model;
		$this->_class_model='Thrand';
		$shops_classliy = ShopsClassliy::getClass();
		$criteria=new CDbCriteria;

		$criteria->with=array(
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
				),
					'order'=>'Thrand_Pro.day_sort,Thrand_Pro.half_sort,Thrand_Pro.sort',
				),
			//'order'=>'Thrand_Pro.day_sort,Thrand_Pro.half_sort,Thrand_Pro.sort',
		);

		$criteria->addColumnCondition(array(
			't.c_id'=>$shops_classliy->id,
			'`Thrand_Shops`.`status`'=>Shops::status_online,
			'`Thrand_Shops`.`audit`'=>Shops::audit_pass,
		));

		//$criteria->order = 'Thrand_Pro.day_sort,Thrand_Pro.half_sort,Thrand_Pro.sort';
		if (isset($_GET['search_info']) && !empty($_GET['search_info']))
		{
			$criteria->params[':search_info']='%'.strtr(trim($_GET['search_info']),array('%'=>'\%', '_'=>'\_', '\\'=>'\\\\')).'%';
			$criteria->addCondition('`Thrand_Shops`.`name` LIKE :search_info');
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
		$model =new Thrand;
		$this->_class_model=$old_model;
		return array('dataProvider'=>$model->search($criteria),'model_search'=>$model);
	}

	/**
	 * 更新=========完整的线
	 * @param $id======记录ID
	 */
	public function actionThrand_update($id){
		$this->addCss(Yii::app()->request->baseUrl . '/css/admin/thrand/bootstrap.min.css');
		$this->addCss(Yii::app()->request->baseUrl . '/css/admin/main/right/screen.css');
		$this->addCss(Yii::app()->request->baseUrl . '/css/admin/thrand/step.css');
		$this->addCss(Yii::app()->request->baseUrl . '/css/admin/thrand/form.css');
		$this->addCss(Yii::app()->request->baseUrl . '/css/admin/thrand/box.css');

		$shops_classliy=ShopsClassliy::getClass();
		$model=$this->new_model($id,$shops_classliy->id);

		$this->_organizer_id = $model->organizer_id;

		$model->scenario='create_thrand';
		$model->Actives_Shops->scenario='create_actives';
		//设置验证场景;
		foreach ($model->Actives_Pro as $Pro)
		{
			$Pro->scenario='create_actives';
			foreach ($Pro->Pro_ProFare as $ProFare)
				$ProFare->scenario='create_actives';
		}

		$this->_Ajax_Verify(array($model,$model->Actives_Shops),'actives-form');


//		$model=new Actives();//觅趣的主要表
//		$model->scenario='create_thrand';
//		$model->Actives_Shops=new Shops;//商品表
//		$model->Actives_Shops->scenario='create_actives';
//
//
//		$Pro=new Pro;//选中点的选中项目表
//		$Pro->scenario='create_actives';
//		$ProFare=new ProFare;//选中项目选中的价格表
//		$ProFare->scenario='create_actives';
//
//		$Pro->Pro_ProFare=array($ProFare);//关系赋值 一个项目可以选多个价格 默认一个
//		$model->Actives_Pro=array($Pro);//一条线可以选择多个点中的项目 默认一个

//		$this->_Ajax_Verify(array($model,$model->Actives_Shops),'actives-form');

		if (isset($_POST['Actives']) && $_POST['Actives']['tour_type'] == Actives::tour_type_thrand && $_POST['Actives']['actives_type'] == Actives::actives_type_tour && isset($_POST['Actives']['thrand_id']) && is_numeric($_POST['Actives']['thrand_id']) && $_POST['Actives']['thrand_id'] > 0) {

			if($model->Actives_User->is_organizer == User::organizer)
				$this->_is_organizer = Actives::is_organizer_yes;
			else
				$this->_is_organizer = Actives::is_organizer_no;

			$status = $this->thrand_save($model,$shops_classliy->id,$_POST['Actives']['thrand_id'],$id);

			if(isset($status))
				$this->back();
		}
		$this->render('thrand_create',array(
			'model'=>$model,
			'search_model'=>$this->search_thrand(),
		));
	}
	/**
	 *	选择查看线
	 * @param unknown $id
	 */
	private function actionThrand_view_thrand($id)
	{

		$this->_class_model='Thrand';
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

		$this->renderPartial('thrand_view_thrand',array('model'=>$model));
	}


	/**
	 * 创建（更新）旅游活动  ======线路保存
	 * @param $model
	 * @param $actives_arr　所有数组
	 * @param $c_id        类型ID
	 * @param $thrand_id   线ID
	 * @param $old_id      旧线ID
	 * @return int
	 */
	private function thrand_save($model,$c_id,$thrand_id,$old_id=''){
		//接收旅游活动信息和商品信息（活动名称）
		$model->attributes=$_POST['Actives'];
		$model->Actives_Shops->attributes=$_POST['Shops'];

		if( ! $this->thrand_model($thrand_id)) {
			$model->addError('thrand_id', '线ID不是有效值2');
			return false;
		}

		$status = '';
		//事务
		$transaction=$model->dbConnection->beginTransaction();
		try{

			$text_con = $old_id?'更新':'创建';
			//删除旧的旅游活动相关表错误
			if($old_id){
				if(!  $this->del_actives($old_id) )
					throw new Exception($text_con."旅游活动(线) 删除旧的旅游活动相关表错误");
			}

			$model->Actives_Shops->c_id=$c_id;                     	//c_id
			$model->Actives_Shops->status=Shops::status_offline; 	//下线状态
			$model->Actives_Shops->audit=Shops::audit_pending; 	//未审核
			if($model->Actives_Shops->save(false)){
				$model->id        		  = $model->Actives_Shops->id;      //旅游活动ID
				$model->c_id      		  = $c_id;                    	    //类型ID　３
				$model->organizer_id    = $this->_organizer_id;           //组织者
				$model->actives_status  = Actives::actives_status_not_start;   //活动状态
				$model->start_time 	  = strtotime($model->start_time);     	//开始时间
				$model->end_time    	  = strtotime($model->end_time); 			//结止时间
				$model->go_time    	  = isset($model->go_time) && $model->go_time ?strtotime($model->go_time):0; 			//活动时间
				$model->order_number 	  = $model->number; //剩余数量
				$model->remark       		= $this->admin_img_replace($model->remark);//处理图片链接
				$model->status     	  = Actives::status_not_publish;       //表示旅游活动没有成订单 下线状态
				// 活动服务费
				if($this->_is_organizer != Actives::is_organizer_yes)
					$model->tour_price	  = $this->_tour_price;//活动服务费
				if(! $model->save(false))
					throw new Exception($text_con."旅游活动(线) 保存旅游活动商品表错误");

				//迁移线的数据
				$thrand_id = $thrand_id;
				$model_thrand = $this->thrand_model($thrand_id);
				//商品ID（活动ID）
				$shop_id=$model->Actives_Shops->id;
				//复制线里的选中项目表到结伴游中
				foreach($model_thrand->Thrand_Pro as $val){
					$val->thrand_id = $thrand_id;
					$model_pro = $this->get_pro($val,$c_id,$shop_id);
					if(! $model_pro->save(false))
						throw new Exception($text_con."旅游活动(线) 保存旅游活动选中项目表错误");
					//选中项目表  ID
					$pro_id = $model_pro->id;
					foreach($val->Pro_ProFare as $kf=>$fare){
						$model_pro_fare = $this->get_pro_fare($fare,$pro_id);
						if(! $model_pro_fare->save(false))
							throw new Exception($text_con."旅游活动(线) 保存旅游活动选中项目对应价格表错误".$kf);
					}
				}

			}else
				throw new Exception($text_con."旅游活动(线) 保存旅游活动表错误");
			//事务提交
			$transaction->commit();
			$status = 1;

		}catch (Exception $e){
			//事务回滚
			$transaction->rollBack();
			$this->error_log($e->getMessage(),ErrorLog::admin,ErrorLog::create,ErrorLog::rollback,__METHOD__);
		}

		return $status;
	}

	/**
	 * 线的model
	 * @param $id
	 * @return mixed
	 */
	public function thrand_model($id){
		$this->_class_model='Thrand';
		$shops_classliy=ShopsClassliy::getClass();

		$model=$this->loadModel($id,array(
			'with'=>array(
				'Thrand_ShopsClassliy',
				'Thrand_Shops'=>array('with'=>array('Shops_Agent')),
				'Thrand_Pro'=>array('with'=>array(
					'Pro_Thrand_Dot'=>array(
						'with'=>array(
							'Dot_Shops'=>array(
								'condition'=>'`Dot_Shops`.`status`=1 AND `Dot_Shops`.`audit`=:audit_dot',
								'params'=>array(':audit_dot'=>Shops::audit_pass),
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
						'condition'=>'`Pro_Items`.`status`=1 AND `Pro_Items`.`audit`=:audit_pro',
						'params'=>array(':audit_pro'=>Items::audit_pass),
					),
					'Pro_ItemsClassliy',
					'Pro_ProFare'=>array(
						'with'=>array('ProFare_Fare'),
					),
				)),
			),
			'condition'=>'t.c_id=:c_id AND `Dot_Shops`.`status`=1 AND `Dot_Shops`.`audit`=:audit ',
			'params'=>array(':c_id'=>$shops_classliy->id,':audit'=>Shops::audit_pass),
			'order'=>'Thrand_Pro.day_sort,Thrand_Pro.half_sort,Thrand_Pro.sort',
		));

		return $model;
	}

	/**
	 * 创建（更新）旅游活动  ======旧线路删除
	 * @param $id
	 */
	private function del_actives($id){
		$model_actives = $this->actives_model($id);
		$arr_pro = array();
		foreach($model_actives->Actives_Pro as $pro){
			$arr_pro[] =  $pro->id;
		}
		$str_pro = implode(',',$arr_pro);
		$fare_pro = ProFare::model()->deleteAll('pro_id in ('.$str_pro.')');
		if(!$fare_pro)
			return false;
		$pro = Pro::model()->deleteAll('shops_id=:shops_id',array(':shops_id'=>$id));
		if(!$pro)
			return false;

		return true;
	}

	/**
	 * tmm_pro
	 */
	public function get_pro($val,$c_id,$shop_id){
		$model_pro = new Pro();

		$model_pro->shops_id     =$shop_id;
		$model_pro->agent_id     =$val->agent_id;
		$model_pro->store_id     =$val->store_id;
		$model_pro->shops_c_id   =$c_id;
		$model_pro->c_id          =$val->c_id;
		$model_pro->sort          =$val->sort;
		$model_pro->day_sort     =$val->day_sort;
		$model_pro->half_sort    =$val->half_sort;
		$model_pro->items_id     =$val->items_id;
		$model_pro->dot_id       =$val->dot_id;
		$model_pro->thrand_id    =$val->thrand_id;
		$model_pro->info          =$val->info;
		$model_pro->add_time     =$val->add_time;
		$model_pro->up_time      =$val->up_time;
		$model_pro->audit        =$val->audit;
		$model_pro->status       =$val->status;

		return $model_pro;
	}

	/**
	 * tmm_pro_fare
	 */
	public function get_pro_fare($fare,$pro_id){
		$model_pro_fare = new ProFare();

		$model_pro_fare->pro_id      = $pro_id;
		$model_pro_fare->fare_id     = $fare->fare_id;
		$model_pro_fare->group_id    = $fare->group_id;
		$model_pro_fare->items_id    = $fare->items_id;
		$model_pro_fare->thrand_id   = $fare->thrand_id;
		$model_pro_fare->add_time    = $fare->add_time;
		$model_pro_fare->up_time     = $fare->up_time;
		$model_pro_fare->status      = $fare->status;

		return $model_pro_fare;
	}
	/**
	 * @param $id
	 * @param string $type
	 * @return mixed
	 */
	private function actives_model($id,$type=''){
		$this->_class_model = 'Actives';
		$c_id=ShopsClassliy::getClass()->id;
		return $this->loadModel($id,array(
			'with'=>$this->actives_model_with(),
			'condition'=>'`t`.`c_id`=:c_id AND `t`.`actives_status`=:actives_status AND `Actives_Shops`.`status`=:status AND `Actives_Shops`.`audit` != :audit',
			'params'=>array(':c_id'=>$c_id,':actives_status'=> Actives::actives_status_not_start,':status'=>Shops::status_offline,':audit'=>Shops::audit_pass),
			'order'=>'Actives_Pro.day_sort,Actives_Pro.half_sort,Actives_Pro.sort',
		));

	}

	/**
	 * 线路  ======  查看
	 * @param $id
	 * @return unknown
	 * @throws CHttpException
	 */
	public function thrand_data_form($id){
		$this->_class_model = 'Thrand';
		$shops_classliy=ShopsClassliy::getClass();
		$thrand_model=$this->loadModel($id,array(
			'with'=>array(
				'Thrand_ShopsClassliy',
				'Thrand_Shops'=>array('with'=>array('Shops_Agent')),
			),
			'condition'=>'t.c_id=:c_id',
			'params'=>array(':c_id'=>$shops_classliy->id),
		));

		return $thrand_model;
	}

}
