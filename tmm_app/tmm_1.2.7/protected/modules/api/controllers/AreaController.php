<?php
/**
 * 定位控制器
 * @author Changhai Zhan
 *
 */
class AreaController extends ApiController
{
	/**
	 * 设置当前操作数据模型
	 * @var string
	 */
	public $_class_model='Area';
	
	/**
	 * 获取城市 可搜索 省/城市/省拼音/城市拼音 （支持缩写拼音）
	 * @param string $search
	 */
	public function actionIndex($search='')
	{
		//获取搜索的城市
		$models=Area::getAreaCity($search);
		//获取热门城市
		$hot_modes=Area::getHotCity();
		$return=array();
		$return['hot_city']=array();
		foreach ($hot_modes as $hot_mode)
		{
			$return['hot_city'][]=array(
					'name'=>$hot_mode->name,
					'value'=>$hot_mode->id,
					'spell'=>$hot_mode->nid,
					'link'=>Yii::app()->params['app_api_domain'].Yii::app()->createUrl('/api/area/set',array('id'=>$hot_mode->id)),
					'counties_link'=>Yii::app()->params['app_api_domain'].Yii::app()->createUrl('/api/area/counties',array('id'=>$hot_mode->id)),
					'parent'=>array(
							'name'=>$hot_mode->Area_Area_P->name,
							'value'=>$hot_mode->Area_Area_P->id,
							'spell'=>$hot_mode->Area_Area_P->nid,
					),
			);
		}
		$return['list_data']=array();
		foreach ($models as $model)
		{
			$group=strtoupper(substr($model->Items_area_id_m_Area_id->nid,0,1));
			$return['list_data'][$group][]=array(
					'name'=>$model->Items_area_id_m_Area_id->name,
					'value'=>$model->Items_area_id_m_Area_id->id,
					'group'=>$group,
					'spell'=>$model->Items_area_id_m_Area_id->nid,
					'link'=>Yii::app()->params['app_api_domain'].Yii::app()->createUrl('/api/area/set',array('id'=>$model->Items_area_id_m_Area_id->id)),
					'counties_link'=>Yii::app()->params['app_api_domain'].Yii::app()->createUrl('/api/area/counties',array('id'=>$model->Items_area_id_m_Area_id->id)),
					'parent'=>array(
							'name'=>$model->Items_area_id_m_Area_id->Area_Area_P->name,
							'value'=>$model->Items_area_id_m_Area_id->Area_Area_P->id,
							'spell'=>$model->Items_area_id_m_Area_id->Area_Area_P->nid,
					),
			);
		}
				
		$return['location']=array(
			'name'=>'GPS',
			'value'=>Yii::app()->cookie->getCookie(Yii::app()->params['gps']),
		);
		$return['orientation']=array(
				'name'=>'orientation',
				'value'=>Yii::app()->cookie->getCookie(Yii::app()->params['orientation']),
		);		
		$return['search']=array(
			'name'=>'search',
			'value'=>Yii::app()->params['app_api_domain'].Yii::app()->createUrl('/api/area/index',array('search'=>'')),
		);
		$this->send($return);
	}
	
	/**
	 * 获取城市的区
	 * @param unknown $id
	 */
	public function actionCounties($id)
	{
		$criteria=new CDbCriteria;
		$criteria->with=array(
				'Area_Area_P',
				'Area_Area',							
		);
		//排除nane=0的市
		$criteria->addCondition(" `t`.`name`!='0' ");
		//自己pid 不等于0 自己的父 等于0
		$criteria->addCondition('`t`.`pid` !=0 AND `Area_Area_P`.`pid`=0');
		
		$model=Area::model()->findByPk($id,$criteria);
		$return=array();
		if($model)
		{
			foreach ($model->Area_Area as $counties)
			{
				$return['list_data'][]=array(
					'name'=>$counties->name,
					'value'=>$counties->id,
					'link'=>Yii::app()->params['app_api_domain'].Yii::app()->createUrl('/api/area/set',array('id'=>$model->id)),
				);
			}
			$this->send($return);
		}
		
		$this->send_error(DATA_NULL);
	}

	/**
	 * 设置当前城市（县）
	 * @param unknown $id
	 */
	public function actionSet($id)
	{
		$criteria=new CDbCriteria;
		$criteria->with=array(
				'Area_Area_P',
				'Area_Area',
		);
		//排除nane=0的市
		$criteria->addCondition(" `t`.`name`!='0' ");
		//自己pid 不等于0 自己的父 等于0
		$criteria->addCondition('`t`.`pid` !=0 AND `Area_Area_P`.`pid`=0');
		//查找到当前的地址
		$criteria->addCondition('`t`.`id`=:id OR `Area_Area`.`id`=:id');
		$criteria->params[':id']=$id;
		$model=Area::model()->find($criteria);
		$return=array();
		if($model && isset($model->Area_Area[0],$model->Area_Area_P))
		{
			$district = ($id==$model->Area_Area[0]->id) ? true : false;
			$return['address_info']=array(
				'name'=>$district ? $model->Area_Area[0]->name:$model->name,
				'value'=>$district ? $model->Area_Area[0]->id:$model->id,
				'link'=>Yii::app()->params['app_api_domain'].Yii::app()->createUrl('/api/area/set',array('id'=>$model->id)),
				'city'=>array(
					'name'=>$model->name,
					'value'=>$model->id,
				),
				'province'=>array(
					'name'=>$model->Area_Area_P->name,
					'value'=>$model->Area_Area_P->id,
				),
				'district'=>array(
					'name'=>$district ? $model->Area_Area[0]->name : '',
					'value'=>$district ? $model->Area_Area[0]->id : '',
				),
			);
			if(! $district)
				$return['address_info']['counties_link']=Yii::app()->params['app_api_domain'].Yii::app()->createUrl('/api/area/counties',array('id'=>$model->id));

			$return['address_info']['address']=$return['address_info']['province']['name'].$return['address_info']['city']['name'].$return['address_info']['district']['name'];			
			$return['address_info']['location']=Area::getLocation($return['address_info']['address']);
			//设置城市或县
			Yii::app()->cookie->saveCookie(Yii::app()->params['orientation'], $return);
			if(isset(Yii::app()->api->id) && Yii::app()->api->id > 0)
				$this->log('设置定位城市:'.($district ? $model->Area_Area[0]->name:$model->name), ManageLog::user,ManageLog::update);
			$this->send($return);
		}
		
		$this->send_error(DATA_NULL);
	}
	
