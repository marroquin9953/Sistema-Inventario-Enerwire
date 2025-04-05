<?php
/*
* CategoryRepository.php - Repository file
*
* This file is part of the Category component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Category\Repositories;

use App\Yantrana\Base\BaseRepository;
use App\Yantrana\Components\Category\Interfaces\CategoryRepositoryInterface;
use App\Yantrana\Components\Category\Models\CategoryModel;

class CategoryRepository extends BaseRepository implements CategoryRepositoryInterface
{
    /**
     * Fetch the record of Category
     *
     * @param    int || string $idOrUid
     * @return    eloquent collection object
     *---------------------------------------------------------------- */
    public function fetch($idOrUid)
    {
        if (is_numeric($idOrUid)) {
            return CategoryModel::where('_id', $idOrUid)->first();
        }

        return CategoryModel::where('_uid', $idOrUid)->first();
    }

    /**
     * Fetch All Categories
     *
     * @param    int || string $idOrUid
     * @return    eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchCategories()
    {
        $dataTableConfig = [
            'fieldAlias' => [],
            'searchable' => [
                'name',
            ],
        ];

        return CategoryModel::where('status', '!=', 3)->dataTables($dataTableConfig)->toArray();
    }

    /**
     * Store Categories Process
     *
     * @param array storeData
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function storeProcess($storeData)
    {
        $keyValues = [
            'name',
            'status' => 1,
            'user_authorities__id' => getUserAuthorityId(),
        ];

        $category = new CategoryModel();

        if ($category->assignInputsAndSave($storeData, $keyValues)) {
            activityLog(8, $category->_id, 1, $category->name);

            return $category;
        }

        return false;
    }

    /**
     * Update Categories Process
     *
     * @param object category
     * @param array updateData
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function updateCategory($category, $updateData)
    {
        if ($category->modelUpdate($updateData)) {
            activityLog(8, $category->_id, 2, $category->name);

            return true;
        }

        return false;
    }

    /**
     * Delete Categories Process
     *
     * @param object category
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function deleteCategory($category, $updateData)
    {
        if ($category->modelUpdate($updateData)) {
            activityLog(8, $category->_id, 3, $category->name);

            return true;
        }

        return false;
    }

    /**
     * Fetch All Categories
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchAllCategory()
    {
        return CategoryModel::get();
    }
}
