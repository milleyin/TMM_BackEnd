<?php
/**
 * 
 * @author Changhai Zhan
 *	创建时间：2015-08-20 14:41:27 */
class Tmm_groupController extends MainController
{
	/**
	 * 默认操作数据模型
	 * @var string
	 */
	public $_class_model='Group';

	/**
	 * 查看详情
	 * @param integer $id
	 */
	public function actionView($id)
	{

		$this->addCss(Yii::app()->baseUrl.'/css/admin/main/right/group_items.css');
		$shops_classliy=ShopsClassliy::getClass();
		$model=$this->loadModel($id,array(
			'with'=>array(
				'Group_ShopsClassliy',
				'Group_Shops',
				'Group_User',
				'Group_Pro'=>array('with'=>array(
					'Pro_Group_Dot'=>array('with'=>'Dot_Shops'),
					'Pro_Group_Thrand'=>array('with'=>'Thrand_Shops'),
					'Pro_Items'=>array(
							'with'=>array(
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
			'order'=>'Group_Pro.day_sort,Group_Pro.half_sort,Group_Pro.sort',
		));
		$model->Group_TagsElement=TagsElement::get_select_tags(TagsElement::tags_shops_group,$id);
		
		$this->render('view',array('model'=>$model));
	}

	/**
	 * 删除
	 * @param integer $id
	 */
	public function actionDelete($id)
	{
		//获取项目类型
		$c_id=ShopsClassliy::getClass()->id;//结伴游
		$this->_class_model='Shops';
		if($this->loadModel($id,'`status`=0 AND `c_id`=:c_id',array(':c_id'=>$c_id))->updateByPk($id,array('status'=>-1)))
			$this->log('删除线路(结伴游)',ManageLog::admin,ManageLog::delete);

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
		$c_id=ShopsClassliy::getClass()->id;//结伴游
		$this->_class_model='Shops';
		if($this->loadModel($id,'`status`=-1 AND `c_id`=:c_id',array(':c_id'=>$c_id))->updateByPk($id,array('status'=>0)))
			$this->log('还原线路(结伴游)',ManageLog::admin,ManageLog::update);

		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));

	}
	
	/**
	 * 审核通过
	 * @param integer $id
	 */
	public function actionPass($id){

		$shops_classliy=ShopsClassliy::getClass();
		//查看是否需要审核
		$model=$this->loadModel($id,array(
			'with'=>array('Group_Shops'),
			'condition'=>'t.c_id=:c_id AND Group_Shops.status=0 AND Group_Shops.audit=:audit',
			'params'=>array(':c_id'=>$shops_classliy->id,':audit'=>Shops::audit_pending),
		));
		
		$model->Group_Shops->pub_time=time();
		$model->Group_Shops->audit=Shops::audit_pass;// 审核通过
		$transaction=$model->dbConnection->beginTransaction();
		try{
			if($model->Group_Shops->save(false)){
				$audit=new AuditLog;
				$audit->info=$model->Group_Shops->name;
				$audit->audit_who=AuditLog::admin;//记录 审核人 （审核人的id 在模型中）
				$audit->audit_element=AuditLog::shops_group;//记录 被审核的类型
				$audit->element_id=$model->id;//记录 被审核id
				$audit->audit=AuditLog::pass;//记录 审核通过
				if($audit->save(false))
					$return=$this->log('添加审核线路(结伴游)记录',ManageLog::admin,ManageLog::create);
				else
					throw new Exception("线路(结伴游)添加审核日志错误");
			}else
				throw new Exception("线路(结伴游)审核通过保存错误");
			$transaction->commit();
		}
		catch(Exception $e)
		{
			$transaction->rollBack();
			$this->error_log($e->getMessage(),ErrorLog::admin,ErrorLog::create,ErrorLog::rollback,__METHOD__);
		}
		//审核通过 搬东西
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
		$model->Audit_Group=$this->loadModel($id,array(
			'with'=>array('Group_Shops'),
			'condition'=>'t.c_id=:c_id AND Group_Shops.status=0 AND Group_Shops.audit=:audit',
			'params'=>array(':c_id'=>$shops_classliy->id,':audit'=>Shops::audit_pending),
		));
		$this->_Ajax_Verify($model,'audit-log-form');

		if(isset($_POST['AuditLog']))
		{
			$model->attributes=$_POST['AuditLog'];
			$model->audit_who=AuditLog::admin;//记录 审核人 （审核人的id 在模型中）
			$model->audit_element=AuditLog::shops_group;//记录 被审核的
			$model->element_id=$model->Audit_Group->id;//记录 被审核id
			$model->audit=AuditLog::nopass;//记录 审核不通过
			if($model->validate()){
				$transaction=$model->dbConnection->beginTransaction();
				try
				{
					if($model->save(false))
					{
						$model->Audit_Group->Group_Shops->audit=Shops::audit_nopass;//审核不通过
						if($model->Audit_Group->Group_Shops->save(false))
							$return=$this->log('线路(结伴游)审核不通过记录',ManageLog::admin,ManageLog::create);
					}else
						throw new Exception("添加线路(结伴游)审核不通过日志错误");

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
	 * 垃圾回收页
	 */
	public function actionIndex()
	{

		$criteria=new CDbCriteria;
		$criteria->with=array(
			'Group_ShopsClassliy',
			'Group_Shops'=>array('with'=>array('Shops_Agent')),
		);
		$criteria->addColumnCondition(array('Group_Shops.status'=>-1));
		$model=new Group;
		$this->render('index',array(
			'model'=>$model->search($criteria),
		));
	}
	
	/**
	 *管理页
	 */
	public function actionAdmin()
	{
		$model = new Group('search');
		$model->unsetAttributes();  // 删除默认属性
		$model->Group_Shops = new Shops('search');
		$model->Group_Shops->unsetAttributes();

		if (isset($_GET['Group'])) {
			$model->attributes = $_GET['Group'];
		}
		if (isset($_GET['Shops'])) {
			$model->Group_Shops->attributes = $_GET['Shops'];
		}

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * 下线
	 * @param integer $id
	 */
	public function actionDisable($id)
	{
		//获取项目类型
		$c_id=ShopsClassliy::getClass()->id;//结伴游
		$this->_class_model='Shops';
		if($this->loadModel($id,'`status`=1 AND `c_id`=:c_id',array(':c_id'=>$c_id))->updateByPk($id,array('status'=>0)))
			$this->log('下线线路(结伴游)',ManageLog::admin,ManageLog::update);
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
	}

	/**
	 * 上线
	 * @param integer $id
	 */
	public function actionStart($id)
	{
		//获取项目类型
		$c_id=ShopsClassliy::getClass()->id;//结伴游
		$this->_class_model='Shops';
		if($this->loadModel($id,'list_info !=\'\' AND `status`=0 AND `c_id`=:c_id AND `audit`=:audit',array(':c_id'=>$c_id,':audit'=>Shops::audit_pass))->updateByPk($id,array('status'=>1)))
			$this->log('上线线路(结伴游)',ManageLog::admin,ManageLog::update);
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
	}

	/**
	 * 选择标签的显示
	 * @param $id
	 * @throws CHttpException
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
				'with'=>array('Group_Shops'),
				'condition'=>'Group_Shops.status=0 AND Group_Shops.audit !=:audit AND Group_Shops.c_id=:c_id',
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
				'with'=>array('Group_Shops'),
				'condition'=>'Group_Shops.status=0 AND Group_Shops.audit !=:audit AND Group_Shops.c_id=:c_id',
				'params'=>array(':audit'=>Shops::audit_pending,':c_id'=>ShopsClassliy::getClass()->id),
			));
			$tags_ids=Tags::filter_tags($_POST['tag_ids']);//安全过滤tags id
			if($type=='yes'){
				//过滤之前有的
				$save_tags_id=TagsElement::not_select_tags_element($tags_ids,$id,TagsElement::tags_shops_group);
				$return=TagsElement::select_tags_ids_save($save_tags_id, $id, TagsElement::tags_shops_group);
			}else
				$return=TagsElement::select_tags_ids_delete($tags_ids, $id, TagsElement::tags_shops_group);
			if($return)
			{
				if($type=='yes')
					$this->log('线路(结伴游)添加标签', ManageLog::admin,ManageLog::create);
				else
					$this->log('线路(结伴游)去除标签', ManageLog::admin,ManageLog::clear);
				echo 1;
			}else
				echo '操作过于频繁，请刷新页面从新选择！';
		}else
			echo '没有选中标签，请重新选择标签！';
	}

	/**
	 * 包装结伴游
	 * @param $id
	 * @throws CHttpException
	 */
	public function actionPack($id){

		$this->addCss(Yii::app()->baseUrl.'/css/admin/main/right/group_items.css');

		$shops_classliy=ShopsClassliy::getClass();
		$model=$this->loadModel($id,array(
			'with'=>array(
				'Group_ShopsClassliy',
				'Group_Shops'=>array('with'=>array('Shops_Agent')),
				'Group_User',
				'Group_Pro'=>array('with'=>array(
					'Pro_Group_Dot'=>array('with'=>'Dot_Shops'),
					'Pro_Group_Thrand'=>array('with'=>'Thrand_Shops'),
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
			'condition'=>'t.c_id=:c_id AND Group_Shops.status=0 AND Group_Shops.audit=:audit',
			'params'=>array(':c_id'=>$shops_classliy->id,':audit'=>Shops::audit_pass),
			'order'=>'Group_Pro.day_sort,Group_Pro.half_sort,Group_Pro.sort',
		));

		$model->Group_Shops->scenario='pack_group';
		$this->set_scenarios($model->Group_Pro, 'pack_group');
		$Verify_array=array();
		foreach($model->Group_Pro as $Pro)
		{
			if(! isset($array[$Pro->day_sort]) && $Pro->half_sort==0 && $Pro->sort==0)
			{
				$array[$Pro->day_sort]=$Pro->half_sort;
				$Verify_array[]=$Pro;
			}
		}	
		
		//ajax验证
		$this->_Ajax_Verify_Same(array_merge(array($model->Group_Shops),$Verify_array),'group-pack-form');
			
		if(isset($_POST['Shops']) && isset($_POST['Pro']) && count($Verify_array)==count($_POST['Pro']))
		{
			$this->_upload=Yii::app()->params['uploads_shops_group'];
			//获得需要的上传图片
			$uploads = array('list_img','page_img');
			//保存原来的
			$data    = $this->upload_save_data($model->Group_Shops, $uploads);
			//过滤空白的值
			$data=array_filter($data);
			//获得参数
			$model->Group_Shops->attributes=$_POST['Shops'];
			//过滤 数据id
			$ids=$this->array_listData($_POST['Pro'],'id');
			if(!empty($ids))
				$ids=Pro::filter_pro($ids,$id,true,'t.day_sort,t.half_sort,t.sort');
			//过滤 数据info
			$infos=array_filter($this->array_listData($_POST['Pro'],'info'));//过滤空白的值
			//验证是否为合法参数
			if(count($infos)==count($ids) && count($infos)<=count($model->Group_Pro))
				$pro_validate=true;
			else
				$pro_validate=false;
			//获取上传的
			$files   = $this->upload($model->Group_Shops,$uploads);
			//看看是修改 还是创建
			if(!empty($data))
				$shop_validate_img=true;
			else
				$shop_validate_img=$this->upload_error($model->Group_Shops, $files, $uploads);
			//验证是否为合法参数
			if($shop_validate_img)
				$shop_validate = $model->Group_Shops->validate();
			else 
				$shop_validate = false;
			
			//提前验证
			if($pro_validate && $shop_validate && $shop_validate_img)
			{
				if(!empty($data))
					//还原赋值
					$old_path=$this->upload_update_data($model->Group_Shops, $data, $files);

				$transaction=$model->dbConnection->beginTransaction();
				try{
					if($model->Group_Shops->save(false))
					{
						if(!empty($data))
						{
							//保存新的
							$this->upload_save($model->Group_Shops, $files);
							//删除原来的
							$this->upload_delete($old_path);
						}else
							$this->upload_save($model->Group_Shops, $files);

						$pro_array=array();
						foreach ($ids as $key=>$id)
							$pro_array[$id]=array('info'=>$infos[$key]);
						$save=0;
						foreach ($model->Group_Pro as $Group_Pro)
						{
							if(isset($pro_array[$Group_Pro->id]) && $Group_Pro->half_sort==0 &&  $Group_Pro->sort==0) {
								$Group_Pro->info = $pro_array[$Group_Pro->id]['info'];
								if (!$Group_Pro->save())
									throw new Exception("包装线路(结伴游)保存选择项目记录错误");
								else
									$save++;
							}
						}
						if(count($Verify_array) != $save)
							throw new Exception("包装线路(结伴游)保存选择项目简介个数错误");
					}else
						throw new Exception("包装线路(结伴游)保存主记录错误");
					$return=$this->log('包装线路(结伴游)',ManageLog::admin,ManageLog::update);
					$transaction->commit();
				}
				catch(Exception $e)
				{
					$transaction->rollBack();
					$this->error_log($e->getMessage(),ErrorLog::admin,ErrorLog::create,ErrorLog::rollback,__METHOD__);
				}

				if(isset($return)){
					if($this->order_organizer($model->id))
						 $this->back();
				}
				else
					$model->Group_Shops->addError('page_info', '包装保存错误');


			}
		}
		$this->render('pack',array('model'=>$model,));
	}

	/**
	 * @param $id
	 */
	public function actiona($id){
		$this->p_r($this->order_organizer($id));

	}

	/**
	 * model处理
	 * @param $id
	 * @return bool
	 */
	public function dot_model($id,$c_id){
		$model=$this->loadModel($id,array(
			'with'=>array(
				'Group_ShopsClassliy',
				'Group_Shops'=>array('with'=>array('Shops_Agent')),
				'Group_User',
				'Group_Pro'=>array('with'=>array(
					'Pro_Group_Dot'=>array('with'=>'Dot_Shops'),
					'Pro_Group_Thrand'=>array('with'=>'Thrand_Shops'),
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
					'Pro_ProFare'=>array(
						'with'=>array('ProFare_Fare')
					),
				)),
			),
			'condition'=>'t.c_id=:c_id  AND `Dot_Shops`.`audit`=:audit ',
			'params'=>array(':c_id'=>$c_id,':audit'=>Shops::audit_pass),
			'order'=>'`Group_Pro`.`day_sort`,`Group_Pro`.`half_sort`,`Group_Pro`.`sort`',
		));
		return $model;
	}



	//包装通过处理
	public function order_organizer($id){
		//获得c_id
		$shops_classliy=ShopsClassliy::getClass();
		//结伴游为点
		$model = $this->dot_model($id,$shops_classliy->id);
		//$this->p_r($model);exit;
		$transaction=$model->dbConnection->beginTransaction();
		try{
			//更新结伴游表团状态
			$group_model = Group::model()->findByPk($id);
			$group_model->status = Group::status_down;
			$group_model->group  = Group::group_peering;
			if(! $group_model->save(false))
				throw new Exception("结伴游 更改(结伴游订单团状态失败)");
			//订单====代理商表
			$model_order_organizer = $this->set_order_organizer($model);
			if(! $model_order_organizer->save(false))
				throw new Exception("结伴游 保存(代理商订单详情表失败)");

			//复制商品表
//			$model_order_shops = $this->set_order_shops($model);
//			if(! $model_order_shops->save(false) )
//				throw new Exception("结伴游 保存(复制商品表失败)");
			//代理商订单详情表  ID
			$order_organizer_id = $model_order_organizer->id;
			//复制商品表    ID
			$order_shops_id=$model->id;
			//需要ID集合
			$arr_id['order_organizer_id'] = $order_organizer_id;
			$arr_id['order_shops_id']     = $order_shops_id;
			//复制项目表
			foreach($model->Group_Pro as $k=>$val) {
				$model_order_items = $this->set_order_items($model,$val,$arr_id);
				if(! $model_order_items->save(false))
					throw new Exception("结伴游 保存(复制项目表失败)".$k );
				//复制项目表   ID
				$order_items_id=$model_order_items->id;
				$arr_id['order_items_id'] = $order_items_id;
				//复制价格表
				foreach($val->Pro_ProFare as $ke=>$fare) {
					$model_order_fare = $this->set_order_items_fare($model,$val,$fare,$arr_id);
					if(! $model_order_fare->save(false))
						throw new Exception("结伴游 保存(复制价格表失败)".$ke );
				}
			}
			$transaction->commit();
			$return = 1;
		}catch(Exception $e){
			//事务回滚
			$transaction->rollBack();
			$this->error_log($e->getMessage(),ErrorLog::admin,ErrorLog::create,ErrorLog::rollback,__METHOD__);
		}
		if(isset($return) && $return==1)
			return true;
		else
			return false;
	}

	public function set_order_organizer($model){
		$OrderOrganizer = new OrderOrganizer();
		$OrderOrganizer->group_no		  = OrderOrganizer::get_group_no($model->id);
		$OrderOrganizer->organizer_id   = $model->user_id;
		$OrderOrganizer->group_id        = $model->id;
		$OrderOrganizer->shops_c_id	  = $model->Group_ShopsClassliy->id;
		$OrderOrganizer->shops_c_name	  = $model->Group_ShopsClassliy->name;
		$OrderOrganizer->shops_name 	  = $model->Group_Shops->name;
		$OrderOrganizer->shops_list_img = $model->Group_Shops->list_img;
		$OrderOrganizer->shops_page_img = $model->Group_Shops->page_img;
		$OrderOrganizer->shops_list_info= $model->Group_Shops->list_info;
		$OrderOrganizer->shops_page_info= $model->Group_Shops->page_info;
		$OrderOrganizer->shops_pub_time = $model->Group_Shops->pub_time;
		$OrderOrganizer->shops_up_time  =  $model->Group_Shops->up_time;
		$OrderOrganizer->shops_add_time =  $model->Group_Shops->add_time;
		$OrderOrganizer->group_price	   = $model->price;
		$OrderOrganizer->group_remark    = $model->remark;
		$OrderOrganizer->group_start_time  = $model->start_time;
		$OrderOrganizer->group_end_time  	  = $model->end_time;
		$OrderOrganizer->group_group      	  = $model->group;



		return $OrderOrganizer;

	}
	/**
	 * 复制商品表
	 * @param unknown $data
	 * @param unknown $type
	 * @param unknown $params
	 * @return OrderShops
	 */
	public function set_order_shops($model)
	{
		$OrderShops=new OrderShops;
		$OrderShops->user_id			= $model->user_id;
		$OrderShops->shops_id			= $model->id;
		$OrderShops->shops_c_id		= $model->Group_ShopsClassliy->id;
		$OrderShops->shops_c_name		= $model->Group_ShopsClassliy->name;
		$OrderShops->shops_name		= $model->Group_Shops->name;
		$OrderShops->shops_list_img	= $model->Group_Shops->list_img;
		$OrderShops->shops_page_img	= $model->Group_Shops->page_img;
		$OrderShops->shops_list_info	= $model->Group_Shops->list_info;
		$OrderShops->shops_page_info	= $model->Group_Shops->page_info;
		$OrderShops->shops_pub_time	= $model->Group_Shops->pub_time;
		$OrderShops->shops_add_time	= $model->Group_Shops->add_time;
		$OrderShops->shops_up_time	= $model->Group_Shops->up_time;
		$OrderShops->group_organizer_id = $model->user_id;
		$OrderShops->group_price      	= $model->price;
		$OrderShops->group_remark     = $model->remark;
		$OrderShops->group_start_time	= $model->start_time;
		$OrderShops->group_end_time  	= $model->end_time;
		$OrderShops->group_time      	= $model->group_time;
		$OrderShops->group            	= $model->group;

		return $OrderShops;
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

		$OrderItems->organizer_id 	= $model->user_id;
		$OrderItems->order_organizer_id = $arr_id['order_organizer_id'];
		$OrderItems->user_id 			= $model->user_id;
		$OrderItems->order_shops_id 	= $arr_id['order_shops_id'];
		$OrderItems->store_id        	= $val->Pro_Items->store_id;
		$OrderItems->manager_id      	= $val->Pro_Items->store_id;

		$OrderItems->shops_id      = $model->Group_Shops->id;
		$OrderItems->shops_name    = $val->Pro_Group_Dot->Dot_Shops->name;
		$OrderItems->shops_c_id    =  $model->Group_ShopsClassliy->id;
		$OrderItems->shops_c_name  = $model->Group_ShopsClassliy->name;

		$OrderItems->items_id     	= $val->Pro_Items->id;
		$OrderItems->items_c_id   	= $val->Pro_Items->Items_ItemsClassliy->id;
		$OrderItems->items_c_name = $val->Pro_Items->Items_ItemsClassliy->name;
		$OrderItems->items_name   = $val->Pro_Items->name;
		$OrderItems->items_address  = $val->Pro_Items->Items_area_id_p_Area_id->name.$val->Pro_Items->Items_area_id_m_Area_id->name.$val->Pro_Items->Items_area_id_c_Area_id->name.$val->Pro_Items->address;

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

		$OrderItemsFare->order_items_id 	= $arr_id['order_items_id'];
		$OrderItemsFare->organizer_id  	= $model->user_id;
		$OrderItemsFare->order_organizer_id = $arr_id['order_organizer_id'];
		$OrderItemsFare->user_id  			= $model->user_id;
		$OrderItemsFare->order_shops_id   = $arr_id['order_shops_id'];

		$OrderItemsFare->store_id   	= $val->Pro_Items->store_id;
		$OrderItemsFare->manager_id   = $val->Pro_Items->manager_id;

		$OrderItemsFare->shops_id      = $model->Group_Shops->id;
		$OrderItemsFare->shops_c_id   	=  $model->Group_ShopsClassliy->id;

		$OrderItemsFare->items_id      = $val->Pro_Items->id;
		$OrderItemsFare->items_c_id   	= $val->Pro_Items->Items_ItemsClassliy->id;

		$OrderItemsFare->fare_id		= $fare->ProFare_Fare->id;
		$OrderItemsFare->fare_name		= $fare->ProFare_Fare->name;
		$OrderItemsFare->fare_info		= $fare->ProFare_Fare->info;
		$OrderItemsFare->fare_number	= $fare->ProFare_Fare->number;
		$OrderItemsFare->fare_price	= $fare->ProFare_Fare->price;
		$OrderItemsFare->fare_up_time	= $fare->ProFare_Fare->up_time;

		return $OrderItemsFare;

	}
}
