<?php

/**
 * This is the model class for table "{{tags}}".
 *
 * The followings are the available columns in table '{{tags}}':
 * @property string $id
 * @property string $admin_id
 * @property string $name
 * @property string $weight
 * @property integer $sort
 * @property integer $status
 * @property string $add_time
 * @property string $up_time
 */
class Tags extends CActiveRecord
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
	public static $_search_time_type=array('创建时间','更新时间'); 
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
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{tags}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name', 'required'),//公共必填
			array('sort, status', 'numerical', 'integerOnly'=>true),
			array('admin_id', 'length', 'max'=>11),
			array('name', 'length', 'max'=>20),
			array('weight, add_time, up_time', 'length', 'max'=>10),
			
			//创建、修改
			array('name', 'unique'),//公共唯一
			array('name,weight,sort','safe','on'=>'create,update'),
			array('search_time_type,search_start_time,search_end_time,id, admin_id, status, add_time, up_time','unsafe','on'=>'create,update'),
			
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('search_time_type,search_start_time,search_end_time,id, admin_id, name, weight, sort, status, add_time, up_time', 'safe', 'on'=>'search'),
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
				//归属创建人
				'Tags_Admin'=>array(self::BELONGS_TO,'Admin','admin_id'),
				//选择的标签
				'Tags_TagsSelect'=>array(self::HAS_ONE,'TagsSelect','tags_id'),
				//该分类是否选中了标签
				'Tags_TagsSelect_MANY'=>array(self::HAS_MANY,'TagsSelect','tags_id'),
				//角色选择的标签
				'Tags_TagsElement'=>array(self::HAS_ONE,'TagsElement','tags_id'),
				//角色选择的查所有标签
				'Tags_TagsElement_MANY'=>array(self::HAS_MANY,'TagsElement','tags_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'admin_id' => '创建人',
			'name' => '名字',
			'weight' => '权重',
			'sort' => '排序',
			'status' => '状态',
			'add_time' => '创建时间',
			'up_time' => '更新时间',
			'search_time_type'=>'时间类型',
			'search_start_time'=>'开始时间',
			'search_end_time'=>'结束时间',
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
			$criteria->compare('t.status','<>-1');
			$criteria->with=array('Tags_Admin'=>array('select'=>'username,name'));
			if($this->search_time_type != '' && isset($this->__search_time_type[$this->search_time_type]))
			{
				if($this->search_start_time !='' && $this->search_end_time !='')
					$criteria->addBetweenCondition('t.'.$this->__search_time_type[$this->search_time_type],strtotime($this->search_start_time),strtotime($this->search_end_time)+3600*24-1);
				elseif($this->search_start_time !='' && $this->search_end_time =='')
					$criteria->compare('t.'.$this->__search_time_type[$this->search_time_type],'>='.strtotime($this->search_start_time));
				elseif($this->search_start_time =='' && $this->search_end_time !='')
					$criteria->compare('t.'.$this->__search_time_type[$this->search_time_type],'<=' . (strtotime($this->search_end_time)+3600*24-1));
			}			
			$criteria->compare('t.id',$this->id,true);
			$criteria->compare('Tags_Admin.username',$this->admin_id,true);
			$criteria->compare('t.name',$this->name,true);
			$criteria->compare('t.weight',$this->weight,true);
			$criteria->compare('t.sort',$this->sort,true);
			$criteria->compare('t.status',$this->status);
			if($this->add_time != '')
				$criteria->addBetweenCondition('t.add_time',strtotime($this->add_time),(strtotime($this->add_time)+3600*24-1));
			if($this->up_time != '')
				$criteria->addBetweenCondition('t.up_time',strtotime($this->up_time),(strtotime($this->up_time)+3600*24-1));
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
	 * 选择task
	 * @param string $criteria
	 * @param string $id
	 * @return CActiveDataProvider
	 */
	public function select_tags($id)
	{
		$criteria=new CDbCriteria;
		$criteria->with=array(
				'Tags_TagsSelect_MANY'=>array('select'=>'type_id'),
				'Tags_Admin'=>array('select'=>'username,name'),
		);
		$criteria->compare('t.status','<>-1');

		if($this->search_time_type != '' && isset($this->__search_time_type[$this->search_time_type]))
		{
			if($this->search_start_time !='' && $this->search_end_time !='')
				$criteria->addBetweenCondition('t.'.$this->__search_time_type[$this->search_time_type],strtotime($this->search_start_time),strtotime($this->search_end_time));
			elseif($this->search_start_time !='' && $this->search_end_time =='')
			$criteria->compare('t.'.$this->__search_time_type[$this->search_time_type],'>='.strtotime($this->search_start_time));
			elseif($this->search_start_time =='' && $this->search_end_time !='')
			$criteria->compare('t.'.$this->__search_time_type[$this->search_time_type],'<='.strtotime($this->search_end_time));
		}
		$criteria->compare('t.id',$this->id,true);
		$criteria->compare('Tags_Admin.username',$this->admin_id,true);
		$criteria->compare('t.name',$this->name,true);
		$criteria->compare('t.weight',$this->weight,true);
		$criteria->compare('t.sort',$this->sort,true);
		if($this->add_time != '')
			$criteria->addBetweenCondition('t.add_time',strtotime($this->add_time),(strtotime($this->add_time)+3600*24-1));
		if($this->up_time != '')
			$criteria->addBetweenCondition('t.up_time',strtotime($this->up_time),(strtotime($this->up_time)+3600*24-1));
		$criteria->compare('t.status',$this->status);
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
	 * 选择task
	 * @param string $criteria
	 * @param string $id
	 * @return CActiveDataProvider
	 */
	public function select_tags_store($id)
	{
		$criteria=new CDbCriteria;
		$criteria->with=array(
			'Tags_TagsElement',
		//	'Tags_TagsElement_MANY'=>array('select'=>'element_id,element_type,tags_id'),
			'Tags_Admin'=>array('select'=>'username,name'),
		);
		$criteria->compare('t.status','<>-1');

		if($this->search_time_type != '' && isset($this->__search_time_type[$this->search_time_type]))
		{
			if($this->search_start_time !='' && $this->search_end_time !='')
				$criteria->addBetweenCondition('t.'.$this->__search_time_type[$this->search_time_type],strtotime($this->search_start_time),strtotime($this->search_end_time));
			elseif($this->search_start_time !='' && $this->search_end_time =='')
				$criteria->compare('t.'.$this->__search_time_type[$this->search_time_type],'>='.strtotime($this->search_start_time));
			elseif($this->search_start_time =='' && $this->search_end_time !='')
				$criteria->compare('t.'.$this->__search_time_type[$this->search_time_type],'<='.strtotime($this->search_end_time));
		}
		$criteria->compare('t.id',$this->id,true);
		$criteria->compare('Tags_Admin.username',$this->admin_id,true);
		$criteria->compare('t.name',$this->name,true);
		$criteria->compare('t.weight',$this->weight,true);
		$criteria->compare('t.sort',$this->sort,true);
		if($this->add_time != '')
			$criteria->addBetweenCondition('t.add_time',strtotime($this->add_time),(strtotime($this->add_time)+3600*24-1));
		if($this->up_time != '')
			$criteria->addBetweenCondition('t.up_time',strtotime($this->up_time),(strtotime($this->up_time)+3600*24-1));
		$criteria->compare('t.status',$this->status);
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
	 *  元素选择tags
	 * @param string $criteria
	 * @param string $id
	 * @return CActiveDataProvider
	 */
	public function select_tags_element($is_unsnt=false)
	{
		if($is_unsnt)
			$this->unsetAttributes();  // 删除默认属性
		$criteria=new CDbCriteria;
		$criteria->with=array(
				'Tags_Admin'=>array('select'=>'username,name'),
		);
		$criteria->compare('t.status','=1');	
		if($this->search_time_type != '' && isset($this->__search_time_type[$this->search_time_type]))
		{
			if($this->search_start_time !='' && $this->search_end_time !='')
				$criteria->addBetweenCondition('t.'.$this->__search_time_type[$this->search_time_type],strtotime($this->search_start_time),strtotime($this->search_end_time));
			elseif($this->search_start_time !='' && $this->search_end_time =='')
			$criteria->compare('t.'.$this->__search_time_type[$this->search_time_type],'>='.strtotime($this->search_start_time));
			elseif($this->search_start_time =='' && $this->search_end_time !='')
			$criteria->compare('t.'.$this->__search_time_type[$this->search_time_type],'<='.strtotime($this->search_end_time));
		}
		$criteria->compare('t.id',$this->id,true);
		$criteria->compare('Tags_Admin.username',$this->admin_id,true);
		$criteria->compare('t.name',$this->name,true);
		$criteria->compare('t.weight',$this->weight,true);
		$criteria->compare('t.sort',$this->sort,true);
		if($this->add_time != '')
			$criteria->addBetweenCondition('t.add_time',strtotime($this->add_time),(strtotime($this->add_time)+3600*24-1));
		if($this->up_time != '')
			$criteria->addBetweenCondition('t.up_time',strtotime($this->up_time),(strtotime($this->up_time)+3600*24-1));
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
	 * @return Tags the static model class
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
	 * 验证商家用户是否选中标签
	 */
	public static function checked_store($data,$store_id,$tags_id){
		foreach ($data as $v){
			if($v->element_type==TagsElement::tags_store_content && $v->element_id==$store_id && $v->tags_id==$tags_id)
				return true;
		}
		return false;
	}

    /**
     * 验证项目吃是否选中标签
     */
    public static function checked_eat($data,$element_id,$tags_id){
        foreach ($data as $v){
            if($v->element_type==TagsElement::tags_items_eat && $v->element_id==$element_id && $v->tags_id==$tags_id)
                return true;
        }
        return false;
    }

	/**
	 * 验证项目玩是否选中标签
	 */
	public static function checked_play($data,$element_id,$tags_id){
		foreach ($data as $v){
			if($v->element_type==TagsElement::tags_items_play && $v->element_id==$element_id && $v->tags_id==$tags_id)
				return true;
		}
		return false;
	}
    
    /**
     * 过滤 传过来的tags id $array 真返回数组 id 假 返回模型对象 $status=禁用的tags
     * @param unknown $ids
     * @param string $array
     * @return multitype:NULL |Ambigous <multitype:static , mixed, static, NULL, multitype:unknown Ambigous <static, NULL> , multitype:, multitype:unknown >
     */
    public static function filter_tags($ids,$array=true,$status=1)
    {
    	$return=array();
    	if(! is_array($ids))
    		$ids=array($ids);
    	if(empty($ids))
    		return $return;
    	$criteria=new CDbCriteria;
    	if($array)
    		$criteria->select='id';
    	$criteria->addInCondition('id',$ids); 	
    	$criteria->addColumnCondition(array('status'=>$status));
    	$models=self::model()->findAll($criteria);	
    	if($array)
    	{
    		foreach ($models as $model)
    			$return[]=$model->id;
    		return $return;
    	}else 
    		return $models;
    }
    
}
