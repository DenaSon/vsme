<?php

namespace App\Livewire\UserDashboard\Report;

use App\Models\Report;
use App\Models\ReportSnapshot;
use App\Services\Reporting\SnapshotBuilder;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class ShowReport extends Component
{
    /** @var Report */
    public Report $report;


    public ?ReportSnapshot $snapshot = null;

    public function mount(Report $report): void
    {


        $this->report = $report->loadMissing(['questionnaire']);
        $this->loadSnapshot();

        $this->generateSnapshot();
    }


    public function loadSnapshot(): void
    {
        $this->snapshot = ReportSnapshot::query()
            ->where('report_id', $this->report->id)
            ->where('scope', 'basic')        // MVP: فقط basic
            ->where('is_latest', true)
            ->first();
    }


    public function generateSnapshot(): void
    {


        $snap = SnapshotBuilder::for($this->report)
            ->basic()
            ->withLocale(app()->getLocale())
            ->byUser(auth()->id())
            ->buildAndStore();

        $this->snapshot = $snap;



    }

    public function render(): View
    {
        return view('livewire.user-dashboard.report.show-report', [
            'report'   => $this->report,
            'snapshot' => $this->snapshot,
            'payload'  => $this->snapshot?->payload_json, // مستقیم برای ویو
        ]);
    }
}
