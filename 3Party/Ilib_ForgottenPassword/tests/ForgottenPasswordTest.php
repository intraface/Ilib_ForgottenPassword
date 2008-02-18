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
require_once 'PHPUnit/Framework.php';
require_once dirname(__FILE__) . '/../src/Ilib/ForgottenPassword.php';

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
    public function testConstruction()
    {
        $forgotten = new Ilib_ForgottenPassword('user');
    }

}