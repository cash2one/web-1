        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">   

   <h3 class="page-header">用户兴趣标签</h3>
              <table width="100%" border="0" class="customers">
                <tr>
                  <th width="9%">序号</th>
                  <th>兴趣标签</th>
                  <th title="搜索指数反映每天搜索
的次数多少">覆盖用户数</span></th>
                  <th>推荐度</th>
                </tr>
                <?php $index = 0 ?>
                <?php foreach ($tags as $item) { ?>
                    <tr>
                    <?php $index = $index + 1 ?>
                    <td>
                        <?php  echo $index ?>
                    </td>
                    <td>
                        <?php  echo $item["tag"] ?>
                    </td>
                    <td>
                        <?php  echo $item["weight_max"] ?>
                    </td>
                    <td>
                        <?php  echo $item["score"]  ?>
                    </td>
                    </tr>
                <?php } ?>
              </table>
                        </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->
