<?php
/**
 * Test class
 *
 * Requires Sebastian Bergmann's PHPUnit
 *
 * PHP version 5
 *
 * @category
 * @package
 * @author    Lars Olesen <lars@legestue.net>
 * @copyright 2007 Authors
 * @license   GPL http://www.opensource.org/licenses/gpl-license.php
 * @version   @package-version@
 * @link      http://public.intraface.dk
 */
require_once dirname(__FILE__) . '/config.test.php';
require_once 'PHPUnit/Framework.php';
require_once 'MDB2.php';
require_once 'Ilib/ForgottenPassword.php';

/**
 * Test class
 *
 * @category
 * @package
 * @author    Lars Olesen <lars@legestue.net>
 * @copyright 2007 Authors
 * @license   GPL http://www.opensource.org/licenses/gpl-license.php
 * @version   @package-version@
 * @link      http://public.intraface.dk
 */
class ForgottenPasswordTest extends PHPUnit_Framework_TestCase
{
    private $db;

    function setUp()
    {
        $this->db = MDB2::factory(DB_DSN);
    }

    public function testUpdatePassword()
    {
        $forgotten = new Ilib_ForgottenPassword($this->db, "liveuser_users", array("username" => "handle", "password" => "passwd"));
        $password = 'skipcheckin';
        $this->assertTrue($forgotten->updatePassword('member@skipcheckin.eu', $password));
    }

}