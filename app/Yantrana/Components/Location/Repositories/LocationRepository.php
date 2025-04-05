<?php
/*
* LocationRepository.php - Repository file
*
* This file is part of the Location component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Location\Repositories;

use App\Yantrana\Base\BaseRepository;
use App\Yantrana\Components\Location\Interfaces\LocationRepositoryInterface;
use App\Yantrana\Components\Location\Models\LocationModel;
use App\Yantrana\Components\Location\Models\UserLocationModel;

class LocationRepository extends BaseRepository implements LocationRepositoryInterface
{
    /**
     * Fetch the record of Location
     *
     * @param    int || string $idOrUid
     * @return    eloquent collection object
     *---------------------------------------------------------------- */
    public function fetch($idOrUid)
    {
        if (is_numeric($idOrUid)) {
            return LocationModel::where('_id', $idOrUid)->first();
        }

        return LocationModel::where('_uid', $idOrUid)->first();
    }

    /**
     * Fetch location datatable source
     *
     * @return  mixed
     *---------------------------------------------------------------- */
    public function fetchLocationDataTableSource($myLocationIds)
    {
        $dataTableConfig = [
            'searchable' => [
                'status',
                'name',
                'location_id',
                'short_description',
            ],
        ];

        return LocationModel::where('status', '!=', 3)
            ->where(function ($query) use ($myLocationIds) {
                if (! canAccess('admin')) {
                    return $query->whereIn('_id', $myLocationIds);
                } else {
                    return $query;
                }
            })
            ->dataTables($dataTableConfig)
            ->toArray();
    }

    /**
     * Delete $location record and return response
     *
     * @param  array  $inputData
     * @return  mixed
     *---------------------------------------------------------------- */
    public function deleteLocation($location, $updateData)
    {
        // Check if $location deleted
        if ($location->modelUpdate($updateData)) {
            activityLog(14, $location->_id, 3, $location->name);

            return true;
        }

        return false;
    }

    /**
     * Store new location record and return response
     *
     * @param  array  $inputData
     * @return  mixed
     *---------------------------------------------------------------- */
    public function storeLocation($inputData)
    {
        $keyValues = [
            'status' => 1,
            'name',
            'location_id',
            'short_description',
            'user_authorities__id' => getUserAuthorityId(),
        ];

        $newLocation = new LocationModel();

        // Check if task testing record added then return positive response
        if ($newLocation->assignInputsAndSave($inputData, $keyValues)) {
            activityLog(14, $newLocation->_id, 1, $newLocation->name);

            return $newLocation;
        }

        return false;
    }

    /**
     * Update location record and return response
     *
     * @param  object  $location
     * @param  array  $inputData
     * @return  mixed
     *---------------------------------------------------------------- */
    public function updateLocation($location, $inputData)
    {
        // Check if location updated then return positive response
        if ($location->modelUpdate($inputData)) {
            activityLog(14, $location->_id, 2, $location->name);

            return true;
        }

        return false;
    }

    /**
     * Fetch All Location for Update Inventory Quantity
     *
     * @return  mixed
     *---------------------------------------------------------------- */
    public function fetchLocations()
    {
        return LocationModel::where('status', 1)->get();
    }

    /**
     * Fetch All Location for Update Inventory Quantity
     *
     * @return  mixed
     *---------------------------------------------------------------- */
    public function fetchLocationsOfUser()
    {
        $query = LocationModel::select('locations._id', 'locations.name', 'locations.status');

        if (canAccess('admin')) {
            return $query->get();
        }

        return $query->join('user_locations', 'locations__id', '=', 'locations._id')
            ->where('user_locations.user_authorities__id', getUserAuthorityId())
            ->get();
    }

    /**
     * Fetch User Location
     *
     * @param  array  $userAuthorityId
     * @return  mixed
     *---------------------------------------------------------------- */
    public function fetchUserLocationsByUserId($userAuthorityId)
    {
        return UserLocationModel::where('user_authorities__id', $userAuthorityId)->get();
    }

    /**
     * Fetch User Location
     *
     * @param  array  $userAuthorityId
     * @return  mixed
     *---------------------------------------------------------------- */
    public function fetchMyLocations($userAuthorityId)
    {
        $locationModel = new LocationModel();

        if (! isAdmin()) {
            $locationModel = $locationModel->where('user_locations.user_authorities__id', $userAuthorityId);

            return $locationModel->join('user_locations', 'locations._id', '=', 'user_locations.locations__id')
                ->where('locations.status', 1)
                ->select(
                    __nestedKeyValues([
                        'user_locations' => [
                            '_id AS user_location_id',
                            'created_at',
                            'status',
                        ],
                        'locations' => [
                            '_id',
                            'name',
                            'short_description',
                        ],
                    ])
                )
                ->get();
        }

        return $locationModel->where('locations.status', 1)
            ->select(
                __nestedKeyValues([
                    'locations' => [
                        '_id',
                        'name',
                        'short_description',
                    ],
                ])
            )
            ->get();
    }

    /**
     * Fetch User Location
     *
     * @param  array  $locationId
     * @return  mixed
     *---------------------------------------------------------------- */
    public function fetchUserLocationsByLocationId($locationId)
    {
        return UserLocationModel::where('locations__id', $locationId)->get();
    }

    /**
     * Store User Location
     *
     * @param  array  $inputData
     * @return  mixed
     *---------------------------------------------------------------- */
    public function storeUserLocation($inputData)
    {
        $newUserLocation = new UserLocationModel();

        // Check if location added successfully
        if ($newUserLocation->prepareAndInsert($inputData)) {
            activityLog(14, $newUserLocation->_id, 9, 'Assign Location');

            return $newUserLocation;
        }

        return false;
    }

    /**
     * Delete User Location
     *
     * @param  array  $userLocationIds
     * @return  mixed
     *---------------------------------------------------------------- */
    public function deleteUserLocations($userLocationIds)
    {
        activityLog(14, null, 3, 'Delete Assign Locations');

        return UserLocationModel::whereIn('_id', $userLocationIds)->delete();
    }
}
