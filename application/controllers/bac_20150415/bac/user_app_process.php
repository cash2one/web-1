<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//用户app的管理
class User_app_process extends CI_Controller {

    //用户关键词管理
    public function keywords_manage()
    {
        //登录检测
        $data["email"] = $this->user_provider->check_login(); 
                //获得用户信息
        $data["user"] = $this->user_provider->get_login_user_info();
        
        if ( isset($_REQUEST["app_id"] ) )
        {
            $app_id = $_REQUEST["app_id"];
        }
        else
        {
            $app_id = "653350791"; //默认一个id
        }


        //获得app信息
        $data["app_info"] = $this->user_app_provider->get_app_info($app_id);
         
        /*
        //系统推测的app关键词
        $data["predict_word"] = $this->user_app_provider->
            get_app_predict_word($data["app_info"]["name"]);
         */

        //用户填写的itunes关键词
        $data["itunes_word"] = $this->user_app_provider->get_app_itunes_word($data["email"], $app_id);
        //用户填写的，未来期望添加的关键词
        $data["wish_word"] = $this->user_app_provider->
            get_app_wish_word($data["email"], $app_id);
        
        $this->load->view('member/header_nav', $data);
        $this->load->view('member/app_process_keywords_manage', $data);
        $this->load->view('member/footer');
    }

    //更新用户填写itunes关键词
    public function update_app_itunes_word()
    {
        //登录检测
        $data["email"] = $this->user_provider->check_login();

        if ( isset($_REQUEST["app_id"] ) )
        {
            $app_id = $_REQUEST["app_id"];
        }
        else
        {
            $app_id = "653350791"; //默认一个id
        }
        
        if ( isset($_REQUEST["itunes_word_list"] ) )
        {
            $itunes_word_list = $_REQUEST["itunes_word_list"];
        }
        
        //获得app信息
        $data["app_info"] = $this->user_app_provider->get_app_info($app_id);
        //更新用户关键词
        $this->user_app_provider->
            update_app_itunes_word($data["email"], $app_id, $itunes_word_list); 
        $url = base_url() . "user_app_process/keywords_manage?app_id=" . $app_id;
        header("location:$url");
    }
    
    //更新用户期望关键词
    public function update_app_wish_word()
    {
        //登录检测
        $data["email"] = $this->user_provider->check_login();

        if ( isset($_REQUEST["app_id"] ) )
        {
            $app_id = $_REQUEST["app_id"];
        }
        else
        {
            $app_id = "653350791"; //默认一个id
        }

            if ( isset($_REQUEST["wish_word_list"] ) )
        {
            $itunes_word_list = $_REQUEST["wish_word_list"];
        }

        //获得app信息
        $data["app_info"] = $this->user_app_provider->get_app_info($app_id);
        //更新用户关键词
        $this->user_app_provider->
            update_app_wish_word($data["email"], $app_id, $itunes_word_list);
        
        $url = base_url() . "user_app_process/keywords_manage?app_id=" . $app_id;
        header("location:$url");
    } 
    
    //用户现有关键词的特征指标
    public function keywords_optimal($start=0)
    {    
        //登录检测
        $data["email"] = $this->user_provider->check_login(); 
                //获得用户信息
        $data["user"] = $this->user_provider->get_login_user_info();
       
        if ( isset($_REQUEST["app_id"] ) )
        {
            $app_id = $_REQUEST["app_id"];
        }
        else
        {
            $app_id = "917670924"; //默认一个id
        }
        //根据id获得app信息
        $data["app_info"] = $this->user_app_provider->get_app_info($app_id);

        //获得用户填写关键词的热度和搜索结果数指标
        $data["word_info"] = $this->user_app_optimal_provider
            ->get_word_rank_and_num($data["email"], $data["app_info"], $start);

        //翻页 
        $record_num = $this->user_app_optimal_provider
            ->get_word_rank_and_num_num($data["email"], $data["app_info"]);
        
        $data["start"] = $start + 1;
        $this->load->library('pagination');
        $config['uri_segment'] = 3; //翻页是第几个参数
        $config['base_url'] = base_url()."user_app_process/keywords_optimal";
        $config['total_rows'] = $record_num;
        $config['per_page'] = '10';
        $config['full_tag_open'] = '<p>';
        $config['num_links'] = 10;
        $config['full_tag_close'] = '</p>';
        $config['first_link'] = '首页';
        $config['last_link'] = '最后一页';
        $config['next_link'] = '&gt;下一页';
        $config['prev_link'] = '上一页&lt;';
        $config ['suffix'] = "?app_id=".$app_id . "&nav=" . (isset($_REQUEST["nav"])?$_REQUEST["nav"]:"");
        $config['first_url'] = base_url()."user_app_process/keywords_optimal/?app_id=".$app_id
                             . "&nav=" . (isset($_REQUEST["nav"])?$_REQUEST["nav"]:"");
        $this->pagination->initialize($config);
        $data['turn_page'] = $this->pagination->create_links();        

        $this->load->view('member/header_nav', $data);
        $this->load->view('member/app_process_keywords_optimal', $data);
        $this->load->view('member/footer');
    }

