<?php

namespace App\Http\Controllers\Lead;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LeadUpdate;
use Auth;
class LeadUpdateController extends Controller
{

    public function index(Request $request)
    {
        $updates = LeadUpdate::where('lead_id',$request->lead_id)->with('user')->orderBy('created_at','desc')->get();
        return response()->json($updates);
    }

    public function store(Request $request)
    {
        $request->validate([
            'lead_id' => 'required',
            'lead_message' => 'required|string',

        ]);

        $leads = LeadUpdate::create([
            'lead_id' => $request->lead_id,
            'lead_message' => $request->lead_message,
            'user_id' => Auth::id(),
            'timestamp' => now()
        ]);


        return response()->json($leads);
    }


}
