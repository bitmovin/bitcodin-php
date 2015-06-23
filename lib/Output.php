<?php
/**
 * Created by PhpStorm.
 * User: cwioro
 * Date: 18.06.15
 * Time: 13:27
 */

namespace bitcodin;


/**
 * Class Output
 * @package bitcodin
 */
class Output extends ApiResource
{
    const URL_CREATE = '/output/create';
    const URL_ANALYZE = '/output/{id}/analyze';
    const URL_GET = '/output/{id}';
    const URL_DELETE = '/output/{id}';
    const URL_GET_LIST = '/outputs/{page}';

    /**
     * @var int
     */
    public $outputId;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $prefix;

    /**
     * @var string
     */
    public $host;

    /**
     * @var string
     */
    public $type;

    /**
     * @var boolean
     */
    public $makePublic;


    /**
     * @param AbstractOutputConfig $outputconfig
     * @return Output
     */
    public static function create(AbstractOutputConfig $outputconfig)
    {
        $response = self::_postRequest(self::URL_CREATE, json_encode($outputconfig), 201);
        return new self(json_decode($response->getBody()->getContents()));
    }


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

    public function delete()
    {
        return self::deleteInput($this);
    }

    /**
     * @param $id
     * @return Input
     */
    public static function get($id)
    {
        if ($id instanceof \stdClass)
            $id = $id->outputId;

        $response = self::_getRequest(str_replace('{id}', $id, self::URL_GET), 200);

        return new self(json_decode($response->getBody()->getContents()));
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
        foreach ($responseDecoded->outputs as $output)
            $responseDecoded->outputs[$count++] = new self($output);

        return $responseDecoded;
    }

    /**
     * @return array
     */
    public static function getListAll()
    {
        $outputsTotal = 1;
        $outputs = [];
        for ($page = 1; sizeof($outputs) < $outputsTotal; $page++) {
            $outputResponse = Output::getList($page);
            $outputList = $outputResponse->outputs;
            $outputsTotal = $outputResponse->totalCount;

            foreach ($outputList as $output) {
                $outputs[] = $output;
            }

        }

        return $outputs;
    }

    /**
     * @param $id
     * @return mixed
     */
    private static function deleteInput($id)
    {
        if ($id instanceof Output)
            $id = $id->outputId;

        $response = self::_deleteRequest(str_replace('{id}', $id, self::URL_DELETE), 204);

        return json_decode($response->getBody()->getContents());
    }

    public static function deleteAll()
    {
        foreach (self::getListAll() as $output) {
            //  var_dump($output->outputId);
            $output->delete();
        }
    }
}