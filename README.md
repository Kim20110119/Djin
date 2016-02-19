# FuelPHPのOpauth実装流れ

## 他社メディアのアプリ登録（例：Yahoo!JP）

- Yahoo　Developersからアプリケーションを登録し、アプリケーションIDとシークレットを取得する
- http://developer.yahoo.co.jp/yconnect/

## Opauthの設定

- opauth.phpを編集し、他社メディアアプリ登録時のアプリIDとアプリシークレットを記述する
- fuel/app/config/opauth.php
- 設定ファイルのStrategyの中身を変種する。プロバイダ名を指定する  
  
       'client_id'========[アプリケーションIDを指定]  
       'client_secret'====[アプリケーションシークレットを指定]  
       'scope'============[ユーザー情報の属性を指定：openid(必須) profile email address]  
       'aUrl'=============[AuthorizationエンドポイントURLを指定]  
       'tUrl'=============[TokenエンドポイントURLを指定]  
       'uUrl'=============[UserInfoエンドポイントURLを指定]  
   
## bootstrapの設定
   
'Opauth\\TestssoStrategy' => __DIR__ . '/classes/Strategy/xxxxxxStrategy.php',
   
## Opauthの有効か

- config.phpに追記し、Opauthを有効かする
- 「app/config/config.php」のpackagesの’opauth’を追加し、有効化する
