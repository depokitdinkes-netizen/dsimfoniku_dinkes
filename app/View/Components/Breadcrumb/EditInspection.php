<?php

namespace App\View\Components\Breadcrumb;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class EditInspection extends Component {
    /**
     * Create a new component instance.
     */
    public function __construct(public String $showRoute) {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string {
        return view('components.breadcrumb.edit-inspection');
    }
}
