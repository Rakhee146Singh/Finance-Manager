<?php

namespace App\Http\Controllers\V1;

use Illuminate\Http\Request;
use App\Models\FinancialYear;
use App\Http\Controllers\Controller;

class FinancialYearController extends Controller
{
    /**
     * API of listing Financial data.
     *
     * @return $finances
     */
    public function list(Request $request)
    {
        $request->validate([
            'search'        => 'nullable|integer|digits:4',
            'sortOrder'     => 'nullable|in:asc,desc',
            'sortField'     => 'nullable',
            'perPage'       => 'nullable|integer',
            'currentPage'   => 'nullable|integer'
        ]);
        $query = FinancialYear::query(); //query

        /* Searching */
        if (isset($request->search)) {
            $query = $query->where("year", "LIKE", "%{$request->search}%");
        }
        /* Sorting */
        if ($request->sortField || $request->sortOrder) {
            $query = $query->orderBy($request->sortField, $request->sortOrder);
        }

        /* Pagination */
        $count = $query->count();
        if ($request->perPage && $request->currentPage) {
            $perPage        = $request->perPage;
            $currentPage    = $request->currentPage;
            $query       = $query->skip($perPage * ($currentPage - 1))->take($perPage);
        }
        /* Get records */
        $finances = $query->get();
        $data = [
            'count' => $count,
            'data'  => $finances
        ];
        return ok('Finance list', $data);
    }

    /**
     * API of new create Finance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response $finance
     */
    public function create(Request $request)
    {
        $request->validate([
            'year'          => 'required|max:' . (date('Y') + 1),
            'start_date'    => 'required|date_format:Y-m-d',
            'end_date'      => 'required_if:start_date,after_or_equal:start_date|date_format:Y-m-d',
        ]);
        $finance = FinancialYear::create($request->only('year', 'start_date', 'end_date'));
        return ok('Finance created successfully!', $finance);
    }

    /**
     * API to get Finance with $id.
     *
     * @param  \App\FinancialYear  $id
     * @return \Illuminate\Http\Response $finance
     */
    public function show($id)
    {
        $finance = FinancialYear::findOrFail($id);
        return ok('Finance retrieved successfully', $finance);
    }

    /**
     * API of Update Finance Data.
     *
     * @param  \App\FinancialYear  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $finance = FinancialYear::findOrFail($id);
        $request->validate([
            'year'          => 'required|max:' . (date('Y') + 1),
            'start_date'    => 'required|date_format:Y-m-d',
            'end_date'      => 'required_if:start_date,after_or_equal:start_date|date_format:Y-m-d',
        ]);
        $finance->update($request->only('year', 'start_date', 'end_date'));
        return ok('Finance updated successfully', $finance);
    }

    /**
     * API of Delete Finance data.
     *
     * @param  \App\FinancialYear  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        FinancialYear::findOrFail($id)->delete();
        return ok('Finance deleted successfully');
    }


    /**
     * API of restore FinancialYear Data.
     *
     * @param  \App\FinancialYear   $id
     * @return \Illuminate\Http\Response
     */
    public function status(Request $request, $id)
    {
        $finance = FinancialYear::findOrFail($id);
        $request->validate([
            'is_active' => 'required|bool'
        ]);
        $finance->update($request->only('is_active'));
        return ok('Finance status updated successfully', $finance);
    }
}
