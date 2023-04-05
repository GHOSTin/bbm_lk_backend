<?php

namespace App\Helper\Exception;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ApiException extends HttpException
{
    /**
     * @var string|null
     * @Groups({"show"})
    */
    protected $message;

    /**
     * @var string|null
     * @Groups({"show"})
     */
    protected $detail;

    /**
     * @var int
     * @Groups({"show"})
     */
    protected $status;

    public function __construct($message, $detail, $status=Response::HTTP_BAD_REQUEST, HttpException $previous = null) {
        $this->message = $message;
        $this->detail = $detail;
        $this->status = $status;
        parent::__construct($status, $message, $previous);
    }

    public function responseBody() {
        return [
            'error' => [
                'status' => $this->getStatus(),
                'message' => $this->getMessage(),
                'detail' => $this->getDetail(),
            ]
        ];
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message): void
    {
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
    public function setStatus($status): void
    {
        $this->status = $status;
    }

    /**
     * @return null
     */
    public function getDetail()
    {
        return $this->detail;
    }

    /**
     * @param null $detail
     */
    public function setDetail($detail): void
    {
        $this->detail = $detail;
    }

}