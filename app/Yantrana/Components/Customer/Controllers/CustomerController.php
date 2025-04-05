<?php
/*
* CustomerController.php - Controller file
*
* This file is part of the Customer component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Customer\Controllers;

use App\Yantrana\Base\BaseController;
use App\Yantrana\Components\Customer\CustomerEngine;
use App\Yantrana\Components\Customer\Requests\CustomerAddRequest;
use App\Yantrana\Components\Customer\Requests\CustomerEditRequest;
use App\Yantrana\Support\CommonPostRequest as Request;

class CustomerController extends BaseController
{
    /**
     * @var  CustomerEngine - Customer Engine
     */
    protected $customerEngine;

    /**
     * Constructor
     *
     * @param  CustomerEngine  $customerEngine - Customer Engine
     * @return  void
     *-----------------------------------------------------------------------*/
    public function __construct(CustomerEngine $customerEngine)
    {
        $this->customerEngine = $customerEngine;
    }

    /**
     * list of Customer
     *
     * @return  json object
     *---------------------------------------------------------------- */
    public function prepareCustomerList()
    {
        return $this->customerEngine
            ->prepareCustomerDataTableSource();
    }

    /**
     * Customer process delete
     *
     * @param  mix  $customerIdOrUid
     * @param  object  $request
     * @return  json object
     *---------------------------------------------------------------- */
    public function processCustomerDelete(Request $request, $customerIdOrUid)
    {
        $processReaction = $this->customerEngine
            ->processCustomerDelete($customerIdOrUid);

        return __processResponse($processReaction, [], [], true);
    }

    /**
     * Customer Add Support Data
     *
     * @return  json object
     *---------------------------------------------------------------- */
    public function prepareCustomerSupportData()
    {
        $processReaction = $this->customerEngine
            ->prepareCustomerSupportData();

        return __processResponse($processReaction, [], [], true);
    }

    /**
     * Customer create process
     *
     * @param  object CustomerListRequest $request
     * @return  json object
     *---------------------------------------------------------------- */
    public function processCustomerCreate(CustomerAddRequest $request)
    {
        $processReaction = $this->customerEngine
            ->processCustomerCreate($request->all());

        return __processResponse($processReaction);
    }

    /**
     * Customer get update data
     *
     * @param  mix  $customerIdOrUid
     * @return  json object
     *---------------------------------------------------------------- */
    public function updateCustomerData($customerIdOrUid)
    {
        $processReaction = $this->customerEngine
            ->prepareCustomerUpdateData($customerIdOrUid);

        return __processResponse($processReaction, [], [], true);
    }

    /**
     * Customer process update
     *
     * @param  mix @param  mix  $customerIdOrUid
     * @param  object CustomerListRequest $request
     * @return  json object
     *---------------------------------------------------------------- */
    public function processCustomerUpdate($customerIdOrUid, CustomerEditRequest $request)
    {
        $processReaction = $this->customerEngine
            ->processCustomerUpdate($customerIdOrUid, $request->all());

        return __processResponse($processReaction, [], [], true);
    }
}
