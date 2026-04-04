<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ButtonSpinner extends Component
{
    public string $id;
    public string $type;
    public string $variant;
    public string $text;
    public string $loadingText;

    public function __construct(
        string $id = 'btn-submit',
        string $type = 'submit',
        string $variant = 'primary',
        string $text = 'Simpan',
        string $loadingText = 'Menyimpan...'
    ) {
        $this->id = $id;
        $this->type = $type;
        $this->variant = $variant;
        $this->text = $text;
        $this->loadingText = $loadingText;
    }

    public function render()
    {
        return view('components.button-spinner');
    }
}
