<?php

/**
 * This is the model class for table "{{hotel}}".
 *
 * The followings are the available columns in table '{{hotel}}':
 * @property string $id
 * @property string $c_id
 */
class Hotel extends CActiveRecord
{
	/**
	 * 搜索的时间类型
	 * @var array
	 */
	public $search_time_type;
	/**
	 *	解释搜索类型时间的含义 search_time_type
	 * @var string
	 */
    public static $_search_time_type=array('发布时间','创建时间','更新时间');
    /**
     *	解释搜索类型时间 search_time_type 的字段搜索
     * @var string
     */
    public $__search_time_type=array('pub_time','add_time','up_time');
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
	 * 商品类型
	 * @var unknown
	 */
	public $fare;
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{hotel}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			//array('id, c_id', 'required'),
			//array('id, c_id', 'length', 'max'=>11),
			
			//array('','safe','on'=>'create,update'),
			array('search_time_type,search_start_time,search_end_time,id, c_id','unsafe','on'=>'create,update'),
			
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('search_time_type,search_start_time,search_end_time,id, c_id', 'safe', 'on'=>'search'),
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
				//项目(住)关联主表 一对一
				'Hotel_Items'=>array(self::HAS_ONE,'Items','id'),
				//项目(住)关联类型表 归属（多对一）
				'Hotel_ItemsClassliy'=>array(self::BELONGS_TO,'ItemsClassliy','c_id'),
				//项目(住)关联酒店服务表 一对多
				'Hotel_ItemsWifi'=>array(self::HAS_MANY,'ItemsWifi','item_id'),
				//项目(住)的票价         	一对多
				'Hotel_Fare'=>array(self::HAS_MANY,'Fare','item_id'),
				//项目(住)关联图片表 	一对多
				'Hotel_ItemsImg'=>array(self::HAS_MANY,'ItemsImg','items_id'),		
				//项目(住)关联的标签        一对多
				'Hotel_TagsElement'=>array(self::HAS_MANY,'TagsElement','element_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'c_id' => '类型',
			'search_time_type'=>'时间类型',
			'search_start_time'=>'开始时间',
			'search_end_time'=>'结束时间',
			'fare'=>'商品信息'
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
			
			$criteria->with=array(
					'Hotel_Items'=>array(
							'with'=>array(
									'Items_StoreContent'=>array('with'=>array('Content_Store')),
									'Items_Store_Manager',
									'Items_agent',
									'Items_area_id_p_Area_id'=>array('select'=>'name'),
									'Items_area_id_m_Area_id'=>array('select'=>'name'),
									'Items_area_id_c_Area_id'=>array('select'=>'name'),
									//'Items_ItemsClassliy',
							)),
					'Hotel_ItemsClassliy',
			);
			
			if($this->search_time_type != '' && isset($this->__search_time_type[$this->search_time_type]))
			{
				if($this->search_start_time !='' && $this->search_end_time !='')
					$criteria->addBetweenCondition('Hotel_Items.'.$this->__search_time_type[$this->search_time_type],strtotime($this->search_start_time),strtotime($this->search_end_time)+3600*24-1);
				elseif($this->search_start_time !='' && $this->search_end_time =='')
					$criteria->compare('Hotel_Items.'.$this->__search_time_type[$this->search_time_type],'>='.strtotime($this->search_start_time));
				elseif($this->search_start_time =='' && $this->search_end_time !='')
					$criteria->compare('Hotel_Items.'.$this->__search_time_type[$this->search_time_type],'<='.strtotime($this->search_end_time)+3600*24-1);
			}			
			$criteria->compare('id',$this->id,true);
			$criteria->compare('Hotel_ItemsClassliy.name',$this->c_id,true);
			
			$criteria->compare('Items_agent.phone',$this->Hotel_Items->agent_id,true);
			$criteria->compare('Content_Store.phone',$this->Hotel_Items->store_id,true);
			$criteria->compare('Items_Store_Manager.phone',$this->Hotel_Items->manager_id,true);
			$criteria->compare('Hotel_Items.name',$this->Hotel_Items->name,true);
			//关联地址
			if(!! $model_p=Area::name($this->Hotel_Items->area_id_p)){
				$model_m=Area::name($this->Hotel_Items->area_id_m);
				if($model_m && $model_p->id != $model_m->pid)
					$this->Hotel_Items->area_id_m='';
			}else
				$this->Hotel_Items->area_id_m='';
			$criteria->compare('Items_area_id_p_Area_id.name',$this->Hotel_Items->area_id_p,true);
			$criteria->compare('Items_area_id_m_Area_id.name',$this->Hotel_Items->area_id_m,true);
			$criteria->compare('Items_area_id_c_Area_id.name',$this->Hotel_Items->area_id_c,true);
			
			$criteria->compare('Hotel_Items.address',$this->Hotel_Items->address,true);
			$criteria->compare('Hotel_Items.push',$this->Hotel_Items->push,true);
			$criteria->compare('Hotel_Items.push_orgainzer',$this->Hotel_Items->push_orgainzer,true);
			$criteria->compare('Hotel_Items.push_store',$this->Hotel_Items->push_store,true);
			$criteria->compare('Hotel_Items.push_agent',$this->Hotel_Items->push_agent,true);
			$criteria->compare('Hotel_Items.phone',$this->Hotel_Items->phone,true);
			$criteria->compare('Hotel_Items.weixin',$this->Hotel_Items->weixin,true);
			$criteria->compare('Hotel_Items.content',$this->Hotel_Items->content,true);
			$criteria->compare('Hotel_Items.audit',$this->Hotel_Items->audit);
			$criteria->compare('Hotel_Items.down',$this->Hotel_Items->down,true);
			$criteria->compare('Hotel_Items.start_work',$this->Hotel_Items->start_work,true);
			$criteria->compare('Hotel_Items.end_work',$this->Hotel_Items->end_work,true);
			if($this->Hotel_Items->pub_time != '')
				$criteria->addBetweenCondition('Hotel_Items.pub_time',strtotime($this->Hotel_Items->pub_time),(strtotime($this->Hotel_Items->pub_time)+3600*24-1));
			if($this->Hotel_Items->add_time != '')
				$criteria->addBetweenCondition('Hotel_Items.add_time',strtotime($this->Hotel_Items->add_time),(strtotime($this->Hotel_Items->add_time)+3600*24-1));
			if($this->Hotel_Items->up_time != '')
				$criteria->addBetweenCondition('Hotel_Items.up_time',strtotime($this->Hotel_Items->up_time),(strtotime($this->Hotel_Items->up_time)+3600*24-1));
			$criteria->compare('Hotel_Items.status',$this->Hotel_Items->status);
			$criteria->compare('Hotel_Items.free_status',$this->Hotel_Items->free_status);
			$criteria->compare('Hotel_Items.lng',$this->Hotel_Items->lng,true);
			$criteria->compare('Hotel_Items.lat',$this->Hotel_Items->lat,true);
			$criteria->compare('Hotel_Items.status','<>-1');
		}
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
					'pageSize'=>Yii::app()->params['admin_pageSize'],
			),
			'sort'=>array(
					'defaultOrder'=>'Hotel_Items.add_time desc', //设置默认排序
					'attributes'=>array(
							'id',
							'Hotel_Items.name'=>array(
									'desc'=>'Hotel_Items.name desc',
							),
							'Hotel_Items.agent_id'=>array(
									'desc'=>'Items_agent.phone desc',
							),
							'Hotel_Items.store_id'=>array(
									'desc'=>'Content_Store.phone desc',
							),
							'Hotel_Items.manager_id'=>array(
									'desc'=>'Items_Store_Manager.phone desc',
							),
							'Hotel_Items.area_id_p'=>array(
									'desc'=>'Hotel_Items.area_id_p desc',
							),
							'Hotel_Items.area_id_m'=>array(
									'desc'=>'Hotel_Items.area_id_m desc',
							),
							'Hotel_Items.area_id_c'=>array(
									'desc'=>'Hotel_Items.area_id_c desc',
							),
							'Hotel_Items.down'=>array(
									'desc'=>'Hotel_Items.down desc',
							),
							'Hotel_Items.push'=>array(
									'desc'=>'Hotel_Items.push desc',
							),
							'Hotel_Items.pub_time'=>array(
									'desc'=>'Hotel_Items.pub_time desc',
							),
							'Hotel_Items.add_time'=>array(
									'desc'=>'Hotel_Items.add_time desc',
							),
							'Hotel_Items.audit'=>array(
									'desc'=>'Hotel_Items.audit desc',
							),
							'Hotel_Items.free_status'=>array(
									'desc'=>'Hotel_Items.free_status desc',
							),
							'Hotel_Items.status'=>array(
									'desc'=>'Hotel_Items.status desc',
							),
					)
			),
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Hotel the static model class
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
			return true;
		}else
			return false;
	}
}
