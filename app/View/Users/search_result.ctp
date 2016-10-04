<?php echo $this->Html->script( 'jquery-3.1.0.min.js', array( 'inline' => false)); ?>
<?php echo $this->Html->script( 'hoverColor.js', array( 'inline' => false)); ?>
<?php echo $this->Html->script( 'loadUsersWhenScrolled.js', array( 'inline' => false)); ?>

<?php
//デバッグ
//echo "<pre>"; print_r($result); echo "</pre>";
?>

<h1>検索結果</h1>
<?php 
	$options = array('label' => '名前', 'required' => false, 'default' => h($query) );
	print(
		$this->Form->create('User', array('type' => 'get', 'url' => 'search_result')) .
		$this->Form->input('username', $options) .
		$this->Form->end('検索')
	);
?>

<h2>
<?php
	echo $this->Session->flash();	//"検索結果は○件です"
?>
</h2>

<?php echo '<div id="userlist" data-page_num=1 data-current_location="search_result/'. $query. '">' ?>
<?php foreach( $users as $user ) {
	echo $this->element('putUserSimple', array('user' => $user));
} ?>
</div>
