<?php

namespace App\Http\Traits;

trait GeneralTrait
{

    public function apiResponse($data=null, bool $status = true, $error=null, $statusCode = 200)
    {
        $array=[
            'data' =>$data,
            'status' => $status ,
            'error' => $error,
            'statusCode' => $statusCode
        ];
        return response($array,$statusCode);

    }

    public function unAuthorizeResponse($message)
    {
        return $this->apiResponse(null,0,$message, 401);
    }

    public function Forbidden($message)
    {
        return $this->apiResponse(null,0,$message, 403);
    }

    public function notFoundResponse($more)
    {
        return $this->apiResponse(null, 0, $more, 404);
    }

    public function requiredField($message)
    {
        // return $this->apiResponse(null, false, $message, 200);
        return $this->apiResponse(null, false, $message, 422);
    }
 




}
