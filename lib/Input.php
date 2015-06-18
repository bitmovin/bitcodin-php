<?php
/**
 * Created by PhpStorm.
 * User: cwioro
 * Date: 18.06.15
 * Time: 13:27
 */

namespace bitcodin;


use bitcodin\exceptions\BitcodinException;

abstract class Input extends ApiResource {

    const URL_CREATE = '/input/create';
    const URL_ANALYZE = '/input/{id}/analyze';


    public static function analyze($id)
    {
        if($id instanceof \stdClass)
            $id = $id->inputId;


        $response = self::_patchRequest(str_replace('{id}', $id, self::URL_ANALYZE));

       self::_checkExpectedStatus($response, 200);

    }

}