<?php
/*
* SuppliersEngine.php - Main component file
*
* This file is part of the Suppliers component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Suppliers;

use App\Yantrana\Base\BaseEngine;
use App\Yantrana\Components\Suppliers\Interfaces\SuppliersEngineInterface;
use App\Yantrana\Components\Suppliers\Repositories\SuppliersRepository;

class SuppliersEngine extends BaseEngine implements SuppliersEngineInterface
{
    /**
     * @var  SuppliersRepository - Suppliers Repository
     */
    protected $suppliersRepository;

    /**
     * Constructor
     *
     * @param  SuppliersRepository  $suppliersRepository - Suppliers Repository
     * @return  void
     *-----------------------------------------------------------------------*/
    public function __construct(SuppliersRepository $suppliersRepository)
    {
        $this->suppliersRepository = $suppliersRepository;
    }

    /**
     * Suppliers datatable source
     *
     * @return  array
     *---------------------------------------------------------------- */
    public function prepareSuppliersDataTableSource()
    {
        $suppliersCollection = $this->suppliersRepository
            ->fetchSuppliersDataTableSource();
        $requireColumns = [
            '_id',
            '_uid',
            'name',
            'short_description' => function ($key) {
                return str_limit($key['short_description'], configItem('string_limit'));
            },
            'can_edit' => function () {
                return canAccess('manage.suppliers.write.update');
            },
            'can_delete' => function () {
                return canAccess('manage.suppliers.write.delete');
            },
        ];

        return $this->dataTableResponse($suppliersCollection, $requireColumns);
    }

    /**
     * Suppliers delete process
     *
     * @param  mix  $suppliersIdOrUid
     * @return  array
     *---------------------------------------------------------------- */
    public function processSuppliersDelete($suppliersIdOrUid)
    {
        $suppliers = $this->suppliersRepository->fetch($suppliersIdOrUid);

        if (__isEmpty($suppliers)) {
            return $this->engineReaction(18, null, __tr('Supplier not found.'));
        }

        $updateData = [
            'status' => 3, // Deleted
        ];

        if ($this->suppliersRepository->deleteSuppliers($suppliers, $updateData)) {
            return $this->engineReaction(1, null, __tr('Supplier deleted successfully.'));
        }

        return $this->engineReaction(2, null, __tr('Supplier not deleted.'));
    }

    /**
     * Suppliers create
     *
     * @param  array  $inputData
     * @return  array
     *---------------------------------------------------------------- */
    public function processSuppliersCreate($inputData)
    {
        if ($newSupplier = $this->suppliersRepository->storeSuppliers($inputData)) {
            $supplierData = [
                'id' => $newSupplier->_id,
                'name' => $newSupplier->name,
            ];

            return $this->engineReaction(1, ['supplierData' => $supplierData], __tr('Suppliers added successfully.'));
        }

        return $this->engineReaction(2, null, __tr('Suppliers not added.'));
    }

    /**
     * Suppliers prepare update data
     *
     * @param  mix  $suppliersIdOrUid
     * @return  array
     *---------------------------------------------------------------- */
    public function prepareSuppliersUpdateData($suppliersIdOrUid)
    {
        $suppliers = $this->suppliersRepository->fetch($suppliersIdOrUid);

        // Check if $suppliers not exist then throw not found
        // exception
        if (__isEmpty($suppliers)) {
            return $this->engineReaction(18, null, __tr('Suppliers not found.'));
        }

        return $this->engineReaction(1, $suppliers->toArray());
    }

    /**
     * Suppliers process update
     *
     * @param  mix  $suppliersIdOrUid
     * @param  array  $inputData
     * @return  array
     *---------------------------------------------------------------- */
    public function processSuppliersUpdate($suppliersIdOrUid, $inputData)
    {
        $suppliers = $this->suppliersRepository->fetch($suppliersIdOrUid);

        // Check if $suppliers not exist then throw not found
        // exception
        if (__isEmpty($suppliers)) {
            return $this->engineReaction(18, null, __tr('Suppliers not found.'));
        }

        $updateData = [
            'name' => $inputData['name'],
            'short_description' => $inputData['short_description'],
        ];

        // Check if Suppliers updated
        if ($this->suppliersRepository->updateSuppliers($suppliers, $updateData)) {
            return $this->engineReaction(1, null, __tr('Suppliers updated successfully.'));
        }

        return $this->engineReaction(14, null, __tr('Nothing to update.'));
    }
}
