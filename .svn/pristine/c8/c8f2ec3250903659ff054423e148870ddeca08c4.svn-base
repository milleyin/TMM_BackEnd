<?php

/**
 * This is the model class for table "{{tags_select}}".
 *
 * The followings are the available columns in table '{{tags_select}}':
 * @property string $id
 * @property string $admin_id
 * @property string $tags_id
 * @property string $type_id
 * @property integer $status
 * @property string $add_time
 * @property string $up_time
 */
class TagsSelect extends CActiveRecord
{
	/**
	 * 解释字段 status 的含义
	 * @var array
	 */
	//public static $_status=array(-1=>'删除','禁用','正常');
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
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{tags_select}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('admin_id,tags_id, type_id', 'required'),
			//array( 'numerical', 'integerOnly'=>true),
			array('admin_id, tags_id, type_id', 'length', 'max'=>11),
			array('add_time, up_time', 'length', 'max'=>10),
			
			//array('','safe','on'=>'create,update'),
			//array('','unsafe','on'=>'create,update'),
			
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('search_time_type,search_start_time,search_end_time,id, admin_id, tags_id, type_id, add_time, up_time', 'safe', 'on'=>'search'),
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
				//选中的Tags
				'TagsSelect_Tags'=>array(self::HAS_ONE,'Tags',array('id'=>'tags_id')),
				//归属标签分类
				'TagsSelect_TagsType'=>array(self::BELONGS_TO,'TagsType','type_id'),
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
			'tags_id' => '标签',
			'type_id' => '分类',
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
			//$criteria->compare('status','<>-1');
			if($this->search_time_type != '' && isset($this->__search_time_type[$this->search_time_type]))
			{
				if($this->search_start_time !='' && $this->search_end_time !='')
					$criteria->addBetweenCondition($this->__search_time_type[$this->search_time_type],strtotime($this->search_start_time),strtotime($this->search_end_time)+3600*24-1);
				elseif($this->search_start_time !='' && $this->search_end_time =='')
					$criteria->compare($this->__search_time_type[$this->search_time_type],'>='.strtotime($this->search_start_time));
				elseif($this->search_start_time =='' && $this->search_end_time !='')
					$criteria->compare($this->__search_time_type[$this->search_time_type],'<='.strtotime($this->search_end_time)+3600*24-1);
			}			
			$criteria->compare('id',$this->id,true);
			$criteria->compare('admin_id',$this->admin_id,true);
			$criteria->compare('tags_id',$this->tags_id,true);
			$criteria->compare('type_id',$this->type_id,true);
			//$criteria->compare('status',$this->status);
			if($this->add_time != '')
				$criteria->addBetweenCondition('add_time',strtotime($this->add_time),(strtotime($this->add_time)+3600*24-1));
			if($this->up_time != '')
				$criteria->addBetweenCondition('up_time',strtotime($this->up_time),(strtotime($this->up_time)+3600*24-1));
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
	 * @return TagsSelect the static model class
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
	 * 获取分类的tags
	 * @param unknown $type_id
	 * @return multitype:NULL
	 */
	public static function get_type_select_tags($type_id)
	{
		$models=self::model()->findAll(array(
			'with'=>array(
					'TagsSelect_Tags'=>array(
							'condition'=>'TagsSelect_Tags.status=1',
							'select'=>'status',
					)
			),
			'condition'=>'t.type_id=:type_id',
			'params'=>array(':type_id'=>$type_id),
			'select'=>'tags_id',
		));
		$return=array();
		if($models)
		{
			foreach ($models as $model)
				$return[]=$model->tags_id;		
		}
		return $return;
	}
	
	/**
	 * 验证是否选中标签
	 */
	public static function checked($tags_id,$type_id)
	{
		if(self::model()->find('tags_id=:tags_id AND type_id=:type_id',array(':tags_id'=>$tags_id,':type_id'=>$type_id)))
			return true;		
		return false;
	}
	
	/**
	 * 过滤掉 之前选择的tags_id  
	 * @param array $tags_ids 需要过滤的tags_ids 
	 * @param unknown $type_id 标签分类
	 */
	public static function not_select_tags_type($tags_ids,$type_id)
	{
		$return=array();
		if(! is_array($tags_ids))
			$tags_ids=array($tags_ids);
		if(empty($tags_ids))
			return $return;
		$criteria=new CDbCriteria;
		$criteria->select='tags_id';
		$criteria->addInCondition('tags_id',$tags_ids);
		$criteria->addColumnCondition(array('type_id'=>$type_id));
		$models=self::model()->findAll($criteria);	
		$new_tags_ids=array();
		foreach ($models as $model)
			$new_tags_ids[]=$model->tags_id;				
		foreach ($tags_ids as $tag_id)
		{
			if(! in_array($tag_id, $new_tags_ids))
				$return[]=$tag_id;
		}
		return $return;
	}

	/**
	 * 保存选择中的tags_ids  
	 * @param unknown $tags_ids 需要保存的tags_ids 
	 * @param unknown $type_id 标签分类
	 */
	public static function select_tags_ids_save($tags_ids,$type_id)
	{
		$return=array();
		foreach ($tags_ids as $tag_id)
		{			
			$model=new TagsSelect;
			$model->admin_id=Yii::app()->admin->id;
			$model->tags_id=$tag_id;
			$model->type_id=$type_id;
			if($model->save())
				$return[]=true;
		}
		return count($tags_ids)==0?false:count($return)==count($tags_ids);
	}
	
	/**
	 * 删除选择分类的标签
	 * @param unknown $tags_ids
	 * @param unknown $type_id
	 * @return Ambigous <number, unknown>
	 */
	public static function select_tags_ids_delete($tags_ids,$type_id)
	{
		$criteria=new CDbCriteria;
		$criteria->addColumnCondition(array('type_id'=>$type_id));
		$criteria->addInCondition('tags_id',$tags_ids);
		return self::model()->deleteAll($criteria);
	}
	
	
}
