<?php
/**
 * 
 * @author Changhai Zhan
 *	创建时间：2015-09-17 10:58:18 */
class ShopsController extends ApiController
{
	/**
	 * 默认操作数据模型
	 * @var string
	 */
	public $_class_model='Shops';

	/**
	 * 获取tag搜索
	 * @param unknown $domain
	 * @param unknown $search_tags
	 * @param string $url
	 * @return multitype:
	 */
	public function search_tags_type($domain,$search_tags,$url='/api/shops/index')
	{
		$criteria=new CDbCriteria;
		$criteria->together = true;
		$criteria->with=array(
			'TagsType_TagsType'=>array(
				'condition'=>'`TagsType_TagsType`.`status`=1 AND `TagsType_TagsType`.`p_id`=0',
			),
		);
		$criteria->condition='`t`.`p_id` !=0 AND `t`.`status`=1 AND `t`.`is_search`=:yes_is_search';	
		$criteria->params=array(':yes_is_search'=>TagsType::yes_is_search);
		$criteria->order='`TagsType_TagsType`.`sort`,`t`.`sort`';
	
		$models=TagsType::model()->findAll($criteria);
		$search=array();
		foreach ($models as $model)
		{
			$search[$model->TagsType_TagsType->id]['name']=CHtml::encode($model->TagsType_TagsType->name);
			$search[$model->TagsType_TagsType->id]['value']=$model->TagsType_TagsType->id;
			$search[$model->TagsType_TagsType->id]['sort']=$model->TagsType_TagsType->sort;
			$search[$model->TagsType_TagsType->id]['son'][]=array(
					'name'=>CHtml::encode($model->name),
					'value'=>$model->id,
					'link'=>$domain.Yii::app()->createUrl($url,array($search_tags=>$model->id)),
			);
		}
		$search = array_values($search);
		return array_splice($search,0,Yii::app()->params['search_tags_type_shops']);	//去除多余的
	}
	
	/**
	 * 添加地址搜索条件
	 * @param unknown $criteria
	 * @param unknown $province
	 * @param unknown $city
	 * @param string $district
	 */
	public function search_area($criteria,$province,$city,$district='')
	{
		$criteria->addColumnCondition(array(
			'`Pro_Items`.`area_id_p`'=>$province,
			'`Pro_Items`.`area_id_m`'=>$city,
		));
		//可以为空
		if($district != '')
			$criteria->addColumnCondition(array('`Pro_Items`.`area_id_c`'=>$district));
	}

