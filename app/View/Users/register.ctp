<h1>新規登録</h1>
<?php 
print(
  $this->Form->create('User') .
  $this->Form->input('username', array('label' =>'ユーザー名')) .
  $this->Form->input('password', array('label' =>'パスワード')) .
	$this->Form->input('nickname', array('label' =>'ニックネーム')) .
	$this->Form->input('mail', array('label' =>'メールアドレス')).
	$this->Form->input('public', array('label' =>'あなたのツイートを公開します', 'default'=>1)).
  $this->Form->end('Submit')
);
?>

<small>ニックネーム・公開設定はは未実装です。</small>