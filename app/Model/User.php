<?php
App::uses('AppModel', 'Model');

class User extends AppModel {
  public $name = 'User';

  public $validate = array(
    'username' => array(
      'rule' => 'notEmpty',
      'message' => '何か入力してください'
    ),
    'email' => array(
      'notempty' => array(
        'rule' => 'notEmpty',
        'message' => '何か入力してください',
        'last'    => true
      ),
      'email' => array(
        'rule' => 'email',
        'message' => '正しくemailを入力してください',
        'last'    => true
      ),
      'unique' => array(
        'rule' => 'isUnique',
        'message' => 'このemailは既に登録されています'
      )
    ),
    'password' => array(
      'notempty' => array(
        'rule' => 'notEmpty',
        'message' => '何か入力してください'
      ),
      'between' => array(
        'rule' => array('between', 6, 20),
        'required' => true,
        'message' => 'passwordは6〜20文字以内で入力してください'
      ),
    ),
    'password_confirm' => array(
      'rule' => 'notEmpty',
      'message' => '何か入力してください'
    ),
  );

	public $hasMany = array('Oauthuser');

  public function beforeSave() {
    $this->data['User']['password'] = AuthComponent::password($this->data['User']['password']);
    return true;
  }

}
