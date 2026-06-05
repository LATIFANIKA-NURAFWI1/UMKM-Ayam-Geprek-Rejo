<?php

namespace App\Livewire\Member;

use App\Models\Member;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    public function updatedSearch(): void { $this->resetPage(); }

    public function render()
    {
        $members = Member::when($this->search, fn ($q) => $q
                ->where('name', 'like', "%{$this->search}%")
                ->orWhere('phone', 'like', "%{$this->search}%")
            )
            ->orderByDesc('points')
            ->paginate(15);

        return view('livewire.member.index', compact('members'));
    }
}
