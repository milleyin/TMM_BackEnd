<?php
/**
 * 商家项目(住)控制器
 * @author Moore Mo
 * Class Store_hotelController
 */
class Store_hotelController extends StoreMainController
{
	/**
	 * 默认操作数据模型
	 * @var string
	 */
	public $_class_model='Hotel';

	/**
	 * 查看项目（住）详情
	 * @param $id
	 */
	public function actionView($id)
	{
		$this->_class_model = 'StoreUser';
		$this->loadModel(Yii::app()->store->id,'status=1');

		$this->_class_model = 'Hotel';
		$model = $this->loadModel($id, array(
			'with' => array(
				'Hotel_Items' => array(
					'with' => array(
						'Items_agent',
						'Items_StoreContent' => array('with' => array('Content_Store')),
						'Items_Store_Manager',
						'Items_area_id_p_Area_id' => array('select' => 'name'),
						'Items_area_id_m_Area_id' => array('select' => 'name'),
						'Items_area_id_c_Area_id' => array('select' => 'name'),
					)),
				'Hotel_ItemsClassliy',
				'Hotel_ItemsWifi' => array('with' => array('ItemsWifi_Wifi')),
				'Hotel_Fare',
				'Hotel_ItemsImg',
			),
			// 查看自己 除删除  查看别人 (上线，审核通过)
			'condition' => '`Hotel_Items`.`status`=1 AND `Hotel_Items`.`audit`=:audit AND (`Hotel_Items`.`store_id`=:store_id OR `Hotel_Items`.`manager_id`=:manager_id)',
			'params'=>array(
				':audit'=>Items::audit_pass,
				':store_id'=>Yii::app()->store->id,
				':manager_id'=>Yii::app()->store->id,
			),
		));
		// 标签
		$model->Hotel_TagsElement = TagsElement::get_select_tags(TagsElement::tags_items_hotel, $id);

		$return  = array();
		$datas = array(
			'name','info','phone','weixin','down','start_work','end_work'
		);
		$img_domain = Yii::app()->params['admin_img_domain'];
		//项目列表详情
		foreach($datas as $data)
			$return[$data] = CHtml::encode($model->Hotel_Items->$data);
		//项目价格
		foreach($model->Hotel_Fare as $k=>$fare){
			$return['fare'][$k]['name']  = CHtml::encode($fare->name);
			$return['fare'][$k]['info']  = CHtml::encode($fare->info);
			$return['fare'][$k]['number']= CHtml::encode($fare->number);
			$return['fare'][$k]['price'] = CHtml::encode($fare->price);
		}
		//标签
		foreach($model->Hotel_TagsElement as $k=>$tags)
			$return['tags'][] = CHtml::encode($tags->TagsElement_Tags->name);

		// 项目服务
		foreach($model->Hotel_ItemsWifi as $wifi) {
			$return['wifis'][] = array(
				'name' => CHtml::encode($wifi->ItemsWifi_Wifi->name),
				'icon' => (isset($wifi->ItemsWifi_Wifi->icon) && $wifi->ItemsWifi_Wifi->icon)?$img_domain.trim($wifi->ItemsWifi_Wifi->icon,'.'):'',
			);

		}

		//详情内容
		$return['content'] = $model->Hotel_Items->content;
		//显示地图
		$return['map'] = (isset($model->Hotel_Items->map) && $model->Hotel_Items->map)?$img_domain.trim($this->litimg_path($model->Hotel_Items->map),'.'):'';
		//显示一张图片
		$return['img'] = (isset($model->Hotel_ItemsImg[0]->img) && $model->Hotel_ItemsImg[0]->img)?$img_domain.trim($this->litimg_path($model->Hotel_ItemsImg[0]->img),'.'):'';

		// 图片列表
		if (! empty($model->Hotel_ItemsImg)) {
			foreach ($model->Hotel_ItemsImg as $hotel_img) {
				$return['img_list'][] = array(
					'img' => (isset($hotel_img->img) && $hotel_img->img)?$img_domain.trim($this->litimg_path($hotel_img->img),'.'):'',
					'litimg' => (isset($hotel_img->litimg) && $hotel_img->litimg)?$img_domain.trim($hotel_img->litimg,'.'):'',
					'title' => CHtml::encode($hotel_img->title),
					'alt' => CHtml::encode($hotel_img->alt),
					'height' => CHtml::encode($hotel_img->height),
					'with' => CHtml::encode($hotel_img->with),
				);
			}

		}

		//项目类型
		$return['item_type']['value'] = (int)$model->Hotel_ItemsClassliy->id;
		$return['item_type']['name']  = $model->Hotel_ItemsClassliy->name;
		//详情地址
		$return['address'] =  CHtml::encode($model->Hotel_Items->Items_area_id_p_Area_id->name .
											$model->Hotel_Items->Items_area_id_m_Area_id->name .
											$model->Hotel_Items->Items_area_id_c_Area_id->name .
											$model->Hotel_Items->address);
		//商家
		$return['store_name'] = CHtml::encode($model->Hotel_Items->Items_StoreContent->name);
		//创建时间
		$return['add_time'] = Yii::app()->format->datetime($model->Hotel_Items->add_time);
		//审核状态
		$return['audit_type']=(int)$model->Hotel_Items->audit;
		$return['audit_name']=CHtml::encode(Items::$_audit[$model->Hotel_Items->audit]);

		$this->send($return);
	}
}
