<?php 

namespace App\Service;

use Dompdf\Dompdf;
use Dompdf\Options;

class PDFService
{
    public function generatePDF($orientation, $html, $title = "default", $size = "A4") {
        $options = new Options();
        $options->setIsRemoteEnabled(true);
        //$options->setDebugLayout(true); Debug CSS

        $pdf = new Dompdf($options);

        $pdf->setPaper($size, $orientation);

        $pdf->loadHtml($html);
        $pdf->render();
        $pdf->stream($title.".pdf", [
            "Attachement"=> true,
        ]);
    }
    
    public function imageToBase64($path) {
        $path = $path;
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        return $base64;
    }
}