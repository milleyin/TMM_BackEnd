<?php

/**
 * This is the model class for table "{{refund}}".
 *
 * The followings are the available columns in table '{{refund}}':
 * @property string $id
 * @property string $admin_id
 * @property string $reason
 * @property integer $is_organizer
 * @property double $push
 * @property double $user_push
 * @property double $push_orgainzer
 * @property double $push_store
 * @property double $push_agent
 * @property string $count
 * @property string $add_time
 * @property string $up_time
 * @property integer $status
 */
class Refund extends CActiveRecord
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
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{refund}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('admin_id, add_time, up_time', 'required'),
			array('is_organizer, status', 'numerical', 'integerOnly'=>true),
			array('push, user_push, push_orgainzer, push_store, push_agent', 'numerical'),
			array('admin_id, count', 'length', 'max'=>11),
			array('reason', 'length', 'max'=>200),
			array('add_time, up_time', 'length', 'max'=>10),
			
			//array('','safe','on'=>'create,update'),
			//array('','unsafe','on'=>'create,update'),
			
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('search_time_type,search_start_time,search_end_time,id, admin_id, reason, is_organizer, push, user_push, push_orgainzer, push_store, push_agent, count, add_time, up_time, status', 'safe', 'on'=>'search'),
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
			'admin_id' => '管理员的id',
			'reason' => '退款原因',
			'is_organizer' => '是否有组织者',
			'push' => '平台对项目的抽成 %',
			'user_push' => '用户退款比例 %',
			'push_orgainzer' => '组织者对项目的抽成 %',
			'push_store' => '商家对项目的抽成 %',
			'push_agent' => '代理商平台对项目的抽成 %',
			'count' => '使用次数',
			'add_time' => '创建时间',
			'up_time' => '更新时间',
			'status' => '记录状态1正常0禁用-1删除',
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
			$criteria->compare('admin_id',$this->admin_id,true);
			$criteria->compare('reason',$this->reason,true);
			$criteria->compare('is_organizer',$this->is_organizer);
			$criteria->compare('push',$this->push);
			$criteria->compare('user_push',$this->user_push);
			$criteria->compare('push_orgainzer',$this->push_orgainzer);
			$criteria->compare('push_store',$this->push_store);
			$criteria->compare('push_agent',$this->push_agent);
			$criteria->compare('count',$this->count,true);
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
	 * @return Refund the static model class
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
}
