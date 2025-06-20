@extends($layout)
@section('title', 'Distributor | ' . config('app.name'))

@section('content')
<div class="widget-content searchable-container list">
    <div class="card card-body">
        <div class="row">
            <div class="col-md-4 col-xl-3">
                <form class="position-relative" id="search-form">
                    <input type="text" class="form-control product-search ps-5" id="input-search" name="search"
                        placeholder="Cari Distributor..." />
                    <i class="ti ti-search position-absolute top-50 start-0 translate-middle-y fs-6 text-dark ms-3"></i>
                </form>
            </div>
            <div class="col-md-8 col-xl-9 text-end d-flex justify-content-md-end justify-content-center mt-3 mt-md-0">
                <a href="javascript:void(0)" class="btn btn-primary d-flex align-items-center btn-add" data-bs-toggle="modal"
                    data-bs-target="#modal"> <i class="ti ti-plus fs-5"></i> Tambah Distributor
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

{{-- Modal Tambah/Edit Distributor --}}
<div class="modal fade" id="modal" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form method="POST" id="distributorForm" novalidate>
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitleId">Form Distributor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="inputNama" class="form-label fw-semibold">Nama</label>
                        <input type="text" class="form-control" id="inputNama" name="nama" required>
                    </div>
                    <div class="mb-3">
                        <label for="inputAlamat" class="form-label fw-semibold">Alamat</label>
                        <input type="text" class="form-control" id="inputAlamat" name="alamat" required>
                    </div>
                    <div class="mb-3">
                        <label for="inputTelepon" class="form-label fw-semibold">Telepon</label>
                        <input type="text" class="form-control" id="inputTelepon" name="telepon">
                    </div>
                    <div class="mb-3">
                        <label for="inputLatitude" class="form-label fw-semibold">Latitude</label>
                        <input type="number" step="any" class="form-control" id="inputLatitude" name="latitude">
                    </div>
                    <div class="mb-3">
                        <label for="inputLongitude" class="form-label fw-semibold">Longitude</label>
                        <input type="number" step="any" class="form-control" id="inputLongitude" name="longitude">
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

{{-- Modal Detail Distributor --}}
<div class="modal fade" id="detailModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Distributor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <strong>Nama:</strong> <span id="detailNama"></span><br>
                    <strong>Alamat:</strong> <span id="detailAlamat"></span><br>
                    <strong>Telepon:</strong> <span id="detailTelepon"></span><br>
                    <strong>Latitude:</strong> <span id="detailLatitude"></span><br>
                    <strong>Longitude:</strong> <span id="detailLongitude"></span>
                </div>
                <div class="mb-3">
                    <div class="mb-3">
                        <h6>Peta Lokasi Distributor:</h6>
                        <iframe
                            id="distributorMapFrame"
                            width="100%"
                            height="300"
                            style="border:0; border-radius:10px;"
                            allowfullscreen
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{ $dataTable->scripts() }}

<script>
    let modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('modal'));
    let detailModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('detailModal'));
    let map, marker;

    function reloadTable() {
        window.LaravelDataTables['distributor-table'].draw(false);
    }

    function resetForm() {
        $('#distributorForm').trigger('reset');
        $('#distributorForm input').removeClass('is-valid is-invalid').prop('readonly', false);
        $('#distributorForm input[name="_method"]').remove();
        $('#distributorForm').show();
    }

    function hideAddModal() {
        resetForm();
        modal.hide();
    }

    function onCreated() {
        hideAddModal();
        reloadTable();
    }

    function onSubmit() {
        submitForm('#distributorForm', onCreated);
    }

    $(document).ready(function () {
        $('.btn-add').click(function () {
            resetForm();
            $('#modalTitleId').text('Tambah Distributor');
            $('#distributorForm').attr('action', route('dashboard.distributor.distributors.store'))
                                 .attr('method', 'POST');
            modal.show();
        });

        $('#distributorForm').submit(function (e) {
            e.preventDefault();
            onSubmit();
        });
    });

    function onEdit(element) {
        resetForm();
        const json = JSON.parse(element.getAttribute('data-json'));

        $('#modalTitleId').text('Edit Distributor');
        $('#inputNama').val(json.nama);
        $('#inputAlamat').val(json.alamat);
        $('#inputTelepon').val(json.telepon);
        $('#inputLatitude').val(json.latitude);
        $('#inputLongitude').val(json.longitude);

        $('#distributorForm').attr('action', route('dashboard.distributor.distributors.update', json.id))
                             .attr('method', 'POST')
                             .append('<input type="hidden" name="_method" value="PUT">');

        modal.show();
    }

    function onDelete(element) {
        const json = JSON.parse(element.getAttribute('data-json'));
        deleteForm(route('dashboard.distributor.distributors.destroy', json.id), reloadTable);
    }

    function onDetail(element) {
        const formModal = bootstrap.Modal.getInstance(document.getElementById('modal'));
        if (formModal) formModal.hide();

        const json = JSON.parse(element.getAttribute('data-json'));

        $('#detailNama').text(json.nama);
        $('#detailAlamat').text(json.alamat);
        $('#detailTelepon').text(json.telepon || '-');
        $('#detailLatitude').text(json.latitude || '-');
        $('#detailLongitude').text(json.longitude || '-');

        // Atur iframe peta
        const lat = parseFloat(json.latitude);
        const lng = parseFloat(json.longitude);

        if (!isNaN(lat) && !isNaN(lng)) {
            const iframeUrl = `https://maps.google.com/maps?q=${lat},${lng}&z=15&output=embed`;
            $('#distributorMapFrame').attr('src', iframeUrl);
        } else {
            $('#distributorMapFrame').attr('src', '');
        }

        detailModal.show();
    }

    function showMap(lat, lng) {
        if (!lat || !lng) {
            document.getElementById('distributorMap').innerHTML = '<p class="text-danger">Koordinat tidak tersedia.</p>';
            return;
        }

        lat = parseFloat(lat);
        lng = parseFloat(lng);
        const distributorPosition = { lat: lat, lng: lng };

        if (!map) {
            map = new google.maps.Map(document.getElementById('distributorMap'), {
                center: distributorPosition,
                zoom: 15,
            });
            marker = new google.maps.Marker({
                position: distributorPosition,
                map: map,
                title: "Lokasi Distributor"
            });
        } else {
            map.setCenter(distributorPosition);
            marker.setPosition(distributorPosition);
        }
    }

    function route(name, param = null) {
        const base = {
            'dashboard.distributor.distributors.store': '{{ route("dashboard.distributor.distributors.store") }}',
            'dashboard.distributor.distributors.update': '{{ url("dashboard/distributor/distributors") }}',
            'dashboard.distributor.distributors.destroy': '{{ url("dashboard/distributor/distributors") }}',
        };
        return param ? `${base[name]}/${param}` : base[name];
    }

</script>

{{-- Google Maps API --}}
<script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY"></script>
@endpush
