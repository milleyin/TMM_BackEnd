<?php

/**
 * This is the model class for table "{{tags_element}}".
 *
 * The followings are the available columns in table '{{tags_element}}':
 * @property string $id
 * @property string $select_id
 * @property string $select_type
 * @property string $element_id
 * @property string $element_type
 * @property string $tags_id
 * @property string $type_id
 * @property integer $sort
 * @property integer $status
 * @property string $add_time
 * @property string $up_time
 */
class TagsElement extends CActiveRecord
{
	/******************************归属元素**********************************/
	/**
	 * 用户选择标签分类 （tags_id=0，type_id=*）不能重复多个
	 * @var integer
	 */
	const tags_user=1;
	/**
	 * 商家(主)选择标签 （tags_id=*，type_id=0）不能重复多个
	 * @var integer
	 */
	const tags_store_content=2;
	/**
	 * 项目（吃）选择标签 （tags_id=*，type_id=0）不能重复多个
	 * @var integer
	 */
	const tags_items_eat=3;
	/**
	 * 项目（住）选择标签 （tags_id=*，type_id=0）不能重复多个
	 * @var integer
	 */
	const tags_items_hotel=4;
	/**
	 * 项目（玩）选择标签 （tags_id=*，type_id=0）不能重复多个
	 * @var integer
	 */
	const tags_items_play=5;
	/**
	 * 商品（点）选择标签（tags_id=*，type_id=0）不能重复多个 且默认附加项目上的标签（可删除）
	 * @var integer
	 */
	const tags_shops_dot=6;
	/**
	 * 商品（ 线 ）选择标签（tags_id=*，type_id=0）不能重复多个 且默认附加项目上的标签（可删除）
	 * @var integer
	 */
	const tags_shops_thrand=7;
	/**
	 * 商品（结伴游）创建时标签（选择多个点：点的标签 去重 或者线的标签）赋给商品（tags_id=*，type_id=0)
	 * @var integer
	 */
	const tags_shops_group=8;
	/**
	 * 商品（旅游活动）创建时标签（选择多个点：点的标签 去重 或者线的标签）赋给商品（tags_id=*，type_id=0)
	 * @var integer
	 */
	const tags_shops_actives=9;
	
	/**
	 * 模型索引类型值
	 * @var unknown
	 */
	public static $model_name=array(
			'Eat'=>self::tags_items_eat,
			'Hotel'=>self::tags_items_hotel,
			'Play'=>self::tags_items_play,
	);
	
	/*********************************操作者***********************************/
	/**
	 * 管理员
	 * @var integer
	 */
	const admin=1;
	/**
	 * 用户
	 * @var integer
	 */
	const user=2;
	/**
	 * 代理商
	 * @var integer
	 */
	const agent=3;
	/**
	 * 代理商
	 * @var integer
	 */
	const operator=3;
	
	/*******************************我是分隔线**********************************/	
	/**
	 * 解释字段 select_type 的 模块名 用来获取当前登录ID
	 * @var array
	 */
	public static $_select_modules=array(1=>'admin',2=>'api',3=>'operator');
	/**
	 * 解释字段 select_type 的含义
	 * @var array
	*/
	public static $_select_type=array(1=>'管理员',2=>'用户',3=>'运营商');
	
	/*******************************我是分隔线**********************************/
	/**
	 * 解释字段 element_type 的含义
	 * @var array
	 */
	public static $_element_type=array(
			1=>'用户',
			2=>'商家(主)',
			3=>'项目(吃)',
			4=>'项目(住)',
			5=>'项目(玩)',		
			6=>'商品(点)',
			7=>'商品(线)',
			8=>'商品(结伴游)',
			9=>'商品(活动)'
	);
	
