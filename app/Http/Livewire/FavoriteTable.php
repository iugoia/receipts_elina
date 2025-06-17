<?php

namespace App\Http\Livewire;

use App\Models\Receipt;
use Livewire\Component;
use Livewire\WithPagination;

class FavoriteTable extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $search = '';

    protected $queryString = ['search'];

    public function render()
    {
        $query = Receipt::whereIn('id', user()->favorites()->pluck('receipt_id'))->whereApproved();
        if ($this->search) {
            $query->where('title', 'like', '%' . $this->search . '%')
                ->orWhere('description', 'like', '%' . $this->search . '%');
        }

        $receipts = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('livewire.favorite-table', compact('receipts'));
    }
}
