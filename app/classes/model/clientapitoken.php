<?php
/**
 * Class Model_ClientApiToken
 *
 * クライアントAPIトークン管理モデル
 */
class Model_ClientApiToken extends \Model_Abstract
{
    /**
     * クライアントAPIトークン管理テーブル名
     */
    const TABLE_NAME = 't_client_api_token';

    /**
     * APIトークンとクライアントNoを取得
     * @param $api_token_id ユーザー識別子
     * @return $rows
     */
    public static function findClientNo($api_token_id)
    {
        $rows = DB::select('client_no')
              ->from(self::TABLE_NAME)
              ->where('api_token', '=', $api_token_id)
              ->where('delete_date', '=', null)
              ->execute();

        return $rows;
    }
    
    /**
     * クライアント情報を取得
     * @param $client_no クライアントNo
     * @param $api_token_id ユーザー識別子
     * @return $rows
     */
    public static function find($client_no, $api_token_id)
    {
        $rows = DB::select('*')
              ->from(self::TABLE_NAME)
              ->where('client_no', '=', $client_no)
              ->where('api_token_id', '=', $api_token_id)
              ->where('delete_date', '=', null)
              ->execute();
        return $rows;
    }
    
    /**
     * クライアントAPIトークン管理情報を登録
     * @param $params 登録するパラメータ
     * @return $rows
     */
    public static function insert(array $params = array())
    {
        try {
            $params['create_date'] = DB::now();
            $params['update_date'] = DB::now();
            list($client_no) = DB::insert(self::TABLE_NAME)
                             ->set($params)->execute();
            return $client_no;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

}
