@extends($layout)
@section($title)

@section('content')
<div class="container-fluid">

    <div class="card bg-primary text-white overflow-hidden shadow-none">
        <div class="card-body">
            <div class="row justify-content-between align-items-center">
                <div class="col-sm-6">
                    <div class="d-flex align-items-center mb-7">
                        <div class="rounded-circle overflow-hidden me-6 flex-shrink-0">
                            <img src="{{ asset('/') }}images/profile/user-1.jpg" width="45" height="45"
                                class="rounded-circle" alt="{{ Auth::user()->name }}" />
                        </div>
                        <h5 class="fw-semibold fs-5 text-white mb-0 d-flex align-items-center"
                            style="line-height: 45px;">
                            {{ __('Selamat Datang, :name!', ['name' => Auth::user()->name]) }}
                        </h5>
                    </div>
                    <p class="mb-9 opacity-75">
                        You have earned 54% more than last month
                        which is great thing.
                    </p>
                    <button type="button" class="btn btn-light">Check</button>
                </div>
                <div class="col-sm-5">
                    <div class="position-relative mb-n7 text-end">
                        <img src="{{ asset('/') }}images/backgrounds/welcome-bg2.png" alt="modernize-img" class="img-fluid">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6 col-xl-3">
            <div class="card bg-primary-subtle shadow-none">
            <div class="card-body p-4">
                <div class="d-flex align-items-center">
                <h6 class="mb-0">Hari ini</h6>
                </div>
                <div class="d-flex align-items-center justify-content-between mt-4">
                <h3 class="mb-0 fw-semibold fs-7">{{ $data['month']['count'] }}</h3>
                <span class="fw-bold">Rp {{ number_format($data['month']['total'], 0, ',', '.') }}</span>
                </div>
            </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card bg-primary-subtle shadow-none">
            <div class="card-body p-4">
                <div class="d-flex align-items-center">
                <h6 class="mb-0">Bulan ini</h6>
                </div>
                <div class="d-flex align-items-center justify-content-between mt-4">
                <h3 class="mb-0 fw-semibold fs-7">{{ $data['month']['count'] }}</h3>
                <span class="fw-bold">Rp {{ number_format($data['month']['total'], 0, ',', '.') }}</span>
                </div>
            </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card bg-primary-subtle shadow-none">
            <div class="card-body p-4">
                <div class="d-flex align-items-center">
                <h6 class="mb-0">Tahun ini</h6>
                </div>
                <div class="d-flex align-items-center justify-content-between mt-4">
                <h3 class="mb-0 fw-semibold fs-7">{{ $data['month']['count'] }}</h3>
                <span class="fw-bold">Rp {{ number_format($data['month']['total'], 0, ',', '.') }}</span>
                </div>
            </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card bg-primary-subtle shadow-none">
            <div class="card-body p-4">
                <div class="d-flex align-items-center">
                <h6 class="mb-0">Semua transaksi</h6>
                </div>
                <div class="d-flex align-items-center justify-content-between mt-4">
                <h3 class="mb-0 fw-semibold fs-7">{{ $data['month']['count'] }}</h3>
                <span class="fw-bold">Rp {{ number_format($data['month']['total'], 0, ',', '.') }}</span>
                </div>
            </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 d-flex align-items-stretch">
            <div class="card w-100">
                <div class="card-body">
                    <div class="d-block mb-7">
                        <div class="mb-3 mb-sm-0">
                            <h4 class="card-title fw-semibold">Obat Paling Banyak Dijual</h4>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-middle text-nowrap mb-0">
                        <thead>
                            <tr class="text-muted fw-semibold">
                            <th scope="col">Nama</th>
                            <th scope="col">Jumlah Terjual</th>
                            <th scope="col">Nominal</th>
                            </tr>
                        </thead>
                            <tbody class="border-top">
                                @foreach ($data['top_drugs'] as $item)
                                    <tr>
                                        <td>
                                            <p class="mb-0 fs-3">{{ $item->drug->nama_obat ?? '-' }}</p>
                                        </td>
                                        <td>
                                            <p class="mb-0 fs-3">{{ $item->total_qty }}</p>
                                        </td>
                                        <td>
                                            <p class="mb-0 fs-3">Rp {{ number_format($item->total_qty * ($item->drug->harga_obat ?? 0), 0, ',', '.') }}</p>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
