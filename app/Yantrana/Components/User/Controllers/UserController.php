<?php
/*
* UserController.php - Controller file
*
* This file is part of the User component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\User\Controllers;

use App\Yantrana\Base\BaseController;
use App\Yantrana\Components\User\Requests\AddCountryRequest;
use App\Yantrana\Components\User\Requests\AddUserRequest;
use App\Yantrana\Components\User\Requests\EditUserRequest;
use App\Yantrana\Components\User\Requests\UserChangeEmailRequest;
use App\Yantrana\Components\User\Requests\UserChangePasswordRequest;
use App\Yantrana\Components\User\Requests\UserContactRequest;
use App\Yantrana\Components\User\Requests\UserDynamicAccessRequest;
use App\Yantrana\Components\User\Requests\UserForgotPasswordRequest;
use App\Yantrana\Components\User\Requests\UserLoginRequest;
use App\Yantrana\Components\User\Requests\UserProfileUpdateRequest;
use App\Yantrana\Components\User\Requests\UserRegisterRequest;
use App\Yantrana\Components\User\Requests\UserResendActivationEmailRequest;
use App\Yantrana\Components\User\Requests\UserResetPasswordRequest;
use App\Yantrana\Components\User\Requests\UserUpdatePasswordRequest;
use App\Yantrana\Components\User\UserEngine;
use App\Yantrana\Support\CommonPostRequest as Request;
use JavaScript;

class UserController extends BaseController
{
    /**
     * @var UserEngine - User Engine
     */
    protected $userEngine;

    /**
     * Constructor.
     *
     * @param  UserEngine  $userEngine - User Engine
     *-----------------------------------------------------------------------*/
    public function __construct(UserEngine $userEngine)
    {
        $this->userEngine = $userEngine;
    }

    /**
     * Handle datatable source data request.
     *
     * @param  number  $status
     * @return json object
     *---------------------------------------------------------------- */
    public function index($status)
    {
        return $this->userEngine->prepareUsersList($status);
    }

    /**
     * Get login attempts for this client ip.
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function loginAttempts()
    {
        $processReaction = $this->userEngine->prepareLoginAttempts();

        return __processResponse(
            $processReaction,
            [],
            $processReaction['data']
        );
    }

    /**
     * Show login view.
     *---------------------------------------------------------------- */
    public function login()
    {
        return $this->loadPublicView('user.login');
    }

    /**
     * Authenticate user based on post form data.
     *
     * @param object UserLoginRequest $request
     * @return json object
     *---------------------------------------------------------------- */
    public function loginProcess(UserLoginRequest $request)
    {
        $processReaction = $this->userEngine->processLogin($request->all());

        return __processResponse($processReaction, [
            1 => __tr('Welcome, you are logged in successfully.'),
            2 => __tr('Authentication failed. Please check your 
                email/password & try again.'),
        ], [], true);
    }

    /**
     * Perform user logout action.
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function logout()
    {
        $processReaction = $this->userEngine->processLogout();

        return __processResponse($processReaction, [], [], true);
        // return redirect()->route('manage.app');
    }

    /**
     * Handle user forgot password request.
     *
     * @param object UserForgotPasswordRequest $request
     * @return json object
     *---------------------------------------------------------------- */
    public function forgotPasswordProcess(UserForgotPasswordRequest $request)
    {
        $processReaction = $this->userEngine
            ->sendPasswordReminder(
                $request->input('usernameOrEmail')
            );

        return __processResponse($processReaction, [
            1 => __tr('We have e-mailed your password reset link.'),
            2 => __tr('Invalid Request.'),
        ]);
    }

    /**
     * Handle forgot password success view request.
     *---------------------------------------------------------------- */
    public function forgotPasswordSuccess()
    {
        return $this->loadPublicView('user.forgot-password-success');
    }

    /**
     * Render reset password view.
     *
     * @param  string  $reminderToken
     *---------------------------------------------------------------- */
    public function restPassword($reminderToken)
    {
        $processReaction = $this->userEngine
            ->varifyPasswordReminderToken($reminderToken);

        if ($processReaction['reaction_code'] === 1) {
            Javascript::put(['passwordReminderToken' => $reminderToken]);

            return $this->loadPublicView('user.reset-password');
        }

        // if activation process failed then
        return redirect(configItem('login_url'));
    }

    /**
     * Handle reset password request.
     *
     * @param object UserResetPasswordRequest $request
     * @param  string  $reminderToken
     * @return json object
     *---------------------------------------------------------------- */
    public function restPasswordProcess(
        UserResetPasswordRequest $request,
        $reminderToken
    ) {
        $processReaction = $this->userEngine
            ->processResetPassword(
                $request->all(),
                $reminderToken
            );

        return __processResponse($processReaction, [
            1 => __tr('Password Reset Successfully.'),
            2 => __tr('Password Not Reset.'),
            18 => __tr('Invalid Request.'),
        ]);
    }

    /**
     * Handle change password request.
     *
     * @param object UserUpdatePasswordRequest $request
     * @return json object
     *---------------------------------------------------------------- */
    public function changePasswordProcess(UserUpdatePasswordRequest $request)
    {
        $processReaction = $this->userEngine
            ->processUpdatePassword(
                $request->only(
                    'new_password',
                    'current_password'
                )
            );

        return __processResponse($processReaction, [
            1 => __tr('Password updated successfully.'),
            3 => __tr('Current password is incorrect.'),
            14 => __tr('Password not updated.'),
        ], null, true);
    }

    /**
     * Get change email support data.
     *---------------------------------------------------------------- */
    public function getChangeEmailSupportData()
    {
        $processReaction = $this->userEngine
            ->getChangeRequestedEmail();

        return __processResponse($processReaction, null, null, true);
    }

    /**
     * Handle change email request.
     *
     * @param object UserChangeEmailRequest $request
     * @return json object
     *---------------------------------------------------------------- */
    public function changeEmailProcess(UserChangeEmailRequest $request)
    {
        $processReaction = $this->userEngine
            ->sendNewEmailActivationReminder(
                $request->only(
                    'new_email',
                    'current_password'
                )
            );

        return __processResponse($processReaction, [], [], true);
    }

    /**
     * Handle new email activation request.
     *
     * @param  number  $userID
     * @param  string  $activationKey
     * @return json object
     *---------------------------------------------------------------- */
    public function newEmailActivation($userID, $activationKey)
    {
        $processReaction = $this->userEngine
            ->newEmailActivation(
                $userID,
                $activationKey
            );

        // Check if activation process succeed
        if ($processReaction['reaction_code'] === 1) {
            return redirect()->route('user.profile')->with([
                'success' => true,
                'message' => __tr('Your new email activated successfully.'),
            ]);
        }

        // if activation process failed then
        return redirect()->route('user.profile')
            ->with([
                'error' => true,
                'message' => __tr('New email activation link invalid.'),
            ]);
    }

    /**
     * Handle user register process request.
     *
     * @param object UserRegisterRequest $request
     * @return json object
     *---------------------------------------------------------------- */
    public function registerProcess(UserRegisterRequest $request)
    {
        $processReaction = $this->userEngine->processRegister($request->all());

        return __processResponse($processReaction, [], [], true);
    }

    /**
     * Handle registration success view request.
     *---------------------------------------------------------------- */
    public function registerSuccess()
    {
        return $this->loadPublicView('user.register-success');
    }

    /**
     * Handle user account activation request.
     *
     * @param  number  $userID
     * @param  string  $activationKey
     * @return json object
     *---------------------------------------------------------------- */
    public function accountActivation($userID, $activationKey)
    {
        $processReaction = $this->userEngine
            ->processAccountActivation(
                $userID,
                $activationKey
            );

        // Check if account activation process succeed
        if ($processReaction['reaction_code'] === 1) {
            return redirect()->route('user.login')
                ->with([
                    'success' => true,
                    'message' => __tr('Your account has been activated successfully. Login with your email ID and password.'),
                ]);
        }

        // if activation process failed then
        return redirect()->route('user.login')
            ->with([
                'error' => true,
                'message' => __tr('Account Activation link invalid.'),
            ]);
    }

    /**
     * Handle profile details request.
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function profileDetails()
    {
        $processReaction = $this->userEngine->prepareProfileDetails();

        return __processResponse($processReaction, [], null, true);
    }

    /**
     * Handle update profile request.
     *
     * @param object UserProfileUpdateRequest $request
     * @return json object
     *---------------------------------------------------------------- */
    public function updateProfileProcess(UserProfileUpdateRequest $request)
    {
        $processReaction = $this->userEngine
            ->processUpdateProfile(
                $request->all()
            );

        return __processResponse($processReaction, [
            1 => __tr('Profile updated successfully.'),
            14 => __tr('Nothing updated.'),
        ], $processReaction['data'], true);
    }

    /**
     * Handle add country process
     *
     * @param object AddCountryRequest $request
     * @return json object
     *---------------------------------------------------------------- */
    public function addCountry(AddCountryRequest $request)
    {
        $processReaction = $this->userEngine
            ->processAddCountry(
                $request->all()
            );

        return __processResponse($processReaction, [], [], true);
    }

    /**
     * Handle user delete request.
     *
     * @param  number  $userID
     * @return json object
     *---------------------------------------------------------------- */
    public function delete($userID, Request $request)
    {
        $processReaction = $this->userEngine->processUserDelete($userID);

        return __processResponse($processReaction, [
            1 => $processReaction['data']['message'],
            2 => __tr('User not deleted.'),
        ]);
    }

    /**
     * Handle user restore request.
     *
     * @param  number  $userID
     * @return json object
     *---------------------------------------------------------------- */
    public function restore($userID, Request $request)
    {
        $processReaction = $this->userEngine->processUserRestore($userID);

        return __processResponse($processReaction, [
            1 => $processReaction['data']['message'],
            2 => __tr('User not restore.'),
        ]);
    }

    /**
     * Handle process contact request.
     *
     * @param object UserContactRequest $request
     * @return json object
     *---------------------------------------------------------------- */
    public function contactProcess(UserContactRequest $request)
    {
        $processReaction = $this->userEngine->processContact($request->all());

        return __processResponse($processReaction, [
            2 => __tr('Failed to send mail.'),
        ]);
    }

    /**
     * Process resend activation email request.
     *
     * @param array UserResendActivationEmailRequest $request
     *------------------------------------------------------------------------ */
    public function resendActivationEmailProccess(UserResendActivationEmailRequest $request)
    {
        $processReaction = $this->userEngine
            ->resendActivationEmail($request->all());

        return __processResponse($processReaction, [
            1 => __tr('Activation mail has been sent successfully, Please check your email.'),
            2 => __tr('Request failed.'),
            3 => __tr('Your account already activated.'),
        ]);
    }

    /**
     * Handle resend activation email success view request.
     *---------------------------------------------------------------- */
    public function resendActivationEmailSuccess()
    {
        return $this->loadPublicView('user.resend-activation-email-success');
    }

    /**
     * change user password by admin.
     *
     * @param  number  $userID
     * @param array UserChangePasswordRequest $request
     *---------------------------------------------------------------- */
    public function changePasswordByAdmin($userID, UserChangePasswordRequest $request)
    {
        $processReaction = $this->userEngine
            ->processChangePassword($userID, $request->all());

        return __processResponse($processReaction, [
            1 => __tr('Password updated successfully.'),
            14 => __tr('Password not updated.'),
            18 => __tr('User not exist.'),
        ]);
    }

    /**
     * Handle process contact request.
     *
     * @param object UserContactRequest $request
     * @return json object
     *---------------------------------------------------------------- */
    public function getInfo($userId)
    {
        $processReaction = $this->userEngine->prepareInfo($userId);

        return __processResponse($processReaction, [], true);
    }

    /**
     * Get Add Support Data.
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function getAddSupportData()
    {
        $processReaction = $this->userEngine->prepareAddSupportData();

        return __processResponse($processReaction, [], true);
    }

    /**
     * Handle add new user request.
     *
     * @param object AddUserRequest $request
     * @return json object
     *---------------------------------------------------------------- */
    public function add(AddUserRequest $request)
    {
        $processReaction = $this->userEngine->processAdd($request->all());

        return __processResponse($processReaction, [], [], true);
    }

    /**
     * Get User Permissions.
     *
     * @param  int  $userId
     * @return json object
     *---------------------------------------------------------------- */
    public function getUserPermissions($userId)
    {
        $processReaction = $this->userEngine->prepareUserPermissions($userId);

        return __secureProcessResponse($processReaction, [], [], true);
    }

    /**
     * Store User Dynamic Permissions.
     *
     * @param object UserDynamicAccessRequest $request
     * @param  int  $userId
     * @return json object
     *---------------------------------------------------------------- */
    public function processUserPermissions(UserDynamicAccessRequest $request, $userId)
    {
        $processReaction = $this->userEngine->processAddUserPermission($request->all(), $userId);

        return __secureProcessResponse($processReaction, [], [], true);
    }

    /**
     * Handle profile details request.
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function profileEditSupportData()
    {
        $processReaction = $this->userEngine->prepareProfileEditSupportData();

        return __processResponse($processReaction, [], null, true);
    }

    /**
     * Handle getCountries
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function getCountries()
    {
        $processReaction = $this->userEngine->getCountries();

        return __processResponse($processReaction, [], null, true);
    }

    /**
     * Handle get Users list
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function prepareReadUsers()
    {
        $processReaction = $this->userEngine->processReadUsers();

        return __processResponse($processReaction, [], null, true);
    }

    /**
     * Get User Edit Support Data
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function getUserEditSupportData($userId)
    {
        $processReaction = $this->userEngine->prepareUserEditSupportData($userId);

        return __processResponse($processReaction, [], [], true);
    }

    /**
     * Get User Edit Support Data
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function processUserUpdate($userId, EditUserRequest $request)
    {
        $processReaction = $this->userEngine
            ->processUpdateUser($userId, $request->all());

        return __processResponse($processReaction, [], [], true);
    }

    /**
     * User get details data
     *
     * @param  mix  $userId
     * @return  json object
     *---------------------------------------------------------------- */
    public function userDetailData($userId)
    {
        $processReaction = $this->userEngine
            ->fetchUserDetails($userId);

        return __processResponse($processReaction, [], [], true);
    }
}
