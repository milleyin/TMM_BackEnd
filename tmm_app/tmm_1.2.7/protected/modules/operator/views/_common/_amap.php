<?php 
	if(! isset($lng,$lat))
		$lng=$lat=0.000000;
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no, width=device-width">
    <title>项目标记地址</title>
    <?php 
    	$this->addCss('http://cache.amap.com/lbs/static/main1119.css');
    	$this->addJs('http://webapi.amap.com/maps?v=1.3&key='.Yii::app()->params['amap_javascript_key'].'&plugin=AMap.Autocomplete,AMap.PlaceSearch');
    ?>
    <?php 
	/*
		<link rel="stylesheet" href="http://cache.amap.com/lbs/static/main1119.css"/>
    	<script type="text/javascript" src="http://webapi.amap.com/maps?v=1.3&key=<?php echo Yii::app()->params['amap_javascript_key'];?>&plugin=AMap.Autocomplete,AMap.PlaceSearch"></script>
	*/
	?>
</head>
<body>
<div id="container" ></div>
<div id="myPageTop">
    <table>
        <tr>
            <td>
                <label>请输入关键字：</label>
            </td>
        </tr>
        <tr>
            <td>
                <input id="tipinput" placeholder="特发信息港" />
            </td>
        </tr>
    </table>
</div>
<?php 
	//<script type="text/javascript">
	
	$center=($lng==0.000000 && $lat==0.000000)?'':'center:['.$lng.','.$lat.'],';
	$getCenter=($lng==0.000000 && $lat==0.000000)?'map.getCenter()':'['.$lng.','.$lat.']';
$amap=<<<"EOD"
	jQuery(function($){
		map.plugin(["AMap.ToolBar", "AMap.OverView", "AMap.Scale"], function(){
            map.addControl(new AMap.ToolBar);
            map.addControl(new AMap.OverView({isOpen: true}));
            map.addControl(new AMap.Scale);
        });
	});
    var map = new AMap.Map("container", { 
        resizeEnable: true,
        $center
		zoom: 13
    });

	//初始化
	var marker = new AMap.Marker({
		map: map,
        position: $getCenter,
		draggable: true,					//拖动
		//animation:'AMAP_ANIMATION_BOUNCE',//设置点跳动
        //cursor: 'move',
        //raiseOnDrag: true
// 		icon: new AMap.Icon({            
// 			size: new AMap.Size(40, 50),  //图标大小
// 			image: "http://webapi.amap.com/theme/v1.3/images/newpc/way_btn2.png",
// 			imageOffset: new AMap.Pixel(0, -60)
// 		}) 
    });
	//调用
	function callback_function_before(e)
	{
		console.log('before e----',e);
		//console.log('before lng----',e.lnglat.getLng());
		//console.log('before lat----',e.lnglat.getLat());	
		callback_function(e);
    	//console.log('before marker----',marker);
		marker.setPosition(e.lnglat);
    	//console.log('after marker----',marker);
	}

    //初始化 绑定拖事件
	AMap.event.addListener(marker, 'dragend', callback_function_before);
    			
	//点击更新
	map.on('click', function(e) {
        marker.setPosition([e.lnglat.lat, e.lnglat.lng]);
		callback_function_before(e);
    });
    
	//输入提示
    var autoOptions = {
        input: "tipinput"
    };
    var auto = new AMap.Autocomplete(autoOptions);
    var placeSearch = new AMap.PlaceSearch({
        map: map
    }); 
	//构造地点查询类
    AMap.event.addListener(auto, "select", select);//注册监听，当选中某条记录时会触发
    function select(e) {
        placeSearch.setCity(e.poi.adcode);
        placeSearch.search(e.poi.name);  //关键字查询查询
    }
EOD;
//</script>

Yii::app()->clientScript->registerScript('amap',$amap);
?>
</body>
</html>