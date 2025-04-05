<?php
/*
* CustomerEngine.php - Main component file
*
* This file is part of the Customer component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Customer;

use App\Yantrana\Base\BaseEngine;
use App\Yantrana\Components\Customer\Interfaces\CustomerEngineInterface;
use App\Yantrana\Components\Customer\Repositories\CustomerRepository;
use App\Yantrana\Support\Country\Repositories\CountryRepository;

class CustomerEngine extends BaseEngine implements CustomerEngineInterface
{
    /**
     * @var  CustomerRepository - Customer Repository
     */
    protected $customerRepository;

    /**
     * @var CountryRepository - Country Repository
     */
    protected $countryRepository;

    /**
     * Constructor
     *
     * @param  CustomerRepository  $customerRepository - Customer Repository
     * @return  void
     *-----------------------------------------------------------------------*/
    public function __construct(
        CustomerRepository $customerRepository,
        CountryRepository $countryRepository
    ) {
        $this->customerRepository = $customerRepository;
        $this->countryRepository = $countryRepository;
    }

    /**
     * Customer datatable source
     *
     * @return  array
     *---------------------------------------------------------------- */
    public function prepareCustomerDataTableSource()
    {
        $customerCollection = $this->customerRepository
            ->fetchCustomerDataTableSource();
        $requireColumns = [
            '_id',
            '_uid',
            'name',
            'short_description' => function ($key) {
                return str_limit($key['short_description'], configItem('string_limit'));
            },
            'can_edit' => function ($key) {
                return canAccess('manage.customer.write.update');
            },
            'can_delete' => function ($key) {
                return canAccess('manage.customer.write.delete');
            },
        ];

        return $this->dataTableResponse($customerCollection, $requireColumns);
    }

    /**
     * Customer delete process
     *
     * @param  mix  $customerIdOrUid
     * @return  array
     *---------------------------------------------------------------- */
    public function processCustomerDelete($customerIdOrUid)
    {
        $customer = $this->customerRepository->fetch($customerIdOrUid);

        if (__isEmpty($customer)) {
            return $this->engineReaction(18, null, __tr('Customer not found.'));
        }

        if ($this->customerRepository->deleteCustomer($customer)) {
            return $this->engineReaction(1, null, __tr('Customer deleted successfully.'));
        }

        return $this->engineReaction(2, null, __tr('Customer not deleted.'));
    }

    /**
     * Customer Add Support Data
     *
     * @return  array
     *---------------------------------------------------------------- */
    public function prepareCustomerSupportData()
    {
        return $this->engineReaction(1, [
            'countries' => $this->countryRepository->fetchAll(),
        ]);
    }

    /**
     * Customer create
     *
     * @param  array  $inputData
     * @return  array
     *---------------------------------------------------------------- */
    public function processCustomerCreate($inputData)
    {
        if ($this->customerRepository->storeCustomer($inputData)) {
            return $this->engineReaction(1, null, __tr('Customer added successfully.'));
        }

        return $this->engineReaction(2, null, __tr('Customer not added.'));
    }

    /**
     * Customer prepare update data
     *
     * @param  mix  $customerIdOrUid
     * @return  array
     *---------------------------------------------------------------- */
    public function prepareCustomerUpdateData($customerIdOrUid)
    {
        $customer = $this->customerRepository->fetch($customerIdOrUid);

        // Check if $customer not exist then throw not found
        // exception
        if (__isEmpty($customer)) {
            return $this->engineReaction(18, null, __tr('Customer not found.'));
        }

        $updateData = [
            'id' => $customer->_id,
            'name' => $customer->name,
            'description' => $customer->short_description,
            'country' => $customer->countries__id,
        ];

        return $this->engineReaction(1, [
            'customer' => $updateData,
            'countries' => $this->countryRepository->fetchAll(),
        ]);
    }

    /**
     * Customer process update
     *
     * @param  mix  $customerIdOrUid
     * @param  array  $inputData
     * @return  array
     *---------------------------------------------------------------- */
    public function processCustomerUpdate($customerIdOrUid, $inputData)
    {
        $customer = $this->customerRepository->fetch($customerIdOrUid);

        // Check if $customer not exist then throw not found
        // exception
        if (__isEmpty($customer)) {
            return $this->engineReaction(18, null, __tr('Customer not found.'));
        }

        $updateData = [
            'name' => $inputData['name'],
            'short_description' => $inputData['description'],
            'countries__id' => $inputData['country'],
        ];

        // Check if Customer updated
        if ($this->customerRepository->updateCustomer($customer, $updateData)) {
            return $this->engineReaction(1, null, __tr('Customer updated successfully.'));
        }

        return $this->engineReaction(14, null, __tr('Nothing to update.'));
    }
}
