<p>
login success! <br>
Hi! <?php echo $user['username']; ?>
</p>
<p><?php echo $this->Html->link('logout', '/users/logout'); ?></p>
<p><?php echo $this->Html->link('user delete', '/users/delete', array(), "削除しても良いですか？"); ?></p>

<?php if(!empty($providers)) : ?>
<p>OAuth連携</p>
<ul>
  <?php foreach($providers as $provider) : ?>
  <li><?php
      $provider_name = $provider['Oauthuser']['provider'];
      echo $this->Html->link("disconnect {$provider_name}" ,
        array(
          'controller' => 'users',
          'action' => 'disconnect',
          $provider_name,
        )
      );
    ?>
  </li>
  <?php endforeach; ?>
</ul>
<?php endif; ?>
