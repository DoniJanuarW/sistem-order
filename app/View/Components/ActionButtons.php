<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ActionButtons extends Component
{
    public string $editRoute;
    public string $deleteRoute;
    public int|string $id;
    public bool $showDetail;
    /**
     * Create a new component instance.
     */
    public function __construct($editRoute, $deleteRoute, $id, $showDetail)
    {
        $this->editRoute   = $editRoute;
        $this->deleteRoute = $deleteRoute;
        $this->id          = $id;
        $this->showDetail  = $showDetail;
    }


    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.action-buttons');
    }
}
