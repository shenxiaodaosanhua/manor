<?php
declare (strict_types=1);


namespace App\Extend\Food;


use App\Exceptions\FoodRequestException;
use GuzzleHttp\Client;

class FoodApi
{

    public function isValidOrder($openid, $food_order_id)
    {
        $data = [
            'order_id' => $food_order_id
        ];

        $header = [
            'openid' => $openid
        ];

        $url = env('FOOD_HOST').'/api/shop/order/detail';

        return $this->post($url, $data,$header);
    }

    public function output($result)
    {
        $result = json_decode($result,true);

        if(isset($result['code']) && $result['code']==0){
            return $result['data'];
        }
        throw new FoodRequestException($data['message'] ?? 'order error');
    }

    public function post(string $uri, array $data, array $header = [], int $timeout = 30)
    {

        $headerOrigin = [
            'Api-Version-Number'  => 'v1.0.0',
            'App-Version-Numbers' => '1.0.0',
            'Content-Type'        => 'application/x-www-form-urlencoded',
            'openid'              => ''
        ];

        $headers = array_merge($headerOrigin, $header);

        $client = new Client();

        $response = $client->request('post', $uri, [
            'headers'     => $headers,
            'form_params' => $data,
            'timeout'     => $timeout
        ]);

        if ($response->getStatusCode() != 200) {
            throw new FoodRequestException($response->getBody()->getContents(), $response->getStatusCode());
        }

        $result = $response->getBody()->getContents();

        return $this->output($result);
    }
}
