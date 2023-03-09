<?php

namespace App\Traits;

trait GeneralTrait
{

    public function getCurrentLang()
    {
        return app()->getLocale();
    }

    public function returnError( $msg ,$code = 422)
    {
        return response()->json([
            'status' => false,
            'msg' => is_array($msg) ? implode(', ', $msg) : $msg
        ],$code);
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

    public function returnData ( $data, $msg =null ,$code = 200 )
    {
        $response = [
            'status' => $code >= 200 && $code < 300,
            'code' => $code,
            'data' => $data,
            'msg' => $msg ??  trans('api.The_action_ran_successfully'),
        ];

        return response()->json($response, $code);

    }


    //////////////////
    public function returnValidationError($code = "E001", $validator)
    {
        return $this->returnError( $validator);
    }


    public function returnCodeAccordingToInput($validator)
    {
        $inputs = array_keys($validator->errors()->toArray());
        $code = $this->getErrorCode($inputs[0]);
        return $code;
    }


}
