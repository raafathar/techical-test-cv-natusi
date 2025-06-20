@extends($layout)
@section('title', 'Obat | ' . config('app.name'))

{{-- @push('css')
    <link rel="stylesheet" href="{{ asset('/') }}libs/prismjs/themes/prism-okaidia.min.css">
<link rel="stylesheet" href="{{ asset('/') }}libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css">
@endpush --}}

@section('content')

<div class="widget-content searchable-container list">
    <div class="card card-body">
        <div class="row">
            <div class="col-md-4 col-xl-3">
                <form class="position-relative" id="search-form">
                    <input type="text" class="form-control product-search ps-5" id="input-search" name="search"
                        placeholder="Cari Obat..." />
                    <i class="ti ti-search position-absolute top-50 start-0 translate-middle-y fs-6 text-dark ms-3"></i>
                </form>
            </div>
            <div class="col-md-8 col-xl-9 text-end d-flex justify-content-md-end justify-content-center mt-3 mt-md-0">
                <a href="javascript:void(0)" class="btn btn-primary d-flex align-items-center btn-add" data-bs-toggle="modal"
                    data-bs-target="#modal"> <i class="ti ti-plus fs-5"></i> Tambah Obat
                </a>
            </div>
        </div>

    </div>

    <div class="card card-body">
        <div class="row">
            <div class="table-responsive">
                {{ $dataTable->table() }}
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="true" role="dialog"
    aria-labelledby="modalTitleId" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <form method="POST" id="brandForm" novalidate enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitleId"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="addEdit-brand-box">
                        <div class="addEdit-brand-content">
                            <div class="row">
                                <div class="col-lg-12 d-flex align-items-stretch">
                                    <div class="card w-100 position-relative overflow-hidden">
                                        <div class="card-body p-4">
                                            <h5 class="card-title fw-semibold">Detail</h5>
                                            <p class="card-subtitle mb-4" id="detail-subtitle"></p>
                                            <div class="mb-4">
                                                <label for="kodeObat" class="form-label fw-semibold">Kode Obat</label>
                                                <input type="text" class="form-control" id="kodeObat" name="kode_obat" required>
                                            </div>

                                            <div class="mb-4">
                                                <label for="namaObat" class="form-label fw-semibold">Nama Obat</label>
                                                <input type="text" class="form-control" id="namaObat" name="nama_obat" required>
                                            </div>

                                            <div class="mb-4">
                                                <label for="satuanObat" class="form-label fw-semibold">Satuan Obat</label>
                                                <input type="text" class="form-control" id="satuanObat" name="satuan_obat" required>
                                            </div>

                                            <div class="mb-4">
                                                <label for="hargaObat" class="form-label fw-semibold">Harga Obat (Rp)</label>
                                                <input type="number" step="0.01" class="form-control" id="hargaObat" name="harga_obat" required>
                                            </div>

                                            <div class="mb-4">
                                                <label for="stockObat" class="form-label fw-semibold">Stok Obat</label>
                                                <input type="number" class="form-control" id="stockObat" name="stock_obat" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kembali</button>
                    <button type="submit" onclick="onSubmit()" class="btn btn-primary px-4">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
{{ $dataTable->scripts() }}

<script>
    let modal = null;

    function reloadTable() {
        window.LaravelDataTables['drug-table'].draw(false);
    }

    function onCreated() {
            hideAddModal();
            reloadTable();
        }
    
    function hideAddModal() {
        $('#brandForm').trigger('reset');
        modal.hide();
    }

    $('#brandForm').submit(function(e) {
        e.preventDefault();
    });

    function onSubmit() {
        submitForm('#brandForm', onCreated);
    }

    function resetForm() {
        $('#brandForm').trigger('reset');
        $('#brandForm input[name]').removeClass('is-invalid', 'is-valid');
        $('#brandForm input[email]').removeClass('is-invalid', 'is-valid');
        $('#brandForm input[address]').removeClass('is-invalid', 'is-valid');
    }

    $(document).ready(function() {
        modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('modal'));
        $('.btn-add').click(function() {
            resetForm();
            $('#modalTitleId').text('Tambah Driver');
            $('#detail-subtitle').text('Tambah detail driver dibawah ini');
            
            $('#inputName').val('');
            $('#inputEmail').val('');
            $('#inputAddress').val('');

            $('#brandForm').attr('action', route('dashboard.drug.drugs.store'))
                .attr('method', 'POST');
        });
    });

    function onEdit(element) {
        resetForm();
        json = element.getAttribute('data-json');
        json = JSON.parse(json);
        $('#json-viewer').jsonViewer(json);
        $('#modalTitleId').text('Edit Obat');
        $('#detail-subtitle').text('Edit detail obat dibawah ini');

        
        $('input[name="kode_obat"]').val(json.kode_obat).trigger('change');
        $('input[name="nama_obat"]').val(json.nama_obat).trigger('change');
        $('input[name="satuan_obat"]').val(json.satuan_obat).trigger('change');
        $('input[name="harga_obat"]').val(json.harga_obat).trigger('change');
        $('input[name="stock_obat"]').val(json.stock_obat).trigger('change');

        $('#brandForm').attr('action', route('dashboard.drug.drugs.update', json.id))
            .attr('method', 'PUT');
    }

    function onDelete(element) {
        json = element.getAttribute('data-json');
        json = JSON.parse(json);
        deleteForm(route('dashboard.drug.drugs.destroy', json.id), reloadTable);
    }

</script>
@endpush
