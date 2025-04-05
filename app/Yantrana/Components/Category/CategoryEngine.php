<?php
/*
* CategoryEngine.php - Main component file
*
* This file is part of the Category component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Category;

use App\Yantrana\Base\BaseEngine;
use App\Yantrana\Components\Category\Interfaces\CategoryEngineInterface;
use App\Yantrana\Components\Category\Repositories\CategoryRepository;
use App\Yantrana\Components\Product\Repositories\ProductRepository;

class CategoryEngine extends BaseEngine implements CategoryEngineInterface
{
    /**
     * @var  CategoryRepository - Category Repository
     */
    protected $categoryRepository;

    /**
     * @var  ProductRepository - Product Repository
     */
    protected $productRepository;

    /**
     * Constructor
     *
     * @param  CategoryRepository  $categoryRepository - Category Repository
     * @return  void
     *-----------------------------------------------------------------------*/
    public function __construct(CategoryRepository $categoryRepository, ProductRepository $productRepository)
    {
        $this->categoryRepository = $categoryRepository;
        $this->productRepository = $productRepository;
    }

    /**
     * Prepare category list.
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function prepareList()
    {
        $categoryCollection = $this->categoryRepository->fetchCategories();

        $requireColumns = [
            '_id',
            '_uid',
            'name',
            'user_authorities__id',
            'status' => function ($key) {
                return configItem('categories', $key['status']);
            },
            'created_at' => function ($key) {
                return formatDateTime($key['created_at']);
            },
            'can_edit' => function () {
                return canAccess('manage.category.write.update');
            },
            'can_delete' => function () {
                return canAccess('manage.category.write.delete');
            },
        ];

        return $this->dataTableResponse($categoryCollection, $requireColumns);
    }

    /**
     * Process Add Category Process
     *
     * @input array $inputData
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function addCategoryProcess($inputData)
    {
        if (! isset($inputData['name']) or __isEmpty($inputData['name'])) {
            return $this->engineReaction(2, null, __tr('Please provide category name.'));
        }

        if ($newCategory = $this->categoryRepository->storeProcess($inputData)) {
            $addedCategory = [
                'id' => $newCategory->_id,
                'name' => $newCategory->name,
            ];

            return $this->engineReaction(1, ['addedCategory' => $addedCategory], __tr('Category added successfully.'));
        }

        return $this->engineReaction(2, null, __tr('Category not added.'));
    }

    /**
     * Prepare Category update data
     *
     * @input number $categoryId
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function prepareUpdateData($categoryId)
    {
        $category = $this->categoryRepository->fetch($categoryId);

        // Check if category exist
        if (__isEmpty($category)) {
            return $this->engineReaction(18, null, __tr('Category does not exist'));
        }

        $updateData = [
            'id' => $category->_id,
            'status' => ($category->status == 1) ? true : false,
            'name' => $category->name,
        ];

        return $this->engineReaction(1, [
            'updateData' => $updateData,
        ]);
    }

    /**
     * Update Category Process
     *
     * @input array $inputData
     * @input number $categoryId
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function updateCategoryProcess($inputData, $categoryId)
    {
        $category = $this->categoryRepository->fetch($categoryId);

        // Check if category exist
        if (__isEmpty($category)) {
            return $this->engineReaction(18, null, __tr('Category does not exist'));
        }

        $updateData = [
            'name' => $inputData['name'],
            'status' => ($inputData['status'] == true) ? 1 : 2,
        ];

        if ($this->categoryRepository->updateCategory($category, $updateData)) {
            return $this->engineReaction(1, null, __tr('Category updated successfully.'));
        }

        return $this->engineReaction(14, null, __tr('Nothing to update.'));
    }

    /**
     * Delete Category Process
     *
     * @input number $categoryId
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function categoryDeleteProcess($categoryId)
    {
        $reactionCode = $this->categoryRepository
            ->processTransaction(function () use ($categoryId) {
                $category = $this->categoryRepository->fetch($categoryId);

                // Check if category exist
                if (__isEmpty($category)) {
                    return $this->categoryRepository->transactionResponse(18, null, __tr('Category does not exist'));
                }

                $updateData = [
                    'status' => 3, // Deleted
                ];

                $deleteCategory = false;

                if ($this->categoryRepository->deleteCategory($category, $updateData)) {
                    $deleteCategory = true;
                    if ($this->productRepository->updateProducts($category->_id, $updateData)) {
                        $deleteCategory = true;
                    }
                }

                if ($deleteCategory) {
                    return $this->categoryRepository->transactionResponse(1, null, __tr('Category deleted successfully.'));
                }

                return $this->categoryRepository->transactionResponse(2, null, __tr('Category not delete.'));
            });

        return $this->engineReaction($reactionCode);
    }
}
