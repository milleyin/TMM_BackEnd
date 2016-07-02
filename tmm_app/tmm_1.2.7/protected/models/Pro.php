<?php

/**
 * This is the model class for table "{{pro}}".
 *
 * The followings are the available columns in table '{{pro}}':
 * @property string $id
 * @property string $shops_id
 * @property string $agent_id
 * @property string $store_id
 * @property string $shops_c_id
 * @property string $c_id
 * @property integer $sort
 * @property integer $day_sort
 * @property integer $half_sort
 * @property string $items_id
 * @property string $dot_id
 * @property string $thrand_id
 * @property string $info
 * @property string $add_time
 * @property string $up_time
 * @property integer $audit
 * @property integer $status
 */
class Pro extends CActiveRecord
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
	 * 距离
	 * @var unknown
	 */
	public $distance;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{pro}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			//array('shops_id, agent_id, store_id, shops_c_id, c_id, items_id, info', 'required'),
			array('items_id, dot_id, sort, day_sort, half_sort, audit, status', 'numerical', 'integerOnly'=>true),
			array('shops_id, agent_id, store_id, shops_c_id, c_id, items_id, dot_id, thrand_id', 'length', 'max'=>11),
			array('add_time, up_time', 'length', 'max'=>10),
			
			//创建点 修改点
			array('items_id', 'required','on'=>'create_dot,update_dot'),		
			array('items_id','safe','on'=>'create_dot,update_dot'),
			array('items_id', 'items_id', 'on'=>'create_dot,update_dot'),
			array('search_time_type,search_start_time,search_end_time,id, shops_id, agent_id, store_id, shops_c_id, c_id, sort, day_sort, half_sort, dot_id, thrand_id, info, add_time, up_time, audit, status', 'unsafe', 'on'=>'create_dot,update_dot'),
				
			//包装点
			array('id,info', 'required','on'=>'pack_dot'),
			array('id,info', 'safe','on'=>'pack_dot'),
			array('search_time_type,search_start_time,search_end_time,items_id, shops_id, agent_id, store_id, shops_c_id, c_id, sort, day_sort, half_sort, dot_id, thrand_id, add_time, up_time, audit, status', 'unsafe', 'on'=>'pack_dot'),

			//包装线
			array('id,info', 'required','on'=>'pack_thrand'),
			array('id,info', 'safe','on'=>'pack_thrand'),
			array('search_time_type,search_start_time,search_end_time,items_id, shops_id, agent_id, store_id, shops_c_id, c_id, sort, day_sort, half_sort, dot_id, thrand_id, add_time, up_time, audit, status', 'unsafe', 'on'=>'pack_thrand'),

			//包装结伴游
			array('id,info', 'required','on'=>'pack_group'),
			array('id,info', 'safe','on'=>'pack_group'),
			array('search_time_type,search_start_time,search_end_time,items_id, shops_id, agent_id, store_id, shops_c_id, c_id, sort, day_sort, half_sort, dot_id, thrand_id, add_time, up_time, audit, status', 'unsafe', 'on'=>'pack_group'),

			//创建线路(线) 修改			
