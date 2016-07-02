<script type="text/javascript">
$(function () {
    $('#income').highcharts({
        /*标题*/
        title: {
            text: '我近30天收益趋势图',
            x: -20 //center
        },
        /*小标题*/
        subtitle: {
            text: '来源:www.365tmm.net',
            x: -20
        },
        /*x轴数据*/
        xAxis: {
	    	/*轴标题的显示文本。*/
	        title: {
 	            text: 'D (天)'
	        },
            categories: ['1', '2', '3', '4', '5', '6','7', '8', '9', '10', '11', '12','13', '14', '15', '16', '17', '18','19', '20', '21', '22', '23', '24','25', '26', '27', '28', '29', '30']
         },
        /*y轴数据*/
        yAxis: {
        	/*轴标题的显示文本。*/
            title: {
                text: '￥ (元)'
            },
            /*绘图区域上标记轴*/
            plotLines: [{
                value: 0, /*区域划分线代表的值*/
                width: 1,
                color: '#808080'
            }]
        },
        tooltip: {
            valueSuffix: '元', /*一串字符被后置在每个Y轴的值之后。*/
        },
        /*图例*/
        legend: {
            layout: 'vertical', /*图例数据项的布局。布局类型：水平或垂直。默认是：水平*/
            align: 'right', 
            verticalAlign: 'middle', /*右中*/
            borderWidth: 0     /*图例边框*/
        },
        series: [
            <?php 
                	if(isset($data['income'])){
    					foreach ($data['income'] as $name=>$value)
    						echo "{name:'".$name."',\n data:['".implode("','", $value)."']},\n";
    				}            
           	?>
          ]
    });
    $('#store_order').highcharts({
        /*标题*/
        title: {
            text: '近30天前5位商家下单趋势图',
            x: -20 //center
        },
        /*小标题*/
        subtitle: {
            text: '来源:www.365tmm.net',
            x: -20
        },
        /*x轴数据*/
        xAxis: {
            categories: ['1', '2', '3', '4', '5', '6','7', '8', '9', '10', '11', '12','13', '14', '15', '16', '17', '18','19', '20', '21', '22', '23', '24','25', '26', '27', '28', '29', '30']
        },
        /*y轴数据*/
        yAxis: {
        	/*轴标题的显示文本。*/
            title: {
                text: '数量(个)'
            },
            /*绘图区域上标记轴*/
            plotLines: [{
                value: 0, /*区域划分线代表的值*/
                width: 1,
                color: '#808080'
            }]
        },
        tooltip: {
            valueSuffix: '个' /*一串字符被后置在每个Y轴的值之后。*/
        },
        /*图例*/
        legend: {
            layout: 'vertical', /*图例数据项的布局。布局类型：水平或垂直。默认是：水平*/
            align: 'right', 
            verticalAlign: 'middle', /*右中*/
            borderWidth: 0     /*图例边框*/
        },
        series: [
          <?php 
                if(isset($data['store_order']))
				{
         			foreach ($data['store_order'] as $name=>$value)
         				echo "{name:'".$name."',\n data:['".implode("','", $value)."']},\n";
         		}            
          ?>
        ]
    });
});
</script>

	<!--[if lt IE 10]>
	<style>
	.gradientElement {
		filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#403a39', endColorstr='#fcf1df');
		-ms-filter: "progid:DXImageTransform.Microsoft.gradient(startColorstr='#403a39', endColorstr='#fcf1df')";
	}
	</style>
	<![endif]-->

<div class="content_box">
	<!--登录的信息数据-->
	<div class="login_record_data">		
	 <div class="content_check_pending">
	 	<div class="title">内容待审核</div>
	 	<div class="content">
	 	 <table class="content_table" id="content_check_table">
	 	  <tr class="first_row">
	 		<td>项目</td>
	 		<td><?php echo isset($audit_pending['items'])?$audit_pending['items']:0;?></td>		
	 	  </tr>
	 	  <tr class="second_row">
	 		<td>点</td>
	 		<td><?php echo isset($audit_pending['dot'])?$audit_pending['dot']:0;?></td>		
	 	  </tr>
	 	  <tr class="lastRow">
	 		<td>线</td>
	 		<td><?php echo isset($audit_pending['thrand'])?$audit_pending['thrand']:0;?></td>		
	 	  </tr>
	 	 </table>
	 	</div>
	 </div>
	 <div class="total_income">
	 	<div class="title">收益总额</div>
	 	<div class="content" id="total_income"><?php echo $income;?><span>元</span></div>
	 </div>
	 <div class="login_record">
	 	<div class="title">登录记录</div>
	 	<div class="content">
	 	 <table class="content_table" id="login_record_table">
	 	  <tr class="first_row">
	 		<td >登录次数</td>
	 		<td><?php echo  CHtml::encode($model->count)?></td>		
	 	  </tr>
	 	  <tr class="lastRow">
	 		<td>最后一次登录</td>
	 		<td><?php echo date('Y/m/d',$model->last_time);?><br><?php echo date('H:i:s',$model->last_time) ?></td>		
	 	  </tr>
	 	 </table>
	 	</div>
	 </div>
	</div>  <!--.login_record_data-->

	<!-- 登录信息图表 -->
	<div class="charts">		
		<div class="login_record_chartone">
		 <div class="title">收益趋势</div>
		 <div class="line"></div>
		 <div class="day_income_chart" id="income">
		 	
		 </div>
		</div>		<!--.login_record_chartone-->
		<div class="login_record_charttwo">
		 <div class="title">下单趋势</div>
		 <div class="line"></div>
		 <div class="month_income_chart" id="store_order">		 	
		 </div>
		</div>		<!--.login_record_charttwo-->
		</div>		<!--.charts-->	
	<!--版权 -->
	<div class="copyright">
		<span>Copyright © TMM365.com All Rights Reserved</span>
	</div>		<!--.copyright-->
</div>			<!--.content_box-->
