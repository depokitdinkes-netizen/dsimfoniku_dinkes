<?php

namespace App\View\Components\Modal;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class RemoveInspection extends Component {
    /**
     * Create a new component instance.
     */
    public function __construct(public String $destroyRoute) {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string {
        return view('components.modal.remove-inspection');
    }
}
