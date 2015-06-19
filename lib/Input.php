<?php
/**
 * Created by PhpStorm.
 * User: cwioro
 * Date: 18.06.15
 * Time: 13:27
 */

namespace bitcodin;

/**
 * Class Input
 * @package bitcodin
 */
class Input extends ApiResource
{

    const URL_CREATE = '/input/create';
    const URL_ANALYZE = '/input/{id}/analyze';
    const URL_GET = '/input/{id}';
    const URL_DELETE = '/input/{id}';
    const URL_GET_LIST = '/inputs/{page}';

    /**
     * @param \stdClass $class
     */
    public function __construct(\stdClass $class)
    {
        parent::__construct($class);
    }

    public function update()
    {
        self::_copy(self::get($this));
    }

    public function analyze()
    {
        self::_copy(self::analyzeInput($this));
    }

    public function delete()
    {
        return self::deleteInput($this);
    }

    /**
     * @param $id
     * @return static
     */
    private static function analyzeInput($id)
    {
        if ($id instanceof \stdClass)
            $id = $id->inputId;

        $response = self::_patchRequest(str_replace('{id}', $id, self::URL_ANALYZE), 200);

        return new static(json_decode($response->getBody()->getContents()));
    }

    /**
     * @param $id
     * @return static
     */
    public static function get($id)
    {
        if ($id instanceof \stdClass)
            $id = $id->inputId;

        $response = self::_getRequest(str_replace('{id}', $id, self::URL_GET), 200);

        return new static(json_decode($response->getBody()->getContents()));
    }

    /**
     * @param int $page
     * @return mixed
     */
    public static function getList($page = 1)
    {
        $response = self::_getRequest(str_replace('{page}', $page, self::URL_GET_LIST), 200);

        $responseDecoded = json_decode($response->getBody()->getContents());

        $count = 0;
        foreach ($responseDecoded->inputs as $input)
            $responseDecoded->inputs[$count++] = new self($input);

        return $responseDecoded;
    }

    /**
     * @param $id
     * @return mixed
     */
    private static function deleteInput($id)
    {
        if ($id instanceof \stdClass)
            $id = $id->inputId;

        $response = self::_deleteRequest(str_replace('{id}', $id, self::URL_DELETE), 204);

        return json_decode($response->getBody()->getContents());
    }
}