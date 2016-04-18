        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">      

    <h3 class="page-header">关键词检测<small> (对您填写的关键进行检测，无数据的关键词，将会在后台下载，约五分钟内完成)</small></h3>
              
            <table width="100%" border="0" class="customers">
                <tr>
                  <th width="9%">序号</th>
                  <th>搜索词</th>
                  <th title="搜索热度反映每天搜索
的次数多少">搜索热度 <span class="glyphicon glyphicon-question-sign text-info"></span></th>
                  <th>搜索结果数</th>
                  <th>第1名APP</th>
                  <th>来源</th>
                </tr>
                <?php $i = $start ; foreach ($word_info as $item) { ?>
                <tr>
                  <td><h3><?php echo $i; ?></h3></td>
                  <td><?php echo $item["word"] ?></td>
                  <td><?php echo $item["rank"] ?></td>
                  <td><?php echo $item["num"] ?></td>
                  <td><span class="c2"><?php echo $item["name"] ?></span></td>
                  <td><span class="c2"><?php echo $item["user_word_type"] ?></span></td> 
                 </tr>
                <?php $i++;} ?>
              </table>
        
            <!-- 翻页 -->
            <span class="loadMore db">
                <?php echo $turn_page ?>
            </span>
            <!--/ 翻页 -->


                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->
