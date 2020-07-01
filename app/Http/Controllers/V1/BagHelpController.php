<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\BagHelpResource;
use App\Models\BagHelp;
use Illuminate\Http\Request;

/**
 * Class BagHelpController
 * @package App\Http\Controllers\V1
 */
class BagHelpController extends ApiController
{
    /**
     * @return mixed
     */
    public function index()
    {
        $help = BagHelp::orderBy('id', 'desc')->first();
        return $this->item($help, BagHelpResource::class);
    }
}
