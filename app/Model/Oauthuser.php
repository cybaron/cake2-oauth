<?php
App::uses('AppModel', 'Model');

class Oauthuser extends AppModel {
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

	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
