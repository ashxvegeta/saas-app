<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class TenantMemberController extends Controller
{
    //
    public function addMember(Request $request)
    {
        // Implementation for adding a member to the tenant
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'role' => 'required|in:owner,manager,member'
        ]);
        $tenant = app('currentTenant');
        $user = User::where('email', $request->email)->first();
        // prevent adding the same user multiple times
        $alreadyMember = $tenant->users()->where('user_id', $user->id)->exists();
        if($alreadyMember) {
            return response()->json(['message' => 'User is already a member of this tenant'], 400);
        }
        $tenant->users()->attach($user->id, ['role' => $request->role]);
        return response()->json(['message' => 'Member added successfully']);
    }
}
