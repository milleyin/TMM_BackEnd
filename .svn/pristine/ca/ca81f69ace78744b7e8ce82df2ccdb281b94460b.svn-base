<?php

/**
 * This is the model class for table "{{sms}}".
 *
 * The followings are the available columns in table '{{sms}}':
 * @property string $id
 * @property integer $type
 * @property integer $use_type
 * @property string $phone
 * @property string $role_id
 * @property string $manager_id
 * @property string $key
 * @property string $code
 * @property string $text
 * @property string $error_count
 * @property integer $number
 * @property integer $source
 * @property string $ip
 * @property string $invalid_time
 * @property string $up_time
 * @property string $add_time
 * @property integer $status
 */
class Sms extends ActiveRecord
{
    /**
     * 通知短信
     * @var integer
     */
    const SMS_TYPE_NOTICE = 0;
    /**
     * 短信用途 解释字段 type 的含义
     * @var unknown
     */
    public $_type = array(
        self::SMS_TYPE_NOTICE => '通知短信',
    );
    
    /**
     * 快递
     * @var integer
     */
    const SMS_USE_TYPE_EXPRESS = 0;
    /**
     * 短信用途 解释字段 type 的含义
     * @var unknown
     */
    public $_use_type = array(
        self::SMS_USE_TYPE_EXPRESS => '快递',
    );
    
    /**
     * 状态 解释字段 status 的含义
     * @var array
     */
    public static $_status = array(
        self::_STATUS_DELETED => '无效',
        self::_STATUS_DISABLE => '已用',
        self::_STATUS_NORMAL => '有效',
    );
    /**
     * 来源 未知
     * @var integer
     */
    const SMS_SOURCE_UNKNOWN = 0;
    /**
     * 来源 PC
     * @var integer
     */
    const SMS_SOURCE_PC = 1;
    /**
     * 来源 安卓
     * @var integer
     */
    const SMS_SOURCE_ANDROID = 2;
    /**
     * 来源 IOS
     * @var integer
     */
    const SMS_SOURCE_IOS = 3;
    /**
     * 来源 微信
     * @var integer
     */
    const SMS_SOURCE_WEIXIN = 4;
    /**
     * 来源 解释字段 source 的含义
     * @var array
     */
    public static $_source = array(
        self::SMS_SOURCE_UNKNOWN => '未知',
        self::SMS_SOURCE_PC => 'PC',
        self::SMS_SOURCE_ANDROID => '安卓',
        self::SMS_SOURCE_IOS => 'IOS',
        self::SMS_SOURCE_WEIXIN => '微信',
    );
    
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{sms}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            //array('error_count, number', 'required'),
            array('type, use_type, number, source, status', 'numerical', 'integerOnly'=>true),
            array('phone, error_count', 'length', 'max'=>11),
            array('role_id, manager_id', 'length', 'max'=>20),
            array('key, text', 'length', 'max'=>128),
            array('code', 'length', 'max'=>32),
            array('ip', 'length', 'max'=>15),
            array('invalid_time, up_time, add_time', 'length', 'max'=>10),
            // 通知
            array('use_type, role_id, phone, text', 'required', 'on'=>'notice'),
            array('type', 'ext.validators.DefaultValueValidator', 'value'=>self::SMS_TYPE_NOTICE, 'on'=>'notice'),
            array('use_type, role_id, phone, text', 'safe', 'on'=>'notice'),
            array('id, type, manager_id, key, code, error_count, number, source, ip, invalid_time, up_time, add_time, status', 'unsafe', 'on'=>'notice'),
            
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, type, use_type, phone, role_id, manager_id, key, code, text, error_count, number, source, ip, invalid_time, up_time, add_time, status', 'safe', 'on'=>'search'),
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
            'id' => 'ID',
            'type' => '类型',
            'use_type' => '类型',
            'phone' => '手机号',
            'role_id' => '接受角色ID',
            'manager_id' => '操作角色ID',
            'key' => '秘钥',
            'code' => 'CODE',
            'text' => '内容',
            'error_count' => '错误统计',
            'number' => '剩余错误',
            'source' => '来源',
            'ip' => 'IP地址',
            'invalid_time' => '失效时间',
            'up_time' => '修改时间',
            'add_time' => '注册时间',
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

        $criteria->compare('id', $this->id,true);
        $criteria->compare('type', $this->type);
        $criteria->compare('use_type', $this->use_type);
        $criteria->compare('phone', $this->phone,true);
        $criteria->compare('role_id', $this->role_id,true);
        $criteria->compare('manager_id', $this->manager_id,true);
        $criteria->compare('key', $this->key,true);
        $criteria->compare('code', $this->code,true);
        $criteria->compare('text', $this->text,true);
        $criteria->compare('error_count', $this->error_count,true);
        $criteria->compare('number', $this->number);
        $criteria->compare('source', $this->source);
        $criteria->compare('ip', $this->ip,true);
        $this->timeSearch('invalid_time', $criteria, $this->invalid_time);
        $this->timeSearch('up_time', $criteria, $this->up_time);
        $this->timeSearch('add_time', $criteria, $this->add_time);
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
     * @return Sms the static model class
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
            {
                $this->invalid_time = $this->up_time = $this->add_time = time();
                $this->ip = Yii::app()->request->userHostAddress;
                $source = array(
                    self::SMS_SOURCE_UNKNOWN => self::SMS_SOURCE_UNKNOWN,
                    'weixin' => self::SMS_SOURCE_WEIXIN,
                    'ios' => self::SMS_SOURCE_IOS,
                    'android' => self::SMS_SOURCE_ANDROID,
                    'pc' => self::SMS_SOURCE_PC,
                );
                $this->source = $source[Helper::getSource(self::SMS_SOURCE_UNKNOWN)];
                if ($this->type == self::SMS_TYPE_NOTICE) {
                    $this->status = self::_STATUS_DISABLE;
                }
                $result = Helper::sendSms($this->phone, $this->text);
                if ($result === true) {
                    return true;
                } else {
                    if ($result === false)
                        $this->addError('use_type', '发送短信 失败');
                    elseif ($result === -1)
                        $this->addError('use_type', '短信余额不足');
                    return false;
                }
            }
            return true;
        }
        return false;
    }
}