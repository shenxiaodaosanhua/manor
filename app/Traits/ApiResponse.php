<?php


namespace App\Traits;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Trait ApiResponse
 * @package App\Traits
 */
trait ApiResponse
{

    /**
     * @var array
     */
    protected $defaultResponseData = [
        'code' => 200,
        'message' => 'success',
    ];

    /**
     * Respond with a created response and associate a location if provided.
     *
     * @param null|string $location
     * @param null $content
     * @return Response
     */
    public function created($location = null, $content = null)
    {
        $response = new Response($content);
        // 201
        $response->setStatusCode(Response::HTTP_CREATED);
        if (!is_null($location)) {
            $response->header('Location', $location);
        }

        return $response;
    }

    /**
     * Respond with an accepted response and associate a location and/or content if provided.
     *
     * @param null|string $location
     * @param mixed $content
     *
     * @return Response
     */
    public function accepted($location = null, $content = null)
    {
        $response = new Response($content);
        // 202
        $response->setStatusCode(Response::HTTP_ACCEPTED);

        if (!is_null($location)) {
            $response->header('Location', $location);
        }

        return $response;
    }

    /**
     * Respond with a no content response.
     *
     * @return Response
     */
    public function noContent()
    {
        $response = new Response(null);
        // 204
        return $response->setStatusCode(Response::HTTP_NO_CONTENT);
    }

    /**
     * Return a json response.
     * @param array $data
     * @param array $headers
     * @return Response
     */
    public function json($data = [], array $headers = [])
    {
        $code = $this->defaultResponseData['code'];
        $message = $this->defaultResponseData['message'];

        return new Response(compact('data', 'code', 'message'), Response::HTTP_OK, $headers);
    }

    /**
     *  Bind an item to a apiResource and start building a response.
     * @param       $data
     * @param       $resourceClass
     * @param array $meta
     * @return mixed
     */
    public function item($data, $resourceClass, $meta = [])
    {
        if (is_null($data)) {
            $data = [];
            $code = $this->defaultResponseData['code'];
            $message = $this->defaultResponseData['message'];
            return Response::create(compact('message', 'code', 'data'));
        }

        $meta = array_merge($meta, $this->defaultResponseData);
        return (new $resourceClass($data))->additional($meta);
    }

    /**
     * Bind a collection to a apiResource and start building a response.
     *
     * @param       $data
     * @param       $resourceClass
     * @param array $meta
     * @return Response
     */
    public function collection($data, $resourceClass, $meta = [])
    {
        if (is_null($data)) {
            $data = [];
            $code = $this->defaultResponseData['code'];
            $message = $this->defaultResponseData['message'];
            return Response::create(compact('message', 'code', 'data'));
        }

        $meta = array_merge($meta, $this->defaultResponseData);
        return $resourceClass::collection($data)->additional($meta);
    }

    /**
     * Bind a paginator to a apiResource and start building a response.
     *
     * @param Paginator $paginator
     * @param           $resourceClass
     * @param array $meta
     * @return Response
     */
    public function paginator(Paginator $paginator, $resourceClass, array $meta = [])
    {
        if (is_null($paginator)) {
            $data = [];
            $code = $this->defaultResponseData['code'];
            $message = $this->defaultResponseData['message'];
            return Response::create(compact('message', 'code', 'data'));
        }

        $meta = array_merge($meta, $this->defaultResponseData);
        return $this->collection($paginator, $resourceClass, $meta);
    }

    /**
     * Return an error response.
     *
     * @param string $message
     * @param        $statusCode
     * @return void
     */
    public function error($message, $statusCode = 400)
    {
        $data = [];
        return new Response(compact('message', 'statusCode', 'data'), $statusCode);
    }

}
