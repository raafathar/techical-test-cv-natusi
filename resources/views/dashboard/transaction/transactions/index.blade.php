@extends($layout)
@section('title', 'Pemesanan Obat | ' . config('app.name'))

@section('content')
<div class="widget-content searchable-container list">
    {{-- Header Pencarian dan Tombol Tambah --}}
    <div class="card card-body">
        <div class="row">
            <div class="col-md-4 col-xl-3">
                <form class="position-relative" id="search-form">
                    <input type="text" class="form-control product-search ps-5" id="input-search" name="search"
                        placeholder="Cari Pemesanan..." />
                    <i class="ti ti-search position-absolute top-50 start-0 translate-middle-y fs-6 text-dark ms-3"></i>
                </form>
            </div>
            <div class="col-md-8 col-xl-9 text-end d-flex justify-content-md-end justify-content-center mt-3 mt-md-0">
                <a href="javascript:void(0)" class="btn btn-primary d-flex align-items-center btn-add" data-bs-toggle="modal"
                    data-bs-target="#modalTransaction"> <i class="ti ti-plus fs-5"></i> Tambah Pemesanan
                </a>
            </div>
        </div>
    </div>

    {{-- Tabel Transaksi --}}
    <div class="card card-body">
        <div class="table-responsive">
            {{ $dataTable->table() }}
        </div>
    </div>
</div>

{{-- MODAL TAMBAH TRANSAKSI --}}
<div class="modal fade" id="modalTransaction" tabindex="-1" data-bs-backdrop="static" role="dialog"
    aria-labelledby="modalTransactionTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <form method="POST" id="transactionForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Transaksi Pemesanan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div id="drug-items">
                        <div class="drug-item border rounded p-3 mb-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label">Obat</label>
                                    <select name="drugs[0][drug_id]" class="form-select" required>
                                        <option value="">-- Pilih Obat --</option>
                                        @foreach ($drugs as $drug)
                                            <option value="{{ $drug->id }}">{{ $drug->nama_obat }} ({{ $drug->kode_obat }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Qty</label>
                                    <input type="number" name="drugs[0][qty]" class="form-control" min="1" required>
                                </div>
                                <div class="col-md-3 d-flex align-items-end">
                                    <button type="button" class="btn btn-danger btn-remove">Hapus</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-success" id="addDrug">+ Tambah Obat</button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4">Simpan Transaksi</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL DETAIL TRANSAKSI --}}
<div class="modal fade" id="modalTransactionDetail" tabindex="-1" data-bs-backdrop="static" role="dialog"
    aria-labelledby="modalTransactionDetailTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Transaksi Pemesanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered mb-0">
                    <thead>
                        <tr>
                            <th>Nama Obat</th>
                            <th>Kode Obat</th>
                            <th>Qty</th>
                            <th>Harga Satuan</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody id="detail-items">
                        <tr>
                            <td colspan="5" class="text-center">Memuat data...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" id="btnPrintDetail" data-id="">Cetak PDF</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{ $dataTable->scripts() }}

<script>
    let index = 1;

    $('#addDrug').click(function () {
        let html = `
        <div class="drug-item border rounded p-3 mb-3">
            <div class="row">
                <div class="col-md-6">
                    <label class="form-label">Obat</label>
                    <select name="drugs[${index}][drug_id]" class="form-select" required>
                        <option value="">-- Pilih Obat --</option>
                        @foreach ($drugs as $drug)
                            <option value="{{ $drug->id }}">{{ $drug->nama_obat }} ({{ $drug->kode_obat }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Qty</label>
                    <input type="number" name="drugs[${index}][qty]" class="form-control" min="1" required>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="button" class="btn btn-danger btn-remove">Hapus</button>
                </div>
            </div>
        </div>`;
        $('#drug-items').append(html);
        index++;
    });

    $(document).on('click', '.btn-remove', function () {
        $(this).closest('.drug-item').remove();
    });

    $('#transactionForm').submit(function(e) {
        e.preventDefault();
        submitForm('#transactionForm', function() {
            $('#modalTransaction').modal('hide');
            window.LaravelDataTables['transaction-table'].draw(false);
        });
    });

    function loadTransactionDetail(transactionId) {
        $('#detail-items').html(`<tr><td colspan="5" class="text-center">Memuat data...</td></tr>`);

        $.get(`/dashboard/transaction/transactions/${transactionId}`, function(response) {
            if (response.success) {
                let rows = '';
                response.details.forEach(item => {
                    rows += `
                        <tr>
                            <td>${item.nama_obat}</td>
                            <td>${item.kode_obat}</td>
                            <td>${item.qty}</td>
                            <td>Rp ${parseFloat(item.price).toLocaleString('id-ID', {minimumFractionDigits: 0})}</td>
                            <td>Rp ${(item.qty * item.price).toLocaleString('id-ID', {minimumFractionDigits: 0})}</td>
                        </tr>
                    `;
                });
                $('#detail-items').html(rows);
                $('#btnPrintDetail').data('id', transactionId);
                $('#modalTransactionDetail').modal('show');
            } else {
                $('#detail-items').html(`<tr><td colspan="5" class="text-danger text-center">Gagal memuat data.</td></tr>`);
            }
        });
    }

    $('#btnPrintDetail').on('click', function () {
        const id = $(this).data('id');
        if (id) {
            window.open(`{{ route('dashboard.transaction.transactions.print', '') }}/${id}`, '_blank');
        }
    });
    
</script>
@endpush
