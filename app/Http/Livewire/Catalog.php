<?php

namespace App\Http\Livewire;

use App\Models\Category;
use App\Models\Cuisine;
use App\Models\Period;
use App\Models\Receipt;
use Livewire\Component;
use Livewire\WithPagination;

class Catalog extends Component
{
    use WithPagination;

    public string $cuisine = '';
    public string $category = '';
    public string $search = '';
    public string $period = '';

    protected $paginationTheme = 'bootstrap';

    public function render()
    {
        $cuisines = Cuisine::all();
        $categories = Category::all();
        $periods = Period::all();

        $receipts = Receipt::query()->whereApproved();

        if ($this->cuisine !== '') {
            $receipts->where('cuisine_id', $this->cuisine);
        }

        if ($this->category !== '') {
            $receipts->where('category_id', $this->category);
        }

        if ($this->period !== '') {
            $receipts->where('period_id', $this->period);
        }

        if ($this->search !== '') {
            $receipts->where(function ($query) {
                $query->whereRaw('LOWER(title) LIKE ?', ['%' . strtolower($this->search) . '%'])
                    ->orWhereRaw('LOWER(description) LIKE ?', ['%' . strtolower($this->search) . '%']);
            });
        }

        $receipts = $receipts->paginate(6);

        return view('livewire.catalog', compact('receipts', 'cuisines', 'categories', 'periods'));
    }

    public function resetFilters()
    {
        $this->cuisine = '';
        $this->category = '';
        $this->search = '';
        $this->period = '';
    }
}
