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
        $this->zipName = 'collaborators.zip';
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
                throw new \Exception("Impossible d'ouvrir {$this->zipName}");
            }
            $this->isZipOpened = true;
        }

        $pdfPath = 'BSI/' . $pdfName;
        if (file_exists($pdfPath)) {
            $this->zip->addFile($pdfPath, $pdfName);
        } else {
            throw new \Exception("Le fichier PDF {$pdfPath} n'existe pas.");
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
