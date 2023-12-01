<?php 

namespace App\Service;

use Dompdf\Dompdf;
use Dompdf\Options;

class PDFService
{
    public function generatePDF($orientation, $html, $title = "default") {
        $options = new Options();
        $options->setIsRemoteEnabled(true);
        $pdf = new Dompdf($options);

        if ($orientation == "landscape") {
            $pdf->setPaper('A4', 'landscape');
        } else {
            $pdf->setPaper('A4', 'portrait');
        }

        $pdf->loadHtml($html);
        $pdf->render();
        $pdf->stream($title.".pdf", [
            "Attachement"=>true,
        ]);
    }
}