<?php

/*
* DashboardRepository.php - Repository file
*
* This file is part of the Dashboard component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Dashboard\Repositories;

use App\Yantrana\Base\BaseRepository;
use App\Yantrana\Components\Dashboard\Blueprints\DashboardRepositoryBlueprint;
use App\Yantrana\Components\Project\Models\ProjectModel;
use App\Yantrana\Components\Project\Models\TimeRecordModel;
use App\Yantrana\Components\Test\Models\TestExecutionModel;
use App\Yantrana\Components\User\Models\UserAuthorityModel;
use Auth;
use Carbon\Carbon;

class DashboardRepository extends BaseRepository implements DashboardRepositoryBlueprint
{
    /**
     * Fetch Time Entry Of Current Month
     *
     * @return  eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchTimeRecordsWithOptions($options = [])
    {
        if (__isEmpty($options)) {
            return TimeRecordModel::get();
        }

        // current month wise
        if (array_has($options, 'current_month') and $options['current_month'] !== false) {
            $query = TimeRecordModel::where('created_at', '>=', Carbon::now()->startOfMonth());
        }

        // Current Day wise
        if (array_has($options, 'current_day') and ! __isEmpty($options['current_day'])) {
            $query = TimeRecordModel::where('created_at', '=', Carbon::now()->startOfDay());
        }

        if (array_has($options, 'user') and $options['user'] !== false) {
            $query->where('users__id', Auth::id());
        }

        return $query->get();
    }

    /**
     * Fetch all users except admin
     *
     * @return  eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchAllUsers()
    {
        return UserAuthorityModel::leftjoin('users', 'user_authorities.users__id', '=', 'users._id')
            ->where('user_authorities.user_roles__id', '!=', 1)
            ->select(
                __nestedKeyValues([
                    'users' => [
                        '_id',
                        'status',
                    ],
                    'user_authorities' => [
                        'user_roles__id',
                    ],
                ])
            )
            ->get();
    }

    /**
     * Fetch recents projects
     *
     * @return  eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchRecentsProjects()
    {
        $query = new ProjectModel();

        if (! canAccess('admin')) {
            $query = $query->leftjoin('project_users', 'projects._id', '=', 'project_users.projects__id')
                ->where([
                    'project_users.status' => 1,
                    'project_users.users__id' => getUserID(),
                ])->orWhere('projects.users__id', getUserID());
        }

        return $query->where('projects.status', '=', 1)
            ->orderBy('projects.updated_at', 'desc')
            ->take(5)
            ->select(
                __nestedKeyValues([
                    'projects' => [
                        '_id',
                        '_uid',
                        'title',
                        'status',
                        'updated_at',
                    ],
                ])
            )
            ->get();
    }

    /**
     * Fetch recents projects
     *
     * @return  eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchAllTestCases($projectIds)
    {
        $query = new TestExecutionModel();

        $query = $query->leftjoin(
            'test_cases',
            'test_case_executions.test_cases__id',
            '=',
            'test_cases._id'
        )
            ->leftjoin(
                'test_suites',
                'test_cases.test_suites__id',
                '=',
                'test_suites._id'
            );

        if (! canAccess('admin')) {
            $query = $query->whereIn('test_suites.projects__id', $projectIds);
        }

        return $query->select(__nestedKeyValues([
            'test_case_executions' => [
                '_id',
                'result',
            ],
            'test_suites' => [
                'projects__id',
            ],
        ]))
            ->get();
    }
}
