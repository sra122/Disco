<?php
namespace Disco\Migrations;

use Plenty\Modules\Market\Settings\Contracts\SettingsRepositoryContract;
use Plenty\Modules\Market\Settings\Models\Settings;

class MergeDiscoSettings
{
    public function __construct()
    {

    }

    public function run()
    {
        /** @var SettingsRepositoryContract $settingsRepositoryContract */
        $settingsRepositoryContract = pluginApp(SettingsRepositoryContract::class);

        /** @var Settings[] $properties */
        $properties = $settingsRepositoryContract->find('Disco', 'property');

        $settings = [];

        foreach ($properties as $key => $property) {
            if ($key === 0) {
                $settings = $property->settings;
            } else {
                $settings = array_merge($settings, $property->settings);
            }
        }

        foreach ($properties as $key => $property) {
            if ($key === 0) {
                $settingsRepositoryContract->update($settings, $property->id);
            } else {
                $settingsRepositoryContract->delete($property->id);
            }
        }
    }
}