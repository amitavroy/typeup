<?php

namespace App\Actions;

use App\Models\Site;

class SiteUpdate
{
    /**
     * Update a site.
     */
    public function execute(Site $site, array $data): Site
    {
        $site->update($data);

        return $site->fresh();
    }
}
