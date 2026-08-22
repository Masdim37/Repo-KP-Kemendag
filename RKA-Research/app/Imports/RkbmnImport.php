<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class RkbmnImport implements WithMultipleSheets
{
    protected string $documentID;
    protected int $tahunAnggaran;

    public function __construct(string $documentID, int $tahunAnggaran)
    {
        $this->documentID = $documentID;
        $this->tahunAnggaran = $tahunAnggaran;
    }

    public function sheets(): array
    {
        return [
            'Pengadaan' => new PengadaanSheetImport(
                $this->documentID,
                $this->tahunAnggaran
            ),
            'Pemeliharaan' => new PemeliharaanSheetImport(
                $this->documentID,
                $this->tahunAnggaran
            ),
        ];
    }
}
