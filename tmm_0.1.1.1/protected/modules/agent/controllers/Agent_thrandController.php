<?php
/**
 * 线路场景
 * @author Changhai Zhan
 *	创建时间：2015-09-09 18:34:39 */
class Agent_thrandController extends AgentController
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
	 * 统计
	 * @return multitype:string Ambigous <string, mixed, unknown>
	 */
	public function thrand_count()
	{
		$this->_class_model='Thrand';
		$shops_classliy=ShopsClassliy::getClass();
		$array=array();
		$criteria_thrand=new CDbCriteria;
		$criteria_thrand->addColumnCondition(array(
				'agent_id'=>Yii::app()->agent->id,
				'c_id'=>$shops_classliy->id,
		));
		$criteria_thrand->compare('audit','<>'.Shops::audit_draft);
		$criteria_thrand->compare('status','>'.Shops::status_del);
		$array['thrand']=Shops::model()->count($criteria_thrand);
	
		$criteria_nopass=new CDbCriteria;
		$criteria_nopass->addColumnCondition(array(
				'c_id'=>$shops_classliy->id,
				'agent_id'=>Yii::app()->agent->id,
				'status'=>Shops::status_offline,
				'audit'=>Shops::audit_nopass,
		));
		$array['nopass']=Shops::model()->count($criteria_nopass);
	
		$criteria_draft=new CDbCriteria;
		$criteria_draft->addColumnCondition(array(
				'c_id'=>$shops_classliy->id,
				'agent_id'=>Yii::app()->agent->id,
				'status'=>Shops::status_offline,
				'audit'=>Shops::audit_draft,
		));
		$array['draft']=Shops::model()->count($criteria_draft);
		return $array;
	}

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
					'Thrand_ShopsClassliy',
					'Thrand_Shops'=>array('with'=>array('Shops_Agent')),
					'Thrand_Pro'=>array('with'=>array(
							'Pro_Thrand_Dot'=>array(
									'with'=>array(											
											'Dot_Shops'=>array(
// 													'condition'=>'`Dot_Shops`.`status`=1 AND `Dot_Shops`.`audit`=:audit',
// 													'params'=>array(':audit'=>Shops::audit_pass),
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
// 									'condition'=>'`Pro_Items`.`status`=1 AND `Pro_Items`.`audit`=:audit',
// 									'params'=>array(':audit'=>Items::audit_pass),
							),
							'Pro_ItemsClassliy',
							'Pro_ProFare'=>array(
									'with'=>array('ProFare_Fare'),
							),
					)),
				),
				'condition'=>'`t`.`c_id`=:c_id AND `Thrand_Shops`.`agent_id`=:agent_id AND `Thrand_Shops`.`status`>:status',
				'params'=>array(':c_id'=>$shops_classliy->id,':agent_id'=>Yii::app()->agent->id,':status'=>Shops::status_del),
				'order'=>'Thrand_Pro.day_sort,Thrand_Pro.half_sort,Thrand_Pro.sort',
		));

		$model->Thrand_TagsElement=TagsElement::get_select_tags(TagsElement::tags_shops_thrand,$id);

		$this->render('view',array('model'=>$model,));
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
				$criteria->addColumnCondition(array(
						'`Dot_Shops`.`agent_id`'=>Yii::app()->agent->id,
				));
			}elseif($_GET['create']==-1){		
				$criteria->addCondition('`Dot_Shops`.`agent_id` !=:agent_id');
				$criteria->params[':agent_id']=Yii::app()->agent->id;
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
	public function actionCreate()
	{	
		$this->addCss(Yii::app()->request->baseUrl . '/css/agent/dist/css/bootstrap.min.css');
		$this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/step.css');
		$this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/form.css');
		$this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/box.css');
				
		$shops_classliy = ShopsClassliy::getClass();
		$model=new Thrand;//线的主要表
		$model->Thrand_Shops=new Shops;//商品表
		$model->Thrand_Shops->scenario='create_thrand';	
		
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
						$model->Thrand_Shops->agent_id=Yii::app()->agent->id;
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
							//继承点的tags
							foreach ($dot_ids as $dot_id)
								$element_ids[]=array(TagsElement::tags_shops_dot,$dot_id);
							TagsElement::select_tags_ids_save(TagsElement::select_tags_all($element_ids), $model->Thrand_Shops->id, TagsElement::tags_shops_thrand,TagsElement::agent);					
							//日志	
							$return=$this->log('创建线路(线)',ManageLog::agent,ManageLog::create);
						}else
							throw new Exception("审核通过保存错误");
						$transaction->commit();
					}
					catch(Exception $e)
					{
						//事务回滚
						$transaction->rollBack();
						$this->error_log($e->getMessage(),ErrorLog::agent,ErrorLog::create,ErrorLog::rollback,__METHOD__);
					}			
				}
			}
			if(isset($return))
				$this->redirect(array('/agent/agent_shops/update_2','id'=>$model->id));	//添加标签
		}

		$this->render('update',array(
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
				'condition'=>'`t`.`c_id`=:c_id AND `Thrand_Shops`.`agent_id`=:agent_id AND `Thrand_Shops`.`status`=:status AND `Thrand_Shops`.`audit` != :audit',
				'params'=>array(':c_id'=>$c_id,':agent_id'=>Yii::app()->agent->id,':status'=>Shops::status_offline,':audit'=>Shops::audit_pending),
				'order'=>'Thrand_Pro.day_sort,Thrand_Pro.half_sort,Thrand_Pro.sort',
		));
	}
	
	/**
	 * 更新
	 * @param integer $id
	 */
	public function actionUpdate($id)
	{
		$this->addCss(Yii::app()->request->baseUrl . '/css/agent/dist/css/bootstrap.min.css');
		$this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/step.css');
		$this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/form.css');
		$this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/box.css');
		
		$shops_classliy=ShopsClassliy::getClass();
		$model=$this->new_model($id,$shops_classliy->id);
		
		$model->Thrand_Shops->scenario='create_thrand';
		//设置验证场景
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
						$model->Thrand_Shops->agent_id=Yii::app()->agent->id;
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
							$return=$this->log('修改线路(线)',ManageLog::agent,ManageLog::update);
						}else
							throw new Exception("修改线路主要记录错误");
						$transaction->commit();
					}
					catch(Exception $e)
					{
						$transaction->rollBack();
						$this->error_log($e->getMessage(),ErrorLog::agent,ErrorLog::create,ErrorLog::rollback,__METHOD__);
					}			
				}
			}
			if(isset($return))
				$this->redirect(array('/agent/agent_shops/update_2','id'=>$model->id));	
		}elseif(isset($_POST['Shops'])){
			$model->Thrand_Shops->addError('name','选中点 不能空白');
		}

		$this->render('create',array(
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
			'`t`.`c_id`'=>$shops_classliy->id,
			'`Thrand_Shops`.`agent_id`'=>Yii::app()->agent->id,
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
	
	/**
	 *管理页
	 */
	public function actionAdmin()
	{
		$this->addCss(Yii::app()->request->baseUrl . '/css/agent/dist/css/bootstrap.min.css');
		$this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/form.css');

		$model=new Thrand;
		$criteria=$this->criteria();
		$criteria->compare('`Thrand_Shops`.`audit`','<>'.Shops::audit_draft);
		$criteria->compare('`Thrand_Shops`.`status`','>'.Shops::status_del);

		// 搜索
		if (isset($_GET['search_status']) && !empty($_GET['search_status']))
		{
			$status_array=array(Shops::status_offline,Shops::status_online);
			$status = trim($_GET['search_status']);
			if (in_array($status,$status_array))
				$criteria->addColumnCondition(array('`Thrand_Shops`.`status`'=>$status));
		}
		$this->search_info($criteria);

		$this->render('admin',array(
			'model'=>$model->search($criteria),
			'count'=>$this->thrand_count()
		));
	}

	/**
	 *审核不通过管理页
	 */
	public function actionAdmin_no_pass()
	{
		$this->addCss(Yii::app()->request->baseUrl . '/css/agent/dist/css/bootstrap.min.css');
		$this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/form.css');

		$model=new Thrand;
		$criteria=$this->criteria();
		$criteria->addColumnCondition(array(
			'`Thrand_Shops`.`status`'=>Shops::status_offline,
			'`Thrand_Shops`.`audit`'=>Shops::audit_nopass,
		));
		$this->search_info($criteria);

		$this->render('admin_no_pass',array(
			'model'=>$model->search($criteria),
			'count'=>$this->thrand_count()
		));
	}

	/**
	 *草稿页管理页
	 */
	public function actionAdmin_draft()
	{
		$this->addCss(Yii::app()->request->baseUrl . '/css/agent/dist/css/bootstrap.min.css');
		$this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/form.css');

		$model=new Thrand;
		$criteria=$this->criteria();
		$criteria->addColumnCondition(array(
			'`Thrand_Shops`.`status`'=>Shops::status_offline,
			'`Thrand_Shops`.`audit`'=>Shops::audit_draft,
		));
		$this->search_info($criteria);

		$this->render('admin_draft',array(
			'model'=>$model->search($criteria),
			'count'=>$this->thrand_count()
		));
	}
	

}
