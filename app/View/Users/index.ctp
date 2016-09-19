ログイン済み：<?php print(h($user['username'])); ?>さん<br />
<?php print($this->Html->link('ログアウト', 'logout')); ?>

<?php echo $this->Html->link('つぶやき画面へ', '/tweets/index'); ?>

