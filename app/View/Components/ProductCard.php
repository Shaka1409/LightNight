<?php

namespace App\View\Components;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;
use App\Models\Product;

class ProductCard extends Component
{
    /**
     * Create a new component instance.
     */
   public $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function render()
    {
        return view('components.product-card'); 
    }
}
