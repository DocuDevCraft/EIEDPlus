<?php

namespace Modules\SignatureSystem\Http\Controllers;

use Illuminate\Routing\Controller;

class SignatureSystemController extends Controller
{
    public static function signature($type, $data)
    {
        function emza($data_pure, $action)
        {

            $data = mb_convert_encoding(json_encode($data_pure), 'UTF-16LE');

            /* EIED */
            $privatekey = file_get_contents(storage_path('app/keys/private_key.pem'));


            $signature = '';

            openssl_sign($data, $signature, $privatekey, OPENSSL_ALGO_SHA1);

            $dataAPP = array(
                "appId" => 5,
                "action" => $action,
                "signature" => base64_encode($signature),
                "data" => $data_pure
            );

            $headers = array(
                'Content-Type: application/json'
            );
            $url = "https://api.pki.co.ir/softsign/query";
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($dataAPP));
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            $response = curl_exec($curl);
            $jsonObject = json_decode($response);


            return json_decode($response, true);
        }

        if ($type === 'signRequestAction') {
            return emza($data, 1000);
        }

        if ($type === 'signProcess') {
            return emza($data, 1001);
        }

        if ($type === 'getUserCertificateAction') {
            return emza($data, 1002);
        }

        if ($type === 'signature_request') {
            return emza($data, 1005);
        }

        if ($type === 'signature_auth') {
            return emza($data, 1006);
        }
    }

    public static function dss($type, $data)
    {
        if ($type === 'digest') {
            return self::requestDss($data, 'https://dss.eied.com/api/CryptoService/PDFDigestForMultiSign', 'POST');
        }

        if ($type === 'signPDF') {
            return self::requestDss($data, 'https://dss.eied.com/api/CryptoService/PutPDFSignatureForMultiSign', 'POST');
        }

        return null;
    }

    private static function requestDss($data, $url, $method = 'POST')
    {
        $headers = [
            'Content-Type: application/json'
        ];

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($curl);
        $error = curl_error($curl);

        curl_close($curl);

        if ($error) {
            return [
                'statusCode' => 500,
                'error' => $error
            ];
        }

        return json_decode($response, true);
    }
}
