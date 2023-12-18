<?php 

namespace App\Service;

use Dompdf\Dompdf;
use Dompdf\Options;

class PDFService
{
    public function generatePDF($orientation, $html, $title = "default", $size = "A4") {
        $options = new Options();
        $options->setIsRemoteEnabled(true);
        $pdf = new Dompdf($options);

        $pdf->setPaper($size, $orientation);

        $pdf->loadHtml($html);
        $pdf->render();
        $pdf->stream($title.".pdf", [
            "Attachement"=>true,
        ]);
    }
}