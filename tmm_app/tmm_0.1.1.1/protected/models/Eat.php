<?php

/**
 * This is the model class for table "{{eat}}".
 *
 * The followings are the available columns in table '{{eat}}':
 * @property string $id
 * @property string $c_id
 */
class Eat extends CActiveRecord
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
		return '{{eat}}';
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
			//array('','unsafe','on'=>'create,update'),
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
                //项目(吃)关联主表 一对一
                'Eat_Items'=>array(self::HAS_ONE,'Items','id'),
                //项目(吃)关联类型表 归属（多对一）
                'Eat_ItemsClassliy'=>array(self::BELONGS_TO,'ItemsClassliy','c_id'),
//                 //项目(吃)关联酒店服务表 一对多
//                 'Eat_ItemsWifi'=>array(self::HAS_MANY,'ItemsWifi','item_id'),
                //项目(吃)的票价
                'Eat_Fare'=>array(self::HAS_MANY,'Fare','item_id'),
                //项目(吃)关联图片表 一对多
                'Eat_ItemsImg'=>array(self::HAS_MANY,'ItemsImg','items_id'),
				//项目(吃)关联的标签        一对多
				'Eat_TagsElement'=>array(self::HAS_MANY,'TagsElement','element_id'),
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
				'Eat_ItemsClassliy',
                'Eat_Items'=>array(
                    'with'=>array(
						'Items_agent',
						'Items_StoreContent'=>array('with'=>array('Content_Store')),
						'Items_Store_Manager',
						'Items_area_id_p_Area_id'=>array('select'=>'name'),
						'Items_area_id_m_Area_id'=>array('select'=>'name'),
						'Items_area_id_c_Area_id'=>array('select'=>'name'),
                    )),
            );

			if($this->search_time_type != '' && isset($this->__search_time_type[$this->search_time_type]))
			{
				if($this->search_start_time !='' && $this->search_end_time !='')
					$criteria->addBetweenCondition('Eat_Items.'.$this->__search_time_type[$this->search_time_type],strtotime($this->search_start_time),strtotime($this->search_end_time)+3600*24-1);
				elseif($this->search_start_time !='' && $this->search_end_time =='')
					$criteria->compare('Eat_Items.'.$this->__search_time_type[$this->search_time_type],'>='.strtotime($this->search_start_time));
				elseif($this->search_start_time =='' && $this->search_end_time !='')
					$criteria->compare('Eat_Items.'.$this->__search_time_type[$this->search_time_type],'<='.strtotime($this->search_end_time)+3600*24-1);
			}
			$criteria->compare('Eat_Items.id',$this->id,true);
			$criteria->compare('Eat_ItemsClassliy.name',$this->c_id,true);
			
			$criteria->compare('Items_agent.phone',$this->Eat_Items->agent_id,true);
			$criteria->compare('Content_Store.phone',$this->Eat_Items->store_id,true);
			$criteria->compare('Items_Store_Manager.phone',$this->Eat_Items->manager_id,true);
			$criteria->compare('Eat_Items.name',$this->Eat_Items->name,true);
				//关联地址
				if(!! $model_p=Area::name($this->Eat_Items->area_id_p)){
					$model_m=Area::name($this->Eat_Items->area_id_m);
					if($model_m && $model_p->id != $model_m->pid)
						$this->Eat_Items->area_id_m='';
				}else
					$this->Eat_Items->area_id_m='';


			$criteria->compare('Items_area_id_p_Area_id.name',$this->Eat_Items->area_id_p,true);
			$criteria->compare('Items_area_id_m_Area_id.name',$this->Eat_Items->area_id_m,true);
			$criteria->compare('Items_area_id_c_Area_id.name',$this->Eat_Items->area_id_c,true);
			$criteria->compare('Eat_Items.address',$this->Eat_Items->address,true);
			$criteria->compare('Eat_Items.push',$this->Eat_Items->push,true);
			$criteria->compare('Eat_Items.push_orgainzer',$this->Eat_Items->push_orgainzer,true);
			$criteria->compare('Eat_Items.push_store',$this->Eat_Items->push_store,true);
			$criteria->compare('Eat_Items.push_agent',$this->Eat_Items->push_agent,true);
			$criteria->compare('Eat_Items.phone',$this->Eat_Items->phone,true);
			$criteria->compare('Eat_Items.weixin',$this->Eat_Items->weixin,true);
			$criteria->compare('Eat_Items.content',$this->Eat_Items->content,true);
			$criteria->compare('Eat_Items.audit',$this->Eat_Items->audit);

			$criteria->compare('Eat_Items.down',$this->Eat_Items->down,true);
			$criteria->compare('Eat_Items.start_work',$this->Eat_Items->start_work,true);
			$criteria->compare('Eat_Items.end_work',$this->Eat_Items->end_work,true);

			if($this->Eat_Items->pub_time != '')
				$criteria->addBetweenCondition('Eat_Items.pub_time',strtotime($this->Eat_Items->pub_time),(strtotime($this->Eat_Items->pub_time)+3600*24-1));
			if($this->Eat_Items->add_time != '')
				$criteria->addBetweenCondition('Eat_Items.add_time',strtotime($this->Eat_Items->add_time),(strtotime($this->Eat_Items->add_time)+3600*24-1));
			if($this->Eat_Items->up_time != '')
				$criteria->addBetweenCondition('Eat_Items.up_time',strtotime($this->Eat_Items->up_time),(strtotime($this->Eat_Items->up_time)+3600*24-1));
			$criteria->compare('Eat_Items.status',$this->Eat_Items->status);
			$criteria->compare('Eat_Items.lng',$this->Eat_Items->lng,true);
			$criteria->compare('Eat_Items.lat',$this->Eat_Items->lat,true);
			$criteria->compare('Eat_Items.free_status',$this->Eat_Items->free_status);
			$criteria->compare('Eat_Items.status','<>-1');
		}
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
					'pageSize'=>Yii::app()->params['admin_pageSize'],
			),
			'sort'=>array(
					'defaultOrder'=>'Eat_Items.add_time desc', //设置默认排序
					'attributes'=>array(
							'id',
							'Eat_Items.name'=>array(
									'desc'=>'Eat_Items.name desc',
							),
							'Eat_Items.agent_id'=>array(
									'desc'=>'Items_agent.phone desc',
							),
							'Eat_Items.store_id'=>array(
									'desc'=>'Content_Store.phone desc',
							),
							'Eat_Items.manager_id'=>array(
									'desc'=>'Items_Store_Manager.phone desc',
							),
							'Eat_Items.area_id_p'=>array(
									'desc'=>'Eat_Items.area_id_p desc',
							),
							'Eat_Items.area_id_m'=>array(
									'desc'=>'Eat_Items.area_id_m desc',
							),
							'Eat_Items.area_id_c'=>array(
									'desc'=>'Eat_Items.area_id_c desc',
							),
							'Eat_Items.down'=>array(
									'desc'=>'Eat_Items.down desc',
							),
							'Eat_Items.push'=>array(
									'desc'=>'Eat_Items.push desc',
							),
							'Eat_Items.pub_time'=>array(
									'desc'=>'Eat_Items.pub_time desc',
							),
							'Eat_Items.add_time'=>array(
									'desc'=>'Eat_Items.add_time desc',
							),
							'Eat_Items.audit'=>array(
									'desc'=>'Eat_Items.audit desc',
							),
							'Eat_Items.free_status'=>array(
									'desc'=>'Eat_Items.free_status desc',
							),
							'Eat_Items.status'=>array(
									'desc'=>'Eat_Items.status desc',
							),
					)
			),
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Eat the static model class
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
