<?php
/**
 * Created by PhpStorm.
 * User: ivanis
 * Date: 08/09/15
 * Time: 18:54
 */

namespace AppBundle\Service;


use Dompdf\Dompdf;
use Dompdf\Options;

class PDF
{

    private $dompdf;

    public function __construct(Dompdf $dompdf){
        $this->dompdf = $dompdf;

        $dompdf->setOptions(
            new Options(
                array(
                    'isHtml5ParserEnabled' => true
                )
            )
        );
    }

    public function create ($html){
        $this->dompdf->loadHtml($html);

        $this->dompdf->setPaper('A4', 'portrait');
        $this->dompdf->render();

        return $this->dompdf->output();
    }
}