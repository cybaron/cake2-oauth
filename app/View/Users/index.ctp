<p>
login success! <br>
Hi! <?php echo $user['username']; ?>
</p>
<p><?php echo $this->Html->link('logout', '/users/logout'); ?></p>
<p><?php echo $this->Html->link('user delete', '/users/delete', array(), "削除しても良いですか？"); ?></p>

<?php
$targets = array('facebook', 'twitter');
if(!empty($oauth)) :
?>
<p>OAuth連携解除</p>
<ul>
  <?php foreach($targets as $target) : ?>
  <?php if(in_array($target, $oauth)) : ?>
  <li>
  <?php
      echo $this->Html->link("disconnect {$target}" ,
        array('controller' => 'users', 'action' => 'disconnect', $target)
      );
  ?>
  </li>
  <?php endif; ?>
  <?php endforeach; ?>
</ul>
<?php endif; ?>

<p>OAuth連携</p>
<?php if (!empty($oauth)) : ?>
<ul>
  <?php
  foreach($targets as $target) :
    if (!in_array($target, $oauth)) :
  ?>
  <li>
  <?php
    echo $this->Html->link("connect {$target}",
      array('controller' => 'users', 'action' => 'connect', $target)
    );
  ?>
  </li>
    <?php endif; ?>
  <?php endforeach; ?>
</ul>
<?php else: ?>
  <?php foreach($targets as $target) : ?>
  <li>
  <?php
    echo $this->Html->link("connect {$target}",
      array('controller' => 'users', 'action' => 'connect', $target)
    );
  ?>
  </li>
  <?php endforeach; ?>
<?php endif; ?>
