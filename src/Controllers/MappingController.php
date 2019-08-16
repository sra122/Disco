<?php
/**
 * Created by PhpStorm.
 * User: sravan
 * Date: 13.06.19
 * Time: 12:07
 */

namespace Disco\Controllers;

use Disco\Helpers\SettingsHelper;
use Plenty\Plugin\Controller;
use Plenty\Plugin\Http\Request;

class MappingController extends Controller
{
    public $mappingInfo = [];
    protected $settingsHelper;

    public function __construct(SettingsHelper $settingsHelper)
    {
        $this->settingsHelper = $settingsHelper;
    }

    /**
     * @return mixed
     */
    public function fetchNotifications()
    {
        return $this->settingsHelper->get(SettingsHelper::NOTIFICATION);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function removeNotification(Request $request)
    {
        $propertyName = $request->get('propertyName');
        $notificationType = $request->get('notificationType');
        $notifications = $this->settingsHelper->get(SettingsHelper::NOTIFICATION);

        $specialNotification = ['noStockProducts', 'noAsinProducts', 'emptyAttributeProducts'];

        if(in_array($notificationType, $specialNotification))
        {
            unset($notifications[$notificationType]);
        } else {
            unset($notifications[$notificationType][$propertyName]);
        }

        $this->settingsHelper->set(SettingsHelper::NOTIFICATION, $notifications);

        return $notifications;
    }


    public function updateNotifications()
    {
        $notification = $this->fetchNotifications();

        $adminNotification = isset($notification['admin']) ? $notification['admin'] : '';

        $app = pluginApp(AppController::class);
        $settingsHelper = pluginApp(SettingsHelper::class);

        $pbNotifications = $app->authenticate('disco_notifications');

        foreach($pbNotifications as $key => $discoNotification)
        {
            if(!isset($adminNotification[$key]) && ((time() - $discoNotification['timestamp']) < 86400)) {
                $adminNotification[$key] = $discoNotification;
                $adminNotification[$key]['id'] = $key;
                if($adminNotification[$key]['type'] !== 'info') {
                    $adminNotification[$key]['categoryName'] = $settingsHelper->get(SettingsHelper::CATEGORIES_LIST)[$discoNotification['values']['category_id']];
                }
            }
        }

        $notification['admin'] = $adminNotification;

        $settingsHelper->set(SettingsHelper::NOTIFICATION, $notification);

        return $notification;
    }
}