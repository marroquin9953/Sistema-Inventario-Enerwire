<?php
/*
* CustomerRepository.php - Repository file
*
* This file is part of the Customer component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Customer\Repositories;

use App\Yantrana\Base\BaseRepository;
use App\Yantrana\Components\Customer\Interfaces\CustomerRepositoryInterface;
use App\Yantrana\Components\Customer\Models\CustomerModel;

class CustomerRepository extends BaseRepository implements CustomerRepositoryInterface
{
    /**
     * Fetch the record of Customer
     *
     * @param    int || string $idOrUid
     * @return    eloquent collection object
     *---------------------------------------------------------------- */
    public function fetch($idOrUid)
    {
        if (is_numeric($idOrUid)) {
            return CustomerModel::where('_id', $idOrUid)->first();
        }

        return CustomerModel::where('_uid', $idOrUid)->first();
    }

    /**
     * Fetch customer datatable source
     *
     * @return  mixed
     *---------------------------------------------------------------- */
    public function fetchCustomerDataTableSource()
    {
        $dataTableConfig = [
            'searchable' => [
                'name',
                'short_description',
            ],
        ];

        return CustomerModel::dataTables($dataTableConfig)->toArray();
    }

    /**
     * Delete $customer record and return response
     *
     * @param  array  $inputData
     * @return  mixed
     *---------------------------------------------------------------- */
    public function deleteCustomer($customer)
    {
        // Check if $customer deleted
        if ($customer->delete()) {
            activityLog(20, $customer->_id, 3);

            return true;
        }

        return false;
    }

    /**
     * Store new customer record and return response
     *
     * @param  array  $inputData
     * @return  mixed
     *---------------------------------------------------------------- */
    public function storeCustomer($inputData)
    {
        $keyValues = [
            'name',
            'status' => 1,
            'countries__id' => $inputData['country'],
            'short_description' => array_get($inputData, 'description'),
            'user_authorities__id' => getUserAuthorityId(),
        ];

        $newCustomer = new CustomerModel();

        // Check if task testing record added then return positive response
        if ($newCustomer->assignInputsAndSave($inputData, $keyValues)) {
            activityLog(20, $newCustomer->_id, 1);

            return $newCustomer;
        }

        return false;
    }

    /**
     * Update customer record and return response
     *
     * @param  object  $customer
     * @param  array  $inputData
     * @return  mixed
     *---------------------------------------------------------------- */
    public function updateCustomer($customer, $inputData)
    {
        // Check if customer updated then return positive response
        if ($customer->modelUpdate($inputData)) {
            activityLog(20, $customer->_id, 2);

            return true;
        }

        return false;
    }

    /**
     * Fetch All Customer
     *
     * @return  mixed
     *---------------------------------------------------------------- */
    public function fetchAll()
    {
        return CustomerModel::leftJoin('countries', 'customers.countries__id', '=', 'countries._id')
            ->select(
                __nestedKeyValues([
                    'customers' => ['*'],
                    'countries' => [
                        '_id AS country_id',
                        'name AS country_name',
                    ],
                ])
            )
            ->get();
    }

    /**
     * Fetch All Customer
     *
     * @return  mixed
     *---------------------------------------------------------------- */
    public function fetchWithCountry($customerId)
    {
        return CustomerModel::leftJoin('countries', 'customers.countries__id', '=', 'countries._id')
            ->where('_id', $customerId)
            ->select(
                __nestedKeyValues([
                    'customers' => ['*'],
                    'countries' => [
                        '_id AS country_id',
                        'name AS country_name',
                    ],
                ])
            )
            ->first();
    }
}
