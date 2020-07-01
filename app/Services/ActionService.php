<?php


namespace App\Services;


use App\Exceptions\ActionDriveException;
use App\Models\User;
use App\Services\Actions\ActionHomeService;

/**
 * Class ActionService
 * @package App\Services
 */
class ActionService
{

    /**
     * @var string[]
     */
    protected $drive = [
        'api/home' => ActionHomeService::class,
    ];

    /**
     * @param string $name
     * @return mixed
     * @throws ActionDriveException
     */
    public function drive($name = '')
    {
        if (! array_key_exists($name, $this->drive)) {
            throw new ActionDriveException('不存在的请求埋点');
        }

        return new $this->drive[$name];
    }
}
