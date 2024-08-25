<?php

namespace App\Http\Controllers\Wholesale;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WholesaleUsers;
use App\Mail\SendInfoWholesaleFail;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendInfoWholesaleOk;
use App\Models\User\Role;
use App\Enums\User\UserRoleEnum;
use App\Models\User\UserRole;
class WholesaleController extends Controller
{
    public function accept(Request $request)
    {
        $wholesaleUser = WholesaleUsers::find($request->userId);
        $wholesaleUser->isApproved = true;
        $wholesaleUser->save();
        $user= $wholesaleUser->user;
        Mail::to($user->email)->send(new SendInfoWholesaleOk($user));
        return response()->json(['message' => 'User accepted']);
    }
    public function deny(Request $request)
    {
        $wholesaleUser = WholesaleUsers::find($request->userId);
        $user= $wholesaleUser->user;
        $rolUser= UserRole::query()->where('userId', $user->id)->first();
        $wholesaleUser->delete();
        $role = Role::query()->where('name', UserRoleEnum::SINGLE_USER->name)->first();
        $rolUser->roleId = $role->id;
        $rolUser->save();
        Mail::to($user->email)->send(new SendInfoWholesaleFail($user));

        return response()->json(['message' => 'User rejected']);
    }
}
