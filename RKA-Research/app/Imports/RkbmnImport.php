<?php
namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class RkbmnImport implements WithMultipleSheets
{
    protected $documentID;

    public function __construct($documentID)
    {
        $this->documentID = $documentID;
    }

    public function sheets(): array
    {
        return [
            // Memanggil class berdasarkan Nama Sheet Excel
            'Pengadaan' => new PengadaanSheetImport($this->documentID),
            'Pemeliharaan' => new PemeliharaanSheetImport($this->documentID),
        ];
    }
}