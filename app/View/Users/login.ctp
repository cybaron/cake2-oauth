<?php
if(!empty($error)) echo $error;
?>
<?php
echo $this->Form->create('User', array('action' => 'login'));
echo $this->Form->input('email');
echo $this->Form->input('password');
echo $this->Form->end(__('Login'));
?>
<p>
<?php
echo $this->Html->link(
  $this->Html->image('login_fb.png', array('alt' => 'Login with Facebook')),
  array('controller' => 'users', 'action' => 'login', 'facebook'),
  array('escape' => false)
);
?>
</p>
<p>
<?php
echo $this->Html->link(
  $this->Html->image('login_tw.png', array('alt' => 'Login with Twitter')),
  array('controller' => 'users', 'action' => 'login', 'twitter'),
  array('escape' => false)
);
?>
</p>
<p>
<?php
echo $this->Html->link('Sign-up', array('action' => 'signup'));
?>
</p>
