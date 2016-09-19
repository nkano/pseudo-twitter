<h1>ログイン</h1>
<?php print(
  $this->Form->create('User') .
  $this->Form->input('username') .
  $this->Form->input('password') .
  $this->Form->end('Submit')
); ?>

<?php echo $this->Session->flash(); //失敗メッセージ ?>