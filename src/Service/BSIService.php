<?php 

namespace App\Service;

class BSIService
{
    public function addPathChartImageTable ($table) {

        foreach ($table as &$tableItem) {
            $tableItem['pieChartSalaire'] = '/chartImage/PieChartSalaire'.$tableItem['matricule'].'.png';
            $tableItem['barChartProtection'] = '/chartImage/BarChartProtection'.$tableItem['matricule'].'.png';
            $tableItem['pieChartProtection'] = '/chartImage/PieChartProtection'.$tableItem['matricule'].'.png';         
        }
        return $table;         
    }
}