<?php

//use yii\authclient\clients\VKontakte;

namespace app\modules\vkmarket;

class DeeplyVKontakte extends \yii\authclient\clients\VKontakte
{

    // it's 400x400 for the item minimal main picture size
    const MARKET_MIN_PIC_HEIGHT = 400; 
    const MARKET_MIN_PIC_WIDTH = 400;
    
    const MARKET_DEFAULT_CATEGORY_ID = 505;
    
    const APIMETHOD_GET_MARKET_UPLOAD_SERVER = 'photos.getMarketUploadServer';
    const 
    
    public function addMarketItem
        (
            $groupID, 
            $mainPhotoPath, 
            $itemName, 
            $description, 
            $price, 
            $category_id = MARKET_DEFAULT_CATEGORY_ID
        )
    {
        print("addMarketItem not implemented!");
        die;
    }

    public function getUploadServer
        (
            $vkgroup_id, 
            $main_photo=1, 
            $crop_x=0, 
            $crop_y=0, 
            $crop_width=MARKET_MIN_PIC_WIDTH
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
        
        $this->api()
        
    }
    
    private function uploadMarktetItemPhoto($pathToFile){
        
    }

}