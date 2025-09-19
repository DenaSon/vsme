<?php

namespace App\Livewire\UserDashboard\Wizard\Modules;

use App\Models\ReportEvidence;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;
use Throwable;

class EvidenceUploader extends Component
{
    use WithFileUploads, Toast;

    public int $reportId;
    public string $questionKey;

    /** @var TemporaryUploadedFile[] */
    #[Rule('required')]
    public array $uploads = [];

    public string $accept = '.pdf,.jpg,.jpeg,.png';
    public int $maxFiles = 10;
    public int $maxSizeMb = 10;
    public string $label;


    public function mount(): void
    {

        $this->label = __('ui.attach_files');
    }


    protected function rules(): array
    {
        $mimes = ['pdf', 'jpg', 'jpeg', 'png', 'doc', 'docx', 'xls', 'xlsx'];
        return [
            'uploads' => ['array', 'max:' . $this->maxFiles],
            'uploads.*' => ['file', 'max:' . ($this->maxSizeMb * 1024), 'mimes:' . implode(',', $mimes)],
        ];
    }

    public function remove(int $i): void
    {
        unset($this->uploads[$i]);
        $this->uploads = array_values($this->uploads);
    }

    public function resetUploads(): void
    {
        $this->uploads = [];
    }

    /**
     * @throws Throwable
     */
    public function save(): void
    {
        $this->validate();

        $max = $this->maxFiles;
        $existingCount = \App\Models\ReportEvidence::where('report_id', $this->reportId)
            ->where('question_key', $this->questionKey)
            ->count();

        if ($existingCount + count($this->uploads) > $max) {
            $this->addError('uploads', __('ui.attach_files_limit', ['n' => $max]));
            return;
        }

        DB::transaction(function () {
            $disk = 'public';
            $basePath = "reports/{$this->reportId}/{$this->questionKey}";

            foreach ($this->uploads as $file) {
                $ext = $file->getClientOriginalExtension();
                $safeName = Str::ulid() . ($ext ? '.' . $ext : '');
                $storedPath = $file->storeAs($basePath, $safeName, $disk);

                \App\Models\ReportEvidence::create([
                    'report_id' => $this->reportId,
                    'question_key' => $this->questionKey,
                    'path' => $storedPath,
                    'original_name' => $file->getClientOriginalName(),
                    'mime' => $file->getMimeType(),
                    'size' => $file->getSize(),
                ]);
            }
        });

        $this->uploads = [];
        $this->resetUploads();
        $this->dispatch('evidence:uploaded', key: $this->questionKey);

        $this->success(__('ui.files_uploaded'));
    }

    public function deleteEvidence($id): void
    {

        $evidence = \App\Models\ReportEvidence::find($id);

        if (! $evidence) {

            session()->flash('error', __('Evidence not found.'));
            return;
        }


        if ($evidence->path && \Illuminate\Support\Facades\Storage::exists($evidence->path)) {
            \Illuminate\Support\Facades\Storage::delete($evidence->path);
        }

        $evidence->delete();


        $this->info('Delete file', __('Evidence deleted successfully.'));
    }


    public function render()
    {
        $evidences = ReportEvidence::where('question_key', $this->questionKey)
            ->where('report_id',$this->reportId)
            ->get();

        return view('livewire.user-dashboard.wizard.modules.evidence-uploader')->with('evidences', $evidences);
    }
}
