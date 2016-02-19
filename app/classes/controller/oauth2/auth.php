<?php
class Controller_Oauth2_Auth extends Controller
{
    private $_config = null;
    
    /**
     * OpenID Connect設定ファイルロード処理
     */
    public function before()
    {
        if(!isset($this->_config))
        {
            $this->_config = Config::load('opauth', 'opauth');
        }
    }
    
    /**
     * OpenID Connectを利用するログイン処理
     */
    public function action_login($_provider = null)
    {
        if(array_key_exists(Inflector::humanize($_provider), Arr::get($this->_config, 'Strategy')))
        {
            new Opauth($this->_config, true);
        }
        else
        {
            return Response::forge('サポートされてないStrategy！');
        }
        
        
    }

    /**
     * OpenID Connectを利用する承認後処理
     */
    public function action_callback()
    {
        $_opauth = new Opauth($this->_config, false);
        switch($_opauth->env['callback_transport'])
        {
            case 'session':
                session_start();
                $response = $_SESSION['opauth'];
                unset($_SESSION['opauth']);
            break;
        }

        if (array_key_exists('error', $response))
        {
            Response::redirect('/login');
        }
        else
        {
            $uid = $response['auth']['uid'];
            $client_no = Model_ClientApiToken::findClientNo($uid)->current();
            $user = Model_Client::find($client_no)->current();
            
            if(empty($user))
            {
                $user = $this->addClient($response['auth']['raw']);
            }
            
            $this->setSession($user);
            Response::redirect('/');
        }
    }
    
    /**
     * セッション管理
     */
    public function setSession($user)
    {
        
        // セッション設定処理
    }
    
    /**
     * ユーザー情報の登録処理
     * 
     * @param array $userInfo ユーザー情報
     */
    public function addClient($userInfo)
    {
        try {
            DB::start_transaction();
        
			// OpenIDとユーザー情報紐付く処理
            DB::commit_transaction();
            
        } catch (Exception $ex) {
            DB::rollback_transaction();
            return NULL;
        }

    }
    
    /**
    * ランダム文字列生成 (英数字)
    * $length: 生成する文字数
    */
    function makeRandStr($length = 8) {
        $str = array_merge(range('a', 'z'), range('0', '9'), range('A', 'Z'));
        $r_str = null;
        for ($i = 0; $i < $length; $i++) {
            $r_str .= $str[rand(0, count($str))];
        }
        return $r_str;
   }
}