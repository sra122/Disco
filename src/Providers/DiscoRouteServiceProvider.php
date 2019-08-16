<?php

namespace Disco\Providers;

use Plenty\Plugin\RouteServiceProvider;
use Plenty\Plugin\Routing\Router;
use Plenty\Plugin\Routing\ApiRouter;

class DiscoRouteServiceProvider extends RouteServiceProvider
{
    /**
     * @param Router $router
     * @param ApiRouter $api
     */
    public function map(Router $router, ApiRouter $api)
    {
        //Authentication route
        $router->get('markets/disco/auth/authentication', 'Disco\Controllers\AuthController@getAuthentication');

        $api->version(['v1'], ['middleware' => ['oauth']], function ($router) {
            $router->get('markets/disco/login-url', 'Disco\Controllers\AuthController@getLoginUrl');
            $router->post('markets/disco/session', 'Disco\Controllers\AuthController@sessionCreation');
            $router->get('markets/disco/expire-time', 'Disco\Controllers\AuthController@tokenExpireTime');

            $router->get('markets/disco/vendor-categories', 'Disco\Controllers\CategoryController@getCategoriesList');

            //Disco Category as Property
            $router->post('markets/disco/create-category-as-property', 'Disco\Controllers\PropertyController@createCategoryAsProperty');

        });
    }
}