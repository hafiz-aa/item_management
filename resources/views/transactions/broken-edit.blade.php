@extends('layouts.app')

@section('title', 'Edit Broken')

@push('scripts')
<script>
$(document).ready(function() {
    let detailIndex = {{ count($broken->details) }};

    $('#addItem').on('click', function() {
        const row = `
            <tr id="detail-row-${detailIndex}">
                <td>
                    <input type="text" class="form-control form-control-sm" name="details[${detailIndex}][itemd_id_search]" placeholder="Search item..." data-index="${detailIndex}" autocomplete="off">
                    <input type="hidden" name="details[${detailIndex}][itemd_id]" class="itemd-id-input">
                    <div class="item-search-results position-absolute bg-white border rounded shadow-sm d-none" id="results-${detailIndex}" style="z-index:10; width:350px; max-height:200px; overflow-y:auto;"></div>
                </td>
                <td>
                    <input type="number" class="form-control form-control-sm" name="details[${detailIndex}][brokd_qty]" min="1" value="1" placeholder="Qty">
                </td>
                <td>
                    <input type="text" class="form-control form-control-sm" name="details[${detailIndex}][brokd_notes]" placeholder="Notes">
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

        $.getJSON('{{ route("transactions.broken.search-items") }}', { search: search }, function(items) {
            let html = '';
            items.forEach(function(item) {
                const label = `${item.itemd_code} - ${item.masti_name}${item.itemd_serial_no ? ' (' + item.itemd_serial_no + ')' : ''}`;
                html += `<div class="search-item px-2 py-1" data-itemd="${item.itemd_id}" data-label="${label}" style="cursor:pointer">${label}</div>`;
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
        container.find('input[type="hidden"]').val($(this).data('itemd'));
        container.find('input[type="text"]').val($(this).data('label'));
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
        <h5 class="fw-bold mb-0">Edit: {{ $broken->brokh_code }}</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('transactions.broken.update', $broken) }}">
            @csrf @method('PUT')
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <label for="brokh_date" class="form-label">Tanggal <span class="text-danger">*</span></label>
                    <input type="date" class="form-control @error('brokh_date') is-invalid @enderror" id="brokh_date" name="brokh_date" value="{{ old('brokh_date', $broken->brokh_date?->format('Y-m-d')) }}" required>
                    @error('brokh_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label for="branch_id" class="form-label">Branch <span class="text-danger">*</span></label>
                    <select class="form-select @error('branch_id') is-invalid @enderror" id="branch_id" name="branch_id" required>
                        <option value="">Pilih Branch</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->branch_id }}" {{ old('branch_id', $broken->branch_id) == $branch->branch_id ? 'selected' : '' }}>
                                {{ $branch->branch_code }} - {{ $branch->branch_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('branch_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label for="brokh_status" class="form-label">Status <span class="text-danger">*</span></label>
                    <select class="form-select @error('brokh_status') is-invalid @enderror" id="brokh_status" name="brokh_status" required>
                        <option value="0" {{ old('brokh_status', $broken->brokh_status) === '0' ? 'selected' : '' }}>Belum</option>
                        <option value="1" {{ old('brokh_status', $broken->brokh_status) === '1' ? 'selected' : '' }}>Proses</option>
                        <option value="2" {{ old('brokh_status', $broken->brokh_status) === '2' ? 'selected' : '' }}>Selesai</option>
                    </select>
                    @error('brokh_status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label for="brokh_notes" class="form-label">Notes</label>
                    <input type="text" class="form-control @error('brokh_notes') is-invalid @enderror" id="brokh_notes" name="brokh_notes" value="{{ old('brokh_notes', $broken->brokh_notes) }}">
                    @error('brokh_notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
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
                        @foreach($broken->details as $idx => $detail)
                            <tr id="detail-row-{{ $idx }}">
                                <td>
                                    <input type="text" class="form-control form-control-sm" name="details[{{ $idx }}][itemd_id_search]" value="{{ $detail->itemDetail->itemd_code ?? '' }}" placeholder="Search item..." data-index="{{ $idx }}" autocomplete="off">
                                    <input type="hidden" name="details[{{ $idx }}][itemd_id]" class="itemd-id-input" value="{{ $detail->itemd_id }}">
                                    <div class="item-search-results position-absolute bg-white border rounded shadow-sm d-none" id="results-{{ $idx }}" style="z-index:10; width:350px; max-height:200px; overflow-y:auto;"></div>
                                </td>
                                <td>
                                    <input type="number" class="form-control form-control-sm" name="details[{{ $idx }}][brokd_qty]" min="1" value="{{ $detail->brokd_qty }}" placeholder="Qty">
                                </td>
                                <td>
                                    <input type="text" class="form-control form-control-sm" name="details[{{ $idx }}][brokd_notes]" value="{{ $detail->brokd_notes }}" placeholder="Notes">
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
                <a href="{{ route('transactions.broken.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
