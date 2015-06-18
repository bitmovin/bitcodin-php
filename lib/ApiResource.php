<?php
/**
 * Created by PhpStorm.
 * User: cwioro
 * Date: 18.06.15
 * Time: 13:38
 */

namespace bitcodin;
use bitcodin\exceptions\BitcodinException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Message\Response;

abstract class ApiResource
{

    /**
     * @param $url
     * @param $body
     * @return \GuzzleHttp\Message\FutureResponse|\GuzzleHttp\Message\ResponseInterface|\GuzzleHttp\Ring\Future\FutureInterface|null
     * @throws exceptions\BitcodinException
     */
    protected static function _postRequest($url, $body)
    {
        $httpClient = new Client();
        try{
            $res = $httpClient->post(Bitcodin::BASE_URL . $url, array(
                'headers' => array(
                    'Content-type' => 'application/json',
                    'Accept' => 'application/json',
                    Bitcodin::API_KEY_FIELD_NAME => Bitcodin::getApiToken()
                ),
                'body' => json_encode($body, 0, 512)
            ));

            return $res;
        } catch (ClientException $ex)
        {
            return $ex->getResponse();
        }
    }

    protected static function _patchRequest($url)
    {

        var_dump("url",$url);
        $httpClient = new Client();
        var_dump(Bitcodin::BASE_URL . $url);
        $res = $httpClient->patch(Bitcodin::BASE_URL . $url, array(
            'headers' => array(
           //     'Content-type' => 'application/json',
              //  'Accept' => 'application/json',
                Bitcodin::API_KEY_FIELD_NAME => Bitcodin::getApiToken()
            )
        ));

        return $res;

    }

    protected static function _checkExpectedStatus(Response $response, $status)
    {
        if($response->getStatusCode() !== $status)
        {
            $response = json_decode($response->getBody()->getContents());
            if(isset($response->message))
                throw new BitcodinException($response->message);
            else
                throw new BitcodinException('Something went wrong during api request: Response status['.$response->getStatusCode().'] does not match expected status ['.$status.']! Response: ' . $response->getBody()->getContents());
        }
    }

}