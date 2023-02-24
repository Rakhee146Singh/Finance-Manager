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
            'year'          => 'required|integer|digits:4',
            'sortOrder'     => 'required|in:asc,desc',
            'sortField'     => 'required',
            'perPage'       => 'required|integer',
            'currentPage'   => 'required|integer'
        ]);
        $finances = FinancialYear::query(); //query

        //sorting
        if ($request->sortField && $request->sortOrder) {
            $finances = $finances->orderBy($request->sortField, $request->sortOrder);
        } else {
            $finances = $finances->orderBy('id', 'DESC');
        };

        //searching
        if (isset($request->name)) {
            $finances->where("year", "LIKE", "%{$request->year}%");
        }
        //pagination
        $perPage        = $request->perPage;
        $currentPage    = $request->currentPage;
        $finances       = $finances->skip($perPage * ($currentPage - 1))->take($perPage);
        return response()->json([
            'success'   => true,
            'message'   => "Financial Year List",
            'data'      => $finances->get()
        ]);
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
            'start_date'    => 'required',
            'end_date'      => 'required',
        ]);
        $finance = FinancialYear::create($request->only('year', 'start_date', 'end_date'));
        return response()->json([
            'success'   => true,
            'message'   => "Year Created Successfully",
            'data'      => $finance
        ]);
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
        return response()->json([
            'success' => true,
            'data'    => $finance
        ]);
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
            'start_date'    => 'required',
            'end_date'      => 'required|after:start_date'
        ]);
        $finance->update($request->only('year', 'start_date', 'end_date'));
        return response()->json([
            'success' => true,
            'message' => "Data Updated Successfully",
        ]);
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
        return response()->json([
            'success' => true,
            'message' => "Data Deleted Successfully",
        ]);
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
        return response()->json([
            'success' => true,
            'message' => "Status Updated Successfully",
        ]);
    }
}
