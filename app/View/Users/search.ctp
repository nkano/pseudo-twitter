<h1>友達を見つけて、フォローしましょう！</h1>

<?php print(
  $this->Form->create('User', array('type' => 'get', 'url' => 'search_result')) .
  $this->Form->input('username', array('label' => '名前', 'required' => false)) .
  $this->Form->end('検索')
); ?>


