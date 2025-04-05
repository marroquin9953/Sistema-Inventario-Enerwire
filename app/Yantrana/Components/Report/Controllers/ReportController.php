<?php
/*
* ReportController.php - Controller file
*
* This file is part of the Report component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Report\Controllers;

use App\Yantrana\Base\BaseController;
use App\Yantrana\Components\Report\ReportEngine;

class ReportController extends BaseController
{
    /**
     * @var  ReportEngine - Report Engine
     */
    protected $reportEngine;

    /**
     * Constructor
     *
     * @param  ReportEngine  $reportEngine - Report Engine
     * @return  void
     *-----------------------------------------------------------------------*/
    public function __construct(ReportEngine $reportEngine)
    {
        $this->reportEngine = $reportEngine;
    }

    /**
     * Preapre Report support data
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function supportData()
    {
        $processReaction = $this->reportEngine->prepareSupportData();

        return __processResponse($processReaction, [], [], true);
    }

    /**
     * list of Report
     *
     * @return  json object
     *---------------------------------------------------------------- */
    public function prepareReportList($start, $end, $subtype, $locations = '')
    {
        return $this->reportEngine->prepareReportDataTableSource($start, $end, $subtype, $locations);
    }
}
