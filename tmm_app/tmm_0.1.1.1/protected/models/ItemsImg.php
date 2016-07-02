<?php

/**
 * This is the model class for table "{{items_img}}".
 *
 * The followings are the available columns in table '{{items_img}}':
 * @property string $id
 * @property string $items_id
 * @property string $c_id
 * @property string $agent_id
 * @property string $store_id
 * @property string $img
 * @property string $litimg
 * @property integer $sort
 * @property string $title
 * @property string $alt
 * @property string $height
 * @property string $with
 * @property string $add_time
 * @property string $up_time
 * @property integer $status
 */
class ItemsImg extends CActiveRecord
{
	/**
	 * 解释字段 status 的含义
	 * @var array
	 */
	public static $_status=array(-1=>'删除','禁用','正常');
	/**
	 * 搜索的时间类型
	 * @var array
	 */
	public $search_time_type;
	/**
	 *	解释搜索类型时间的含义 search_time_type
	 * @var string
	 */
	public static $_search_time_type=array('add_time','up_time'); 
	/**
	 *	解释搜索类型时间 search_time_type 的字段搜索
	 * @var string
	 */
	public $__search_time_type=array('add_time','up_time'); 
	/**
	 * 搜索开始的时间
	 * @var string
	 */
	public $search_start_time;
	/**
	 * 搜索结束的时间
	 * @var string
	 */
	public $search_end_time;
	/**
	 * 上传缓存文件
	 * @var unknown
	 */
	public $tmp;
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{items_img}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
		//	array('items_id, c_id, agent_id, store_id, img, add_time, up_time', 'required'),
			array('sort, status', 'numerical', 'integerOnly'=>true),
			array('items_id, c_id, agent_id, store_id', 'length', 'max'=>11),
			array('img, litimg, title, alt', 'length', 'max'=>100),
			array('height, with', 'length', 'max'=>5),
			array('add_time, up_time', 'length', 'max'=>10),
			
			//array('','safe','on'=>'create,update'),
			//array('','unsafe','on'=>'create,update'),
			
			//批量上传图片
			array(
					'tmp','file','allowEmpty'=>true,
					'types'=>'jpg,png', 'maxSize'=>1024*1024*2,
					'tooLarge'=>'图片超过2M,请重新上传', 'wrongType'=>'图片格式错误',
					'on'=>'uploads',
			),
			array('search_time_type,search_start_time,search_end_time,id, items_id, c_id, tmp，agent_id, store_id, img, litimg, sort, title, alt, height, with, add_time, up_time, status', 'unsafe', 'on'=>'uploads'),
				
			//创建住
			array('tmp,search_time_type,search_start_time,search_end_time,id, items_id, c_id, agent_id, store_id, img, litimg, sort, title, alt, height, with, add_time, up_time, status', 'unsafe', 'on'=>'create_hotel'),

			//创建玩
			array('tmp,search_time_type,search_start_time,search_end_time,id, items_id, c_id, agent_id, store_id, img, litimg, sort, title, alt, height, with, add_time, up_time, status', 'unsafe', 'on'=>'create_play'),

            //创建吃
            array('tmp,search_time_type,search_start_time,search_end_time,id, items_id, c_id, agent_id, store_id, img, litimg, sort, title, alt, height, with, add_time, up_time, status', 'unsafe', 'on'=>'create_eat'),


