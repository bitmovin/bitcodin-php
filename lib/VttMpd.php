<?php
/**
 * Created by David Moser <david.moser@bitmovin.net>
 * Date: 04.11.15
 * Time: 16:34
 */

namespace bitcodin;


class VttMpd extends ApiResource
{
    const URL_CREATE = '/manifest/mpd/vtt';

    /**
     * @param VttMpdConfig $vttMpdConfig
     * @return VttMpd
     */
    static function create($vttMpdConfig)
    {
        $response = self::_postRequest(self::URL_CREATE, $vttMpdConfig->getRequestBody(), 200);
        return new self(json_decode($response->getBody()->getContents()));
    }
}