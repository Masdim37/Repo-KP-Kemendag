<script>
    function refAsString(value) {
        return value === null || value === undefined ? '' : String(value).trim();
    }

    function refSame(left, right) {
        return refAsString(left) === refAsString(right);
    }

    function refUniqueBy(data, key) {
        const seen = new Set();
        return data.filter(item => {
            const value = refAsString(item?.[key]);
            if (!value || seen.has(value)) return false;
            seen.add(value);
            return true;
        });
    }

    function refResetSelect(select, placeholder) {
        if (!select) return;
        select.innerHTML = `<option value="">${placeholder}</option>`;
        select.disabled = true;
    }

    function refRenderOptions({
        select,
        data,
        valueKey,
        labelKey,
        placeholder,
        selectedValue = '',
        emptyText = '-- Data tidak tersedia --',
        extraLabel = null
    }) {
        select.innerHTML = '';
        const first = document.createElement('option');
        first.value = '';
        first.textContent = data.length ? placeholder : emptyText;
        select.appendChild(first);
        data.forEach(item => {
            const code = refAsString(item?.[valueKey]);
            if (!code) return;
            const name = refAsString(item?.[labelKey]);
            const option = document.createElement('option');
            option.value = code;
            const extra = typeof extraLabel === 'function' ? refAsString(extraLabel(item)) : '';
            option.textContent = `[${code}] ${name}${extra?` — ${extra}`:''}`.trim();
            select.appendChild(option);
        });
        select.disabled = data.length === 0;
        if (selectedValue && data.some(item => refSame(item?.[valueKey], selectedValue))) select.value = refAsString(
            selectedValue);
        else select.value = '';
    }

    function refSetFieldError(input, errorEl, message = '') {
        const show = !!message;
        input?.classList.toggle('is-invalid', show);
        if (errorEl) {
            errorEl.textContent = message;
            errorEl.classList.toggle('show', show);
        }
    }

    function refModalErrorMessage(payload, fallback) {
        if (payload && payload.errors) {
            const messages = Object.values(payload.errors).flat().filter(Boolean);
            if (messages.length) return messages.join('\n');
        }
        return payload?.message || fallback;
    }
    async function refReadResponse(response) {
        const text = await response.text();
        if (!text) return {};
        try {
            return JSON.parse(text);
        } catch (_) {
            return {
                message: response.ok ? text : 'Server mengembalikan respons yang tidak dapat dibaca.'
            };
        }
    }
    async function refSubmitAjax({
        form,
        saveButton,
        loadingMessage,
        successReset,
        fieldMap = {}
    }) {
        saveButton.disabled = true;
        DocumentProcessingModal.showLoading({
            title: 'Menyimpan Data Referensi',
            message: loadingMessage
        });
        try {
            const response = await fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            const payload = await refReadResponse(response);
            if (!response.ok || payload.success === false) {
                Object.entries(fieldMap).forEach(([field, {
                    input,
                    error
                }]) => {
                    if (payload.errors?.[field]?.[0]) refSetFieldError(input, error, payload.errors[field][
                        0]);
                });
                DocumentProcessingModal.showError({
                    title: payload.title || 'Data Referensi Gagal Disimpan',
                    message: refModalErrorMessage(payload,
                        'Terjadi kesalahan saat menyimpan data referensi.'),
                    buttonText: 'TUTUP'
                });
                return false;
            }
            DocumentProcessingModal.showSuccess({
                title: payload.title || 'Data Referensi Berhasil Ditambahkan',
                message: payload.message || 'Data referensi berhasil ditambahkan.',
                buttonText: 'OKE',
                onClose: () => successReset?.(payload)
            });
            return true;
        } catch (error) {
            DocumentProcessingModal.showError({
                title: 'Data Referensi Gagal Disimpan',
                message: 'Tidak dapat terhubung ke server. Silakan coba kembali.',
                details: error?.message || '',
                buttonText: 'TUTUP'
            });
            return false;
        } finally {
            saveButton.disabled = false;
        }
    }
</script>