	/*******************************我是分隔线**********************************/
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
	 * 用户选择的标签分类
	 * @var string
	 */
	public $user_select_tags_type;
	
	
	public static $element_select_tags;
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{tags_element}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			//array('select_id, select_type, element_id, element_type', 'required'),
			array('sort, status', 'numerical', 'integerOnly'=>true),
			array('select_id, select_type, element_id, element_type, tags_id, type_id', 'length', 'max'=>11),
			array('add_time, up_time', 'length', 'max'=>10),
			
			//用户选择分类标签
			array('user_select_tags_type', 'required','on'=>'user_tags_type'),
			array('user_select_tags_type','safe','on'=>'user_tags_type'),
			array('search_time_type,search_start_time,search_end_time,id, select_id, select_type, element_id, element_type, tags_id, type_id, sort, status, add_time, up_time', 'unsafe', 'on'=>'user_tags_type'),
					
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('search_time_type,search_start_time,search_end_time,id, select_id, select_type, element_id, element_type, tags_id, type_id, sort, status, add_time, up_time', 'safe', 'on'=>'search'),
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
				//选中的标签名
				'TagsElement_Tags'=>array(self::HAS_ONE,'Tags',array('id'=>'tags_id')),
				//归属选择的 标签分类 （目前只有用户选择）
				'TagsElement_TagsType'=>array(self::BELONGS_TO,'TagsType','type_id'),
				//归属给用户的 标签分类
				'TagsElement_User'=>array(self::BELONGS_TO,'User','element_id'),
				//归属给商家(主) 标签
				'TagsElement_StoreContent'=>array(self::BELONGS_TO,'StoreContent','element_id'),
				//归属给项目(住) 标签
				'TagsElement_Hotel'=>array(self::BELONGS_TO,'Hotel','element_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'select_id' => '选择人ID',
			'select_type' => '选择人类型',
			'element_id' => '元素ID',
			'element_type' => '元素类型',
			'tags_id' => '标签类型ID',
			'type_id' => '标签类型ID',
			'sort' => '排序',
			'status' => '状态',
			'add_time' => '创建时间',
			'up_time' => '更新时间',
			'search_time_type'=>'时间类型',
			'search_start_time'=>'开始时间',
			'search_end_time'=>'结束时间',
			'user_select_tags_type'=>'用户属性标签'
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
					$criteria->addBetweenCondition($this->__search_time_type[$this->search_time_type],strtotime($this->search_start_time),strtotime($this->search_end_time)+3600*24-1);
				elseif($this->search_start_time !='' && $this->search_end_time =='')
					$criteria->compare($this->__search_time_type[$this->search_time_type],'>='.strtotime($this->search_start_time));
				elseif($this->search_start_time =='' && $this->search_end_time !='')
					$criteria->compare($this->__search_time_type[$this->search_time_type],'<=' . (strtotime($this->search_end_time)+3600*24-1));
			}			
			$criteria->compare('id',$this->id,true);
			$criteria->compare('select_id',$this->select_id,true);
			$criteria->compare('select_type',$this->select_type,true);
			$criteria->compare('element_id',$this->element_id,true);
			$criteria->compare('element_type',$this->element_type,true);
			$criteria->compare('tags_id',$this->tags_id,true);
			$criteria->compare('type_id',$this->type_id,true);
			$criteria->compare('sort',$this->sort);
			$criteria->compare('status',$this->status);
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
	 * @return TagsElement the static model class
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
	 * 用户选择了的标签分类
	 */
	public static function user_select_tages_type($user_id,$type_name=false,$p_id=false)
	{
		$array=array(
				'condition'=>'`t`.`element_type`=:element_type AND `t`.`element_id`=:element_id AND `TagsElement_TagsType`.`status`=1 AND `TagsElement_TagsType`.`p_id`!=0 AND TagsElement_TagsType.is_user=:is_user',
				'params'=>array(
						':element_type'=>TagsElement::tags_user,
						':element_id'=>$user_id,
						':is_user'=>TagsType::yes_is_user,
				),
				'with'=>array('TagsElement_TagsType')
		);
		if($p_id)
			$array['with']['TagsElement_TagsType']=array(
					'with'=>array(	
						'TagsType_TagsType'
					),
					'condition'=>'`TagsType_TagsType`.`p_id`=0 AND `TagsType_TagsType`.`status`=1',
			);
		return self::model()->findAll($array);
	}

