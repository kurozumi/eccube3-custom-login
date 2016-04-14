<?php
namespace Plugin\CustomLogin\ServiceProvider;

use Eccube\Application;
use Silex\Application as BaseApplication;
use Silex\ServiceProviderInterface;

class CustomLoginServiceProvider implements ServiceProviderInterface
{

    /**
     * Registers services on the given app.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     */
    public function register(BaseApplication $app)
    {
        $app['security.firewalls'] = array(
            'admin' => array(
                'pattern' => "^/{$app['config']['admin_route']}",
                'form' => array(
                    'login_path' => "/{$app['config']['admin_route']}/login",
                    'check_path' => "/{$app['config']['admin_route']}/login_check",
                    'username_parameter' => 'login_id',
                    'password_parameter' => 'password',
                    'with_csrf' => true,
                    'use_forward' => true,
                ),
                'logout' => array(
                    'logout_path' => "/{$app['config']['admin_route']}/logout",
                    'target_url' => "/{$app['config']['admin_route']}/",
                ),
                'users' => $app['orm.em']->getRepository('Eccube\Entity\Member'),
                'anonymous' => true,
            ),
            'customer' => array(
                'pattern' => '^/',
                'form' => array(
                    'login_path' => '/mypage/login',
                    'check_path' => '/login_check',
                    'username_parameter' => 'login_email',
                    'password_parameter' => 'login_pass',
                    'with_csrf' => true,
                    'use_forward' => true,
                ),
                'logout' => array(
                    'logout_path' => '/logout',
                    'target_url' => '/mypage/login', // logout後にログインページヘ飛ばす
                ),
                'remember_me' => array(
                    'key' => sha1($app['config']['auth_magic']),
                    'name' => 'eccube_rememberme',
                    // lifetimeはデフォルトの1年間にする
                    // 'lifetime' => $this['config']['cookie_lifetime'],
                    'path' => $app['config']['root_urlpath'] ?: '/',
                    'secure' => $app['config']['force_ssl'],
                    'httponly' => true,
                    'always_remember_me' => false,
                    'remember_me_parameter' => 'login_memory',
                ),
                'users' => $app['orm.em']->getRepository('Eccube\Entity\Customer'),
                'anonymous' => true,
            ),
        );
    }

    /**
     * Bootstraps the application.
     *
     * This method is called after all services are registered
     * and should be used for "dynamic" configuration (whenever
     * a service must be requested).
     */
    public function boot(BaseApplication $app)
    {
        // TODO: Implement boot() method.
    }
}
