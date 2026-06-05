<?php

namespace App\Livewire\Pengeluaran;

use App\Models\Expense;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public string $bulan = '';

    public function mount(): void
    {
        $this->bulan = $this->bulan ?: today()->format('Y-m');
    }

    public function updatedSearch(): void { $this->resetPage(); }
    public function updatedBulan(): void { $this->resetPage(); }

    public function delete(int $id): void
    {
        Expense::findOrFail($id)->delete();
        $this->dispatch('notify', message: 'Pengeluaran berhasil dihapus.');
    }

    public function render()
    {
        [$year, $month] = explode('-', $this->bulan);

        $expenses = Expense::when($this->search, fn ($q) => $q->where('description', 'like', "%{$this->search}%"))
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->latest('date')
            ->paginate(15);

        $totalBulanIni = Expense::whereYear('date', $year)->whereMonth('date', $month)->sum('amount');

        return view('livewire.pengeluaran.index', compact('expenses', 'totalBulanIni'));
    }
}
