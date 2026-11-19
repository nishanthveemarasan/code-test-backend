<?php
namespace App\Traits;

use Illuminate\Support\Facades\Cache;

trait SignatureTrait
{
       
    /**
     * Method getSignatureAdata
     *
     * @param string $method [explicite description]
     * @param string $path [explicite description]
     * @param string $randomString [explicite description]
     *
     * @return string
     */
    public function getSignatureAdata(string $method, string $path, string $randomString): string
    {
        $secret = config('admin.keys.service');
        $dataToHash = "{$method}{$path}{$randomString}123asd";
        // dd($dataToHash);
        $computedSignature = hash_hmac('sha256', $dataToHash, $secret);
        return $computedSignature;
    }
}