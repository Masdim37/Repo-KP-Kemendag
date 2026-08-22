<style>
    body.document-processing-modal-open {
        overflow: hidden;
    }

    .document-processing-modal {
        position: fixed;
        inset: 0;
        z-index: 5000;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 20px;
        background: rgba(15, 35, 60, .48);
        backdrop-filter: blur(4px);
    }

    .document-processing-modal.show {
        display: flex;
    }

    .document-processing-dialog {
        width: min(430px, 100%);
        border: 1px solid #dbe5ee;
        border-radius: 18px;
        background: #ffffff;
        box-shadow: 0 24px 70px rgba(20, 44, 72, .24);
        overflow: hidden;
        animation: documentModalEnter .22s ease-out;
    }

    @keyframes documentModalEnter {
        from { opacity: 0; transform: translateY(10px) scale(.98); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }

    .document-processing-body {
        padding: 30px 28px 25px;
        text-align: center;
    }

    .document-processing-icon {
        width: 72px;
        height: 72px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 18px;
        border-radius: 50%;
        font-size: 34px;
    }

    .document-processing-icon.loading {
        background: #eef5ff;
    }

    .document-processing-icon.success {
        color: #159957;
        background: #effaf4;
        animation: documentStatePop .28s ease-out;
    }

    .document-processing-icon.error {
        color: #df4052;
        background: #fff4f5;
        animation: documentStatePop .28s ease-out;
    }

    @keyframes documentStatePop {
        0% { transform: scale(.72); opacity: .25; }
        70% { transform: scale(1.08); }
        100% { transform: scale(1); opacity: 1; }
    }

    .document-processing-spinner {
        width: 34px;
        height: 34px;
        border: 4px solid #cfe1fb;
        border-top-color: #0759b7;
        border-radius: 50%;
        animation: documentSpinner .8s linear infinite;
    }

    @keyframes documentSpinner {
        to { transform: rotate(360deg); }
    }

    .document-processing-title {
        margin: 0;
        color: #18365b;
        font-size: 17px;
        font-weight: 800;
        line-height: 1.35;
    }

    .document-processing-message {
        margin-top: 9px;
        color: #607995;
        font-size: 11px;
        line-height: 1.65;
        white-space: pre-line;
    }

    .document-processing-details {
        display: none;
        margin-top: 15px;
        padding: 11px 13px;
        border: 1px solid #e2e9f0;
        border-radius: 10px;
        color: #6b7f95;
        background: #f7f9fb;
        font-size: 10px;
        line-height: 1.55;
        text-align: left;
        white-space: pre-line;
        word-break: break-word;
    }

    .document-processing-details.show {
        display: block;
    }

    .document-processing-footer {
        display: none;
        justify-content: center;
        padding: 0 28px 25px;
    }

    .document-processing-footer.show {
        display: flex;
    }

    .document-processing-button {
        min-width: 120px;
        height: 40px;
        border: 0;
        border-radius: 10px;
        color: #ffffff;
        background: #0759b7;
        box-shadow: 0 5px 13px rgba(7, 89, 183, .18);
        font-size: 10px;
        font-weight: 800;
        cursor: pointer;
    }

    .document-processing-button:hover {
        background: #06498f;
    }

    .document-processing-modal[data-state="error"] .document-processing-button {
        background: #df4052;
        box-shadow: 0 5px 13px rgba(223, 64, 82, .16);
    }

    .document-processing-modal[data-state="error"] .document-processing-button:hover {
        background: #c93344;
    }

    @media (max-width: 520px) {
        .document-processing-body {
            padding: 26px 20px 21px;
        }

        .document-processing-footer {
            padding: 0 20px 21px;
        }

        .document-processing-button {
            width: 100%;
        }
    }
</style>

<div
    class="document-processing-modal"
    id="documentProcessingModal"
    data-state="loading"
    role="dialog"
    aria-modal="true"
    aria-labelledby="documentProcessingTitle"
    aria-describedby="documentProcessingMessage"
    aria-hidden="true"
>
    <div class="document-processing-dialog">
        <div class="document-processing-body">
            <div class="document-processing-icon loading" id="documentProcessingIcon" aria-hidden="true">
                <span class="document-processing-spinner"></span>
            </div>

            <h2 class="document-processing-title" id="documentProcessingTitle">
                Memproses Dokumen
            </h2>

            <div class="document-processing-message" id="documentProcessingMessage" aria-live="polite">
                Dokumen sedang diproses. Mohon tunggu hingga proses selesai.
            </div>

            <div class="document-processing-details" id="documentProcessingDetails"></div>
        </div>

        <div class="document-processing-footer" id="documentProcessingFooter">
            <button type="button" class="document-processing-button" id="documentProcessingButton">
                OKE
            </button>
        </div>
    </div>
</div>
