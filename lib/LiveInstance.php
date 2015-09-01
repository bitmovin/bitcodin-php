<?php
/**
 * Created by David Moser <david.moser@bitmovin.net>
 * Date: 31.08.15
 * Time: 15:29
 */

namespace bitcodin;


class LiveInstance extends ApiResource
{
    const URL_CREATE = '/live-instance';
    const URL_GET = '/live-instance/{id}';
    const URL_DELETE = '/live-instance/{id}';

    public $id;
    public $label;
    public $status;
    public $created_at;
    public $terminated_at;
    public $rtmp_push_url;
    public $mpd_url;
    public $hls_url;

    /**
     * @param string $label
     * @return LiveInstance
     */
    public static function create($label)
    {
        $requestBody = json_encode(array(
            "label" => $label
        ));
        $response = self::_postRequest(self::URL_CREATE, $requestBody, 201);
        return new self(json_decode($response->getBody()->getContents()));
    }

    public static function delete($id)
    {
        $response = self::_deleteRequest(str_replace('{id}', $id, self::URL_DELETE), 200);
        return new self(json_decode($response->getBody()->getContents()));
    }

    public function update()
    {
        self::_copy(self::get($this->id));
    }

    /**
     * @param $id
     * @return LiveInstance
     */
    public static function get($id)
    {
        $response = self::_getRequest(str_replace('{id}', $id, self::URL_GET), 200);
        return new self(json_decode($response->getBody()->getContents()));
    }

}