
</div>
<!-- /#wrapper --> 

<!-- footer 开始 -->
    <div class="footer">
        <h5 class="copy_link"><a href="http://blog.appbk.com/about/" target="_blank">关于我们</a> <a href="http://blog.appbk.com/" target="_blank" >团队博客</a> 用户Q群:<a href="http://jq.qq.com/?_wv=1027&amp;k=KcLnh5" target="_blank">39351116</a></h5>
</h6>       Copyright © 2014-2015 应用宝库 版权所有　沪ICP备12031794号
<script type="text/javascript">var cnzz_protocol = (("https:" == document.location.protocol) ? " https://" : " http://");document.write(unescape("%3Cspan id='cnzz_stat_icon_1253052544'%3E%3C/span%3E%3Cscript src='" + cnzz_protocol + "s19.cnzz.com/z_stat.php%3Fid%3D1253052544%26show%3Dpic1' type='text/javascript'%3E%3C/script%3E"));</script> </h6>
    </div>
    <!-- ./footer始 -->

    <!-- jQuery -->
    <script src="http://cdn.appbk.com/js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="http://cdn.appbk.com/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="http://cdn.appbk.com/js/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="http://cdn.appbk.com/js/sb-admin-2.js"></script>
   
    <!-- highcharts JavaScript -->
    <script src="http://cdn.appbk.com/js/highcharts.js"></script> 
    
    <!-- 处理nav点击变化背景色 -->
    <script type="text/javascript">
    
        <?php if ( isset($_REQUEST["nav"]) ) { ?>
            nav_select = '<?php echo "#" . $_REQUEST["nav"] ?>';
        <?php } else {  ?>
            nav_select = '<?php echo "#app"?>';
        <?php }  ?>
        $(nav_select).css("background-color","#e0e0e0"); 
    
       $(function(){
        $('#trend').highcharts(<?php echo $trend;?>); 
       }); 
  </script>

    
</body>
</html>

