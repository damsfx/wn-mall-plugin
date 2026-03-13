<?php

namespace Winter\Mall\Tests;

use Winter\Mall\Classes\Index\Index;
use Winter\Mall\Classes\Index\Noop;
use System\Tests\Bootstrap\PluginTestCase;
use Winter\Mall\Plugin;
use Winter\Mall\Models\Settings;

if (class_exists('System\Tests\Bootstrap\PluginTestCase')) {
    class BaseTestCase extends \System\Tests\Bootstrap\PluginTestCase
    {
    }
} else {
    class BaseTestCase extends \PluginTestCase
    {
    }
}

abstract class MallPluginTestCase extends BaseTestCase
{
    /**
     * @var array   Plugins to refresh between tests.
     */
    // protected $refreshPlugins = [
    //     'Winter.Mall',
    // ];

    /**
     * Perform test case set up.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        // reset any modified settings
        Settings::resetDefault();

        // log out after each test
        // \Winter\User\Classes\AuthManager::instance()->logout();

        // // register the auth facade
        // $alias = AliasLoader::getInstance();
        // $alias->alias('Auth', 'Winter\User\Facades\Auth');

        // App::singleton('user.auth', function () {
        //     return \Winter\User\Classes\AuthManager::instance();
        // });
    }
}
