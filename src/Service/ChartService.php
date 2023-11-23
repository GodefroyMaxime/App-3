<?php 

namespace App\Service;

class ChartService{

    public function downloadDataImage($dataImage, $path, $name, $extention) {
        
        $img = str_replace('data:image/png;base64,', '', $dataImage);
        $img = str_replace(' ', '+', $img);
        $image = base64_decode($img);
        file_put_contents($path.'/'.$name.'.'.$extention, $image);
    }
}