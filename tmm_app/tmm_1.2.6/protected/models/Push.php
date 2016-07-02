<?php

/**	
 * 分成比率
 * This is the model class for table "{{push}}".
 *
 * The followings are the available columns in table '{{push}}':
 * @property string $id
 * @property string $manage_id
 * @property integer $manage_who
 * @property string $push_id
 * @property integer $push_element
 * @property double $push
 * @property double $push_orgainzer
 * @property double $push_store
 * @property double $push_agent
 * @property string $start_time
 * @property string $info
 * @property string $add_time
 * @property string $up_time
 * @property integer $status
 */
class Push extends CActiveRecord
{
	/*******************************操作角色**********************************/
	/**
	 * 管理员操作
	 * @var integer
	 */
	const admin=1;
	
	/*******************************设置元素**********************************/
	/**
	 * 被设置 项目
	 * @var integer
	 */
	const push_items=1;
	
	/*******************************我是分隔线**********************************/
	
	/*********************************操作角色***********************************/
	/**
	 * 解释字段 manage_who 的 模块名 用来获取当前登录ID
	 * @var array
	*/
	public static $_manage_modules=array(1=>'admin');
	/**
	 * 解释字段 manage_who 的含义
	 * @var array
	*/
	public static $_manage_who=array(1=>'管理员');
	
	/*******************************要审核的元素**********************************/
	/**
	 * 解释字段 push_element 的含义
	 * @var array
	*/
	public static $_push_element=array(1=>'项目');
	/**
	 * 记录状态 删除
	 * @var unknown
	 */
	const status_delete = -1;
	/**
	 * 记录状态 禁用
	 * @var unknown
	 */
	const status_disable = 0;
	/**
	 * 记录状态 正常
	 * @var unknown
	 */
	const status_start = 1;
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
	public static $_search_time_type=array('生效时间','创建时间','修改时间'); 
	/**
	 *	解释搜索类型时间 search_time_type 的字段搜索
	 * @var string
	 */
	public $__search_time_type=array('start_time','add_time','up_time'); 
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
	 * 辅助生效时间
	 * @var unknown
	 */
	public $_start_time;	
	/**
	 * 存储生效值
	 * @var unknown
	 */
	public static $_push_array;
	
	/**
	 * 显示生效值
	 * @var unknown
	 */
	public $executed_push;
	/**
	 * 显示生效值
	 * @var unknown
	 */
	public $executed_push_orgainzer;
	/**
	 * 显示生效值
	 * @var unknown
	 */
	public $executed_push_store;
	/**
	 * 显示生效值
	 * @var unknown
	 */
	public $executed_push_agent;
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{push}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			//array('manage_id, manage_who, push_id, push_element, start_time, info, add_time, up_time', 'required'),
			array('manage_who, push_element, status', 'numerical', 'integerOnly'=>true),
			array('push', 'numerical'),
			array('manage_id, push_id', 'length', 'max'=>11),
			array('start_time, add_time, up_time', 'length', 'max'=>10),
			
			//添加比率条件
			array('_start_time,push,push_orgainzer,push_store,push_agent', 'required','on'=>'create'),
			array('_start_time','type','dateFormat'=>'yyyy-mm-dd','type'=>'date','on'=>'create','message'=>'{attribute} 必须是日期'),
			array('_start_time,info,push,push_orgainzer,push_store,push_agent','safe','on'=>'create'),
			array('search_time_type,search_start_time,start_time,search_end_time,id, manage_id, manage_who, push_id, push_element, add_time, up_time, status','unsafe','on'=>'create'),
			array('push,push_orgainzer,push_store,push_agent','ext.Validator.Validator_push','on'=>'create'),	
			array('push,push_orgainzer,push_store,push_agent','push_count','on'=>'create'),
				
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.

