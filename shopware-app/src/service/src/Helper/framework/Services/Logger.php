<?php

class Logger
{
    const LOGS_FOLDER = __DIR__ . '/../../../../_logs';

    /**
     * STATIC is bad, but it's ok for this sample ;)
     *
     * @param string $type
     */
    public static function logRequest(string $type)
    {
        $data = [
            'GET' => $_GET,
            'POST' => $_POST,
            'headers' => $_SERVER,
            'body' => file_get_contents('php://input'),
        ];

        if (!file_exists(self::LOGS_FOLDER)) {
            mkdir(self::LOGS_FOLDER);
        }

        file_put_contents(self::LOGS_FOLDER . '/request_' . $type . '.json', json_encode($data));
    }

    /**
     * @param string $type
     * @param array $data
     */
    public static function logData(string $type, array $data)
    {
        if (!file_exists(self::LOGS_FOLDER)) {
            mkdir(self::LOGS_FOLDER);
        }

        file_put_contents(self::LOGS_FOLDER . '/data_' . $type . '.json', json_encode($data));
    }

    /**
     * @param string $type
     * @param string $message
     */
    public static function logError(string $type, string $message)
    {
        if (!file_exists(self::LOGS_FOLDER)) {
            mkdir(self::LOGS_FOLDER);
        }

        file_put_contents(self::LOGS_FOLDER . '/error_' . $type . '.json', $message);
    }

}
