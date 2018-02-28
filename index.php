<?php
session_start();
date_default_timezone_set('PRC'); 
$user_name=$_POST['user_name'];
$user_pswd=$_POST['user_pswd_hidden'];
include("thinkphp/Extend/Library/ORG/Util/GuestInfo.class.php");
if($user_name){
    if(date("Y-m-d")>'3018-04-20'){
    $user_name=null;
    $user_pswd=null;
    }else{
        $select="select * from user where user_name='".$user_name."'";
        $db= mysql_connect('localhost','root','mkdq');
        mysql_query("set names 'utf8'"); 
        mysql_select_db('ok_erp',$db);
        $result=mysql_query($select,$db);
        $array=mysql_fetch_array($result);
        if($array&&$array['user_pswd']==sha1($user_pswd."mkserver")){
            $keys=array_keys($array);
            for ($i=1;$i<count($keys);$i+=2){
                $_SESSION[$keys[$i]]=$array[$keys[$i]];
            }
            $guestinfo=new GuestInfo();
            $userinfo['name']=$array['name'];
            $userinfo['user_name']=$array['user_name'];
            $userinfo['user_os']=$guestinfo->GetOS();
            $userinfo['user_browser']= $guestinfo->GetBrowser();
            $userinfo['user_ip']= $guestinfo->GetIp();
            $userinfo['user_lang']=$guestinfo->GetLang();
            $userinfo['load_date']=date('Y-m-d H:i:s');
            $insert=sprintf("insert into userload (name,user_name,user_os,user_browser,user_ip,user_lang,load_date) values('%s','%s','%s','%s','%s','%s','%s')",$userinfo['name'],$userinfo['user_name'],$userinfo['user_os'],$userinfo['user_browser'],$userinfo['user_ip'],$userinfo['user_lang'],$userinfo['load_date']);
            mysql_query($insert,$db);
            switch ($array['user_group']){
                case "sale":
                    header("Location: http://".$_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT']."/ok_erp/index.php/Claim/NewClaim");
                    break;
                case "finance":
                    header("Location: http://".$_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT']."/ok_erp/finance_index.php/Claim/selectClaim");
                    break;
                case "factory":
                    header("Location: http://".$_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT']."/ok_erp/warehouse_index.php/Logistics/InStorage");
                    break;
                case "warehouse":
                    header("Location: http://".$_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT']."/ok_erp/warehouse_index.php/BarCode/PurchaseOrder");
                    break;
                case "cust":
                    header("Location: http://".$_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT']."/ok_erp/warehouse_index.php/SxmkStorage/SxmkStorage");
                    break;
                case "admin":
                    header("Location: http://".$_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT']."/ok_erp/admin_index.php/Index/Index");
                    break;
                case "phone":
                    header("Location: http://".$_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT']."/ok_erp/phone_index.php/Stock/SelectStock");
                    break;
                default :
                    echo "非法进入";
                    break;
            }
        }
    }
    
}

?>
<html>
<head>
<meta  http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
<title>系统登录界面</title>
<style>
body
{
	background-color: #f6f6f6;

	margin: 2px 0 0 0;
	padding: 0px;
	font-family: 'Open Sans', sans-serif;
	font-size: 11pt;
}
h3
{
	font-size: 1.4em;
	color: #555;
}
form
{
	margin-top:200px;
}
</style>
<script type="text/javascript" src="/ok_erp/Tpl/Public/js/md5.js"></script> 
 <script type="text/javascript">   
    //js实现在一个输入框按回车键后光标跳到下一个输入框  
    function focusNextInput(thisInput)  
    {   
        var inputs = document.getElementsByTagName("input");   
        for(var i = 0;i<inputs.length;i++){   
            // 如果是最后一个，则焦点回到第一个   
            if(i==(inputs.length-1)){   
                inputs[0].focus(); break;   
            }else if(thisInput == inputs[i]){   
                inputs[i+1].focus(); break;   
            }   
        }   
    }   
//jqueryMD5加密
    function md5submit(){
    	var pswd=document.getElementById("user_pswd").value;
    	document.getElementById('user_pswd_hidden').value=hex_md5(pswd);
    	form1.submit();   	
    }
    

</script> 

</head>
<body >
<center>

<form method="post" action="" class="form" name="form1" id="form1" onkeydown="if(event.keyCode==13){return false;}">
<h3>登录</h3>
<input type="hidden" id="user_pswd_hidden" name="user_pswd_hidden">
帐号:<input type="text" name="user_name" id="user_name"  onkeydown="if(event.keyCode==13) focusNextInput(this);"/><br /><br />
密码:<input type="password" name="user_pswd" id="user_pswd" onkeydown="if(event.keyCode==13) md5submit();" /><br />

</form>
<button onclick="md5submit()">登录</button>
</center>
<script type="text/javascript">
document.getElementById('user_name').focus();
</script>
</body>

