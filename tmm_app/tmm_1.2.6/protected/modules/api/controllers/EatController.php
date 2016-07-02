<?php
/**
 * 
 * @author Changhai Zhan
 *	创建时间：2015-09-17 11:02:11 */
class EatController extends ApiController
{
	/**
	 * 默认操作数据模型
	 * @var string
	 */
	public $_class_model='Eat';

	/**
	 * 查看详情
	 * @param integer $id
	 */
	public function actionView($id)
	{

		$model = $this->loadModel($id,array(
			'with'=>array(
				'Eat_Items'=>array(
					'with'=>array(
						'Items_agent',
						'Items_StoreContent'=>array('with'=>array('Content_Store')),
						'Items_Store_Manager',
						'Items_area_id_p_Area_id'=>array('select'=>'name'),
						'Items_area_id_m_Area_id'=>array('select'=>'name'),
						'Items_area_id_c_Area_id'=>array('select'=>'name'),
					)),
				'Eat_ItemsClassliy',
				'Eat_Fare',
				'Eat_ItemsImg',
			),
			'condition' => '`Eat_Items`.`status`=1 AND `Eat_Items`.`audit`=:audit ',
			'params'=>array(
				':audit'=>Items::audit_pass,
			),
		));
		// 标签
		$model->Eat_TagsElement = TagsElement::get_select_tags(TagsElement::tags_items_eat,$id);

		$return  = array();
		$datas = array(
			'name','info','phone','weixin','down','start_work','end_work','lng','lat'
		);
		//项目列表详情
		foreach($datas as $data)
			$return[$data] = CHtml::encode($model->Eat_Items->$data);
		$return['value']=$model->id;
		//项目价格
		foreach($model->Eat_Fare as $k=>$fare){
			$return['fare'][$k]['value']=$fare->id;
			$return['fare'][$k]['name']  = CHtml::encode($fare->name);
			$return['fare'][$k]['info']  = CHtml::encode($fare->info);
			$return['fare'][$k]['price'] = CHtml::encode($fare->price);
		}
		//标签
		foreach($model->Eat_TagsElement as $k=>$tags)
			$return['tags'][] = CHtml::encode($tags->TagsElement_Tags->name);
		//详情内容
		$return['content'] = $model->Eat_Items->content;
		//显示地图
		$return['map'] = (isset($model->Eat_Items->map) && $model->Eat_Items->map)?Yii::app()->params['admin_img_domain'].trim($this->litimg_path($model->Eat_Items->map),'.'):'';
		//显示一张图片
		$return['img']		= (isset($model->Eat_ItemsImg[0]->img) && $model->Eat_ItemsImg[0]->img)?Yii::app()->params['admin_img_domain'].trim($this->litimg_path($model->Eat_ItemsImg[0]->img),'.'):'';
		$return['img_arr']	= Items::items_img($model->Eat_ItemsImg);
		//项目类型
		$return['item_type']['value'] = (int)$model->Eat_ItemsClassliy->id;
		$return['item_type']['name']  = $model->Eat_ItemsClassliy->name;
		//距离多少米
		$return['distance'] = Items::items_gps($id);
		//是否免费
		$return['free_status']= array(
				'name'=>Items::$_free_status[$model->Eat_Items->free_status],
				'value'=>CHtml::encode($model->Eat_Items->free_status)
		);
		//详情地址
		$return['address'] =  CHtml::encode($model->Eat_Items->Items_area_id_p_Area_id->name .
											$model->Eat_Items->Items_area_id_m_Area_id->name .
											$model->Eat_Items->Items_area_id_c_Area_id->name .
											$model->Eat_Items->address);
		//地址状态
		$return['address_arr'] = array(
			'province'=> CHtml::encode($model->Eat_Items->Items_area_id_p_Area_id->name),
			'city'=> CHtml::encode($model->Eat_Items->Items_area_id_m_Area_id->name),
			'area'=> CHtml::encode($model->Eat_Items->Items_area_id_c_Area_id->name),
			'address'=> CHtml::encode($model->Eat_Items->address),
		);
		//商家
		$return['store_name'] = CHtml::encode($model->Eat_Items->Items_StoreContent->name);
		//创建时间
		$return['add_time'] = Yii::app()->format->datetime($model->Eat_Items->add_time);
		//审核状态
		$return['audit_type']=(int)CHtml::encode($model->Eat_Items->audit);
		$return['audit_name']=CHtml::encode(Items::$_audit[$model->Eat_Items->audit]);

		$this->send($return);
	}


}
