<?php namespace App\Classes;

use App\Models\Setting;
use Mail;

class Instagram
{
    public function getInstagramFeeds($limit = 5)
    {
        $accessToken = config()->get('services.instagram.access-token');
        $api_url = "https://api.instagram.com/v1/users/self/media/recent/?access_token=".$accessToken."&count=".$limit;
        $connection_c = curl_init();
        curl_setopt( $connection_c, CURLOPT_URL, $api_url );
        curl_setopt( $connection_c, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt( $connection_c, CURLOPT_TIMEOUT, 20 );
        $json_return = curl_exec( $connection_c );
        curl_close( $connection_c );
        $insta = json_decode( $json_return );
        foreach($insta->data as $feed)
        {
            $items[] = [
                'link'=>$feed->link,
                'thumbnail'=>$feed->images->thumbnail->url,
                'low_resolution'=>$feed->images->low_resolution->url,
                'standard_resolution'=>$feed->images->standard_resolution->url
            ];
        }
        return $items;
    }
}