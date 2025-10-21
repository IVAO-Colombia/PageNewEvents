<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\News as NewsModel;

class News extends Component
{
    public $news;

    public function mount()
    {
        $this->news = NewsModel::where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->get();
    }
    public function render()
    {
        return view('livewire.news', ['news' => $this->news]);
    }
}