// 			array('sort, day_sort, half_sort, items_id, dot_id', 'required', 'on'=>'create_thrand,update_thrand,create_actives,update_actives'),
// 			array('sort, day_sort, half_sort, items_id, dot_id', 'safe','on'=>'create_thrand,update_thrand,create_actives,update_actives'),
// 			array('search_time_type,search_start_time,search_end_time,id, shops_id, agent_id, store_id, shops_c_id, c_id, thrand_id, info, add_time, up_time, audit, status', 'unsafe', 'on'=>'create_thrand,update_thrand,create_actives,update_actives'),
				
			array('sort, day_sort, half_sort, items_id, dot_id', 'required', 'on'=>'create_thrand, update_thrand'),
			array('sort, day_sort, half_sort, items_id, dot_id', 'safe', 'on'=>'create_thrand, update_thrand'),
			array('dot_id', 'validatorDot_id', 'on'=>'create_thrand, update_thrand'),
			array('search_time_type,search_start_time,search_end_time,id, shops_id, agent_id, store_id, shops_c_id, c_id, thrand_id, info, add_time, up_time, audit, status', 'unsafe', 'on'=>'create_thrand, update_thrand'),
			
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('search_time_type,search_start_time,search_end_time,id, shops_id, agent_id, store_id, shops_c_id, c_id, sort, day_sort, half_sort, items_id, dot_id, thrand_id, info, add_time, up_time, audit, status', 'safe', 'on'=>'search'),
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
				//选中的项目
				'Pro_Items'=>array(self::HAS_ONE,'Items',array('id'=>'items_id')),
				//结伴游选中的点
				'Pro_Group_Dot'=>array(self::BELONGS_TO,'Dot','dot_id'),
				//线选中的点
				'Pro_Thrand_Dot'=>array(self::HAS_ONE,'Dot',array('id'=>'dot_id')),
				//结伴游选中的线
				'Pro_Group_Thrand'=>array(self::BELONGS_TO,'Thrand','thrand_id'),
				//选中的价格
				'Pro_ProFare'=>array(self::HAS_MANY,'ProFare','pro_id'),
				//活动选中的线
				'Pro_Actives_Thrand'=>array(self::BELONGS_TO,'Thrand','thrand_id'),
				//活动选中的点
				'Pro_Actives_Dot'=>array(self::BELONGS_TO,'Dot','dot_id'),

				//连接点中项目的价格
				'Pro_Fare'=>array(self::HAS_MANY,'Fare',array('items_id'=>'item_id')),

				'Pro_ItemsClassliy'=>array(self::BELONGS_TO,'ItemsClassliy','c_id'),
				'Pro_Shops'=>array(self::HAS_ONE,'Shops',array('id'=>'shops_id')),
				'Pro_ShopsClassliy'=>array(self::BELONGS_TO,'ShopsClassliy','shops_c_id'),

				//管理活动表
				'Pro_Actives'=>array(self::HAS_ONE,'Actives',array('id'=>'shops_id')),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => '自增ID',
			'shops_id' => '商品id',
			'agent_id' => '关联代理商用户表（agent）主键id',
			'store_id' => '关联商家用户表(store)主键id',
			'shops_c_id' => '关联项目数据模型表（shops_classliy）主键id',
			'c_id' => '关联项目数据模型表（items_classliy）主键id',
			'sort' => '点排序',
			'day_sort' => '区分天(0.5)',
			'half_sort' => '排序',
			'items_id' => '添加项目',
			'dot_id' => '当前项目关联点id',
			'thrand_id' => '当前项目关联线id',
			'info' => '项目简介',
			'add_time' => '创建时间',
			'up_time' => '更新时间',
			'audit' => '审核状态-1不通过 0 未审核 1 通过',
			'status' => '状态0禁用1启用',
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
			$criteria->compare('shops_id',$this->shops_id,true);
			$criteria->compare('agent_id',$this->agent_id,true);
			$criteria->compare('store_id',$this->store_id,true);
			$criteria->compare('shops_c_id',$this->shops_c_id,true);
			$criteria->compare('c_id',$this->c_id,true);
			$criteria->compare('sort',$this->sort);
			$criteria->compare('day_sort',$this->day_sort);
			$criteria->compare('half_sort',$this->half_sort);
			$criteria->compare('items_id',$this->items_id,true);
			$criteria->compare('dot_id',$this->dot_id,true);
			$criteria->compare('thrand_id',$this->thrand_id,true);
			$criteria->compare('info',$this->info,true);
			if($this->add_time != '')
				$criteria->addBetweenCondition('add_time',strtotime($this->add_time),(strtotime($this->add_time)+3600*24-1));
			if($this->up_time != '')
				$criteria->addBetweenCondition('up_time',strtotime($this->up_time),(strtotime($this->up_time)+3600*24-1));
			$criteria->compare('audit',$this->audit);
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
	 * @return Pro the static model class
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
	 * 过滤 id
	 * @param string $ids
	 * @param string $array
	 * @return multitype:NULL |unknown
	 */
	public static function filter_pro($ids='',$shops_id,$array=true,$order='')
	{
		$criteria=new CDbCriteria;
		if($array)
			$criteria->select='id';
		if(! empty($ids))
		{
			if(! is_array($ids))
				$ids=array($ids);
			$criteria->addInCondition('id',$ids);

			if(!$order) {
				$i=0;
				foreach ($ids as $id)
				{
					$params[]=':id_'.$i;
					$criteria->params[':id_'.$i++]=$id;
				}

				$criteria->order = 'field(`id`,' . implode(',', $params) . ')';

			}else{
				$criteria->addColumnCondition(array(
					't.half_sort'=>0,
					't.sort'=>0,
				));
				$criteria->order=$order;
			}
		}

		$criteria->addColumnCondition(array('shops_id'=>$shops_id));
	//	print_r($criteria);exit;
		$models=self::model()->findAll($criteria);

		if($array)
		{
			$return=array();
			foreach ($models as $model)
				$return[]=$model->id;
			return $return;
		}else
			return $models;
	}

	/** 拼接显示信息
	 * @param int $day
	 * @return string
	 */
	public static function item_swithc($day=1){
		$wu = ($day%2==1)?'上':'下'; //计算上下午
		$day_num = ceil($day/2);     //计算第几天
		return '第'.self::num_han($day_num).'天'.$wu.'午';
	}

	/**
	 * 数字替换汉字 11 转成 一一
	 * @param int $num
	 * @return string
	 */

	public static function num_han($num=1)
	{
		if($num>=100)
			return $num;

		$zi = array('零','一','二','三','四','五','六','七','八','九','十');
		if( $num>10 || $num <0 || !is_numeric($num) ){
			if($num>10){
				$str = (string)$num;
				$nu = '';
				for($i=0,$len=strlen($str);$i<$len;$i++){
					$j = $i==0?$zi[10]:'';
					if($str{$i} != 0)
						$nu .= self::num_han($str{$i}).$j;
				}
				return $nu;
			}
			else
				return $num;
		}
		else
			return $zi[$num];
	}

	/**
	 * 线路（线，结伴游）详情显示
	 * @param $model
	 * @param $item
	 * @return array
	 */
	public static function circuit_info($model,$item)
	{
		if(!$item || !$model)
			return array();
		$data_array = array();
		$info_array = array();
		foreach ($model as $value)
		{
			$data_array[$value->day_sort][$value->half_sort][$value->$item->Dot_Shops->id][$value->sort] = $value;
			$info_array['dot_data'][$value->$item->Dot_Shops->id] = $value->$item->Dot_Shops;
			if ($value->half_sort==0 && $value->sort==0)
				$info_array[$value->day_sort] = array('info'=>$value->info, 'data'=>$value);				
		}
		return array('data_arr'=>$data_array,'info_arr'=>$info_array);
	}
	
	/**
	 * 获取当项目下线了 的日程
	 */
	public static function get_day_info($day_sort,$shops_id)
	{
		$model=self::model()->find('`day_sort`=:day_sort AND `shops_id`=:shops_id AND `half_sort`=0 AND `sort`=0',array(
				':day_sort'=>$day_sort,
				':shops_id'=>$shops_id,
		));
		if($model)
			return $model->info;
		return null;
	}

	/**
	 * 获取 当前商品 共 多少天
	 * @param $shops_id
	 * @return float|null
	 */
	public static function get_day_num($shops_id){
		$model = self::model()->find(array(
			'condition'=>'shops_id=:shops_id',
			'params'=>array(':shops_id'=>$shops_id),
			'order'=>'day_sort desc')
		);
		if($model)
			return ceil($model->day_sort/2);
		return 1;
	}

	/**
	 * 验证项目
	 */
	public function items_id()
	{
		if ( !$this->hasErrors('items_id'))
		{
			$criteria = new CDbCriteria;
			$criteria->addColumnCondition(array(
					'audit'=>Items::audit_pass,
					'status'=>Items::status_online,
			));
			if ( !!$model = Items::model()->findByPk($this->items_id, $criteria))
			{
				$this->agent_id = $model->agent_id;
				$this->store_id = $model->store_id;
				$this->c_id = $model->c_id;
			}
			else
				$this->addError('items_id', '选择项目不是有效值');
		}
	}
	
	/**
	 * 验证景点 项目 是不是有效的
	 */
	public function validatorDot_id()
	{
		if ( !$this->hasErrors())
		{
			$criteria = new CDbCriteria;
			$criteria->select = 'id';
			$criteria->with = array(
				'Pro_Shops'=>array('select'=>'id'),
				'Pro_Items'=>array('select'=>'agent_id, store_id, store_id')
			);
			$criteria->addColumnCondition(array(
					'`t`.`shops_c_id`'=>Shops::shops_dot,
					'`Pro_Shops`.`audit`'=>Shops::audit_pass,
					'`Pro_Shops`.`status`'=>Shops::status_online,
					'`Pro_Items`.`audit`'=>Items::audit_pass,
					'`Pro_Items`.`status`'=>Items::status_online,
					'`t`.`shops_id`'=>$this->dot_id,
					'`t`.`items_id`'=>$this->items_id,
			));
			if ( !!$model = self::model()->find($criteria))
			{
				$this->agent_id = $model->Pro_Items->agent_id;
				$this->store_id = $model->Pro_Items->store_id;
				$this->c_id = $model->Pro_Items->c_id;
			}
			else
				$this->addError('dot_id', '选择景点或项目 不是有效值');
		}
	}
}
