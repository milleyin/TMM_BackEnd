<?php

/**
 * This is the model class for table "{{retinue}}".
 *
 * The followings are the available columns in table '{{retinue}}':
 * @property string $id
 * @property string $user_id
 * @property string $name
 * @property integer $gender
 * @property string $identity
 * @property string $phone
 * @property string $email
 * @property integer $is_main
 * @property string $add_time
 * @property string $up_time
 * @property integer $status
 */
class Retinue extends CActiveRecord
{
	/**
	 * is_main 是主要联系人
	 * @var array
	 */
	const is_main=1;
	/**
	 * is_main 否主要联系人
	 * @var array
	 */
	const not_is_main=0;
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
	 * 解释字段 gender 的含义
	 * @var array
	 */
	public static $_gender=array('保密',1=>'男',2=>'女');
	/**
	 * 解释字段 is_main 的含义
	 * @var array
	 */
	public static $_is_main=array('不是','是');
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{retinue}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			//array('user_id, name, identity, phone', 'required'),
			array('gender, is_main, status', 'numerical', 'integerOnly'=>true),
			array('user_id', 'length', 'max'=>11),
			// array('name', 'length', 'max'=>20),
			array('identity', 'length', 'max'=>18),
			array('phone', 'length', 'max'=>15),
			array('email', 'length', 'max'=>100),
			array('add_time, up_time', 'length', 'max'=>10),
			//array('gender','in','range'=>array_keys(self::$_gender)),	
				
			//创建、修改
			array('phone','match','pattern'=>Yii::app()->params['pattern']['phone'],'message'=>'{attribute} 不是有效的','on'=>'create,update'),	
			array('name,identity,phone,is_main', 'required','on'=>'create,update'),
			array('email','email','on'=>'create,update'),
			array('email','main_email','on'=>'create,update'),
			array('name', 'length', 'max'=>10,'on'=>'create,update'),
			array('is_main','in','range'=>array_keys(self::$_is_main)),
			array('identity','ext.Validator.Validator_identity','on'=>'create,update'),
			array('identity','unique_identity','on'=>'create,update'),
			array('is_main','is_main','on'=>'create,update'),
			array('phone','phone_user_phone','on'=>'create,update'),
			array('name, identity, phone, email,is_main', 'safe', 'on'=>'create,update'),			
			array('search_time_type,search_start_time,search_end_time,id, user_id, gender, add_time, up_time, status', 'unsafe', 'on'=>'create,update'),

