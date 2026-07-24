@extends('layouts.app')

@section('title', 'Edit Transfer Item')

@push('scripts')
<script>
$(document).ready(function() {
    let detailIndex = {{ count($transfer->details) }};

    $('#addItem').on('click', function() {
        const row = `
            <tr id="detail-row-${detailIndex}">
                <td>
                    <input type="text" class="form-control form-control-sm" name="details[${detailIndex}][itemd_id_search]" placeholder="Search item..." data-index="${detailIndex}" autocomplete="off">
                    <input type="hidden" name="details[${detailIndex}][itemd_id]" class="itemd-id-input">
                    <div class="item-search-results position-absolute bg-white border rounded shadow-sm d-none" id="results-${detailIndex}" style="z-index:10; width:300px; max-height:200px; overflow-y:auto;"></div>
                </td>
                <td>
                    <select class="form-select form-select-sm" name="details[${detailIndex}][whsl_id_from][]" multiple>
                        @foreach($warehouses as $wh)
                            <option value="{{ $wh->whsl_id }}">{{ $wh->whsl_code }} - {{ $wh->whsl_name }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <input type="text" class="form-control form-control-sm" name="details[${detailIndex}][ttd_notes]" placeholder="Notes">
                </td>
                <td>
                    <button type="button" class="btn btn-sm btn-danger remove-item" data-index="${detailIndex}"><i class="bi bi-trash"></i></button>
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
        <h5 class="fw-bold mb-0">Edit: {{ $transfer->tth_code }}</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('transfers.update', $transfer) }}">
            @csrf @method('PUT')
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <label for="tth_date" class="form-label">Tanggal <span class="text-danger">*</span></label>
                    <input type="date" class="form-control @error('tth_date') is-invalid @enderror" id="tth_date" name="tth_date" value="{{ old('tth_date', $transfer->tth_date?->format('Y-m-d')) }}" required>
                    @error('tth_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label for="branch_id" class="form-label">Branch Asal <span class="text-danger">*</span></label>
                    <select class="form-select @error('branch_id') is-invalid @enderror" id="branch_id" name="branch_id" required>
                        <option value="">Pilih Branch</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->branch_id }}" {{ old('branch_id', $transfer->branch_id) == $branch->branch_id ? 'selected' : '' }}>
                                {{ $branch->branch_code }} - {{ $branch->branch_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('branch_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label for="branch_id_to" class="form-label">Branch Tujuan <span class="text-danger">*</span></label>
                    <select class="form-select @error('branch_id_to') is-invalid @enderror" id="branch_id_to" name="branch_id_to" required>
                        <option value="">Pilih Branch</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->branch_id }}" {{ old('branch_id_to', $transfer->branch_id_to) == $branch->branch_id ? 'selected' : '' }}>
                                {{ $branch->branch_code }} - {{ $branch->branch_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('branch_id_to') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label for="emp_id_sender" class="form-label">Pengirim <span class="text-danger">*</span></label>
                    <select class="form-select @error('emp_id_sender') is-invalid @enderror" id="emp_id_sender" name="emp_id_sender" required>
                        <option value="">Pilih Employee</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->emp_id }}" {{ old('emp_id_sender', $transfer->emp_id_sender) == $emp->emp_id ? 'selected' : '' }}>
                                {{ $emp->emp_code }} - {{ $emp->emp_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('emp_id_sender') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label for="tth_status" class="form-label">Status <span class="text-danger">*</span></label>
                    <select class="form-select @error('tth_status') is-invalid @enderror" id="tth_status" name="tth_status" required>
                        <option value="0" {{ old('tth_status', $transfer->tth_status) === '0' ? 'selected' : '' }}>Draft</option>
                        <option value="1" {{ old('tth_status', $transfer->tth_status) === '1' ? 'selected' : '' }}>Proses</option>
                        <option value="2" {{ old('tth_status', $transfer->tth_status) === '2' ? 'selected' : '' }}>Selesai</option>
                    </select>
                    @error('tth_status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-9">
                    <label for="tth_notes" class="form-label">Notes</label>
                    <input type="text" class="form-control @error('tth_notes') is-invalid @enderror" id="tth_notes" name="tth_notes" value="{{ old('tth_notes', $transfer->tth_notes) }}">
                    @error('tth_notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
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
                            <th style="width:35%">Warehouse Asal <span class="text-danger">*</span></th>
                            <th style="width:20%">Notes</th>
                            <th style="width:5%">Action</th>
                        </tr>
                    </thead>
                    <tbody id="detailBody">
                        @foreach($transfer->details as $idx => $detail)
                            <tr id="detail-row-{{ $idx }}">
                                <td>
                                    <input type="text" class="form-control form-control-sm" name="details[{{ $idx }}][itemd_id_search]" value="{{ $detail->itemDetail->itemd_code ?? '' }}" placeholder="Search item..." data-index="{{ $idx }}" autocomplete="off">
                                    <input type="hidden" name="details[{{ $idx }}][itemd_id]" class="itemd-id-input" value="{{ $detail->itemd_id }}">
                                    <div class="item-search-results position-absolute bg-white border rounded shadow-sm d-none" id="results-{{ $idx }}" style="z-index:10; width:300px; max-height:200px; overflow-y:auto;"></div>
                                </td>
                                <td>
                                    <select class="form-select form-select-sm" name="details[{{ $idx }}][whsl_id_from][]" multiple>
                                        @foreach($warehouses as $wh)
                                            <option value="{{ $wh->whsl_id }}" {{ in_array($wh->whsl_id, [$detail->whsl_id_from]) ? 'selected' : '' }}>
                                                {{ $wh->whsl_code }} - {{ $wh->whsl_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="text" class="form-control form-control-sm" name="details[{{ $idx }}][ttd_notes]" value="{{ $detail->ttd_notes }}" placeholder="Notes">
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
                <a href="{{ route('transfers.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