	/**
	 * 	没设置城市  gps 没有 距离显示 定位失败 无搜索条件 排序商品时间倒序
	 * 
	 * 没设置城市  gps 有 距离显示  m / km 无搜索条件 排序距离 时间排序倒序
	 * 
	 * 设置城市 gps 没有 距离显示 定位失败 有搜索条件（当前设置城市）
	 * 
	 * 设置城市 gps 有 距离显示 m / km 有搜索条件（当前设置城市）
	 * 
	 * 兼容上版接口
	 * 
	 * 搜索条件 商品名称，项目名称，列表介绍 详情介绍 地址搜索
	 * 
	 * 点 至少有一个项目
	 * 
	 * 线 有一个项目下线 线路不显示
	 * 
	 * 活动 项目不影响 不显示私密活动 //无法计算 项目修改经纬度会影响活动距离计算	
	 */
	public function actionIndex()
	{	
		//gps 的 信息
		$getGps=Yii::app()->cookie->getCookie(Yii::app()->params['gps']);
		$exist_Gps=$getGps==null?false:true;
		//设置的 城市
		$getCity=Yii::app()->cookie->getCookie(Yii::app()->params['orientation']);
		$exist_City=$getCity==null?false:true;
		
		$criteria=new CDbCriteria;
		$criteria->with=array(
				'Pro_Shops'=>array(
					'with'=>array('Shops_Actives', ),
				),
				'Pro_ShopsClassliy'=>array('select'=>'`id`,`name`,`admin`,`append`'),
				'Pro_Items'=>array(
					'select'=>'`id`',
					'with'=>array(
						'Items_area_id_p_Area_id'=>array('select'=>'name'),
						'Items_area_id_m_Area_id'=>array('select'=>'name'),
						'Items_area_id_c_Area_id'=>array('select'=>'name'),
					)
				)
		);
		//条件
		$this->search_centre($criteria);									//核心条件
		$this->search_info($criteria);										//搜索
		$this->search_tags($criteria);										//tags 搜索
		$this->search_classliy($criteria);									//添加点表单
		$this->search_dot_thrand($criteria);							//选择 点 或者 线 活动
		$order_brow = $this->order_brow($criteria);			// 通过 浏览量排序
		
		$distance=false;															//是否有计算距离定位
		if($exist_City==false && $exist_Gps==false)
		{
			$criteria->order='`Pro_Shops`.`tops` desc,`Pro_Shops`.`tops_time` desc,'.$order_brow.'`Pro_Shops`.`up_time` desc,`Pro_Items`.`up_time` desc';
		}
		elseif($exist_City==false && $exist_Gps==true)
		{
			if(isset($getGps['address_info']['location']['lng'],$getGps['address_info']['location']['lat']))
			{
				$select='MIN(ROUND(6378.138*2*ASIN(SQRT(POW(SIN(( :lat *PI()/180-`Pro_Items`.`lat`*PI()/180)/2) , 2)+COS( :lat *PI()/180)*COS(`Pro_Items`.`lat`*PI()/180)*POW(SIN(( :lng *PI()/180-`Pro_Items`.`lng`*PI()/180)/2),2)))*1000)) AS distance';
				$criteria->params[':lat']=$getGps['address_info']['location_accurate']['lat'];
				$criteria->params[':lng']=$getGps['address_info']['location_accurate']['lng'];
				$criteria->select=array($select);
				$criteria->order='`Pro_Shops`.`tops` desc,`Pro_Shops`.`tops_time` desc,'.$order_brow.'distance,`Pro_Shops`.`up_time` desc,`Pro_Items`.`up_time` desc';
				$distance=true;
			}
		}
		elseif($exist_City==true && $exist_Gps==false)
		{
			if(isset($getCity['address_info']['province']['value'],$getCity['address_info']['city']['value'],$getCity['address_info']['district']['value']))
			{
				if(! isset($_GET['is_area']))
					$this->search_area($criteria, $getCity['address_info']['province']['value'], $getCity['address_info']['city']['value'],$getCity['address_info']['district']['value']);

				$criteria->order='`Pro_Shops`.`tops` desc,`Pro_Shops`.`tops_time` desc,'.$order_brow.'`Pro_Shops`.`up_time` desc,`Pro_Items`.`up_time` desc';
			}
		}
		elseif($exist_City==true && $exist_Gps==true)
		{
			if(isset($getGps['address_info']['location']['lng'],$getGps['address_info']['location']['lat'],$getCity['address_info']['province']['value'],$getCity['address_info']['city']['value'],$getCity['address_info']['district']['value']))
			{
				$select='MIN(ROUND(6378.138*2*ASIN(SQRT(POW(SIN(( :lat *PI()/180-`Pro_Items`.`lat`*PI()/180)/2) , 2)+COS( :lat *PI()/180)*COS(`Pro_Items`.`lat`*PI()/180)*POW(SIN(( :lng *PI()/180-`Pro_Items`.`lng`*PI()/180)/2),2)))*1000)) AS distance';
				$criteria->params[':lat']=$getGps['address_info']['location']['lat'];
				$criteria->params[':lng']=$getGps['address_info']['location']['lng'];
				$criteria->select=array($select);
				if(! isset($_GET['is_area']))
					$this->search_area($criteria, $getCity['address_info']['province']['value'], $getCity['address_info']['city']['value'],$getCity['address_info']['district']['value']);
				$criteria->order='`Pro_Shops`.`tops` desc,`Pro_Shops`.`tops_time` desc,'.$order_brow.'distance,`Pro_Shops`.`up_time` desc,`Pro_Items`.`up_time` desc';
				$distance=true;
			}
		}			
		$criteria->group='`t`.`shops_id`';
		//商品
		$criteria->addColumnCondition(array(
				'`Pro_Shops`.`status`'=>Shops::status_online,	//上线
				'`Pro_Shops`.`audit`'=>Shops::audit_pass,			//审核通过
		));		
		$count = Pro::model()->count($criteria);
		
		$return=array();
		//分页设置
		$return['page']=$this->page($criteria, $count, Yii::app()->params['api_pageSize']['ts_shops'], Yii::app()->params['app_api_domain']);
		//根据条件查询
		$models = Pro::model()->findAll($criteria);
		//分页数据
		$return['list_data']=$this->list_data($models, Yii::app()->params['app_api_domain'],$distance);
		
		if(empty($return['list_data']))
		{
			$return['list_data']=array();
			$return['null']='小觅已经很努力了！';
		}
		$return['location']=array(
			'name'=>'GPS',
			'value'=>$getGps,
		);
		$return['orientation']=array(
			'name'=>'orientation',
			'value'=>$getCity,
		);
		$return['search']=$this->search_tags_type(Yii::app()->params['app_api_domain'],'search_tags');
		$return['search_tags']='search_tags';
		$return['search_info']='search_info';
		$return['add_order_dot']='add_order_dot';
		$return['order_brow']='order_brow';
		$return['is_area']='is_area';
		$return['select_dot_thrand']=array('all','dot','thrand','actives');
		$this->send($return);
	}

