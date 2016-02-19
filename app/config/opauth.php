<?php
/**
 * Opauth
 * Multi-provider authentication framework for PHP
 * FuelPHP Package by Andreo Vieira <andreoav@gmail.com>
 *
 * @copyright    Copyright © 2012 U-Zyn Chua (http://uzyn.com)
 * @link         http://opauth.org
 * @package      Opauth
 * @license      MIT License
 */

namespace Opauth;

/**
 * Opauth configuration file with advanced options.
 * 
 * Copy this file to fuel/app/config and tweak as you like.
 */
return array(
    /**
     * Path whre Fuel-Opauth is accessed.
     * 
     * Begins and ends with /.
     * eg. if Opauth is reached via http://example.org/auth/, path is '/auth/'.
     * if Opauth is reached via http://auth.example.org/, path is '/'.
     */
    'path' => '/oauth/',
    
    /**
     * Uncoment if you would like to view debug messages.
     */
     //'debug' => true,
     
     /**
      * Callback URL: redirected to after authentication, successful or otherwise.
      * 
      * eg. if Opauth callback is reached via http://example.org/auth/callback, callback_url id '/auth/callback/'
      */
    'callback_url'  => '/callback/',
    
    /**
     * Callback transport, for sending of $auth response.
     * 'session' - Default. Works best unless callback_url is on a different domain than Opauth.
     * 'post'    - Works cross-domain, but relies on availability of client side JavaScript.
     * 'get'     - Works cross-domain, but may be limited or corrupted by browser URL length limit
     *             (eg. IE8/IE9 has 2083-char limit).
     */
     //'callback_transport' => 'session',
     
    /**
     * A random string used for signing of $auth response.
     */
    'security_salt' => 'YahooJPTest',
    
    /**
     * Higher value, better security, slower hashing.
     * Lower value, lower security, faster hashing.
     */
    //'security_iteration' => 300,
     
    /**
     * Time limit for valid $auth response, starting form $auth response generation to validation.
     */
    //'security_timeout' => '2 minutes',
      
    /**
     * Directory wher Opauth strategies reside.
     */
    //'strategy_dir' => PKGPATH . '/Opauth/classes/Strategy/',
    
    /**
     * Strategy
     * 
     * provider : プロバイダー
     * client_id : アプリケーションID
     * client_secret : シークレット
     * scope : スコープ（UserInfo APIから取得できる属性情報を指定）
     * aUrl : AuthorizationエンドポイントRUL
     * tUrl : TokenエンドポイントURL
     * uUrl : UserInfoエンドポイントURL
     * 
     */
    'Strategy' => array(
        'Yahoojp' => array(
            'provider' => 'Yahoo!JP',
            'client_id' => 'dj0zaiZpPWxqZXpGamNZR1hTUiZzPWNvbnN1bWVyc2VjcmV0Jng9ZDI-',
            'client_secret' => 'fc6d67d6356001b5c4ca1f889ed97011abba80d8',
            'scope' => 'openid profile email address',
            'aUrl' => 'https://auth.login.yahoo.co.jp/yconnect/v1/authorization',
            'tUrl' => 'https://auth.login.yahoo.co.jp/yconnect/v1/token',
            'uUrl' => 'https://userinfo.yahooapis.jp/yconnect/v1/attribute'
        ),
        'Facebook' => array(
            'provider' => 'FaceBook',
            'client_id' => 'dj0zaiZpPWxqZXpGamNZR1hTUiZzPWNvbnN1bWVyc2VjcmV0Jng9ZDI-',
            'client_secret' => 'fc6d67d6356001b5c4ca1f889ed97011abba80d8',
            'scope' => 'openid profile email address',
            'aUrl' => 'https://auth.login.yahoo.co.jp/yconnect/v1/authorization',
            'tUrl' => 'https://auth.login.yahoo.co.jp/yconnect/v1/token',
            'uUrl' => 'https://userinfo.yahooapis.jp/yconnect/v1/attribute'
        ),
    ),     
);