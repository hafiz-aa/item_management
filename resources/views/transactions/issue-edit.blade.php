@extends('layouts.app')

@section('title', 'Edit Issue Item')

@push('scripts')
<script>
$(document).ready(function() {
    let detailIndex = {{ count($issue->details) }};

    $('#addItem').on('click', function() {
        const row = `
            <tr id="detail-row-${detailIndex}">
                <td>
                    <input type="text" class="form-control form-control-sm" name="details[${detailIndex}][itemd_id_search]" placeholder="Search item..." data-index="${detailIndex}" autocomplete="off">
                    <input type="hidden" name="details[${detailIndex}][itemd_id]" class="itemd-id-input">
                    <div class="item-search-results position-absolute bg-white border rounded shadow-sm d-none" id="results-${detailIndex}" style="z-index:10; width:300px; max-height:200px; overflow-y:auto;"></div>
                </td>
                <td>
                    <input type="number" class="form-control form-control-sm" name="details[${detailIndex}][issuingd_qty]" min="1" value="1" placeholder="Qty">
                </td>
                <td>
                    <input type="text" class="form-control form-control-sm" name="details[${detailIndex}][issuingd_notes]" placeholder="Notes">
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
        <h5 class="fw-bold mb-0">Edit: {{ $issue->issuingh_code }}</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('transactions.issue.update', $issue) }}">
            @csrf @method('PUT')
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <label for="issuingh_date" class="form-label">Tanggal <span class="text-danger">*</span></label>
                    <input type="date" class="form-control @error('issuingh_date') is-invalid @enderror" id="issuingh_date" name="issuingh_date" value="{{ old('issuingh_date', $issue->issuingh_date?->format('Y-m-d')) }}" required>
                    @error('issuingh_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label for="branch_id" class="form-label">Branch <span class="text-danger">*</span></label>
                    <select class="form-select @error('branch_id') is-invalid @enderror" id="branch_id" name="branch_id" required>
                        <option value="">Pilih Branch</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->branch_id }}" {{ old('branch_id', $issue->branch_id) == $branch->branch_id ? 'selected' : '' }}>
                                {{ $branch->branch_code }} - {{ $branch->branch_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('branch_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label for="issuingh_type" class="form-label">Type <span class="text-danger">*</span></label>
                    <select class="form-select @error('issuingh_type') is-invalid @enderror" id="issuingh_type" name="issuingh_type" required>
                        <option value="0" {{ old('issuingh_type', $issue->issuingh_type) === '0' ? 'selected' : '' }}>Customer</option>
                        <option value="1" {{ old('issuingh_type', $issue->issuingh_type) === '1' ? 'selected' : '' }}>Vendor</option>
                    </select>
                    @error('issuingh_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label for="cust_id" class="form-label">Customer / Vendor</label>
                    <select class="form-select @error('cust_id') is-invalid @enderror" id="cust_id" name="cust_id">
                        <option value="">Pilih</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->cust_id }}" {{ old('cust_id', $issue->cust_id) == $customer->cust_id ? 'selected' : '' }}>
                                {{ $customer->cust_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('cust_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label for="emp_id" class="form-label">PIC <span class="text-danger">*</span></label>
                    <select class="form-select @error('emp_id') is-invalid @enderror" id="emp_id" name="emp_id" required>
                        <option value="">Pilih Employee</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->emp_id }}" {{ old('emp_id', $issue->emp_id) == $emp->emp_id ? 'selected' : '' }}>
                                {{ $emp->emp_code }} - {{ $emp->emp_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('emp_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label for="issuingh_receiver_name" class="form-label">Receiver Name</label>
                    <input type="text" class="form-control @error('issuingh_receiver_name') is-invalid @enderror" id="issuingh_receiver_name" name="issuingh_receiver_name" value="{{ old('issuingh_receiver_name', $issue->issuingh_receiver_name) }}">
                    @error('issuingh_receiver_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label for="issuingh_ba_no" class="form-label">BA No</label>
                    <input type="text" class="form-control @error('issuingh_ba_no') is-invalid @enderror" id="issuingh_ba_no" name="issuingh_ba_no" value="{{ old('issuingh_ba_no', $issue->issuingh_ba_no) }}">
                    @error('issuingh_ba_no') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label for="issuingh_po_no" class="form-label">Purchase No</label>
                    <input type="text" class="form-control @error('issuingh_po_no') is-invalid @enderror" id="issuingh_po_no" name="issuingh_po_no" value="{{ old('issuingh_po_no', $issue->issuingh_po_no) }}">
                    @error('issuingh_po_no') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label for="issuingh_do_no" class="form-label">DO No</label>
                    <input type="text" class="form-control @error('issuingh_do_no') is-invalid @enderror" id="issuingh_do_no" name="issuingh_do_no" value="{{ old('issuingh_do_no', $issue->issuingh_do_no) }}">
                    @error('issuingh_do_no') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label for="issuingh_sent_by" class="form-label">Sent By</label>
                    <input type="text" class="form-control @error('issuingh_sent_by') is-invalid @enderror" id="issuingh_sent_by" name="issuingh_sent_by" value="{{ old('issuingh_sent_by', $issue->issuingh_sent_by) }}">
                    @error('issuingh_sent_by') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label for="issuingh_vehicle_no" class="form-label">Vehicle No</label>
                    <input type="text" class="form-control @error('issuingh_vehicle_no') is-invalid @enderror" id="issuingh_vehicle_no" name="issuingh_vehicle_no" value="{{ old('issuingh_vehicle_no', $issue->issuingh_vehicle_no) }}">
                    @error('issuingh_vehicle_no') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-12">
                    <label for="issuingh_notes" class="form-label">Notes</label>
                    <input type="text" class="form-control @error('issuingh_notes') is-invalid @enderror" id="issuingh_notes" name="issuingh_notes" value="{{ old('issuingh_notes', $issue->issuingh_notes) }}">
                    @error('issuingh_notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
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
                            <th style="width:40%">Item <span class="text-danger">*</span></th>
                            <th style="width:15%">Qty <span class="text-danger">*</span></th>
                            <th style="width:40%">Notes</th>
                            <th style="width:5%">Action</th>
                        </tr>
                    </thead>
                    <tbody id="detailBody">
                        @foreach($issue->details as $idx => $detail)
                            <tr id="detail-row-{{ $idx }}">
                                <td>
                                    <input type="text" class="form-control form-control-sm" name="details[{{ $idx }}][itemd_id_search]" value="{{ $detail->itemDetail->itemd_code ?? '' }}" placeholder="Search item..." data-index="{{ $idx }}" autocomplete="off">
                                    <input type="hidden" name="details[{{ $idx }}][itemd_id]" class="itemd-id-input" value="{{ $detail->itemd_id }}">
                                    <div class="item-search-results position-absolute bg-white border rounded shadow-sm d-none" id="results-{{ $idx }}" style="z-index:10; width:300px; max-height:200px; overflow-y:auto;"></div>
                                </td>
                                <td>
                                    <input type="number" class="form-control form-control-sm" name="details[{{ $idx }}][issuingd_qty]" min="1" value="{{ $detail->issuingd_qty }}" placeholder="Qty">
                                </td>
                                <td>
                                    <input type="text" class="form-control form-control-sm" name="details[{{ $idx }}][issuingd_notes]" value="{{ $detail->issuingd_notes }}" placeholder="Notes">
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
                <a href="{{ route('transactions.issue.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
