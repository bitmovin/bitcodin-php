<?php
/**
 * Created by PhpStorm.
 * User: cwioro
 * Date: 18.06.15
 * Time: 13:27
 */

namespace bitcodin;


/**
 * Class Transfer
 * @package bitcodin
 */
class Transfer extends ApiResource
{

    /**
     * @var string
     */
    public $id;

    /**
     * @var integer
     */
    public $jobId;

    /**
     * @var integer
     */
    public $progress;


    public function __construct($apiResonse)
    {
        parent::__construct($apiResonse);
    }
}