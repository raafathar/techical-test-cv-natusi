<div class="d-flex justify-content-center align-items-center gap-2">
    <a href="javascript:void(0)" class="text-info detail me-2" data-bs-toggle="detailModal" data-bs-target="#detailModal"
        data-json='{{ $distributor }}' onclick="onDetail(this)">
        <i class="ti ti-eye fs-5"></i>
    </a>
    <a href="javascript:void(0)" class="text-warning edit me-2" data-bs-toggle="modal" data-bs-target="#modal"
        data-json='{{ $distributor }}' onclick="onEdit(this)">
        <i class="ti ti-pencil fs-5"></i>
    </a>
    <a href="javascript:void(0)" class="text-danger delete" data-json='{{ $distributor }}' onclick="onDelete(this)">
        <i class="ti ti-trash fs-5"></i>
    </a>
</div>
