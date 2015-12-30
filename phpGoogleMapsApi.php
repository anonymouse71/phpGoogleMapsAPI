<?php

class phpGoogleMapsApi {

    public $address;
    public $postal_code;
    public $name;
    public $googleAPIKey;
    public $country;
    public $types;
    public $radius;
    public $maxPicWidth;

    public function getLatLng($address, $country, $postal_code) {
        $edited_address = str_replace(" ", "+", str_replace(", ", "+", $address));
        $url = "http://maps.googleapis.com/maps/api/geocode/json?address=$edited_address&components=country:$country|postal_code:$postal_code";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $lng_lat = json_decode(curl_exec($ch), true);
        curl_close($ch);
        $lat = $lng_lat['results'][0]["geometry"]["location"]['lat'];
        $lng = $lng_lat['results'][0]["geometry"]["location"]['lng'];
        return $location = $numbers = array($lat, $lng);
    }

    public function getPlaceId($lat, $lng, $radius, $types, $name, $googleAPIKey) {
        $url = "https://maps.googleapis.com/maps/api/place/nearbysearch/json?location=$lat,$lng&radius=$radius&types=$types&name=$name&key=$googleAPIKey";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $placeId_photos = json_decode(curl_exec($ch), true);
        curl_close($ch);
        //$photos = $placeId_photos['results'][0]['photos'];
        $place_id = $placeId_photos['results'][0]['place_id'];
        return $place_id;
    }

    public function getPhotoRefrance($place_id, $googleAPIKey) {
        $url = "https://maps.googleapis.com/maps/api/place/details/json?placeid=$place_id&key=$googleAPIKey";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $place_photos = json_decode(curl_exec($ch), true);
        curl_close($ch);
        $photo_refrances = array();
        if (count($place_photos['result']['photos']) > 1) {
            for ($i = 0; $i < count($place_photos['result']['photos']); $i++) {
                array_push($photo_refrances, $place_photos['result']['photos'][$i]['photo_reference']);
            }
        }
        return $photo_refrances;
    }

    public function getPhotoURL($googleAPIKey, $maxPicWidth, $photorefrence) {
        $url = "https://maps.googleapis.com/maps/api/place/photo?key=$googleAPIKey&maxwidth=$maxwidth&photoreference=$photorefrence";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $photo = curl_exec($ch);
        $img_url = curl_getinfo($ch);
        curl_close($ch);
        return $img_url['redirect_url'];
    }

}

/*
 * 

We recommend every repository include a README, LICENSE, and .gitignore.
 * 
…or create a new repository on the command line

echo "# phpGoogleMapsAPI" >> README.md
git init
git add README.md
git commit -m "first commit"
git remote add origin git@github.com:messam88/phpGoogleMapsAPI.git
git push -u origin master
 * 
 * 
 * 
…or push an existing repository from the command line

git remote add origin git@github.com:messam88/phpGoogleMapsAPI.git
git push -u origin master
 
 */