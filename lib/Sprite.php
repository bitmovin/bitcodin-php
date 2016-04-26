<?php

namespace bitcodin;


class Sprite extends ApiResource
{
    const URL_CREATE = '/sprite';
    const URL_GET = '/sprite/{id}';

    /**
     * @param SpriteConfig $spriteConfig
     * @return Sprite
     */
    static function create($spriteConfig)
    {
        $response = self::_postRequest(self::URL_CREATE, $spriteConfig->getRequestBody(), 200);
        return new self(json_decode($response->getBody()->getContents()));
    }

    /**
     * @param SpriteConfig $spriteConfig
     * @return Sprite
     */
    static function get($id)
    {
        $response = self::_getRequest(str_replace('{id}', $id, self::URL_GET), 200);
        return new self(json_decode($response->getBody()->getContents()));
    }
}
