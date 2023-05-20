<?php

namespace App\Models;

use App\Models\Visita;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Cell\Hyperlink;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DatosExport implements FromCollection, WithHeadings, WithStyles, WithDrawings
{
    protected $visitas;
    protected $foto;

    public function __construct(Collection $visitas)
    {
        $this->visitas = $visitas;
    }

    public function collection()
    {
        $appUrl = config('app.storage_url');
        $this->visitas->transform(function($visita) use ($appUrl) {

            /* if (app()->environment('production')) {
                $visita->foto = $appUrl.'/'.'visitas/' . $visita->usuario_id. '/' . $visita->visitante_foto;
            } else {
                $visita->foto = storage_path('app/public/visitas/' . $visita->usuario_id. '/' . $visita->visitante_foto);
            } */
            $visita->foto = $appUrl.'/'.'visitas/' . $visita->visitante_foto;

            $this->foto[] = storage_path('app/public/visitas/' . '/' . $visita->visitante_foto);

            unset($visita->usuario_id);
            unset($visita->visitante_foto);

            return $visita;

            });

        return $this->visitas;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nombre del visitante',
            'Nombre de la ARL',
            'Nombre de la EPS',
            'Tipo de sangre',
            'Torre',
            'Apartamento',
            'Propietario',
            'Hora de ingreso',
            'Observación',
            'Foto del visitante',
        ];
    }

    public function styles(Worksheet $sheet)
    {

        // Obtener la primera fila (fila 1)
        // $row = $sheet->getRowDimension(1);

        // Obtener el rango de celdas de la fila
        $range = 'A1:' . $sheet->getHighestColumn() . '1';

        // Establecer el color de fondo del rango de celdas
        $sheet->getStyle($range)
            ->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB('1976d2');

        // Establecer el color de fuente de la primera fila
        $sheet->getStyle('A1:' . $sheet->getHighestColumn() . '1')
            ->getFont()
            ->getColor()
            ->setARGB(Color::COLOR_WHITE);

        // Configurar ancho de columna para la columna 2 (B)
        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(13);
        $sheet->getColumnDimension('D')->setWidth(13);
        $sheet->getColumnDimension('E')->setWidth(13);
        $sheet->getColumnDimension('F')->setWidth(13);
        $sheet->getColumnDimension('G')->setWidth(13);
        $sheet->getColumnDimension('H')->setWidth(13);
        $sheet->getColumnDimension('I')->setWidth(13);
        $sheet->getColumnDimension('J')->setWidth(20);
        $sheet->getColumnDimension('K')->setWidth(25);

        // Ajustar el ancho de las columnas al contenido
        $sheet->getStyle('A1:' . $sheet->getHighestColumn() . $sheet->getHighestRow())
        ->getAlignment()
        ->setWrapText(true);

        // Configurar la altura de las filas para que se ajusten automáticamente al contenido
        $sheet->getStyle('A1:' . $sheet->getHighestColumn() . $sheet->getHighestRow())
        ->getAlignment()
        ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
        // Configurar altura de fila para la fila 1
        // $row->setRowHeight(20);
        // Establecer altura de la primera fila
        $firstRow = $sheet->getRowDimension(1);
        $firstRow->setRowHeight(35);

        // Establecer altura de las filas restantes
        $highestRow = $sheet->getHighestRow();
        for ($i = 2; $i <= $highestRow; $i++) {
            $row = $sheet->getRowDimension($i);
            $row->setRowHeight(100);
        }
    }

    public function drawings()
    {
        $sheet = new Worksheet(null, 'Sheet1');
        $rowIndex = 2;
        // $rowIndex = $sheet->getHighestRow();
        $drawings = [];
        // Dibujar una imagen por cada visita en la colección
        $i = 0;
        foreach ($this->visitas as $visita) {
            $drawing = new Drawing();
            // $drawing->setPath($visita->foto); // Establecer la ruta de la imagen
            $drawing->setPath($this->foto[$i++]); // Establecer la ruta de la imagen
            $drawing->setHeight(120); // Establecer la altura de la imagen
            $drawing->setCoordinates('L' . $rowIndex); // Establecer las coordenadas de la celda donde se dibujará la imagen
            $drawings[] = $drawing; // Agregar el objeto Drawing al arreglo de dibujos
            // Obtener la fila correspondiente a la visita actual

           /*  $url = $visita->foto;
            $hyperlink = new Hyperlink($url, $url);
            $sheet->setCellValue('J' . $rowIndex, $url);
            $sheet->getCell('J' . $rowIndex)->setHyperlink($hyperlink); */
            unset($visita->foto);
            $rowIndex++;
        }

        return $drawings;
    }
}
