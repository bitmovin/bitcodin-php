<?php
/**
 * Created by David Moser <david.moser@bitmovin.net>
 * Date: 13.11.15
 * Time: 10:07
 */

namespace bitcodin;


class Thumbnail extends ApiResource
{
    const URL_CREATE = '/thumbnail';

    /**
     * @param ThumbnailConfig $thumbnailConfig
     * @return Thumbnail
     */
    static function create($thumbnailConfig)
    {
        $response = self::_postRequest(self::URL_CREATE, $thumbnailConfig->getRequestBody(), 200);
        return new self(json_decode($response->getBody()->getContents()));
    }}