<?php
header('content-type:text/html;charset=utf8');
//echo '<head><meta http-equiv="Content-Type" content="text/html; charset=gb18030" /> </head>';

//----------------------------获取当前客户端IP地址--------------


function get_real_ip(){
    static $realip;
    if(isset($_SERVER)){
        if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
            $realip=$_SERVER['HTTP_X_FORWARDED_FOR'];
        }else if(isset($_SERVER['HTTP_CLIENT_IP'])){
            $realip=$_SERVER['HTTP_CLIENT_IP'];
        }else{
            $realip=$_SERVER['REMOTE_ADDR'];
        }
    }else{
        if(getenv('HTTP_X_FORWARDED_FOR')){
            $realip=getenv('HTTP_X_FORWARDED_FOR');
        }else if(getenv('HTTP_CLIENT_IP')){
            $realip=getenv('HTTP_CLIENT_IP');
        }else{
            $realip=getenv('REMOTE_ADDR');
        }
    }
    return $realip;
}

$ip=get_real_ip();//获取到IP地址

function GetIpLookup($ip = ''){  
    if(empty($ip)){  
        $ip = get_real_ip();
		echo $ip;
    }  
    $res = @file_get_contents('http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=js&ip=' . $ip);  
    if(empty($res)){ return false; }  
    $jsonMatches = array();  
    preg_match('#\{.+?\}#', $res, $jsonMatches);  
    if(!isset($jsonMatches[0])){ return false; }  
    $json = json_decode($jsonMatches[0], true);  
    if(isset($json['ret']) && $json['ret'] == 1){  
        $json['ip'] = $ip;  
        unset($json['ret']);  
    }else{  
        return false;  
    }  
    return $json;  
}  
  
  
$ipInfos = GetIpLookup($ip); //baidu.com IP地址  
//var_dump($ipInfos);
if($ipInfos["province"]!=$ipInfos["city"]){
	$city_name=$ipInfos["province"].' '.$ipInfos["city"];
}else{
	$city_name=$ipInfos["province"];
}
//print_r($city_name);





//--------------------------接收表单数据，验证格式后存进数据库----------------

if($_POST){
    //var_dump($_POST);

    @mysql_connect('localhost','root','$HW.2015$');	//连接数据库
    mysql_select_db('edu_ah_hf');  //关联其中一个数据库
    mysql_query('set names utf8');  //设置字符集
	
    $tel = $_POST['tel'];
    $qq = $_POST['qq'];
    $name = $_POST['name'];
    $course= $_POST['course_name'];
   // $city=$_POST['city_name'];

    $qq_mode='/^\d{5,15}$/';        //匹配qq格式
    $tel_mode = "/^1[34578]\d{9}$/";    //匹配手机号码格式

    if(preg_match($tel_mode,$tel) && preg_match($qq_mode,$qq)){ //验证电话号码

        $sql = "select * from course where p_name='$name' and p_tel='$tel' and p_QQ='$qq' and p_course='$_course' ";
        $data=mysql_query($sql);    //判断接收的数据在数据表中是否存在

        if(!mysql_fetch_assoc($data)){

            $sql="insert into course(p_name,p_tel,p_QQ,p_course,p_city,c_time,IP) values('$name','$tel','$qq','$course','$city_name',now(),'$ip')";
            mysql_query($sql);  //将数据存进数据库

            echo "<script>alert('信息填写成功');
                    window.location ='index.shtml'; 
                </script>";
        }else{
            echo "<script>alert('请勿重复提交');
            window.location ='index.shtml'; </script>";
        }


    }elseif(preg_match($tel_mode,$tel)==1 && preg_match($qq_mode,$qq)==0){

        echo "<script>alert('qq格式有误,请重新填写');
                    window.location ='index.shtml'; 
                </script>";


    }elseif(preg_match($tel_mode,$tel)==0 && preg_match($qq_mode,$qq)==1){

        echo "<script>alert('电话号码格式有误,请重新填写');
                    window.location ='index.shtml'; 
                </script>";


    }else{

        echo "<script>alert('电话号码和QQ格式有误,请重新输入');
                    window.location ='index.shtml'; 
                </script>";

    }

}



?>