	/**
	 * 按浏览量排序
	 * @param $criteria
	 * @return string
	 */
	public function order_brow($criteria)
	{
		if(isset($_GET['order_brow']) && !empty($_GET['order_brow']) && !is_array($_GET['order_brow']) && $_GET['order_brow']=='order_brow' )
		{
			return '`Pro_Shops`.`brow` desc,';
		}
		return '';
	}
	
	/** 
	 * 商品核心条件
	 * 
	 * 点 至少有一个项目
	 * 
	 * 线 有一个项目下线 线路不显示
	 * 
	 * 活动 项目不影响 不显示私密活动 显示开放
	 * @param unknown $criteria
	 */
	public function search_centre($criteria)
	{	
		//点的条件
		$this->_class_model = 'Dot';
		$dot = ShopsClassliy::getClass();
		$condition_dot = array(
			'`t`.`shops_c_id`=:dot',
			'`Pro_Items`.`status`=:dot_status',
			'`Pro_Items`.`audit`=:dot_audit',
		);
		$criteria->params[':dot'] = $dot->id;														//点
		$criteria->params[':dot_status'] = Items::status_online;						//上线
		$criteria->params[':dot_audit'] = Items::audit_pass;								//审核通过
		//线的条件
		$this->_class_model = 'Thrand';
		$thrand = ShopsClassliy::getClass();	
		$condition_thrand = array(
			'`t`.`shops_c_id`=:thrand',		
			'(`Pro_Items`.`status`!=:thrand_status_offline OR `Pro_Items`.`status`!=:thrand_status_del	OR `Pro_Items`.`audit`!=:thrand_audit_nopass OR `Pro_Items`.`audit`!=:thrand_audit_pending OR `Pro_Items`.`audit`!=:thrand_audit_draft)',
		);
		$criteria->params[':thrand'] = $thrand->id;											//线
 		$criteria->params[':thrand_status_offline'] = Items::status_offline;		//下线
 		$criteria->params[':thrand_status_del'] = Items::status_del;					//删除
 		$criteria->params[':thrand_audit_nopass'] = Items::audit_nopass;		//审核不通过
		$criteria->params[':thrand_audit_pending'] = Items::audit_pending;	//审核中
		$criteria->params[':thrand_audit_draft'] = Items::audit_draft;				//未提交
		//活动的条件
 		$this->_class_model='Actives';
 		$actives = ShopsClassliy::getClass();
		$condition_actives = array(
			'`t`.`shops_c_id`=:Actives',
			'`Shops_Actives`.`is_open`=:actives_is_open',
		);
		$criteria->params[':Actives'] = $actives->id;											//活动
		$criteria->params[':actives_is_open'] = Actives::is_open_yes;				//开放
		$criteria->addCondition(implode(' OR ', array('('.implode(' AND ',$condition_dot).')','('.implode(' AND ',$condition_thrand).')','('.implode(' AND ',$condition_actives).')')));
	}
	
