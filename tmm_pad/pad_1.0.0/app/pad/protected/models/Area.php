<?php

/**
 * This is the model class for table "{{area}}".
 *
 * The followings are the available columns in table '{{area}}':
 * @property string $id
 * @property string $name
 * @property string $spell
 * @property string $pid
 * @property string $sort
 * @property integer $status
 */
class Area extends ActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{area}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('status', 'numerical', 'integerOnly'=>true),
            array('name', 'length', 'max'=>32),
            array('spell', 'length', 'max'=>64),
            array('pid, sort', 'length', 'max'=>11),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, name, spell, pid, sort, status', 'safe', 'on'=>'search'),
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
            //下级 市
            'Area_Area'=>array(self::HAS_MANY, 'Area', 'pid'),
            //下级 县
            'Area_Area_Area'=>array(self::HAS_MANY, 'Area', 'pid'),
            //上级 省
            'Area_Area_province'=>array(self::BELONGS_TO, 'Area', 'pid', 'condition'=>'Area_Area_province.pid=0'),
            //上级 市
            'Area_Area_city'=>array(self::BELONGS_TO, 'Area', 'pid', 'condition'=>'Area_Area_city.pid=Area_Area_province.id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'name' => '名称',
            'spell' => '拼音',
            'pid' => '父级ID',
            'sort' => '排序',
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
        $criteria->compare('name', $this->name,true);
        $criteria->compare('spell', $this->spell,true);
        $criteria->compare('pid', $this->pid,true);
        $criteria->compare('sort', $this->sort,true);
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
     * @return Area the static model class
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
                return true;    
            return true;
        }
        return false;
    }
    
    /**
     * 验证属性 是否有效
     * @param unknown $attribute
     * @param unknown $value
     */
    public function validateAttribute($attribute, $value)
    {
        $criteria = new CDbCriteria;
        $criteria->addColumnCondition(array('t.status'=>self::_STATUS_NORMAL));
        $criteria->addColumnCondition(array(
                't.name'=>$value,
                't.spell'=>$value,
                't.id'=>$value,
        ), 'OR');
        if ($attribute == 'province')
            $criteria->addCondition('t.pid=0');
        else if ($attribute == 'city')
        {
            $criteria->with = array(
                'Area_Area_province'
            );
            $criteria->addCondition('t.pid!=0');
            $criteria->addColumnCondition(array('Area_Area_province.status'=>self::_STATUS_NORMAL));
        }
        else if ($attribute == 'district')
        {
            $criteria->with = array(
                'Area_Area_city'=>array(
                   'with'=>'Area_Area_province'
                ),
            );
            $criteria->addCondition('t.pid!=0');
            $criteria->addColumnCondition(array(
                    'Area_Area_city.status'=>self::_STATUS_NORMAL,
                    'Area_Area_province.status'=>self::_STATUS_NORMAL,
            ));
        }
        else
            return false;
        return $this->find($criteria);
    }
    
    /**
     * 获取 地区数组
     * @param number $pid
     * @param unknown $status
     * @return Ambigous <multitype:, multitype:unknown mixed , mixed, multitype:unknown , string, unknown>
     */
    public function getAreaArray($pid = 0, $status = self::_STATUS_NORMAL)
    {
        if ($pid !== '')
        {
            $criteria = new CDbCriteria;
            $criteria->addColumnCondition(array(
                    'pid'=>$pid,
                    'status'=>$status,
            ));
            return CHtml::listData($this->findAll($criteria), 'id', 'name');
        }
        return array();
    }
}