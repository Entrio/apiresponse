<?php

namespace Entrio\APIResponse;

/**
 *  API Response
 *
 *  This class simplifies working with JSON APIs.
 *
 * @author Alexander Titarenko <alexander.titarenko@resmi.kz>
 */
class APIResponse
{
    /**
     * Payload data of the response (if any)
     *
     * @var object
     */
    private $payload = null;

    /**
     * Is this response positive or negative?
     *
     * @var boolean
     */
    private $successful = false;

    /**
     * If the response was not successful, this is the reason why
     *
     * @var string
     */
    private $errorReason = 'Not initialized';

    private $validated = false;

    /**
     * Set the payload data. This automatically marks response as successful.
     *
     * @param $object
     */
    public function SetPayload($object)
    {
        $this->errorReason = null;
        $this->successful  = true;
        $this->payload     = $object;
    }

    /**
     * Specifies if the response was successful or not
     *
     * @return boolean
     */
    public function IsSuccessful(): bool
    {
        $this->validated = true;

        return $this->successful;
    }

    /**
     * Get the payload data. If the payload data is null, an exception is thrown.
     *
     * @return object
     * @throws \Exception
     */
    public function GetPayload()
    {
        if (!$this->validated)
            throw new \Exception('Validate the result using IsSuccessful prior to calling GetPayload or GetPayloadObject');

        if ($this->payload != null) {
            return $this->payload;
        }
        throw new \Exception('Payload is null!');
    }

    /**
     * @return \stdClass
     * @throws \Exception
     */
    public function GetPayloadObject(): \stdClass
    {
        if (!$this->validated)
            throw new \Exception('Validate the result using IsSuccessful prior to calling GetPayload or GetPayloadObject');

        if ($this->payload != null) {
            try {
                return json_decode(json_encode($this->payload), false, 512, JSON_THROW_ON_ERROR);
            } catch (\Exception $exception) {
                throw $exception;
            }
        }
        throw new \Exception('Payload is null!');
    }

    /**
     * Set the reason why the response was not successful
     *
     * @param string $reason
     */
    public function SetErrorReason(string $reason)
    {
        $this->payload     = null;
        $this->successful  = false;
        $this->validated   = false;
        $this->errorReason = $reason;
    }

    /**
     * Get the reason why the response was not successful. throws an exception if response is successful.
     *
     * @return string
     * @throws \Exception
     */
    public function GetErrorReason(): string
    {
        if ($this->successful == true) {
            throw new \Exception('You should only call GetErrorReason if IsSuccessful returned false.');
        }

        return $this->errorReason;
    }
}