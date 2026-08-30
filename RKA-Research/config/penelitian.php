<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Penelitian CHP - C.3 Mapping Belanja Pegawai
    |--------------------------------------------------------------------------
    |
    | Klasifikasi PNS / PPPK / Honorer dilakukan berdasarkan kode akun yang
    | eksplisit. Jangan menambahkan akun ambigu tanpa konfirmasi business rule.
    |
    | Parent "Belanja Pegawai" tetap merupakan seluruh akun prefix 51.
    | Akun prefix 51 yang belum ada pada mapping ini tidak dibuang; nominalnya
    | tetap masuk parent dan akan dijelaskan pada CATATAN sebagai belum
    | terpetakan.
    |
    | Mapping awal di bawah ini hanya memasukkan akun yang nomenklaturnya
    | cukup eksplisit pada master akun yang saat ini digunakan.
    |
    */

    'c3_employee_account_mapping' => [

        'PNS' => [
            '511111', // Belanja Gaji Pokok PNS
            '511119', // Belanja Pembulatan Gaji PNS
            '511121', // Belanja Tunj. Suami/Istri PNS
            '511122', // Belanja Tunj. Anak PNS
            '511123', // Belanja Tunj. Struktural PNS
            '511124', // Belanja Tunj. Fungsional PNS
            '511125', // Belanja Tunj. PPh PNS
            '511126', // Belanja Tunj. Beras PNS
            '511129', // Belanja Uang Makan PNS
            '511141', // Belanja Tunj. Sewa Rumah PNS
            '511142', // Belanja Tunj. Restitusi Pengobatan PNS
            '511145', // Belanja Tunj. Penghidupan LN Home Staff PNS
            '511151', // Belanja Tunjangan Umum PNS
        ],

        'PPPK' => [
            '511611', // Belanja Gaji Pokok PPPK
            '511619', // Belanja Pembulatan Gaji PPPK
            '511621', // Belanja Tunjangan Suami/Istri PPPK
            '511622', // Belanja Tunjangan Anak PPPK
            '511624', // Belanja Tunjangan Fungsional PPPK
            '511625', // Belanja Tunjangan Beras PPPK
            '511628', // Belanja Uang Makan PPPK
            '512212', // Belanja Uang Lembur PPPK
            '512414', // Belanja Pegawai Tunjangan Khusus/Kegiatan/Kinerja PPPK
        ],

        'HONORER' => [
            '512111', // Belanja Uang Honor Tetap
        ],

        /*
         * Contoh akun 51 yang sengaja BELUM dipetakan karena business meaning
         * belum cukup tegas untuk dimasukkan otomatis ke PNS/PPPK/Honorer:
         *
         * 511149 - Belanja Lokal Staff Lainnya
         * 511323 - Belanja Tunj. Struktural Pejabat Negara
         * 511337 - Belanja Tunjangan Lain
         * 511339 - Belanja Tunjangan Penghasilan Pejabat Negara
         * 511512 - Belanja Tunjangan Pegawai Non PNS
         * 512211 - Belanja Uang Lembur
         * 512411 - Belanja Pegawai (Tunjangan Khusus/Kegiatan/Kinerja)
         *
         * Jika nanti telah diputuskan kategorinya, cukup tambahkan kode akun
         * ke salah satu array di atas tanpa mengubah Research Service.
         */
    ],

    /*
    |--------------------------------------------------------------------------
    | Penelitian CHP - C.4 Belanja Barang Operasional
    |--------------------------------------------------------------------------
    |
    | Belanja Barang Operasional adalah seluruh akun prefix 52 yang berada
    | pada hierarki RKA:
    |
    | KRO EBA -> RO 994 -> Komponen 002
    |
    | Jika minimal satu path di bawah ini ditemukan pada RKA Satker:
    | - akun prefix 52 pada path tersebut = Operasional;
    | - akun prefix 52 lainnya = Non Operasional.
    |
    | Jika tidak satu pun path ditemukan, engine TIDAK menganggap seluruh akun
    | 52 sebagai Non Operasional. Parent tetap seluruh akun 52, sedangkan child
    | Operasional/Non Operasional ditampilkan Rp0 dengan CATATAN bahwa
    | klasifikasi perlu dikonfirmasi.
    |
    | Rule tidak boleh bergantung pada kode/nama Satker.
    |
    */

    'c4_operational_paths' => [
        [
            'kode_kro' => 'EBA',
            'kode_ro' => '994',
            'kode_komponen' => '002',
        ],

        /*
         * Jika di kemudian hari ditemukan struktur operasional resmi lain
         * pada RKA Satker lain, tambahkan sebagai elemen array baru di sini.
         *
         * Jangan membuat rule berdasarkan kode/nama Satker.
         */
    ],

    /*
    |--------------------------------------------------------------------------
    | Penelitian CHP - F Validasi Data Jumlah Pegawai
    |--------------------------------------------------------------------------
    |
    | Keyword hanya dipakai untuk memilih rincian RKA yang memang berkaitan
    | dengan gaji/honor pegawai. Jangan memakai Gemini untuk keputusan ini.
    |
    | Untuk mencegah false positive pada satuan OB/OJ/OK, jumlah orang
    | diambil terlebih dahulu dari pola seperti:
    |     [5 org x 6 bln]
    | Jika pola orang tidak tersedia, rka.volume hanya dipakai bila satuannya
    | memang merupakan satuan headcount (ORG/ORANG/PEGAWAI/SDM).
    |
    | Perbandingan dilakukan per detail RKA, bukan menjumlahkan seluruh
    | honorarium karena satu pegawai dapat memegang lebih dari satu peran.
    |
    */

    'f_employee_validation' => [
        'keywords' => [
            'GAJI',
            'HONORARIUM OPERASIONAL SATUAN KERJA',
            'HONOR OPERASIONAL SATUAN KERJA',
            'HONORARIUM PENGELOLA KEUANGAN',
            'HONORARIUM PENGELOLA PNBP',
            'HONORARIUM PENGELOLA SISTEM AKUNTANSI',
        ],

        'headcount_units' => [
            'ORG',
            'ORANG',
            'PEGAWAI',
            'SDM',
        ],
    ],


];
