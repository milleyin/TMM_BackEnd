<?php
/**
 * 
 * @author Changhai Zhan
 *	创建时间：2015-09-17 10:59:45 */
class ThrandController extends ApiController
{
	/**
	 * 默认操作数据模型
	 * @var string
	 */
	public $_class_model='Thrand';

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
					'Pro_Thrand_Dot'=>array(
						'with'=>array(
							'Dot_Shops'=>array(
// 								'condition'=>'`Dot_Shops`.`status`=1 AND `Dot_Shops`.`audit`=:audit_dot',
// 								'params'=>array(':audit_dot'=>Shops::audit_pass),
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
			'condition'=>'t.c_id=:c_id AND `Thrand_Shops`.`status`=:status AND `Thrand_Shops`.`audit`=:audit ',
			'params'=>array(':c_id'=>$shops_classliy->id,':audit'=>Shops::audit_pass,':status'=>Shops::status_online),
			'order'=>'Thrand_Pro.day_sort,Thrand_Pro.half_sort,Thrand_Pro.sort',
		));

		// 添加 浏览量
		Shops::set_shops_brow($id);

		$model->Thrand_TagsElement=TagsElement::get_select_tags(TagsElement::tags_shops_thrand,$id);

		$model->Thrand_Shops->Shops_Collect  = Collect::get_collect_praise(Collect::collect_type_praise,$id,Yii::app()->api->id);

		$return  = array();
		$datas = array(
			'name','c_id','list_info','page_info','brow','share','praise'
		);
		//运营商电话
		$return['manage_phone']=CHtml::encode($model->Thrand_Shops->Shops_Agent->manage_phone);
		//田觅觅电话
		$return['tmm_phone']=CHtml::encode(Yii::app()->params['tmm_400']);
		//多少起
		$return['price']=Thrand::shops_price_num($model->id);
		//下单量
		$return['down']=Thrand::get_down($model->id);
		//费用包含简介  cost_info
		$return['cost_info'] = isset($model->Thrand_Shops->cost_info) ? $model->Thrand_Shops->cost_info:'';
		//预定须知简介  book_info
		$return['book_info'] = isset($model->Thrand_Shops->book_info) ? $model->Thrand_Shops->book_info:'';
		//商品列表详情
		foreach($datas as $data)
			$return[$data] = CHtml::encode($model->Thrand_Shops->$data);
		//标签
		foreach($model->Thrand_TagsElement as $k=>$tags)
			$return['tags'][] = CHtml::encode($tags->TagsElement_Tags->name);

		//显示图片
		$return['list_img'] = (isset($model->Thrand_Shops->list_img) && $model->Thrand_Shops->list_img)?Yii::app()->params['admin_img_domain'].trim($this->litimg_path($model->Thrand_Shops->list_img),'.'):'';
		$return['page_img'] = (isset($model->Thrand_Shops->page_img) && $model->Thrand_Shops->page_img)?Yii::app()->params['admin_img_domain'].trim($this->litimg_path($model->Thrand_Shops->page_img),'.'):'';
		//分享图片
		$share_img=$this->litimg_path(isset($model->Thrand_Shops->list_img)?$model->Thrand_Shops->list_img:'','litimg_share',$this->litimg_path(isset($model->Thrand_Shops->list_img)?$model->Thrand_Shops->list_img:''));
		$return['share_image']=empty($share_img)?'':Yii::app()->params['admin_img_domain'].ltrim($share_img,'.');
		
		//项目名
		$return['c_name']   = CHtml::encode(ShopsClassliy::$_shop_type_name[$model->Thrand_Shops->c_id]);
		//创建时间
		$return['add_time'] = Yii::app()->format->datetime($model->Thrand_Shops->add_time);
		//审核状态
		$return['audit_type']=(int)$model->Thrand_Shops->audit;
		$return['audit_name']=CHtml::encode(Items::$_audit[$model->Thrand_Shops->audit]);

		//日程安排====处理
		$item_arr = Pro::circuit_info($model->Thrand_Pro,'Pro_Thrand_Dot');
		$data_array = isset($item_arr['data_arr']) && $item_arr['data_arr'] ? $item_arr['data_arr'] : array();
		$info_array = isset($item_arr['info_arr']) && $item_arr['info_arr'] ? $item_arr['info_arr'] : array();

		//点赞====链接
		$return['link_collect'] = Yii::app()->params['app_api_domain'].Yii::app()->createUrl('/api/shops/collect',array('id'=>$id));
		$return['thrand_id']    = $id;

		//点赞====状态
		$return['collent_status'] =  isset($model->Thrand_Shops->Shops_Collect->is_collect) && $model->Thrand_Shops->Shops_Collect->is_collect==1 ?1:0;
		$return['collent_name']   = $return['collent_status']==1 ? '已赞':'取消';

		//卖、不
		$return['is_sale']   = array('name'=>Shops::$_is_sale[$model->Thrand_Shops->is_sale],'value'=>$model->Thrand_Shops->is_sale);

		$num = 0;
		//日程安排====规划
		if($data_array && $info_array ){
			foreach ($data_array as $key=>$data_dot_sort)
			{
				// 日程安排====时间
				$return['list'][$num]['day_num'] =$key;
				$return['list'][$num]['day'] = CHtml::encode(Pro::item_swithc($key));
				//日程亮点
				$return['list'][$num]['day_content'] = isset($info_array[$key])? CHtml::encode($info_array[$key]['info']):'';

				//$num_num 替换点的ID做下标
				$dot_num = 0;

				foreach ($data_dot_sort as $k=>$data_dot) {

					foreach ($data_dot as $dot_id => $data_items) {
						//点名称
						$return['list'][$num]['dot_list'][$dot_num]['day_dot_name'] = CHtml::encode($info_array['dot_data'][$dot_id]->name);
						$return['list'][$num]['dot_list'][$dot_num]['day_dot_list_img'] = (isset( $info_array['dot_data'][$dot_id]->list_img) &&  $info_array['dot_data'][$dot_id]->list_img)?Yii::app()->params['admin_img_domain'].trim($this->litimg_path( $info_array['dot_data'][$dot_id]->list_img),'.'):'';
						//点ID
						$return['list'][$num]['dot_list'][$dot_num]['day_dot_id']   = (int)$dot_id;
						//点链接
						$return['list'][$num]['dot_list'][$dot_num]['day_dot_link'] = Yii::app()->params['app_api_domain'].Yii::app()->createUrl('/api/dot/view',array('id'=>$dot_id));
						//$return['list'][$key-1]['day_dot_id'] = CHtml::encode();

						foreach ($data_items as $sort => $items)
						{
							if ($items->Pro_Items->audit != Items::audit_pass || $items->Pro_Items->status != Items::status_online )
								$this->send_error(DATA_NULL);
							//项目id
							$return['list'][$num]['dot_list'][$dot_num]['day_item'][$sort]['value'] = $items->Pro_Items->id;
							//排序
							$return['list'][$num]['dot_list'][$dot_num]['day_item'][$sort]['sort'] = $sort+1;
							//项目名称
							$return['list'][$num]['dot_list'][$dot_num]['day_item'][$sort]['item_name'] = CHtml::encode($items->Pro_Items->name);
							//项目链接
							$return['list'][$num]['dot_list'][$dot_num]['day_item'][$sort]['item_link'] = Yii::app()->params['app_api_domain'].Yii::app()->createUrl('/api/'.$items->Pro_Items->Items_ItemsClassliy->admin.'/view',array('id'=>$items->Pro_Items->id));
							//项目图片
							$return['list'][$num]['dot_list'][$dot_num]['day_item'][$sort]['item_img']   	= isset($items->Pro_Items->Items_ItemsImg[0]->img)?Yii::app()->params['admin_img_domain'].trim($this->litimg_path($items->Pro_Items->Items_ItemsImg[0]->img),'.'):'';
							$return['list'][$num]['dot_list'][$dot_num]['day_item'][$sort]['item_img_arr']= Items::items_img($items->Pro_Items->Items_ItemsImg);
							//项目类型
							$return['list'][$num]['dot_list'][$dot_num]['day_item'][$sort]['item_type']['value']  = (int)$items->Pro_Items->Items_ItemsClassliy->id;
							$return['list'][$num]['dot_list'][$dot_num]['day_item'][$sort]['item_type']['name']   = CHtml::encode($items->Pro_Items->Items_ItemsClassliy->name);
							//商家名称
							$return['list'][$num]['dot_list'][$dot_num]['day_item'][$sort]['store_name'] = CHtml::encode($items->Pro_Items->Items_StoreContent->name);
							//价格集合
							$return['list'][$num]['dot_list'][$dot_num]['day_item'][$sort]['shop_fare'] = Fare::show_fare_api($items->Pro_ProFare,$items->Pro_Items->Items_ItemsClassliy->append=="Hotel"?true:false,true);
							//详细地址
							$return['list'][$num]['dot_list'][$dot_num]['day_item'][$sort]['address']   = CHtml::encode($items->Pro_Items->Items_area_id_p_Area_id->name.
																												$items->Pro_Items->Items_area_id_m_Area_id->name.
																												$items->Pro_Items->Items_area_id_c_Area_id->name.
																												$items->Pro_Items->address);
							//是否免费
							$return['list'][$num]['dot_list'][$dot_num]['day_item'][$sort]['free_status']= array(
									'name'=>Items::$_free_status[$items->Pro_Items->free_status],
									'value'=>CHtml::encode($items->Pro_Items->free_status)
							);
						}
					}
					$dot_num ++;

				}
				$num ++;
			}
		}
		//exit;
		$return['list_num']=count($return['list']);
		$this->send($return);
	}

