<?php

class Response
{

    /**
     * @param array $data
     */
    public static function success(array $data)
    {
        die(json_encode($data));
    }

    /**
     * @param string $message
     */
    public static function error(string $message)
    {
        $data = [
            'error' => $message,
        ];

        http_response_code(500);

        die(json_encode($data));
    }

}
