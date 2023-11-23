<?php 

namespace App\Service;

use Dompdf\Dompdf;

class PDFService{

    public function generatePDF($html) {
        $pdf = new Dompdf();
        $pdf->setPaper('A4', 'portait');
        $pdf->loadHtml($html);
        $pdf->render();
        $pdf->stream("Test.pdf", [
            "Attachement"=>false,
        ]);
    }

}