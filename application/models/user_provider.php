<?php

//sina weibo api
include_once( 'resource/weibo_api/config.php' );
include_once( 'resource/weibo_api/saetv2.ex.class.php' );

//用户相关的model
class User_provider extends CI_Model {
    public function __construct()
    {
        $this->load->database();
        $this->db2 = $this->load->database('user', TRUE); //用户相关的数据，需要读写库
    }

    
    #获得weibo登录的url
    public function get_weibo_login_url()
    {
        $o = new SaeTOAuthV2( WB_AKEY , WB_SKEY );
        $code_url = $o->getAuthorizeURL(WB_CALLBACK_URL);
        return $code_url;
    }

    //微博用户登录检测，登录信息写入数据库
    public function weibo_user_login($code)
    {
        //step 1, 获取微博用户的uid
        $user_info = $this->get_weibo_user_info($code);
        $uid = $user_info["id"];

        //step 2, 查看是否已经存在
        $email = $uid . "@weibo.com";
        $password = $uid;
        $nickname = $user_info["screen_name"];
        
        $sql = "select count(*) as num from member where email='$email'";
        $result = $this->db->query($sql)->result_array();
        $result_number = $result[0]["num"];
        
        if ( 0 == $result_number ) //如果email没注册过
        {
            $data["email"] = $email;
            $data["password"] = $password;
            $data["nickname"] = $nickname;
            //注册账号，并写session
            $this->reg_user($data);
        }
        else //如果已经登录过
        {
            $token = $this->write_user_login_info($email);
            return array("status"=>0, "token"=>$token);
        }
    }

    //获得weibo用户的信息
    public function get_weibo_user_info($code)
    {
        $o = new SaeTOAuthV2( WB_AKEY , WB_SKEY );
        $code_url = $o->getAuthorizeURL( WB_CALLBACK_URL );
        
        #step 1, get token
        $o = new SaeTOAuthV2( WB_AKEY , WB_SKEY );
        $keys = array();
        $keys['code'] = $code;
        $keys['redirect_uri'] = WB_CALLBACK_URL;
        $token = $o->getAccessToken( 'code', $keys ) ;

        #step 2, get uid
        $c = new SaeTClientV2( WB_AKEY , WB_SKEY , $token['access_token'] );
        $uid_get = $c->get_uid();
        $uid = $uid_get['uid']; //当前用户的uid
        $user_info = $c->show_user_by_id($uid);
        return $user_info;
    }

    //注册用户
    public function reg_user($data)
    {
        $email = $data["email"];
        if ( strlen($data["password"]) < 2 )
        {
            return array("status"=>-1, "message"=>"password too short");
        }

        $password = md5($data["password"]); //md5加密

        //如果没有设置nickname，则取email中@前的部分
        if ( !isset($data["nickname"]) )
        {
            $item = explode("@", $email);
            $nickname = $item[0];
        }
        else
        {
            $nickname = $data["nickname"];
        }

        $sql = "replace into member (`email`,`password`,`regdate`,`nickname`) 
            values ('$email','$password',now(),'$nickname')";
        $result = $this->db2->query($sql);
        
        //为用户添加一个默认的app，"天天飞车" app_id=728200220
        $app_id = "728200220";
        $CI = get_instance();
        $CI->load->model('user_app_provider');
        $CI->user_app_provider->add_user_app($email, $app_id);

        /*
        //为用户添加一个iTunes关键词
        $itunes_word = "飞车";
        $CI->user_app_provider->add_user_app_words($email, $app_id, $itunes_word);

        //为用户添加一个期望填写的关键词
        $wish_word = "赛车";
        $CI->user_app_provider->add_user_app_words($email, $app_id, $wish_word);
        */

        //记录
        $token = $this->write_user_login_info($email);
        return array("status"=>0, "token"=>$token);
    }


    //将用户登录信息写入数据库,返回一个token
    public function write_user_login_info($email)
    {
        $salt = "appbk.com";
        $cur_time = (string)time();
        $token = md5($email.$salt.$cur_time);
        $ip = $this->input->ip_address();
        //插入数据库
        $sql = "insert into member_token (email,token,login_time,ip)
                values ('$email', '$token', now(), '$ip')";
        $this->db2->query($sql);
        return $token; 
    }

    
    //restful调用中检查用户是否已经登录,并且调用的账号和登录账号一致
    //如果不一致，跳转到错误页面,输出一个展示错误的json数据后续设计检测token来验证
    //注： admin账号58100533@qq.com不需要检测登录
    public function check_login_restful($email)
    {
        //admin账号，不需要登录
        if ($email == "58100533@qq.com")
        {
            return 0;
        }

        $token = "";
        if ( isset($_REQUEST["token"])  )//如果没输入$token，检测cookie是
        {
            $token = $_REQUEST["token"];
        }
        else
        {
            if ( isset($_COOKIE["token"])  )
            {
                $token = $_COOKIE["token"];
            }
        }

        $error_url = base_url() . "rest/error?ec=-2&ei=no_log_in";
        if ( ""==$token )
        {
            header("location:$error_url");//如果没token，发错误信息
        }
        else
        {
            //检查token
            $sql = "select count(*) as num from member_token where email='$email' and token='$token'";
            $result = $this->db2->query($sql)->result_array();
            $result_number = (int)$result[0]["num"]; 
            if ( 0 == $result_number )
            {
                //错误,没有颁发过次token
                header("location:$error_url");//转到错误页面，发错误信息
            }
            else
            {
                return 0;
            }

        } 
    }  

