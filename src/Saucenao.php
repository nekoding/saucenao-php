<?php

declare(strict_types=1);

namespace Nekoding\Saucenao;

use Exception;
use Nekoding\Saucenao\Exceptions\FileSizeException;
use Nekoding\Saucenao\Exceptions\InvalidKeyException;
use Nekoding\Saucenao\Exceptions\LimitReachedException;

class Saucenao
{

    const SAUCENAO_URL = 'https://saucenao.com/search.php';

    protected static $saucenaoInstance;

    protected $params = [];

    public function __construct(
        string $apiKey,
        int $testMode = 0,
        int $db = DB::ALL,
        int $numres = 6,
        int $hide = Hide::NONE,
        int $dedupe = Dedupe::ALL,
        int $dbMask = null,
        int $dbMaski = null,
    ) {
        $this->params = [
            'api_key'       => $apiKey,
            'testmode'      => $testMode,
            'dbmask'        => $dbMask,
            'dbmaski'       => $dbMaski,
            'db'            => $db,
            'numres'        => $numres,
            'dedupe'        => $dedupe,
            'hide'          => $hide,
            'output_type'   => OutputType::JSON
        ];
    }

    public static function getOrCreateInstance(
        string $apiKey,
        int $testMode = 0,
        int $db = DB::ALL,
        int $numres = 6,
        int $hide = Hide::NONE,
        int $dedupe = Dedupe::ALL,
        int $dbMask = null,
        int $dbMaski = null,
    ): self {
        if (!self::$saucenaoInstance) {
            self::$saucenaoInstance = new self(
                $apiKey,
                $testMode,
                $db,
                $numres,
                $hide,
                $dedupe,
                $dbMask,
                $dbMaski
            );
        }

        return self::$saucenaoInstance;
    }

    public function fromUrl(string $url): array
    {
        $this->params['url'] = $url;
        return $this->search($this->params);
    }

    protected function search($params): array
    {
        $ch = curl_init();
        $endpoint = self::SAUCENAO_URL;
        $params = http_build_query($this->params);
        $url = $endpoint . "?" . $params;

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        if ($httpcode === 200) {
            $response = $this->verifyResponse($response);
            return $response;
        }

        if ($httpcode === 403) {
            throw new InvalidKeyException("Invalid API key");
        }

        if ($httpcode === 413) {
            throw new FileSizeException("File is too large");
        }

        if ($httpcode === 429) {
            throw new LimitReachedException("Too many request API call limit reached");
        }

        throw new Exception(sprintf("Server returned status code %d", $httpcode));
    }

    protected function verifyResponse($response): array
    {
        $response = json_decode($response, true);

        $response_header = $response['header'];

        $status = $response_header['status'];
        $userid = (int) $response_header['user_id'];

        if ($status < 0) {
            throw new Exception("Unknown client error, status < 0");
        }

        if ($status > 0) {
            throw new Exception("Unknown API error, status > 0");
        }

        if ($userid < 0) {
            throw new Exception("Unknown API error, user_id < 0");
        }

        return $response;
    }
}
