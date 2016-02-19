# FuelPHPのOpauth実装流れ

## Opauthをインストール（修正完了）

- FuelPHPのパッケージは、以下のリポジトリで配布されている
  https://github.com/andreoav/fuel-opauth

## 他社メディアのアプリ登録（例：Yahoo!JP）

- Yahoo　Developersからアプリケーションを登録し、アプリケーションIDとシークレットを取得する
- http://developer.yahoo.co.jp/yconnect/

## Opauthの設定

- opauth.phpを編集し、他社メディアアプリ登録時のアプリIDとアプリシークレットを記述する
　　fuel/app/config/opauth.php
   'Strategy' => array(
      'xxxxxx' => array(                 [プロバイダ名を指定＊以下のProviderと同名]
	     'provider' => 'xxxxxx',         [プロバイダ名を指定＊以下のStrategyと同名]
		 'client_id' => 'xxxxxx',        [アプリケーションIDを指定]
		 'client_secret' => 'xxxxxx',    [アプリケーションシークレットを指定]
		 'scope' => 'xxxxxx',            [ユーザー情報の属性を指定：openid(必須) profile email address]
		 'aUrl' => 'xxxxxx',             [AuthorizationエンドポイントURLを指定]
		 'tUrl' => 'xxxxxx',             [TokenエンドポイントURLを指定]
		 'uUrl' => 'xxxxxx'              [UserInfoエンドポイントURLを指定]
	  )	
   )
   
## bootstrapの設定
   
'Opauth\\TestssoStrategy' => __DIR__ . '/classes/Strategy/TestssoStrategy.php',
   
## Opauthの有効か

- config.phpに追記し、Opauthを有効かする
  app/config/config.php
  
  'always_load'  => array(
	'packages'  => array(
		'orm',
		'auth',
		'opauth', //opauthを有効にする
	),
  ),