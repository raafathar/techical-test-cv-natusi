<div class="action-btn">
    <a href="javascript:void(0)" class="text-warning edit" data-bs-toggle="modal" data-bs-target="#modal"
    data-json='{{ $drug }}' onclick="onEdit(this)">
        <i class="ti ti-pencil fs-5"></i>
    </a>
    <a href="javascript:void(0)" class="text-dark delete ms-2" data-json='{{ $drug }}' onclick="onDelete(this)">
        <i class="ti ti-trash fs-5"></i>
    </a>
</div>