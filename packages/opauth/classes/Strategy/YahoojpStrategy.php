<?php
/**
 * 共通Strategy for Opauth
 * 
 * @copyright Copyright © 2012 Ryo Ito (https://github.com/ritou)
 * @link http://opauth.org
 * @package Opauth.共通Strategy
 * @license MIT License
 */

/**
 * 共通Strategy for Opauth
 * based on URL
 * 
 * @package Opauth.Common
 */
class YahoojpStrategy extends OpauthStrategy{
	
    /**
     * 強制設定キー（アプリケーションID・シークレット）
     */
    public $expects = array('client_id', 'client_secret');
	
    /**
     * オプション設定キー（コールバックURL・スコープ・ステート）
     */
    public $optionals = array('redirect_uri', 'scope', 'state');
	
    /**
     * オプション設定キー（コールバックURL・スコープ）
     */
    public $defaults = array(
        'redirect_uri' => '{complete_url_to_strategy}oauth2callback'
    );
	
    /**
    * レスポンスタイプ
    */
   const RESPONSE_TYPE = "code";

    /**
    * グラントタイプ
    */
   const GRANT_TYPE = "authorization_code";
       
    /**
     * コンテンツタイプ
     */
    const CONTENT_TYPE = "Content-type: application/x-www-form-urlencoded\r\n";
    
        /**
     * レスポンスタイプ
     */
    const BASIC = "Authorization: Basic ";
        
    /**
     * Auth リクエスト
     */
    public function request(){
        // Authorizationエンドポイントリクエストパラメータ
        $params = array(
                'client_id' => $this->strategy['client_id'],       // アプリケーション登録時に発行したアプリケーションID
                'redirect_uri' => $this->strategy['redirect_uri'], // アプリケーション登録時に設定したフルURL
                'response_type' => self::RESPONSE_TYPE,            // 「code」という固定文字列
                'scope' => $this->strategy['scope']                // UserInfo APIから取得できる属性情報
        );

        // Strategy設定ファイルの読み込み処理
        foreach ($this->optionals as $key){
            if (!empty($this->strategy[$key])){
                $params[$key] = $this->strategy[$key];
            } 
        }
        
        // Authorizationエンドポイントへリダイレクト
        $this->clientGet($this->strategy['aUrl'], $params);
    }
	
    /**
     * OAuthの後の内部コールバック
     */
    public function oauth2callback(){
        
        if (array_key_exists('code', $_GET) && !empty($_GET['code'])){
            // Tokenエンドポイントリクエストパラメータ
            $params = array(
                'code' => $_GET['code'],                           // 認可コード
                'redirect_uri' => $this->strategy['redirect_uri'], // Authorizationエンドポイントで指定したURI
                'grant_type' => self::GRANT_TYPE                   // 「authorization_code」という固定文字列
            );
            
            // Tokenエンドポイントリクエストメッセージ
            $options = array(
                'http' => array(
                    'method' => 'POST',
                    'content' => http_build_query($params),
                    'header' => self::CONTENT_TYPE.self::BASIC.$this->basicEncode()
                )
            );

            // アクセストークンを取得する
            $response = $this->getFileContents($options);
            // レスポンス結果をJSONデコードする
            $results = json_decode($response);
            
            // ユーザー情報の取得処理
            if (!empty($results) && !empty($results->access_token)){
                $userinfo = $this->userinfo($results->access_token);
                $this->auth = array(
                    'provider' => $this->strategy['provider'],
                    'uid' => $userinfo->user_id,
                    'info' => array(
                        'name' => $userinfo->name,
                        'email' => $userinfo->email,
                        'email_verified' => $userinfo->email_verified
                    ),
                    'credentials' => array(
                        'token' => $results->access_token,
                        'expires' => date('c', time() + $results->expires_in)
                    ),
                    'raw' => $userinfo
                );

                $this->callback();
            }
            else{
                $error = array(
                    'provider' => $this->strategy['provider'],
                    'code' => 'access_token_error',
                    'message' => 'アクセストークンの取得に失敗！',
                    'raw' => array(
                        'response' => $response
                    )
                );

                $this->errorCallback($error);
            }
        }
        else{
            $error = array(
                'provider' => $this->strategy['provider'],
                'code' => 'oauth2callback_error',
                'raw' => $_GET
            );

            $this->errorCallback($error);
        }
    }
	
    /**
     * ユーザー情報取得処理
     *
     * @param string $access_token アクセストークン
     * @return array $results JSON形式ユーザー情報
     */
    private function userinfo($access_token){
        
        $userinfo = $this->serverGet(
                        $this->strategy['uUrl'],
                        array(
                            'schema' => 'openid',
                            'access_token' => $access_token
                                ),
                        null);
        if (!empty($userinfo)){
            return json_decode($userinfo);
        }
        else{
            $error = array(
                'provider' => $this->strategy['provider'],
                'code' => 'userinfo_error',
                'message' => 'ユーザー情報の取得に失敗！',
                'raw' => array(
                    'response' => $userinfo
                )
            );

            $this->errorCallback($error);
        }
    }
    /**
     * Basic認証処理
     */
    private function basicEncode(){
        return base64_encode($this->strategy['client_id'].':'
                            . $this->strategy['client_secret']
                            );
    }
    
    /**
     * トークンエンドポイントのレスポンスを文字列で読み込む処理
     *
     * @param array $options トークンエンドポイントのリクエストメッセージヘッダー
     */
    private function getFileContents($options){
        $streamOptions = stream_context_create($options);
        return file_get_contents( $this->strategy['tUrl'], false, $streamOptions );
    }
}
