<?php
class HttpRequest extends CHttpRequest
{
    /**
     * 能否 跨域
     * @var boolean | array
     */
    public $enableCrossValidation = true;
    /**
     * 跨域 域名
     * @var string | array
     */
    public $crossDomainName = '*';
    /**
     * 是否强制 https
     * @var boolean | array
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
        if ($this->enableCsrfValidation && isset($event->enableCsrfValidation)) {
            $this->enableCsrfValidation = $this->isException($event, $event->enableCsrfValidation);
        }
        if ($this->enableCsrfValidation) {
            parent::validateCsrfToken($event);
            if (isset($_POST[$this->csrfTokenName])) {
                unset($_POST[$this->csrfTokenName]);
            }
        }
    }
    
    /**
     * 跨域程序
     */
    public function validateCrossDomain($event)
    {
        if ($this->enableCrossValidation && isset($event->enableCrossValidation)) {
            $this->enableCrossValidation = $this->isException($event, $event->enableCrossValidation);
            $this->crossDomainName = $event->crossDomainName;
        }
        if ($this->enableCrossValidation && isset($_SERVER['HTTP_ORIGIN'])) {
            header('Access-Control-Allow-Credentials: true');
            if ($this->crossDomainName == '*') {
                header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
            } elseif (is_array($this->crossDomainName) && in_array($_SERVER['HTTP_ORIGIN'], $this->crossDomainName)) {
                header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
            } else {
                header('Access-Control-Allow-Origin: ' . $this->crossDomainName);
            }
        }
    }
    
    /**
     * 例外的
     * @param unknown $event
     * @param unknown $validation
     * @return boolean
     */
    public function isException($event , $validation)
    {
        if (is_array($validation)) {
            if (in_array($event->id, $validation)) {
                return true;
            } else {
                return false;
            }
        } else {
            return $validation === true ? true : false;
        }
    }
    
    /**
     * 是否强制使用 https
     * @param unknown $event
     */
    public function validateHttpsMust($event)
    {
        if ($this->enableHttpsValidation && isset($event->enableHttpsValidation)) {
            $this->enableHttpsValidation = $this->isException($event, $event->enableHttpsValidation);
        }
        if ($this->enableHttpsValidation && !$this->getIsSecureConnection()) {
            $url = $this->getUrl();
            if (strpos($url, '/') === 0 && strpos($url, '//') !== 0) {
                $url = $this->getHostInfo() . $url;
            }
            if (strpos($url, 'https') !== 0) {
                $url = 'https' . ltrim($url, 'http');
                $event->redirect($url);
            }
        }
    }
}