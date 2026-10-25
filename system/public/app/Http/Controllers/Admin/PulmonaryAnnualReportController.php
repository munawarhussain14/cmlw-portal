<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PulmonaryAnnualReportRequest;
use App\Models\FyYear;
use App\Models\Labour;
use App\Models\PulmonaryAnnualReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PulmonaryAnnualReportController extends AdminController
{
    public function __construct()
    {
        parent::__construct('labours');
    }

    protected function resolveLabourForReport(PulmonaryAnnualReport $report): Labour
    {
        $labour = Labour::where('l_id', $report->l_id)->first();
        if (!$labour) {
            abort(404);
        }

        return $labour;
    }

    public function index(Labour $labour)
    {
        if (!Gate::any(['read-labours', 'pulmonary-test-report'])) {
            abort(403);
        }

        $reports = PulmonaryAnnualReport::where('l_id', $labour->l_id)
            ->orderByDesc('test_date')
            ->orderByDesc('id')
            ->get();

        $title = 'Pulmonary annual test reports — ' . $labour->name;

        return view('admin.pulmonary_annual_reports.index', compact('labour', 'reports', 'title'));
    }

    public function create(Labour $labour)
    {
        if (!Gate::any(['update-labours', 'pulmonary-test-report'])) {
            abort(403);
        }

        $title = 'Add pulmonary annual report — ' . $labour->name;
        $row = new PulmonaryAnnualReport();
        $fyYearOptions = FyYear::optionsForSelect();
        $active = optional(FyYear::first())->getActualYear();
        $fyYearDefault = $active !== null ? (string) $active : '';

        return view('admin.pulmonary_annual_reports.form', compact('labour', 'row', 'title', 'fyYearOptions', 'fyYearDefault'));
    }

    public function store(PulmonaryAnnualReportRequest $request, Labour $labour)
    {
        if (!Gate::any(['update-labours', 'pulmonary-test-report'])) {
            abort(403);
        }

        PulmonaryAnnualReport::create([
            'l_id' => $labour->l_id,
            'test_date' => $request->test_date,
            'severity_level' => $request->severity_level,
            'remarks' => $request->remarks,
            'fy_year' => $request->fy_year,
        ]);

        return redirect()
            ->route('admin.labours.pulmonary-annual-reports.list', $labour->l_id)
            ->with('success', 'Report added successfully.');
    }

    public function edit(PulmonaryAnnualReport $pulmonary_annual_report)
    {
        if (!Gate::any(['update-labours', 'pulmonary-test-report'])) {
            abort(403);
        }

        $labour = $this->resolveLabourForReport($pulmonary_annual_report);
        $row = $pulmonary_annual_report;
        $title = 'Edit pulmonary annual report — ' . $labour->name;
        $fyYearOptions = FyYear::optionsForSelect();
        $fyYearDefault = '';

        return view('admin.pulmonary_annual_reports.form', compact('labour', 'row', 'title', 'fyYearOptions', 'fyYearDefault'));
    }

    public function update(PulmonaryAnnualReportRequest $request, PulmonaryAnnualReport $pulmonary_annual_report)
    {
        if (!Gate::any(['update-labours', 'pulmonary-test-report'])) {
            abort(403);
        }

        $labour = $this->resolveLabourForReport($pulmonary_annual_report);

        $pulmonary_annual_report->update([
            'test_date' => $request->test_date,
            'severity_level' => $request->severity_level,
            'remarks' => $request->remarks,
            'fy_year' => $request->fy_year,
        ]);

        return redirect()
            ->route('admin.labours.pulmonary-annual-reports.list', $labour->l_id)
            ->with('success', 'Report updated successfully.');
    }

    public function destroy(Request $request, PulmonaryAnnualReport $pulmonary_annual_report)
    {
        if (!Gate::any(['update-labours', 'pulmonary-test-report'])) {
            abort(403);
        }

        $labour = $this->resolveLabourForReport($pulmonary_annual_report);
        $labourId = $labour->l_id;

        $pulmonary_annual_report->delete();

        if ($request->ajax()) {
            return response()->json(['status' => 'ok', 'message' => 'Deleted successfully']);
        }

        return redirect()
            ->route('admin.labours.pulmonary-annual-reports.list', $labourId)
            ->with('success', 'Report deleted successfully.');
    }
}