	public function actionIndex($id){

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

		$return = array();
		$item_name_arr = array();
		$face_room_name = '房间数';
		$face_room_num  = 1;
		$face_room_arr = array();
		$num = 0;
		//日程安排====处理
		$item_arr = Pro::circuit_info($model->Thrand_Pro,'Pro_Thrand_Dot');
		$data_array = isset($item_arr['data_arr']) && $item_arr['data_arr'] ? $item_arr['data_arr'] : array();
		$info_array = isset($item_arr['info_arr']) && $item_arr['info_arr'] ? $item_arr['info_arr'] : array();
		//日程安排====规划
		if($data_array && $info_array ){
			foreach ($data_array as $key=>$data_dot_sort)
			{
				foreach ($data_dot_sort as $k=>$data_dot) {
					foreach ($data_dot as $dot_id => $data_items) {

						foreach ($data_items as $sort => $items)
						{
							//项目名称
							$return['list'][$num]['item_name'] = CHtml::encode($items->Pro_Items->name);
//							//项目链接
//							$return['list'][$sort]['item_link'] = Yii::app()->params['app_api_domain'].Yii::app()->createUrl('/api/'.$items->Pro_ItemsClassliy->admin.'/view',array('id'=>$items->Pro_Items->id));
//							//项目类型
//							$return['list'][$sort]['item_type']['value']  = (int)$items->Pro_ItemsClassliy->id;
//							$return['list'][$sort]['item_type']['name']   = CHtml::encode($items->Pro_Items->Items_ItemsClassliy->name);
							//价格集合
							$Hotel_val =  $items->Pro_Items->Items_ItemsClassliy->append=="Hotel"?true:false;
							$attributes = $this->fare($Hotel_val);
							$html = array();
							foreach ($items->Pro_ProFare as $k=>$models)
							{
								foreach ($attributes as $attribute=>$unit) {
									//获得 fare 列表
									$html[$k][$attribute] = $models->ProFare_Fare->$attribute;
									if(!$Hotel_val){
										$item_name_arr[] = $models->ProFare_Fare->name;
									}else{
										if(!$face_room_arr) {
											$face_room_arr['name'] = $face_room_name;
											$face_room_arr['num']  = $models->ProFare_Fare->number;
										}
										continue;
									}
								}
							}
							$return['list'][$num]['item_arr'] = $html;
							$num++;
							//$return['list'][$key-1]['day_item'][$sort]['shop_fare'] = Fare::show_fare_api($items->Pro_ProFare,$items->Pro_Items->Items_ItemsClassliy->append=="Hotel"?true:false,true);

						}
					}
				}
			}
		}
		$item_name_arr = array_values(array_unique($item_name_arr));
		foreach($item_name_arr as $k=>$v){
			$main_type[$k]['name'] = $v;
			$main_type[$k]['value'] = 1;
		}
		$return['main']['combo'] = $main_type;
		$return['main']['room'] = $face_room_arr;
		$this->send($return);
	}


	public function actionIndex_bak($id)
	{

		$shops_classliy = ShopsClassliy::getClass();
		$model = $this->loadModel($id, array(
			'with' => array(
				'Thrand_ShopsClassliy',
				'Thrand_Shops' => array('with' => array('Shops_Agent')),
				'Thrand_Pro' => array('with' => array(
					'Pro_Thrand_Dot' => array(
						'with' => array(
							'Dot_Shops' => array(
								'condition' => '`Dot_Shops`.`status`=1 AND `Dot_Shops`.`audit`=:audit_dot',
								'params' => array(':audit_dot' => Shops::audit_pass),
							),
						),
					),
					'Pro_Items'=>array(
						'with'=>array(
							'Items_ItemsClassliy',
						),
						'condition'=>'`Pro_Items`.`status`=1 AND `Pro_Items`.`audit`=:audit_pro',
						'params'=>array(':audit_pro'=>Items::audit_pass),
					),
					'Pro_ProFare' => array(
						'with' => array('ProFare_Fare'),
					),
				)),
			),
			'condition' => 't.c_id=:c_id AND `Dot_Shops`.`status`=1 AND `Dot_Shops`.`audit`=:audit ',
			'params' => array(':c_id' => $shops_classliy->id, ':audit' => Shops::audit_pass),
			'order' => 'Thrand_Pro.day_sort,Thrand_Pro.half_sort,Thrand_Pro.sort',
		));

		$return = array();
		$item_name_arr  = array();
		$face_room_name = '房间数';
		$face_room_num  = 1;
		$face_room_arr = array();
		$num = 0;
		foreach($model->Thrand_Pro as $key=>$data_item){

			//项目名称
			$return['list'][$num]['item_name'] = CHtml::encode($data_item->Pro_Items->name);
					$Hotel_val =  $data_item->Pro_Items->Items_ItemsClassliy->append=="Hotel"?true:false;
					$attributes = $this->fare($Hotel_val);
					$html = array();
					foreach ($data_item->Pro_ProFare as $k=>$models)
					{
						foreach ($attributes as $attribute=>$unit) {
							//获得 fare 列表
							$html[$k][$attribute] = $models->ProFare_Fare->$attribute;
							if(!$Hotel_val){
								$item_name_arr[] = $models->ProFare_Fare->name;
							}else{
								if(!$face_room_arr) {
									$face_room_arr['name'] = $face_room_name;
									$face_room_arr['num']  = $models->ProFare_Fare->number;
								}
								continue;
							}
						}
					}
					$return['list'][$num]['item_arr'] = $html;
				$num ++;
		}

		$main_type = array();
		//数重去重，并重新索引
		$item_name_arr = array_values(array_unique($item_name_arr));
		foreach($item_name_arr as $k=>$v){
			$main_type[$k]['name'] = $v;
			$main_type[$k]['value'] = 1;
		}
		$return['main']['combo'] = $main_type;
		$return['main']['room'] = $face_room_arr;
		$this->send($return);

	}


