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

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|min:5|max:20',
            'email' => 'required|email|max:255',
            'mobile'  => 'required|digits_between:7,15,',
            'description' => 'required|string|min:50|max:500',
            'source' => 'required|string|min:5|max:20',
            'status' => 'required|in:new,accepted,completed,rejected,invalid',
        ]);

        $lead = Lead::create($request->only(['name','email','mobile', 'description', 'source', 'status']));

        return response()->json(['message' => 'Lead added successfully']);
    }

    public function edit($id)
    {
        $lead = Lead::findOrFail($id);
        return response()->json($lead);
    }

   public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|min:5|max:20',
            'description' => 'required|string|min:50|max:500',
            'source' => 'required|string|min:5|max:20',
            'status' => 'required|in:new,accepted,completed,rejected,invalid',
        ]);

        $lead = Lead::findOrFail($id);
        $lead->update($request->only(['name', 'description', 'source', 'status']));

        return response()->json(['message' => 'Lead updated successfully']);
    }


}

