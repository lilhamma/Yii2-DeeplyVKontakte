<?php

//use yii\authclient\clients\VKontakte;

namespace app\modules\vkmarket;

class DeeplyVKontakte extends \yii\authclient\clients\VKontakte
{

    // it's 400x400 for the item minimal main picture size
    const MARKET_MIN_PIC_HEIGHT = 400; 
    const MARKET_MIN_PIC_WIDTH = 400;

    public function addMarketItem
        (
            $groupID, 
            $mainPhotoPath, 
            $itemName, 
            $description, 
            $price, 
            $category_id = 505
        )
    {
        print("addMarketItem not implemented!");
        die;
    }

    private function getUploadServer
        (
            $vkgroup_id, 
            $main_photo=1, 
            $crop_x=0, 
            $crop_y=0, 
            $crop_width=400
        )
    {
        $postargs_getserv = [
            'group_id'=>$vkgroup_id, 
            'main_photo'=>$main_photo, 
            'crop_x'=>$crop_x, 
            'crop_y'=>$crop_y, 
            'crop_width'=>$crop_width,
            'access_token'=>$this->getAccessToken()->token
        ];
        
        
    }
    
    private function uploadMarkteItemPhoto($pathToFile){
        
    }

}