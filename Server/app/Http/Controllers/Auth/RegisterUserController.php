<?php
declare (strict_types = 1);

namespace App\Http\Controllers\Auth;

use App\Enums\EmailTrackingTypes;
use App\Enums\SentEmailStatus;
use App\Enums\User\UserRoleEnum;
use App\Helpers\UserHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\RegisterAdminRequest;
use App\Http\Requests\User\RegisterInternalUserRequest;
use App\Http\Requests\User\RegisterUserRequest;
use App\Http\Requests\WholesaleRequest;
use App\Http\Resources\User\UserResource;
use App\Mail\SendConfirmationRegistration;
use App\Mail\SendWelcomeEmail;
use App\Mail\SendConfirmationWholesale;
use App\Mail\SendInfoWholesaleFail;
use App\Mail\SendInfoWholesaleO;
use App\Mail\SendInfoWholesaleOk;
use App\Models\EmailTracking\EmailTracking;
use App\Models\User;
use App\Models\User\Role;
use App\Models\User\UserProfile;
use App\Models\User\UserRole;
use App\Models\WholesaleUsers;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use App\Http\Requests\WholesaleUserRequest;

/**
 * Class RegisterUserController
 *
 * @extends  Controller controller
 * @category Controllers
 * @package  App\Http\Controllers\Auth
 * @author   Carlos Gonzalez <carlos.gonzalez@tsolife.com>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     http://tsolife.com
 */
class RegisterUserController extends Controller
{

    /**
     * Register New User
     *
     * @param  RegisterUserRequest $request
     * @return JsonResponse
     */
    public function register(RegisterUserRequest $request): JsonResponse
    {
        /**
         * @var User $user
         */
        $user = User::query()
            ->create(
                [
                    'email' => $request->input('email'),
                    'password' => Hash::make($request->input('password')),
                ]
            );
        UserProfile::query()->create(
            [
                'userId' => $user->id,
                'firstName' => $request->input('firstName'),
                'lastName' => $request->input('lastName'),
                'urlSlug' => UserHelper::createUniqueUserSlug($request->input('firstName'), $request->input('lastName')),
            ]
        );
        $role = Role::query()->where('name', UserRoleEnum::SINGLE_USER->name)->first();
        UserRole::query()->create(
            [
                'userId' => $user->id,
                'roleId' => $role->id,
            ]
        );

        ## Send email to user
        $sendEmail = Mail::to($request->input('email'))->send(new SendConfirmationRegistration($user));

        $html = view('mail.confirm-registration', ['user' => $user])->render();

        EmailTracking::create(
            [
                'emailId' => Str::replace(["<", ">"], "", $sendEmail->getMessageId()),
                'sender' => $sendEmail->getEnvelope()->getSender()->getAddress(),
                'receiver' => $request->input('email'),
                'body' => $html,
                'userId' => $user->id,
                'active' => 1,
                'status' => SentEmailStatus::QUEUE->value,
                'type' => EmailTrackingTypes::CONFIRM_REGISTRATION->value,
            ]
        );

        ## Response
        $response = [
            'user' => UserResource::make($user),
            'token' => $user->createToken('auth_token'),
        ];
        return response()
            ->json($response)
            ->setStatusCode(Response::HTTP_CREATED);

    } //end register()

    /**
     * Register New Internal User
     *
     * @param  RegisterInternalUserRequest $request
     * @return JsonResponse
     */
    public function createInternal(RegisterInternalUserRequest $request): JsonResponse
    {
        return $this->createUserProfileRole($request);

    } //end createInternal()

    /**
     * Register New Internal User
     *
     * @param  RegisterAdminRequest $request
     * @return JsonResponse
     */
    public function createAdmin(RegisterAdminRequest $request): JsonResponse
    {
        return $this->createUserProfileRole($request);


    } //end createAdmin()

    /** 
     * Create User
     *
     * @param  Request $request
     * @return JsonResponse
     */
    protected function createUserProfileRole(Request $request): JsonResponse
    {
        $password = $request->input('password', Str::random(32));
        $email = $request->input('email', UserHelper::createUniqueEmail($request->validated()));
        /**
         * @var User $user
         */
        $user = User::query()->create(
            [
                'email' => $email,
                'password' => Hash::make($password),
            ]
        );

        UserProfile::query()->create(
            [
                'userId' => $user->id,
                'firstName' => $request->input('firstName'),
                'lastName' => $request->input('lastName'),
                'phoneNumber' => $request->input('phoneNumber', null),
                'urlSlug' => UserHelper::createUniqueUserSlug($request->input('firstName'), $request->input('lastName')),
            ]
        );
        /**
         * @var Role $role
         */
        $role = Role::query()->where('name', $request->input('role'))->first();

        UserRole::query()->create(
            [
                'userId' => $user->id,
                'roleId' => $role->id,
            ]
        );
        Mail::to($email)->send(new SendWelcomeEmail($user, $role));
       

        return response()
            ->json(UserResource::make($user))
            ->setStatusCode(Response::HTTP_OK);

    } //end createUserProfileRole()