	/**
	 * 获取当前的设置的城市 如果有显示设置的城市 没有显示gps
	 */
	public function actionGet()
	{
		$return=Yii::app()->cookie->getCookie(Yii::app()->params['orientation']);
		if(! $return)
			$return=Yii::app()->cookie->getCookie(Yii::app()->params['gps']);
		
		if($return)
			$this->send($return);
				
		$this->send_error(DATA_NULL);
	}
	
	/**
	 * gps 定位 
	 */
	public function actionGps()
	{	
		if(isset($_POST['location']['lng'],$_POST['location']['lat']) && preg_match('/^[-]?(\d|([1-9]\d)|(1[0-7]\d)|(180))(\.\d*)?$/',$_POST['location']['lng']) && preg_match('/^[-]?(\d|([1-8]\d)|(90))(\.\d*)?$/',$_POST['location']['lat']))
		{
			$result=Area::getAddress($_POST['location']['lng'],$_POST['location']['lat']);
			if(! empty($result))
			{
				$criteria=new CDbCriteria;
				$criteria->with=array( 
						'Area_Area_P',
						'Area_Area',
				);
				//排除nane=0的市
				$criteria->addCondition(" `t`.`name`!='0' ");
				//自己pid 不等于0 自己的父 等于0
				$criteria->addCondition('`t`.`pid` !=0 AND `Area_Area_P`.`pid`=0');
				//查找到当前的地址
				$criteria->addSearchCondition('`t`.`name`', rtrim(trim($result['city']),'市'));

				$criteria->params[':province']='%'.strtr(trim($result['province']),array('%'=>'\%', '_'=>'\_', '\\'=>'\\\\')).'%';
				$criteria->params[':district']='%'.strtr(trim($result['district']),array('%'=>'\%', '_'=>'\_', '\\'=>'\\\\')).'%';
				
				$criteria->addCondition('`Area_Area_P`.`name` LIKE :province AND `Area_Area`.`name` LIKE :district');
				
				$model=Area::model()->find($criteria);
				if(! $model)
				{
					$criteria=new CDbCriteria;
					$criteria->with=array(
							'Area_Area_P',
							'Area_Area',
					);
					//排除nane=0的市
					$criteria->addCondition(" `t`.`name`!='0' ");
					//自己pid 不等于0 自己的父 等于0
					$criteria->addCondition('`t`.`pid` !=0 AND `Area_Area_P`.`pid`=0');
					//查找到当前的地址
					$criteria->addSearchCondition('`t`.`name`', rtrim(trim($result['city']),'市'));					
					$model=Area::model()->find($criteria);
				}
		
				if($model && isset($model->Area_Area[0],$model->Area_Area_P))
				{				
					$district = ( strpos($result['district'],$model->Area_Area[0]->name) === false ) ? false : true;							
					$return['address_info']=array(
							'name'=>$model->name,
							'value'=>$model->id,
							'link'=>Yii::app()->params['app_api_domain'].Yii::app()->createUrl('/api/area/set',array('id'=>$model->id)),
							'counties_link'=>Yii::app()->params['app_api_domain'].Yii::app()->createUrl('/api/area/counties',array('id'=>$model->id)),
							'city'=>array(
									'name'=>$model->name,
									'value'=>$model->id,
							),
							'province'=>array(
									'name'=>$model->Area_Area_P->name,
									'value'=>$model->Area_Area_P->id,
							),
							'district'=>array(
									'name'=>$district ? $model->Area_Area[0]->name : '',
									'value'=>$district ? $model->Area_Area[0]->id : '',
							),
					);
					$return['address_info']['address']=$return['address_info']['province']['name'].$return['address_info']['city']['name'].$return['address_info']['district']['name'];
					$return['address_info']['location']=Area::getLocation($return['address_info']['address']);
					
					$return['address_info']['location_accurate']=array(
							'location'=>$_POST['location']['lng'] .',' . $_POST['location']['lat'],
							'lng'=>$_POST['location']['lng'],
							'lat'=>$_POST['location']['lat'],
					);
					//gps 的 信息
					Yii::app()->cookie->saveCookie(Yii::app()->params['gps'], $return);
					//删除 设置的信息
					Yii::app()->cookie->unsetCookie(Yii::app()->params['orientation']);
					$this->send($return);
				}else
					$this->send_error_form(array('location_lng'=>array('定位失败')));
			}else
				$this->send_error_form(array('location_lng'=>array(Area::getAddress($_POST['location']['lng'],$_POST['location']['lat'],true))));		
		}
		$this->send_csrf();
	}
}