<?php
/**
 * 
 * @author Changhai Zhan
 *	创建时间：2015-09-05 10:27:05 */
class Agent_dotController extends AgentController
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
		$this->addCss(Yii::app()->request->baseUrl . '/css/agent/dist/css/bootstrap.min.css');
		$this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/form.css');
		$this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/box.css');
		$this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/style.css');

		$shops_classliy=ShopsClassliy::getClass();
		$model=$this->loadModel($id,array(
			'with'=>array(
				'Dot_Shops'=>array('with'=>array('Shops_Agent')),
				'Dot_Pro'=>array('with'=>array(
					'Pro_Items'=>array(
							'with'=>array(
								'Items_area_id_p_Area_id',
								'Items_area_id_m_Area_id',
								'Items_area_id_c_Area_id',
								'Items_ItemsImg',
								'Items_StoreContent'=>array('with'=>array('Content_Store')),
								'Items_Store_Manager',
								'Items_Fare',
							),
					),
					'Pro_ItemsClassliy',
				)),
			),
			'condition'=>'t.c_id=:c_id AND ((`Dot_Shops`.`status`>=0 AND `Dot_Shops`.`agent_id`=:agent_id) OR (`Dot_Shops`.`status`=1 AND `Dot_Shops`.`audit`=:audit AND `Dot_Shops`.`agent_id`!=:agent_id))',
			'params'=>array(':c_id'=>$shops_classliy->id,':agent_id'=>Yii::app()->agent->id,':audit'=>Shops::audit_pass),
			'order'=>'Dot_Pro.sort',
		));
		$model->Dot_TagsElement=TagsElement::get_select_tags(TagsElement::tags_shops_dot,$id);
		$this->render('view',array('model'=>$model));
	}

	/**
	 * 创建
	 */
	public function actionCreate()
	{
		//万能的CSS AND JS
		$this->addCss(Yii::app()->request->baseUrl . '/css/agent/dist/css/bootstrap.min.css');
		$this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/step.css');
		$this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/form.css');
		$this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/box.css');

		//获得当前登录ID
		$id = Yii::app()->agent->id;
		//实例化要用的类
		$model=new Dot;
		$model->Dot_Shops=new Shops;
		//设置MODEL
		$this->_class_model='Agent';
		//查询当前代理商信息
		$model->Dot_Shops->Shops_Agent=$this->loadModel($id,'status=1');
		//创建添加时验证
		$model->Dot_Shops->scenario='create_dot';
		$model->Dot_Pro=$this->new_modes('Pro','create_dot');//默认一个
		// ajax 验证
		$this->_Ajax_Verify($model->Dot_Shops,'dot-form');
		//是否POST提交
		if(isset($_POST['Shops']) && isset($_POST['Pro']) && is_array($_POST['Pro']))
		{
			//获得所有POST值
			$model->Dot_Shops->attributes=$_POST['Shops'];
			$items_ids=array();
			//处理项目类型ID
			foreach ($_POST['Pro'] as $value)
			{
				if(isset($value['items_id']))
					$items_ids[]=$value['items_id'];
			}
			
			$validate_shops=$model->Dot_Shops->validate();
			//过滤项目类型ID
			if(! empty($items_ids))
				$items_models=Items::filter_items($items_ids,false);//过滤项目
				
			if(! isset($items_models))
			{			
				$model->addError('select_items', '选择项目 不可空白');
				$validate_shops=false;
			}elseif(count($items_models) > Yii::app()->params['shops_dot_items_number']){
				$model->addError('select_items', '一个点 最多只能有'.Yii::app()->params['shops_dot_items_number'].'项目');
				$validate_shops=false;
			}elseif(count($items_ids) != count($items_models)){	
				$model->addError('select_items', '选择的项目 不是有效值');		
				$validate_shops=false;
			}

			//保存前的验证
			if($validate_shops)
			{
				//开启事务
				$transaction=$model->dbConnection->beginTransaction();
				try
				{
					//获取类型
					$this->_class_model='Dot';
					$shops_classliy=ShopsClassliy::getClass();

					$model->Dot_Shops->c_id=$shops_classliy->id;
					$model->Dot_Shops->agent_id=$model->Dot_Shops->Shops_Agent->id;
					$model->Dot_Shops->status=Shops::status_offline;//创建的没有上线
					$model->Dot_Shops->audit=Shops::audit_draft;//创建的默认未提交
					//保存 tmm_shops 表
					if(! $model->Dot_Shops->save(false))
						throw new Exception("添加商品(点)主要表记录错误");
					else{
						$model->id=$model->Dot_Shops->id;
						$model->c_id=$shops_classliy->id;
						if(! $model->save(false))
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
							if(! $pro_model->save(false))
								throw new Exception("添加商品(点)选中的项目记录错误");
							$new_ids[]=$items_model->id;
						}
						//继承项目的tags
						$models_class=Items::get_class($new_ids);
						$element_ids=array();
						foreach ($models_class as $model_class)
							$element_ids[]=array(TagsElement::$model_name[$model_class->Items_ItemsClassliy->append],$model_class->id);

						//继承项目的标签
						TagsElement::select_tags_ids_save(TagsElement::select_tags_all($element_ids), $model->Dot_Shops->id, TagsElement::tags_shops_dot,TagsElement::agent);

						$return = $this->log('创建商品(点)',ManageLog::agent,ManageLog::create);
					}
					//事务提交
					$transaction->commit();
				}
				catch(Exception $e)
				{
					//事务回滚
					$transaction->rollBack();
					$this->error_log($e->getMessage(),ErrorLog::agent,ErrorLog::create,ErrorLog::rollback,__METHOD__);
				}
				//跳转到添加标签页
				if(isset($return))
					$this->redirect(array('/agent/agent_shops/update_2','id'=>$model->id));	
			}
		}elseif(isset($_POST['Shops']) && ! isset($_POST['Pro'][0]['items_id'])){
			$model->Dot_Shops->attributes=$_POST['Shops'];
			$model->addError('select_items', $model->getAttributeLabel('Dot_Pro.items_id') . ' 不能空白');
		}	

		$this->render('create',array_merge(array('model'=>$model),$this->search_items()));
	}

	/**
	 * 项目搜索
	 * @param unknown $id
	 */
	public function search_items()
	{
		$criteria=new CDbCriteria;
		$criteria->with=array(
			'Items_Fare',
			'Items_agent',
			'Items_ItemsImg'=>array('order'=>'rand()'),
			'Items_StoreContent'=>array('select'=>'name','with'=>array('Content_Store'=>array('select'=>'phone'))),
			'Items_Store_Manager'=>array('select'=>'phone'),
			'Items_ItemsClassliy',
			'Items_area_id_p_Area_id'=>array('select'=>'name'),
			'Items_area_id_m_Area_id'=>array('select'=>'name'),
			'Items_area_id_c_Area_id'=>array('select'=>'name'),
		);
		$model_search=new Items;
		$model_search->Items_StoreContent=new StoreContent;
		//搜索店铺
		if(isset($_GET['Items']['name']) && !empty($_GET['Items']['name']))
		{
			$model_search->name=$_GET['Items']['name'];		
			$criteria->params[':name']='%'.strtr(trim($model_search->name),array('%'=>'\%', '_'=>'\_', '\\'=>'\\\\')).'%';
			$criteria->addCondition('(`Items_area_id_p_Area_id`.`name` like :name OR `Items_area_id_m_Area_id`.`name` like :name OR `Items_area_id_c_Area_id`.`name` like :name OR `t`.`name` LIKE :name OR `Items_StoreContent`.`name` LIKE :name OR `Items_StoreContent`.`address` LIKE :name)');	
		}

		// 搜索 代理商 项目 的 创建
		if(isset($_GET['create']) && $_GET['create'] != '')
		{
			if($_GET['create']==1)
				$criteria->addColumnCondition(array('`t`.`agent_id`'=>Yii::app()->agent->id));
			elseif($_GET['create']==-1)
			{
				$criteria->addCondition('`t`.`agent_id` !=:agent_id');
				$criteria->params[':agent_id']=Yii::app()->agent->id;
			}
		}
		// 搜索 代理商 项目 的 1吃，2住，3玩
		if(isset($_GET['agent_item']) && $_GET['agent_item'])
		{
			if($_GET['agent_item']==Items::items_eat)
			{
				$criteria->addCondition('`t`.`c_id` =:c_id');
				$criteria->params[':c_id']=Items::items_eat;
			}elseif($_GET['agent_item']==Items::items_hotel){
				$criteria->addCondition('`t`.`c_id` =:c_id');
				$criteria->params[':c_id']=Items::items_hotel;
			}elseif($_GET['agent_item']==Items::items_play){
				$criteria->addCondition('`t`.`c_id` =:c_id');
				$criteria->params[':c_id']=Items::items_play;
			}
		}

		$criteria->addColumnCondition(array(
			't.status'=>Items::status_online,
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
		//CSS AND JS
		$this->addCss(Yii::app()->request->baseUrl . '/css/agent/dist/css/bootstrap.min.css');
		$this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/step.css');
		$this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/box.css');
		$this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/form.css');
		$this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/style.css');

		//查询线路(点)的信息
		$shops_classliy=ShopsClassliy::getClass();
		$model=$this->loadModel($id,array(
			'with'=>array(
				'Dot_Shops'=>array('with'=>array('Shops_Agent')),
				'Dot_Pro'=>array('with'=>array(					
					'Pro_Items'=>array(
							'with'=>array(
									'Items_area_id_p_Area_id',
									'Items_area_id_m_Area_id',
									'Items_area_id_c_Area_id',
									'Items_ItemsImg',
									'Items_StoreContent'=>array('with'=>array('Content_Store')),
							),
					),
					'Pro_ItemsClassliy',
				)),
			),
			'condition'=>'`t`.`c_id`=:c_id AND `Dot_Shops`.`status`=0 AND `Dot_Shops`.`audit` !=:audit AND `Dot_Shops`.`agent_id`=:agent_id',
			'params'=>array(':c_id'=>$shops_classliy->id,':audit'=>Shops::audit_pending,':agent_id'=>Yii::app()->agent->id),
			'order'=>'Dot_Pro.sort',
		));
		//设置难属性
		$model->Dot_Shops->scenario='update_dot';
		//AJAX 验证
		$this->_Ajax_Verify($model->Dot_Shops,'dot-form');

		if(isset($_POST['Shops']) && isset($_POST['Pro'][0]['items_id']) && is_array($_POST['Pro']))
		{
			// 获得数据
			$model->Dot_Shops->attributes=$_POST['Shops'];
			$items_ids=array();
			//处理项目ID
			foreach ($_POST['Pro'] as $value)
			{
				if(isset($value['items_id']))
					$items_ids[]=$value['items_id'];
			}
			$validate_shops=$model->Dot_Shops->validate();
			//过滤项目ID
			if(! empty($items_ids))
				$items_models=Items::filter_items($items_ids,false);//过滤项目
			
			if(! isset($items_models))
			{
				$model->Dot_Shops->addError('name', '选择项目 不可空白');
				$validate_shops=false;
			}elseif(count($items_models) > Yii::app()->params['shops_dot_items_number']){
				$model->addError('select_items', '一个点 最多只能有'.Yii::app()->params['shops_dot_items_number'].'项目');
				$validate_shops=false;
			}elseif(count($items_ids) != count($items_models)){
				$model->Dot_Shops->addError('name', '选择的项目 没上线');
				$validate_shops=false;
			}
			
			$old_count=count($model->Dot_Pro);//原来的数量
			$new_count=count($items_models);//现在的数量
			//保存前的验证
			if($validate_shops)
			{
				//开启事务
				$transaction=$model->dbConnection->beginTransaction();
				try
				{
					//获取类型
					$this->_class_model='Dot';
					$shops_classliy=ShopsClassliy::getClass();
					$model->Dot_Shops->audit=Shops::audit_draft;//修改未提交
					if(! $model->Dot_Shops->save(false))
						throw new Exception("更新线路(点)主要表记录错误");
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
							if(! $pro_model->save(false))
								throw new Exception("更新线路(点)选中的项目记录错误");
						}
						if($new_count < $old_count)
						{
							$delete_models=array_slice($model->Dot_Pro,$new_count);
							foreach ($delete_models as $delete_model)
							{
								if(! $delete_model->delete())
									throw new Exception("删除更新线路(点)选中的项目记录错误");
							}
						}
						$return = $this->log('更新线路(点)',ManageLog::agent,ManageLog::update);
					}
					//提交事务
					$transaction->commit();
				}
				catch(Exception $e)
				{
					//回滚事务
					$transaction->rollBack();
					$this->error_log($e->getMessage(),ErrorLog::agent,ErrorLog::update,ErrorLog::rollback,__METHOD__);
				}
				//成功跳转到下一页面
				if(isset($return))
					$this->redirect(array('/agent/agent_shops/update_2','id'=>$model->id));	
			}
		}elseif(isset($_POST['Shops']) && ! isset($_POST['Pro'][0]['items_id'])){
			$model->Dot_Shops->attributes=$_POST['Shops'];
			$model->Dot_Shops->addError('name', $model->getAttributeLabel('Dot_Pro.items_id') . ' 不能空白');
		}

		$this->render('update',array_merge(
			array('model'=>$model),
			$this->search_items()
		));
	}

	/**
	 * 统计
	 * @return array
	 */
	public function dot_count()
	{
		$array=array();

		$criteria_dot = $this->dot_criterias();
		$criteria_dot->compare('`Dot_Shops`.`audit`','<>'.Shops::audit_draft);
		$criteria_dot->compare('`Dot_Shops`.`status`','>'.Shops::status_del);
		$array['dot'] = Dot::model()->count($criteria_dot);

		$criteria_nopass = $this->dot_criterias();
		$criteria_nopass->addColumnCondition(array(
			'`Dot_Shops`.`status`'=>Shops::status_offline,
			'`Dot_Shops`.`audit`'=>Shops::audit_nopass,
		));
		$array['nopass'] = Dot::model()->count($criteria_nopass);

		$criteria_draft = $this->dot_criterias();
		$criteria_draft->addColumnCondition(array(
			'`Dot_Shops`.`status`'=>Shops::status_offline,
			'`Dot_Shops`.`audit`'=>Shops::audit_draft,
		));
		$array['draft'] = Dot::model()->count($criteria_draft);
		return $array;
	}

	/**
	 * 搜索商家名称 项目名称
	 * @param unknown $criteria
	 */
	public function search_info($criteria)
	{
		if (isset($_GET['search_info']) && !empty($_GET['search_info']))
		{
			$criteria->params[':search_info']='%'.strtr(trim($_GET['search_info']),array('%'=>'\%', '_'=>'\_', '\\'=>'\\\\')).'%';
			$criteria->addCondition('(`Dot_Shops`.`name` LIKE :search_info)');
		}
	}

	/**
	 *管理页
	 */
	public function actionAdmin()
	{
		$this->addCss(Yii::app()->request->baseUrl . '/css/agent/dist/css/bootstrap.min.css');
		$this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/form.css');
		$this->addJs(Yii::app()->request->baseUrl . '/css/agent/script/jquery.masonry.min.js');
		//实例化公用类
		$criteria = $this->dot_criterias();
		$criteria->compare('`Dot_Shops`.`audit`','<>'.Shops::audit_draft);
		$criteria->compare('`Dot_Shops`.`status`','>'.Shops::status_del);

		// 搜索
		if (isset($_GET['search_status']) && !empty($_GET['search_status']))
		{
			$status_array=array(Shops::status_offline,Shops::status_online);
			$status = trim($_GET['search_status']);
			if (in_array($status,$status_array))
				$criteria->addColumnCondition(array('`Dot_Shops`.`status`'=>$status));
		}
		$this->search_info($criteria);

		$data= new CActiveDataProvider('Dot', array(
			'criteria'=>$criteria,
			'pagination'=>array(
				'pageSize'=>Yii::app()->params['admin_pageSize'],
			),
			'sort'=>array(
				'defaultOrder'=>'Dot_Shops.add_time desc', //设置默认排序
			)));
		$this->render('admin',array(
			'model'=>$data,
			'count'=>$this->dot_count(),
		));
	}

	/**
	 * 管理页-审核未通过
	 */
	public function actionAdmin_no_pass()
	{
		$this->addCss(Yii::app()->request->baseUrl . '/css/agent/dist/css/bootstrap.min.css');
		$this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/form.css');
		$this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/style.css');
		$this->addJs(Yii::app()->request->baseUrl . '/css/agent/script/jquery.masonry.min.js');

		//实例化公用类
		$criteria = $this->dot_criterias();
		//添加判断条件
		$criteria->compare('`Dot_Shops`.`audit`','='.Shops::audit_nopass);
		$criteria->compare('`Dot_Shops`.`status`','='.Shops::status_offline);
		//页面搜索
		$this->search_info($criteria);

		//执行SQL
		$data = new CActiveDataProvider('Dot', array(
			'criteria' => $criteria,
			'pagination' => array(
				'pageSize' => Yii::app()->params['admin_pageSize'],
			),
			'sort' => array(
				'defaultOrder' => 'Dot_Shops.add_time desc', //设置默认排序
			)));

		$this->render('admin_no_pass', array(
			'model' => $data,
			'count'=>$this->dot_count(),
		));
	}

	/**
	 * 管理页-草稿
	 */
	public function actionAdmin_draft()
	{
		$this->addCss(Yii::app()->request->baseUrl . '/css/agent/dist/css/bootstrap.min.css');
		$this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/form.css');
		$this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/style.css');
		$this->addJs(Yii::app()->request->baseUrl . '/css/agent/script/jquery.masonry.min.js');
		
		//实例化公用类
		$criteria = $this->dot_criterias();
		//添加判断条件
		$criteria->compare('`Dot_Shops`.`audit`','='.Shops::audit_draft);
		$criteria->compare('`Dot_Shops`.`status`','='.Shops::status_offline);
		//页面搜索
		$this->search_info($criteria);

		//执行SQL
		$data = new CActiveDataProvider('Dot', array(
			'criteria' => $criteria,
			'pagination' => array(
				'pageSize' => Yii::app()->params['admin_pageSize'],
			),
			'sort' => array(
				'defaultOrder' => 'Dot_Shops.add_time desc', //设置默认排序
			)));


		$this->render('admin_draft', array(
			'model' => $data,
			'count'=>$this->dot_count(),
		));

	}

	/**
	 * 公用 $criteria
	 */
	public function dot_criterias()
	{
		$this->_class_model='Dot';
		$shops_classliy = ShopsClassliy::getClass();
		$criteria = new CDbCriteria;

		$with=array(
			'Dot_Pro' => array(
				'with' => array(
					'Pro_Items' => array(
						'with' => array(
							'Items_ItemsImg'=>array('order'=>'rand()'),
							'Items_StoreContent' => array('with' => array('Content_Store')),
							'Items_area_id_p_Area_id',
							'Items_area_id_m_Area_id',
							'Items_area_id_c_Area_id',
						),
// 						'condition'=>'Pro_Items.status=1 AND Pro_Items.audit=:audit',
// 						'params'=>array(':audit'=>Items::audit_pass),
					),
					'Pro_ItemsClassliy',
				)
			),
			'Dot_Shops',
			'Dot_ShopsClassliy',
		);

		$criteria->with =$with;

		$criteria->addColumnCondition(array(
			'`t`.`c_id`'=>$shops_classliy->id,
			'`Dot_Shops`.`agent_id`'=>Yii::app()->agent->id
		));
		return $criteria;
	}


}