    //判断用户是否登录，返回用户信息email，否则，返回空字符串
    public function get_login_user_email()
    {
        $email = $this->session->userdata('email');
        
        if ( $email ) //如果session设置了email,返回用户信息
        {
            $user_info = $this->get_user_info($email);
            return $user_info["email"];
        }
        else //否则返回一个空字符串
        {
            return "";
        }
    }
    
    //根据mail，获得用户信息
    public function get_user_info($email)
    {
        $sql = "select email,nickname,level_name,member.level,level_icon from member 
            left join member_level
            on member.level=member_level.level
            where email='$email'";
        $result = $this->db->query($sql)->result_array();
        return $result[0];
    }

    //检查用户注册的输入
    //input : 用户输入的数据
    //return : 正确，返回"0"，else，返回错误信息文本
    public function check_user_register_input($email)
    {
        //step 1,检测email是否符合规范,bootstrap已经检测
        //step 2，检测两次输入的密码是否一致,js检查
        //step 3，检测email是否已经存在
        $sql = "select count(*) as num from member where email='$email'";
        $result = $this->db2->query($sql)->result_array();
        $result_number = $result[0]["num"];
        if ( 0 != $result_number )
        {
            //如果错误，返回一个错误信息
            $error = "该Email已经注册，请输入新的Email，或者直接登录 ";
            return array("status"=>-1, "message"=>$error);
        }
        else
        {
            return array("status"=>0, "message"=>"success");
        }
    }

    //检查用户登陆的输入
    //input : 用户输入的数据
    //return : 正确，返回一个token，同时数据库写入用户登录信息，else，返回错误信息文本
    public function check_user_login_input($email, $password)
    {
        //step 1，检测帐号或者密码是否正确
        $password = md5($password); //md5加密
        $sql = "select count(*) as num from member where
            email='$email' and password='$password'";
        $result = $this->db2->query($sql)->result_array();
        $result_number = $result[0]["num"];
        if ( 0 == $result_number )
        {
            //如果错误，返回一个错误信息
            $error = "帐号或密码错误，请重新输入,如仍有错误，请联系q群:39351116";
            return array("status"=>-2, "message"=>$error);
        } 
        else
        {
            $token = $this->write_user_login_info($email);
            $expire_time = date("Y-m-d H:i:s",time() + 30*24*60*60);//过期时间，一个月后
            return array("status"=>0, "token"=>$token, "expire"=>$expire_time);
        }
    }

    //短信验证,给某个手机号码发送验证短信
    //暂时不进行用户账号验证,注册/修改密码 时也可使用
    public function request_sms_code($phone_num)
    {
        $ch = curl_init();
        $url = 'https://api.leancloud.cn/1.1/requestSmsCode';
        $header = array(
            'X-LC-Id: wdVkR3HBdEm5JuxvUwx7a5ye',
            'X-LC-Key: 9F3g8WJXJaPlmGJXxBVU8BgV',
            'Content-Type: application/json'
        );
        $post_data = array("mobilePhoneNumber"=>$phone_num);
        $post_date_string = json_encode($post_data);
        // 添加apikey到header
        curl_setopt($ch, CURLOPT_HTTPHEADER  , $header);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_date_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // 执行HTTP请求
        curl_setopt($ch , CURLOPT_URL , $url);
        $res = curl_exec($ch);


        $res = "{}";
        $return_result =  json_decode($res,true);
        if (empty($return_result))//如果为空,表示正确
        {
            $result = array("status"=>200,"message"=>"send message success");
        }
        else //如果错误
        {
            $result = array("status"=>-1,"message"=>"error,code error".$res);
        }
        return $result;
    }

    //验证收到的 6 位数字验证码是否正确
    public function verify_sms_code($email, $phone_num, $code)
    {
        $ch = curl_init();
        $url = "https://api.leancloud.cn/1.1/verifySmsCode/$code?mobilePhoneNumber=$phone_num";
        $header = array(
            'X-LC-Id: wdVkR3HBdEm5JuxvUwx7a5ye',
            'X-LC-Key: 9F3g8WJXJaPlmGJXxBVU8BgV',
            'Content-Type: application/json'
        );
        //echo $url;
        //$post_data = array("mobilePhoneNumber"=>$phone_num);
        //$post_date_string = json_encode($post_data);
        $post_date_string = "";
        // 添加apikey到header
        curl_setopt($ch, CURLOPT_HTTPHEADER  , $header);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_date_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // 执行HTTP请求
        curl_setopt($ch , CURLOPT_URL , $url);
        $res = curl_exec($ch);
        $result = json_decode($res,true);
        if (empty($result))//如果为空,表示正确
        {
            //更新用户数据库的电话号码
            $sql = "update member set phone_num='$phone_num'
                    where email='$email'";
            $result = $this->db2->query($sql);
            $result = array("status"=>200,"message"=>"verify success,update phone num");
        }
        else //如果错误
        {
            $result = array("status"=>-1,"message"=>"error,code error".$res);
        }
        return $result;
    }
}

?>
