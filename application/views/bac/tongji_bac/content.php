 <div class="row">
 <div class="col-md-8 col-md-offset-2">
 <h2>“"<?php echo $name;?>”统计结果</h2>

      <div class="panel panel-default">
  <!-- Default panel contents -->
  	<div class="panel-heading">基本信息</div>
  	<div class="panel-body">
    <p>
    厂商：<span  class="text-danger"><?php echo $app_list[0]["company"];?></span>；<br />
      总下载：<span  class="text-danger">1888</span>；<br />
      更新时间：<span  class="text-danger"><?php echo $app_list[0]["update_time"];?></span>；
    </p>
  	</div>
	</div>
    
    
    <div class="panel panel-default">
  <!-- Default panel contents -->
  <div class="panel-heading">各个市场下载统计</div>
  <div class="panel-body">
  <table class="table table-striped" >
        <thead>
        <tr>
            <th>名称</th>
            <th>类型</th>
            <th>市场</th>
            <th>下载量</th>
        </tr>
        </thead>
        <tbody>
    <?php foreach ($app_list as $app) {?>
        <tr>
        <td><?php echo $app["name"]?></td>
        <td><?php echo $app["type"]?></td>
        <td><?php echo $app["from_plat"]?></td>
        <td><?php echo $app["download_times"]?></td>
        </tr>
    <?php } ?>
        </tbody>
		</table>
  </div>
</div>

<div class="panel panel-default">
  <!-- Default panel contents -->
  <div class="panel-heading">走势图</div>
  <div class="panel-body">
    <div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
  </div>
</div>

<div class="panel panel-default news">
  <!-- Default panel contents -->
  <div class="panel-heading">相关新闻</div>
  <div class="panel-body">
    <ul class="list-group">
      <li class="list-group-item"><a>货币基金的走势</a></li>
      <li class="list-group-item"><a>赶紧买赶紧买</a></li>
      <li class="list-group-item"><a>财付通理财通财付通理财通</a></li>
      <li class="list-group-item"><a>余额宝真好用真好用</a></li>
      <li class="list-group-item"><a>走起！</a></li>
    </ul>
  </div>
</div>

</div>    <!-- div -->
</div> <!-- row -->

      
      
	<footer>
        <ul>
          <li><a href="">关于我们 </a> | </li>
          <li><a href="http://jos.jd.com/">京东 jos </a> | </li>
          <li><a href="http://zone.jd.com/">京东 code</a> </li>
        </ul>
        <p>&copy;如意搜 沪ICP备12031794号</p>
      </footer>
    </div>

	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
	<script src="http://app.ruyiso.com/resource/js/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
	<script src="http://app.ruyiso.com/resource/js/bootstrap.min.js"></script>
    <script src="http://tongji.ruyiso.com/resource/js/highcharts.js"></script>
	<script type="text/javascript">
    $(function(){
		$('#container').highcharts({
            title: {
                text: '下载统计图',
                x: -20 //center
            },
            subtitle: {
                text: '',
                x: -20
            },
            xAxis: {
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                    'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
            },
            yAxis: {
                title: {
                    text: '下载量'
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            },
            tooltip: {
                valueSuffix: '째C'
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle',
                borderWidth: 0
            },
            series: [{
                name: '余额宝',
                data: [7.0, 6.9, 9.5, 14.5, 18.2, 21.5, 25.2, 26.5, 23.3, 18.3, 13.9, 9.6]
            }, {
                name: '理财通',
                data: [-0.2, 0.8, 5.7, 11.3, 17.0, 22.0, 24.8, 24.1, 20.1, 14.1, 8.6, 2.5]
            }, {
                name: '百付宝',
                data: [-0.9, 0.6, 3.5, 8.4, 13.5, 17.0, 18.6, 17.9, 14.3, 9.0, 3.9, 1.0]
            }, {
                name: '零钱宝',
                data: [3.9, 4.2, 5.7, 8.5, 11.9, 15.2, 17.0, 16.6, 14.2, 10.3, 6.6, 4.8]
            }, {
                name: '网易宝',
                data: [3.9, 4.5, 5.7, 18.5, 11.9, 15.2, 17.0, 16.6, 14.2, 10.3, 6.6, 4.8]
            }]
        });
		
        document.onkeydown = function(e){
            var ev = document.all ? window.event : e;
            if(ev.keyCode==13) {
                   $('#searchBar').submit();//处理事件
             }
        }
        $("#contener").height($(window).height()+800);
		
      });
	  
	  
    </script>
  </body>
</html>
