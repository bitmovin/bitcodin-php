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
     * @var int
     */
    public $inputId;

    /**
     * @var string
     */
    public $filename;

    /**
     * @var string
     */
    public $thumbnailUrl;

    /**
     * @var string
     */
    public $inputType;

    /**
     * @var array
     */
    public $mediaConfigurations;


    /**
     * @param AbstractInputConfig $inputConfig
     * @return Input
     */
    public static function create(AbstractInputConfig $inputConfig)
    {
        $inputConfig->url = str_replace('?dl=0', '?dl=1', $inputConfig->url);

        $response = self::_postRequest(self::URL_CREATE, json_encode($inputConfig), 201);
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

    public function analyze()
    {
        self::_copy(self::analyzeInput($this));
    }

    public function delete()
    {
        return self::deleteInput($this);
    }

    public function getVideoMediaConfigurations()
    {
        $videoMediaConfigurations = [];
        foreach($this->mediaConfigurations as $mediaConfig)
        {
            if($mediaConfig->type == 'video')
                $videoMediaConfigurations[] = $mediaConfig;
        }
        return $videoMediaConfigurations;
    }

    /**
     * @param $id
     * @return Input
     */
    private static function analyzeInput($id)
    {
        if ($id instanceof \stdClass)
            $id = $id->inputId;

        $response = self::_patchRequest(str_replace('{id}', $id, self::URL_ANALYZE), null, 200);

        return new self(json_decode($response->getBody()->getContents()));
    }

    /**
     * @param $id
     * @return Input
     */
    public static function get($id)
    {
        if ($id instanceof \stdClass)
            $id = $id->inputId;

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
        foreach ($responseDecoded->inputs as $input)
            $responseDecoded->inputs[$count++] = new self($input);

        return $responseDecoded;
    }

    /**
     * @return Input[]|array
     */
    public static function getListAll()
    {
        $inputsTotal = 1;
        $inputs = [];
        for ($page = 1; sizeof($inputs) < $inputsTotal; $page++) {
            $inputResponse = Input::getList($page);
            $inputList = $inputResponse->inputs;
            $inputsTotal = $inputResponse->totalCount;

            foreach ($inputList as $input) {
                $inputs[] = $input;
            }

        }

        return $inputs;
    }

    /**
     * @param $id
     * @return mixed
     */
    private static function deleteInput($id)
    {
        if ($id instanceof Input)
            $id = $id->inputId;

        $response = self::_deleteRequest(str_replace('{id}', $id, self::URL_DELETE), 204);

        return json_decode($response->getBody()->getContents());
    }

    public static function deleteAll()
    {
        foreach (self::getListAll() as $input) {
            //  var_dump($input->inputId);
            $input->delete();
        }
    }


}