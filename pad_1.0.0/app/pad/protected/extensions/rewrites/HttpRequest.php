<?php
class HttpRequest extends CHttpRequest
{
    public $enableCrossValidation = false;
    public $crossDomainName = '*';
    
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
        if (isset($event->enableCsrfValidation))
            $this->enableCsrfValidation && $this->enableCsrfValidation = $event->enableCsrfValidation;
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
        if (isset($event->enableCrossValidation))
        {
            $this->enableCrossValidation = $event->enableCrossValidation;
            $this->crossDomainName = $event->crossDomainName;
        }
        if ($this->enableCrossValidation && isset($_SERVER['HTTP_ORIGIN']))
        {
            header('Access-Control-Allow-Credentials: true');
            if ($this->crossDomainName == '*')
                header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
            else 
                header('Access-Control-Allow-Origin: ' . $this->crossDomainName);
        }
    }
}