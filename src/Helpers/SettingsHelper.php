<?php

namespace Disco\Helpers;

use Plenty\Modules\Market\Credentials\Contracts\CredentialsRepositoryContract;
use Plenty\Modules\Market\Credentials\Models\Credentials;
use Plenty\Modules\Market\Settings\Contracts\SettingsRepositoryContract;
use Plenty\Modules\Market\Settings\Models\Settings;
use Plenty\Modules\Order\Referrer\Contracts\OrderReferrerRepositoryContract;

class SettingsHelper
{
    const CATEGORIES_AS_PROPERTIES = 'disco_category_as_property';
    const CATEGORIES_LIST = 'disco_categories_list';
    const NOTIFICATION = 'disco_notification';
    const ORDER_REFERRER = 'orderReferrerId';
    const DISCO_KATEGORIE_PROPERTY = 'Disco Kategorie';


    /** @var SettingsRepositoryContract */
    protected $SettingsRepositoryContract;
    /** @var CredentialsRepositoryContract */
    protected $CredentialsRepositoryContract;

    /** @var Settings */
    protected $settingProperty;
    /** @var bool */
    protected $hasSettingProperty;

    /** @var Credentials */
    protected $credentialProperty;
    /** @var bool */
    protected $hasCredentialProperty;

    public function __construct(SettingsRepositoryContract $SettingsRepositoryContract, CredentialsRepositoryContract $CredentialsRepositoryContract)
    {
        $this->SettingsRepositoryContract = $SettingsRepositoryContract;
        $this->CredentialsRepositoryContract = $CredentialsRepositoryContract;
    }

    public function getSettingProperty($key = '', $value = '')
    {
        /** @var Settings[] $properties */
        $properties = $this->SettingsRepositoryContract->find('Disco', 'property');

        if(count($properties) > 0) {
            $this->settingProperty = $properties[0];
        } else {
            if(($key !== '') && ($value !== '')) {
                $this->settingProperty = $this->SettingsRepositoryContract->create('Disco', 'property', [$key => $value]);
            }
        }

        return $this->settingProperty;
    }

    protected function getCredentialProperty()
    {
        if ($this->hasCredentialProperty === null) {
            $credentialsId = $this->get('disco_credentials_id');
            if ($credentialsId === null) {
                $this->hasCredentialProperty = false;
                return null;
            }

            try {
                $this->credentialProperty = $this->CredentialsRepositoryContract->get($credentialsId);
                $this->hasCredentialProperty = true;
            } catch (\Exception $e) {
                $this->hasCredentialProperty = false;
                return null;
            }
        }

        return $this->credentialProperty;
    }

    public function get($key)
    {
        $settingProperty = $this->getSettingProperty();
        if ($settingProperty === null || !isset($settingProperty->settings[$key])) {
            return null;
        }

        return $settingProperty->settings[$key];
    }

    public function set($key, $value)
    {
        if(!empty($key) && !empty($value)) {
            $this->getSettingProperty($key, $value);

            if($this->settingProperty->settings === null) {
                $this->SettingsRepositoryContract->update([$key => $value], $this->settingProperty->id);
            } else  {
                $combinedArray = array_merge($this->settingProperty->settings, [$key => $value]);
                if($combinedArray !== null) {
                    $this->SettingsRepositoryContract->update($combinedArray, $this->settingProperty->id);
                }
            }
        }

        return false;
    }

    public function getCredential($key)
    {
        $credentialProperty = $this->getCredentialProperty();
        if ($credentialProperty !== null && isset($credentialProperty[$key])) {
            return $credentialProperty[$key];
        }

        return null;
    }

    public function setCredential($key, $value)
    {
        $credentialProperty = $this->getCredentialProperty();
        if ($credentialProperty === false) {
            $this->credentialProperty = $this->CredentialsRepositoryContract->create([$key => $value]);
            $this->set('disco_credentials_id', $this->credentialProperty->id);
            $this->hasCredentialProperty = true;
        } else {
            $this->CredentialsRepositoryContract->update($this->get('disco_credentials_id'), array_merge($this->credentialProperty->data, [$key => $value]));
        }
    }


    public function getReferrerId()
    {
        $orderReferrerRepositoryContract = pluginApp(OrderReferrerRepositoryContract::class);

        foreach($orderReferrerRepositoryContract->getList() as $orderReferrer)
        {
            if($orderReferrer->backendName === 'Disco' && $orderReferrer->name === 'Disco') {
                $this->set(SettingsHelper::ORDER_REFERRER, $orderReferrer->id);
            }
        }
    }
}