<?php

namespace App\Livewire\Laporan;

use App\Services\ProfitLossService;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('layouts.app')]
class Index extends Component
{
    #[Url]
    public string $dari = '';

    #[Url]
    public string $sampai = '';

    #[Url]
    public string $preset = 'bulan_ini';

    // =========================================================================
    // LIFECYCLE
    // =========================================================================

    public function mount(): void
    {
        if (! $this->dari && ! $this->sampai) {
            $this->applyPreset($this->preset);
        }
    }

    // =========================================================================
    // PRESET SHORTCUTS
    // =========================================================================

    public function applyPreset(string $p): void
    {
        $this->preset = $p;

        match ($p) {
            'hari_ini'    => [$this->dari, $this->sampai] = [today()->toDateString(), today()->toDateString()],
            'minggu_ini'  => [$this->dari, $this->sampai] = [now()->startOfWeek()->toDateString(), now()->endOfWeek()->toDateString()],
            'bulan_ini'   => [$this->dari, $this->sampai] = [now()->startOfMonth()->toDateString(), now()->toDateString()],
            'bulan_lalu'  => [$this->dari, $this->sampai] = [now()->subMonth()->startOfMonth()->toDateString(), now()->subMonth()->endOfMonth()->toDateString()],
            'tahun_ini'   => [$this->dari, $this->sampai] = [now()->startOfYear()->toDateString(), now()->toDateString()],
            default       => null,
        };
    }

    public function updatedDari(): void   { $this->preset = 'custom'; }
    public function updatedSampai(): void { $this->preset = 'custom'; }

    // =========================================================================
    // COMPUTED
    // =========================================================================

    #[Computed]
    public function report(): array
    {
        if (! $this->dari || ! $this->sampai) {
            return [];
        }
        return app(ProfitLossService::class)->calculate($this->dari, $this->sampai);
    }

    #[Computed]
    public function dailyTrend(): array
    {
        if (! $this->dari || ! $this->sampai) {
            return [];
        }
        return app(ProfitLossService::class)->dailyTrend($this->dari, $this->sampai);
    }

    #[Computed]
    public function topItems(): array
    {
        if (! $this->dari || ! $this->sampai) {
            return [];
        }
        return app(ProfitLossService::class)->topMenuItems($this->dari, $this->sampai, 10);
    }

    // =========================================================================
    // EXPORT
    // =========================================================================

    public function exportPdf()
    {
        $report = $this->report;
        $topItems = $this->topItems;
        $dari = $this->dari;
        $sampai = $this->sampai;

        if (empty($report)) {
            session()->flash('error', 'Data tidak tersedia untuk diekspor.');
            return;
        }

        $pdf = Pdf::loadView('exports.laporan-labarugi', compact('report', 'topItems', 'dari', 'sampai'));
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'Laporan_Laba_Rugi_' . $dari . '_to_' . $sampai . '.pdf');
    }

    // =========================================================================
    // RENDER
    // =========================================================================

    public function render()
    {
        return view('livewire.laporan.index');
    }
}
