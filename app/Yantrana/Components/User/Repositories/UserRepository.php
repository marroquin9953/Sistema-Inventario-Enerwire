<?php
/*
* UserRepository.php - Repository file
*
* This file is part of the User component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\User\Repositories;

use App\Yantrana\Base\BaseRepository;
use App\Yantrana\Components\User\Blueprints\UserRepositoryBlueprint;
use App\Yantrana\Components\User\Models\EmailChangeRequest;
use App\Yantrana\Components\User\Models\LoginAttempt;
use App\Yantrana\Components\User\Models\LoginLog;
use App\Yantrana\Components\User\Models\PasswordReset;
use App\Yantrana\Components\User\Models\User as UserModel;
use App\Yantrana\Components\User\Models\UserAuthorityModel;
use App\Yantrana\Components\User\Models\UserProfile as UserProfileModel;
use App\Yantrana\Components\User\Models\UserRole as UserRoleModel;
use App\Yantrana\Services\YesTokenAuth\TokenRegistry\Models\TokenRegistryModel;
use Auth;
use Carbon\Carbon;
use DB;
use Request;
use YesSecurity;

class UserRepository extends BaseRepository implements UserRepositoryBlueprint
{
    /**
     * @var UserModel - User Model
     */
    protected $user;

    /**
     * @var LoginLog - LOgin Model
     */
    protected $loginLog;

    /**
     * @var TokenRegistryModel - Token Registry Model
     */
    protected $tokenRegistryModel;

    /**
     * @var LoginLog - LOgin Model
     */
    protected $userProfileModel;

    /**
     * @var UserRole - UserRole Model
     */
    protected $userRoleModel;

    /**
     * @var UserAuthorityModel - UserAuthority  Model
     */
    protected $userAuthorityModel;

    /**
     * Constructor.
     *
     * @param  UserModel  $user - User Model
     *-----------------------------------------------------------------------*/
    public function __construct(
        UserModel $user,
        LoginLog $loginLog,
        UserProfileModel $userProfileModel,
        UserRoleModel $userRoleModel,
        UserAuthorityModel $userAuthorityModel,
        TokenRegistryModel $tokenRegistryModel
    ) {
        $this->user = $user;
        $this->loginLog = $loginLog;
        $this->userProfileModel = $userProfileModel;
        $this->userRoleModel = $userRoleModel;
        $this->userAuthorityModel = $userAuthorityModel;
        $this->tokenRegistryModel = $tokenRegistryModel;
    }

    /**
     * Fetch the records of Invoices
     *
     * @return  eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchUsersWithOptions($options = [])
    {
        if (__isEmpty($options)) {
            return UserModel::get();
        }

        // current month wise
        if (array_has($options, 'current_month') and $options['current_month'] !== false) {
            $query = UserModel::where('created_at', '>=', Carbon::now()->startOfMonth());
        }

        // Current Day wise
        if (array_has($options, 'current_day') and ! __isEmpty($options['current_day'])) {
            $query = UserModel::where('created_at', '=', Carbon::now()->startOfDay());
        }

        if (array_has($options, 'status') and ! __isEmpty($options['status'])) {
            $query = UserModel::where('status', '=', $options['status']);
        }

        return $query->get();
    }

    /**
     * Fetch the records of Invoices
     *
     * @return  eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchTeamMbemberAndCustomers()
    {
        return UserAuthorityModel::whereIn('user_roles__id', [2, 3])->get();
    }

    /**
     * Fetch users
     *
     * @return  eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchUsersIDs($options = [])
    {
        $query = $this->user->join('user_authorities', 'users._id', 'user_authorities.users__id');

        if (array_has($options, 'admin') && ! __isEmpty($options['admin'])) {
            return $query->where('user_authorities.user_roles__id', 1)->pluck('users._uid'); // admin
        }
    }

    /**
     * Fetch users
     *
     * @return  eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchAdmin()
    {
        return UserAuthorityModel::where('user_roles__id', 1)->select('_id')->first();
    }

    /**
     * Fetch users for manage section.
     *
     * @param number status
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchUsers($status)
    {
        $dataTableConfig = [
            'fieldAlias' => [
                'name' => 'users.first_name',
                'creation_date' => 'users.created_at',
                'user_role' => 'user_authorities.user_roles__id',
            ],
            'searchable' => [
                'users.first_name',
                'users.last_name',
                'users.email',
                'username',
            ],
        ];

        return  $this->user
            ->join('user_authorities', 'users._id', 'user_authorities.users__id')
            ->join('user_roles', 'user_authorities.user_roles__id', '=', 'user_roles._id')
            ->with([
                'profile' => function ($profile) {
                    $profile->select('_id', 'users__id', 'profile_picture');
                },
            ])
            ->where('users.status', $status)
            ->select(
                'users._id',
                'users._uid',
                'user_authorities.user_roles__id',
                'user_authorities._id AS user_authority_id',
                'users.status',
                DB::raw('CONCAT(users.first_name, " ", users.last_name) AS name'),
                'users.email',
                'username',
                'users.created_at',
                'users.updated_at',
                'user_roles.title'
            )
            ->dataTables($dataTableConfig)
            ->toArray();
    }

    /**
     * Fetch User by id
     *
     * @param  int  $userId
     * @return void
     *-----------------------------------------------------------------------*/
    public function fetchUser($userId)
    {
        return $this->user
            ->with([
                'profile' => function ($profile) {
                    $profile->select('_id', 'users__id', 'address_line_1', 'address_line_2', 'countries__id');
                },
            ])
            ->join('user_authorities', 'users._id', '=', 'user_authorities.users__id')
            ->join('user_roles', 'user_authorities.user_roles__id', '=', 'user_roles._id')
            ->select(
                'users._id',
                'users._uid',
                'user_authorities.user_roles__id',
                'users.status',
                DB::raw('CONCAT(users.first_name, " ", users.last_name) AS name'),
                'users.email',
                'username',
                'users.created_at',
                'users.updated_at',
                'users.first_name',
                'users.last_name',
                'user_roles.title AS userRoleTitle'
            )
            ->where('users._id', $userId)
            ->first();
    }

    /**
     * Fetch User by id
     *
     * @param  array  $userId
     * @return Eloquent collection object
     *-----------------------------------------------------------------------*/
    public function fetchUserInfoByIds($userId)
    {
        return $this->user->whereIn('_id', $userId)->select('_id', 'email', DB::raw('CONCAT(users.first_name, " ", users.last_name) AS full_name'))->get();
    }

    /**
     * Fetch User by id
     *
     * @param  array  $userId
     * @return Eloquent collection object
     *-----------------------------------------------------------------------*/
    public function fetchActiveUsersByIds($userId)
    {
        return $this->user->where('status', 1)->whereIn('_id', $userId)->get();
    }

    /**
     * Fetch the record of user
     *
     * @param    int || string $idOrUid
     * @return    eloquent collection object
     *---------------------------------------------------------------- */
    public function fetch($idOrUid)
    {
        if (is_numeric($idOrUid)) {
            return $this->user->where('_id', $idOrUid)->first();
        }

        return $this->user->where('_uid', $idOrUid)->first();
    }

    /**
     * Fetch never activated users
     *
     * @return void
     *-----------------------------------------------------------------------*/
    public function updateNeverActivatedUser()
    {
        $user = $this->user
            ->where(
                DB::raw('UNIX_TIMESTAMP(created_at)'),
                '<',
                time() - configItem('account_activation')
            )
            ->whereStatus(12) // never activated
            ->first();

        if (! __isEmpty($user)) {
            if ($user->modelUpdate(['status' => 5])) {
                activityLog('User', $user->_id, 'Update');
            }
        }
    }

    /**
     * Update login attempts.
     *---------------------------------------------------------------- */
    public function updateLoginAttempts()
    {
        $ipAddress = Request::getClientIp();
        $loginAttempt = LoginAttempt::where('ip_address', $ipAddress)
            ->first();

        // Check if login attempt record exist for this ip address
        if (! empty($loginAttempt)) {
            $loginAttempt->attempts = $loginAttempt->attempts + 1;
            $loginAttempt->save();
        } else {
            $newLoginAttempt = new LoginAttempt();

            $newLoginAttempt->ip_address = $ipAddress;
            $newLoginAttempt->attempts = 1;
            //$newLoginAttempt->created_at = currentDateTime();
            $newLoginAttempt->save();
            activityLog('User Login Attempt', $newLoginAttempt->_id, 'Update');
        }
    }

    /**
     * Clear login attempts.
     *---------------------------------------------------------------- */
    public function clearLoginAttempts()
    {
        LoginAttempt::where('ip_address', Request::getClientIp())->delete();
    }

    /**
     * Fetch login attempts based on ip address.
     *
     * @return number
     *---------------------------------------------------------------- */
    public function fetchLoginAttemptsCount()
    {
        $loginAttempt = LoginAttempt::where(
            'ip_address',
            Request::getClientIp()
        )
            ->select('attempts')
            ->first();

        if (! empty($loginAttempt)) {
            return $loginAttempt->attempts;
        }

        return 0;
    }

    /**
     * This method handle the login type
     * if credentials match permit the authentication creating login -
     * log & return boolean response.
     *
     * @param  array  $input
     * @param  number  $loginType
     * @return bool
     *---------------------------------------------------------------- */
    public function login($input, $loginType)
    {
        if ($loginType === 1) { // login using password
            return $this->loginWithPassword($input);
        }

        return $this->loginUsingId($input['userId']); // social login
    }

    /**
     * Handle user login attempts based on passed user credentials,
     * if credentials match permit the authentication creating login -
     * log & return boolean response.
     *
     * @param  array  $input
     * @return bool
     *---------------------------------------------------------------- */
    protected function loginWithPassword($input)
    {
        $isLoggedIn = false;

        // set credentials for login attempt.
        $credentialsWithEmail = [
            'email' => $input['emailOrUsername'],
            'status' => 1,            // active
            'password' => $input['password'],
        ];

        $credentialsWithUsername = [
            'username' => $input['emailOrUsername'],
            'status' => 1,            // active
            'password' => $input['password'],
        ];

        $rememberme = isset($input['remember_me']) ? $input['remember_me'] : false;

        // Try to login via email
        if (Auth::attempt(
            $credentialsWithEmail,
            $rememberme
        )) {
            $isLoggedIn = true;
        }

        // If $isLoggedIn not by email then try to login via username
        if (
            $isLoggedIn === false
            and Auth::attempt($credentialsWithUsername, $rememberme)
        ) {
            $isLoggedIn = true;
        }

        // Get logged in if credentials valid
        if ($isLoggedIn) {
            $this->clearLoginAttempts(); // make login log entry

            $user = Auth::user();
            $loginLog = new $this->loginLog();
            $loginLog->user_id = $user->_id;
            $loginLog->email = __isEmpty($user->email) ? null : $user->email;
            $loginLog->role = getUserAuthInfo('designation');
            $loginLog->ip_address = Request::getClientIp();
            $loginLog->save();

            $userFullName = $user->first_name.' '.$user->last_name;
            activityLog(1, $loginLog->_id, 1, $userFullName);

            return true;
        }

        // If authentication failed
        $this->updateLoginAttempts();   // update login attempts

        return false;
    }

    /**
     * Handle user login attempts based on passed user credentials,
     * if credentials match permit the authentication creating login -
     * log & return boolean response.
     *
     * @param  array  $userId
     * @return bool
     *---------------------------------------------------------------- */
    public function loginUsingId($userId)
    {
        // Get logged in if credentials valid
        if (Auth::loginUsingId($userId)) {
            $this->clearLoginAttempts(); // make login log entry

            $user = Auth::user();
            $loginLog = new $this->loginLog();
            $loginLog->user_id = $user->_id;
            $loginLog->email = __isEmpty($user->email) ? null : $user->email;
            $loginLog->role = getUserAuthInfo('designation');
            $loginLog->ip_address = Request::getClientIp();
            $loginLog->save();
            //activityLog('User Login', $loginLog->_id, 'Create');
            return true;
        }

        // If authentication failed
        $this->updateLoginAttempts();   // update login attempts

        return false;
    }

    /**
     * Fetch active user using email address & return response.
     *
     * @param  string  $email
     * @param  bool  $selectRecord
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchActiveUserByEmail($usernameOremail, $selectRecord = false)
    {
        $activeUser = $this->user
            ->where('status', 1)
            ->where('email', $usernameOremail)
            ->orWhere('username', $usernameOremail);

        if ($selectRecord) {
            $activeUser->select(
                '_id',
                'first_name',
                'last_name',
                'email',
                'username'
            );
        }

        return $activeUser->first();
    }

    /**
     * Store password reminder & return response.
     *
     * @param  string  $email
     * @param  string  $token
     * @return bool
     *---------------------------------------------------------------- */
    public function storePasswordReminder($email, $token)
    {
        $passwordReminder = new PasswordReset();

        $passwordReminder->email = $email;
        $passwordReminder->token = $token;
        $passwordReminder->created_at = Carbon::now()->toDateTimeString();

        return $passwordReminder->save();
        activityLog('User Password Remainder', $passwordReminder->_id, 'Create');
    }

    /**
     * Delete old password reminder.
     *
     * @param  string  $email
     * @return bool
     *---------------------------------------------------------------- */
    public function deleteOldPasswordReminder($email)
    {
        $expiryTime = time() - configItem('account.password_reminder_expiry')
            * 60 * 60;

        return PasswordReset::where('email', $email)
            ->orWhere(
                DB::raw('UNIX_TIMESTAMP(created_at)'),
                '<',
                $expiryTime
            )
            ->delete();
    }

    /**
     * Fetch password reminder count.
     *
     * @param  string  $reminderToken
     * @param  string  $email
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchPasswordReminderCount($reminderToken, $email = null)
    {
        return PasswordReset::where(function ($query) use ($reminderToken, $email) {
            $query->where('token', $reminderToken);

            if (! __isEmpty($email)) {
                $query->where('email', $email);
            }
        })
            ->get()
            ->count();
    }

    /**
     * Reset password.
     *
     * @param  object  $user
     * @param  string  $newPassword
     * @return bool
     *---------------------------------------------------------------- */
    public function resetPassword($user, $newPassword)
    {
        $user->password = bcrypt($newPassword);

        if ($user->save()) {  // Check for if user password reset
            $this->deleteOldPasswordReminder($user->email);
            activityLog('User', $user->_id, 'Password Reset');

            return true;
        }

        return false;
    }

    /**
     * Update password.
     *
     * @param  object  $user
     * @param  string  $newPassword
     * @return bool
     *---------------------------------------------------------------- */
    public function updatePassword($user, $newPassword)
    {
        $user->password = bcrypt($newPassword);

        if ($user->save()) {
            $userName = $user->first_name.' '.$user->last_name;
            activityLog(5, $user->_id, 2, $userName);

            return true;
        }

        return false;
    }

    /**
     * Store user new email reminder.
     *
     * @param  string  $newEmail
     * @param  string  $activationKey
     * @return bool
     *---------------------------------------------------------------- */
    public function storeNewEmailReminder($newEmail, $activationKey)
    {
        $tempEmail = new EmailChangeRequest();

        $tempEmail->activation_key = $activationKey;
        $tempEmail->new_email = $newEmail;
        $tempEmail->users__id = Auth::id();

        if ($tempEmail->save()) {
            activityLog('User Temp Mail', $tempEmail->_id, 'Create');

            return $tempEmail;
        }

        return false;
    }

    /**
     * Delete old email change request.
     *
     * @param  string  $newEmail
     * @return bool
     */
    public function deleteOldEmailChangeRequest($newEmail = null)
    {
        $userID = Auth::id();
        $expiryTime = time() - configItem('account.change_email_expiry')
            * 60 * 60;

        return EmailChangeRequest::where([
            'new_email' => $newEmail,
            'users__id' => $userID,
        ])
            ->orWhere(
                DB::raw('UNIX_TIMESTAMP(created_at)'),
                '<',
                $expiryTime
            )
            ->orWhere(['users__id' => $userID])
            ->delete();
    }

    /**
     * Fetch temparary email.
     *
     * @param  number  $userID
     * @param  string  $activationKey
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchTempEmail($userID, $activationKey)
    {
        return EmailChangeRequest::where([
            'activation_key' => $activationKey,
            'users__id' => $userID,
        ])
            ->select('new_email')
            ->first();
    }

    /**
     * Fetch change email requested.
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchChangeEmailRequested()
    {
        return EmailChangeRequest::where('users__id', Auth::id())
            ->select('new_email')
            ->first();
    }

    /**
     * Update user email.
     *
     * @param  string  $newEmail
     * @return bool
     *---------------------------------------------------------------- */
    public function updateEmail($newEmail)
    {
        $user = Auth::user();

        $user->email = strtolower($newEmail);

        // Check if user email updated
        if ($user->save()) {
            $this->deleteOldEmailChangeRequest($newEmail);
            $userName = $user->first_name.' '.$user->last_name;
            activityLog(15, $user->_id, 2, $userName, $userName.' Change email to '.$user->email);

            return true;
        }

        return false;
    }

    /**
     * Store new user.
     *
     * @param  array  $input
     * @return mixed
     *---------------------------------------------------------------- */
    public function storeNewUser($input, $isSocialUser = false)
    {
        $newUser = new $this->user();

        if ($isSocialUser === true) {
            $status = 1;
        } else {
            $status = getConfigurationSettings('activation_required_for_new_user') == 1
                ? 12 // never activated user
                : 1; // Activated
        }

        $newUser->email = strtolower($input['email']);
        $newUser->password = bcrypt($input['password']);
        $newUser->status = $status;    // Activated
        $newUser->remember_token = YesSecurity::generateUid();
        $newUser->first_name = $input['first_name'];
        $newUser->last_name = $input['last_name'];

        // Check if user stored
        if ($newUser->save()) {
            $profile = new $this->userProfileModel();
            activityLog('User', $newUser->_id, 'Create');
            $profile->users__id = $newUser->_id;
            $profile->countries__id = __ifIsset($input['country']) ? $input['country'] : null;

            $profile->save();

            $userAuthority = new $this->userAuthorityModel();

            $userAuthority->status = 1;

            $userAuthority->users__id = $newUser->_id;

            $userAuthority->user_roles__id = 3;

            return $newUser;
        }

        return [];
    }

    /**
     * Fetch never activated user.
     *
     * @param  number  $userID
     * @param  string  $activationKey
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchNeverActivatedUser($userID, $activationKey)
    {
        return $this->user->where([
            'remember_token' => $activationKey,
            '_id' => $userID,
            'status' => 12,  // never activated
        ])
            ->first();
    }

    /**
     * Activate user by updating its status information.
     *
     * @param  object  $user
     * @return bool
     *---------------------------------------------------------------- */
    public function activateUser($user)
    {
        $user->status = 1;  // activate status

        // Check if information updated
        if ($user->save()) {
            activityLog('Activate User', $user->_id, 'Create');

            return true;
        }

        return false;
    }

    /**
     * Fetch user profile.
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchProfile()
    {
        $userId = Auth::id();

        return $this->user
            ->leftjoin('user_authorities', 'users._id', '=', 'user_authorities.users__id')
            ->leftjoin('user_roles', 'user_authorities.user_roles__id', '=', 'user_roles._id')
            ->select(
                __nestedKeyValues([
                    'users' => [
                        '_id',
                        '_uid',
                        'first_name',
                        'last_name',
                        'email',
                    ],
                    'user_roles' => [
                        'title',
                    ],
                ])
            )
            ->where('users._id', '=', $userId)
            ->first();
    }

    /**
     * Fetch user profile.
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchProfileById()
    {
        return $this->userProfileModel
            ->where('users__id', Auth::id())
            ->first();
    }

    /**
     * Update profile.
     *
     * @param  array  $input
     * @return bool
     *---------------------------------------------------------------- */
    public function updateProfile($input)
    {
        $user = Auth::user();

        // Check if profile updated
        if ($user->modelUpdate([
            'first_name' => $input['first_name'],
            'last_name' => $input['last_name'],
        ])) {
            $userFullName = $user->first_name.' '.$user->last_name;
            activityLog(2, $user->_id, 2, $userFullName);

            return $user;
        }

        return false;
    }

    /**
     * Fetch user by id.
     *
     * @param  number  $userID
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchByID($userID)
    {
        return $this->user->find($userID);
    }

    /**
     * Delete user by updating user status.
     *
     * @param  object  $user
     * @return bool
     *---------------------------------------------------------------- */
    public function delete($user)
    {
        // Check if user status is never activated, then delete user permanently
        if ($user->status === 12 or $user->status === 5) { // Never activated or Deleted
            if ($user->delete()) {
                $userFullName = $user->first_name.' '.$user->last_name;
                activityLog(1, $user->_id, 3, $userFullName);

                return true;
            }
        } elseif ($user->modelUpdate(['status' => 5])) { // if user is active then soft delete it
            $userFullName = $user->first_name.' '.$user->last_name;
            activityLog(1, $user->_id, 4, $userFullName);

            return true;
        }

        return false;
    }

    /**
     * Restore user by updating user deleted status to active status.
     *
     * @param  object  $user
     * @return bool
     *---------------------------------------------------------------- */
    public function restore($user)
    {
        // Check if user restored
        if ($user->modelUpdate(['status' => 1])) {
            $userFullName = $user->first_name.' '.$user->last_name;
            activityLog(1, $user->_id, 5, $userFullName);

            return true;
        }

        return false;
    }

    /**
     * Get admin user.
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function getAdmin()
    {
        return $this->user->where([
            'status' => 1,
            'user_roles__id' => 1,
        ])
            ->first();
    }

    /**
     * Fetch non activated user record by email.
     *
     * @param  string  $email.
     * @return Eloquent Collection Object.
     *---------------------------------------------------------------- */
    public function getNonActicatedUserByEmail($email)
    {
        return $this->user
            ->whereEmailAndStatus($email, 12)
            ->first();
    }

    /**
     * Fetch all users email.
     *
     *
     * @return Eloquent Collection Object.
     *---------------------------------------------------------------- */
    public function fetchEmailOfUsers()
    {
        return $this->user
            ->get(['email', 'status']);
    }

    /**
     * Fetch User First and Last name.
     *
     * @param  number  $userID
     * @return Eloquent Collection Object.
     *---------------------------------------------------------------- */
    public function fetchUserFullName($userID)
    {
        return $this->user
            ->where('_id', $userID)
            ->first(['first_name', 'last_name']);
    }

    /**
     * Store new active user & return response
     *
     * @param  array  $inputData
     * @return bool
     *---------------------------------------------------------------- */
    public function storeActive($inputData)
    {
        $keyValues = [
            'email' => __ifisset($inputData['email']) ? strtolower($inputData['email']) : null,
            'password' => bcrypt($inputData['password']),
            'status' => 1,
            'first_name',
            'last_name',
            'username',
        ];

        $newUser = new $this->user();

        // Check if new User added
        if ($newUser->assignInputsAndSave($inputData, $keyValues)) {
            $userId = $newUser->_id;
            $userFullName = $newUser->first_name.' '.$newUser->last_name;
            activityLog(1, $userId, 1, $userFullName);

            return $newUser;
        }

        return false;   // on failed
    }

    /**
     * Store new active user & return response
     *
     * @param  array  $inputData
     * @return bool
     *---------------------------------------------------------------- */
    public function storeCountry($inputData)
    {
        $keyValues = [
            'countries__id' => $inputData['country'],
            'users__id' => Auth::id(),
        ];

        $userProfileModel = new $this->userProfileModel();

        // Check if new User added
        if ($userProfileModel->assignInputsAndSave($inputData, $keyValues)) {
            $userId = $userProfileModel->_id;

            activityLog('User Country', $userId, 'Create');

            return $userId;
        }

        return false;   // on failed
    }

    /**
     * Store new active user & return response
     *
     * @param  array  $inputData
     * @return bool
     *---------------------------------------------------------------- */
    public function updateCountry($userProfile, $inputData)
    {
        // Check if new User added
        if ($userProfile->modelUpdate(['countries__id' => $inputData['country']])) {
            $userId = Auth::id();
            activityLog('User Country', $userId, 'Update');

            return $userId;
        } else {
            return false;   // on failed
        }
    }

    /**
     * Fetch Profile
     *
     *
     * @return Eloquent Collection Object.
     *---------------------------------------------------------------- */
    public function fetchProfileData()
    {
        return $this->userProfileModel
            ->where('users__id', Auth::id())
            ->first();
    }

    /**
     * Store profile
     *
     * @param  array  $inputData
     * @return bool
     *---------------------------------------------------------------- */
    public function storeProfile($inputData)
    {
        $keyValues = [
            'address_line_1',
            'address_line_2',
            'users__id' => Auth::id(),
            'countries__id' => $inputData['country'],
        ];

        if (__ifIsset($inputData['profile_picture'])) {
            $keyValues['profile_picture'] = $inputData['profile_picture'];
        }

        $userProfileModel = new $this->userProfileModel();

        // Check if new User added
        if ($userProfileModel->assignInputsAndSave($inputData, $keyValues)) {
            activityLog(2, $userProfileModel->_id, 2, $inputData['userFullName']);

            return true;
        }

        return false;   // on failed
    }

    /**
     * Update profile.
     *
     * @param  array  $input
     * @return bool
     *---------------------------------------------------------------- */
    public function updateProfileData($profile, $input)
    {
        $updateData = [
            'address_line_1' => $input['address_line_1'],
            'address_line_2' => $input['address_line_2'],
            'countries__id' => $input['country'],
        ];

        if (__ifIsset($input['profile_picture'])) {
            $updateData['profile_picture'] = $input['profile_picture'];
        }

        // Check if profile updated
        if ($profile->modelUpdate($updateData)) {
            $user = $this->fetchUser($profile->users__id);
            $userFullName = $user->first_name.' '.$user->last_name;

            activityLog(2, $profile->_id, 2, $userFullName);

            return true;
        }

        return false;
    }

    /**
     * Return user if exists; create and return if doesn't
     *
     * @param $socialUser
     * @return User
     *---------------------------------------------------------------- */
    public function socialLogin($socialUser)
    {
        if ($user = $this->storeNewUser($socialUser, true)) {
            activityLog('User Login', $user->_id, 'Create');

            return $user;
        }

        return false;
    }

    /**
     * Return user if exists; create and return if doesn't
     *
     * @param $facebookUser
     * @return User
     *---------------------------------------------------------------- */
    public function checkEmail($email)
    {
        return $this->user->where('email', $email)->first();
    }

    /**
     * Return the country id of user
     *
     * @return Eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchUserCountryId()
    {
        return $this->userProfileModel
            ->where('users__id', Auth::id())
            ->pluck('countries__id');
    }

    /**
     * Return the country id of user
     *
     * @return Eloquent collection object
     *---------------------------------------------------------------- */
    public function checkUserCountryIsPresent()
    {
        return $this->userProfileModel
            ->where('users__id', Auth::id())
            ->where('countries__id', null)
            ->exists();
    }

    /**
     * get Active users count
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function getActiveUserCount()
    {
        return $this->user
            ->join('user_authorities', 'users._id', '=', 'user_authorities.users__id')
            ->where('users.status', 1)
            ->where('user_authorities.user_roles__id', '!=', 1)
            ->count();
    }

    /**
     * Fetch all User
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchAllUser()
    {
        return $this->user
            ->join('user_authorities', 'users._id', '=', 'user_authorities.users__id')
            ->where('user_authorities.user_roles__id', '!=', 1)
            ->select(
                __nestedKeyValues([
                    'users' => [
                        '_id',
                        '_uid',
                        'created_at',
                        'updated_at',
                        'email',
                        'status',
                        'first_name',
                        'last_name',
                    ],
                    'user_authorities' => [
                        'users__id',
                        'user_roles__id',
                    ],
                ])
            )
            ->get();
    }

    /**
     * Fetch user register count which is added in this month
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchMonthUserRegisterCount()
    {
        return $this->user
            ->where('created_at', '>=', Carbon::now()->startOfMonth())
            ->count();
    }

    /**
     * Verify the email is inactive
     *
     * @param  string  $email
     * @return Eloquent collection object
     *-----------------------------------------------------------------------*/
    public function varifyUsernameOrEmail($usernameOrEmail)
    {
        return $this->user->where('email', $usernameOrEmail)->orWhere('username', $usernameOrEmail)->first();
    }

    /**
     * Verify the email is deleted user
     *
     * @param  string  $email
     * @return Eloquent collection object
     *-----------------------------------------------------------------------*/
    public function varifyDeletedUserEmailOrUsername($usernameOrEmail)
    {
        return $this->user
            ->where('status', 5)
            ->where('username', $usernameOrEmail)
            ->orWhere('email', $usernameOrEmail)
            ->exists();
    }

    /**
     * Prepare data for store new social user
     *
     * @param  object  $input
     * @return bool
     *---------------------------------------------------------------- */
    public function storeSocialUser($input)
    {
        $keyValues = [
            'email' => strtolower($input['email']),
            'status' => 1,
            //'user_roles__id'    => 3, // customer
            'first_name',
            'last_name',
        ];

        $newUser = new $this->user();

        // Check if new User added
        if ($newUser->assignInputsAndSave($input, $keyValues)) {
            $userId = $newUser->_id;
            activityLog('Social User', $userId, 'Create');

            $authorityKeyValues = [
                'status' => 1,
                'user_roles__id' => 3, // customer
                'users__id' => $userId,
            ];

            $userAuthority = new UserAuthorityModel();

            if ($userAuthority->assignInputsAndSave($input, $authorityKeyValues)) {
                activityLog('User Authority', $userAuthority->_id, 'Create');
                // add social access  account
                return $this->updateSocialUser($userId, $input);
            }

            // activityLog("Id of $userId new user added related to social.");
        }

        return false;   // on failed
    }

    /**
     * Store User Role
     *
     * @param  string  $accountId
     * @return Eloquent collection object
     *-----------------------------------------------------------------------*/
    public function storeUserRole($inputData)
    {
        $keyValues = [
            'status' => 1,
            'title',
            'role',
        ];

        $userRoleModel = new $this->userRoleModel();

        // Check if new User Role added
        if ($userRoleModel->assignInputsAndSave($inputData, $keyValues)) {
            activityLog('User Role', $userRoleModel->_id, 'Create');

            return $userRoleModel->_id;
        }

        return false;   // on failed
    }

    /**
     * Fetch all role permissions
     *
     * @return Eloquent collection object
     *-----------------------------------------------------------------------*/
    public function fetchAllRoles()
    {
        return $this->userRoleModel
            ->where('status', 1)
            ->get();
    }

    /**
     * Fetch all active users
     *
     * @return Eloquent collection object
     *-----------------------------------------------------------------------*/
    public function fetchActiveUsers()
    {
        return $this->userAuthorityModel->leftjoin('users', 'user_authorities.users__id', '=', 'users._id')
            ->where('users.status', 1) // Active
            ->where('user_roles__id', 3)
            ->select(
                __nestedKeyValues([
                    'users' => [
                        DB::raw('CONCAT(first_name, " ", last_name) AS name'), '_id AS id',
                    ],
                ])
            )
            ->get();
    }

    /**
     * Update user
     *
     * @param  model  $user
     * @param  array  $updateData
     * @return Eloquent collection object
     *-----------------------------------------------------------------------*/
    public function updateUser($user, $updateData)
    {
        if ($user->modelUpdate($updateData)) {
            $userName = $user->first_name.' '.$user->last_name;
            activityLog(1, $user->_id, 2, $userName);

            return true;
        }

        return false;
    }

    /**
     * Update user
     *
     * @param  model  $user
     * @param  array  $updateData
     * @return Eloquent collection object
     *-----------------------------------------------------------------------*/
    public function updateUserPermissions($user, $updateData)
    {
        if ($user->modelUpdate($updateData)) {
            return $user;
        }

        return false;
    }

    /**
     * Get active Team member
     *
     * @return Eloquent collection object
     *-----------------------------------------------------------------------*/
    public function fetchTeamMember($userIds)
    {
        return $this->user->join('user_authorities', 'users._id', '=', 'user_authorities.users__id')
            ->whereNotIn('user_authorities.user_roles__id', [1, 3])
            ->whereNotIn('users._id', $userIds)
            ->where('users.status', 1)
            ->get(['users._id', DB::raw('CONCAT(users.first_name, " ", users.last_name) AS full_name')]);
    }

    /**
     * Store support ticket user
     *
     * @return Eloquent collection object
     *-----------------------------------------------------------------------*/
    public function storeSupportTicketUser($inputData)
    {
        $newUser = new $this->user();

        $newUser->status = 12;    // Never Activated
        $newUser->email = strtolower($inputData['email']);
        $newUser->remember_token = YesSecurity::generateUid();
        $newUser->first_name = $inputData['full_name'];
        $newUser->password = bcrypt($inputData['password']);

        // Check if user stored
        if ($newUser->save()) {
            $profile = new $this->userProfileModel();

            $profile->users__id = $newUser->_id;
            $profile->countries__id = null;
            $profile->save();

            $userAuthority = new $this->userAuthorityModel();

            $userAuthority->status = 1;

            $userAuthority->users__id = $newUser->_id;

            $userAuthority->user_roles__id = 3;

            $userAuthority->save();
            activityLog('Support Ticket User', $newUser->_id, 'Create');

            return $newUser;
        }

        return false;
    }

    /**
     * Store support ticket user
     *
     * @return Eloquent collection object
     *-----------------------------------------------------------------------*/
    public function storeServiceRequestUser($inputData)
    {
        $newUser = new $this->user();

        $newUser->status = 12;    // Never Activated
        $newUser->email = strtolower($inputData['email']);
        $newUser->remember_token = YesSecurity::generateUid();
        $newUser->first_name = $inputData['full_name'];
        $newUser->password = bcrypt($inputData['password']);

        // Check if user stored
        if ($newUser->save()) {
            $profile = new $this->userProfileModel();

            $profile->users__id = $newUser->_id;
            $profile->countries__id = null;
            $profile->save();

            $userAuthority = new $this->userAuthorityModel();

            $userAuthority->status = 1;

            $userAuthority->users__id = $newUser->_id;

            $userAuthority->user_roles__id = 3;

            $userAuthority->save();
            activityLog('Service Request User', $newUser->_id, 'Create');

            return $newUser;
        }

        return false;
    }

    /**
     * Store New user Authority
     *
     * @param  number  $userId
     * @return Eloquent collection object
     *-----------------------------------------------------------------------*/
    public function storeUserAuthority($userId, $roleId = null)
    {
        $userAuthority = new UserAuthorityModel();

        $userAuthority->status = 1;
        $userAuthority->users__id = $userId;
        $userAuthority->user_roles__id = (! __isEmpty($roleId))
            ? $roleId
            : 3; // Customer

        // Check if user authority stored successfully.
        if ($userAuthority->save()) {
            //activityLog('User Authority', $userAuthority->_id, 'Create');
            return true;
        }

        return false;
    }

    /**
     * Fetch Authority
     *
     * @param  number  $userAuthorityId
     * @return Eloquent collection object
     *-----------------------------------------------------------------------*/
    public function fetchAuthority($userAuthorityId)
    {
        return UserAuthorityModel::where('_id', $userAuthorityId)->first();
    }

    /**
     * Store New user Authority
     *
     * @param  number  $userId
     * @return Eloquent collection object
     *-----------------------------------------------------------------------*/
    public function fetchUserAuthorities($userId)
    {
        return UserAuthorityModel::where('users__id', $userId)->first();
    }

    /**
     * Fetch all users
     *
     * @return Eloquent collection object
     *-----------------------------------------------------------------------*/
    public function fetchAllUsers()
    {
        return $this->userAuthorityModel
            ->leftjoin('users', 'user_authorities.users__id', '=', 'users._id')
            ->where('users.status', 1) // Active
            ->whereIn('user_roles__id', [1, 2])
            ->select(
                'users._id',
                'users._uid',
                'first_name',
                'last_name',
                'users.status'
            )
            ->get();
    }

    /**
     * Fetch all users
     *
     * @return Eloquent collection object
     *-----------------------------------------------------------------------*/
    public function fetchUsersByIds($usersIds)
    {
        return $this->user->leftJoin('user_authorities', 'users._id', '=', 'user_authorities.users__id')
            ->leftJoin('project_users', 'users._id', '=', 'project_users.users__id')
            ->where('users.status', 1) // Active
            ->whereNotIn('user_authorities.user_roles__id', [1]) // Admin
            ->select(
                __nestedKeyValues([
                    'users' => [
                        '_id',
                        '_uid',
                        'first_name',
                        'last_name',
                    ],
                ])
            )
            ->get();
    }

    /**
     * Fetch user with profile
     *
     * @param  int  $userId
     * @return Eloquent collection object
     *-----------------------------------------------------------------------*/
    public function fetchUserWithProfile($userId)
    {
        return $this->user
            ->where('_id', $userId)
            ->with('profile')
            ->first();
    }

    /**
     * Get active Team member
     *
     * @return Eloquent collection object
     *-----------------------------------------------------------------------*/
    public function fetchTeamMembers()
    {
        return $this->user->join('user_authorities', 'users._id', '=', 'user_authorities.users__id')
            ->whereIn('user_authorities.user_roles__id', [1, 2])
            ->where('users.status', 1)
            ->get(['users._id', DB::raw('CONCAT(users.first_name, " ", users.last_name) AS full_name')]);
    }

    /**
     * Fetch Admin with details
     *
     * @return Eloquent collection object
     *-----------------------------------------------------------------------*/
    public function fetchAdminWithDetails()
    {
        return $this->user
            ->join('user_authorities', 'users._id', '=', 'user_authorities.users__id')
            ->where('user_authorities.user_roles__id', 1)
            ->select(
                __nestedKeyValues([
                    'users' => [
                        '_id as user_id',
                        DB::raw('CONCAT(users.first_name, " ", users.last_name) AS full_name'),
                        'email',
                    ],
                    'user_authorities' => [
                        '_id',
                        'users__id',
                        'user_roles__id',
                    ],
                ])
            )
            ->first();
    }

    /**
     * Fetch User With Authority
     *
     * @return Eloquent collection object
     *-----------------------------------------------------------------------*/
    public function fetchUserWithAuthority($userId)
    {
        return $this->user
            ->join('user_authorities', 'users._id', '=', 'user_authorities.users__id')
            ->where('users._id', $userId)
            ->select(
                __nestedKeyValues([
                    'users' => [
                        '_id',
                        '_uid',
                        'first_name',
                        'last_name',
                        'username',
                        'email',
                        'status',
                    ],
                    'user_authorities' => [
                        'users__id',
                        'user_roles__id',
                    ],
                ])
            )
            ->first();
    }

    /**
     * Update User Authority
     *
     * @return Eloquent collection object
     *-----------------------------------------------------------------------*/
    public function updateUserAuthority($userAuthority, $updateData)
    {
        if ($userAuthority->modelUpdate($updateData)) {
            return true;
        }

        return false;
    }

    /**
     * Fetch User by id
     *
     * @param  int  $userIdorUid
     * @return void
     *-----------------------------------------------------------------------*/
    public function fetchUserData($userIdorUid)
    {
        return $this->user
            ->with([
                'profile' => function ($profile) {
                    $profile->select('_id', 'users__id', 'address_line_1', 'address_line_2', 'countries__id');
                },
            ])
            ->join('user_authorities', 'users._id', '=', 'user_authorities.users__id')
            ->join('user_roles', 'user_authorities.user_roles__id', '=', 'user_roles._id')
            ->select(
                'users._id',
                'users._uid',
                'user_authorities.user_roles__id',
                'users.status',
                DB::raw('CONCAT(users.first_name, " ", users.last_name) AS name'),
                'users.email',
                'username',
                'users.created_at',
                'users.updated_at',
                'user_roles.title AS userRoleTitle'
            )
            ->where('users._uid', $userIdorUid)
            ->first();
    }

    /**
     * Fetch User With Authority
     *
     * @return Eloquent collection object
     *-----------------------------------------------------------------------*/
    public function fetchUsersWithAuthority()
    {
        return $this->user
            ->join('user_authorities', 'users._id', '=', 'user_authorities.users__id')
            ->select(
                __nestedKeyValues([
                    'users' => [
                        '_id',
                        '_uid',
                        'first_name',
                        'last_name',
                        'username',
                        'status',
                    ],
                    'user_authorities' => [
                        '_id AS authority_id',
                        'users__id',
                        'user_roles__id',
                    ],
                ])
            )
            ->where('users.status', 1)
            ->get();
    }
}
