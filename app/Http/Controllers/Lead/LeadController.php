<?php

namespace App\Http\Controllers\Lead;

use App\Models\Lead;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

use Log;
class LeadController extends Controller
{

    public function index(Request $request)
    {
            if ($request->ajax()) {
                $lead_datatable = Lead::withCount('leadUpdate');

                return DataTables::of($lead_datatable)
                    ->make(true);
            }

        return view('leads.index');
    }

    public function edit($id)
    {
        $lead = Lead::findOrFail($id);
        return response()->json($lead);
    }

   public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:500',
            'source' => 'required|string|max:255',
            'status' => 'required|in:new,accepted,completed,rejected,invalid',
        ]);

        $lead = Lead::findOrFail($id);
        $lead->update($request->only(['name', 'description', 'source', 'status']));

        return response()->json(['message' => 'Lead updated successfully']);
    }


}