			array('search_time_type,search_start_time,search_end_time,id, manage_id, manage_who, push_id, push_element, push, push_orgainzer,push_store,push_agent,start_time, info, add_time, up_time, status', 'safe', 'on'=>'search'),
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
				// 归属给项目
				'Push_Items'=>array(self::BELONGS_TO,'Items','push_id'),
				//管理员的操作日志
				'Push_Admin'=>array(self::BELONGS_TO,'Admin','manage_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => '主键',
			'manage_id' =>'操作人',// '操作人的id',
			'manage_who' =>'操作角色',//'操作的用户表（管理、代理商、商家、组织者等）',
			'push_id' => '元素ID',
			'push_element' =>'元素类型',//'比率归属的元素类型 （代理商、商家、组织者等）',
			'push' =>'平台分成',//'代理分成比例 % 最大为100',
			'push_orgainzer'=>'组织者分成',
			'push_store'=>'商家分成',
			'push_agent'=>'代理商分成',				
			'executed_push' =>'生效平台分成',//'代理分成比例 % 最大为100',
			'executed_push_orgainzer'=>'生效组织分成',
			'executed_push_store'=>'生效商家分成',
			'executed_push_agent'=>'生效代理分成',	
			'start_time' => '生效时间',		
			'info' => '备注',
			'add_time' => '创建时间',
			'up_time' => '更新时间',
			'status' => '状态',
			'search_time_type'=>'时间类型',
			'search_start_time'=>'开始时间',
			'search_end_time'=>'结束时间',
			'_start_time'=>'生效时间',
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

			$criteria->with = array(
				'Push_Items'=>array('with'=>array(
					'Items_ItemsClassliy'
				)),
				'Push_Admin'
			);

			$criteria->compare('t.status','<>-1');
			if($this->search_time_type != '' && isset($this->__search_time_type[$this->search_time_type]))
			{
				if($this->search_start_time !='' && $this->search_end_time !='')
					$criteria->addBetweenCondition('t.'.$this->__search_time_type[$this->search_time_type],strtotime($this->search_start_time),strtotime($this->search_end_time)+3600*24-1);
				elseif($this->search_start_time !='' && $this->search_end_time =='')
					$criteria->compare('t.'.$this->__search_time_type[$this->search_time_type],'>='.strtotime($this->search_start_time));
				elseif($this->search_start_time =='' && $this->search_end_time !='')
					$criteria->compare('t.'.$this->__search_time_type[$this->search_time_type],'<='.strtotime($this->search_end_time)+3600*24-1);
			}			
			$criteria->compare('t.id',$this->id,true);
			$criteria->compare('Push_Admin.name',$this->manage_id,true);
			$criteria->compare('t.manage_who',$this->manage_who);
			$criteria->compare('`Push_Items`.`name`',$this->Push_Items->name,true);
			$criteria->compare('`t`.`push_id`',$this->push_id,true);
			$criteria->compare('t.push_element',$this->push_element);
			$criteria->compare('t.push',$this->push);
			$criteria->compare('t.push_orgainzer',$this->push_orgainzer);
			$criteria->compare('t.push_store',$this->push_store);
			$criteria->compare('t.push_agent',$this->push_agent);
			$criteria->compare('Push_Items.c_id',$this->Push_Items->c_id);
				
			if($this->start_time != '')
				$criteria->addBetweenCondition('t.start_time',strtotime($this->start_time),(strtotime($this->start_time)+3600*24-1));
			$criteria->compare('t.info',$this->info,true);
			if($this->add_time != '')
				$criteria->addBetweenCondition('t.add_time',strtotime($this->add_time),(strtotime($this->add_time)+3600*24-1));
			if($this->up_time != '')
				$criteria->addBetweenCondition('t.up_time',strtotime($this->up_time),(strtotime($this->up_time)+3600*24-1));
			$criteria->compare('t.status',$this->status);
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
	 * @return Push the static model class
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
			if($this->isNewRecord){
				$this->up_time=$this->add_time=time();
				if(isset(self::$_manage_modules[$this->manage_who])){
					$modules=self::$_manage_modules[$this->manage_who];
					$this->manage_id=Yii::app()->$modules->id;
				}else
					return false;
			}else
				$this->up_time=time();			
			return true;
		}else
			return false;
	}
	
	/**
	 * 生效的比率
	 * @param unknown $id
	 * @param unknown $element
	 * @throws CHttpException
	 */
	public static function executed($id,$name,$push=null,$element=self::push_items)
	{
		if(isset(self::$_push_element[$element]))
		{
			if(isset(self::$_push_array[$element][$id][$name]))
				return self::$_push_array[$element][$id][$name];
			else
			{
				$name_array=array('push','push_orgainzer','push_store','push_agent');
				if(!! $model=self::push_condition($id,$element))
				{	
					foreach ($name_array as $value)
						self::$_push_array[$element][$id][$value]=$model->$value;
					return $model->$name;
				}elseif($push !== null)
					return $push;
				elseif(!! $model=Items::model()->findByPk($id,array('select'=>'push,push_orgainzer,push_store,push_agent')))
				{ 
					foreach ($name_array as $value)
						self::$_push_array[$element][$id][$value]=$model->$value;
					return $model->$name;
				}
			}
		}
		throw new CHttpException(404,'没有找到分成比率！');
	}
	
	/**
	 * 生效条件
	 * @param unknown $push_id
	 * @param unknown $push_element
	 * @return Ambigous <static, mixed, NULL, multitype:static , multitype:unknown Ambigous <static, NULL> , multitype:, multitype:unknown >
	 */
	public static function push_condition($push_id,$push_element)
	{
		$criteria=new CDbCriteria;
		$criteria->select='push,push_orgainzer,push_store,push_agent';
		$criteria->addColumnCondition(array(
				'push_id'=>$push_id,
				'push_element'=>$push_element,
				'status'=>self::status_start,
		));
		$criteria->compare('start_time','<='.time());
		$criteria->order='add_time desc';
		return self::model()->find($criteria);
	}
	
	/**
	 * 分成比例 之和验证
	 * @param unknown $attribute
	 */
	public function push_count($attribute)
	{
		if(($this->push+$this->push_orgainzer+$this->push_store+$this->push_agent) !=100)
			$this->addError($attribute,$this->getAttributeLabel($attribute).' 它们之和只能等于100%');
	}
}
