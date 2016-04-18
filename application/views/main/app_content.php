<!-- Page Content -->
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">

      
    <h3 class="page-header">应用详情</h3>
     
    <div class="searchResultList">
        <table width="100%" border="0">
          <tr>
          <td width="9%" rowspan="2"><img src="<?php echo $app_info["icon"];?>" width="72" height="72" class="sImg" alt="img"></td>
          <td width="77%"><span class="sRTitle c3"> <a href="<?php echo $app_info["download_url"];?>" target="_blank"> <?php echo $app_info["name"];?></a></span> <span class="db sRBtn"> <?php echo $app_info["ori_classes"];?></span></td>
            <td width="8%"><a href="#" class="db sRBtn">更新</a></td>
          </tr>
          <tr> </tr>
        </table>
      </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">简介</div>
                        <div class="panel-body">
                            <?php echo $app_info["brief"];?>
                        </div>
                    </div>
                    
                    <div class="panel panel-default">
                        <div class="panel-heading">当前版本</div>
                        <div class="panel-body">
                            <strong>版本：</strong><span><?php echo $app_info["version"];?></span><br/>
                            <strong>开发者：</strong><span><?php echo $app_info["company"];?></span><br/>
                            <strong>更新时间：</strong><span><?php echo $app_info["update_time"];?></span>
                        </div>
                    </div>
                    
                    <div class="panel panel-default">
                        <div class="panel-heading">排名趋势</div>
                        <div class="panel-body">
                            <div id="trend" style="width:100%;height: 400px; margin: 0 auto"></div>
                        </div>
                    </div>
                    
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->
