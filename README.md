# FuelPHP��Opauth��������

## ���Ѓ��f�B�A�̃A�v���o�^�i��FYahoo!JP�j

- Yahoo�@Developers����A�v���P�[�V������o�^���A�A�v���P�[�V����ID�ƃV�[�N���b�g���擾����
- http://developer.yahoo.co.jp/yconnect/

## Opauth�̐ݒ�

- opauth.php��ҏW���A���Ѓ��f�B�A�A�v���o�^���̃A�v��ID�ƃA�v���V�[�N���b�g���L�q����
- fuel/app/config/opauth.php
- �ݒ�t�@�C����Strategy�̒��g��ώ킷��B�v���o�C�_�����w�肷��  
  �yclient_id�z�F�A�v���P�[�V����ID���w��  
  �yclient_secret�z�F�A�v���P�[�V�����V�[�N���b�g���w��  
  �yscope�z�F���[�U�[���̑������w��Fopenid(�K�{) profile email address  
  �yaUrl�z�FAuthorization�G���h�|�C���gURL���w��  
  �ytUrl�z�FToken�G���h�|�C���gURL���w��  
  �yuUrl�z�FUserInfo�G���h�|�C���gURL���w��  
   
## bootstrap�̐ݒ�
   
'Opauth\\TestssoStrategy' => __DIR__ . '/classes/Strategy/xxxxxxStrategy.php',
   
## Opauth�̗L����

- config.php�ɒǋL���AOpauth��L��������
- �uapp/config/config.php�v��packages�́fopauth�f��ǉ����A�L��������
