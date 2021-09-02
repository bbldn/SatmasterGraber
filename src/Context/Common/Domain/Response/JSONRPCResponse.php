<?php

namespace App\Context\Common\Domain\Response;

use Throwable;
use Symfony\Component\HttpFoundation\Response;

class JSONRPCResponse extends Response
{
    /** @var int */
    public const DEFAULT_ENCODING_OPTIONS = 15;

    protected int $encodingOptions = self::DEFAULT_ENCODING_OPTIONS;

    /**
     * @param mixed $result
     * @param mixed $error
     * @param mixed $id
     * @param int $status
     * @param array $headers
     *
     * @noinspection PhpDocMissingThrowsInspection
     */
    public function __construct(
        $result = null,
        $error = null,
        $id = null,
        int $status = 200,
        array $headers = []
    )
    {
        parent::__construct('', $status, $headers);

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->setData($result, $error, $id);
    }

    /**
     * @param mixed $result
     * @param mixed $error
     * @param mixed $id
     * @return $this
     * @throws Throwable
     */
    public function setData($result = null, $error = null, $id = null): self
    {
        $data = [
            'jsonrpc' => '2.0',
            'result' => $result,
            'error' => $error,
            'id' => $id,
        ];

        try {
            $json = json_encode($data, $this->encodingOptions);
        } catch (\Exception $e) {
            if ('Exception' === \get_class($e) && true === str_starts_with($e->getMessage(), 'Failed calling ')) {
                throw $e->getPrevious() ?: $e;
            }

            throw $e;
        }

        if (\PHP_VERSION_ID >= 70300 && (\JSON_THROW_ON_ERROR & $this->encodingOptions)) {
            return $this->setJson($json);
        }

        if (\JSON_ERROR_NONE !== json_last_error()) {
            throw new \InvalidArgumentException(json_last_error_msg());
        }

        return $this->setJson($json);
    }

    /**
     * @param string $json
     * @return void
     */
    private function setJson(string $json): self
    {
        if (false === $this->headers->has('Content-Type')) {
            $this->headers->set('Content-Type', 'application/json');
        }

        return $this->setContent($json);
    }
}