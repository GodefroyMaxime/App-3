<?php 

namespace App\Service;

use ZipArchive;

class BSIService
{
    private $zip;
    private $zipName;
    private $isZipOpened = false;

    public function __construct()
    {
        $this->zip = new ZipArchive();
        $this->zipName = 'collaborators.zip'; // Name of the zip file
    }

    public function addPathChartImageTable ($table) {

        foreach ($table as &$tableItem) {
            $tableItem['pieChartSalaire'] = '/chartImage/PieChartSalaire'.$tableItem['matricule'].'.png';
            $tableItem['barChartProtection'] = '/chartImage/BarChartProtection'.$tableItem['matricule'].'.png';
            $tableItem['pieChartProtection'] = '/chartImage/PieChartProtection'.$tableItem['matricule'].'.png';         
        }
        return $table;         
    }

    public function addZipFile($pdfName)
    {
        if (!$this->isZipOpened) {
            if ($this->zip->open($this->zipName, ZipArchive::CREATE) !== true) {
                throw new \Exception("Cannot open {$this->zipName}");
            }
            $this->isZipOpened = true;
        }

        $pdfPath = 'BSI/' . $pdfName;
        if (file_exists($pdfPath)) {
            $this->zip->addFile($pdfPath, $pdfName);
        } else {
            // Optionally handle the case where the PDF file doesn't exist
        }
    }

    public function finalizeZip()
    {
        if ($this->isZipOpened) {
            $this->zip->close();
            $this->isZipOpened = false;
        }
        return $this->zipName;
    }
}
