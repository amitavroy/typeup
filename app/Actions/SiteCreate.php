<?php

namespace App\Actions;

use App\Models\Site;
use Illuminate\Support\Str;

class SiteCreate
{
    /**
     * Create a new site.
     */
    public function execute(array $data): Site
    {
        $data['site_key'] = Str::uuid()->toString();

        return Site::create($data);
    }
}
