# FuelPHP��Opauth��������

## Opauth���C���X�g�[���i�C�������j

- FuelPHP�̃p�b�P�[�W�́A�ȉ��̃��|�W�g���Ŕz�z����Ă���
  https://github.com/andreoav/fuel-opauth

## ���Ѓ��f�B�A�̃A�v���o�^�i��FYahoo!JP�j

- Yahoo�@Developers����A�v���P�[�V������o�^���A�A�v���P�[�V����ID�ƃV�[�N���b�g���擾����
- http://developer.yahoo.co.jp/yconnect/

## Opauth�̐ݒ�

- opauth.php��ҏW���A���Ѓ��f�B�A�A�v���o�^���̃A�v��ID�ƃA�v���V�[�N���b�g���L�q����
�@�@fuel/app/config/opauth.php
   'Strategy' => array(
      'xxxxxx' => array(                 [�v���o�C�_�����w�聖�ȉ���Provider�Ɠ���]
	     'provider' => 'xxxxxx',         [�v���o�C�_�����w�聖�ȉ���Strategy�Ɠ���]
		 'client_id' => 'xxxxxx',        [�A�v���P�[�V����ID���w��]
		 'client_secret' => 'xxxxxx',    [�A�v���P�[�V�����V�[�N���b�g���w��]
		 'scope' => 'xxxxxx',            [���[�U�[���̑������w��Fopenid(�K�{) profile email address]
		 'aUrl' => 'xxxxxx',             [Authorization�G���h�|�C���gURL���w��]
		 'tUrl' => 'xxxxxx',             [Token�G���h�|�C���gURL���w��]
		 'uUrl' => 'xxxxxx'              [UserInfo�G���h�|�C���gURL���w��]
	  )	
   )
   
## bootstrap�̐ݒ�
   
'Opauth\\TestssoStrategy' => __DIR__ . '/classes/Strategy/TestssoStrategy.php',
   
## Opauth�̗L����

- config.php�ɒǋL���AOpauth��L��������
  app/config/config.php
  
  'always_load'  => array(
	'packages'  => array(
		'orm',
		'auth',
		'opauth', //opauth��L���ɂ���
	),
  ),