<?php
/**
 * 
 * @author Changhai Zhan
 *	创建时间：2015-09-17 11:00:51 */
class GroupController extends ApiController
{
	/**
	 * 默认操作数据模型
	 * @var string
	 */
	public $_class_model='Group';

//     /**
//      * 新的统计数量
//      * @var unknown
//      */
//     public $_new_number=array();

//     public function actionView($id){
//         $shops_classliy=ShopsClassliy::getClass();

//         $model = $this->loadModel($id,' t.c_id='.$shops_classliy->id);

//         //结伴游订单 复制前  （结伴中）
//         if(isset($model->group) && $model->group==0)
//             $this->order_organizer_view($id);  //查询 复制后的表
//         else
//             $this->dot_view($id);                       // 正常查询

//     }
// 	/**
// 	 * 查看详情=====结伴游（点）
// 	 * @param $id
// 	 * @throws CHttpException
// 	 */
// 	public function dot_view($id)
// 	{
//         $shops_classliy=ShopsClassliy::getClass();
// 		$model=$this->loadModel($id,array(
// 			'with'=>array(
// 				'Group_ShopsClassliy',
// 				'Group_Shops'=>array('with'=>array('Shops_Agent')),
// 				'Group_User',
// 				'Group_Pro'=>array('with'=>array(
// 					'Pro_Group_Dot'=>array('with'=>'Dot_Shops'),
// 					'Pro_Group_Thrand'=>array('with'=>'Thrand_Shops'),
// 					'Pro_Items'=>array('with'=>array(
// 						'Items_area_id_p_Area_id',
// 						'Items_area_id_m_Area_id',
// 						'Items_area_id_c_Area_id',
// 						'Items_ItemsImg',
// 						'Items_StoreContent'=>array('with'=>array('Content_Store')),
// 						'Items_Store_Manager',
// 						'Items_ItemsClassliy',
// 					)),
// 					'Pro_ItemsClassliy',
// 					'Pro_ProFare'=>array(
// 						'with'=>array('ProFare_Fare')
// 					),
// 				)),
// 			),
// 			'condition'=>'t.c_id=:c_id AND `Group_Shops`.`status`=1 AND `Dot_Shops`.`audit`=:audit ',
// 			'params'=>array(':c_id'=>$shops_classliy->id,':audit'=>Shops::audit_pass),
// 			'order'=>'`Group_Pro`.`day_sort`,`Group_Pro`.`half_sort`,`Group_Pro`.`sort`',
// 		));

// 		$model->Group_TagsElement=TagsElement::get_select_tags(TagsElement::tags_shops_group,$id);

//         $model->Group_Shops->Shops_Collect  = Collect::get_collect_praise(Collect::collect_type_praise,$id,Yii::app()->api->id);

//         $return  = array();
//         $datas = array(
//             'name','c_id','list_info','page_info','brow','share','praise'
//         );
//         //商品列表详情
//         foreach($datas as $data)
//             $return[$data] = CHtml::encode($model->Group_Shops->$data);
//         //标签
//         foreach($model->Group_TagsElement as $k=>$tags)
//             $return['tags'][] = CHtml::encode($tags->TagsElement_Tags->name);

//         //显示图片
//         $return['list_img'] = (isset($model->Group_Shops->list_img) && $model->Group_Shops->list_img)?Yii::app()->params['admin_img_domain'].trim($this->litimg_path($model->Group_Shops->list_img),'.'):'';
//         $return['page_img'] = (isset($model->Group_Shops->page_img) && $model->Group_Shops->page_img)?Yii::app()->params['admin_img_domain'].trim($this->litimg_path($model->Group_Shops->page_img),'.'):'';
// 		//项目名
// 		$return['c_name']   = CHtml::encode(ShopsClassliy::$_shop_type_name[$model->Group_Shops->c_id]);
// 		//创建时间====开始时间
// 		$return['add_time'] = CHtml::encode(Yii::app()->format->datetime($model->Group_Shops->add_time));
//         $return['end_time'] = CHtml::encode(Yii::app()->format->datetime($model->Group_Shops->up_time));
// 		//审核状态
// 		$return['audit_type'] = (int)$model->Group_Shops->audit;
// 		$return['audit_name'] = CHtml::encode(Items::$_audit[$model->Group_Shops->audit]);

//         //点赞====链接
//         $return['link_collect'] = Yii::app()->params['app_api_domain'].Yii::app()->createUrl('/api/shops/collect',array('id'=>$id));
//         $return['group_id']    = $id;
//       //  $return['link_group_update'] =  Yii::app()->params['app_api_domain'].Yii::app()->createUrl('/api/group/update',array('id'=>$id));

//         //点赞====状态
//         $return['collent_status'] =  isset($model->Group_Shops->Shops_Collect->is_collect) && $model->Group_Shops->Shops_Collect->is_collect==1 ?1:0;
//         $return['collent_name']   = $return['collent_status']==1 ? '已赞':'取消';

// 		//日程安排====处理
// 		$item_arr = Pro::circuit_info($model->Group_Pro,'Pro_Group_Dot');
// 		$data_array = isset($item_arr['data_arr']) && $item_arr['data_arr'] ? $item_arr['data_arr'] : array();
// 		$info_array = isset($item_arr['info_arr']) && $item_arr['info_arr'] ? $item_arr['info_arr'] : array();

//         $num = 0;
// 		//日程安排====规划
// 		if($data_array && $info_array ){
// 			foreach ($data_array as $key=>$data_dot_sort)
// 			{
// 				// 日程安排====时间
// 				$return['list'][$num]['day'] = CHtml::encode(Pro::item_swithc($key));
// 				//日程亮点
// 				$return['list'][$num]['day_content'] = CHtml::encode($info_array[$key]['info']);
// 				foreach ($data_dot_sort as $k=>$data_dot) {
// 					foreach ($data_dot as $dot_id => $data_items) {
// 						//点名称
// 						$return['list'][$num]['day_dot_name'] = CHtml::encode($info_array['dot_data'][$dot_id]->name);
// 						//点ID
// 						$return['list'][$num]['day_dot_id']   = $dot_id;
//                         //图片
//                         $return['list'][$num]['day_dot_list_img'] = (isset( $info_array['dot_data'][$dot_id]->list_img) &&  $info_array['dot_data'][$dot_id]->list_img)?Yii::app()->params['admin_img_domain'].trim($this->litimg_path( $info_array['dot_data'][$dot_id]->list_img),'.'):'';
//                         //点链接
//                         $return['list'][$num]['day_dot_link'] = Yii::app()->params['app_api_domain'].Yii::app()->createUrl('/api/dot/view',array('id'=>$dot_id));
// 						foreach ($data_items as $sort => $items)
// 						{
// 							//排序
// 							$return['list'][$num]['day_item'][$sort]['sort'] = $sort+1;
// 							//项目名称
// 							$return['list'][$num]['day_item'][$sort]['item_name'] = CHtml::encode($items->Pro_Items->name);
// 							//项目ID
// 							$return['list'][$num]['day_item'][$sort]['item_id']   = $items->Pro_Items->id;
//                             //项目链接
//                             $return['list'][$num]['day_item'][$sort]['item_link'] = Yii::app()->params['app_api_domain'].Yii::app()->createUrl('/api/'.$items->Pro_ItemsClassliy->admin.'/view',array('id'=>$items->Pro_Items->id));
//                             //项目类型
//                             $return['list'][$num]['day_item'][$sort]['item_type']['value']  = (int)$items->Pro_ItemsClassliy->id;
//                             $return['list'][$num]['day_item'][$sort]['item_type']['name']   = CHtml::encode($items->Pro_Items->Items_ItemsClassliy->name);
// 							//商家名称
// 							$return['list'][$num]['day_item'][$sort]['store_name'] = CHtml::encode($items->Pro_Items->Items_StoreContent->name);
// 							//价格集合
// 							$return['list'][$num]['day_item'][$sort]['shop_fare'] = Fare::show_fare_api($items->Pro_ProFare,$items->Pro_Items->Items_ItemsClassliy->append=="Hotel"?true:false,true);
// 							//详细地址
// 							$return['list'][$num]['day_item'][$sort]['address']   = CHtml::encode($items->Pro_Items->Items_area_id_p_Area_id->name).
// 								$items->Pro_Items->Items_area_id_m_Area_id->name.
// 								$items->Pro_Items->Items_area_id_c_Area_id->name.
// 								$items->Pro_Items->address;
// 						}
// 					}
// 				}
//                 $num ++;
// 			}
// 		}

// 		$this->send($return);

// 	}

//     /**
//      * 查看详情=====结伴游（订单组织者）
//      * @param $id
//      */
//     public function order_organizer_view($id){

//         $model = OrderOrganizer::model()->find(array(
//             'with'=>array(
//                 'OrderOrganizer_OrderItems'=>array(
//                     'with'=>array(
//                         'OrderItems_OrderItemsFare',
//                         'OrderItems_StoreUser'=>array('with'=>array('Store_Content')),
//                         'OrderItems_ItemsClassliy',
//                     )
//                 ),

//             ),
//             'condition'=>' t.group_id=:group_id AND t.shops_c_id=:shops_c_id ',
//             'params'=>array(':group_id'=>$id,':shops_c_id'=>3),
//             'order'=>'OrderOrganizer_OrderItems.shops_day_sort,OrderOrganizer_OrderItems.shops_half_sort,OrderOrganizer_OrderItems.shops_sort',
//         ));

//         $model_collect  = Collect::get_collect_praise(Collect::collect_type_praise,$id,Yii::app()->api->id);

//         $return  = array();
//         //商品列表详情
//         $return['name']  = CHtml::encode($model->shops_name);
//         $return['c_id']  = CHtml::encode($model->shops_c_id);
//         $return['list_info']  = CHtml::encode($model->shops_list_info);
//         $return['page_info']  = CHtml::encode($model->shops_page_info);

//         //显示图片
//         $return['list_img'] = (isset($model->shops_list_img) && $model->shops_list_img)?Yii::app()->params['admin_img_domain'].trim($this->litimg_path($model->shops_list_img),'.'):'';
//         $return['page_img'] = (isset($model->shops_page_img) && $model->shops_page_img)?Yii::app()->params['admin_img_domain'].trim($this->litimg_path($model->shops_page_img),'.'):'';
//         //项目名
//         $return['c_name']   = CHtml::encode(ShopsClassliy::$_shop_type_name[$model->shops_c_id]);

//         //创建时间====开始时间
//         $return['add_time'] = CHtml::encode(Yii::app()->format->datetime($model->shops_add_time));
//         $return['end_time'] = CHtml::encode(Yii::app()->format->datetime($model->shops_up_time));
//         //审核状态
//         $return['group_status'] = (int)$model->group_group;
//         $return['group_name']   = CHtml::encode(OrderOrganizer::$_group_group[$model->group_group]);

//         //点赞====链接
//         $return['link_collect'] = Yii::app()->params['app_api_domain'].Yii::app()->createUrl('/api/shops/collect',array('id'=>$id));
//         $return['group_id']    = $id;

//         //点赞====状态
//         $return['collent_status'] =  isset($model->is_collect) && $model->is_collect==1 ?1:0;
//         $return['collent_name']   = $return['collent_status']==1 ? '已赞':'取消';


//         $data_array=array();
//         $info_array=array();

//         foreach ($model->OrderOrganizer_OrderItems as $value)
//         {
//             $data_array[$value->shops_day_sort][]=$value;

//             if($value->shops_half_sort==0 && $value->shops_sort==0){
//                 $info_array[$value->shops_day_sort]['shops_info'] = $value->shops_info;
//                 $info_array[$value->shops_day_sort]['shops_name'] = $value->shops_name;
//                 $info_array[$value->shops_day_sort]['shops_dot_id'] = $value->shops_dot_id;
//                 $info_array[$value->shops_day_sort]['items_img'] = $value->items_img;
//             }
//         }
//         $num = 0;
//         //日程安排====规划
//         if($data_array && $info_array ) {
//             foreach($data_array as $k=>$data_arr) {
//                 // 日程安排====时间
//                 $return['list'][$num]['day'] = CHtml::encode(Pro::item_swithc($k));
//                 //日程亮点
//                 $return['list'][$num]['day_content'] = CHtml::encode($info_array[$k]['shops_info']);
//                 //点名称
//                 $return['list'][$num]['day_dot_name'] = CHtml::encode($info_array[$k]['shops_name']);
//                 //点ID
//                 $return['list'][$num]['day_dot_id']   = (int)$info_array[$k]['shops_dot_id'];
//                 //图片
//                 $return['list'][$num]['day_dot_list_img'] = (isset( $info_array[$k]['items_img']) &&  $info_array[$k]['items_img'])?Yii::app()->params['admin_img_domain'].trim($this->litimg_path( $info_array[$k]['items_img']),'.'):'';
//                 //点链接
//                 $return['list'][$num]['day_dot_link'] = Yii::app()->params['app_api_domain'].Yii::app()->createUrl('/api/dot/view',array('id'=>$info_array[$k]['shops_dot_id']));

//                 foreach($data_arr as $val){

//                             //排序
//                             $return['list'][$num]['day_item'][$val->shops_sort]['sort'] = CHtml::encode($val->shops_sort + 1);
//                             //项目名称
//                             $return['list'][$num]['day_item'][$val->shops_sort]['item_name'] = CHtml::encode($val->items_name);
//                             //项目ID
//                             $return['list'][$num]['day_item'][$val->shops_sort]['item_id']   = (int)$val->items_id;
//                             //项目链接
//                             $return['list'][$num]['day_item'][$val->shops_sort]['item_link'] = Yii::app()->params['app_api_domain'].Yii::app()->createUrl('/api/'.$val->OrderItems_ItemsClassliy->admin.'/view',array('id'=>$val->items_id));
//                             //项目类型
//                             $return['list'][$num]['day_item'][$val->shops_sort]['item_type']['value']  = (int)$val->OrderItems_ItemsClassliy->id;
//                             $return['list'][$num]['day_item'][$val->shops_sort]['item_type']['name']   = CHtml::encode($val->OrderItems_ItemsClassliy->name);
//                             //商家名称
//                             $return['list'][$num]['day_item'][$val->shops_sort]['store_name'] = CHtml::encode($val->OrderItems_StoreUser->Store_Content->name);
//                             //价格集合
//                             $return['list'][$num]['day_item'][$val->shops_sort]['shop_fare'] = Fare::show_order_organizer_fare_api($val,$val->OrderItems_ItemsClassliy->append=="Hotel"?true:false,true);
//                             //详细地址
//                             $return['list'][$num]['day_item'][$val->shops_sort]['address']   = CHtml::encode($val->items_address);
//                     }
//                 $num ++;
//                 }

//             }

//         $this->send($return);
//     }
//     /**
//      * 查看详情=====结伴游（线）
//      * @param $id
//      */
//     public function thrand_view($id)
//     {

//         $model = $this->thrand_model($id);

//         $model->Thrand_TagsElement=TagsElement::get_select_tags(TagsElement::tags_shops_thrand,$id);

//         $model->Thrand_Shops->Shops_Collect  = Collect::get_collect_praise(Collect::collect_type_praise,$id,Yii::app()->api->id);

//         $return  = array();
//         $datas = array(
//             'name','c_id','list_info','page_info','brow','share','praise'
//         );
//         //商品列表详情
//         foreach($datas as $data)
//             $return[$data] = CHtml::encode($model->Thrand_Shops->$data);
//         //标签
//         foreach($model->Thrand_TagsElement as $k=>$tags)
//             $return['tags'][] = CHtml::encode($tags->TagsElement_Tags->name);

//         //显示图片
//         $return['list_img'] = (isset($model->Thrand_Shops->list_img) && $model->Thrand_Shops->list_img)?Yii::app()->params['admin_img_domain'].trim($this->litimg_path($model->Thrand_Shops->list_img),'.'):'';
//         $return['page_img'] = (isset($model->Thrand_Shops->page_img) && $model->Thrand_Shops->page_img)?Yii::app()->params['admin_img_domain'].trim($this->litimg_path($model->Thrand_Shops->page_img),'.'):'';
//         //项目名
//         $return['c_name']   = CHtml::encode(ShopsClassliy::$_shop_type_name[$model->Thrand_Shops->c_id]);
//         //创建时间
//         $return['add_time'] = Yii::app()->format->datetime($model->Thrand_Shops->add_time);
//         $return['end_time'] = Yii::app()->format->datetime($model->Thrand_Shops->up_time);
//         //审核状态
//         $return['audit_type']=(int)$model->Thrand_Shops->audit;
//         $return['audit_name']=CHtml::encode(Items::$_audit[$model->Thrand_Shops->audit]);

//         //日程安排====处理
//         $item_arr = Pro::circuit_info($model->Thrand_Pro,'Pro_Thrand_Dot');
//         $data_array = isset($item_arr['data_arr']) && $item_arr['data_arr'] ? $item_arr['data_arr'] : array();
//         $info_array = isset($item_arr['info_arr']) && $item_arr['info_arr'] ? $item_arr['info_arr'] : array();

//         //点赞====链接
//         $return['link_collect'] = Yii::app()->params['app_api_domain'].Yii::app()->createUrl('/api/shops/collect',array('id'=>$id));
//         $return['thrand_id']    = $id;
//         $return['link_group_update'] =  Yii::app()->params['app_api_domain'].Yii::app()->createUrl('/api/group/update',array('id'=>$id));

//         //点赞====状态
//         $return['collent_status'] =  isset($model->Thrand_Shops->Shops_Collect->is_collect) && $model->Thrand_Shops->Shops_Collect->is_collect==1 ?1:0;
//         $return['collent_name']   = $return['collent_status']==1 ? '已赞':'取消';

//         //日程安排====规划
//         if($data_array && $info_array ){
//             foreach ($data_array as $key=>$data_dot_sort)
//             {
//                 // 日程安排====时间
//                 $return['list'][$key-1]['day'] = CHtml::encode(Pro::item_swithc($key));
//                 //日程亮点
//                 $return['list'][$key-1]['day_content'] = CHtml::encode($info_array[$key]['info']);
//                 foreach ($data_dot_sort as $k=>$data_dot) {
//                     foreach ($data_dot as $dot_id => $data_items) {
//                         //$this->p_r($data_items);exit;
//                         //点名称
//                         $return['list'][$key-1]['day_dot_name'] = CHtml::encode($info_array['dot_data'][$dot_id]->name);
//                         $return['list'][$key-1]['day_dot_list_img'] = (isset( $info_array['dot_data'][$dot_id]->list_img) &&  $info_array['dot_data'][$dot_id]->list_img)?Yii::app()->params['admin_img_domain'].trim($this->litimg_path( $info_array['dot_data'][$dot_id]->list_img),'.'):'';

//                         //点ID
//                         $return['list'][$key-1]['day_dot_id']   = (int)$dot_id;
//                         //点链接
//                         $return['list'][$key-1]['day_dot_link'] = Yii::app()->params['app_api_domain'].Yii::app()->createUrl('/api/dot/view',array('id'=>$dot_id));

//                         foreach ($data_items as $sort => $items)
//                         {
//                             //排序
//                             $return['list'][$key-1]['day_item'][$sort]['sort'] = $sort+1;
//                             //项目名称
//                             $return['list'][$key-1]['day_item'][$sort]['item_name'] = CHtml::encode($items->Pro_Items->name);
//                             //项目链接
//                             $return['list'][$key-1]['day_item'][$sort]['item_link'] = Yii::app()->params['app_api_domain'].Yii::app()->createUrl('/api/'.$items->Pro_ItemsClassliy->admin.'/view',array('id'=>$items->Pro_Items->id));
//                             //项目类型
//                             $return['list'][$key-1]['day_item'][$sort]['item_type']['value']  = (int)$items->Pro_ItemsClassliy->id;
//                             $return['list'][$key-1]['day_item'][$sort]['item_type']['name']   = CHtml::encode($items->Pro_Items->Items_ItemsClassliy->name);
//                             //商家名称
//                             $return['list'][$key-1]['day_item'][$sort]['store_name'] = CHtml::encode($items->Pro_Items->Items_StoreContent->name);
//                             //价格集合
//                             $return['list'][$key-1]['day_item'][$sort]['shop_fare'] = Fare::show_fare_api($items->Pro_ProFare,$items->Pro_Items->Items_ItemsClassliy->append=="Hotel"?true:false,true);
//                             //详细地址
//                             $return['list'][$key-1]['day_item'][$sort]['address']   = CHtml::encode($items->Pro_Items->Items_area_id_p_Area_id->name.
//                                 $items->Pro_Items->Items_area_id_m_Area_id->name.
//                                 $items->Pro_Items->Items_area_id_c_Area_id->name.
//                                 $items->Pro_Items->address);
//                         }
//                     }
//                 }
//             }
//         }
//         $return['list_num']=count($return['list']);
//         $this->send($return);
//     }

//     /**
//      * 线的model
//      * @param $id
//      * @return mixed
//      */
//     public function thrand_model($id){
//         $this->_class_model='Thrand';
//         $shops_classliy=ShopsClassliy::getClass();

//         $model=$this->loadModel($id,array(
//             'with'=>array(
//                 'Thrand_ShopsClassliy',
//                 'Thrand_Shops'=>array('with'=>array('Shops_Agent')),
//                 'Thrand_Pro'=>array('with'=>array(
//                     'Pro_Thrand_Dot'=>array(
//                         'with'=>array(
//                             'Dot_Shops'=>array(
//                                 'condition'=>'`Dot_Shops`.`status`=1 AND `Dot_Shops`.`audit`=:audit_dot',
//                                 'params'=>array(':audit_dot'=>Shops::audit_pass),
//                             ),
//                         ),
//                     ),
//                     'Pro_Items'=>array(
//                         'with'=>array(
//                             'Items_area_id_p_Area_id',
//                             'Items_area_id_m_Area_id',
//                             'Items_area_id_c_Area_id',
//                             'Items_ItemsImg',
//                             'Items_StoreContent'=>array('with'=>array('Content_Store')),
//                             'Items_Store_Manager',
//                             'Items_ItemsClassliy',
//                         ),
//                         'condition'=>'`Pro_Items`.`status`=1 AND `Pro_Items`.`audit`=:audit_pro',
//                         'params'=>array(':audit_pro'=>Items::audit_pass),
//                     ),
//                     'Pro_ItemsClassliy',
//                     'Pro_ProFare'=>array(
//                         'with'=>array('ProFare_Fare'),
//                     ),
//                 )),
//             ),
//             'condition'=>'t.c_id=:c_id AND `Dot_Shops`.`status`=1 AND `Dot_Shops`.`audit`=:audit ',
//             'params'=>array(':c_id'=>$shops_classliy->id,':audit'=>Shops::audit_pass),
//             'order'=>'Thrand_Pro.day_sort,Thrand_Pro.half_sort,Thrand_Pro.sort',
//         ));

//         return $model;
//     }



//     /**
//      *  创建结伴游
//      */
//     public  function actionCreate(){

//         //判断是否是组织者
//         $model_organizer = User::model()->find('is_organizer=1 AND  status=1 AND  id='.Yii::app()->api->id);
//         if(!$model_organizer)
//             $this->send_error(GROUP_NOT_ORGANIZER_ERROR);

//         $shops_classliy = ShopsClassliy::getClass();

// //        $group_test = array(
// //                'group_thrand'=>81,          //线路ID 点为0
// //                'price'=>200.00,               //结伴游服务费
// //                'remark'=>'app 线路',        //结伴游备注
// //                'end_time'=>'2015-10-01', //报名截止时间
// //        );
// //        $shop_test = array('name'=>'app创建线路结伴游');  //结伴游名称
// //         $_POST = array(
// //             'group_type'=>2,  //发起结伴游类型  1=点 2=线
// //             'is_insurance'=>1,
// //             'Pro'=>$this->pro_arr_test(),
// //             'ProFare'=>$this->fate_arr_test(),
// //             'Shops'=>$shop_test,
// //             'Group'=>$group_test,
// //         );

//         $model=new Group();//结伴游的主要表
//         $model->scenario='create';
//         $model->Group_Shops=new Shops;//结伴游的商品表
//         $this->_class_model='User';
//         $model->Group_User=$this->loadModel(Yii::app()->api->id,'status=1');
//         $ProFare=new ProFare;//选中项目选中的价格表
//         $time   = time();   //当前时间撮
//         if(isset($_POST['group_type']) && isset($_POST['Group']) && isset($_POST['Shops']) )
//         {
// 	        //判断是否是线
// 	       if( isset($_POST['group_type']) && $_POST['group_type'] == 2 && isset($_POST['Group']) && isset($_POST['Group']['group_thrand']) && $_POST['Group']['group_thrand'] && is_numeric($_POST['Group']['group_thrand'])  ){

// 	           //判断报名截止时间是否在指定时间段内
// 	           if($this->end_time($_POST['Group']['end_time']))
// 	               //结伴游，线路创建
// 	               $status = $this->thrand_save($model,$shops_classliy->id);
// 	             else
// 	                $this->send_error(GROUP_CURRENT_END_ERROR);

// 	       }
//             //判断 是否是点
//             if (isset($_POST['group_type']) && $_POST['group_type'] == 1) {
//                 $model->Group_Shops->scenario = 'create_thrand';
//                 $Pro = new Pro;//选中点的选中项目表
//                 $Pro->scenario = 'create_thrand';
//                 $ProFare = new ProFare;//选中项目选中的价格表
//                 $ProFare->scenario = 'create_thrand';

//                 $Pro->Pro_ProFare = array($ProFare);//关系赋值 一个项目可以选多个价格 默认一个
//                 $model->Group_Pro = array($Pro);//一条线可以选择多个点中的项目 默认一个

//                 if (isset($_POST['Shops']) && isset($_POST['Group']) && isset($_POST['Pro']) && is_array($_POST['Pro']) && isset($_POST['ProFare']) && is_array($_POST['ProFare']) && count($_POST['ProFare']) == count($_POST['Pro'])) {
//                     //判断报名截止时间是否在指定时间段内
//                     if ($this->end_time($_POST['Group']['end_time'])) {

//                         //验证数据是否有错误
//                         if ($this->validate_thrand($model, $shops_classliy->id))
//                         {
//                         	$model->scenario='create';
//                         	$model->attributes=$_POST['Group'];
//                             //提前验证
//                             $validate_pros_fares = true;
//                             $validate_pros_fares=$model->validate();
//                             foreach ($model->Group_Pro as $pro) {
//                                 if (!$pro->validate())
//                                     $validate_pros_fares = false;
//                                 foreach ($pro->Pro_ProFare as $fare) {
//                                     if (!$fare->validate())
//                                         $validate_pros_fares = false;
//                                 }
//                             }
//                             if ($validate_pros_fares) {
//                                 $status = $this->dot_save($model, $shops_classliy->id);
//                             }else
//                             	$this->send_error_form($this->form_error(array($model,$model->Group_Shops)));
//                         } else
//                             $this->send_error_form($this->form_error(array($model,$model->Group_Shops)));
//                     } else
//                         $this->send_error(GROUP_CURRENT_END_ERROR);
//                 }
//             }

//             if (isset($status) && $status == 1) {
//                 //成功
//                 $return = array(
//                     'status' => STATUS_SUCCESS,
//                 );
//                 $this->send($return);
//             } else{
//                 $this->send_error(DATA_NOT_SCUSSECS);
//             }
//         }

//         $this->send_csrf();
// 	}

//     /**
//      * 创建结伴游====点保存
//      * @param $model
//      * @param $c_id
//      * @return int|string
//      */
//     private function dot_save($model,$c_id)
//     {
//         $status = '';
//         //事物
//         $transaction=$model->dbConnection->beginTransaction();
//         try{
//             $model->Group_Shops->c_id=$c_id;
//             $model->Group_Shops->status=Shops::status_online;
//             $model->Group_Shops->audit=Shops::audit_pending;
//             if($model->Group_Shops->save(false))
//             {
//                 $model->id          = $model->Group_Shops->id;      //结伴游ID
//                 $model->c_id        = $c_id;                         //类型ID　３
//                 $model->user_id     = Yii::app()->api->id;          //用户ID（组织者）
//                 $model->start_time  = time();                       //开始时间
//                 $model->end_time    = strtotime($_POST['Group']['end_time']); //结止时间
//                 $model->group       = Group::group_none;             //团状态
//                 $model->status      = Group::status_down;       //表示结伴游没有成订单 下线状态
//                 if(! $model->save(false))
//                     throw new Exception("创建线路(结伴游) 保存线路附表错误");

//                 $dot_ids=array();
//                 foreach ($model->Group_Pro as $pro_save)
//                 {
//                     $dot_ids[]=$pro_save->dot_id;
//                     $pro_save->shops_id=$model->id;
//                     if(! $pro_save->save(false))
//                         throw new Exception("创建线路(结伴游) 保存选中项目表错误");
//                     foreach ($pro_save->Pro_ProFare as $fare_save)
//                     {
//                         $fare_save->pro_id=$pro_save->id;
//                         $fare_save->group_id=$model->id;
//                         if(! $fare_save->save(false))
//                             throw new Exception("创建线路(结伴游) 保存选中项目的选中价格表错误");
//                     }
//                 }
//                 //继承点的tags
//                 foreach ($dot_ids as $dot_id)
//                     $element_ids[]=array(TagsElement::tags_shops_dot,$dot_id);
//                 TagsElement::select_tags_ids_save(TagsElement::select_tags_all($element_ids), $model->Group_Shops->id, TagsElement::tags_shops_group,TagsElement::user);

//                 //日志
//                 $return=$this->log('创建线路(结伴游)',ManageLog::user,ManageLog::create);
//             }else
//                 throw new Exception("审核通过保存错误");
//             $transaction->commit();
//             $status = 1;

//         }catch(Exception $e){
//             //事务回滚
//             $transaction->rollBack();
//             $this->error_log($e->getMessage(),ErrorLog::api,ErrorLog::create,ErrorLog::rollback,__METHOD__);
//         }
//         return $status;
//     }

//     /**
//      * tmm_pro
//      */
//     public function get_pro($val,$c_id,$shop_id){
//         $model_pro = new Pro();

//         $model_pro->shops_id     =$shop_id;
//         $model_pro->agent_id     =$val->agent_id;
//         $model_pro->store_id     =$val->store_id;
//         $model_pro->shops_c_id   =$val->shops_c_id;
//         $model_pro->c_id          =$c_id;
//         $model_pro->sort          =$val->sort;
//         $model_pro->day_sort     =$val->day_sort;
//         $model_pro->half_sort    =$val->half_sort;
//         $model_pro->items_id     =$val->items_id;
//         $model_pro->dot_id       =$val->dot_id;
//         $model_pro->thrand_id    =$val->thrand_id;
//         $model_pro->info          =$val->info;
//         $model_pro->add_time     =$val->add_time;
//         $model_pro->up_time      =$val->up_time;
//         $model_pro->audit        =$val->audit;
//         $model_pro->status       =$val->status;

//         return $model_pro;
//     }

//     /**
//      * tmm_pro_fare
//      */
//     public function get_pro_fare($fare,$pro_id){
//         $model_pro_fare = new ProFare();

//         $model_pro_fare->pro_id      = $pro_id;
//         $model_pro_fare->fare_id     = $fare->fare_id;
//         $model_pro_fare->group_id    = $fare->group_id;
//         $model_pro_fare->items_id    = $fare->items_id;
//         $model_pro_fare->thrand_id   = $fare->thrand_id;
//         $model_pro_fare->add_time    = $fare->add_time;
//         $model_pro_fare->up_time     = $fare->up_time;
//         $model_pro_fare->status      = $fare->status;

//         return $model_pro_fare;
//     }
//     /**
//      * 创建结伴游  ======线路保存
//      * @param $model
//      * @param $group_arr
//      * @param $c_id
//      * @return int
//      */
//     private function thrand_save($model,$c_id){

//         $model->attributes=$_POST['Group'];
//         $model->Group_Shops->attributes=$_POST['Shops'];
//         $status = '';
//         //事务
//         $transaction=$model->dbConnection->beginTransaction();
//         try{
//             $model->Group_Shops->c_id=$c_id;                           //c_id
//             $model->Group_Shops->status=Shops::status_offline;   //下线状态
//             $model->Group_Shops->audit=Shops::audit_pending;     //未审核
//             if($model->Group_Shops->save(false)){
//                 $model->id          = $model->Group_Shops->id;      //结伴游ID
//                 $model->c_id        = $c_id;                      //类型ID　３
//                 $model->user_id     = Yii::app()->api->id;          //用户ID（组织者）
//                 $model->start_time  = time();                       //开始时间
//                 $model->end_time    = strtotime($_POST['Group']['end_time']); //结止时间
//                 $model->group       = Group::group_none;        //团状态
//                 $model->status      = Group::status_down;       //表示结伴游没有成订单 下线状态
//                 if(! $model->save(false))
//                     throw new Exception("创建结伴游(线) 保存结伴游表错误");

//                 //迁移线的数据
//                 $thrand_id = $model->group_thrand;
//                 $model_thrand = $this->thrand_model($thrand_id);
//                 //商品ID
//                 $shop_id=$model->Group_Shops->id;
//                 //复制线里的选中项目表到结伴游中
//                 foreach($model_thrand->Thrand_Pro as $val){
//                     $model_pro = $this->get_pro($val,$c_id,$shop_id);
//                     if(! $model_pro->save(false))
//                         throw new Exception("创建结伴游(线) 保存结伴游选中项目表错误");
//                     //选中项目表  ID
//                     $pro_id = $model_pro->id;
//                     foreach($val->Pro_ProFare as $kf=>$fare){
//                          $model_pro_fare = $this->get_pro_fare($fare,$pro_id);
//                         if(! $model_pro_fare->save(false))
//                             throw new Exception("创建结伴游(线) 保存结伴游选中项目对应价格表错误".$kf);
//                     }
//                 }

//             }else
//                 throw new Exception("审核通过保存错误");
//             //事务提交
//             $transaction->commit();
//             $status = 1;

//         }catch (Exception $e){
//             //事务回滚
//             $transaction->rollBack();
//             $this->error_log($e->getMessage(),ErrorLog::user,ErrorLog::create,ErrorLog::rollback,__METHOD__);
//         }

//         return $status;
//     }

//     /**
//      * 验证
//      * @param unknown $model
//      * @return boolean
//      */
//     public function validate_thrand($model,$c_id)
//     {
//         //$validate_array=array();//需要验证的数据
//         if(! ( isset($_POST['Pro']) && isset($_POST['ProFare'])))
//         {
//             $model->Group_Shops->addError('name', '选择点或选择的项目或选择的价格 不可空白');

//             return false;
//         }
//         $model->Group_Shops->attributes=$_POST['Shops'];
//         $day_number=count($_POST['Pro']);//天数
//         if($day_number != count($_POST['ProFare'])) //比较是否为给每一天都选了价格
//         {
//             $model->Group_Shops->addError('name', '选择点或选择线无选择价格');
//             return false;
//         }
//         //线路中的天数至少一天 最多不超过 设置的天数
//         if($day_number >=1 && $day_number<=Yii::app()->params['shops_thrand_day_number'])
//         {
//             $i=0;//项目数
//             foreach ($_POST['Pro'] as $day_sort=>$day_dots)
//             {
//                 if(!is_int($day_sort) || $day_sort<1 || $day_sort>Yii::app()->params['shops_thrand_day_number'])
//                 {
//                     $model->Group_Shops->addError('name', '日程天数不是有效值');
//                     return false;
//                 }
//                 if(!is_array($day_dots) || empty($day_dots))
//                 {
//                     $model->Group_Shops->addError('name', '结伴游中的选择点存在未上线的项目');
//                     return false;
//                 }
//                 $dot_sort=0; //点的排序
//                 $j=0;
//                 foreach ($day_dots as $half_sort=>$dot_items_ids)
//                 {
//                     if(!is_array($dot_items_ids) || empty($dot_items_ids))
//                     {
//                         $model->Group_Shops->addError('name', '结伴游中的选择点存在未上线的项目');
//                         return false;
//                     }

//                     if($half_sort !=$dot_sort || $half_sort > Yii::app()->params['shops_thrand_one_day_dot_number'])
//                     {
//                         $model->Group_Shops->addError('name', '结伴游中的选择点存在未上线的项目');
//                         return false;
//                     }

//                     foreach ($dot_items_ids as $dot_id=>$items)
//                     {
//                         if(!is_array($items) || empty($items))
//                         {
//                             $model->Group_Shops->addError('name', '结伴游中的选择点存在未上线的项目');
//                             return false;
//                         }

//                         //获取id 点所有的信息
//                         $dot_items_fares_array=$this->get_dot($dot_id);

//                         if(empty($dot_items_fares_array))
//                         {
//                             $model->Group_Shops->addError('name', '结伴游 选择点或项目或价格 不是有效值');
//                             return false;
//                         }

//                         $items_sort=0;//项目排序
//                         foreach ($items as $sort=>$item)
//                         {
//                             if($items_sort != $sort)
//                             {
//                                 $model->Group_Shops->addError('name', '结伴游中的选择点存在未上线的项目');
//                                 return false;
//                             }
//                             //判断点中是否有项目的id
//                             if(isset($dot_items_fares_array['items'][$item]) && $dot_items_fares_array['items'][$item]['is_validate'])
//                                 $dot_items_fares_array['items'][$item]['is_validate']=false;//一个点不能有重复的项目
//                             else{
//                                 $model->Group_Shops->addError('name', '结伴游中或点存在已经选择的项目');
//                                 return false;
//                             }

//                             //项目中的数据
//                             $item_data=$dot_items_fares_array['items'][$item]['data'];
//                             //赋值
//                             $Thrand_Pro=isset($model->Group_Pro[$i])?$model->Group_Pro[$i]:new Pro;
//                             $Thrand_Pro->scenario='create_thrand';
//                             $Thrand_Pro->shops_c_id=$c_id;
//                             $Thrand_Pro->sort=$sort;
//                             $Thrand_Pro->day_sort=$day_sort;
//                             $Thrand_Pro->half_sort=$half_sort;
//                             $Thrand_Pro->items_id=$item;
//                             $Thrand_Pro->dot_id=$dot_id;
//                             $Thrand_Pro->agent_id=$item_data->agent_id;
//                             $Thrand_Pro->store_id=$item_data->store_id;
//                             $Thrand_Pro->c_id=$item_data->c_id;

//                             if(! isset($_POST['ProFare'][$day_sort][$half_sort][$dot_id][$sort][$item]))
//                             {
//                                 $model->Group_Shops->addError('name', '结伴游中或点中的项目无选中的价格');
//                                 return false;
//                             }

//                             $item_select_fares=$_POST['ProFare'][$day_sort][$half_sort][$dot_id][$sort][$item];
//                             if(!is_array($item_select_fares) || empty($item_select_fares))
//                             {
//                                 $model->Group_Shops->addError('name', '结伴游中或点中的项目选中的价格无效');
//                                 return false;
//                             }

//                             $Pro_ProFares=array();
//                             $j=0;//价格数
//                             $fares=array();
//                             foreach ($item_select_fares as $fare)
//                             {
//                                 if(isset($dot_items_fares_array['fares'][$item][$fare]) && $dot_items_fares_array['fares'][$item][$fare]['is_validate'])
//                                     $dot_items_fares_array['fares'][$item][$fare]['is_validate']=false;
//                                 else{
//                                     $model->Group_Shops->addError('name', '结伴游中或点中的项目选中的价格存在重复');
//                                     return false;
//                                 }
//                                 $Pro_ProFare=isset($model->Group_Pro[$i]->Pro_ProFare[$j])?$model->Group_Pro[$i]->Pro_ProFare[$j]:new ProFare;
//                                 $Pro_ProFare->scenario='create_thrand';
//                                 $Pro_ProFare->fare_id=$fare;
//                                 $Pro_ProFare->items_id=$item;
//                                 $Pro_ProFares[]=$Pro_ProFare;
//                                 if(in_array($fare, $fares))
//                                 {
//                                     $model->Group_Shops->addError('name', '结伴游中或点中的项目选中的价格存在重复');
//                                     return false;
//                                 }
//                                 $fares[]=$fare;
//                                 $j++;//价格数
//                             }
//                             $Thrand_Pro->Pro_ProFare=$Pro_ProFares;
//                             $Thrand_Pros[]=$Thrand_Pro;
//                             $this->_new_number[$i]=$j;
//                             $items_sort++;
//                             $i++;//项目数
//                         }
//                     }
//                     $dot_sort++;
//                 }
//             }
//         }
//         if(! isset($Thrand_Pros))
//         {
//             $model->Group_Shops->addError('name', '结伴游中的选择点中存在未上线的项目');
//             return false;
//         }

//         $model->Group_Pro=$Thrand_Pros;
//         return true;
//     }

//     /**
//      * 获得点
//      * @param $dot_id
//      * @return array
//      */
//     public function get_dot($dot_id)
//     {
//         $model=Dot::model()->findByPk($dot_id,array(
//             'with'=>array(
//                 'Dot_Shops'=>array(
//                     'condition'=>'`Dot_Shops`.`status`=:status && `Dot_Shops`.`audit`=:audit',
//                     'params'=>array(':status'=>Shops::status_online,':audit'=>Shops::audit_pass),
//                 ),
//                 'Dot_Pro'=>array(
//                     'with'=>array(
//                         'Pro_ItemsClassliy',
//                         'Pro_Items'=>array(
//                             'with'=>array(
//                                 'Items_StoreContent'=>array('with'=>array('Content_Store')),
//                                 'Items_Fare',
//                                 'Items_area_id_p_Area_id',
//                                 'Items_area_id_m_Area_id',
//                                 'Items_area_id_c_Area_id',
//                             ),
//                             'condition'=>'`Pro_Items`.`status`=1 AND `Pro_Items`.`audit`=:audit',
//                             'params'=>array(':audit'=>Items::audit_pass),
//                         ),
//                     ),
//                     'order'=>'Dot_Pro.sort',
//                 ),
//             ),
//         ));
//         if($model)
//         {
//             if($model->Dot_Shops)
//                 $shops=$model->Dot_Shops;
//             else
//                 return array();
//             if(! $model->Dot_Pro)
//                 return array();

//             foreach ($model->Dot_Pro as $Dot_Pro)
//             {
//                 if(! $Dot_Pro->Pro_Items)
//                     return array();
//                 $items[$Dot_Pro->items_id]=array('data'=>$Dot_Pro->Pro_Items,'is_validate'=>true);
//                 if(! $Dot_Pro->Pro_Items->Items_Fare)
//                     return array();
//                 foreach ($Dot_Pro->Pro_Items->Items_Fare as $Items_Fare)
//                     $fares[$Dot_Pro->items_id][$Items_Fare->id]=array('data'=>$Items_Fare,'is_validate'=>true);
//             }
//             return array('shops'=>$shops,'items'=>$items,'fares'=>$fares);
//         }
//         return array();
//     }


//     /**
//      * 更新结伴游====点
//      * @param unknown $id
//      * @param string $c_id
//      * @return unknown
//      */
//     public function new_model($id,$c_id='')
//     {
//         if($c_id=='')
//             $c_id=ShopsClassliy::getClass()->id;
//         return $this->loadModel($id,array(
//             'with'=>array(
//                 'Group_ShopsClassliy',
//                 'Group_Shops'=>array('with'=>array('Shops_Agent')),
//                 'Group_User',
//                 'Group_Pro'=>array('with'=>array(
//                     'Pro_Group_Dot'=>array('with'=>'Dot_Shops'),
//                     'Pro_Group_Thrand'=>array('with'=>'Thrand_Shops'),
//                     'Pro_Items'=>array(
//                         'with'=>array(
//                             'Items_area_id_p_Area_id',
//                             'Items_area_id_m_Area_id',
//                             'Items_area_id_c_Area_id',
//                             'Items_ItemsImg',
//                             'Items_StoreContent'=>array('with'=>array('Content_Store')),
//                             'Items_Store_Manager',
//                             'Items_ItemsClassliy',
//                         ),
//                     ),
//                     'Pro_ItemsClassliy',
//                     'Pro_ProFare'=>array('with'=>array('ProFare_Fare')),
//                 )),
//             ),
//             'condition'=>'`t`.`c_id`=:c_id AND `Group_Shops`.`status`=:status AND `Group_Shops`.`audit` != :audit',
//             'params'=>array(':c_id'=>$c_id,':status'=>Shops::status_offline,':audit'=>Shops::audit_pass),
//             'order'=>'Group_Pro.day_sort,Group_Pro.half_sort,Group_Pro.sort',
//         ));

//     }

//     /**
//      *更新结伴游====线
//      * @param $id
//      * @param string $c_id
//      * @return mixed
//      */
//     public function group_thrand_model($id,$c_id=''){
//         if($c_id=='')
//             $c_id=ShopsClassliy::getClass()->id;
//         return $this->loadModel($id,array(
//             'with'=>array(
//                 'Group_ShopsClassliy',
//                 'Group_Shops',
//                 'Group_User',
//             ),
//             'condition'=>'`t`.`c_id`=:c_id AND `Group_Shops`.`status`=:status AND `Group_Shops`.`audit` != :audit',
//             'params'=>array(':c_id'=>$c_id,':status'=>Shops::status_offline,':audit'=>Shops::audit_pass),
//         ));
//     }
//     /**
//      * 结伴游更新
//      * @param $id
//      */
//     public function actionUpdate($id){

//         $shops_classliy = ShopsClassliy::getClass();


// //        $group_test = array(
// //            'group_thrand'=>0,          //线路ID 点为0
// //            'price'=>2100.00,               //结伴游服务费
// //            'remark'=>'app 73线路',        //结伴游备注
// //            'end_time'=>'2015-10-01', //报名截止时间
// //        );
// //        $shop_test = array('name'=>'app创建线路73结伴游');  //结伴游名称
// //         $_POST = array(
// //             'group_type'=>1,  //发起结伴游类型  1=点 2=线
// //             'is_insurance'=>1,
// //             'Pro'=>$this->pro_arr_test(),
// //             'ProFare'=>$this->fate_arr_test(),
// //             'Shops'=>$shop_test,
// //             'Group'=>$group_test,
// //         );

//         if(isset($_POST['group_type']) && isset($_POST['Shops']) && isset($_POST['Group']) ) {
//             //判断是否是线
//             if (isset($_POST['group_type']) && $_POST['group_type'] == 2 && isset($_POST['Group']) && isset($_POST['Group']['group_thrand']) && $_POST['Group']['group_thrand'] && is_numeric($_POST['Group']['group_thrand'])) {

//                 //判断报名截止时间是否在指定时间段内
//                 if ($this->end_time($_POST['Group']['end_time'])){
//                     $model = $this->group_thrand_model($id,$shops_classliy->id);
//                     //结伴游，线路创建
//                     $status = $this->thrand_save($model, $shops_classliy->id);
//                 }
//                 else
//                     $this->send_error(GROUP_CURRENT_END_ERROR);

//             }
//             //判断是否是点
//             if (isset($_POST['group_type']) && $_POST['group_type'] == 1) {

//                 //查询数据获得model
//                 $model=$this->new_model($id,$shops_classliy->id);

//                 $model->Group_Shops->scenario='create_thrand';
//                 //设置验证场景
//                 foreach ($model->Group_Pro as $Pro)
//                 {
//                     $Pro->scenario='create_thrand';
//                     foreach ($Pro->Pro_ProFare as $ProFare)
//                         $ProFare->scenario='create_thrand';
//                 }

//                 if (isset($_POST['Shops']) && isset($_POST['Group']) && isset($_POST['Pro']) && is_array($_POST['Pro']) && isset($_POST['ProFare']) && is_array($_POST['ProFare']) && count($_POST['ProFare']) == count($_POST['Pro'])) {
//                     //判断报名截止时间是否在指定时间段内
//                     if ($this->end_time($_POST['Group']['end_time'])) {

//                         //验证数据是否有错误
//                         if ($this->validate_thrand($model, $shops_classliy->id))
//                         {
//                             $model->scenario='create';
//                             $model->attributes=$_POST['Group'];
//                             //提前验证
//                             $validate_pros_fares = true;
//                             $validate_pros_fares=$model->validate();
//                             foreach ($model->Group_Pro as $pro) {
//                                 if (!$pro->validate())
//                                     $validate_pros_fares = false;
//                                 foreach ($pro->Pro_ProFare as $fare) {
//                                     if (!$fare->validate())
//                                         $validate_pros_fares = false;
//                                 }
//                             }

//                             if ($validate_pros_fares) {
//                                 $status = $this->update_dot_save($model,$id, $shops_classliy->id);
//                             }else
//                                 $this->send_error_form($this->form_error(array($model,$model->Group_Shops)));
//                         } else
//                             $this->send_error_form($this->form_error(array($model,$model->Group_Shops)));
//                     } else
//                         $this->send_error(GROUP_CURRENT_END_ERROR);
//                 }



//             }
//             //反状态
//             if (isset($status) && $status == 1) {
//                 //成功
//                 $return = array(
//                     'status' => STATUS_SUCCESS,
//                 );
//                 $this->send($return);
//             } else{
//                 $this->send_error(DATA_NOT_SCUSSECS);
//             }
//         }
//         $this->send_csrf();
//     }

//     /**
//      * @param $model
//      * @param $id
//      * @param $c_id
//      * @return int|string
//      */
//     public function update_dot_save($model,$id,$c_id){

//         $status = '';
//         $delete_pro_number=count($this->_new_number);
//         $delete_models=array();
//         $old_model=$this->new_model($id,$c_id);
//         foreach ($old_model->Group_Pro as $key_pro=>$delete_pro)
//         {
//             if(($key_pro+1) <= $delete_pro_number)
//             {
//                 $delete_fare_number=$this->_new_number[$key_pro];
//                 foreach ($delete_pro->Pro_ProFare as $key_fare=>$delete_fare)
//                 {
//                     if(($key_fare+1) > $delete_fare_number)
//                         $delete_models[]=$delete_fare;
//                 }
//             }else
//                 $delete_models[]=$delete_pro;
//         }

//         //事物
//         $transaction=$model->dbConnection->beginTransaction();
//         try{
//             $model->Group_Shops->c_id=$c_id;
//             $model->Group_Shops->status=Shops::status_offline;
//             $model->Group_Shops->audit=Shops::audit_pending;
//             if($model->Group_Shops->save(false))
//             {
//                 $model->id           = $model->Group_Shops->id;      //结伴游ID
//                 $model->c_id         = $c_id;                         //类型ID　３
//                 $model->user_id     = Yii::app()->api->id;          //用户ID（组织者）
//                 $model->end_time    = strtotime($_POST['Group']['end_time']); //结止时间
//                 $model->group       = Group::group_none;             //团状态
//                 $model->status      = Shops::status_offline;        //状态
//                 if(! $model->save(false))
//                     throw new Exception("修改线路(结伴游) 保存线路附表错误");
//                 foreach ($model->Group_Pro as $pro_save)
//                 {
//                     $pro_save->shops_id=$model->id;
//                     if(! $pro_save->save(false))
//                         throw new Exception("修改线路(结伴游) 保存选中项目表错误");
//                     foreach ($pro_save->Pro_ProFare as $fare_save)
//                     {
//                         $fare_save->pro_id=$pro_save->id;
//                         $fare_save->group_id=$model->id;
//                         if(! $fare_save->save(false))
//                             throw new Exception("修改线路(结伴游) 保存选中项目的选中价格表错误");
//                     }
//                 }
//                 foreach ($delete_models as $delete_model)
//                 {
//                     if(isset($delete_model->Pro_ProFare))
//                     {
//                         foreach ($delete_model->Pro_ProFare as $fare_delete)
//                         {
//                             if(! $fare_delete->delete())
//                                 throw new Exception("修改线路选中项的选中价格错误");
//                         }
//                     }
//                     if(! $delete_model->delete())
//                         throw new Exception("修改线路选中项错误");
//                 }
//                 $return=$this->log('修改线路(结伴游)',ManageLog::user,ManageLog::update);
//             }else
//                 throw new Exception("修改线路主要记录错误");
//             $transaction->commit();
//             $status = 1;
//         }
//         catch(Exception $e)
//         {
//             $transaction->rollBack();
//             $this->error_log($e->getMessage(),ErrorLog::user,ErrorLog::update,ErrorLog::rollback,__METHOD__);
//         }

//         return $status;
//     }



//     /**
//      * 判断报名截止时间
//      * @param $end_time
//      */
//     public function end_time($end,$day=''){
//         $status = 0;
//         $day    = $day?$day:Yii::app()->params['group_end_time'];
//         $stars_time = time();
//         $end_time   =  $stars_time+ ($day*24*60*60);
//         $end  = strtotime($end);
//         if($end > $stars_time && $end <$end_time)
//             $status = 1;
//         return $status;
//     }

//     public function fate_arr_test(){
//         return  $fate_arr_test =  array(
//             1 => array(
//                 0=> array(
//                     15 => array(
//                         0 => array (
//                             37 => array(
//                                 0=> 41,
//                                 1=> 42
//                             )
//                         ),
//                         1 => array(
//                             5 => array (
//                                 0 => 5,
//                                 1 => 45
//                             )
//                         )
//                     )
//                 )
//             ),
//             2 => array(
//                 0 => array(
//                     17 => array(
//                         0 => array (
//                             37 => array(
//                                 0 => 44
//                             )
//                         )
//                     )
//                 )
//             )
//         );
//         }

//     public function pro_arr_test(){
//         return  $pro_arr_test = array(
//             1 => array(
//                 0 => array(
//                     15 => array(
//                         0 => 37,
//                         1 => 5
//                     )
//                 )
//             ),
//             2 => array(
//                 0 => array(
//                     17 => array(
//                         0 => 37
//                     )
//                 )
//             )
//         );
//     }

}
