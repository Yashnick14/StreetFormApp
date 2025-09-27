<?php

namespace App\Livewire\Customer;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;

class WomenProducts extends Component
{
    use WithPagination;

    public array $selectedTypes = [];
    public array $selectedSizes = [];
    public int $minPrice = 0;
    public int $maxPrice = 1000;
    public int $floor = 0;
    public int $ceil = 1000;

    // Product types
    public array $typeOptions = [
        'Hoodies',
        'Sweatshirts',
        'T-Shirts',
        'Cargo Pants',
        'Jackets',
    ];

    public array $categoryOptions = ['Men' => 1, 'Women' => 2];

    // Standard sizes
    public array $sizeOptions = [
        'XS','S','M','L','XL',
    ];

    public function mount(): void
    {
        $min = Product::where('category_id', 2)->min('price');
        $max = Product::where('category_id', 2)->max('price');

        $this->floor = (int) floor($min ?? 0);
        $this->ceil  = (int) ceil($max ?? 1000);

        $this->minPrice = $this->floor;
        $this->maxPrice = $this->ceil;
    }

    public function updating($field): void
    {
        $this->resetPage();
    }

    public function updatedMinPrice($value): void
    {
        if ($value > $this->maxPrice) {
            $this->minPrice = $this->maxPrice;
        }
    }

    public function updatedMaxPrice($value): void
    {
        if ($value < $this->minPrice) {
            $this->maxPrice = $this->minPrice;
        }
    }

    public function clear(): void
    {
        $this->selectedTypes = [];
        $this->selectedSizes = [];
        $this->minPrice = $this->floor;
        $this->maxPrice = $this->ceil;
        $this->resetPage();
    }

    public function render()
    {
        $query = Product::query()->where('category_id', 2);

        if (!empty($this->selectedTypes)) {
            $query->whereIn('type', $this->selectedTypes);
        }

        if (!empty($this->selectedSizes)) {
            $query->where(function ($q) {
                foreach ($this->selectedSizes as $size) {
                    $q->orWhere(function ($sub) use ($size) {
                        $sub->whereRaw("JSON_EXTRACT(stockquantity, '$.\"$size\"') > 0");
                    });
                }
            });
        }

        $query->whereBetween('price', [$this->minPrice, $this->maxPrice]);

        $products = $query->paginate(12);

        return view('livewire.customer.women-products', [
            'products' => $products,
        ])->layout('layouts.app');
    }
}
