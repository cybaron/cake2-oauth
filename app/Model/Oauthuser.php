<?php
App::uses('AppModel', 'Model');

class Oauthuser extends AppModel {
  public $name = 'Oauthuser';

	public $validate = array(
		'provider' => array(
			'notempty' => array(
				'rule' => array('notempty'),
			),
		),
		'provider_uid' => array(
			'notempty' => array(
				'rule' => array('notempty'),
			),
		),
	);

	public $belongsTo = array('User');

  public function add($user_id, $provider, $provider_uid) {
    $data = array(
      'user_id' => $user_id,
      'provider' => $provider,
      'provider_uid' => $provider_uid,
    );
    $this->create($data);
    $this->save();

    return $this->getLastInsertID();
  }
}
