<?php

class Request
{

    /**
     * @return false|string
     */
    public static function getBody()
    {
        return file_get_contents('php://input');
    }

}
