<?php
class HttpRequest extends CHttpRequest
{
    /**
     * 能否 跨域
     * @var boolean
     */
    public $enableCrossValidation = true;
    /**
     * 跨域 域名
     * @var string
     */
    public $crossDomainName = '*';
    /**
     * 是否强制 https
     * @var boolean
     */
    public $enableHttpsValidation = true;
    
    /**
     * (non-PHPdoc)
     * @see CHttpRequest::normalizeRequest()
     */
    protected function normalizeRequest()
    {
        $old = $this->enableCsrfValidation;
        $this->enableCsrfValidation = false;
        parent::normalizeRequest();
       $this->enableCsrfValidation = $old;
    }
    
    /**
     * (non-PHPdoc)
     * @see CHttpRequest::validateCsrfToken()
     */
    public function validateCsrfToken($event)
    {
        if ($this->enableCsrfValidation && isset($event->enableCsrfValidation))
            $this->enableCsrfValidation = $event->enableCsrfValidation;
        if ($this->enableCsrfValidation)
        {
            parent::validateCsrfToken($event);
            if (isset($_POST[$this->csrfTokenName]))
                unset($_POST[$this->csrfTokenName]);
        }
    }
    
    /**
     * 跨域程序
     */
    public function validateCrossDomain($event)
    {
        if ($this->enableCrossValidation && isset($event->enableCrossValidation))
        {
            $this->enableCrossValidation = $event->enableCrossValidation;
            $this->crossDomainName = $event->crossDomainName;
        }
        if ($this->enableCrossValidation && isset($_SERVER['HTTP_ORIGIN']))
        {
            header('Access-Control-Allow-Credentials: true');
            if ($this->crossDomainName == '*')
                header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
            elseif (is_array($this->crossDomainName) && in_array($_SERVER['HTTP_ORIGIN'], $this->crossDomainName))
                header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
            else
                header('Access-Control-Allow-Origin: ' . $this->crossDomainName);
        }
    }
    
    /**
     * 是否强制使用 https
     * @param unknown $event
     */
    public function validateHttpsMust($event)
    {
        if ($this->enableHttpsValidation && isset($event->enableHttpsValidation))
            $this->enableHttpsValidation = $event->enableHttpsValidation;
        if ($this->enableHttpsValidation && !$this->getIsSecureConnection())
        {
            $url = $this->getUrl();
            if (strpos($url, '/') === 0 && strpos($url, '//') !== 0)
                $url = $this->getHostInfo() . $url;
            if (strpos($url, 'https') !== 0)
            {
                $url = 'https' . ltrim($url, 'http');
                $this->redirect($url);
            }
        }
    }
}