    //为用户推荐新的关键词
    public function keywords_recommend($start=0)
    {
        //登录检测
        $data["email"] = $this->user_provider->check_login();
                //获得用户信息
        $data["user"] = $this->user_provider->get_login_user_info();

        if ( isset($_REQUEST["app_id"] ) )
        {
            $app_id = $_REQUEST["app_id"];
        }
        else
        {
            $app_id = "917670924"; //默认一个id
        }   

        //根据id获得app信息
        $data["app_info"] = $this->user_app_provider->get_app_info($app_id); 
        
        //为用户推荐新的关键词
        $data["word_info"] = $this->user_app_optimal_provider
            ->get_recommend_word($data["email"], $app_id, $data["app_info"]["name"], $start);
        
        $record_num = $this->user_app_optimal_provider
            ->get_recommend_word_num($data["email"], $app_id, $data["app_info"]["name"]);;
       
        //翻页 
        $data["start"] = $start + 1;
        $this->load->library('pagination');
        $config['uri_segment'] = 3; //翻页是第几个参数
        $config['base_url'] = base_url()."user_app_process/keywords_recommend";
        $config['total_rows'] = $record_num;
        $config['per_page'] = '10';
        $config['full_tag_open'] = '<p>';
        $config['num_links'] = 10;
        $config['full_tag_close'] = '</p>';
        $config['first_link'] = '首页';
        $config['last_link'] = '最后一页';
        $config['next_link'] = '&gt;下一页';
        $config['prev_link'] = '上一页&lt;';
        $config ['suffix'] = "?app_id=".$app_id . "&nav=" . (isset($_REQUEST["nav"])?$_REQUEST["nav"]:"");
        $config['first_url'] = base_url()."user_app_process/keywords_recommend/?app_id=".$app_id
                             . "&nav=" . (isset($_REQUEST["nav"])?$_REQUEST["nav"]:"");
        $this->pagination->initialize($config);
        $data['turn_page'] = $this->pagination->create_links();

         
        $this->load->view('member/header_nav', $data);
        $this->load->view('member/app_process_keywords_recommend', $data);
        $this->load->view('member/footer');
    }

    //相关app推荐
    public function app_recommend($start=0)
    {
        //登录检测
        $data["email"] = $this->user_provider->check_login();
        
                //获得用户信息
        $data["user"] = $this->user_provider->get_login_user_info();

        if ( isset($_REQUEST["app_id"] ) )
        {
            $app_id = $_REQUEST["app_id"];
        }
        else
        {
            $app_id = "917670924"; //默认一个id
        }

        //根据id获得app信息
        $data["app_info"] = $this->user_app_provider->get_app_info($app_id);

        //为用户推荐相关app
        $data["app_list"] = $this->user_app_optimal_provider
            ->get_search_sim_app($data["app_info"]["name"], $start);
        
        //翻页 
        $record_num = $this->user_app_optimal_provider
            ->get_search_sim_app_num($data["app_info"]["name"]);
        $data["start"] = $start + 1;
        $this->load->library('pagination');
        $config['uri_segment'] = 3; //翻页是第几个参数
        $config['base_url'] = base_url()."user_app_process/app_recommend";
        $config['total_rows'] = $record_num;
        $config['per_page'] = '10';
        $config['full_tag_open'] = '<p>';
        $config['num_links'] = 10;
        $config['full_tag_close'] = '</p>';
        $config['first_link'] = '首页';
        $config['last_link'] = '最后一页';
        $config['next_link'] = '&gt;下一页';
        $config['prev_link'] = '上一页&lt;';
        $config ['suffix'] = "?app_id=".$app_id . "&nav=" . (isset($_REQUEST["nav"])?$_REQUEST["nav"]:"");
        $config['first_url'] = base_url()."user_app_process/app_recommend/?app_id=".$app_id
                             . "&nav=" . (isset($_REQUEST["nav"])?$_REQUEST["nav"]:"");
        $this->pagination->initialize($config);
        $data['turn_page'] = $this->pagination->create_links();
        
        $this->load->view('member/header_nav', $data);
        $this->load->view('member/app_process_app_recommend', $data);
        $this->load->view('member/footer'); 
    }
}