	public  function fare($all){
		if($all==false)
			$attributes=array('name'=>'','info'=>'','price'=>'元','id'=>'');
		else
			$attributes=array('name'=>'','info'=>'平方','number'=>'人间','price'=>'元','id'=>'');
		return $attributes;
	}


    public function actionNew($id){

		exit;
		$this->_class_model='Shops';

		$model = $this->loadModel($id,array(
			'with'=>array(
				'Shops_Thrand',
				'Shops_Pro'=>array('with'=>array(
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
			'condition'=>' t.c_id=:c_id AND t.status=1 AND t.audit=:audit ',
			'params'=>array(':c_id'=>2,':audit'=>Shops::audit_pass),
				'order'=>'Shops_Pro.day_sort,Shops_Pro.half_sort,Shops_Pro.sort',
		));
		//$this->p_r($model);
		//日程安排====处理
		$item_arr = Pro::circuit_info($model->Shops_Pro,'Pro_Thrand_Dot');
		$data_array = isset($item_arr['data_arr']) && $item_arr['data_arr'] ? $item_arr['data_arr'] : array();
		$info_array = isset($item_arr['info_arr']) && $item_arr['info_arr'] ? $item_arr['info_arr'] : array();

		//echo count($data_array);
		//$this->p_r($data_array);exit;


			$return = array();
			$item_name_arr = array();
			$face_room_name = '房间数';
			$face_room_num  = 1;
			$face_room_arr = array();
			$num = 0;
			//日程安排====处理
//			$item_arr = Pro::circuit_info($model->Thrand_Pro,'Pro_Thrand_Dot');
//			$data_array = isset($item_arr['data_arr']) && $item_arr['data_arr'] ? $item_arr['data_arr'] : array();
//			$info_array = isset($item_arr['info_arr']) && $item_arr['info_arr'] ? $item_arr['info_arr'] : array();
			//日程安排====规划
			if($data_array && $info_array ){
				foreach ($data_array as $key=>$data_dot_sort)
				{
					// 日程安排====时间
					$return['list'][$key-1]['day'] = $key;
					foreach ($data_dot_sort as $k=>$data_dot) {
						foreach ($data_dot as $dot_id => $data_items) {

							foreach ($data_items as $sort => $items)
							{
								//项目名称
								$return['list'][$num]['item_name'] = CHtml::encode($items->Pro_Items->name);
								//价格集合
								$Hotel_val =  $items->Pro_Items->Items_ItemsClassliy->append=="Hotel"?true:false;
								$attributes = $this->fare($Hotel_val);
								$html = array();
								foreach ($items->Pro_ProFare as $k=>$models)
								{
									foreach ($attributes as $attribute=>$unit) {
										//获得 fare 列表
										$html[$k][$attribute] = $models->ProFare_Fare->$attribute;
										if(!$Hotel_val){
											$item_name_arr[] = $models->ProFare_Fare->name;
										}else{
											if(!$face_room_arr) {
												$face_room_arr['name'] = $face_room_name;
												$face_room_arr['num']  = $models->ProFare_Fare->number;
											}
											continue;
										}
									}
								}
								$return['list'][$num]['item_arr'] = $html;
								$num++;
								//$return['list'][$key-1]['day_item'][$sort]['shop_fare'] = Fare::show_fare_api($items->Pro_ProFare,$items->Pro_Items->Items_ItemsClassliy->append=="Hotel"?true:false,true);

							}
						}
					}
				}
			}

		if($data_array && $info_array ){
			foreach ($data_array as $key=>$data_dot_sort)
			{
				// 日程安排====时间
				$return['list'][$key-1]['day'] = Pro::item_swithc($key);
				//日程亮点
				$return['list'][$key-1]['day_content'] = CHtml::encode($info_array[$key]['info']);
				foreach ($data_dot_sort as $k=>$data_dot) {
					foreach ($data_dot as $dot_id => $data_items) {
						//$this->p_r($data_items);exit;
						//点名称
						$return['list'][$key-1]['day_dot_name'] = CHtml::encode($info_array['dot_data'][$dot_id]->name);
						$return['list'][$key-1]['day_dot_list_img'] = (isset( $info_array['dot_data'][$dot_id]->list_img) &&  $info_array['dot_data'][$dot_id]->list_img)?Yii::app()->params['admin_img_domain'].trim($this->litimg_path( $info_array['dot_data'][$dot_id]->list_img),'.'):'';
						//点ID
						$return['list'][$key-1]['day_dot_id']   = (int)$dot_id;
						//点链接
						$return['list'][$key-1]['day_dot_link'] = Yii::app()->params['app_api_domain'].Yii::app()->createUrl('/api/dot/view',array('id'=>$dot_id));
						//$return['list'][$key-1]['day_dot_id'] = CHtml::encode();
						foreach ($data_items as $sort => $items)
						{
							//排序
							$return['list'][$key-1]['day_item'][$sort]['sort'] = $sort+1;
							//项目名称
							$return['list'][$key-1]['day_item'][$sort]['item_name'] = CHtml::encode($items->Pro_Items->name);
							//项目链接
							$return['list'][$key-1]['day_item'][$sort]['item_link'] = Yii::app()->params['app_api_domain'].Yii::app()->createUrl('/api/'.$items->Pro_ItemsClassliy->admin.'/view',array('id'=>$items->Pro_Items->id));
							//项目图片
							$return['list'][$key-1]['day_item'][$sort]['item_img']   = isset($items->Pro_Items->Items_ItemsImg[0]->img)?Yii::app()->params['admin_img_domain'].trim($this->litimg_path($items->Pro_Items->Items_ItemsImg[0]->img),'.'):'';
							//项目类型
							$return['list'][$key-1]['day_item'][$sort]['item_type']['value']  = (int)$items->Pro_ItemsClassliy->id;
							$return['list'][$key-1]['day_item'][$sort]['item_type']['name']   = CHtml::encode($items->Pro_Items->Items_ItemsClassliy->name);
							//商家名称
							$return['list'][$key-1]['day_item'][$sort]['store_name'] = CHtml::encode($items->Pro_Items->Items_StoreContent->name);
							//价格集合
							$return['list'][$key-1]['day_item'][$sort]['shop_fare'] = Fare::show_fare_api($items->Pro_ProFare,$items->Pro_Items->Items_ItemsClassliy->append=="Hotel"?true:false,true);
							//详细地址
							$return['list'][$key-1]['day_item'][$sort]['address']   = CHtml::encode($items->Pro_Items->Items_area_id_p_Area_id->name.
								$items->Pro_Items->Items_area_id_m_Area_id->name.
								$items->Pro_Items->Items_area_id_c_Area_id->name.
								$items->Pro_Items->address);
						}
					}
				}
			}
		}


		$item_name_arr = array_values(array_unique($item_name_arr));
			foreach($item_name_arr as $k=>$v){
				$main_type[$k]['name'] = $v;
				$main_type[$k]['value'] = 1;
			}
			$return['main']['combo'] = $main_type;
			$return['main']['room'] = $face_room_arr;
			$this->send($return);
		}




}
