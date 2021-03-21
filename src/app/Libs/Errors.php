<?php 
namespace App\Libs;

class Errors{
    static function getLogLevel(int $http)
        {
           
        if ($http >= 200) {
            $response = 200;
        }
        if ($http >= 250) {
            $response = 250;
        }
        if ($http >= 300) {
            $response = 300;
        }
        if ($http >= 400) {
            $response = 400;
        }
        if ($http >= 500) {
            $response = 500;
        }
        if ($http >= 550) {
            $response = 550;
        }
        if ($http >= 600) {
            $response = 600;
        }
        if ($http == 404) {
            $response = 500;
        }
        return $response;
    }
}
