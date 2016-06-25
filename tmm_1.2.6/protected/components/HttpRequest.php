<?php
/**
 * User: ChangHai Zhan
 * Date: 2015/8/19
 * Time: 14:30
 */
class HttpRequest extends CHttpRequest
{
    /**
     * Initializes the application component.
     * This method overrides the parent implementation by preprocessing
     * the user request data.
     */
    public function init()
    {
        parent::init();
    }

    /**
     * Normalizes the request data.
     * This method strips off slashes in request data if get_magic_quotes_gpc() returns true.
     * It also performs CSRF validation if {@link enableCsrfValidation} is true.
     */
    protected function normalizeRequest()
    {
        // normalize request
        if(function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc())
        {
            if(isset($_GET))
                $_GET=$this->stripSlashes($_GET);
            if(isset($_POST))
                $_POST = $this->stripSlashes($_POST);
            if(isset($_REQUEST))
                $_REQUEST=$this->stripSlashes($_REQUEST);
            if(isset($_COOKIE))
                $_COOKIE=$this->stripSlashes($_COOKIE);
        }
        Yii::app()->attachEventHandler('onBeginRequest',array($this,'getApiPut'));
        if($this->enableCsrfValidation)
            Yii::app()->attachEventHandler('onBeginRequest',array($this,'validateCsrfToken'));
    }
    
    /**
     * 删除 CsrfToken 使其更新
     * @return boolean
     */
    public function unsetCsrfToken()
    {
    	$cookie = $this->getCookies();
    	if(isset($cookie[$this->csrfTokenName]->value))
    		unset($cookie[$this->csrfTokenName]);

    	return true;
    }
   
    /**
     * Initializes the application component.
     * This method overrides the parent implementation by preprocessing
     * the user request data.
     */
    public function getApiPut()
    {
    	if(Yii::app()->params['post_put_ajax'])
    		$ajax=$this->getIsAjaxRequest();
    	else
    		$ajax=true;
	    if($ajax && $this->getRequestType() == 'POST' && isset($_POST) && empty($_POST)) 
	    {
            $post_puts=Yii::app()->params['post_put'];
            if(!empty($post_puts)) 
            {
                foreach ($post_puts as $url)
                {
                    $url = $this->url_str_replace($url);
                    if (preg_match('/' . $url . '.*/', $this->getUrl()))
                    {
                        $_POST=json_decode($this->getRawBody(), true);
                        if(function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc())
                            $_POST = $this->stripSlashes($_POST);
                        break;
                    }
                }
            }	        
    	}
    }

    /**
     * Performs the CSRF validation.
     * This is the event handler responding to {@link CApplication::onBeginRequest}.
     * The default implementation will compare the CSRF token obtained
     * from a cookie and from a POST field. If they are different, a CSRF attack is detected.
     * @param CEvent $event event parameter
     * @throws CHttpException if the validation fails
     */
    public function validateCsrfToken($event)
    {
        if ($this->getIsPostRequest() ||
            $this->getIsPutRequest() ||
            $this->getIsPatchRequest() ||
            $this->getIsDeleteRequest()
        ) {
            $cookies = $this->getCookies();

            $method = $this->getRequestType();
            switch ($method) {
                case 'POST':
                    $userToken = $this->getPost($this->csrfTokenName);
                    break;
                case 'PUT':
                    $userToken = $this->getPut($this->csrfTokenName);
                    break;
                case 'PATCH':
                    $userToken = $this->getPatch($this->csrfTokenName);
                    break;
                case 'DELETE':
                    $userToken = $this->getDelete($this->csrfTokenName);
            }
            if (!empty($userToken) && $cookies->contains($this->csrfTokenName)) {
                $cookieToken = $cookies->itemAt($this->csrfTokenName)->value;
                $valid = $cookieToken === $userToken;
            } else
                $valid = false;
            if (!$valid)
            {
                $csrf_allowed=Yii::app()->params['csrf_allowed'];
                if(empty($csrf_allowed))
                    $this->error_json(Yii::app()->params['csrf_error_json']);
                else
                {
                   	foreach ($csrf_allowed as $url)
                    {
                        $url = $this->url_str_replace($url);
                        if (preg_match('/' . $url . '.*/', $this->getUrl()))
                           return true;
                    }
                    $this->error_json(Yii::app()->params['csrf_error_json']);
                }
              //  throw new CHttpException(400, Yii::t('yii', 'The CSRF token could not be verified.'));        
            }
        }
    }

    /**
     * 判断输出错误类型
     * @param $csrf_error_jsons
     * @throws CHttpException
     */
    private function error_json($csrf_error_jsons)
    {
        if (! empty($csrf_error_jsons)) 
        {
            foreach ($csrf_error_jsons as $csrf_error_json) 
            {
                $csrf_error_json= $this->url_str_replace($csrf_error_json);
                if (preg_match('/^' . $csrf_error_json . '.*/', $this->getUrl()))
                    $this->send_csrf();
            }
         }
         
       	 throw new CHttpException(400, Yii::t('yii', 'The CSRF token could not be verified.'));
    }

    /**
     * 转义
     * @param $url
     * @return mixed
     */
    private function url_str_replace($url)
    {
        $url = Yii::app()->createUrl($url);
        $url = str_replace('.', '\.', $url);
        $url = str_replace('/', '\/', $url);
        $url = str_replace('?', '\?', $url);
        return $url;
    }

    /**
     * 输出错误信息
     */
    private function send_csrf()
    {
        if (isset($_SERVER['HTTP_ORIGIN']))
        {
            header("Access-Control-Allow-Credentials: true");
            header("Access-Control-Allow-Origin: " . $_SERVER['HTTP_ORIGIN']);
        }
        header("Content-type:application/json;");
        $response = array(
            'status' => SYSTEM_FROM_CSRF_ERROR,
            'code' => STATUS_FAIL,
            'msg' => constant(SYSTEM_FROM_CSRF_ERROR . '_MSG'),
            'data' => new stdClass(),
        );
        echo json_encode($response);
        Yii::app()->end();
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