<?php

namespace App\Livewire\Kategori;

use App\Models\Category;
use Illuminate\Support\Str;
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

    public bool $showModal = false;
    public ?int $editId = null;
    public string $name = '';
    public string $icon = '';
    public int $sort_order = 0;
    public bool $is_active = true;

    public function updatedSearch(): void { $this->resetPage(); }

    public function openCreate(): void
    {
        $this->reset(['editId', 'name', 'icon', 'sort_order']);
        $this->is_active = true;
        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        $cat = Category::findOrFail($id);
        $this->editId     = $cat->id;
        $this->name       = $cat->name;
        $this->icon       = $cat->icon ?? '';
        $this->sort_order = $cat->sort_order;
        $this->is_active  = $cat->is_active;
        $this->showModal  = true;
    }

    public function save(): void
    {
        $this->validate([
            'name'       => 'required|string|max:100',
            'sort_order' => 'integer|min:0',
        ]);

        $data = [
            'name'       => $this->name,
            'slug'       => Str::slug($this->name),
            'icon'       => $this->icon,
            'sort_order' => $this->sort_order,
            'is_active'  => $this->is_active,
        ];

        if ($this->editId) {
            Category::findOrFail($this->editId)->update($data);
        } else {
            Category::create($data);
        }

        $this->showModal = false;
        $this->dispatch('notify', message: 'Kategori berhasil disimpan.');
    }

    public function delete(int $id): void
    {
        Category::findOrFail($id)->delete();
        $this->dispatch('notify', message: 'Kategori berhasil dihapus.');
    }

    public function render()
    {
        // Urut A-Z berdasarkan nama
        $categories = Category::withCount('menuItems')
            ->when($this->search, fn ($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->orderBy('name')
            ->paginate(15);

        return view('livewire.kategori.index', compact('categories'));
    }
}
