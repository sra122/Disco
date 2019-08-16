<?php

namespace Disco\Controllers;

use Disco\Helpers\SettingsHelper;
use Plenty\Plugin\Controller;

/**
 * Class CategoryController
 * @package Disco\Controllers
 */
class CategoryController extends Controller
{
    public $completeCategoryRepo = [];

    public function getDiscoCategoriesAsDropdown()
    {
        $app = pluginApp(AppController::class);

        $discoCategories = $app->authenticate('disco_categories');

        return $discoCategories;
    }


    public function getCategoriesList()
    {
        $settingsHelper = pluginApp(SettingsHelper::class);
        $categoriesList = $settingsHelper->get(SettingsHelper::CATEGORIES_LIST);
        if(!empty($categoriesList)) {
            return $categoriesList;
        } else {
            $categoriesData = $this->getDiscoCategoriesAsDropdown();
            if(!empty($categoriesData)) {
                $this->saveDiscoCategoriesInPM();
                return $categoriesData;
            }
        }
    }

    public function saveDiscoCategoriesInPM()
    {
        $settingsHelper = pluginApp(SettingsHelper::class);

        $categoriesList = $settingsHelper->get(SettingsHelper::CATEGORIES_LIST);

        if(empty($categoriesList)) {
            $categoriesListAsDropdown = $this->getDiscoCategoriesAsDropdown();
            if(count($categoriesListAsDropdown) > 0) {
                $settingsHelper->set(SettingsHelper::CATEGORIES_LIST, $categoriesListAsDropdown);
            }
        }
    }
}