	/**
	 * 某个分类下用户选择了的标签分类
	 * @param $user_id 用户id
	 * @param $p_id 父分类id
	 * @return array|mixed|null
	 */
	public static function user_select_tags_type_son($user_id,$p_id)
	{
		$array=array(
			'condition'=>'`t`.`element_type`=:element_type AND `t`.`element_id`=:element_id AND `TagsElement_TagsType`.`p_id`=:p_id AND `TagsElement_TagsType`.`status`=1',
			'params'=>array(
				':element_type'=>TagsElement::tags_user,
				':element_id'=>$user_id,
				':p_id'=>$p_id,
			),
			'with'=>array(
				'TagsElement_TagsType',
			)
		);
		return self::model()->findAll($array);
	}
	
	/**
	 * 获取选择的标签
	 * @param unknown $element_type
	 * @param unknown $element_id
	 */
	public static function get_select_tags($element_type,$element_id)
	{	
		return self::model()->findAll(array(
						'with'=>array('TagsElement_Tags'=>array('select'=>'name')),
						'condition'=>'`t`.`element_type`=:element_type AND `t`.`element_id`=:element_id AND `TagsElement_Tags`.`status`=1',
						'params'=>array(':element_type'=>$element_type,':element_id'=>$element_id),
				));
	}
	
	/**
	 * 显示标签
	 * @param unknown $models
	 */
	public static function show_tags($models)
	{
		$html='';
		foreach ($models as $model)
		{
			$html .= '[<b>'.$model->TagsElement_Tags->name.'</b>] ';
		}
		 
		return $html;
	}
	
	/**
	 * 是否选中了标签
	 * @param unknown $models
	 * @param unknown $tags_id
	 * @param unknown $element_id
	 * @param unknown $element_type
	 * @return boolean
	 */
	public static function checked_element($tags_id,$element_id,$element_type){
		if(isset(self::$_element_type[$element_type]))
		{
			if(self::model()->find(array(
					'select'=>'id',
					'condition'=>'element_type=:element_type AND element_id=:element_id AND tags_id=:tags_id',
					'params'=>array(
							':element_type'=>$element_type,
							':element_id'=>$element_id,
							':tags_id'=>$tags_id,
					)))
				)
				return true;			
		}
		return false;
	}
	
	/**
	 * 没有选中的tags
	 * @param unknown $tags_ids
	 */
	public static function not_select_tags_element($tags_ids,$element_id,$element_type)
	{
		$return=array();
		if(isset(self::$_element_type[$element_type]))
		{
			if(! is_array($tags_ids))
				$tags_ids=array($tags_ids);		
			if(empty($tags_ids))
				return $return;
			$criteria=new CDbCriteria;
			$criteria->select='tags_id';
			$criteria->addInCondition('tags_id',$tags_ids);
			$criteria->addColumnCondition(array(
					'element_id'=>$element_id,
					'element_type'=>$element_type,
			));
			$models=self::model()->findAll($criteria);
			$new_tags_ids=array();
			foreach ($models as $model)
				$new_tags_ids[]=$model->tags_id;
			foreach ($tags_ids as $tag_id)
			{
				if(! in_array($tag_id, $new_tags_ids))
					$return[]=$tag_id;
			}	
		}
		return $return;
	}
	
