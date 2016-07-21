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
     * 是否RawBody获取数据
     * @var boolean | array
     */
    public $enableRawBodyValidation = true;
    
    /**
     * 删除 CsrfToken 使其更新
     * @return boolean
     */
    public function unsetCsrfToken()
    {
        $cookie = $this->getCookies();
        if(isset($cookie[$this->csrfTokenName]->value)) {
            unset($cookie[$this->csrfTokenName]);
        }
        return true;
    }
    
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
            $this->enableCsrfValidation = $this->validate($event, $event->enableCsrfValidation);
        }
        if ($this->enableCsrfValidation) {
            if ($this->getIsPostRequest() ||
                $this->getIsPutRequest() ||
                $this->getIsPatchRequest() ||
                $this->getIsDeleteRequest())
            {
                $cookies=$this->getCookies();
                $method=$this->getRequestType();
                switch($method)
                {
                    case 'POST':
                        $userToken=$this->getPost($this->csrfTokenName);
                        break;
                    case 'PUT':
                        $userToken=$this->getPut($this->csrfTokenName);
                        break;
                    case 'PATCH':
                        $userToken=$this->getPatch($this->csrfTokenName);
                        break;
                    case 'DELETE':
                        $userToken=$this->getDelete($this->csrfTokenName);
                }
                if (!empty($userToken) && $cookies->contains($this->csrfTokenName))
                {
                    $cookieToken=$cookies->itemAt($this->csrfTokenName)->value;
                    $valid=$cookieToken===$userToken;
                }
                else
                    $valid = false;
                if (!$valid) {
                    if (isset($event->enableCsrfException)) {
                        $this->evaluateExpression($event->enableCsrfException, array('this'=>$event, 'request'=>$this));
                    } else {
                        throw new CHttpException(400, Yii::t('yii','The CSRF token could not be verified.'));
                    }
                }
            }
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
            $this->enableCrossValidation = $this->validate($event, $event->enableCrossValidation);
            $this->crossDomainName = $event->crossDomainName;
        }
        if ($this->enableCrossValidation && isset($_SERVER['HTTP_ORIGIN'])) {
            if ($this->crossDomainName == '*') {
                header('Access-Control-Allow-Credentials: true');
                header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
            } elseif (is_array($this->crossDomainName) && in_array($_SERVER['HTTP_ORIGIN'], $this->crossDomainName)) {
                header('Access-Control-Allow-Credentials: true');
                header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
            } elseif ( !is_array($this->crossDomainName)) {
                header('Access-Control-Allow-Credentials: true');
                header('Access-Control-Allow-Origin: ' . $this->crossDomainName);
            }
        }
    }
    
    /**
     * 例外的
     * @param unknown $event array(false, 'actionName', 'actionName' => false) 
     * @param unknown $validation
     * @return boolean
     */
    public function validate($event , $validation)
    {
        if (is_array($validation) && in_array($event->getAction()->id, $validation)) {
            return true;
        } elseif (is_array($validation) && isset($validation[$event->getAction()->id])) {
            return !!$validation[$event->getAction()->id];
        } elseif (is_array($validation)) {
            return isset($validation[0]) && ($validation[0] === true || $validation[0] === false) ? $validation[0] : true;
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
            $this->enableHttpsValidation = $this->validate($event, $event->enableHttpsValidation);
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
    
    /**
     * 是否开启 rawBody
     * @param unknown $event
     */
    public function validateRawBody($event)
    {
        if ($this->enableRawBodyValidation && isset($event->enableRawBodyValidation)) {
            $this->enableRawBodyValidation = $this->validate($event, $event->enableRawBodyValidation);
        }
        if ($this->enableRawBodyValidation && $this->getIsPostRequest() && $_POST === array()) {
            $_POST = json_decode($this->getRawBody(), true);
            if (function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc()) {
                $_POST = $this->stripSlashes($_POST);
            }
        }
    }
    
    /**
     * Sends a file to user.
     * @param string $fileName file name
     * @param string $content content to be set.
     * @param string $mimeType mime type of the content. If null, it will be guessed automatically based on the given file name.
     * @param boolean $terminate whether to terminate the current application after calling this method
     */
    public function sendFile($fileName,$content,$mimeType=null,$terminate=true,$file=false)
    {
        if(! $file)
            parent::sendFile($fileName,$content,$mimeType,$terminate);
        else
        {
            if($mimeType===null)
            {
                if(($mimeType=CFileHelper::getMimeTypeByExtension($fileName))===null)
                    $mimeType='text/plain';
            }
            if(file_exists($content))
            {
                $fileSize=filesize($content);
            }
    
            $fp = fopen($content, 'rb');
    
            $contentStart=0;
            $contentEnd=$fileSize-1;
             
            $httpVersion=$this->getHttpVersion();
            if(isset($_SERVER['HTTP_RANGE']))
            {
                header('Accept-Ranges: bytes');
                 
                //client sent us a multibyte range, can not hold this one for now
                if(strpos($_SERVER['HTTP_RANGE'],',')!==false)
                {
                    header("Content-Range: bytes $contentStart-$contentEnd/$fileSize");
                    throw new CHttpException(416,'Requested Range Not Satisfiable');
                }
    
                $range=str_replace('bytes=','',$_SERVER['HTTP_RANGE']);
                 
                //range requests starts from "-", so it means that data must be dumped the end point.
                if($range[0]==='-')
                    $contentStart=$fileSize-substr($range,1);
                else
                {
                    $range=explode('-',$range);
                    $contentStart=$range[0];
                     
                    // check if the last-byte-pos presents in header
                    if((isset($range[1]) && is_numeric($range[1])))
                        $contentEnd=$range[1];
                }
                 
                /* Check the range and make sure it's treated according to the specs.
                 * http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html
                */
                // End bytes can not be larger than $end.
                $contentEnd=($contentEnd > $fileSize) ? $fileSize-1 : $contentEnd;
                 
                // Validate the requested range and return an error if it's not correct.
                $wrongContentStart=($contentStart>$contentEnd || $contentStart>$fileSize-1 || $contentStart<0);
                 
                if($wrongContentStart)
                {
                    header("Content-Range: bytes $contentStart-$contentEnd/$fileSize");
                    throw new CHttpException(416,'Requested Range Not Satisfiable');
                }
                 
                header("HTTP/$httpVersion 206 Partial Content");
                header("Content-Range: bytes $contentStart-$contentEnd/$fileSize");
            }
            else
                header("HTTP/$httpVersion 200 OK");
             
            $length=$contentEnd-$contentStart+1; // Calculate new content length
             
            header('Pragma: public');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header("Content-Type: $mimeType");
            header('Content-Length: '.$length);
            header("Content-Disposition: attachment; filename=\"$fileName\"");
            header('Content-Transfer-Encoding: binary');
            if($contentStart != 0)
                fseek($fp, $contentStart);
            ob_start();
            while(! feof($fp))
            {
                echo fread($fp, 10*1024*1024);
                flush();
                ob_flush();
            }
            if($fp != null)
                fclose($fp);
            if(($contentEnd+1) != $length)
            {
                exit(0);
            }
            if($terminate)
            {
                exit(0);
            }
        }
    }
}