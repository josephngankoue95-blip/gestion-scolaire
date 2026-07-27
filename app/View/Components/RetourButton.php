<?php
namespace App\View\Components;

use Illuminate\View\Component;

class RetourButton extends Component
{
    public string $url;
    public string $label;

    public function __construct(string $fallbackRoute, string $label = '← Retour', ?array $fallbackParams = [])
    {
        // Utilise l'URL précédente si elle appartient au même domaine, sinon fallback
        $previous = url()->previous();
        $current  = url()->current();

        $this->url = ($previous && $previous !== $current)
            ? $previous
            : route($fallbackRoute, $fallbackParams);

        $this->label = $label;
    }

    public function render()
    {
        return view('components.retour-button');
    }
}