<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function store(Request $request)
    {
        $report = Report::firstOrCreate(
            ['user_id' => $request->user_id, 'pin_id' => $request->pin_id],
            ['reason' => $request->reason]
        );

        return back()->with('success', 'Report has been submitted.');
    }

    public function destroy($id)
    {
        $report = Report::where('user_id', auth()->id())->where('pin_id', $id);
        $report->delete();

        return back()->with('success', 'Report has been removed.');
    }
}
