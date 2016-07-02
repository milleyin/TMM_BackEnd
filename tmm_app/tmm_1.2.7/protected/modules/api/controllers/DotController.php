<?php
/**
 * 
 * @author Changhai Zhan
 *	创建时间：2015-09-17 10:59:05 */
class DotController extends ApiController
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
		$shops_classliy=ShopsClassliy::getClass();
		$model=$this->loadModel($id,array(
			'with'=>array(
				'Dot_Shops'=>array('with'=>array('Shops_Agent',)),
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
						'condition'=>'Pro_Items.status=1 AND Pro_Items.audit=:audit',
						'params'=>array(':audit'=>Items::audit_pass),
					),
					'Pro_ItemsClassliy',
				)),
			),
			'condition'=>'t.c_id=:c_id AND `Dot_Shops`.`status`=1 AND `Dot_Shops`.`audit`=:audit  ',
			'params'=>array(':c_id'=>$shops_classliy->id,':audit'=>Shops::audit_pass),
			'order'=>'Dot_Pro.sort',
		));
		// 添加 浏览量
		Shops::set_shops_brow($id);
		//标签
		$model->Dot_TagsElement=TagsElement::get_select_tags(TagsElement::tags_shops_dot,$id);
		//赞
		$model->Dot_Shops->Shops_Collect  = Collect::get_collect_praise(Collect::collect_type_praise,$id,Yii::app()->api->id);
		//点的外链
		$model->Dot_FarmOuter  = FarmOuter::get_farm_outer($id);
		$return  = array();
		$datas = array(
			'name','list_info','page_info','brow','share','praise','c_id',
		);
		//商品列表详情
		foreach($datas as $data)
			$return[$data] = CHtml::encode($model->Dot_Shops->$data);
		//运营商电话
		$return['manage_phone']=CHtml::encode($model->Dot_Shops->Shops_Agent->manage_phone);
		//田觅觅电话
		$return['tmm_phone']=CHtml::encode(Yii::app()->params['tmm_400']);
		//多少起
		$return['price']=Dot::shops_price_num($model->id);
		//下单量
		$return['down']=Dot::get_down($model->id);
		//费用包含简介  cost_info
		$return['cost_info'] = isset($model->Dot_Shops->cost_info) ?$model->Dot_Shops->cost_info:'';
		//预定须知简介  book_info
		$return['book_info'] = isset($model->Dot_Shops->book_info) ? $model->Dot_Shops->book_info:'';

		//标签
		foreach($model->Dot_TagsElement as $k=>$tags)
			$return['tags'][] = CHtml::encode($tags->TagsElement_Tags->name);

		//显示图片
		$return['list_img'] = (isset($model->Dot_Shops->list_img) && $model->Dot_Shops->list_img)?Yii::app()->params['admin_img_domain'].trim($this->litimg_path($model->Dot_Shops->list_img),'.'):'';
		$return['page_img'] = (isset($model->Dot_Shops->page_img) && $model->Dot_Shops->page_img)?Yii::app()->params['admin_img_domain'].trim($this->litimg_path($model->Dot_Shops->page_img),'.'):'';
		//分享图片
		$share_img=$this->litimg_path(isset($model->Dot_Shops->list_img)?$model->Dot_Shops->list_img:'','litimg_share',$this->litimg_path(isset($model->Dot_Shops->list_img)?$model->Dot_Shops->list_img:''));
		$return['share_image']=empty($share_img)?'':Yii::app()->params['admin_img_domain'].ltrim($share_img,'.');
		
		//项目名
		$return['c_name'] = CHtml::encode(ShopsClassliy::$_shop_type_name[$model->Dot_Shops->c_id]);
		//创建时间
		$return['add_time'] = Yii::app()->format->datetime($model->Dot_Shops->add_time);
		//审核状态
		$return['audit_type']=(int)$model->Dot_Shops->audit;
		$return['audit_name']=CHtml::encode(Items::$_audit[$model->Dot_Shops->audit]);

		//点赞====链接
		$return['link_collect'] = Yii::app()->params['app_api_domain'].Yii::app()->createUrl('/api/shops/collect',array('id'=>$id));
		$return['dot_id']    = $id;


		//点赞====状态
		$return['collent_status'] =  isset($model->Dot_Shops->Shops_Collect->is_collect) && $model->Dot_Shops->Shops_Collect->is_collect==1 ?1:0;
		$return['collent_name']   = $return['collent_status']==1 ? '已赞':'取消';
		//卖、不
		$return['is_sale']   = array('name'=>Shops::$_is_sale[$model->Dot_Shops->is_sale],'value'=>$model->Dot_Shops->is_sale);

		//项目列表
		foreach($model->Dot_Pro as $k=>$v){
			$return['list'][$k]['item_name']  = CHtml::encode($v->Pro_Items->name);
			$return['list'][$k]['item_id'] 	 = CHtml::encode($v->Pro_Items->id);
			$return['list'][$k]['item_link'] 	 = Yii::app()->params['app_api_domain'].Yii::app()->createUrl('/api/'.$v->Pro_ItemsClassliy->admin.'/view',array('id'=>$v->Pro_Items->id));
			$return['list'][$k]['item_type']['value']  = (int)$v->Pro_ItemsClassliy->id;
			$return['list'][$k]['item_type']['name']   =  CHtml::encode($v->Pro_Items->Items_ItemsClassliy->name);
			$return['list'][$k]['item_info'] 		= CHtml::encode($v->info);
			$return['list'][$k]['store_name']	= CHtml::encode($v->Pro_Items->Items_StoreContent->name);
			$return['list'][$k]['item_img_arr']	= Items::items_img($v->Pro_Items->Items_ItemsImg);
			$return['list'][$k]['item_img']  		= isset($v->Pro_Items->Items_ItemsImg[0]->img)?Yii::app()->params['admin_img_domain'].trim($this->litimg_path($v->Pro_Items->Items_ItemsImg[0]->img),'.'):'';
			$return['list'][$k]['fare']      		= Fare::show_fare_api($v->Pro_Items->Items_Fare,$v->Pro_ItemsClassliy->append=='Hotel'?true:false);
			$return['list'][$k]['address']   		= CHtml::encode($v->Pro_Items->Items_area_id_p_Area_id->name.
																$v->Pro_Items->Items_area_id_m_Area_id->name.
																$v->Pro_Items->Items_area_id_c_Area_id->name.
																$v->Pro_Items->address);
			//是否免费
			$return['list'][$k]['free_status']= array(
					'name'=>Items::$_free_status[$v->Pro_Items->free_status],
					'value'=>CHtml::encode($v->Pro_Items->free_status)
			);
		}

		//农产品 外链 数量
		$exterior_link_num = 0;
		$link_arr		   = array();
		//点 外链
		if($model->Dot_FarmOuter){
			foreach($model->Dot_FarmOuter as $outer){
				$link_arr[] = $return['list'][] = array(
					'item_id'  =>$outer->id,
					'item_name'=>$outer->name,
					'item_info'=>$outer->info,
					'item_img'=>Yii::app()->params['admin_img_domain'].trim($this->litimg_path($outer->img),'.'),
					'item_link'=>$outer->link,
					'item_type'=>array(
						'value'  => FarmOuter::outer_type,
						'name'  =>  FarmOuter::outer_name,
					)
				);
				$exterior_link_num ++ ;
			}
		}
		//农产品 外链 数量
		$return['exterior_link_num'] =$exterior_link_num;
		$return['exterior_link_arr'] =$link_arr;

		$return['list_num']=count($return['list']);
		$this->send($return);
	}

}