    /**
     * Send Email Confirmation
     *
     * @param  User $user
     * @return JsonResponse
     */
    public function sendEmailConfirmation(User $user): JsonResponse
    {
        ## Send email to user
        if (!empty($user->emailConfirmedAt)) {
            return response()
                ->json(['message' => 'Email Already Confirmed'])
                ->setStatusCode(ResponseAlias::HTTP_BAD_REQUEST);
        }

        $sendEmail = Mail::to($user->email)->send(new SendConfirmationRegistration($user));
        $html = view('mail.confirm-registration', ['user' => $user])->render();

        EmailTracking::create(
            [
                'emailId' => Str::replace(["<", ">"], "", $sendEmail->getMessageId()),
                'sender' => $sendEmail->getEnvelope()->getSender()->getAddress(),
                'receiver' => $user->email,
                'body' => $html,
                'userId' => $user->id,
                'active' => 1,
                'status' => SentEmailStatus::QUEUE->value,
                'type' => EmailTrackingTypes::CONFIRM_REGISTRATION->value,
            ]
        );
        return response()
            ->json(['message' => 'Email Sent'])
            ->setStatusCode(ResponseAlias::HTTP_OK);

    } //end sendEmailConfirmation()

    /**
     * Email Confirmation
     *
     * @param  User $user
     * @return JsonResponse
     */
    public function emailConfirmation(User $user)
    {



    
        $rol = $user->rolesArray();
        if (!empty($user->emailConfirmedAt)) {
            if(in_array('WHOLESALE_USER', $rol))
            {
                return Redirect()->to(env('EMAIL_CONFIRMED_SUCCESS_ROUTE') ?? 'https://lazomascotas.com/#/wholesale-pending');
            }
            return Redirect()->to(env('EMAIL_CONFIRMED_SUCCESS_ROUTE') ?? 'https://lazomascotas.com/#/account/auth/confirm-mail');
        }

        ## Send email to user
        $user->emailConfirmedAt = Carbon::now();
        $user->save();

        
        $confirmSuccess = env('EMAIL_CONFIRMED_SUCCESS_ROUTE') ?? 'https://lazomascotas.com/#/account/auth/confirm-mail';
        if(in_array('WHOLESALE_USER', $rol)){
            $confirmSuccess = env('EMAIL_CONFIRMED_SUCCESS_ROUTE') ?? 'https://lazomascotas.com/#/wholesale-pending';
        }
        return redirect()->to($confirmSuccess);

        // return response()
        //     ->json(['message' => 'Email Confirmed'])
        //     ->setStatusCode(ResponseAlias::HTTP_OK);

    } //end emailConfirmation()

    public function registerWholeSaleUser(WholesaleUserRequest $request)
    {
        /**
         * @var User $user
         */
        $user = User::query()
            ->create(
                [
                    'email' => $request->input('email'),
                    'password' => Hash::make($request->input('password')),
                ]
            );
        UserProfile::query()->create(
            [
                'userId' => $user->id,
                'firstName' => $request->input('firstName'),
                'lastName' => $request->input('lastName'),
                'urlSlug' => UserHelper::createUniqueUserSlug($request->input('firstName'), $request->input('lastName')),
            ]
        );
        $role = Role::query()->where('name', UserRoleEnum::WHOLESALE_USER->name)->first();
        UserRole::query()->create(
            [
                'userId' => $user->id,
                'roleId' => $role->id,
            ]
        );
        WholesaleUsers::query()->create(
            [
                'userId' => $user->id,
                'companyName' => $request->input('companyName'),
                'phone' => $request->input('phone'),
                'address' => $request->input('address'),
                'companySize' => $request->input('companySize'),
                'rutMediaId' => $request->input('mediaId'),
            ]
        );

        ## Send email to user
        $sendEmail = Mail::to($request->input('email'))->send(new SendConfirmationRegistration($user));

        $html = view('mail.confirm-registration', ['user' => $user])->render();

        EmailTracking::create(
            [
                'emailId' => Str::replace(["<", ">"], "", $sendEmail->getMessageId()),
                'sender' => $sendEmail->getEnvelope()->getSender()->getAddress(),
                'receiver' => $request->input('email'),
                'body' => $html,
                'userId' => $user->id,
                'active' => 1,
                'status' => SentEmailStatus::QUEUE->value,
                'type' => EmailTrackingTypes::CONFIRM_REGISTRATION->value,
            ]
        );

        $response = [
            'user' => UserResource::make($user),
            'token' => $user->createToken('auth_token'),
        ];
        return response()
            ->json($response)
            ->setStatusCode(Response::HTTP_CREATED);

    }

    public function wholeSaleDenail(User $user){
        $wholesaleUser = WholesaleUsers::where('userId', $user->id)->first();
        if($wholesaleUser == null){
            return Redirect()->to(env('EMAIL_CONFIRMED_SUCCESS_ROUTE') ?? 'https://lazomascotas.com/#/');
        }
        $user= $wholesaleUser->user;
        $rolUser= UserRole::query()->where('userId', $user->id)->first();
        $wholesaleUser->delete();
        $role = Role::query()->where('name', UserRoleEnum::SINGLE_USER->name)->first();
        $rolUser->roleId = $role->id;
        $rolUser->save();
        Mail::to($user->email)->send(new SendInfoWholesaleFail($user));
        return Redirect()->to(env('EMAIL_CONFIRMED_SUCCESS_ROUTE') ?? 'https://lazomascotas.com/#/');
    }

    public function wholeSaleApprove(User $user){
        
        $wholesaleUser = WholesaleUsers::where('userId', $user->id)->first();
        if($wholesaleUser == null){
            return Redirect()->to(env('EMAIL_CONFIRMED_SUCCESS_ROUTE') ?? 'https://lazomascotas.com/#/');
        }
        $wholesaleUser->isApproved = true;
        $wholesaleUser->save();
        Mail::to($user->email)->send(new SendInfoWholesaleOk($user));
        return Redirect()->to(env('EMAIL_CONFIRMED_SUCCESS_ROUTE') ?? 'https://lazomascotas.com/#/');
    }
 } //end class
