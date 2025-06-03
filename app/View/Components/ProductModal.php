<?php

namespace App\View\Components;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;
use app\Models\Product;
use App\Models\Category;

class ProductModal extends Component
{
    /**
     * Create a new component instance.
     */
    public $product;
    public $category;

    public function __construct(Product $product)
    {
        $this->product = $product;
        $this->category = $product->category;
    }
    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.product-modal');
    }
}
