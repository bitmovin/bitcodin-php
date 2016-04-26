<?php

namespace bitcodin;


class Thumbnail extends ApiResource
{
    const URL_CREATE = '/thumbnail';
    const URL_GET = '/thumbnail/{id}';

    /**
     * @param ThumbnailConfig $thumbnailConfig
     * @return Thumbnail
     */
    static function create($thumbnailConfig)
    {
        $response = self::_postRequest(self::URL_CREATE, $thumbnailConfig->getRequestBody(), 200);
        return new self(json_decode($response->getBody()->getContents()));
    }

    /**
     * @param ThumbnailConfig $thumbnailConfig
     * @return Thumbnail
     */
    static function get($id)
    {
        $response = self::_getRequest(str_replace('{id}', $id, self::URL_GET), 200);
        return new self(json_decode($response->getBody()->getContents()));
    }
}
