<?php


namespace App\Services\Task;


use App\Models\Plant;

/**
 * Interface TaskInterface
 * @package App\Services\Task
 */
interface TaskInterface
{

    /**
     * @param Plant $plant
     * @return mixed
     */
    public function findState(Plant $plant);

    /**
     * @param Plant $plant
     * @return mixed
     */
    public function getState(Plant $plant);

    /**
     * @param Plant $plant
     * @return mixed
     */
    public function achieve(Plant $plant);

    /**
     * @param array $data
     * @param Plant $plant
     * @return mixed
     */
    public function getDynamic(array $data, Plant $plant);
}
