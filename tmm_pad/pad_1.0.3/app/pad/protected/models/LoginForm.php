<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class LoginForm extends CFormModel
{
    /**
     * 手机号
     * @var unknown
     */
    public $phone;
    /**
     * 密码
     * @var unknown
     */
    public $password;
    /**
     * 设备系列号
     * @var unknown
     */
    public $number;
    /**
     * 设备网卡地址
     * @var unknown
     */
    public $mac;
    /**
     * 登录凭证
     * @var unknown
     */
    private $_identity;

    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules()
    {
        return array(
            array('phone, password, number', 'required', 'on'=>'login'),
            array('password', 'length', 'max'=>25, 'on'=>'login'),
            array('phone', 'ext.validators.PhoneValidator', 'on'=>'login'),
            array('phone, password, number, mac', 'safe', 'on'=>'login'),
            array('password', 'authenticate', 'on'=>'login'),
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels()
    {
        return array(
            'phone' => '手机号',
            'password' => '密码',
            'number' => 'MAC地址',
            'mac' => 'IMEI号',
        );
    }

    /**
     * Authenticates the password.
     * This is the 'authenticate' validator as declared in rules().
     */
    public function authenticate($attribute, $params)
    {
        if ( !$this->hasErrors())
        {
            $this->_identity = new UserIdentity($this->phone, $this->password);
            $this->_identity->number = $this->number;
            $this->_identity->mac = $this->mac;
            $status = $this->_identity->authenticate();
            if ($this->_identity->errorCode === UserIdentity::ERROR_ROLE_STATUS_INVALID || $this->_identity->errorCode === UserIdentity::ERROR_STORE_STATUS_INVALID)
                $this->addError('password', '该账户已被禁用');
            elseif ($this->_identity->errorCode === UserIdentity::ERROR_PAD_STATUS_INVALID)
                $this->addError('password', '展示屏已被禁用');
            elseif ($this->_identity->errorCode === UserIdentity::ERROR_NUMBER_INVALID)
                $this->addError('password', '该展示屏已被其他账号绑定了');
            elseif ( !$status)
                $this->addError('password', '手机号或密码错误');
        }
    }

    /**
     * Logs in the user using the given username and password in the model.
     * @return boolean whether login is successful
     */
    public function login()
    {
        if ($this->_identity === null)
        {
            $this->_identity = new UserIdentity($this->phone,$this->password);
            $this->_identity->number = $this->number;
            $this->_identity->mac = $this->mac;
            $this->_identity->authenticate();
        }
        if ($this->_identity->errorCode === UserIdentity::ERROR_NONE)
            return Yii::app()->user->login($this->_identity, 3600 * 24 * 365);
        else
            return false;
    }
}