			// 更新主要联系人
			array('name, identity', 'required','on'=>'update_main'),
			array('identity','ext.Validator.Validator_identity','on'=>'update_main'),
			array('identity','unique_identity','on'=>'update_main'),
			array('name, phone,identity, email', 'safe', 'on'=>'update_main'),
			array('email','email','on'=>'update_main'),
			array('is_main','in','range'=>array(1),'on'=>'update_main'),
			array('phone','phone_user_phone','on'=>'update_main'),
			array('search_time_type,search_start_time,search_end_time,id, user_id, gender, is_main, add_time, up_time, status', 'unsafe', 'on'=>'update_main'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('search_time_type,search_start_time,search_end_time,id, user_id, name, gender, identity, phone, email, is_main, add_time, up_time, status', 'safe', 'on'=>'search'),
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
				//归属用户的随行人员
				'Retinue_User'=>array(self::BELONGS_TO,'User','user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'user_id' => '用户',
			'name' => '姓名',
			'gender' => '性别',
			'identity' => '身份证号',
			'phone' => '手机号',
			'email' => '邮箱地址',
			'is_main' => '主要联系人',
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
			$criteria->compare('t.status','<>-1');
			$criteria->with=array(
				'Retinue_User'=>array('select'=>'id,phone,nickname,status')
			);
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
			$criteria->compare('Retinue_User.phone',$this->user_id,true);
			$criteria->compare('Retinue_User.id',$this->Retinue_User->id);
			$criteria->compare('t.name',$this->name,true);
			$criteria->compare('t.gender',$this->gender);
			$criteria->compare('t.identity',$this->identity,true);
			$criteria->compare('t.phone',$this->phone,true);
			$criteria->compare('t.email',$this->email,true);
			$criteria->compare('t.is_main',$this->is_main);
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
	 * 随行人员
	 * @param string $criteria
	 * @return CActiveDataProvider
	 */
	public function search_order($id)
	{
			$criteria=new CDbCriteria;
			$criteria->compare('t.status','<>-1');
			$criteria->with=array(
				'Retinue_OrderRetinue'=>array(
					'condition'=>'`Retinue_OrderRetinue`.`order_id`=:order_id',
					'params'=>array(':order_id'=>$id),
					),
				'Retinue_User'=>array('select'=>'id,phone,nickname,status')
			);
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
			$criteria->compare('Retinue_User.phone',$this->user_id,true);
			$criteria->compare('t.name',$this->name,true);
			$criteria->compare('t.gender',$this->gender);
			$criteria->compare('t.identity',$this->identity,true);
			$criteria->compare('t.phone',$this->phone,true);
			$criteria->compare('t.email',$this->email,true);
			$criteria->compare('t.is_main',$this->is_main);
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
	 * @return Retinue the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/**
	 * 保存之前的操作
	 * @see CActiveRecord::beforeSave()
	 */
	public function beforeSave()
	{
		if(parent::beforeSave())
		{
			if(isset(self::$_gender[substr($this->identity, 16,1)%2==0?2:1]))
				$this->gender=substr($this->identity, 16,1)%2==0?2:1;
			if($this->isNewRecord)
				$this->up_time=$this->add_time=time();	
			else
				$this->up_time=time();			
			return true;
		}else
			return false;
	}
	
	/**
	 * 对一个用户 身份证验证唯一 （排除非正常状态）
	 * @param unknown $attribute
	 */
	public function unique_identity($attribute)
	{
		if(! $this->isNewRecord)
		{		
			if(self::model()->find('`identity`=:identity AND `user_id`=:user_id AND `status`=1 AND id!=:id',array(':identity'=>$this->$attribute,':user_id'=>$this->Retinue_User->id,':id'=>$this->id)))
				$this->addError($attribute, $this->getAttributeLabel($attribute).' 已被添加');
		}else{
			if(self::model()->find('`identity`=:identity AND `user_id`=:user_id AND `status`=1',array(':identity'=>$this->$attribute,':user_id'=>$this->Retinue_User->id)))
				$this->addError($attribute, $this->getAttributeLabel($attribute).' 已被添加');
		}
	}
	
	/**
	 * 验证主要联系人的手机号
	 */
	public function phone_user_phone()
	{
		if($this->is_main==self::is_main)
		{
			if($this->phone && $this->user_id)
			{
				$model=User::model()->findByPk($this->user_id);
				if($model && ($this->phone != $model->phone))
					$this->addError('phone', $this->getAttributeLabel('phone').' 用户手机号必须重复');
			}
		}
	}
	
	/**
	 * 验证主要联系人的有效地址是否存在
	 */
	public function main_email()
	{
		if($this->is_main==self::is_main)
		{
			if(! $this->email)
			{
				$this->addError('email', $this->getAttributeLabel('email').' 不可空白');
			}
		}
	}
	
	/**
	 * 是否设置了主要联系人
	 * @param unknown $attribute
	 */
	public function is_main($attribute)
	{
		if($this->$attribute==self::is_main)
		{
			if($this->isNewRecord)
			{
				if(self::model()->find('`is_main`=:is_main AND `user_id`=:user_id AND `status`=1',array(':is_main'=>self::is_main,':user_id'=>$this->Retinue_User->id)))
					$this->addError($attribute, $this->getAttributeLabel($attribute).' 已经设置了');
			
			}else{
				if(self::model()->find('`is_main`=:is_main AND `user_id`=:user_id AND `status`=1 AND id!=:id',array(':is_main'=>self::is_main,':user_id'=>$this->Retinue_User->id,':id'=>$this->id)))
					$this->addError($attribute, $this->getAttributeLabel($attribute).' 已经设置了');
			}
		}
	}
	
	/**
	 * 订单
	 * 验证随行人员
	 * @param unknown $model
	 */
	public static function validate_retinue($model)
	{
		if(isset($_POST['OrderRetinue']) && !empty($_POST['OrderRetinue']) && is_array($_POST['OrderRetinue']))
		{
			$i=0;//随行人员的排序
			$is_main_yes=0; //主要联系人的个数
			$is_main_not=0; //主要联系人的个数
			Order::$retinue_count=count($_POST['OrderRetinue']);
			$retinue_ids=array();
			$retinue_is_mian='';
			foreach ($_POST['OrderRetinue'] as $key=>$retinue)
			{
				if($i==$key && isset($retinue['is_main']) && isset($retinue['retinue_id']) && $is_main_yes<=1)
				{
					if($retinue['is_main']==OrderRetinue::is_main_yes)
					{
						$retinue_is_mian=$retinue['retinue_id'];
						$is_main_yes++;
					}elseif($retinue['is_main']==OrderRetinue::is_main_not)
					$is_main_not++;
					$retinue_ids[]=$retinue['retinue_id'];
				}
				else
				{
					$model->addError('order_price','订单中随行人员 不是有效值');
					$model->addError('status','001');
					return false;
				}
				$i++;
			}
			$retinue_ids=array_flip(array_flip($retinue_ids));//去掉重复的
			if($is_main_yes !=1 || Order::$retinue_count != ($is_main_not+$is_main_yes) || empty($retinue_ids) || Order::$retinue_count != count($retinue_ids))
			{
				$model->addError('order_price','订单中随行人员 不是有效值');
				$model->addError('status','001');
				return false;
			}
			$datas=Retinue::get_retinue($retinue_ids,$retinue_is_mian);//获取随行人的资料
			if(empty($datas) || count($datas) != Order::$retinue_count)
			{
				$model->addError('order_price','订单中随行人员 不是有效值');
				$model->addError('status','001');
				return false;
			}
			if($model->user_go_count != Order::$retinue_count)
			{
				$model->addError('order_price','订单中随行人员 不是有效值');
				$model->addError('status','001');
				return false;
			}
			$OrderRetinue=array();
			foreach ($datas as $data)
				$OrderRetinue[]=Retinue::set_order_retinue($data);
			if(empty($OrderRetinue))
			{
				$model->addError('order_price','订单中随行人员 不是有效值');
				$model->addError('status','001');
				return false;
			}
			$model->Order_OrderRetinue=$OrderRetinue;
			if($model->order_type==Order::order_type_dot)//点
			{
				//随行人员的数量限制
				if(Yii::app()->params['order_limit']['dot']['retinue_count'] < Order::$retinue_count || Order::$retinue_count<1)
				{
					$model->addError('order_price','订单中随行人员 不是有效值');
					$model->addError('status','001');
					return false;
				}
			}
			elseif($model->order_type==Order::order_type_thrand)
			{
				//随行人员的数量限制
				if(Yii::app()->params['order_limit']['thrand']['retinue_count'] < Order::$retinue_count || Order::$retinue_count<1)
				{
					$model->addError('order_price','订单中随行人员 不是有效值');
					$model->addError('status','001');
					return false;
				}
				if(Order::$retinue_count != (Order::$adult_number+Order::$children_number))
				{
					$model->addError('order_price','订单中随行人员 不是有效值');
					$model->addError('status','001');
					return false;
				}
			}			
			elseif($model->order_type==Order::order_type_actives_tour)
			{
				//随行人员的数量限制
				if(Yii::app()->params['order_limit']['actives_tour']['retinue_count'] < Order::$retinue_count || Order::$retinue_count<1)
				{
					$model->addError('order_price','订单中随行人员 不是有效值');
					$model->addError('status','001');
					return false;
				}
				if(Order::$retinue_count != (Order::$adult_number+Order::$children_number))
				{
					$model->addError('order_price','订单中随行人员 不是有效值');
					$model->addError('status','001');
					return false;
				}
				
			}
			return true;
		}
		else
		{
			$model->addError('order_price','订单中随行人员 不可空白 r5');
			$model->addError('status','001');
			return false;
		}
	}
	
	/**
	 *	随行人员数据生成
	 * @param unknown $data
	 * @return OrderRetinue
	 */
	public static function set_order_retinue($data)
	{
		$model=new OrderRetinue;
		$model->scenario='create';
		$model->user_id=Yii::app()->api->id;
		$model->retinue_id=$data->id;
		$model->retinue_name=$data->name;
		$model->retinue_gender=$data->gender;
		$model->retinue_identity=$data->identity;
		$model->retinue_phone=$data->phone;
		$model->is_main=$data->is_main;
		//Order::$retinue_count++;
		return $model;
	}
	
	/**
	 * 获取数据
	 */
	public static function get_retinue($retinue_ids,$id)
	{
		$return=array();
		if(!empty($retinue_ids))
		{
			$criteria=new CDbCriteria;
			$criteria->addColumnCondition(array(
					'user_id'=>Yii::app()->api->id,
					'status'=>1,
			));
			$criteria->addInCondition('id', $retinue_ids);
			$criteria->addCondition('(id=:id AND is_main=:is_main) OR (id!=:id AND is_main=:is_main_not)');
			$criteria->params[':id']=$id;
			$criteria->params[':is_main']=Retinue::is_main;
			$criteria->params[':is_main_not']=Retinue::not_is_main;
			$models=Retinue::model()->findAll($criteria);
			if(!empty($models) && is_array($models))
			{
				foreach ($models as $model)
					$return[$model->id]=$model;
			}
		}
		return $return;
	}
}
