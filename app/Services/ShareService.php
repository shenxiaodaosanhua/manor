<?php


namespace App\Services;


use App\Models\Share;

/**
 * Class ShareService
 * @package App\Services
 */
class ShareService
{
    /**
     * @param int $type
     * @return mixed
     */
    public function findShareByType(int $type)
    {
        return Share::orderBy('id', 'desc')
            ->where('type', $type)
            ->firstOrFail();
    }
}
