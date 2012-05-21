<?php
class UsersController extends AppController {
  public $name = 'Users';
  public $uses = array('User', 'Oauthuser');

  public function beforeFilter() {
    parent::beforeFilter();
    $this->Auth->allow('login', 'signup', 'hybridauth');
  }

  public function index() {
    $this->set('user', $this->Auth->user());
  }

  public function hybridauth() {
    require_once(APP . '/Vendor/Hybrid/Auth.php');
    require_once(APP . '/Vendor/Hybrid/Endpoint.php');
    Hybrid_Endpoint::process();
  }

  public function login($provider = null) {
    if (empty($provider)) {
      // email ログイン処理
      if($this->Auth->login()){
        return $this->redirect($this->Auth->redirect());
      } else {
        if(!empty($this->data)) {
          $this->Session->setFlash($this->Auth->authError);
        }
      }
    } else {
      // OAuth ログイン処理
      $hybridauth_config = array(
        "base_url" => "", 
        "providers" => array ( 
          "Facebook" => array ( 
            "enabled" => true,
            "keys"    => array ( "id" => "", "secret" => "" ),
            "scope"   => array("email", "user_birthday"),
          ),
          "Twitter" => array (
            "enabled" => true,
            "keys"    => array ( "key" => "", "secret" => "" ),
          ),
        ),
        "debug_mode" => false,
        "debug_file" => "",
      );

      if(is_file(APP . '/Vendor/secret.php')) include_once(APP . '/Vendor/secret.php');  // ここにapi-key,api-secret を記述してます。
      require_once(APP . '/Vendor/Hybrid/Auth.php');
      $hybridauth_config['base_url'] = 'http://'.$_SERVER['HTTP_HOST'].$this->base . "/users/hybridauth/";

      try{
        $hybridauth = new Hybrid_Auth($hybridauth_config);
        $adapter = $hybridauth->authenticate($provider);
        $user_profile = $adapter->getUserProfile();

        $oauthuser = $this->Oauthuser->findByProviderAndProviderUid($provider, $user_profile->identifier);
        $existing  = $this->User->findByEmail($user_profile->email);

        if($existing && empty($oauthuser)) {
          // provider->email で ユーザー登録されている場合は、そちらでログインしてもらう
          // emailとoauthユーザーを重複してユーザー登録させないため。
          $this->Session->setFlash("既にユーザー登録されています。 {$user_profile->email} でログインしてください。");
          $this->redirect(array('controller' => 'users', 'action' => 'login'));
        }

        if(empty($oauthuser)) {
          // 別provider認証の時に重複ユーザーとして作られるが、emailアドレスユニークでユーザーへ気づきとしている。
          // ドットインストールでも同様の処理となっていた。

          // ユーザー情報を$usr_profileから引き継ぎ & 会員登録画面に遷移
          $this->Session->setFlash('ユーザー登録します。情報を入力してください。');
          $this->Session->write('provider', $provider);
          $this->Session->write('provider_uid', $user_profile->identifier);
          $this->request->data['User']['username'] = $user_profile->displayName;
          $this->request->data['User']['email'] = $user_profile->email;
          $this->render('signup');
        } else {
          $user = $oauthuser['User'];
          if($user) {
            // oauth で ユーザー登録済みなのでログイン認証OK
            $this->Auth->login($user);
            return $this->redirect($this->Auth->redirect());
          } else {
            // oauthusers に存在して、usersに存在しない場合。なんらかのデータ不整合
          }
        }

      } catch (Exception $e) {
        switch( $e->getCode() ){ 
          case 0 : $error = "Unspecified error."; break;
          case 1 : $error = "Hybriauth configuration error."; break;
          case 2 : $error = "Provider not properly configured."; break;
          case 3 : $error = "Unknown or disabled provider."; break;
          case 4 : $error = "Missing provider application credentials."; break;
          case 5 : $error = "Authentification failed. The user has canceled the authentication or the provider refused the connection."; break;
          case 6 : $error = "User profile request failed. Most likely the user is not connected to the provider and he should to authenticate again."; 
            $adapter->logout(); 
            break;
          case 7 : $error = "User not connected to the provider."; 
            $adapter->logout(); 
          break;
        }
        // todo:エラーコードに応じたメッセージをログイン画面に表示させる。
        $error .= "<br /><br /><b>Original error message:</b> " . $e->getMessage(); 
        $error .= "<hr /><pre>Trace:<br />" . $e->getTraceAsString() . "</pre>";  
        $this->set('error', $error);
      }
    }
  }

  public function logout() {
    $this->redirect($this->Auth->logout());
  }

  public function signup() {
    if(empty($this->data)) return;

    // ユーザー情報登録処理
    if($this->data['User']['password'] !== $this->data['User']['password_confirm']) {
      $this->Session->setFlash('パスワードを正しく入力してください');
    } else {
      $this->User->create();
      if ($this->User->save($this->data)) {
        $user_id      = $this->User->getLastInsertId();
        $provider     = $this->Session->read('provider');
        $provider_uid = $this->Session->read('provider_uid');
        if(!empty($provider) && !empty($provider_uid)){
          // users と oauth 情報の関連データ登録
          $this->Oauthuser->add($user_id, $provider, $provider_uid);
          $this->Session->delete('provider');
          $this->Session->delete('provider_uid');
        }
        $this->Session->setFlash('ユーザー情報を登録しました');
        $this->redirect(array('action' => 'login'));
      } else {
        $this->Session->setFlash('ユーザー情報を登録できませんでした。再度入力してください');
      }
    }
  }

  public function delete() {
    // ユーザー削除
    $user_id = $this->Auth->user('id');
    $this->Oauthuser->deleteAll(array('Oauthuser.user_id' => $user_id));
    $this->User->delete($user_id);

    $this->logout();
  }
}
