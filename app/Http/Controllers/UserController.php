<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Get ALL users.
     */
    public function index()
    {
        if (auth()->user()->rol !== 'admin') {
            return response()->json(['error' => 'Unauthorized','code' => 403], 403);
        }

        return User::all();
    }

    /**
     * Get current user.
     */
    public function show(Request $request)
    {
        return $request->user();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {}



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {}
}
