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
        if (!auth()->user()->hasRole('admin')) {
            return parent::LogError('Unauthorized' . 'User: ' . auth()->user(), 403);
        }     

        try {
            return User::all();
        } catch (\Throwable $th) {
            return parent::LogError('Error occurred while retrieving users' . $th, 500);
        }
    }

    /**
     * Get current user.
     */
    public function show(Request $request)
    {
        try {
            return $request->user();
        } catch (\Throwable $th) {
            return parent::LogError($th, 500);
        }
        
    }

    /**
     * Get user by id.
     */
    public function getUserById($id)
    {
        try {
            $user = User::findOrFail($id);
            return response()->json($user);        
        } catch (\Throwable $e) {
            return parent::LogError('User not found with the ID: ' . $id, 404);
        }

    }

    /**
     * Get user by email.
     */
    public function getUserByEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);
    
        try {
            $user = User::where('email', $request->email)->firstOrFail();
            return response()->json($user);
    
        } catch (\Throwable $e) {
            return parent::LogError('User not found with email: ' . $request->email, 404);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request){
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'surname' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:8|confirmed',
                'password_confirmation' => 'required|string|min:8'
            ]);

            User::create([
                'name' => $validated['name'],
                'surname' => $validated['surname'],
                'email' => $validated['email'],
                'password' => bcrypt($validated['password']),
            ]);

            return response()->json(['message' => 'Usuario creado con éxito'],201);

        } catch (\Throwable $th) {
            return parent::LogError($th, 500);

        }
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        if (!(auth()->user()->can('edit users') || auth()->id() === $request->id)) {
            return parent::LogError('Unauthorized' . 'User: ' . auth()->user(), 403);
        }

        try {
        $validated = $request->validate([
            'id' => 'required|integer|exists:users,id',
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $request->id, 
            'password' => 'nullable|string|min:8|confirmed',
        ]);
    
        $user = User::findOrFail($validated['id']);
    
        $user->fill([
            'name' => $validated['name'],
            'surname' => $validated['surname'],
            'email' => $validated['email'],
        ]);
    
        if (!empty($validated['password'])) {
            $user->password = bcrypt($validated['password']);
        }
    
        $user->save();
    
        return response()->json([
            'message' => 'Usuario actualizado correctamente.',
            'user' => $user,
        ]);
    } catch (\Throwable $th) {
        return parent::LogError($th, 500);
    }
}
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request){

        if (!auth()->user()->hasRole('admin')) {
            return parent::LogError('Unauthorized' . 'User: ' . auth()->user(), 403);
        }     

        try {
            $user = User::findOrFail($request->id);
            $user->delete();
            return response()->json([
                'message' => 'Usuario eliminado correctamente.',
            ], 200);
        } catch (\Throwable $th) {
            return parent::LogError($th, 500);
        }

    }
}
