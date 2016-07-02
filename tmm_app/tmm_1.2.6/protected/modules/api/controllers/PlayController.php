<?php
/**
 * 
 * @author Changhai Zhan
 *	创建时间：2015-09-17 11:02:38 */
class PlayController extends ApiController
{
	/**
	 * 默认操作数据模型
	 * @var string
	 */
	public $_class_model='Play';

	/**
	 * 查看项目（玩）详情
	 * @param $id
	 * @throws CHttpException
	 */
	public function actionView($id)
	{
		$model = $this->loadModel($id,array(
			'with'=>array(
				'Play_Items'=>array(
					'with'=>array(
						'Items_agent',
						'Items_StoreContent'=>array('with'=>array('Content_Store')),
						'Items_Store_Manager',
						'Items_area_id_p_Area_id'=>array('select'=>'name'),
						'Items_area_id_m_Area_id'=>array('select'=>'name'),
						'Items_area_id_c_Area_id'=>array('select'=>'name'),
					)),
				'Play_ItemsClassliy',
				'Play_Fare',
				'Play_ItemsImg',
			),
			// 查看自己 除删除  查看别人 (上线，审核通过)
			'condition' => '`Play_Items`.`status`=1 AND `Play_Items`.`audit`=:audit ',
			'params'=>array(
				':audit'=>Items::audit_pass,
			),
		));
		// 标签
		$model->Play_TagsElement = TagsElement::get_select_tags(TagsElement::tags_items_play,$id);

		$return  = array();
		$datas = array(
			'name','info','phone','weixin','down','start_work','end_work','lng','lat'
		);
		//项目列表详情
		foreach($datas as $data)
			$return[$data] = CHtml::encode($model->Play_Items->$data);
		$return['value']=$model->id;
		//项目价格
		foreach($model->Play_Fare as $k=>$fare){
			$return['fare'][$k]['value']=$fare->id;
			$return['fare'][$k]['name']  = CHtml::encode($fare->name);
			$return['fare'][$k]['info']  = CHtml::encode($fare->info);
			$return['fare'][$k]['price'] = CHtml::encode($fare->price);
		}
		//标签
		foreach($model->Play_TagsElement as $k=>$tags)
			$return['tags'][] = CHtml::encode($tags->TagsElement_Tags->name);
		//详情内容
		$return['content'] = $model->Play_Items->content;
		//显示地图
		$return['map'] = (isset($model->Play_Items->map) && $model->Play_Items->map)?Yii::app()->params['admin_img_domain'].ltrim($this->litimg_path($model->Play_Items->map),'.'):'';
		//显示一张图片
		$return['img'] 		= (isset($model->Play_ItemsImg[0]->img) && $model->Play_ItemsImg[0]->img)?Yii::app()->params['admin_img_domain'].ltrim($this->litimg_path($model->Play_ItemsImg[0]->img),'.'):'';
		$return['img_arr']	= Items::items_img($model->Play_ItemsImg);
		//项目类型
		$return['item_type']['value'] = (int)$model->Play_ItemsClassliy->id;
		$return['item_type']['name']  = $model->Play_ItemsClassliy->name;
		//距离多少米
		$return['distance'] = Items::items_gps($id);
		//是否免费
		$return['free_status']= array(
			'name'=>Items::$_free_status[$model->Play_Items->free_status],
			'value'=>CHtml::encode($model->Play_Items->free_status)
		);
		//详情地址
		$return['address'] =  CHtml::encode($model->Play_Items->Items_area_id_p_Area_id->name .
											$model->Play_Items->Items_area_id_m_Area_id->name .
											$model->Play_Items->Items_area_id_c_Area_id->name .
											$model->Play_Items->address);
		//地址状态
		$return['address_arr'] = array(
			'province'=> CHtml::encode($model->Play_Items->Items_area_id_p_Area_id->name),
			'city'=> CHtml::encode($model->Play_Items->Items_area_id_m_Area_id->name),
			'area'=> CHtml::encode($model->Play_Items->Items_area_id_c_Area_id->name),
			'address'=> CHtml::encode($model->Play_Items->address),
		);
		//商家
		$return['store_name'] = CHtml::encode($model->Play_Items->Items_StoreContent->name);
		//创建时间
		$return['add_time'] = Yii::app()->format->datetime($model->Play_Items->add_time);
		//审核状态
		$return['audit_type']=(int)$model->Play_Items->audit;
		$return['audit_name']=CHtml::encode(Items::$_audit[$model->Play_Items->audit]);

		$this->send($return);
	}
}
