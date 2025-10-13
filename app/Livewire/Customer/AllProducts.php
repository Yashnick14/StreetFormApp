<?php

namespace App\Livewire\Customer;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;

class AllProducts extends Component
{
    use WithPagination;

    public array $selectedTypes = [];
    public array $selectedSizes = [];
    public array $selectedCategories = [];

    public int $minPrice = 0;
    public int $maxPrice = 1000;
    public int $floor = 0;
    public int $ceil = 1000;

    public array $typeOptions = ['Hoodies', 'Sweatshirts', 'T-Shirts', 'Cargo Pants', 'Jackets'];
    public array $sizeOptions = ['XS','S','M','L','XL'];
    public array $categoryOptions = ['Men' => 1, 'Women' => 2];

    public function mount(): void
    {
        $min = Product::min('price');
        $max = Product::max('price');

        $this->floor = (int) floor($min ?? 0);
        $this->ceil  = (int) ceil($max ?? 1000);

        $this->minPrice = $this->floor;
        $this->maxPrice = $this->ceil;
    }

    public function updating($field): void
    {
        $this->resetPage();
    }

    public function clear(): void
    {
        $this->selectedTypes = [];
        $this->selectedSizes = [];
        $this->selectedCategories = [];
        $this->minPrice = $this->floor;
        $this->maxPrice = $this->ceil;
        $this->resetPage();
    }

    public function render()
    {
        $query = Product::query();

        // Category filter (only in "All" page)
        if (!empty($this->selectedCategories)) {
            $query->whereIn('category_id', $this->selectedCategories);
        }

        if (!empty($this->selectedTypes)) {
            $query->whereIn('type', $this->selectedTypes);
        }

        if (!empty($this->selectedSizes)) {
            $query->where(function ($q) {
                foreach ($this->selectedSizes as $size) {
                    $q->orWhereRaw("JSON_EXTRACT(stockquantity, '$.\"$size\"') > 0");
                }
            });
        }

        $query->whereBetween('price', [$this->minPrice, $this->maxPrice]);

        $products = $query->paginate(12);

        return view('livewire.customer.all-products', [
            'products' => $products,
        ])->layout('layouts.app');
    }
}
