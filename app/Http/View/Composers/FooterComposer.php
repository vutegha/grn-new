<?php

namespace App\Http\View\Composers;

use App\Models\SocialLink;
use Illuminate\View\View;

class FooterComposer
{
    public function compose(View $view)
    {
        $socialLinks = SocialLink::active()
            ->orderBy('order')
            ->get();
            
        $view->with('socialLinks', $socialLinks);
    }
}
