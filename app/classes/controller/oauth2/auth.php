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
//            Response::redirect('/');
            return Response::forge(var_dump($response));
        }
    }
    
    /**
     * セッション管理
     */
    public function setSession($user)
    {
        
        \Session::set('client_no', $user['no']);
        \Session::set('client_id', $user['id']);
        \Session::set('client_name', "{$user['person_name1']} {$user['person_name2']}");
        \Session::set('agency_no', $user['agency_no']);
        \Session::set('point', $user['point']);
        \Session::set('use_capacity', $user['disk_volume']);
        \Session::set('demo_user', ($user['user_type'] == '0' ? TRUE : FALSE));
        \Session::set('is_video_edit', Model_Authority::isClientVideoEdit($user['no']));
        \Session::set('is_video_preview', Model_Authority::isClientVideoPreview($user['no']));
        \Session::set('is_material_edit', Model_Authority::isClientMaterial($user['no']));
        \Session::set('is_purchase_unlimited', Model_Authority::isClientPoint($user['no']));
        
        $agency = Model_Agency::find($user['agency_no'])->current();
        \Session::set('unit_type', $agency['charges_setting']);
        \Session::instance()->rotate();
        Model_Client::updateLastLoginDate();
    }
    
    /**
     * クライアント情報の登録処理
     * 
     * @param array $userInfo ユーザー情報
     */
    public function addClient($userInfo)
    {
        try {
            DB::start_transaction();
            $client = array(
                'id'=>'000001',                                                                // クライアントID
                'agency_no'=>2,                                                                // 代理店No
                'name'=>'テスト企業',                                                           // 正式企業名
                'display_name'=>'テスト',                                                      // 表示名
                'mail_address'=>'test@mail.com',                                               // メールアドレス
                'password'=>'',                                                                // パスワード
                'person_name1'=>$userInfo['family_name#ja-Hani-JP'],                           // 登録名（姓）
                'person_name2'=>$userInfo['given_name#ja-Hani-JP'],                            // 登録名（名）
                'person_name_kana1'=>mb_convert_kana($userInfo['family_name#ja-Kana-JP'],"c"), // ひらがな（姓）
                'person_name_kana2'=>mb_convert_kana($userInfo['given_name#ja-Kana-JP'],"c"),  // ひらがな（名）
                'user_no'=>1,                                                                  // 担当
                'user_type'=>1,                                                                // ユーザー種別
                'create_alert'=>0,                                                             // アカウント発行通知
                'initial_max_num'=>0,                                                          // 初期上限本数
                'notes'=>NULL,                                                                 // 備考
                'last_login_date'=>NULL,                                                       // 最終ログイン
                'last_video_create_date'=>NULL,                                                // 最終動画作成
                'max_capacity'=>2147483648,                                                    // MAX容量
                'purchase_video'=>0,                                                           // 購入動画数
                'sleep_flag'=>0,                                                               // 停止フラグ
            );

            $client_no = Model_Client::insert($client);

            $client_api = array(
                'client_no'=>$client_no,             // クライアントNo
                'open_id'=>$userInfo['user_id'],     // オープンID
                'api_token'=>$this->makeRandStr(),   // APIトークン
            );

            Model_ClientApiToken::insert($client_api);

            DB::commit_transaction();
            return Model_Client::find($client_no)->current();
            
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