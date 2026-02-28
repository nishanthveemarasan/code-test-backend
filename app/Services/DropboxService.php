<?php
namespace App\Services;
use App\Models\DropboxCredential;
use GuzzleHttp\Client;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\Filesystem;
use Spatie\Dropbox\Client as DropboxClient;
use Spatie\FlysystemDropbox\DropboxAdapter;
use Illuminate\Support\Facades\Http;

class DropboxService
{
    protected $httpClient;
    

    public function __construct()
    {
        $this->httpClient = new Client(['verify' => false]);
    }

    public function connectDropbox($event){

        $dropboxAccessToken = $this->getActiveToken($event);
        
        Storage::extend('dropbox', function ($app, $config) use($dropboxAccessToken) {
            $adapter = new DropboxAdapter(new DropboxClient(
                $dropboxAccessToken,
            ));
                return new FilesystemAdapter(
                    new Filesystem($adapter, $config),
                    $adapter,
                    $config

                );
        });
    }

    public function authorizeDropboxApp($event){

        $dropboxCredential = DropboxCredential::where('code', $event)->first();
        
        $authorization_url = 'https://www.dropbox.com/oauth2/authorize?client_id=' . $dropboxCredential->client_id
                                                . '&token_access_type=offline'
                                                . '&response_type=code'
                                                . '&redirect_uri=' . $dropboxCredential->app_redirect_url;
        return redirect($authorization_url);
        
    }

    public function dropboxAuthorizeCode($data,$event){

        $authorizationCode = $data['code'];
        $dropboxCredential = DropboxCredential::where('code', $event)->first();
        
        $requestBody = array(
            'code' => $authorizationCode,
            'grant_type' => 'authorization_code',
            'redirect_uri' => $dropboxCredential->app_redirect_url
        );
        $response = $this->httpPostRequest($requestBody,$dropboxCredential);
        
        $dropboxCredential->refresh_token = $response['refresh_token'];
        $dropboxCredential->access_token = $response['access_token'];
        $dropboxCredential->save();

        return 'Access token has been generated successfully, user will be able to store files without any interrupt';
    }

    private function getActiveToken($event){

        $dropboxCredential = DropboxCredential::where('code', $event)->first();
        $currentActiveToken = $dropboxCredential->access_token;
        $isCurrentTokenActive = $this->validateDropboxToken($currentActiveToken);
        
        $token = $isCurrentTokenActive ? $currentActiveToken : $this->regenerateToken($dropboxCredential);
        
        return $token;
    }

    public function validateDropboxToken($token){

        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer '.$token,
        ];
        $data = ['query' => 'test'];

        $res = Http::withHeaders($headers)->post("https://api.dropboxapi.com/2/check/user",$data);
        $response = json_decode($res->body(), TRUE);
        
        if(isset($response['error'])){
            return false;
        }

        if(isset($response['result']) && $response['result'] == 'test'){
            return true;
        }
    }

    private function regenerateToken(DropboxCredential $dropboxCredential){

        $requestBody = array(
            'grant_type' => 'refresh_token',
            'refresh_token' => $dropboxCredential->refresh_token,
        );
        
        $response = $this->httpPostRequest($requestBody,$dropboxCredential);

        $dropboxCredential->access_token = $response['access_token'];
        $dropboxCredential->save();

        return $response['access_token'];
    }

    private function httpPostRequest($body,DropboxCredential $dropboxCredential){

        $apiUrl = "https://{$dropboxCredential->client_id}:{$dropboxCredential->client_secret}@api.dropbox.com/oauth2/token";
        $httpRequest = $this->httpClient->request("POST", $apiUrl, [
            'form_params' => $body
        ]);

        $response = json_decode($httpRequest->getBody(), TRUE);
        return $response;
    }

}