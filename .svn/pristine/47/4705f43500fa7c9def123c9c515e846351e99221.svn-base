<?php
/**
 * 运营商短信登录身份验证
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
        else
        {
            $this->_id = $this->_agent->id;
            $this->errorCode = self::ERROR_NONE;
        }
        return $this->errorCode==self::ERROR_NONE;
    }

    /**
     * 返回登录唯一标示id
     * @see CUserIdentity::getId()
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     *持久保存数据
     * @see CBaseUserIdentity::getPersistentStates()
     */
    public function getPersistentStates()
    {
    	return array();
    }
    
    /**
     * 返回登录人的数据
     * @return Ambigous <static, mixed, NULL, multitype:static , multitype:unknown Ambigous <static, NULL> , multitype:, multitype:unknown >
     */
    public function getAgent()
    {
        return $this->_agent;
    }

}