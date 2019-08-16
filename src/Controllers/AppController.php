<?php

namespace Disco\Controllers;

use Disco\Helpers\SettingsHelper;
use Plenty\Modules\Plugin\Libs\Contracts\LibraryCallContract;
use Plenty\Plugin\Controller;
class AppController extends Controller
{
    /** @var SettingsHelper */
    protected $Settings;

    public function __construct(SettingsHelper $SettingsHelper)
    {
        $this->Settings = $SettingsHelper;
    }

    public function authenticate($apiCall, $params = null, $productDetails = null)
    {
        $libCall = pluginApp(LibraryCallContract::class);
        $token = $this->Settings->get('discoToken');

        if ($token !== null) {
            if ($token['expires_in'] > time()) {
                $response = $libCall->call(
                    'Disco::'. $apiCall,
                    [
                        'token' => $token['token'],
                        'category_id' => $params,
                        'product_details' => $productDetails
                    ]
                );
            } else if($token['refresh_token_expires_in'] > time()) {
                $response = $libCall->call(
                    'Disco::disco_categories',
                    [
                        'token' => $token['refresh_token'],
                        'category_id' => $params,
                        'product_details' => $productDetails
                    ]
                );
            }

            if (\is_array($response) && isset($response['Response'])) {
                return $response['Response'];
            }
        }
    }
}