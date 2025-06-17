<?php

namespace App\Http\Livewire;

use App\Models\Comment;
use Livewire\Component;
use Livewire\WithPagination;

class AdminComment extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $statusFilter = null;

    public function render()
    {
        $comments = Comment::query();

        if ($this->statusFilter)
            $comments = $comments->where('status', $this->statusFilter);

        $comments = $comments->paginate(10);
        return view('livewire.admin-comment', compact('comments'));
    }
}
