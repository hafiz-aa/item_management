@extends('layouts.app')

@section('title', 'Tambah Change Item Description')

@push('scripts')
<script>
$(document).ready(function() {
    let detailIndex = 0;

    $('#addItem').on('click', function() {
        const row = `
            <tr id="detail-row-${detailIndex}">
                <td>
                    <input type="text" class="form-control form-control-sm" name="details[${detailIndex}][itemd_id_search]" placeholder="Search item..." data-index="${detailIndex}" autocomplete="off">
                    <input type="hidden" name="details[${detailIndex}][itemd_id]" class="itemd-id-input">
                    <input type="hidden" name="details[${detailIndex}][masti_id_old]" class="masti-old-input">
                    <div class="item-search-results position-absolute bg-white border rounded shadow-sm d-none" id="results-${detailIndex}" style="z-index:10; width:350px; max-height:200px; overflow-y:auto;"></div>
                </td>
                <td>
                    <input type="text" class="form-control form-control-sm old-description bg-light" readonly placeholder="Old description">
                </td>
                <td>
                    <select class="form-select form-select-sm" name="details[${detailIndex}][masti_id_new]">
                        <option value="">Pilih Description</option>
                        @foreach($masterItems as $mi)
                            <option value="{{ $mi->masti_id }}">{{ $mi->masti_code }} - {{ $mi->masti_name }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <input type="text" class="form-control form-control-sm" name="details[${detailIndex}][cidd_notes]" placeholder="Notes">
                </td>
                <td>
                    <button type="button" class="btn btn-sm btn-danger remove-item"><i class="bi bi-trash"></i></button>
                </td>
            </tr>
        `;
        $('#detailBody').append(row);
        detailIndex++;
    });

    $(document).on('click', '.remove-item', function() {
        $(this).closest('tr').remove();
    });

    $(document).on('keyup', '[name*="itemd_id_search"]', function() {
        const search = $(this).val();
        const index = $(this).data('index');
        const resultsDiv = $(`#results-${index}`);

        if (search.length < 2) {
            resultsDiv.addClass('d-none').empty();
            return;
        }

        $.getJSON('{{ route("transactions.change-description.search-items") }}', { search: search }, function(items) {
            let html = '';
            items.forEach(function(item) {
                const label = `${item.itemd_code} - ${item.masti_name}${item.itemd_serial_no ? ' (' + item.itemd_serial_no + ')' : ''}`;
                html += `<div class="search-item px-2 py-1" data-itemd="${item.itemd_id}" data-masti-old="${item.masti_id}" data-label="${label}" style="cursor:pointer">${label}</div>`;
            });
            if (html) {
                resultsDiv.html(html).removeClass('d-none');
            } else {
                resultsDiv.addClass('d-none');
            }
        });
    });

    $(document).on('click', '.search-item', function() {
        const container = $(this).closest('td');
        container.find('input[type="hidden"].itemd-id-input').val($(this).data('itemd'));
        container.find('input[type="hidden"].masti-old-input').val($(this).data('masti-old'));
        container.find('input[type="text"]').val($(this).data('label'));
        container.closest('tr').find('.old-description').val($(this).data('label'));
        container.find('.item-search-results').addClass('d-none');
    });

    $(document).on('click', function(e) {
        if (!$(e.target).closest('td').length) {
            $('.item-search-results').addClass('d-none');
        }
    });
});
</script>
@endpush

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="fw-bold mb-0">TAMBAH CHANGE ITEM DESCRIPTION BARU</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('transactions.change-description.store') }}">
            @csrf
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <label for="cidh_date" class="form-label">Tanggal <span class="text-danger">*</span></label>
                    <input type="date" class="form-control @error('cidh_date') is-invalid @enderror" id="cidh_date" name="cidh_date" value="{{ old('cidh_date', date('Y-m-d')) }}" required>
                    @error('cidh_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label for="branch_id" class="form-label">Branch <span class="text-danger">*</span></label>
                    <select class="form-select @error('branch_id') is-invalid @enderror" id="branch_id" name="branch_id" required>
                        <option value="">Pilih Branch</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->branch_id }}" {{ old('branch_id', session('branch_filter')) == $branch->branch_id ? 'selected' : '' }}>
                                {{ $branch->branch_code }} - {{ $branch->branch_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('branch_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label for="cidh_notes" class="form-label">Notes</label>
                    <input type="text" class="form-control @error('cidh_notes') is-invalid @enderror" id="cidh_notes" name="cidh_notes" value="{{ old('cidh_notes') }}">
                    @error('cidh_notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="fw-bold mb-0">Detail Items</h6>
                <button type="button" class="btn btn-sm btn-success" id="addItem"><i class="bi bi-plus-lg"></i> Add Item</button>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th style="width:30%">Item <span class="text-danger">*</span></th>
                            <th style="width:20%">Old Description</th>
                            <th style="width:30%">New Description <span class="text-danger">*</span></th>
                            <th style="width:15%">Notes</th>
                            <th style="width:5%">Action</th>
                        </tr>
                    </thead>
                    <tbody id="detailBody">
                    </tbody>
                </table>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Save</button>
                <a href="{{ route('transactions.change-description.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
