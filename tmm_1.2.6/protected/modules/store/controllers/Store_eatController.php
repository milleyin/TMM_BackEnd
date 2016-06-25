<?php
/**
 * 商家项目(吃)控制器
 * @author Moore Mo
 * Class Store_eatController
 */
class Store_eatController extends StoreMainController
{
	/**
	 * 默认操作数据模型
	 * @var string
	 */
	public $_class_model='Eat';

	/**
	 * 查看项目（吃）详情
	 * @param $id
	 */
	public function actionView($id)
	{
		$this->_class_model = 'StoreUser';
		$this->loadModel(Yii::app()->store->id,'status=1');

		$this->_class_model = 'Eat';
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
			'condition' => '`Eat_Items`.`status`=1 AND `Eat_Items`.`audit`=:audit AND (`Eat_Items`.`store_id`=:store_id OR `Eat_Items`.`manager_id`=:manager_id)',
			'params'=>array(
				':audit'=>Items::audit_pass,
				':store_id'=>Yii::app()->store->id,
				':manager_id'=>Yii::app()->store->id,
			),
		));
		// 标签
		$model->Eat_TagsElement = TagsElement::get_select_tags(TagsElement::tags_items_eat,$id);

		$return  = array();
		$datas = array(
			'name','info','phone','weixin','down','start_work','end_work'
		);
		$img_domain = Yii::app()->params['admin_img_domain'];
		//项目列表详情
		foreach($datas as $data)
			$return[$data] = CHtml::encode($model->Eat_Items->$data);
		//项目价格
		foreach($model->Eat_Fare as $k=>$fare){
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
		$return['map'] = (isset($model->Eat_Items->map) && $model->Eat_Items->map)?$img_domain.trim($this->litimg_path($model->Eat_Items->map),'.'):'';
		//显示一张图片
		$return['img'] = (isset($model->Eat_ItemsImg[0]->img) && $model->Eat_ItemsImg[0]->img)?$img_domain.trim($this->litimg_path($model->Eat_ItemsImg[0]->img),'.'):'';

		// 图片列表
		if (! empty($model->Eat_ItemsImg)) {
			foreach ($model->Eat_ItemsImg as $eat_img) {
				$return['img_list'][] = array(
					'img' => (isset($eat_img->img) && $eat_img->img)?$img_domain.trim($this->litimg_path($eat_img->img),'.'):'',
					'litimg' => (isset($eat_img->litimg) && $eat_img->litimg)?$img_domain.trim($eat_img->litimg,'.'):'',
					'title' => CHtml::encode($eat_img->title),
					'alt' => CHtml::encode($eat_img->alt),
					'height' => CHtml::encode($eat_img->height),
					'with' => CHtml::encode($eat_img->with),
				);
			}

		}

		//项目类型
		$return['item_type']['value'] = (int)$model->Eat_ItemsClassliy->id;
		$return['item_type']['name']  = $model->Eat_ItemsClassliy->name;
		//详情地址
		$return['address'] =  CHtml::encode($model->Eat_Items->Items_area_id_p_Area_id->name .
											$model->Eat_Items->Items_area_id_m_Area_id->name .
											$model->Eat_Items->Items_area_id_c_Area_id->name .
											$model->Eat_Items->address);
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
