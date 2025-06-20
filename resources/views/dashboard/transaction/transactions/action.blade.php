<div class="action-btn d-flex justify-content-center align-items-center gap-2">
    {{-- Tombol Lihat Detail --}}
    <a href="javascript:void(0)" class="text-dark"
        onclick="loadTransactionDetail('{{ $transaction->id }}')" title="Lihat Detail">
        <i class="ti ti-eye fs-5"></i>
    </a>

    {{-- Tombol Cetak PDF --}}
    <a href="{{ route('dashboard.transaction.transactions.print', $transaction->id) }}"
        target="_blank" class="text-success" title="Cetak PDF">
        <i class="ti ti-printer fs-5"></i>
    </a>
</div>
