<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title></title>
</head>
<body>
<?php 
	if (isset($model->id) && !empty($wxpay))
	{
?>
<script>
		function encodeDashes(str) { // Replace dashes with encoded "\-"
   			return encodeURIComponent(str).replace(/-/g, function(c) { return '%5C%' + c.charCodeAt(0).toString(16).toUpperCase(); });
  		}
        //调用微信JS api 支付
        function jsApiCall()
        {
            WeixinJSBridge.invoke(
                    'getBrandWCPayRequest',
                    <?php echo $wxpay; ?>,
                    function(res){
                        if (res.err_msg == "get_brand_wcpay_request:ok") {
                            // success
                            window.location.replace("<?php echo Yii::app()->params['wx_domain'] . '/#/' . $page . '/' . $model->id;?>");
                        } else {
                            // error or cancle
                            //window.location.replace();
                            //alert(res.err_msg);
                           // alert(res.err_code+res.err_desc+res.err_msg);
                            window.location.replace("<?php echo Yii::app()->params['wx_domain'] . '/#/' . $error; ?>");
                            //WeixinJSBridge.log(res.err_msg);
							//alert(res.err_code+res.err_desc+res.err_msg);                     	
                        }
                    }
            );
        }
        function callpay()
        {
            if (typeof WeixinJSBridge == "undefined"){
                if( document.addEventListener ){
                    document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
                }else if (document.attachEvent){
                    document.attachEvent('WeixinJSBridgeReady', jsApiCall);
                    document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
                }
            }else{
                jsApiCall();
            }
        }
        callpay();
 </script>
 <?php 
	}
 ?>
 </body>
 </html>