	/**
	 * 选中需要保存的tags
	 * @param unknown $tags_ids
	 * @param unknown $element_id
	 * @param unknown $element_type
	 * @param string $select_type
	 * @throws CHttpException
	 * @return boolean
	 */
	public static function select_tags_ids_save($tags_ids,$element_id,$element_type,$select_type='')
	{
		if(! is_array($tags_ids))
			$tags_ids=array($tags_ids);
		if(empty($tags_ids))
			return true;
		if($select_type=='')
			$select_type=self::admin;
		if(! isset(self::$_select_modules[$select_type]))
			throw new CHttpException(404,'没有这样的角色！');
		else 
			$modules=self::$_select_modules[$select_type];
		$return=array();
		foreach ($tags_ids as $tag_id)
		{
			if(count($return) <= Yii::app()->params['tags']['shops']['select'])
			{
				$model=new TagsElement;
				$model->select_id=Yii::app()->$modules->id;
				$model->select_type=$select_type;
				$model->element_id=$element_id;
				$model->element_type	=$element_type;
				$model->tags_id=$tag_id;
				$return[] =$model->save();
			}else
				break;
		}
		self::shops_update_tags_ids(array_slice($tags_ids,0,Yii::app()->params['tags']['shops']['select']),$element_id,$element_type,'save');
		return true;
	}
	
	/**
	 *选择要删除的tags_ids
	 * @param unknown $tags_ids
	 * @param unknown $element_id
	 * @param unknown $element_type
	 */
	public static function select_tags_ids_delete($tags_ids,$element_id,$element_type)
	{
		if(! is_array($tags_ids))
			$tags_ids=array($tags_ids);
		if(empty($tags_ids))
			return true;
		$criteria=new CDbCriteria;
		$criteria->addColumnCondition(array(
				'element_id'=>$element_id,
				'element_type'=>$element_type,
		));
		$criteria->addInCondition('tags_id',$tags_ids);
		self::shops_update_tags_ids($tags_ids,$element_id,$element_type,'delete');
		return self::model()->deleteAll($criteria);
	}

	/**
	 * 保存商品标签
	 * @param $tags_ids
	 * @param $element_id
	 * @param $element_type
	 * @param string $type
	 * @return bool
	 */

	public static function shops_update_tags_ids($tags_ids,$element_id,$element_type,$type='save')
	{
		$element_type_array=array(
				TagsElement::tags_shops_dot,
				TagsElement::tags_shops_thrand,
				TagsElement::tags_shops_actives,
		);
		if(in_array($element_type, $element_type_array))
		{
			$shops_item =  Shops::model()->findByPk($element_id);
			if($type=='save')
			{
				$tags_ids=array_flip(array_flip($tags_ids));
				$tags_id =implode(',',$tags_ids);
				$sign = $shops_item->tags_ids==''?'':',';
				$shops_item->tags_ids = $shops_item->tags_ids.$sign.$tags_id;
				$shops_item->save(false);
			}
			elseif($type=='delete' && $shops_item->tags_ids !='')
			{
				$tags_ids=array_flip(array_flip($tags_ids));
				$arr_tags = explode(',',$shops_item->tags_ids);
				$tags_ids = array_diff($arr_tags,$tags_ids);
				$shops_item->tags_ids=implode(',',$tags_ids);
				$shops_item->save(false);			
			}
		}
		return true;
	}
	
	/**
	 * 查出所有的 选中的tags
	 * @param unknown $ele_id
	 * @param string $array
	 * @param string $attribute
	 * @return multitype:NULL |Ambigous <number, unknown>
	 */
	public static function select_tags_all($ele_id=array(),$array=true,$attribute='tags_id')
	{
		$criteria=new CDbCriteria;
		$criteria->select="`$attribute`";
		$criteria->distinct=true;//排除重复
		foreach ($ele_id as $value)
		{
			if(isset($value[0]) && isset($value[1]))
				$criteria->addColumnCondition(array('element_type'=>$value[0],'element_id'=>$value[1]), 'AND', 'OR');
		}
		if(! empty($ele_id))
			$models= self::model()->findAll($criteria);
		else 
			return array();
		if($array)
		{
			$return =array();
			foreach ($models as $model)
				$return[]=$model->$attribute;
			return $return;
		}else		
			return $models;	
	}
}
