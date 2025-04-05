<?php
/*
* LocationEngine.php - Main component file
*
* This file is part of the Location component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Location;

use App\Yantrana\Base\BaseEngine;
use App\Yantrana\Components\Location\Interfaces\LocationEngineInterface;
use App\Yantrana\Components\Location\Repositories\LocationRepository;
use App\Yantrana\Components\User\Repositories\UserRepository;

class LocationEngine extends BaseEngine implements LocationEngineInterface
{
    /**
     * @var  LocationRepository - Location Repository
     */
    protected $locationRepository;

    /**
     * @var UserRepository - User Repository
     */
    protected $userRepository;

    /**
     * Constructor
     *
     * @param  LocationRepository  $locationRepository - Location Repository
     * @return  void
     *-----------------------------------------------------------------------*/
    public function __construct(
        LocationRepository $locationRepository,
        UserRepository $userRepository
    ) {
        $this->locationRepository = $locationRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * Location datatable source
     *
     * @return  array
     *---------------------------------------------------------------- */
    public function prepareLocationDataTableSource()
    {
        $myLocationIds = [];
        $myLocations = $this->locationRepository->fetchMyLocations(getUserAuthorityId());

        if (! __isEmpty($myLocations)) {
            $myLocationIds = $myLocations->pluck('_id')->toArray();
        }

        $locationCollection = $this->locationRepository->fetchLocationDataTableSource($myLocationIds);

        $requireColumns = [
            '_id',
            '_uid',
            'name',
            'location_id',
            'short_description' => function ($key) {
                return str_limit($key['short_description'], configItem('string_limit'));
            },
            'status' => function ($key) {
                return configItem('location', $key['status']);
            },
            'can_edit' => function () {
                return canAccess('manage.location.write.update');
            },
            'can_delete' => function () {
                return canAccess('manage.location.write.delete');
            },
            'can_assign' => function () {
                return canAccess('manage.location.write.user_assign_process');
            },
        ];

        return $this->dataTableResponse($locationCollection, $requireColumns);
    }

    /**
     * Location delete process
     *
     * @param  mix  $locationIdOrUid
     * @return  array
     *---------------------------------------------------------------- */
    public function processLocationDelete($locationIdOrUid)
    {
        $location = $this->locationRepository
            ->fetch($locationIdOrUid);

        if (__isEmpty($location)) {
            return $this->engineReaction(18, null, __tr('Location not found.'));
        }

        $updateData = [
            'status' => 3, // Deleted
        ];

        if ($this->locationRepository->deleteLocation($location, $updateData)) {
            return $this->engineReaction(1, null, __tr('Location deleted successfully.'));
        }

        return $this->engineReaction(2, null, __tr('Location not deleted.'));
    }

    /**
     * Location Add Support Data
     *
     * @return  array
     *---------------------------------------------------------------- */
    public function prepareLocationSupportData()
    {
        return $this->engineReaction(1, []);
    }

    /**
     * Location create
     *
     * @param  array  $inputData
     * @return  array
     *---------------------------------------------------------------- */
    public function processLocationCreate($inputData)
    {
        if ($this->locationRepository->storeLocation($inputData)) {
            return $this->engineReaction(1, null, __tr('Location added successfully.'));
        }

        return $this->engineReaction(2, null, __tr('Location not added.'));
    }

    /**
     * Location prepare update data
     *
     * @param  mix  $locationIdOrUid
     * @return  array
     *---------------------------------------------------------------- */
    public function prepareLocationUpdateData($locationIdOrUid)
    {
        $location = $this->locationRepository->fetch($locationIdOrUid);

        // Check if $location not exist then throw not found
        // exception
        if (__isEmpty($location)) {
            return $this->engineReaction(18, null, __tr('Location not found.'));
        }

        $locationData = [
            'name' => $location->name,
            'location_id' => $location->location_id,
            'short_description' => $location->short_description,
            'status' => ($location->status == 1) ? true : false,
        ];

        return $this->engineReaction(1, [
            'locationData' => $locationData,
        ]);
    }

    /**
     * Location process update
     *
     * @param  mix  $locationIdOrUid
     * @param  array  $inputData
     * @return  array
     *---------------------------------------------------------------- */
    public function processLocationUpdate($locationIdOrUid, $inputData)
    {
        $location = $this->locationRepository->fetch($locationIdOrUid);

        // Check if $location not exist then throw not found
        // exception
        if (__isEmpty($location)) {
            return $this->engineReaction(18, null, __tr('Location not found.'));
        }

        $updateData = [
            'status' => ($inputData['status']) ? 1 : 2,
            'name' => $inputData['name'],
            'location_id' => $inputData['location_id'],
            'short_description' => $inputData['short_description'],
        ];

        // Check if Location updated
        if ($this->locationRepository->updateLocation($location, $updateData)) {
            return $this->engineReaction(1, null, __tr('Location updated successfully.'));
        }

        return $this->engineReaction(14, null, __tr('Location not updated.'));
    }

    /**
     * Prepare Assign Location Data
     *
     * @param  number  $userId
     * @return  array
     *---------------------------------------------------------------- */
    public function prepareAssignLocationData($userAuthorityId)
    {
        $userAuthority = $this->userRepository->fetchAuthority($userAuthorityId);

        // check if user authority exist
        if (__isEmpty($userAuthority)) {
            return $this->engineReaction(18, null, __tr('User does not exist.'));
        }

        $locations = $this->locationRepository->fetchLocations();
        $locationData = [];

        // Check if locations are exist
        if (! __isEmpty($locations)) {
            foreach ($locations as $key => $location) {
                $locationData[] = [
                    'id' => $location->_id,
                    'name' => $location->name,
                ];
            }
        }

        $locationIds = [];
        $userLocations = $this->locationRepository->fetchUserLocationsByUserId($userAuthorityId);
        if (! __isEmpty($userLocations)) {
            $locationIds = [
                'locations' => $userLocations->pluck('locations__id'),
            ];
        }

        return $this->engineReaction(1, [
            'locationData' => $locationData,
            'locationIds' => $locationIds,
        ]);
    }

    /**
     * Process Assign Location
     *
     * @param  array  $inputData
     * @param  number  $userId
     * @return  array
     *---------------------------------------------------------------- */
    public function processAssignLocation($inputData, $userAuthorityId)
    {
        $userAuthority = $this->userRepository->fetchAuthority($userAuthorityId);

        // check if user authority exist
        if (__isEmpty($userAuthority)) {
            return $this->engineReaction(18, null, __tr('User does not exist.'));
        }

        $isUpdated = false;
        $storeData = [];

        // Fetch All User Locations
        $userLocations = $this->locationRepository->fetchUserLocationsByUserId($userAuthorityId);

        if (! __isEmpty($userLocations)) {
            // Get Stored location ids
            $locationIds = $userLocations->pluck('locations__id')->toArray();
            // Get already added location ids
            $addedLocationIds = array_diff($inputData['locations'], $locationIds);
            // Get removed location ids
            $removedLocationIds = array_diff($locationIds, $inputData['locations']);
            // Search for removed location ids
            $inputData['locations'] = $addedLocationIds;
            // Get User location ids
            $userLocationIds = $userLocations->whereIn('locations__id', $removedLocationIds)->pluck('_id');
            // delete user locations
            if ($this->locationRepository->deleteUserLocations($userLocationIds)) {
                $isUpdated = true;
            }
        }

        if (! __isEmpty($inputData['locations'])) {
            foreach ($inputData['locations'] as $key => $input) {
                $storeData[] = [
                    'status' => 1,
                    'user_authorities__id' => $userAuthority->_id,
                    'locations__id' => $input,
                ];
            }
            // Check if location stored successfully
            if ($this->locationRepository->storeUserLocation($storeData)) {
                $isUpdated = true;
            }
        }

        if ($isUpdated) {
            return  $this->engineReaction(1, null, __tr('Location assigned successfully.'));
        }

        return  $this->engineReaction(2, null, __tr('Location not added.'));
    }

    /**
     * Process Assign User
     *
     * @param  number  $locationId
     * @return  array
     *---------------------------------------------------------------- */
    public function prepareAssignUserData($locationId)
    {
        $location = $this->locationRepository->fetch($locationId);

        // Check if $location not exist then throw not found
        // exception
        if (__isEmpty($location)) {
            return $this->engineReaction(18, null, __tr('Location not found.'));
        }

        $userLocations = $this->locationRepository->fetchUserLocationsByLocationId($locationId);

        $userAuthorityIds = [];
        // Check if user locations are exist
        if (! __isEmpty($userLocations)) {
            $userAuthorityIds = [
                'users' => $userLocations->pluck('user_authorities__id'),
            ];
        }

        $userCollection = $this->userRepository->fetchUsersWithAuthority();
        $userData = [];
        if (! __isEmpty($userCollection)) {
            foreach ($userCollection as $key => $user) {
                if ($user->user_roles__id != 1) {
                    $userData[] = [
                        'id' => $user->authority_id,
                        'name' => $user->first_name.' '.$user->last_name,
                    ];
                }
            }
        }

        return $this->engineReaction(1, [
            'userAuthorityIds' => $userAuthorityIds,
            'userData' => $userData,
        ]);
    }

    /**
     * Process Assign User
     *
     * @param  array  $inputData
     * @param  number  $locationId
     * @return  array
     *---------------------------------------------------------------- */
    public function processAssignUser($inputData, $locationId)
    {
        $location = $this->locationRepository->fetch($locationId);

        // Check if $location not exist then throw not found
        // exception
        if (__isEmpty($location)) {
            return $this->engineReaction(18, null, __tr('Location not found.'));
        }

        $isUpdated = false;
        $storeData = [];

        $userLocations = $this->locationRepository->fetchUserLocationsByLocationId($locationId);

        if (! __isEmpty($userLocations)) {
            // Get Stored user ids
            $userAuthorityIds = $userLocations->pluck('user_authorities__id')->toArray();
            // Get already added user ids
            $addedUserIds = array_diff($inputData['users'], $userAuthorityIds);
            // Get removed user ids
            $removedUserIds = array_diff($userAuthorityIds, $inputData['users']);
            // Search for removed user ids
            $inputData['users'] = $addedUserIds;
            // Get User user ids
            $userUserLocationIds = $userLocations->whereIn('user_authorities__id', $removedUserIds)->pluck('_id');
            // delete user location
            if ($this->locationRepository->deleteUserLocations($userUserLocationIds)) {
                $isUpdated = true;
            }
        }

        if (! __isEmpty($inputData['users'])) {
            foreach ($inputData['users'] as $key => $input) {
                $storeData[] = [
                    'status' => 1,
                    'user_authorities__id' => $input,
                    'locations__id' => $location->_id,
                ];
            }
            // Check if location stored successfully
            if ($this->locationRepository->storeUserLocation($storeData)) {
                $isUpdated = true;
            }
        }

        if ($isUpdated) {
            return  $this->engineReaction(1, null, __tr('User assigned successfully.'));
        }

        return  $this->engineReaction(2, null, __tr('Location not added.'));
    }
}
