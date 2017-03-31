<?php


/// USES:
/// \yii\authclient\clients\VKontakte   (http://www.yiiframework.com/doc-2.0/yii-authclient-clients-vkontakte.html)
/// \yii\httpclient\Client              (http://www.yiiframework.com/doc-2.0/yii-httpclient-client.html)

namespace app\modules\vkmarket;

class DeeplyVKontakte extends \yii\authclient\clients\VKontakte
{

    // it's 400x400 for the item minimal main picture size
    const MARKET_MIN_PIC_HEIGHT = 400; 
    const MARKET_MIN_PIC_WIDTH = 400;
    
    const MARKET_DEFAULT_CATEGORY_ID = 505;
    
    const METHOD_DEFAULT_TYPE = 'POST';
    
    const APIMETHOD_GET_MARKET_UPLOAD_SERVER =      'photos.getMarketUploadServer';
    const APIMETHOD_SAVE_UPLOADED_MARKET_PHOTO =    'photos.saveMarketPhoto';
    const APIMETHOD_ADD_MARKET_ITEM =               'market.add';
    
    
    /// docs:   https://vk.com/dev/market.add
    public function addMarketItem(
        $vkgroup_id, 
        $mainPhotoPath, 
        $itemName, 
        $description, 
        $price, 
        $category_id = self::MARKET_DEFAULT_CATEGORY_ID
    ){
        $uploadedPhotoData = $this->uploadMarketItemPhoto($vkgroup_id, $mainPhotoPath, '1');
        
        $savedPhotoData = $this->saveUploadedMarketItemPhoto($uploadedPhotoData, $vkgroup_id, '1');
        
        return = $this->api(
            self::APIMETHOD_ADD_MARKET_ITEM,
            self::METHOD_DEFAULT_TYPE,
            [
                'owner_id' =>       '-'.$vkgroup_id,
                'main_photo_id' =>  $savedPhotoData[0]['pid'],
                'name' =>           $itemName,
                'description' =>    $description,
                'price' =>          $price,
                'category_id' =>    $category_id
            ]
        )
        ['response']
        ['market_item_id'];
    }

    /// docs:   https://vk.com/dev/upload_files_2?f=6.%20Uploading%20a%20Market%20Item%20Photo
    public function getUploadServerURL(
        $vkgroup_id, 
        $main_photo, 
        $crop_x, 
        $crop_y, 
        $crop_width
    ){
        $getServerParams = [
            'group_id'=>$vkgroup_id, 
            'main_photo'=>$main_photo, 
            'crop_x'=>$crop_x, 
            'crop_y'=>$crop_y, 
            'crop_width'=>$crop_width,
        ];
        
        return $this->api
            (
                self::APIMETHOD_GET_MARKET_UPLOAD_SERVER,
                self::METHOD_DEFAULT_TYPE,
                $getServerParams
            )
            ['response']
            ['upload_url'];
    }
    
    public function uploadMarketItemPhoto($vkgroup_id, $pathToFile, $main_photo){
        $upload_url = $this->getUploadServerURL(
            $vkgroup_id,
            $main_photo, 
            0, 
            0, 
            self::MARKET_MIN_PIC_WIDTH
        );
        
        $uploadRequest = $this->initializeEmptyPOSTRequestFromURL($upload_url);
        $uploadParams = $this->getParamsArrayFromUrl($upload_url);
        $uploadParams['main_photo'] = $main_photo;
        
        $uploadResponse = $uploadRequest
            ->setData($uploadParams)
            ->addFile('photo', $pathToFile)
            ->send();
            
        //$uploadResponse->setFormat(\yii\httpclient\Client::FORMAT_JSON); // Works without this. Autodetection? Response headers?
        return  $uploadResponse->getData();
    }
    
    public function saveUploadedMarketItemPhoto($uploadedPhotoData, $group_id, $main_photo){
        $saveRequestData = [
            'group_id'=>    $group_id,
            'server'=>      $uploadedPhotoData['server'],
            'photo'=>       $uploadedPhotoData['photo'],
            'hash'=>        $uploadedPhotoData['hash']
        ];
        if($main_photo=='1'){
            $saveRequestData['crop_data'] = $uploadedPhotoData['crop_data'];
            $saveRequestData['crop_hash'] = $uploadedPhotoData['crop_hash'];
        }
        return $this->api(
            self::APIMETHOD_SAVE_UPLOADED_MARKET_PHOTO,
            self::METHOD_DEFAULT_TYPE,
            $saveRequestData
        )['response'];
    }
    
    public function initializeEmptyPOSTRequestFromURL($urlToUse){
        $uploadHost = parse_url($urlToUse, PHP_URL_HOST);
        $uploadPath = parse_url($urlToUse, PHP_URL_PATH);
        
        $tmpClient = new \yii\httpclient\Client(['baseUrl' => 'https://' . $uploadHost . $uploadPath]);
        
        return ($tmpClient->createRequest()->setMethod('post'));
    }
    
    public function getParamsArrayFromUrl($urlToUse){
        $uploadQueryBase_Str = parse_url($urlToUse, PHP_URL_QUERY);
        parse_str($uploadQueryBase_Str, $uploadQueryBase_Arr);
        return $uploadQueryBase_Arr;
    }
}