	/**
	 * 搜索条件设置 搜索条件 商品名称，项目名称，列表介绍 详情介绍 地址搜索(拼音)
	 * @param unknown $criteria
	 */
	public function search_info($criteria)
	{
		if(isset($_GET['search_info']) && !empty($_GET['search_info']) && !is_array($_GET['search_info']))
		{
			if ($_GET['search_info'] != '' && preg_match ("/^[A-Za-z]/", $_GET['search_info']))
			{
				$criteria->params[':search_info']='%'.implode('%', str_split($_GET['search_info'])).'%';
				$condition=array(
						'`Pro_Shops`.`name` LIKE :search_info',
						'`Pro_Items`.`name` LIKE :search_info',
						'`Pro_Shops`.`list_info` LIKE :search_info',
						'`Pro_Shops`.`page_info` LIKE :search_info',
						'`Items_area_id_p_Area_id`.`nid` LIKE :search_info',
						'`Items_area_id_m_Area_id`.`nid` LIKE :search_info',
						'`Items_area_id_c_Area_id`.`nid` LIKE :search_info',
				);
				$criteria->addCondition(implode(' OR ', $condition));
			}
			else
			{			
				$criteria->params[':search_info']='%'.strtr($_GET['search_info'],array('%'=>'\%', '_'=>'\_', '\\'=>'\\\\')).'%';
				$condition=array(
						'`Pro_Shops`.`name` LIKE :search_info',
						'`Pro_Items`.`name` LIKE :search_info',
						'`Pro_Shops`.`list_info` LIKE :search_info',
						'`Pro_Shops`.`page_info` LIKE :search_info',
						'`Items_area_id_p_Area_id`.`name` LIKE :search_info',
						'`Items_area_id_m_Area_id`.`name` LIKE :search_info',
						'`Items_area_id_c_Area_id`.`name` LIKE :search_info',
				);
				$criteria->addCondition(implode(' OR ', $condition));
			}
		}
	}
	
	/**
	 * 搜索条件设置
	 * @param unknown $criteria
	 */
	public function search_info_praise($criteria)
	{
		if(isset($_GET['search_info']) && !empty($_GET['search_info']) && !is_array($_GET['search_info']))
		{
			$criteria->params[':search_info']='%'.strtr($_GET['search_info'],array('%'=>'\%', '_'=>'\_', '\\'=>'\\\\')).'%';
			$condition='`Collect_Shops`.`name` LIKE :search_info OR `Collect_Shops`.`list_info` LIKE :search_info OR `Collect_Shops`.`page_info` LIKE :search_info';
			$criteria->addCondition($condition);
		}
	}
	
	/**
	 * tags 搜索
	 * @param unknown $criteria
	 */
	public function search_tags($criteria)
	{
		if(isset($_GET['search_tags']) && !empty($_GET['search_tags']) && !is_array($_GET['search_tags']))
		{
			$model=TagsType::model()->findByPk($_GET['search_tags'],'`p_id` !=0 AND `is_search`=:is_search AND `status`=1',array(':is_search'=>TagsType::yes_is_search));
			if($model)
			{
				$tags=TagsSelect::get_type_select_tags($model->id);
				if(!empty($tags))
				{
					$condition=array();
					foreach ($tags as $key=>$tag)
					{
						$condition[]=' FIND_IN_SET(:tag_'.$key.',`Pro_Shops`.`tags_ids`) ';
						$criteria->params[':tag_'.$key] = $tag;
					}
					$criteria->addCondition( implode(' OR ', $condition) );
				}
			}
		}
	}
	
	/**
	 * 添加搜索点
	 * @param unknown $criteria
	 */
	public function search_classliy($criteria)
	{
		if(isset($_GET['add_order_dot']) && !empty($_GET['add_order_dot']) && !is_array($_GET['add_order_dot']) && $_GET['add_order_dot']=='add_order_dot')
		{
			$this->_class_model='User';
			$this->loadModel(Yii::app()->api->id,'status=1');
			$this->_class_model='Dot';			
			$model=ShopsClassliy::getClass();
			$criteria->addColumnCondition(array('`Pro_Shops`.`c_id`'=>$model->id));
		}
	}
	
	/**
	 * 添加搜索点,线
	 * @param unknown $criteria
	 */
	public function search_dot_thrand($criteria)
	{
		if(isset($_GET['select_dot_thrand']) && !empty($_GET['select_dot_thrand']) && !is_array($_GET['select_dot_thrand']) && in_array($_GET['select_dot_thrand'],array('all','dot','thrand','actives')))
		{
			$this->_class_model='User';
			$this->loadModel(Yii::app()->api->id,'status=1');
			if($_GET['select_dot_thrand']=='dot')
				$this->_class_model='Dot';
			elseif($_GET['select_dot_thrand']=='thrand')
				$this->_class_model='Thrand';
			elseif($_GET['select_dot_thrand']=='actives')
				$this->_class_model='Actives';
			else
				return false;				
			$model=ShopsClassliy::getClass();
			$criteria->addColumnCondition(array('`Pro_Shops`.`c_id`'=>$model->id));
		}
	}
	
