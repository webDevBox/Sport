<?php


namespace App;


use Config;

class ApiResponse
{
    public static function success($data=null, $key=null)
    {
        $responseConstants = Config::get('constants.RESPONSE_CONSTANTS');

        return [
            'status'        => $responseConstants['STATUS_SUCCESS'],
            'message'       => $responseConstants[$key]??'Successfully Get Records',
            'response_code' => $responseConstants['RESPONSE_CODE_SUCCESS'],
            'data'          => $data
        ];
    }

    public static function successWithToken($data=null, $key=null, $token=null)
    {
        $responseConstants = Config::get('constants.RESPONSE_CONSTANTS');

        return [
            'status'        => $responseConstants['STATUS_SUCCESS'],
            'message'       => $responseConstants[$key],
            'response_code' => $responseConstants['RESPONSE_CODE_SUCCESS'],
            'token'         => $token,
            'data'          => $data,
        ];
    }

    public static function validation($message=null, $responseCodeKey='INVALID_PARAMETERS_CODE')
    {
        $responseConstants = Config::get('constants.RESPONSE_CONSTANTS');

        return [
            'status'        => $responseConstants['STATUS_ERROR'],
            'message'       => $message,
            'response_code' => $responseConstants[$responseCodeKey],
        ];
    }

    public static function error($key=null, $responseCodeKey='INVALID_PARAMETERS_CODE')
    {
        $responseConstants = Config::get('constants.RESPONSE_CONSTANTS');

        return [
            'status'        => $responseConstants['STATUS_ERROR'],
            'message'       => $responseConstants[$key],
            'response_code' => $responseConstants[$responseCodeKey],
        ];
    }

    public static function notFound($key=null)
    {
        $responseConstants = Config::get('constants.RESPONSE_CONSTANTS');

        return [
            'status'        => $responseConstants['STATUS_ERROR'],
            'message'       => $responseConstants[$key],
            'type'          => 'resource_not_found',
            'response_code' => $responseConstants['RESPONSE_CODE_NOT_FOUND'],
        ];
    }

    public static function update($key=null)
    {
        $responseConstants = Config::get('constants.RESPONSE_CONSTANTS');

        return [
            'status'        => $responseConstants['STATUS_SUCCESS'],
            'message'       => $responseConstants[$key]??'Successfully Updated Record',
            'response_code' => $responseConstants['RESPONSE_CODE_SUCCESS'],
        ];
    }
}