            // The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('search_time_type,search_start_time,search_end_time,id, items_id, c_id, agent_id, store_id, img, litimg, sort, title, alt, height, with, add_time, up_time, status', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => '主键',
			'items_id' => '关联项目主表（items）主键id',
			'c_id' => '关联项目数据模型表（items_classliy）主键id',
			'agent_id' => '关联代理商用户表（agent）主键id',
			'store_id' => '关联商家用户表(store)主键id',
			'img' => '原上传图片',
			'litimg' => '上传图片缩略图',
			'sort' => '图片排序',
			'title' => '图片title',
			'alt' => '图片alt',
			'height' => '图片高',
			'with' => '图片宽',
			'add_time' => '创建时间',
			'up_time' => '更新时间',
			'status' => '记录状态',
			'search_time_type'=>'时间类型',
			'search_start_time'=>'开始时间',
			'search_end_time'=>'结束时间',
			'tmp' => '上传图片',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search($criteria='')
	{
		// @todo Please modify the following code to remove attributes that should not be searched.
		if($criteria ===''){
			$criteria=new CDbCriteria;
			$criteria->compare('status','<>-1');
			if($this->search_time_type != '' && isset($this->__search_time_type[$this->search_time_type]))
			{
				if($this->search_start_time !='' && $this->search_end_time !='')
					$criteria->addBetweenCondition($this->__search_time_type[$this->search_time_type],strtotime($this->search_start_time),strtotime($this->search_end_time));
				elseif($this->search_start_time !='' && $this->search_end_time =='')
					$criteria->compare($this->__search_time_type[$this->search_time_type],'>='.strtotime($this->search_start_time));
				elseif($this->search_start_time =='' && $this->search_end_time !='')
					$criteria->compare($this->__search_time_type[$this->search_time_type],'<='.strtotime($this->search_end_time));
			}			
			$criteria->compare('id',$this->id,true);
			$criteria->compare('items_id',$this->items_id,true);
			$criteria->compare('c_id',$this->c_id,true);
			$criteria->compare('agent_id',$this->agent_id,true);
			$criteria->compare('store_id',$this->store_id,true);
			$criteria->compare('img',$this->img,true);
			$criteria->compare('litimg',$this->litimg,true);
			$criteria->compare('sort',$this->sort);
			$criteria->compare('title',$this->title,true);
			$criteria->compare('alt',$this->alt,true);
			$criteria->compare('height',$this->height,true);
			$criteria->compare('with',$this->with,true);
			if($this->add_time != '')
				$criteria->addBetweenCondition('add_time',strtotime($this->add_time),(strtotime($this->add_time)+3600*24-1));
			if($this->up_time != '')
				$criteria->addBetweenCondition('up_time',strtotime($this->up_time),(strtotime($this->up_time)+3600*24-1));
			$criteria->compare('status',$this->status);
		}
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
					'pageSize'=>Yii::app()->params['admin_pageSize'],
			),
			'sort'=>array(
					'defaultOrder'=>'t.add_time desc', //设置默认排序
			),
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ItemsImg the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/**
	 * 保存之前的操作
	 * @see CActiveRecord::beforeSave()
	 */
	public function beforeSave(){
		if(parent::beforeSave()){		
			if($this->isNewRecord)
				$this->up_time=$this->add_time=time();
			else
				$this->up_time=time();			
			return true;
		}else
			return false;
	}
	
	/**
	 * 过滤 图片id $id='' 返回 全部图片 $array=true 返回数据id  否则 返回对象数据
	 * @param unknown $items_id
	 * @param string $ids
	 * @param string $array
	 * @return multitype:NULL |Ambigous <multitype:static , mixed, static, NULL, multitype:unknown Ambigous <static, NULL> , multitype:, multitype:unknown >
	 */
	public static function filter_id($items_id,$ids='',$array=true,$listData=array()){
		$criteria =new CDbCriteria;
		if($array)
			$criteria->select='id';
		if(! empty($ids)){
			if(! is_array($ids))
				$ids=array($ids);
			$criteria->addInCondition('id', $ids);
		}
		$models=self::model()->findAll($criteria);
		if($array){
			$return=array();
			foreach ($models as $model)
				$return[]=$model->id;
			return $return;
		}elseif($listData==array()) 
			return $models;
		else
			return CHtml::listData($models,key($listData), $listData[key($listData)]);
	}
	
	/**
	 * 显示多张图片调用
	 * @param unknown $models
	 * @return string
	 */
	public static function show_img($models)
	{
		$html='';
		foreach ($models as $model)
			$html .= Yii::app()->controller->show_img($model->img);
		
		return $html;
	}
	
}
