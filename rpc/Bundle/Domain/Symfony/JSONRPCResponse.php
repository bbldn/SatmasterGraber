<?php

namespace BBLDN\JSONRPCBundle\Bundle\Domain\Symfony;

use Symfony\Component\HttpFoundation\Response;
use BBLDN\JSONRPCBundle\Bundle\Domain\DTO\JSONRPCResponse as JSONRPCResponseDTO;

class JSONRPCResponse extends Response
{
    /** @var int */
    public const DEFAULT_ENCODING_OPTIONS = 15;

    protected int $encodingOptions = self::DEFAULT_ENCODING_OPTIONS;

    /**
     * @param JSONRPCResponseDTO[]|JSONRPCResponseDTO|null $response
     * @param int $status
     * @param array $headers
     */
    public function __construct($response = null, int $status = self::HTTP_OK, array $headers = [])
    {
        parent::__construct('', $status, $headers);
        $this->setResponse($response);
    }

    /**
     * @param JSONRPCResponseDTO[]|JSONRPCResponseDTO|null $response
     * @return JSONRPCResponse
     */
    private function setResponse($response = null): self
    {
        if (null === $response) {
            return $this->setJson('');
        }

        if (true === is_array($response)) {
            $result = [];
            foreach ($response as $item) {
                $result[] = $item->toArray();
            }

            return $this->setJson(json_encode($result));
        }

        return $this->setJson(json_encode($response->toArray()));
    }

    /**
     * @param string $json
     * @return JSONRPCResponse
     */
    private function setJson(string $json): self
    {
        $key = 'Content-Type';
        if (false === $this->headers->has($key)) {
            $this->headers->set($key, 'application/json');
        }

        return $this->setContent($json);
    }
}