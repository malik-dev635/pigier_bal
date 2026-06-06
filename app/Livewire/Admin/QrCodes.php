<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('QR codes')]
class QrCodes extends Component
{
    public function render(): View
    {
        $this->authorize('viewAny', Category::class);

        $categories = Category::query()
            ->orderBy('voter_type')
            ->orderBy('name')
            ->get();

        return view('livewire.admin.qr-codes', [
            'categories' => $categories,
        ]);
    }
}
