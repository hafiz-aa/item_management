@extends('layouts.app')

@section('title', 'Edit Return Item')

@push('scripts')
<script>
$(document).ready(function() {
    let detailIndex = {{ count($return->details) }};

    $('#addItem').on('click', function() {
        const row = `
            <tr id="detail-row-${detailIndex}">
                <td>
                    <input type="text" class="form-control form-control-sm" name="details[${detailIndex}][issuingd_id_search]" placeholder="Search item..." data-index="${detailIndex}" autocomplete="off">
                    <input type="hidden" name="details[${detailIndex}][issuingd_id]" class="issuingd-id-input">
                    <div class="item-search-results position-absolute bg-white border rounded shadow-sm d-none" id="results-${detailIndex}" style="z-index:10; width:300px; max-height:200px; overflow-y:auto;"></div>
                </td>
                <td>
                    <input type="number" class="form-control form-control-sm" name="details[${detailIndex}][retd_qty]" min="0" step="0.01" value="1" placeholder="Qty">
                </td>
                <td>
                    <select class="form-select form-select-sm" name="details[${detailIndex}][whsl_id]">
                        <option value="">Pilih Warehouse</option>
                        @foreach($warehouses as $wh)
                            <option value="{{ $wh->whsl_id }}">{{ $wh->whsl_code }} - {{ $wh->whsl_name }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <input type="text" class="form-control form-control-sm" name="details[${detailIndex}][retd_notes]" placeholder="Notes">
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

    $(document).on('keyup', '[name*="issuingd_id_search"]', function() {
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
        <h5 class="fw-bold mb-0">Edit: {{ $return->reth_code }}</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('transactions.return.update', $return) }}">
            @csrf @method('PUT')
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <label for="reth_date" class="form-label">Tanggal <span class="text-danger">*</span></label>
                    <input type="date" class="form-control @error('reth_date') is-invalid @enderror" id="reth_date" name="reth_date" value="{{ old('reth_date', $return->reth_date?->format('Y-m-d')) }}" required>
                    @error('reth_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label for="issuingh_id" class="form-label">Ref Issue <span class="text-danger">*</span></label>
                    <select class="form-select @error('issuingh_id') is-invalid @enderror" id="issuingh_id" name="issuingh_id" required>
                        <option value="">Pilih Issue</option>
                        @foreach($issuings as $issuing)
                            <option value="{{ $issuing->issuingh_id }}" {{ old('issuingh_id', $return->issuingh_id) == $issuing->issuingh_id ? 'selected' : '' }}>
                                {{ $issuing->issuingh_code }}
                            </option>
                        @endforeach
                    </select>
                    @error('issuingh_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label for="branch_id" class="form-label">Branch <span class="text-danger">*</span></label>
                    <select class="form-select @error('branch_id') is-invalid @enderror" id="branch_id" name="branch_id" required>
                        <option value="">Pilih Branch</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->branch_id }}" {{ old('branch_id', $return->branch_id) == $branch->branch_id ? 'selected' : '' }}>
                                {{ $branch->branch_code }} - {{ $branch->branch_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('branch_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label for="emp_id_receiver" class="form-label">Penerima <span class="text-danger">*</span></label>
                    <select class="form-select @error('emp_id_receiver') is-invalid @enderror" id="emp_id_receiver" name="emp_id_receiver" required>
                        <option value="">Pilih Employee</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->emp_id }}" {{ old('emp_id_receiver', $return->emp_id_receiver) == $emp->emp_id ? 'selected' : '' }}>
                                {{ $emp->emp_code }} - {{ $emp->emp_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('emp_id_receiver') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label for="whsl_id" class="form-label">Warehouse</label>
                    <select class="form-select @error('whsl_id') is-invalid @enderror" id="whsl_id" name="whsl_id">
                        <option value="">Pilih Warehouse</option>
                        @foreach($warehouses as $wh)
                            <option value="{{ $wh->whsl_id }}" {{ old('whsl_id', $return->whsl_id) == $wh->whsl_id ? 'selected' : '' }}>
                                {{ $wh->whsl_code }} - {{ $wh->whsl_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('whsl_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label for="reth_ref_no" class="form-label">Ref No</label>
                    <input type="text" class="form-control @error('reth_ref_no') is-invalid @enderror" id="reth_ref_no" name="reth_ref_no" value="{{ old('reth_ref_no', $return->reth_ref_no) }}">
                    @error('reth_ref_no') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label for="reth_ba_no" class="form-label">BA No</label>
                    <input type="text" class="form-control @error('reth_ba_no') is-invalid @enderror" id="reth_ba_no" name="reth_ba_no" value="{{ old('reth_ba_no', $return->reth_ba_no) }}">
                    @error('reth_ba_no') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label for="reth_po_no" class="form-label">Purchase No</label>
                    <input type="text" class="form-control @error('reth_po_no') is-invalid @enderror" id="reth_po_no" name="reth_po_no" value="{{ old('reth_po_no', $return->reth_po_no) }}">
                    @error('reth_po_no') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-12">
                    <label for="reth_notes" class="form-label">Notes</label>
                    <input type="text" class="form-control @error('reth_notes') is-invalid @enderror" id="reth_notes" name="reth_notes" value="{{ old('reth_notes', $return->reth_notes) }}">
                    @error('reth_notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
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
                            <th style="width:30%">Warehouse <span class="text-danger">*</span></th>
                            <th style="width:25%">Notes</th>
                            <th style="width:5%">Action</th>
                        </tr>
                    </thead>
                    <tbody id="detailBody">
                        @foreach($return->details as $idx => $detail)
                            <tr id="detail-row-{{ $idx }}">
                                <td>
                                    <input type="text" class="form-control form-control-sm" name="details[{{ $idx }}][issuingd_id_search]" value="{{ $detail->issuingDetail->itemDetail->itemd_code ?? '' }}" placeholder="Search item..." data-index="{{ $idx }}" autocomplete="off">
                                    <input type="hidden" name="details[{{ $idx }}][issuingd_id]" class="issuingd-id-input" value="{{ $detail->issuingd_id }}">
                                    <div class="item-search-results position-absolute bg-white border rounded shadow-sm d-none" id="results-{{ $idx }}" style="z-index:10; width:300px; max-height:200px; overflow-y:auto;"></div>
                                </td>
                                <td>
                                    <input type="number" class="form-control form-control-sm" name="details[{{ $idx }}][retd_qty]" min="0" step="0.01" value="{{ $detail->retd_qty }}" placeholder="Qty">
                                </td>
                                <td>
                                    <select class="form-select form-select-sm" name="details[{{ $idx }}][whsl_id]">
                                        <option value="">Pilih Warehouse</option>
                                        @foreach($warehouses as $wh)
                                            <option value="{{ $wh->whsl_id }}" {{ $detail->whsl_id == $wh->whsl_id ? 'selected' : '' }}>
                                                {{ $wh->whsl_code }} - {{ $wh->whsl_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="text" class="form-control form-control-sm" name="details[{{ $idx }}][retd_notes]" value="{{ $detail->retd_notes }}" placeholder="Notes">
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-danger remove-item"><i class="bi bi-trash"></i></button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Update</button>
                <a href="{{ route('transactions.return.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
