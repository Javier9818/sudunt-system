<?php

namespace App\View\Components;

use Illuminate\View\Component;

class SideNav extends Component
{
    public $tab;
    public $selected;

    public function __construct($tab, $selected)
    {
        $this->selected = $selected;
        $this->tab = $tab;
    }

    public function isSelected($tab, $option)
    {
        return ($option === $this->selected && $tab === $this->tab);
    }

    public function isTab($tab)
    {
        return $tab === $this->tab;
    }

    public function render()
    {
        return view('components.side-nav');
    }
}
