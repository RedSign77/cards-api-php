<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\UserActivityLog;
use Illuminate\Http\Request;

class UserActivityLogs extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = UserActivityLog::query();

        if ($request->has('user_id')) {
            $query->where('user_id', $request->input('user_id'));
        }

        if ($request->has('event_type')) {
            $query->where('event_type', $request->input('event_type'));
        }

        $logs = $query->orderBy('created_at', 'desc')->get();

        return response()->json($logs);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $log = UserActivityLog::findOrFail($id);

        return response()->json($log);
    }
}
