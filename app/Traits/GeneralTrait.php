<?php

namespace App\Traits;

trait GeneralTrait
{

    public function getCurrentLang()
    {
        return app()->getLocale();
    }

    public function returnError($errNum, $msg)
    {
        return response()->json([
            'status' => false,
            'msg' => implode(', ', $msg)
        ],422);
    }

    public function unauthorized()
    {
        return response()->json([
            'status' => false,
            'msg' => trans('auth.unauthorized')
        ], 401);
    }

    public function returnSuccessMessage ( $msg = "", $code = 200 )
    {
        return [
            'status' => $code >= 200 && $code < 300,
            'code' => $code,
            'msg' => $msg
        ];
    }

    public function returnData ( $data, $code = 200, $message = null )
    {
        $response = [
            'status' => $code >= 200 && $code < 300,
            'code' => $code,
            'data' => $data,
            'message' => $message,
        ];

        return response()->json($response, $code);

    }


    //////////////////
    public function returnValidationError($code = "E001", $validator)
    {
        return $this->returnError($code, $validator);
    }


    public function returnCodeAccordingToInput($validator)
    {
        $inputs = array_keys($validator->errors()->toArray());
        $code = $this->getErrorCode($inputs[0]);
        return $code;
    }


}
