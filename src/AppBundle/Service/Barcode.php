<?php
/**
 * Created by PhpStorm.
 * User: ivanis
 * Date: 09/09/15
 * Time: 00:27
 */

namespace AppBundle\Service;

class Barcode
{
    /**
     * @param $text
     * @param string $barcodeType
     * @return mixed
     */
    public function draw(
        $text,
        $barcodeType = 'ean13'
    ){
        $barcodeParams  = array('text' => $text);
        return \Zend\Barcode\Barcode::draw(
            $barcodeType,
            'image',
            $barcodeParams,
            array('imageType' => 'png')
        );
    }

    public function dataURI(
        $text,
        $barcodeType = 'code39'
    ) {
        $resource = $this->draw($text, $barcodeType);

        ob_start();
        imagepng($resource);
        return 'data:image/png;base64,'.base64_encode(ob_get_clean());
    }
}