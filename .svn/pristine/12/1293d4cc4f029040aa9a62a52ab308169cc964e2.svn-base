<?php
/**
 * 代理商登录身份验证
 * @author Changhai Zhan
 *
 */
class AgentSmsUserIdentity extends CUserIdentity
{
    private $_id;
    private $_agent;

    /**
     * Authenticates a user.
     * The example implementation makes sure if the username and password
     * are both 'demo'.
     * In practical applications, this should be changed to authenticate
     * against some persistent user identity storage (e.g. database).
     * @return boolean whether authentication succeeds.
     */
    public function authenticate()
    {
        $this->_agent = Agent::model()->find('phone=:phone', array(':phone' => $this->username));
        if (!isset($this->_agent->phone) || $this->_agent->phone != $this->username)
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        else {
            Yii::import('ext.Send_sms.Send_sms');
            $params = array(
                'sms_id' => $this->_agent->id,
                'sms_type' => SmsLog::sms_agent,
                'role_id' => $this->_agent->id,
                'role_type' => SmsLog::send_agent,
                'sms_use' => SmsLog::use_login,
            );
            if (!Send_sms::verifycode($this->username, $params, $this->password))
                $this->errorCode = self::ERROR_PASSWORD_INVALID;
            else {
                $this->_id = $this->_agent->id;
                $this->errorCode = self::ERROR_NONE;
            }
        }
        return $this->errorCode==self::ERROR_NONE;
    }

    /**
     * 返回登录唯一标示id
     * @see CUserIdentity::getId()
     */
    public function getId(){
        return $this->_id;
    }

    /**
     * 返回登录人的数据
     * @return Ambigous <static, mixed, NULL, multitype:static , multitype:unknown Ambigous <static, NULL> , multitype:, multitype:unknown >
     */
    public function getAgent(){
        return $this->_agent;
    }

}