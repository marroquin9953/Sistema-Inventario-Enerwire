<?php
/*
* ReportRepository.php - Repository file
*
* This file is part of the Report component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Report\Repositories;

use App\Yantrana\Base\BaseRepository;
use App\Yantrana\Components\Inventory\Models\StockTransactionModel;
use App\Yantrana\Components\Report\Interfaces\ReportRepositoryInterface;
use DB;

class ReportRepository extends BaseRepository implements ReportRepositoryInterface
{
    /**
     * Fetch report datatable source
     *
     * @return  mixed
     *---------------------------------------------------------------- */
    public function fetchReportDataTableSource($start, $end, $subtype, $locations)
    {
        $dataTableConfig = [
            'searchable' => [
                'created_by_user' => DB::raw('CONCAT(first_name, " ", last_name)'), 'product_id', 'title', 'location_name' => 'locations.name', 'location_id',
            ],
            'fieldAlias' => [
                'created_by_user' => DB::raw('CONCAT(first_name, " ", last_name)'),
                'formated_amount' => 'stock_transactions.total_amount',
                'location_name' => 'locations.name',
                'formated_created_at' => 'stock_transactions.created_at',
                'formated_price' => 'stock_transactions.total_price',
            ],
        ];

        $subtype = (int) $subtype;

        if ($locations != 'null' and ! __isEmpty($locations)) {
            $locations = explode(',', $locations);
        } else {
            $locations = [];
        }

        return StockTransactionModel::join('product_combinations', 'product_combinations._id', '=', 'stock_transactions.product_combinations__id')
            ->join('user_authorities', 'stock_transactions.user_authorities__id', 'user_authorities._id')
            ->leftJoin('users', 'user_authorities.users__id', '=', 'users._id')
            ->leftJoin('locations', 'locations._id', '=', 'stock_transactions.locations__id')
            ->whereBetween(DB::raw('DATE(stock_transactions.created_at)'), [$start, $end])
            ->when($subtype, function ($q) use ($subtype) {
                $q->where('stock_transactions.sub_type', $subtype);
            })
            ->when($locations, function ($q) use ($locations) {
                $q->whereIn('stock_transactions.locations__id', $locations);
            })
            ->select(
                __nestedKeyValues([
                    'stock_transactions.*',
                    'product_combinations' => [
                        'product_id', 'title',
                    ],
                    'product_combinations' => [
                        'product_id', 'title',
                    ],
                    'locations' => [
                        'name AS location_name', 'location_id',
                    ],
                    'users' => [
                        DB::raw('CONCAT(first_name, " ", last_name) as created_by_user'),
                    ],
                ])
            )
            ->dataTables($dataTableConfig)
            ->toArray();
    }
}
