<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


/* * DashboardController
 * 
 * This controller handles requests to the /dashboard endpoint.
 * It returns user-specific data and application statistics.
 * 
 * Middleware: auth:sanctum
 * 
 * Methods:
 * - index(Request $request): Returns dashboard data for the authenticated user.
*/

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Get authenticated user
        $user = $request->user();
        // Fetch user info and user list
        return response()->json([
            'message' => 'Welcome to your dashboard!',
            'user' => $user->only(['id', 'name', 'email']), // pick only needed fields
            'stats' => [
                'totalUsers' => \App\Models\User::count(),
                'userList'   => \App\Models\User::select('id', 'name', 'email')->get(),
            ],
        ]);
    }
}