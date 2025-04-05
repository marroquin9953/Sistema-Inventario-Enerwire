<?php
/*
* LocationController.php - Controller file
*
* This file is part of the Location component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Location\Controllers;

use App\Yantrana\Base\BaseController;
use App\Yantrana\Components\Location\LocationEngine;
use App\Yantrana\Components\Location\Requests\AssignLocationRequest;
use App\Yantrana\Components\Location\Requests\AssignUserRequest;
use App\Yantrana\Components\Location\Requests\LocationAddRequest;
use App\Yantrana\Components\Location\Requests\LocationEditRequest;
use App\Yantrana\Support\CommonPostRequest as Request;

class LocationController extends BaseController
{
    /**
     * @var  LocationEngine - Location Engine
     */
    protected $locationEngine;

    /**
     * Constructor
     *
     * @param  LocationEngine  $locationEngine - Location Engine
     * @return  void
     *-----------------------------------------------------------------------*/
    public function __construct(LocationEngine $locationEngine)
    {
        $this->locationEngine = $locationEngine;
    }

    /**
     * list of Location
     *
     * @return  json object
     *---------------------------------------------------------------- */
    public function prepareLocationList()
    {
        return $this->locationEngine
          ->prepareLocationDataTableSource();
    }

    /**
     * Location process delete
     *
     * @param  mix  $locationIdOrUid
     * @return  json object
     *---------------------------------------------------------------- */
    public function processLocationDelete(Request $request, $locationIdOrUid)
    {
        $processReaction = $this->locationEngine
          ->processLocationDelete($locationIdOrUid);

        return __processResponse($processReaction, [], [], true);
    }

    /**
     * Location Add Support Data
     *
     * @return  json object
     *---------------------------------------------------------------- */
    public function prepareLocationSupportData()
    {
        $processReaction = $this->locationEngine
          ->prepareLocationSupportData();

        return __processResponse($processReaction, [], [], true);
    }

    /**
     * Location create process
     *
     * @param  object LocationListRequest $request
     * @return  json object
     *---------------------------------------------------------------- */
    public function processLocationCreate(LocationAddRequest $request)
    {
        $processReaction = $this->locationEngine
          ->processLocationCreate($request->all());

        return __processResponse($processReaction);
    }

    /**
     * Location get update data
     *
     * @param  mix  $locationIdOrUid
     * @return  json object
     *---------------------------------------------------------------- */
    public function updateLocationData($locationIdOrUid)
    {
        $processReaction = $this->locationEngine
          ->prepareLocationUpdateData($locationIdOrUid);

        return __processResponse($processReaction, [], [], true);
    }

    /**
     * Location process update
     *
     * @param  mix @param  mix  $locationIdOrUid
     * @param  object LocationListRequest $request
     * @return  json object
     *---------------------------------------------------------------- */
    public function processLocationUpdate($locationIdOrUid, LocationEditRequest $request)
    {
        $processReaction = $this->locationEngine
          ->processLocationUpdate($locationIdOrUid, $request->all());

        return __processResponse($processReaction, [], [], true);
    }

    /**
     * Get Assign Location Data
     *
     * @param  number  $userAuthorityId
     * @return  json object
     *---------------------------------------------------------------- */
    public function getAssignLocationData($userAuthorityId)
    {
        $processReaction = $this->locationEngine
          ->prepareAssignLocationData($userAuthorityId);

        return __processResponse($processReaction, [], [], true);
    }

    /**
     * Process ASsign Location
     *
     * @param  number  $userId
     * @return  json object
     *---------------------------------------------------------------- */
    public function processAssignLocation(AssignLocationRequest $request, $userAuthorityId)
    {
        $processReaction = $this->locationEngine->processAssignLocation($request->all(), $userAuthorityId);

        return __processResponse($processReaction, [], [], true);
    }

    /**
     * Get Assign User
     *
     * @param  number  $locationId
     * @return  json object
     *---------------------------------------------------------------- */
    public function getAssignUserData($locationId)
    {
        $processReaction = $this->locationEngine
          ->prepareAssignUserData($locationId);

        return __processResponse($processReaction, [], [], true);
    }

    /**
     * Process Assign User
     *
     * @param  number  $locationId
     * @return  json object
     *---------------------------------------------------------------- */
    public function processAssignUser(AssignUserRequest $request, $locationId)
    {
        $processReaction = $this->locationEngine
          ->processAssignUser($request->all(), $locationId);

        return __processResponse($processReaction, [], [], true);
    }
}
