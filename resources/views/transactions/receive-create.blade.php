@extends('layouts.app')

@section('title', 'Tambah Receive Item')

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
                    <div class="item-search-results position-absolute bg-white border rounded shadow-sm d-none" id="results-${detailIndex}" style="z-index:10; width:300px; max-height:200px; overflow-y:auto;"></div>
                </td>
                <td>
                    <input type="number" class="form-control form-control-sm" name="details[${detailIndex}][rtd_qty]" min="1" value="1" placeholder="Qty">
                </td>
                <td>
                    <select class="form-select form-select-sm" name="details[${detailIndex}][whsl_id_new]">
                        <option value="">Pilih Warehouse</option>
                        @foreach($warehouses as $wh)
                            <option value="{{ $wh->whsl_id }}">{{ $wh->whsl_code }} - {{ $wh->whsl_name }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <input type="text" class="form-control form-control-sm" name="details[${detailIndex}][rtd_notes]" placeholder="Notes">
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

        $.get('{{ route("items.index") }}', { search: search }, function(data) {
            const $data = $(data);
            const rows = $data.find('#itemsTable tbody tr');
            let html = '';
            rows.each(function() {
                const cols = $(this).find('td');
                if (cols.length > 1) {
                    const id = cols.eq(0).text().trim();
                    const code = cols.eq(1).text().trim();
                    const name = cols.eq(2).text().trim();
                    html += `<div class="search-item px-2 py-1" data-id="${id}" style="cursor:pointer">${code} - ${name}</div>`;
                }
            });
            if (html) {
                resultsDiv.html(html).removeClass('d-none');
            } else {
                resultsDiv.addClass('d-none');
            }
        });
    });

    $(document).on('click', '.search-item', function() {
        const id = $(this).data('id');
        const text = $(this).text();
        const container = $(this).closest('td');
        container.find('input[type="hidden"]').val(id);
        container.find('input[type="text"]').val(text);
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
        <h5 class="fw-bold mb-0">TAMBAH RECEIVE BARU</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('transactions.receive.store') }}">
            @csrf
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <label for="rth_date" class="form-label">Tanggal <span class="text-danger">*</span></label>
                    <input type="date" class="form-control @error('rth_date') is-invalid @enderror" id="rth_date" name="rth_date" value="{{ old('rth_date', date('Y-m-d')) }}" required>
                    @error('rth_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label for="tth_id" class="form-label">Transfer Reference <span class="text-danger">*</span></label>
                    <select class="form-select @error('tth_id') is-invalid @enderror" id="tth_id" name="tth_id" required>
                        <option value="">Pilih Transfer</option>
                        @foreach($transfers as $transfer)
                            <option value="{{ $transfer->tth_id }}" {{ old('tth_id') == $transfer->tth_id ? 'selected' : '' }}>
                                {{ $transfer->tth_code }}
                            </option>
                        @endforeach
                    </select>
                    @error('tth_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label for="branch_id" class="form-label">Branch Tujuan <span class="text-danger">*</span></label>
                    <select class="form-select @error('branch_id') is-invalid @enderror" id="branch_id" name="branch_id" required>
                        <option value="">Pilih Branch</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->branch_id }}" {{ old('branch_id') == $branch->branch_id ? 'selected' : '' }}>
                                {{ $branch->branch_code }} - {{ $branch->branch_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('branch_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label for="branch_id_from" class="form-label">Branch Asal <span class="text-danger">*</span></label>
                    <select class="form-select @error('branch_id_from') is-invalid @enderror" id="branch_id_from" name="branch_id_from" required>
                        <option value="">Pilih Branch</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->branch_id }}" {{ old('branch_id_from') == $branch->branch_id ? 'selected' : '' }}>
                                {{ $branch->branch_code }} - {{ $branch->branch_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('branch_id_from') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label for="emp_id_receiver" class="form-label">Penerima <span class="text-danger">*</span></label>
                    <select class="form-select @error('emp_id_receiver') is-invalid @enderror" id="emp_id_receiver" name="emp_id_receiver" required>
                        <option value="">Pilih Employee</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->emp_id }}" {{ old('emp_id_receiver') == $emp->emp_id ? 'selected' : '' }}>
                                {{ $emp->emp_code }} - {{ $emp->emp_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('emp_id_receiver') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label for="rth_ba_no" class="form-label">BA No</label>
                    <input type="text" class="form-control @error('rth_ba_no') is-invalid @enderror" id="rth_ba_no" name="rth_ba_no" value="{{ old('rth_ba_no') }}">
                    @error('rth_ba_no') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label for="rth_po_no" class="form-label">Purchase No</label>
                    <input type="text" class="form-control @error('rth_po_no') is-invalid @enderror" id="rth_po_no" name="rth_po_no" value="{{ old('rth_po_no') }}">
                    @error('rth_po_no') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label for="rth_notes" class="form-label">Notes</label>
                    <input type="text" class="form-control @error('rth_notes') is-invalid @enderror" id="rth_notes" name="rth_notes" value="{{ old('rth_notes') }}">
                    @error('rth_notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
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
                            <th style="width:10%">Qty <span class="text-danger">*</span></th>
                            <th style="width:30%">Warehouse Tujuan <span class="text-danger">*</span></th>
                            <th style="width:25%">Notes</th>
                            <th style="width:5%">Action</th>
                        </tr>
                    </thead>
                    <tbody id="detailBody">
                    </tbody>
                </table>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Save</button>
                <a href="{{ route('transactions.receive.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
