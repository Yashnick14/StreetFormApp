<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;
use Livewire\Attributes\On;

class ProductManagement extends Component
{
    use WithPagination;

    public $editingProductId = null;
    public $showModal = false;
    public $search = '';
    public $sortBy = 'name';
    public $sortDirection = 'asc';

    protected $queryString = ['search', 'sortBy', 'sortDirection'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        $this->sortDirection = $this->sortBy === $field && $this->sortDirection === 'asc'
            ? 'desc'
            : 'asc';
        $this->sortBy = $field;
    }

    public function openModal($id = null)
    {
        $this->editingProductId = $id;
        $this->showModal = true;
        $this->dispatch('loadProduct', $id);
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->editingProductId = null;
    }

    #[On('refresh')]
    public function refreshComponent()
    {
        $this->resetPage();
    }

    public function render()
    {
        $products = Product::query()
            ->with('category')
            ->when($this->search, function ($query) {
                $s = strtolower(trim($this->search));
                $query->where(function ($q) use ($s) {
                    $q->whereRaw('LOWER(name) LIKE ?', ["%{$s}%"])
                      ->orWhereRaw('LOWER(description) LIKE ?', ["%{$s}%"])
                      ->orWhereRaw('LOWER(type) LIKE ?', ["%{$s}%"])
                      ->orWhereHas('category', function($categoryQuery) use ($s) {
                          $categoryQuery->whereRaw('LOWER(name) LIKE ?', ["%{$s}%"]);
                      });
                });
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(10);

        return view('livewire.admin.product-management', compact('products'))
            ->layout('layouts.admin');
    }
}