	/**
	 * 分页数据处理
	 * @param unknown $models
	 * @param unknown $domain
	 * @return multitype:string NULL multitype:number string  multitype:NULL
	 */
	public function list_data($models,$domain,$distance)
	{
		$return=array();
		foreach ($models as $key=>$model)
		{
			$img=$this->litimg_path($model->Pro_Shops->list_img);
			$share_img=$this->litimg_path($model->Pro_Shops->list_img,'litimg_share',$img);
			$actives = array();
			if(isset($model->Pro_Shops->Shops_Actives) && $model->Pro_Shops->Shops_Actives)
			{
				$actives['actives_status'] = array(
						'name'=>Actives::$_actives_status[$model->Pro_Shops->Shops_Actives->actives_status],
						'value'=>$model->Pro_Shops->Shops_Actives->actives_status,
				);
				$actives['actives_type'] =array(
						'name'=>Actives::$_actives_type[$model->Pro_Shops->Shops_Actives->actives_type],
						'value'=>$model->Pro_Shops->Shops_Actives->actives_type,
				);
				$actives['actives_pay_type'] =array(
					'name'=>Actives::$_pay_type[$model->Pro_Shops->Shops_Actives->pay_type],
					'value'=>$model->Pro_Shops->Shops_Actives->pay_type,
				);
			}
			$return[$key]=array(
					'day_num' =>Pro::get_day_num($model->shops_id),
					'collect_count'=> Collect::get_collect_count(Collect::collect_type_praise,$model->Pro_Shops->id),
					'value'=>$model->Pro_Shops->id,
					'price'=>Shops::get_price_num($model->Pro_Shops->id,$model->Pro_ShopsClassliy->append,true),
					'down'=>Shops::get_down($model->Pro_Shops->id,$model->Pro_ShopsClassliy->append),
					'brow'=>$model->Pro_Shops->brow,
					'share'=>$model->Pro_Shops->share,
					'praise'=>$model->Pro_Shops->praise,
					'link'=>$domain.Yii::app()->createUrl('/api/'.$model->Pro_ShopsClassliy->admin.'/view',array('id'=>$model->Pro_Shops->id)),
					'name'=>CHtml::encode($model->Pro_Shops->name),
					'info'=>CHtml::encode($model->Pro_Shops->list_info),
					'image'=>empty($img)?'':Yii::app()->params['admin_img_domain'].ltrim($img,'.'),
					'share_image'=>empty($share_img)?'':Yii::app()->params['admin_img_domain'].ltrim($share_img,'.'),
					'classliy'=>array(
							'name'=>CHtml::encode($model->Pro_ShopsClassliy->name),
							'value'=>$model->Pro_ShopsClassliy->id,
					),
					'top'=>array(
						'name'=>Shops::$_tops[$model->Pro_Shops->tops],
						'value'=>$model->Pro_Shops->tops,
					),
					'selected_tops'=>array(
							'name'=>Shops::$_selected_tops[$model->Pro_Shops->selected_tops],
							'value'=>$model->Pro_Shops->selected_tops,
					),
					'type'=>array(
							'name'=>$model->Pro_ShopsClassliy->append=='Actives'?($model->Pro_Shops->Shops_Actives->actives_type==Actives::actives_type_tour ? '觅趣' : '觅鲜'):($model->Pro_ShopsClassliy->append=='Dot'?'景点':'线路'),
							'value'=>$model->Pro_ShopsClassliy->id,
					),
					'actives'=>$actives,
			);
			if($distance)
				$return[$key]['distance']=$this->FormatDistance($model->distance);
			else 
				$return[$key]['distance']='';
		}	
		return $return;		
	}
	
