<?php

namespace app\controllers;

use yii;
use yii\web\Controller;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use yii\helpers\ArrayHelper;

class AutocompleteController extends Controller
{

    public static function getGeocoder($location)
    {
        $api_key = Yii::$app->params['apiKey'];
        $geocoder_coordinates_key = Yii::$app->params['geocoderCoordinatesKey'];
        $geocoder_address_key = Yii::$app->params['geocoderAdressKey'];
        $geocoder_city_key = Yii::$app->params['geocoderCityKey'];
        $geocoder_url = Yii::$app->params['geocoderUrl'];
        $geocoder_version = Yii::$app->params['geocoderVersion'];
        $geocoder_options_key = Yii::$app->params['geocoderOptionsKey'];

        $client = new Client([
        'base_uri' => $geocoder_url,
        ]);

        $response = $client->request('GET', $geocoder_version, [
            'query' =>
            [
                'apikey' => $api_key,
                'geocode' => $location,
                'format' => 'json',
                'results' => 5
                ]
        ]);

        try
        {
            $content = $response->getBody()->getContents();
            $response = json_decode($content, true);
            $options = ArrayHelper::getValue($response, $geocoder_options_key);
            $result = [];

            foreach ($options as $value)
            {
                $coordinates = explode(' ', ArrayHelper::getValue($value, $geocoder_coordinates_key));
                $address = explode(',', ArrayHelper::getValue($value, $geocoder_city_key));

                $result[] =
                [
                    'location' => ArrayHelper::getValue($value, $geocoder_address_key),
                    'city' => $address[0],
                    'lng' => $coordinates[0],
                    'lat' => $coordinates[1],
                ];
            }
        }

        catch(RequestException $e)
        {
            $result = [];
        }

        return $result;
    }   

    public function actionIndex($location)
    {

        return $this->asJson(self::getGeocoder($location));
    }
}
