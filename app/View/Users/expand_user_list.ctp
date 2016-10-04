<?php foreach($users as $user):
	echo $this->element('putUserSimple', array('user' => $user));
endforeach; ?>