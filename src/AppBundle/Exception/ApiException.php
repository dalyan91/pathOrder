<?php

namespace AppBundle\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

class ApiException extends HttpException
{
    /**
     * Api exception extra data
     *
     * @var mixed
     */
    protected $extras;

    /**
     * ApiException constructor.
     *
     * @param string $message Api error message
     * @param int $code Response status code
     * @param null $extras Exception extra data
     * @param \Exception|null $previous
     */
    public function __construct($message, $code = 200, $extras = null, \Exception $previous = null)
    {
        parent::__construct($code, $message, null, [], $code);

        $this->extras = $extras;
    }

    /**
     * Set extra data
     *
     * @param mixed $extras
     */
    public function setExtras($extras)
    {
        $this->extras = $extras;
    }

    /**
     * Get Extra data
     *
     * @return array
     */
    public function getExtras()
    {
        return $this->extras;
    }


}