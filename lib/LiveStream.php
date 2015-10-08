<?php
/**
 * Created by David Moser <david.moser@bitmovin.net>
 * Date: 31.08.15
 * Time: 15:29
 */

namespace bitcodin;


class LiveStream extends ApiResource
{
    const URL_CREATE = '/livestream';
    const URL_ALL = '/livestream';
    const URL_GET = '/livestream/{id}';
    const URL_DELETE = '/livestream/{id}';

    const STATUS_RUNNING = 'RUNNING';
    const STATUS_STARTING = 'STARTING';
    const STATUS_STOPPING = 'STOPPING';
    const STATUS_TERMINATED = 'TERMINATED';
    const STATUS_ERROR = 'ERROR';

    public $id;
    public $label;
    public $status;
    public $createdAt;
    public $terminatedAt;
    public $rtmpPushUrl;
    public $mpdUrl;
    public $hlsUrl;
    public $streamKey;
    public $timeshift;

    /**
     * @param string $label
     * @param string $streamKey
     * @param EncodingProfile $encodingProfile
     * @param Output $output
     * @param int $timeshift
     * @return LiveStream
     */
    public static function create($label, $streamKey, $encodingProfile, $output, $timeshift=30)
    {
        $requestBody = json_encode(array(
            "label" => $label,
            "encodingProfileId" => $encodingProfile->encodingProfileId,
            "streamKey" => $streamKey,
            "timeshift" => $timeshift,
            "outputId" => $output->outputId
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
     * @return LiveStream
     */
    public static function get($id)
    {
        $response = self::_getRequest(str_replace('{id}', $id, self::URL_GET), 200);
        return new self(json_decode($response->getBody()->getContents()));
    }

    public static function getAll($status=null, $limit=null, $offset=null)
    {
        $query = array();

        if(is_numeric($limit))
            $query['limit'] = $limit;
        if(is_numeric($offset))
            $query['offset'] = $offset;
        if(!is_null($status))
        {
            if(is_array($status))
            {
                $index = 0;
                foreach($status as $s)
                {
                    $query['status['.$index.']'] = $s;
                    $index++;
                }
            }
            else
            {
                $query['status'] = $status;
            }
        }

        $response = self::_getRequest(self::URL_ALL, 200, $query);
        return json_decode($response->getBody()->getContents());
    }

    public static function getCount()
    {
        $response = self::_getRequest(self::URL_ALL.'/information/count', 200);
        return json_decode($response->getBody()->getContents());
    }

}