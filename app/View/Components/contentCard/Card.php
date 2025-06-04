<?php

namespace App\View\Components\contentCard;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class Card extends Component
{
    public $image;
    public $type;
    public $title;
    public function __construct($image, $type = null, $title = null)
    {
        $this->image = $image;
        $this->type = $type;
        $this->title = $title;
    }

    public function badgeColor()
    {
        return match ($this->type) {
            'live' => 'bg-red-600 ',
            'upcoming' => 'bg-green-600',
            default => 'bg-gray-500',
        };
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.content-card.card');
    }
}
