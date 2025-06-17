<?php

namespace App\Http\Livewire;

use App\Models\Receipt;
use Livewire\Component;
use Livewire\WithPagination;

class MyReceiptsTable extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $search = '';

    protected $queryString = ['search'];

    public function render()
    {
        $query = Receipt::query()
            ->whereAuthUser();

        if ($this->search) {
            $query->where('title', 'like', '%' . $this->search . '%')
                ->orWhere('description', 'like', '%' . $this->search . '%');
        }

        $recipes = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('livewire.my-receipts-table', compact('recipes'));
    }
}