	/**
	 * 分页数据处理
	 * @param unknown $models
	 * @param unknown $domain
	 * @return multitype:string NULL multitype:number string  multitype:NULL
	 */
	public function list_data_praise($models,$domain)
	{
		$return=array();
		foreach ($models as $model)
		{
			$img=$this->litimg_path($model->Collect_Shops->list_img);
			$share_img=$this->litimg_path($model->Collect_Shops->list_img,'litimg_share',$img);
			$actives = array();
			if(isset($model->Collect_Shops->Shops_Actives) && $model->Collect_Shops->Shops_Actives)
			{
				$actives['actives_status'] = array(
						'name'=>Actives::$_actives_status[$model->Collect_Shops->Shops_Actives->actives_status],
						'value'=>$model->Collect_Shops->Shops_Actives->actives_status,
				);
				$actives['actives_type'] =array(
						'name'=>Actives::$_actives_type[$model->Collect_Shops->Shops_Actives->actives_type],
						'value'=>$model->Collect_Shops->Shops_Actives->actives_type,
				);
			}
			$return[]=array(
					'value'=>$model->Collect_Shops->id,
					'link'=>$domain.Yii::app()->createUrl('/api/'.$model->Collect_Shops->Shops_ShopsClassliy->admin.'/view',array('id'=>$model->Collect_Shops->id)),
					'name'=>CHtml::encode($model->Collect_Shops->name),
					'info'=>CHtml::encode($model->Collect_Shops->list_info),
					'image'=>empty($img)?'':Yii::app()->params['admin_img_domain'].ltrim($img,'.'),
					'share_image'=>empty($share_img)?'':Yii::app()->params['admin_img_domain'].ltrim($share_img,'.'),
					'classliy'=>array(
							'name'=>CHtml::encode($model->Collect_Shops->Shops_ShopsClassliy->name),
							'value'=>$model->Collect_Shops->Shops_ShopsClassliy->id,
					),
					'type'=>array(
							'name'=>$model->Collect_Shops->Shops_ShopsClassliy->append=='Actives'?($model->Collect_Shops->Shops_Actives->actives_type==Actives::actives_type_tour ? '觅趣' : '觅鲜'):($model->Collect_Shops->Shops_ShopsClassliy->append=='Dot'?'景点':'线路'),
							'value'=>$model->Collect_Shops->Shops_ShopsClassliy->id,
					),
					'actives'=>$actives,
			);
		}
		return $return;
	}
	
	/**
	 * 推荐列表页
	 * 
	 * gps 没有 距离显示 定位失败 时间排序倒序
	 * 
	 * gps 有 距离显示 m / km
	 * 
	 */
	public function actionSelected()
	{
		//gps 的 信息
		$getGps=Yii::app()->cookie->getCookie(Yii::app()->params['gps']);
		$exist_Gps=$getGps==null?false:true;
			
		$criteria=new CDbCriteria;
		$criteria->with=array(
				'Pro_Shops'=>array('with'=>'Shops_Actives'),
				'Pro_ShopsClassliy'=>array('select'=>'`id`,`name`,`admin`,`append`'),
				'Pro_Items'=>array(
						'select'=>'`id`',
						'with'=>array(
								'Items_area_id_p_Area_id'=>array('select'=>'name'),
								'Items_area_id_m_Area_id'=>array('select'=>'name'),
								'Items_area_id_c_Area_id'=>array('select'=>'name'),
						)
				)
		);
		$this->search_centre($criteria);			//核心条件
		$this->search_info($criteria);				//搜索
	
		$distance=false;//是否有计算距离定位
		if($exist_Gps==true && isset($getGps['address_info']['location']['lng'],$getGps['address_info']['location']['lat']))
		{
			$select='MIN(ROUND(6378.138*2*ASIN(SQRT(POW(SIN(( :lat *PI()/180-`Pro_Items`.`lat`*PI()/180)/2) , 2)+COS( :lat *PI()/180)*COS(`Pro_Items`.`lat`*PI()/180)*POW(SIN(( :lng *PI()/180-`Pro_Items`.`lng`*PI()/180)/2),2)))*1000)) AS distance';
			$criteria->params[':lat']=$getGps['address_info']['location_accurate']['lat'];
			$criteria->params[':lng']=$getGps['address_info']['location_accurate']['lng'];
			$criteria->select=array($select);
			$criteria->order='`Pro_Shops`.`selected_tops` desc,`Pro_Shops`.`selected_tops_time` desc,distance,`Pro_Shops`.`up_time` desc,`Pro_Items`.`up_time` desc';
			$distance=true;
		}else 
			$criteria->order='`Pro_Shops`.`selected_tops` desc,`Pro_Shops`.`selected_tops_time` desc,`Pro_Shops`.`up_time` desc,`Pro_Items`.`up_time` desc';
		
		$criteria->group='`t`.`shops_id`';
		//商品
		$criteria->addColumnCondition(array(
				'`Pro_Shops`.`status`'=>Shops::status_online,		//上线
				'`Pro_Shops`.`audit`'=>Shops::audit_pass,				//审核通过
				'`Pro_Shops`.`selected`'=>Shops::selected,			//审核通过
		));
		$count = Pro::model()->count($criteria);
		
		$return=array();
		//分页设置
		$return['page']=$this->page($criteria, $count, Yii::app()->params['api_pageSize']['ts_shops'], Yii::app()->params['app_api_domain']);
		//根据条件查询
		$models = Pro::model()->findAll($criteria);
		//分页数据
		$return['list_data']=$this->list_data($models, Yii::app()->params['app_api_domain'],$distance);
		
		if(empty($return['list_data']))
		{
			$return['list_data']=array();
			$return['null']='小觅已经很努力了！';
		}
		$return['search_info']='search_info';
		
		$this->send($return);
	}

