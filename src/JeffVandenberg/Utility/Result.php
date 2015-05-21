<?php
/**
 * Created by PhpStorm.
 * User: JeffVandenberg
 * Date: 5/21/2015
 * Time: 4:25 AM
 */

namespace JeffVandenberg\Utility;


/**
 * Class Result
 * @package JeffVandenberg\Utility
 */
class Result
{
    /**
     * @var
     */
    private $status;
    /**
     * @var
     */
    private $message;

    /**
     * @param $status
     * @param $message
     */
    function __construct($status, $message)
    {
        $this->status = $status;
        $this->message = $message;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }
}