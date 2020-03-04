<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Support\Arr;

class JsonResponseFormatter
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if (!$this->isJsonResponse($response)) {
            return $response;
        }

        $response->setData($this->formatResponseData($response));

        return $response;
    }

    /**
     * 确认响应内容是否是 Json 响应.
     *
     * @param mixed $response
     *
     * @return bool
     */
    protected function isJsonResponse($response): bool
    {
        return $response instanceof JsonResponse;
    }

    /**
     * 格式化响应数据.
     *
     * @param \Illuminate\Http\JsonResponse $response
     *
     * @return array
     */
    protected function formatResponseData($response): array
    {
        $content = $response->getData(true);

        $defaultOptions = [
            'code' => 0,
            'message' => '',
        ];

        // 如果响应内容符合标准格式
        if (isset($content['code'], $content['data'], $content['message'])) {
            return $content;
        }

        // 如果响应内容带分页的 API 资源
        if (isset($content['data'], $content['links'], $content['meta'])) {
            return array_merge($defaultOptions, $content);
        }

        $originalContent = $response->getOriginalContent();

        // 处理分页数据
        if ($originalContent instanceof AbstractPaginator) {
            $data = [
                'data' => $content['data'],
                'meta' => Arr::except($content, [
                    'data',
                    'first_page_url',
                    'last_page_url',
                    'prev_page_url',
                    'next_page_url',
                ]),
            ];

            return array_merge($defaultOptions, $data);
        }

        return [
            'code' => 0,
            'data' => $content,
            'message' => '',
        ];
    }
}