	/**
	 *攒 列表页
	 */
	public function actionPraise()
	{
		$criteria=new CDbCriteria;
		$criteria->with=array(
			'Collect_Shops'=>array(
				'with'=>array(	
					'Shops_ShopsClassliy',
					'Shops_Actives',
				),
			),
		);
		$this->search_info_praise($criteria);
		$criteria->addColumnCondition(array(
				't.is_collect'=>Collect::is_collect,
				'Collect_Shops.status'=>Shops::status_online,
				'Collect_Shops.audit'=>Shops::audit_pass,
				't.user_id'=>Yii::app()->api->id,
		));
		$criteria->order='`t`.`up_time` desc';
		$count = Collect::model()->count($criteria);
	
		$return=array();
		//分页设置
		$return['page']=$this->page($criteria, $count, Yii::app()->params['api_pageSize']['ts_shops'], Yii::app()->params['app_api_domain']);
		//根据条件查询
		$models = Collect::model()->findAll($criteria);
		//分页数据
		$return['list_data']=$this->list_data_praise($models, Yii::app()->params['app_api_domain']);
		//用户赞总数
		$return['user_collect_count']=$count;

		if(empty($return['list_data']))
		{
			$return['list_data']=array();
			$return['null']='小觅已经很努力了！';
		}
		$return['search_name']='search_info';
		$this->send($return);
	}
	
	/**
	 * 收集(点赞)
	 */
	public function actionCollect()
	{
		if(isset($_POST['Collect']['shops_id']) && !empty($_POST['Collect']['shops_id']) && !is_array($_POST['Collect']['shops_id']))
		{
			$this->_class_model='Shops';
			$shops = $this->loadModel($_POST['Collect']['shops_id'], 'status=1 AND audit=:audit',array(':audit'=>Shops::audit_pass));

			$model = Collect::model()->find('`c_id`=:c_id AND `shops_id`=:shops_id AND `user_id`=:user_id',array(':c_id'=>$shops->c_id,':shops_id'=>$shops->id,':user_id'=>Yii::app()->api->id));
			if(! $model)
				$model = new Collect;
			$this->_class_model='User';
			$model->Collect_User=$this->loadModel(Yii::app()->api->id,'status=1');
			$model->scenario='create';
			$model->attributes=$_POST['Collect'];

			if ($model->validate()) //验证
			{
				if ($model->isNewRecord)
				{
					$model->user_id = Yii::app()->api->id;
					$model->c_id = $shops->c_id;
					$model->is_collect = Collect::is_collect;
				} 
				else
				{
					if($model->is_collect == Collect::is_collect)
						$model->is_collect = Collect::not_is_collect;
					else
						$model->is_collect = Collect::is_collect;
				}
				if($model->save(false) && $this->log(Collect::$_is_collect[$model->is_collect].'-收集点赞',ManageLog::user,ManageLog::create))
				{
					//商品表字段点赞数 + 1
					Shops::set_shops_collect($_POST['Collect']['shops_id'],$model->is_collect);

					$return['value']=$model->is_collect;
					$return['name']=Collect::$_is_collect[$model->is_collect];
					$this->send($return);
				}
				else
					$this->send_error($this->send_error_form($this->form_error($model)));
			} else {
				$this->send_error_form($this->form_error($model));
			}
		}
		$this->send_csrf();
	}
}