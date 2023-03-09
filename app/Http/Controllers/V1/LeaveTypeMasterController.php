<?php

namespace App\Http\Controllers\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\LeaveTypeMaster;
use App\Http\Helpers\APIResponse;

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
            'search'        => 'nullable',
            'name'          => 'nullable|string',
            'sortOrder'     => 'nullable|in:asc,desc',
            'sortField'     => 'nullable|string',
            'perPage'       => 'nullable|integer',
            'currentPage'   => 'nullable|integer'
        ]);
        $query = LeaveTypeMaster::query();  //query

        /* Searching */
        if ($request->search) {
            $query = $query->where("name", "LIKE", "%{$request->search}%");
        }

        //sorting
        if ($request->sortField || $request->sortOrder) {
            $query = $query->orderBy($request->sortField, $request->sortOrder);
        }

        /* Pagination */
        $count = $query->count();
        if ($request->perPage && $request->currentPage) {
            $perPage        = $request->perPage;
            $currentPage    = $request->currentPage;
            $query         = $query->skip($perPage * ($currentPage - 1))->take($perPage);
        }

        /* Get records */
        $leaves = $query->get();

        $data = [
            'count' => $count,
            'data'  => $leaves
        ];
        return ok('Leaves list', $data);
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
        return ok('Leave created successfully!', $leave);
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
        return ok('Leave retrieved successfully', $leave);
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
            'name'    => 'required|string|max:50',
        ]);
        $leave->update($request->only('name'));
        return ok('Leave Updated successfully', $leave);
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
        return ok('Leave deleted successfully');
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
        return ok('Leave Status updated successfully', $leave);
    }
}
