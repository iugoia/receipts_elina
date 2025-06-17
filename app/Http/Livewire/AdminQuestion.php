<?php

namespace App\Http\Livewire;

use App\Models\Question;
use Livewire\Component;
use Livewire\WithPagination;

class AdminQuestion extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $statusFilter = 'new';


    public function render()
    {
        $questions = Question::query();

        if ($this->statusFilter)
            $questions->where('status', $this->statusFilter);

        $questions = $questions->orderBy('id', 'desc');

        $questions = $questions->paginate(20);
        return view('livewire.admin-question', compact('questions'));
    }
}
