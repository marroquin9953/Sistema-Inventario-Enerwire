<?php
/*
* ReportEngine.php - Main component file
*
* This file is part of the Report component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Report;

use App\Yantrana\Base\BaseEngine;
use App\Yantrana\Components\Location\Repositories\LocationRepository;
use App\Yantrana\Components\Report\Interfaces\ReportEngineInterface;
use App\Yantrana\Components\Report\Repositories\ReportRepository;

class ReportEngine extends BaseEngine implements ReportEngineInterface
{
    /**
     * @var  ReportRepository - Report Repository
     */
    protected $reportRepository;

    /**
     * @var  LocationRepository  - Location Repository
     */
    protected $locationRepository;

    /**
     * Constructor
     *
     * @param  ReportRepository  $reportRepository - Report Repository
     * @param  LocationRepository  $locationRepository - Location Repository
     * @return  void
     *-----------------------------------------------------------------------*/
    public function __construct(
        ReportRepository $reportRepository,
        LocationRepository $locationRepository
    ) {
        $this->reportRepository = $reportRepository;
        $this->locationRepository = $locationRepository;
    }

    /**
     * Report datatable source
     *
     * @return  array
     *---------------------------------------------------------------- */
    public function prepareSupportData()
    {
        $locations = $this->locationRepository->fetchLocationsOfUser()->toArray();

        if (! __isEmpty($locations)) {
            foreach ($locations as $key => $location) {
                if ($location['status'] == 1) {
                    $locations[$key]['title'] = $location['name'];
                } else {
                    $locations[$key]['title'] = __tr('__title__ ( __status__ )', [
                        '__title__' => $location['name'],
                        '__status__' => techItemString($location['status']),
                    ]);
                }
            }
        }

        return $this->engineReaction(1, [
            'durations' => configItem('durations'),
            'stock_trn_subtypes' => configItem('stock_transactions.sub_types'),
            'locations' => $locations,
        ]);
    }

    /**
     * Calculate Product Tax
     *
     * @return  array
     *---------------------------------------------------------------- */
    public function calculateTaxAmount($data)
    {
        $jsonData = $data['__data'];
        $totalPrice = $data['total_amount'];
        $taxAmount = [];
        if (isset($jsonData['tax_details']) and ! __isEmpty($jsonData['tax_details'])) {
            foreach ($jsonData['tax_details'] as $taxKey => $tax) {
                if (isset($tax['tax_amount']) and isset($tax['title'])) {
                    if ($tax['type'] == 2) {
                        $amount = $totalPrice * ($tax['tax_amount'] / 100);
                        $taxAmount[] = $amount;
                    } else {
                        $taxAmount[] = $tax['tax_amount'];
                    }
                }
            }
        }

        return array_sum($taxAmount);
    }

    /**
     * Report datatable source
     *
     * @return  array
     *---------------------------------------------------------------- */
    public function prepareReportDataTableSource($start, $end, $subtype, $locations)
    {
        $reportCollection = $this->reportRepository
            ->fetchReportDataTableSource($start, $end, $subtype, $locations);

        $requireColumns = [
            '_id',
            '_uid',
            'created_by_user',
            'product_id',
            'title',
            'total_price',
            'total_amount' => function ($key) use ($subtype) {
                $amount = $key['total_amount'];
                if ($subtype == 2) {
                    $amount = $key['total_amount'] + $this->calculateTaxAmount($key);
                }

                return $amount;
            },
            'currency_code',
            'currency_symbol' => function ($key) {
                return  config("__tech.currencies.details.{$key['currency_code']}.symbol", '');
            },
            'location_name',
            'location_id',
            'formatted_tax_title' => function ($key) {
                $totalPrice = $key['total_amount'];

                $jsonData = $key['__data'];
                $taxes = [];
                if (isset($jsonData['tax_details']) and ! __isEmpty($jsonData['tax_details'])) {
                    foreach ($jsonData['tax_details'] as $key => $tax) {
                        if (isset($tax['tax_amount']) and isset($tax['title'])) {
                            $amount = 0;
                            if ($tax['type'] == 2) {
                                $amount = $totalPrice * ($tax['tax_amount'] / 100);

                                $taxes[] = [
                                    'tax_title' => ($tax['title'].'('.$tax['tax_amount'].'%)'),
                                    'amount' => moneyFormat($amount, false, true),
                                ];
                            } else {
                                $taxes[] = [
                                    'tax_title' => $tax['title'],
                                    'amount' => moneyFormat($tax['tax_amount'], false, true),
                                ];
                            }
                        }
                    }
                }

                return $taxes;
            },
            'formatted_tax' => function ($key) {
                return moneyFormat($this->calculateTaxAmount($key), $key['currency_code']);
            },
            'quantity' => function ($key) {
                return round($key['quantity']);
            },
            'formated_created_at' => function ($key) {
                return formatDateTime($key['created_at']);
            },
            'formated_price' => function ($key) {
                return moneyFormat($key['total_price'], $key['currency_code']);
            },
            'formated_total' => function ($key) {
                return moneyFormat($key['total_amount'], $key['currency_code']);
            },
            'formated_amount' => function ($key) use ($subtype) {
                $amount = $key['total_amount'];
                if ($subtype == 2) {
                    $amount = $key['total_amount'] + $this->calculateTaxAmount($key);
                }

                return moneyFormat($amount, $key['currency_code']);
            },
        ];

        return $this->dataTableResponse($reportCollection, $requireColumns);
    }
}
