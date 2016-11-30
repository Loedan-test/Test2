<?php
header('content-type:text/html;charset=utf8');


$user=array(
    array('name'=>'ocpclasssdadmin','pwd'=>'ocpclasssdhwua'),
    );


if($_POST){
    //var_dump($_POST);
    $data['name']=$_POST['name'];
    $data['pwd']=$_POST['pwd'];
    if(in_array($data,$user)) {

        @mysql_connect('localhost', 'root', '');
        mysql_select_db('edu_sd_jn');  //关联其中一个数据库
        mysql_query('set names utf8');  //设置字符集
        $sql = mysql_query("select * from ocpclass");//查询出course表中的所有记录
        $total = mysql_num_rows($sql);//计算记录的总条数
        $size = 15;//设置每页显示5条
        $page_count = ceil($total / $size);//计算出总共的页数,用ceil将商数向上取证
        $current_page = 1;//设置当前页面默认为第一页

//-----------分页显示-----------
       
        if (@$_GET['page'] <= 1) {
            $current_page = 1;
        } elseif (@$_GET['page'] > $page_count) {
            $current_page = $page_count;
        } else {
            $current_page = $_GET['page'];
        }
        $id = ($current_page - 1) * $size;
        $sql = "select * from ocpclass  limit $id,$size";   //从数据表中取出数据
        $data = mysql_query($sql);
        // var_dump($data);
        while ($rows = mysql_fetch_assoc($data)) {
            $res[] = $rows;        //取出表中的数据放在一个数组中
        }
      //var_dump($res);
//-----------------------------

    }else{

        echo "<script>alert('账号或密码错误,请重新输入');
                    window.location ='index.html';
                </script>";
   }
}elseif($_GET){

    @mysql_connect('localhost', 'root', '');
    mysql_select_db('edu_sd_jn');  //关联其中一个数据库
    mysql_query('set names utf8');  //设置字符集
    $sql = mysql_query("select * from ocpclass");//查询出course表中的所有记录
    $total = mysql_num_rows($sql);//计算记录的总条数
    $size = 15;//设置每页显示5条
    $page_count = ceil($total / $size);//计算出总共的页数,用ceil将商数向上取证
    $current_page = 1;//设置当前页面默认为第一页

    if (@$_GET['page'] <= 1) {
        $current_page = 1;
    } elseif (@$_GET['page'] > $page_count) {
        $current_page = $page_count;
    } else {
        $current_page = $_GET['page'];
    }
    $id = ($current_page - 1) * $size;
    $sql = "select * from ocpclass  limit $id,$size";   //从数据表中取出数据
    $data = mysql_query($sql);
    // var_dump($data);
    while ($rows = mysql_fetch_assoc($data)) {
        $res[] = $rows;        //取出表中的数据放在一个数组中
    }
}else{
    echo "<script>
                    window.location ='index.html';
                </script>";
}



?>


<style type="text/css">
    table{
        width: 1000px;
        margin: 30px auto;
    }
    h1{

        width:168px;
        margin: 30px auto;
    }
    td{
        text-align: center;
    }
    #title{
        background: rgba(45, 97, 105, 0.68);
    }

    #data{
        
    }
    .page a{
	text-decoration:none;
	color:#000;
	font-weight:bold;
    }
    .page a:hover{
	text-decoration:none;
	color:#e50011;
	font-weight:bold;
    }
</style>


<h1>提交者列表</h1>
    <table border="5">
        <tr id="title">
            <td width="7%">编号</td>
            <td width="10%">姓名</td>
            <td width="13%">电话</td>
            <td width="15%">QQ</td>
            <td width="10%">意向课程</td>
            <td width="18%">提交时间</td>
            <td width="15%">IP地址</td>
	        <td width="12%">城市</td>
        </tr>
        <?php if(!empty($res)):?>
            <?php foreach($res as $k=>$v):?>
                <tr id="data">
                    <td width="7%"><?php echo $id + $k + 1; ?></td>
                    <td width="10%"><?php echo $v['p_name'];?></td>
                    <td width="13%"><?php echo $v['p_tel'];?></td>
                    <td width="15%"><?php echo $v['p_email'];?></td>
                    <td width="10%"><?php echo $v['p_course'];?></td>
                    <td width="18%"><?php echo $v['c_time'];?></td>
                    <td width="15%"><?php echo $v['IP'];?></td>
		            <td width="12%"><?php echo $v['p_city'];?></td>
                </tr>
            <?php endforeach;?>
        <?php endif;?>
    </table>
<!------------------------------------------------------------分页按钮部分--------------------------------------------------------->
<div align=center class="page">
    <a href="datalist.php?page=1">首页</a>
    <a href="datalist.php?page=<?php echo $current_page-1;?>">上一页</a>
    <a href="datalist.php?page=<?php echo $current_page+1;?>">下一页</a>
    <a href="datalist.php?page=<?php echo $page_count;?>">尾页</a>
</div>


