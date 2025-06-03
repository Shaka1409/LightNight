<?php

namespace App\View\Components;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;
use App\Models\Blogs;

class BlogCard extends Component
{
    /**
     * Create a new component instance.
     */
    public $blog;
    public function __construct(Blogs $blog)
    {
        $this->blog = $blog;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('components.blog-card');
    }
}
