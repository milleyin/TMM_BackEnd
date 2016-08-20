<?php

/**
 * This is the model class for table "{{chance}}".
 *
 * The followings are the available columns in table '{{chance}}':
 * @property string $id
 * @property string $user_id
 * @property string $store_id
 * @property string $pad_id
 * @property string $config_id
 * @property integer $type
 * @property string $count
 * @property string $number
 * @property string $date_time
 * @property string $up_time
 * @property string $add_time
 * @property integer $status
 */
class Chance extends ActiveRecord
{
    /**
     * 抽奖状态 解释字段 status 含义 
     * @var array
     */
    public static $_status = array(
        self::_STATUS_DELETED => '已结束',
        self::_STATUS_DISABLE => '未抽完',
        self::_STATUS_NORMAL => '抽奖中',
    ); 
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{chance}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            //array('user_id, store_id, pad_id, config_id', 'required'),
            array('type, number, status', 'numerical', 'integerOnly'=>true),
            array('user_id, store_id, pad_id, config_id', 'length', 'max'=>20),
            array('count, number', 'length', 'max'=>11),
            array('date_time, up_time, add_time', 'length', 'max'=>10),
            
            array('status', 'in', 'range'=>array_keys(self::$_status)),
            array('type', 'in', 'range'=>array_keys(Config::$_type)),
            // 添加抽奖机会
            array('user_id, store_id, pad_id, config_id, type, count, number, date_time, status', 'required', 'on'=>'create'),
            array('user_id, store_id, pad_id, config_id, type, count, number, date_time, status', 'safe', 'on'=>'create'),
            array('id, up_time, add_time', 'unsafe', 'on'=>'create'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, user_id, store_id, pad_id, config_id, type, count, number, date_time, up_time, add_time, status', 'safe', 'on'=>'search'),
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
            'Chance_Pad'=>array(self::BELONGS_TO, 'Pad', 'pad_id'),
            'Chance_Store'=>array(self::BELONGS_TO, 'Store', 'store_id'),
            'Chance_Config'=>array(self::BELONGS_TO, 'Config', 'config_id'),
            'Chance_User'=>array(self::BELONGS_TO, 'User', 'user_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'user_id' => '用户昵称',
            'store_id' => '体验店',
            'pad_id' => '展示屏',
            'config_id' => '抽奖配置',
            'type' => '抽奖类型',
            'count' => '抽奖次数',
            'number' => '剩余次数',
            'date_time' => '获取日期',
            'up_time' => '更新时间',
            'add_time' => '创建时间',
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
        $criteria->with = array(
            'Chance_Pad',
            'Chance_Store'=>array(
                'with'=>array(
                    'Store_Area_province',
                    'Store_Area_city',
                    'Store_Area_district',
                ),
            ),
            'Chance_User',
        );

        $criteria->compare('t.id', $this->id,true);

        if (strpos($this->user_id, '=') === 0)
            $criteria->compare('t.user_id', $this->user_id);
        else
            $criteria->compare('Chance_User.name', $this->user_id, true);
        
        if (strpos($this->store_id, '=') === 0)
            $criteria->compare('t.store_id', $this->store_id);
        else
            $criteria->compare('Chance_Store.store_name', $this->store_id, true);
        
        if (strpos($this->pad_id, '=') === 0)
            $criteria->compare('t.pad_id', $this->pad_id);
        else
            $criteria->compare('Chance_Pad.name', $this->pad_id, true);
        
        $criteria->compare('t.config_id', $this->config_id,true);
        $criteria->compare('t.type', $this->type);
        $criteria->compare('t.count', $this->count,true);
        $criteria->compare('t.number', $this->number,true);
        $this->timeSearch('t.date_time', $criteria, $this->date_time);
        $this->timeSearch('t.up_time', $criteria, $this->up_time);
        $this->timeSearch('t.add_time', $criteria, $this->add_time);
        $criteria->compare('t.status', $this->status);
        
        $criteria->compare('Chance_Store.phone', $this->Chance_Store->phone, true);
        if ( (!!$area = Area::model()->validateAttribute('city', $this->Chance_Store->city)) && $area->pid != $this->Chance_Store->province)
            $this->Chance_Store->city = $this->Chance_Store->district = '';
        $criteria->compare('Chance_Store.province', $this->Chance_Store->province);
        $criteria->compare('Chance_Store.city', $this->Chance_Store->city);
        $criteria->compare('Chance_Store.district', $this->Chance_Store->district);
        
        $criteria->compare('Chance_Pad.number', $this->Chance_Pad->number, true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
            'pagination'=>array(
                'pageSize'=>10,
            ),
            'sort'=>array(
                'defaultOrder'=>'t.id desc',
                'attributes'=>array(
                    'Chance_Store.phone'=>array(
                            'desc'=>'Chance_Store.phone desc',
                    ),
                    'Chance_Store.province'=>array(
                            'desc'=>'Chance_Store.province desc',
                    ),
                    'Chance_Store.city'=>array(
                            'desc'=>'Chance_Store.city desc',
                    ),
                    'Chance_Store.district'=>array(
                            'desc'=>'Chance_Store.district desc',
                    ),
                    'Chance_Pad.number'=>array(
                            'desc'=>'Chance_Pad.number desc',
                    ),
                    '*',
                ),
            ),
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Chance the static model class
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
                $this->up_time = $this->add_time = time();
            return true;
        }
        return false;
    }
    
    /**
     * 获取抽奖机会数据模型
     * @param unknown $id
     * @param unknown $type
     * @return Ambigous <NULL, unknown, multitype:unknown Ambigous <unknown, NULL> , mixed, multitype:, multitype:unknown >
     */
    public function getChance($id, $type = Config::Config_TYPE_FREE)
    {
        $criteria = new CDbCriteria;
        $criteria->addColumnCondition(array(
            '`user_id`' => Yii::app()->user->id,
            '`pad_id`' => $id,
            '`date_time`' => strtotime(date('Y-m-d', time())),
            '`type`' => $type,
        ));
        $criteria->order = 'number desc';
        $criteria->addCondition('status!=:status AND number > 0');
        $criteria->params[':status'] = Config::_STATUS_DELETED;
        return Chance::model()->find($criteria);
    }
    
    /**
     * 更新抽奖机会
     * @param unknown $model
     * @param unknown $id
     * @return boolean
     */
    public function updateChance($model, $id)
    {
        if ($model->status != Chance::_STATUS_NORMAL) {
            $model->status = Chance::_STATUS_NORMAL;
            if ( !$model->save(false)) {
                return false;
            }
        }
        //将抽奖中的用户 更新为 未抽完
        $criteria = new CDbCriteria;
        $criteria->addColumnCondition(array(
            '`pad_id`' => $id,
            '`date_time`' => strtotime(date('Y-m-d', time())),
            '`status`' => Chance::_STATUS_NORMAL,
        ));
        //排除刚查询到的
        $criteria->compare('id', '<>' . $model->id);
        if (Chance::model()->count($criteria) == 0) {
            return true;
        }
        if (Chance::model()->updateAll(array('status'=>Chance::_STATUS_DISABLE, 'up_time'=>time()), $criteria)) {
            return true;
        }
        return false;
    }
    
    /**
     * 统计抽奖机会
     * @param unknown $id
     * @param unknown $type
     * @return Ambigous <string, mixed, unknown>
     */
    public function countChance($id, $type = Config::Config_TYPE_FREE)
    {
        $criteria = new CDbCriteria;
        $criteria->addColumnCondition(array(
            '`user_id`' => Yii::app()->user->id,
            '`pad_id`' => $id,
            '`type`' => $type,
            '`date_time`' => strtotime(date('Y-m-d', time())),
        ));
        return Chance::model()->count($criteria);
    }
    
    /**
     * 创建抽奖机会
     * @param unknown $modelPad
     * @param unknown $type
     * @return boolean
     */
    public function createChance($modelPad, $type = Config::Config_TYPE_FREE)
    {
        $model = new Chance;
        $model->scenario = 'create';
        if (is_array($modelPad)) {
            $model->attributes = $modelPad;
        } else {
            $model->attributes = array(
                'user_id' => Yii::app()->user->id,
                'store_id' => $modelPad->Pad_Store->id,
                'pad_id' => $modelPad->id,
                'config_id' => $modelPad->Pad_Config->id,
                'type' => $type,
                'count' => $modelPad->Pad_Config->number,
                'number' => $modelPad->Pad_Config->number,
                'date_time' => strtotime(date('Y-m-d', time())),
                'status' => Chance::_STATUS_NORMAL,
            );
        }
        if ($model->save()) {
            return $model;
        } else {
            return false;
        }
    }
}