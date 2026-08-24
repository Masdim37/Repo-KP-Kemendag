<script>
    function refGetKegiatanCodesForSatker(satkerCode, satkerKegiatanData) {
        return new Set(satkerKegiatanData.filter(item => refSame(item.kode_satker, satkerCode)).map(item => refAsString(
            item.kode_kegiatan)).filter(Boolean));
    }

    function refPopulateProgramForSatker({
        satkerEl,
        programEl,
        satkerKegiatanData,
        kegiatanData,
        programData,
        selectedValue = ''
    }) {
        const satker = refAsString(satkerEl.value);
        if (!satker) {
            refResetSelect(programEl, '-- Pilih Satker terlebih dahulu --');
            return;
        }
        const allowed = refGetKegiatanCodesForSatker(satker, satkerKegiatanData);
        const programCodes = new Set(kegiatanData.filter(item => allowed.has(refAsString(item.kode_kegiatan))).map(
            item => refAsString(item.kode_program)).filter(Boolean));
        const filtered = programData.filter(item => programCodes.has(refAsString(item.kode_program)));
        refRenderOptions({
            select: programEl,
            data: refUniqueBy(filtered, 'kode_program'),
            valueKey: 'kode_program',
            labelKey: 'nama_program',
            placeholder: '-- Pilih Program --',
            selectedValue,
            emptyText: '-- Program untuk Satker ini tidak tersedia --'
        });
    }

    function refPopulateKegiatan({
        programEl,
        kegiatanEl,
        kegiatanData,
        selectedValue = '',
        allowedKegiatanCodes = null
    }) {
        const program = refAsString(programEl.value);
        if (!program) {
            refResetSelect(kegiatanEl, '-- Pilih Program terlebih dahulu --');
            return;
        }
        let filtered = kegiatanData.filter(item => refSame(item.kode_program, program));
        if (allowedKegiatanCodes instanceof Set) filtered = filtered.filter(item => allowedKegiatanCodes.has(
            refAsString(item.kode_kegiatan)));
        refRenderOptions({
            select: kegiatanEl,
            data: refUniqueBy(filtered, 'kode_kegiatan'),
            valueKey: 'kode_kegiatan',
            labelKey: 'nama_kegiatan',
            placeholder: '-- Pilih Kegiatan --',
            selectedValue,
            emptyText: '-- Kegiatan tidak tersedia --'
        });
    }

    function refPopulateKro({
        kegiatanEl,
        kroEl,
        kroData,
        selectedValue = ''
    }) {
        const kegiatan = refAsString(kegiatanEl.value);
        if (!kegiatan) {
            refResetSelect(kroEl, '-- Pilih Kegiatan terlebih dahulu --');
            return;
        }
        const filtered = kroData.filter(item => refSame(item.kode_kegiatan, kegiatan));
        refRenderOptions({
            select: kroEl,
            data: refUniqueBy(filtered, 'kode_kro'),
            valueKey: 'kode_kro',
            labelKey: 'nama_kro',
            placeholder: '-- Pilih KRO --',
            selectedValue,
            emptyText: '-- KRO tidak tersedia --'
        });
    }

    function refPopulateRo({
        kegiatanEl,
        kroEl,
        roEl,
        roData,
        selectedValue = ''
    }) {
        const kegiatan = refAsString(kegiatanEl.value),
            kro = refAsString(kroEl.value);
        if (!kegiatan || !kro) {
            refResetSelect(roEl, '-- Pilih KRO terlebih dahulu --');
            return;
        }
        const filtered = roData.filter(item => refSame(item.kode_kegiatan, kegiatan) && refSame(item.kode_kro, kro));
        refRenderOptions({
            select: roEl,
            data: refUniqueBy(filtered, 'kode_ro'),
            valueKey: 'kode_ro',
            labelKey: 'nama_ro',
            placeholder: '-- Pilih RO --',
            selectedValue,
            emptyText: '-- RO tidak tersedia --'
        });
    }

    function refPopulateKomponen({
        kegiatanEl,
        kroEl,
        roEl,
        komponenEl,
        komponenData,
        selectedValue = ''
    }) {
        const kegiatan = refAsString(kegiatanEl.value),
            kro = refAsString(kroEl.value),
            ro = refAsString(roEl.value);
        if (!kegiatan || !kro || !ro) {
            refResetSelect(komponenEl, '-- Pilih RO terlebih dahulu --');
            return;
        }
        const filtered = komponenData.filter(item => refSame(item.kode_kegiatan, kegiatan) && refSame(item.kode_kro,
            kro) && refSame(item.kode_ro, ro));
        refRenderOptions({
            select: komponenEl,
            data: refUniqueBy(filtered, 'kode_komponen'),
            valueKey: 'kode_komponen',
            labelKey: 'nama_komponen',
            placeholder: '-- Pilih Komponen --',
            selectedValue,
            emptyText: '-- Komponen tidak tersedia --',
            extraLabel: item => item.jenis_komponen ? `Jenis ${item.jenis_komponen}` : ''
        });
    }

    function refPopulateUnit2({
        unit1El,
        unit2El,
        unitEselon2Data,
        selectedValue = ''
    }) {
        const code = refAsString(unit1El.value);
        if (!code) {
            refResetSelect(unit2El, '-- Pilih Unit Eselon I terlebih dahulu --');
            return;
        }
        const filtered = unitEselon2Data.filter(item => refSame(item.kode_unit_eselon1, code));
        refRenderOptions({
            select: unit2El,
            data: filtered,
            valueKey: 'kode_unit_eselon2',
            labelKey: 'nama_unit_eselon2',
            placeholder: '-- Pilih Unit Eselon II --',
            selectedValue,
            emptyText: '-- Unit Eselon II tidak tersedia --'
        });
    }

    function refPopulateSatker({
        unit2El,
        satkerEl,
        satkerData,
        selectedValue = ''
    }) {
        const code = refAsString(unit2El.value);
        if (!code) {
            refResetSelect(satkerEl, '-- Pilih Unit Eselon II terlebih dahulu --');
            return;
        }
        const filtered = satkerData.filter(item => refSame(item.kode_unit_eselon2, code));
        refRenderOptions({
            select: satkerEl,
            data: refUniqueBy(filtered, 'kode_satker'),
            valueKey: 'kode_satker',
            labelKey: 'nama_satker',
            placeholder: '-- Pilih Satker --',
            selectedValue,
            emptyText: '-- Satker tidak tersedia --'
        });
    }
</script>
