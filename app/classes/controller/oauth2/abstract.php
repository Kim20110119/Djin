<?php

/**
 * フロントサイトの基底コントローラークラス
 */
abstract class Controller_Oauth2_Abstract extends Controller_Abstract
{
    /** サイトフレームのテンプレート */
    public $template = 'oauth2/index.smarty';

    /** ページタイトル */
    public $page_title;

    /**
     * @override
     */
    public function before()
    {
//        if ( ! Auth::forge()->isLoggedIn() && Request::active()->action !== 'login' && Request::active()->action !== 'flogin') {
//            \Fuel\Core\Response::redirect('/login');
//        }

        if (Session::get('client_no')) {
            $user = DB::select('*')->from('v_client')->where('no', '=', Session::get('client_no'))->execute()->current();
            Session::set('point', $user['point']);
            Session::set('use_capacity', $user['disk_volume']);
        }

        // 親クラスのbeforeより先にViewの初期化を実行する
        if ( ! empty($this->template) and is_string($this->template))
        {
            $this->template = \View::forge($this->template, array('page_title' => $this->page_title));
        }
        parent::before();

        $controller = Request::active()->controller;
        $action = Request::active()->action;
    }

    /**
     * @override
     */
    protected function setLoggerInstance()
    {
        $this->logger = Logger::getLogger('front');
    }
}

