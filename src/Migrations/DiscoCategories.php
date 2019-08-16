<?php

namespace Disco\Migrations;

use Disco\Helpers\SettingsHelper;
use Plenty\Modules\Property\Contracts\PropertyNameRepositoryContract;
use Plenty\Modules\Property\Contracts\PropertyRepositoryContract;

class DiscoCategories
{
    /** @var SettingsHelper */
    protected $Settings;

    public function __construct(SettingsHelper $SettingsHelper)
    {
        $this->Settings = $SettingsHelper;
    }


    public function run()
    {
        if(empty($this->Settings->get('disco_category_as_property'))) {
            /** @var PropertyRepositoryContract $propertyRepository */
            $propertyRepository = pluginApp(PropertyRepositoryContract::class);

            /** @var PropertyNameRepositoryContract $propertyNameRepository */
            $propertyNameRepository = pluginApp(PropertyNameRepositoryContract::class);

            $propertyData = [
                'cast' => 'selection',
                'typeIdentifier' => 'item',
                'position' => 0,
                'names' => [
                    [
                        'lang' => 'de',
                        'name' => 'Disco Kategorie',
                        'description' => 'Disco Kategorie als Eigenschaften'
                    ]
                ]
            ];

            $property = $propertyRepository->createProperty($propertyData);

            foreach($propertyData['names'] as $propertyName) {
                $propertyName['propertyId'] = $property->id;
                $propertyName = $propertyNameRepository->createName($propertyName);
            }

            $this->Settings->set(SettingsHelper::CATEGORIES_AS_PROPERTIES, $property->id);
        }
    }
}