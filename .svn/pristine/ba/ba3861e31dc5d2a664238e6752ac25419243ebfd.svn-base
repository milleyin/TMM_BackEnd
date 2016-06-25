<!doctype html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0">
	<title>Document</title>
</head>
<body>

	<h3 id="menu-share">分享接口</h3>
	<span class="desc">获取“分享到朋友圈”按钮点击状态及自定义分享内容接口</span>
	<button class="btn btn_primary" id="onMenuShareTimeline">onMenuShareTimeline</button>
	<span class="desc">获取“分享给朋友”按钮点击状态及自定义分享内容接口</span>
	<button class="btn btn_primary" id="onMenuShareAppMessage">onMenuShareAppMessage</button>
	<span class="desc">获取“分享到QQ”按钮点击状态及自定义分享内容接口</span>
	<button class="btn btn_primary" id="onMenuShareQQ">onMenuShareQQ</button>
	<span class="desc">获取“分享到腾讯微博”按钮点击状态及自定义分享内容接口</span>
	<button class="btn btn_primary" id="onMenuShareWeibo">onMenuShareWeibo</button>
	<span class="desc">获取“分享到QZone”按钮点击状态及自定义分享内容接口</span>
	<button class="btn btn_primary" id="onMenuShareQZone">onMenuShareQZone</button>

	<h3 id="menu-location">地理位置接口</h3>
	<span class="desc">使用微信内置地图查看位置接口</span>
	<button class="btn btn_primary" id="openLocation">openLocation</button>
	<span class="desc">获取地理位置接口</span>
	<button class="btn btn_primary" id="getLocation">getLocation</button>
</body>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script>
	wx.config({
		debug: true,
		appId: '<?php echo $signPackage["appId"];?>',
		timestamp: <?php echo $signPackage["timestamp"];?>,
		nonceStr: '<?php echo $signPackage["nonceStr"];?>',
		signature: '<?php echo $signPackage["signature"];?>',
		jsApiList: [
			'checkJsApi',
			'onMenuShareTimeline',
			'onMenuShareAppMessage',
			'onMenuShareQQ',
			'onMenuShareWeibo',
			'onMenuShareQZone',
			'hideMenuItems',
			'showMenuItems',
			'hideAllNonBaseMenuItem',
			'showAllNonBaseMenuItem',
			'translateVoice',
			'startRecord',
			'stopRecord',
			'onVoiceRecordEnd',
			'playVoice',
			'onVoicePlayEnd',
			'pauseVoice',
			'stopVoice',
			'uploadVoice',
			'downloadVoice',
			'chooseImage',
			'previewImage',
			'uploadImage',
			'downloadImage',
			'getNetworkType',
			'openLocation',
			'getLocation',
			'hideOptionMenu',
			'showOptionMenu',
			'closeWindow',
			'scanQRCode',
			'chooseWXPay',
			'openProductSpecificView',
			'addCard',
			'chooseCard',
			'openCard'
		]
	});


	// 7 地理位置接口
	// 7.1 查看地理位置
	document.querySelector('#openLocation').onclick = function () {
		wx.openLocation({
			latitude: 22.55309,
			longitude: 113.9446,
			name: 'TIT 创意园',
			address: '广州市海珠区新港中路 397 号',
			scale: 14,
			infoUrl: 'http://weixin.qq.com'
		});
	};

	// 7.2 获取当前地理位置
	document.querySelector('#getLocation').onclick = function () {
		wx.getLocation({
			success: function (res) {
				alert(JSON.stringify(res));
			},
			cancel: function (res) {
				alert('用户拒绝授权获取地理位置');
			}
		});
	};



</script>
</html>