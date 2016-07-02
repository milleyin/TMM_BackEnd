<?php

/**
 * This is the model class for table "{{wifi}}".
 *
 * The followings are the available columns in table '{{wifi}}':
 * @property string $id
 * @property string $admin_id
 * @property string $name
 * @property string $info
 * @property string $icon
 * @property string $add_time
 * @property string $up_time
 * @property integer $status
 */
class Wifi extends CActiveRecord
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
		return '{{wifi}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name', 'required'),
			array('status', 'numerical', 'integerOnly'=>true),
			array('admin_id', 'length', 'max'=>11),
			array('name', 'length', 'max'=>20),
			array('info, icon', 'length', 'max'=>100),
			array('add_time, up_time', 'length', 'max'=>10),

			//创建、修改
			//array('name', 'required','on'=>'create,update,update_image'),
			array('name','unique'),//公共唯一
			array(
				'icon','file','allowEmpty'=>true,
				'types'=>'jpg,gif,png,svg', 'maxSize'=>1024*1024*2,
				'tooLarge'=>'图片超过2M,请重新上传', 'wrongType'=>'图片格式错误',
				'on'=>'create,update'
			),
			array('name,info,icon','safe','on'=>'create,update'),
			array('search_time_type,search_start_time,search_end_time,id, admin_id, add_time, up_time, status','unsafe','on'=>'create,update'),
				
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('search_time_type,search_start_time,search_end_time,id, admin_id, name, info, icon, add_time, up_time, status', 'safe', 'on'=>'search'),
			//运营商搜索
			array('search_time_type,search_start_time,search_end_time,id, name, info, icon, add_time, up_time, status', 'safe', 'on'=>'operatorSearch'),
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
				/**
				 * 归属创建管理员
				 */
				'Wifi_Admin'=>array(self::BELONGS_TO,'Admin','admin_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'admin_id' => '管理员',
			'name' => '名字',
			'info' => '说明',
			'icon' => '图标',
			'add_time' => '创建时间',
			'up_time' => '更新时间',
			'status' => '状态',
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
			
			$criteria->with=array('Wifi_Admin'=>array('select'=>'username,name'));
			$criteria->compare('t.status','<>-1');
			if($this->search_time_type != '' && isset($this->__search_time_type[$this->search_time_type]))
			{
				if($this->search_start_time !='' && $this->search_end_time !='')
					$criteria->addBetweenCondition('t.'.$this->__search_time_type[$this->search_time_type],strtotime($this->search_start_time),strtotime($this->search_end_time)+3600*24-1);
				elseif($this->search_start_time !='' && $this->search_end_time =='')
					$criteria->compare('t.'.$this->__search_time_type[$this->search_time_type],'>='.strtotime($this->search_start_time));
				elseif($this->search_start_time =='' && $this->search_end_time !='')
					$criteria->compare('t.'.$this->__search_time_type[$this->search_time_type],'<='. (strtotime($this->search_end_time)+3600*24-1));
			}			
			$criteria->compare('t.id',$this->id,true);
			$criteria->compare('Wifi_Admin.username',$this->admin_id,true);
			$criteria->compare('t.name',$this->name,true);
			$criteria->compare('t.info',$this->info,true);
			$criteria->compare('t.icon',$this->icon,true);
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
	 * 运营商搜索
	 * @param string $criteria
	 * @return CActiveDataProvider
	 */
	public function operatorSearch($criteria = '')
	{
		// @todo Please modify the following code to remove attributes that should not be searched.
		if($criteria ==='')
		{
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
			$criteria->compare('t.id',$this->id,true);
			$criteria->compare('t.name',$this->name,true);
			$criteria->compare('t.info',$this->info,true);
			$criteria->compare('t.icon',$this->icon,true);
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
	 * 项目住选择wifi 
	 * @return CActiveDataProvider
	 */
	public function search_wifi($is_unsnt=false)
	{
		if($is_unsnt)
			$this->unsetAttributes();  // 删除默认属性
		$criteria=new CDbCriteria;
				
		$criteria->with=array('Wifi_Admin'=>array('select'=>'username,name'));
		if($is_unsnt)
			$criteria->compare('t.status','=1');
		else
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
		$criteria->compare('Wifi_Admin.username',$this->admin_id,true);
		$criteria->compare('t.name',$this->name,true);
		$criteria->compare('t.info',$this->info,true);
		$criteria->compare('t.icon',$this->icon,true);
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
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Wifi the static model class
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
	 * 过滤提交的id
	 * @param string $ids
	 * @param string $array
	 * @return multitype:NULL |unknown
	 */
	public static  function filter_wifi($ids='',$array=true)
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
		$criteria->addColumnCondition(array('status'=>1));
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
