<?php
App::uses('Oauthuser', 'Model');

/**
 * Oauthuser Test Case
 *
 */
class OauthuserTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.oauthuser', 'app.user');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Oauthuser = ClassRegistry::init('Oauthuser');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Oauthuser);

		parent::tearDown();
	}

}
