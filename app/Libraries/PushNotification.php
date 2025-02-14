<?php

namespace App\Libraries;

use App\Models\Notification;
use Google\Client;
use Google\Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PushNotification
{

    public static function Pusher($title, $body, $token, $auth_id, $data = null, $insertInTable = true, $topic = null, $image = null, $collapseKey = null ,$platform = null, $registration_ids = null)
    {

        $client = new Client();
        try {
            $envPostfix = '';
            // if(config('app.env')!=''){
            //     $envPostfix = '_STAGGING';
            // }

            $client->setAuthConfig(base_path() . config('app.GOOGLE_APPLICATION_CREDENTIALS'.$envPostfix));
            $client->addScope(\Google\Service\FirebaseCloudMessaging::CLOUD_PLATFORM);

            // retrieve the saved oauth token if it exists, you can save it on your database or in a secure place on your server
            $savedTokenJson = self::readToken();
            if ($savedTokenJson != null) {
                // the token exists, set it to the client and check if it's still valid
                $client->setAccessToken($savedTokenJson);
                $accessToken = $savedTokenJson;
                if ($client->isAccessTokenExpired()) {
                    // the token is expired, generate a new token and set it to the client
                    $accessToken = self::generateToken($client);
                    $client->setAccessToken($accessToken);
                }
            } else {
                // the token doesn't exist, generate a new token and set it to the client
                $accessToken = self::generateToken($client);
                $client->setAccessToken($accessToken);
            }

            $oauthToken = $accessToken["access_token"];
            // dd($oauthToken);

            // the client is configured, now you can send the push notification using the $oauthToken.
            $headers = [
                'Authorization' => 'Bearer ' . $oauthToken,
                'Content-Type' => 'application/json'
            ];

            $fields = array(
                // for testing make true, not send to real device
                'validate_only' => false,
                'message' => array(
                    'notification' => [
                        'title' => $title,
                        'body' => $body,
                        'image' => $image,
                    ],
                    'data' => $data
                )
            );

            if ($token)
                $fields['message']['token'] = $token;
            // elseif($registration_ids)
            // {
            //     // $fields['message']['registration_token'] = $registration_ids;
            //     // dd($registration_ids);
            //     // $fields[]['data']['registration_ids'] = $registration_ids;
            // }
            elseif ($topic)
                $fields['message']['topic'] = $topic;

            //  dd($fields);
            if($image) {
                $fields['message']['notification']['image'] = $image;
                $fields['message']['apns'] = array(
                    "payload" => [
                        "aps" => [
                            "mutable-content" => 1
                        ]
                    ],
                    "fcm_options" => [
                        "image" => $image
                    ]
                );
            }

            // if($collapseKey)
            //      $fields['message']['collapse_key'] = $collapseKey;

            $response = Http::withHeaders($headers)
//            ->withoutVerifying() // for testing from localhost
                ->post('https://fcm.googleapis.com/v1/projects/' . config('app.FIREBASE_PROJECT_ID'.$envPostfix) . '/messages:send', $fields);

            // dd($response->successful());
            if ($response->successful()) {
                if ($insertInTable) {
                    $not = new Notification();
                    $not->title = $title;
                    $not->body = $body;
                    $not->user_id = $auth_id;
                    $not->notification_to = $auth_id?'SPECIFIC_USER':'ALL_USERS';
                    $not->event = isset($data['event']) ? $data['event'] : null;
                    $not->event_id = isset($data['event_id']) ? $data['event_id'] : null;
                    $not->save();
                }
            } else{
                $e = $response->body();
                if($e){
                    $e = json_decode($e,true);
                    if($e && isset($e['error']) && isset($e['error']['code']) && $e['error']['code'] == 404){
                        return $response->successful();
                    }
                }

                Log::warning('Error in push notification: ' . json_encode($e));
            }
            return $response->successful();
        } catch (Exception $e) {
            Log::warning('Error in google client (Push): ' . json_encode($e));
            // handle exception
            return false;
        }
    }

    private static function generateToken($client)
    {
        $client->fetchAccessTokenWithAssertion();
        $accessToken = $client->getAccessToken();

        // save the oauth token json on your database or in a secure place on your server
        $tokenJson = $accessToken;
        self::saveToken($tokenJson);

        return $accessToken;
    }

    public static function readToken()
    {
        return Cache::get('OAUTHTOKEN');
    }

    public static function saveToken($tokenJson)
    {
        return Cache::put('OAUTHTOKEN', $tokenJson);
    }
}

