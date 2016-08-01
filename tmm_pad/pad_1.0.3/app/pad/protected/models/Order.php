<?php

/**
 * This is the model class for table "{{order}}".
 *
 * The followings are the available columns in table '{{order}}':
 * @property string $id
 * @property string $p_id
 * @property integer $type
 * @property string $order_no
 * @property string $role_id
 * @property string $money
 * @property string $trade_money
 * @property string $trade_no
 * @property string $trade_id
 * @property string $trade_name
 * @property integer $trade_type
 * @property string $trade_time
 * @property string $manager_id
 * @property string $up_time
 * @property string $add_time
 * @property integer $pay_status
 * @property integer $status
 */
class Order extends ActiveRecord
{
    /**
     * 订单类型 抢菜订单类型
     * @var integer
     */
    const ORDER_TYPE_FOOD = 0;
    /**
     * 订单类型 解释字段 type 含义
     * @var array
     */
    public static $_type = array(
        self::ORDER_TYPE_FOOD => '抢菜订单',
    );
    /**
     * 订单号前缀
     * @var array
     */
    public static $__type = array(
        self::ORDER_TYPE_FOOD => 'FOOD',
    );
    /**
     * 支付状态 未支付
     * @var integer
     */
    const ORDER_PAY_STATUS_NO = 0;
    /**
     * 支付状态 支付中
     * @var integer
     */
    const ORDER_PAY_STATUS_ING = 1;
    /**
     * 支付状态 已支付
     * @var integer
     */
    const ORDER_PAY_STATUS_YES = 2;
    /**
     * 支付状态 解释字段 type 含义
     * @var array
     */
    public static $_pay_status = array(
        self::ORDER_PAY_STATUS_NO => '未支付',
        self::ORDER_PAY_STATUS_ING => '支付中',
        self::ORDER_PAY_STATUS_YES => '已支付',
    );
    /**
     * 支付类型 微信支付
     * @var integer
     */
    const ORDER_TRADE_TYPE_NONE = 0;
    /**
     * 支付类型 微信支付
     * @var integer
     */
    const ORDER_TRADE_TYPE_WX = 1;
    /**
     * 支付类型 解释字段 trade_type 含义
     * @var array
     */
    public static $_trade_type = array(
        self::ORDER_TRADE_TYPE_NONE => '--------',
        self::ORDER_TRADE_TYPE_WX => '微信支付',
    );

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{order}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('type, trade_type, pay_status, status', 'numerical', 'integerOnly'=>true),
            array('p_id, role_id, money, trade_money, manager_id', 'length', 'max'=>20),
            array('order_no, trade_no, trade_id, trade_name', 'length', 'max'=>256),
            array('trade_time, up_time, add_time', 'length', 'max'=>10),
            
            array('type', 'in', 'range'=>array_keys(self::$_type)),
            array('pay_status', 'in', 'range'=>array_keys(self::$_pay_status)),
            array('trade_type', 'in', 'range'=>array_keys(self::$_trade_type)),
            array('status', 'in', 'range'=>array_keys(self::$_status)),
            //创建订单
            array('type, money', 'required', 'on'=>'create'),
            array('type, money', 'safe', 'on'=>'create'),
            array('id, p_id, order_no, role_id, trade_money, trade_no, trade_id, trade_name, trade_type, trade_time, manager_id, up_time, add_time, pay_status, status', 'unsafe', 'on'=>'create'),
            
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, p_id, type, order_no, role_id, money, trade_money, trade_no, trade_id, trade_name, trade_type, trade_time, manager_id, up_time, add_time, pay_status, status', 'safe', 'on'=>'search'),
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
            'Order_OrderFood' => array(self::HAS_ONE, 'OrderFood' , 'id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'p_id' => '批量支付',
            'type' => '类型',
            'order_no' => '订单号',
            'role_id' => '归属角色',
            'money' => '订单价格',
            'trade_money' => '支付价格',
            'trade_no' => '回调订单号',
            'trade_id' => '支付账号',
            'trade_name' => '支付账号昵称',
            'trade_type' => '支付类型',
            'trade_time' => '支付时间',
            'manager_id' => '操作角色',
            'up_time' => '更新时间',
            'add_time' => '创建时间',
            'pay_status' => '支付状态',
            'status' => '状态',
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
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id, true);
        $criteria->compare('p_id', $this->p_id, true);
        $criteria->compare('type', $this->type);
        $criteria->compare('order_no', $this->order_no, true);
        $criteria->compare('role_id', $this->role_id, true);
        $criteria->compare('money/100', $this->money, true);
        $criteria->compare('trade_money/100', $this->trade_money, true);
        $criteria->compare('trade_no', $this->trade_no, true);
        $criteria->compare('trade_id', $this->trade_id, true);
        $criteria->compare('trade_name', $this->trade_name, true);
        $criteria->compare('trade_type', $this->trade_type);
        $this->timeSearch('trade_time', $criteria, $this->trade_time);
        $criteria->compare('manager_id', $this->manager_id, true);
        $this->timeSearch('up_time', $criteria, $this->up_time);
        $this->timeSearch('add_time', $criteria, $this->add_time);
        $criteria->compare('pay_status', $this->pay_status);
        if ($this->status != self::_STATUS_DELETED)
            $criteria->compare('status', '<>' . self::_STATUS_DELETED);
        $criteria->compare('status', $this->status);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
            'pagination'=>array(
                'pageSize'=>10,
            ),
            'sort'=>array(
                'defaultOrder'=>'t.id desc',
                /*
                'attributes'=>array(
                    'id'=>array(
                        'desc'=>'id desc',
                    ),
                ),
                */
            ),
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Order the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
    
    /**
     * 保存之前的操作
     * (non-PHPdoc)
     * @see CActiveRecord::beforeSave()
     */
    public function beforeSave()
    {
        if (parent::beforeSave())
        {
            if ($this->getIsNewRecord())
                $this->trade_time = $this->up_time = $this->add_time = time();
            return true;
        }
        return false;
    }
    
    /**
     * 获取订单号
     * @return string
     */
    public function getOrderNo()
    {
        $prefix = isset(self::$__type[$this->type]) ? self::$__type[$this->type] : 'NONE';
        return $prefix . date('YmdHis') . mt_rand(10000, 99999) . $this->id;
    }
    
    /**
     * 创建订单 一定要在事物中调用
     * @param unknown $data array()
     * @return \Order|boolean
     */
    public function createOrder($data)
    {
        $model = new \Order;
        $model->scenario = 'create';
        $model->attributes = $data;
        $model->role_id = Yii::app()->user->id;
        $model->pay_status = Order::ORDER_PAY_STATUS_NO;
        if ($model->save()) {
            $model->order_no = $model->getOrderNo();
            if ($model->save(false)) {
                return $model;
            }
        }
        return false;
    }
}