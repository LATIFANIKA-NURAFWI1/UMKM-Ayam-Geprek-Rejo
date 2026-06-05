<?php

namespace App\Livewire\Menu;

use App\Models\Category;
use App\Models\MenuItem;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
class Create extends Component
{
    use WithFileUploads;

    #[Validate('required|string|max:100')]
    public string $name = '';

    #[Validate('nullable|string|max:500')]
    public string $description = '';

    #[Validate('required|numeric|min:0')]
    public string $price = '';

    #[Validate('required|integer|exists:categories,id')]
    public string $category_id = '';

    #[Validate('boolean')]
    public bool $is_available = true;

    #[Validate('integer|min:0')]
    public int $sort_order = 0;

    #[Validate('nullable|image|max:2048')]
    public $image = null;

    public function save(): void
    {
        $this->validate();

        $data = [
            'name'         => $this->name,
            'description'  => $this->description,
            'price'        => $this->price,
            'category_id'  => $this->category_id,
            'is_available' => $this->is_available,
            'sort_order'   => $this->sort_order,
        ];

        if ($this->image) {
            $data['image'] = $this->image->store('menu', 'public');
        }

        MenuItem::create($data);

        $this->dispatch('notify', message: 'Menu berhasil ditambahkan.');
        $this->redirect(route('menu.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.menu.create', [
            'categories' => Category::active()->orderBy('name')->get(),
        ]);
    }
}
