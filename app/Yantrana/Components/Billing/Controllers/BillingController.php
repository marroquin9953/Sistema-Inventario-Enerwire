<?php
/*
* BillingController.php - Controller file
*
* This file is part of the Billing component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Billing\Controllers;

use App\Yantrana\Base\BaseController;
use App\Yantrana\Components\Billing\BillingEngine;
use App\Yantrana\Components\Billing\Requests\BillAddRequest;
use App\Yantrana\Components\Billing\Requests\BillEditRequest;

class BillingController extends BaseController
{
    /**
     * @var  BillingEngine - Billing Engine
     */
    protected $billingEngine;

    /**
     * Constructor
     *
     * @param  BillingEngine  $billingEngine - Billing Engine
     * @return  void
     *-----------------------------------------------------------------------*/
    public function __construct(BillingEngine $billingEngine)
    {
        $this->billingEngine = $billingEngine;
    }

    /**
     * list of Billing
     *
     * @return  json object
     *---------------------------------------------------------------- */
    public function prepareBillingList()
    {
        return $this->billingEngine
          ->prepareBillingDataTableSource();
    }

    /**
     * Get Billing Add Support Data
     *
     * @return  json object
     *---------------------------------------------------------------- */
    public function getAddSupportData()
    {
        $processReaction = $this->billingEngine->prepareAddSupportData();

        return __processResponse($processReaction, [], [], true);
    }

    /**
     * Get Billing Add Support Data
     *
     * @return  json object
     *---------------------------------------------------------------- */
    public function getEditSupportData($billUid)
    {
        $processReaction = $this->billingEngine->prepareEditSupportData($billUid);

        return __processResponse($processReaction, [], [], true);
    }

    /**
     * Get Billing details Support Data
     *
     * @return  json object
     *---------------------------------------------------------------- */
    public function getDetailSupportData($billUid)
    {
        $processReaction = $this->billingEngine->prepareBillDetails($billUid);

        return __processResponse($processReaction, [], [], true);
    }

    /**
     * Get Search Combination Data
     *
     * @return  json object
     *---------------------------------------------------------------- */
    public function getSearchCombinationData($searchTerm)
    {
        $processReaction = $this->billingEngine->prepareSearchCombinationData($searchTerm);

        return __processResponse($processReaction, [], [], true);
    }

    /**
     * Get Combination locationwise
     *
     * @return  json object
     *---------------------------------------------------------------- */
    public function getCombinationLocationWise($combinationId, $productId)
    {
        $processReaction = $this->billingEngine->prepareLocationWiseCombination($combinationId, $productId);

        return __processResponse($processReaction, [], [], true);
    }

    /**
     * Store Product Bill
     *
     * @return  json object
     *---------------------------------------------------------------- */
    public function storeProductBill(BillAddRequest $requests)
    {
        $processReaction = $this->billingEngine->processStoreProductBill($requests->all());

        return __processResponse($processReaction, [], [], true);
    }

    /**
     * Store Product Bill
     *
     * @return  json object
     *---------------------------------------------------------------- */
    public function updateProductBill($billId, BillEditRequest $requests)
    {
        $processReaction = $this->billingEngine->processUpdateProductBill($billId, $requests->all());

        $inactiveProducts = [];

        return __processResponse($processReaction, [], [], true);
    }

    /**
     * Print Bill
     *
     * @param  string  $billId
     * @return  json object
     *---------------------------------------------------------------- */
    public function printBill($billId)
    {
        $processReaction = $this->billingEngine->processPrintBill($billId);

        return $this->loadView('billing.download-pdf', ['billData' => $processReaction['data']['billData']]);
    }

    /**
     * Download Bill as Pdf
     *
     * @param  string  $billId
     * @return  json object
     *---------------------------------------------------------------- */
    public function downloadPdf($billId)
    {
        return $this->billingEngine->processDownloadPdf($billId);
    }

    /**
     * Bill Delete Process
     *
     * @param  object  $request
     * @param  string  $billId
     * @return json object
     *---------------------------------------------------------------- */
    public function billDeleteProcess($billId)
    {
        $processReaction = $this->billingEngine->billDeleteProcess($billId);

        return __processResponse($processReaction, [], [], true);
    }

    /**
     * bill stock transaction delete Process
     *
     * @param  object  $request
     * @param  string  $transactionId
     * @return json object
     *---------------------------------------------------------------- */
    public function deleteStockTransaction($billId, $transactionId)
    {
        $processReaction = $this->billingEngine->deleteTransactionProcess($billId, $transactionId);

        return __processResponse($processReaction, [], [], true);
    }
}
