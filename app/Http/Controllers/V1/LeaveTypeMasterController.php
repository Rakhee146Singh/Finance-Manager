<?php

namespace App\Http\Controllers\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\LeaveTypeMaster;

class LeaveTypeMasterController extends Controller
{
    /**
     * API of listing Leave data.
     *
     * @return $leaves
     */
    public function list(Request $request)
    {
        $request->validate([
            'name'          => 'required|string',
            'sortOrder'     => 'required|in:asc,desc',
            'sortField'     => 'required|string',
            'perPage'       => 'required|integer',
            'currentPage'   => 'required|integer'
        ]);
        $leaves = LeaveTypeMaster::query();  //query

        //sorting
        if ($request->sortField && $request->sortOrder) {
            $leaves = $leaves->orderBy($request->sortField, $request->sortOrder);
        } else {
            $leaves = $leaves->orderBy('id', 'DESC');
        };
        //searching
        if (isset($request->name)) {
            $leaves->where("name", "LIKE", "%{$request->name}%");
        }
        //pagination
        $perPage        = $request->perPage;
        $currentPage    = $request->currentPage;
        $leaves         = $leaves->skip($perPage * ($currentPage - 1))->take($perPage);
        return response()->json([
            'success'   => true,
            'message'   => "Leave List",
            'data'      => $leaves->get()
        ]);
    }

    /**
     * API of new create Leave.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response $leave
     */
    public function create(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:50',
        ]);
        $leave = LeaveTypeMaster::create($request->only('name'));
        return response()->json([
            'success'   => true,
            'message'   => "Leave Type Created Successfully",
            'data'      => $leave
        ]);
    }

    /**
     * API to get LeaveTypeMaster with $id.
     *
     * @param  \App\LeaveTypeMaster  $id
     * @return \Illuminate\Http\Response $leave
     */
    public function show($id)
    {
        $leave = LeaveTypeMaster::findOrFail($id);
        return response()->json([
            'success'    => true,
            'data'       => $leave
        ]);
    }

    /**
     * API of Update LeaveTypeMaster Data.
     *
     * @param  \App\LeaveTypeMaster  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $leave = LeaveTypeMaster::findOrFail($id);
        $request->validate([
            'name'    => 'required|string',
        ]);
        $leave->update($request->only('name'));
        return response()->json([
            'success' => true,
            'message' => "Data Updated Successfully",
        ]);
    }

    /**
     * API of Delete LeaveTypeMaster data.
     *
     * @param  \App\LeaveTypeMaster  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        LeaveTypeMaster::findOrFail($id)->forceDelete();
        return response()->json([
            'success' => true,
            'message' => "Data Deleted Successfully",
        ]);
    }

    /**
     * API of restore LeaveTypeMaster Data.
     *
     * @param  \App\LeaveTypeMaster   $id
     * @return \Illuminate\Http\Response
     */
    public function status(Request $request, $id)
    {
        $leave = LeaveTypeMaster::findOrFail($id);
        $request->validate([
            'is_active' => 'required|bool'
        ]);
        $leave->update($request->only('is_active'));
        return response()->json([
            'success' => true,
            'message' => "Status Updated Successfully",
        ]);
    }
}
