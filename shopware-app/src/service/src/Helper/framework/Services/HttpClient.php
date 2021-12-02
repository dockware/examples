<?php


class HttpClient
{
    private $apiKey;

    private string $baseUrl;

    private ?string $token = null;

    private ?DateTime $expires = null;
    private string $apiSecret;


    /**
     * @param string $baseUrl
     * @param string $apiKey
     * @throws Exception
     */
    public function __construct(string $baseUrl, string $apiKey, string $apiSecret)
    {
        $this->baseUrl = $baseUrl;
        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;

        $this->auth();
    }

    public function post(string $path, array $body, array $header = [])
    {
        return $this->performRequest($path, 'post', $body, $header);
    }

    public function sync(array $body)
    {
        $path = '_action/sync';

        return $this->post($path, $body, ['fail-on-error' => false, 'disable-indexing' => true]);
    }

    public function getId(string $entity, array $body): ?string
    {
        $data = $this->post('search-ids/' . $entity, $body);

        if (count($data[1]['data']) > 1) {
            throw new Exception('found more then one id');
        }
        if (array_key_exists(0, $data[1]['data'])) {
            return $data[1]['data'][0];
        }

        return null;
    }

    public function patch(string $path, array $body)
    {
        return $this->performRequest($path, 'patch', $body);
    }

    public function get(string $path)
    {
        return $this->performRequest($path, 'get');
    }

    private function performRequest(string $path, string $method, ?array $body = null, array $header = [])
    {
        $now = new DateTime('now');
        try {
            if (strpos($path, 'oauth/token') === false) {
                if ($this->expires !== null && $now > $this->expires) {
                    $this->auth();
                }
            }

            if ($method !== 'post' && $method !== 'get' && $method !== 'patch') {
                throw new Exception('Method ' . $method . ' not supported');
            }

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->baseUrl . '/' . $path);
            curl_setopt($ch, CURLOPT_HEADER, false);
#curl_setopt($ch, CURLOPT_NOBODY, true); // remove body
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            if ($method === 'patch') {
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
            }
            if ($method === 'post') {
                curl_setopt($ch, CURLOPT_POST, true);
            }

            if ($method === 'post' || $method === 'patch') {
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
            }

            $authorization = "Authorization: Bearer " . $this->token;
            $_header[] = 'Content-Type: application/json';
            $_header[] = $authorization;
            $header = array_merge($_header, $header);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

            $response = curl_exec($ch);
            if ($response === false) {
                throw new Exception('Got bad Response from request to ' . $path . ' payload:' . json_encode($body));
            }
            $response = json_decode($response, true);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            return [$httpCode, $response];
        } catch (Exception $e) {
            $a = 1;
            throw  $e;
        }
    }

    public function auth()
    {
        list($httpCode, $response) = $this->post(
            'oauth/token',
            [
                'client_id' => $this->apiKey,
                'grant_type' => 'client_credentials',
                'client_secret' => $this->apiSecret,
            ],
        );
        if ($httpCode !== 200) {
            throw new Exception('Could not login.');
        }
        $this->token = $response['access_token'];
        $expires = new DateTime('now');
        $expires->add(new DateInterval('PT550S'));
        $this->expires = $expires;
    }

    public function buildFilter(string $type, string $field, $value)
    {
        return [
            'type' => $type,
            'field' => $field,
            'value' => $value,
        ];
    }

}

