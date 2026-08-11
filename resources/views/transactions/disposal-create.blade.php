@extends('layouts.app')

@section('title', 'Tambah Disposal')

@push('scripts')
<script>
$(document).ready(function() {
    let detailIndex = 0;

    function toggleSource() {
        const source = $('#disph_sources').val();
        if (source === '1') {
            $('#brokh_id_wrapper').addClass('d-none');
            $('#brokh_id').val('');
        } else {
            $('#brokh_id_wrapper').removeClass('d-none');
        }
    }

    $('#disph_sources').on('change', toggleSource);
    toggleSource();

    $('#addItem').on('click', function() {
        const row = `
            <tr id="detail-row-${detailIndex}">
                <td>
                    <input type="text" class="form-control form-control-sm" name="details[${detailIndex}][itemd_id_search]" placeholder="Search item..." data-index="${detailIndex}" autocomplete="off">
                    <input type="hidden" name="details[${detailIndex}][itemd_id]" class="itemd-id-input">
                    <div class="item-search-results position-absolute bg-white border rounded shadow-sm d-none" id="results-${detailIndex}" style="z-index:10; width:350px; max-height:200px; overflow-y:auto;"></div>
                </td>
                <td>
                    <input type="number" class="form-control form-control-sm" name="details[${detailIndex}][dispd_qty]" min="1" value="1" placeholder="Qty">
                </td>
                <td>
                    <input type="text" class="form-control form-control-sm" name="details[${detailIndex}][dispd_notes]" placeholder="Notes">
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

        $.getJSON('{{ route("transactions.disposal.search-items") }}', { search: search }, function(items) {
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
        <h5 class="fw-bold mb-0">TAMBAH DISPOSAL BARU</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('transactions.disposal.store') }}">
            @csrf
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <label for="disph_date" class="form-label">Tanggal <span class="text-danger">*</span></label>
                    <input type="date" class="form-control @error('disph_date') is-invalid @enderror" id="disph_date" name="disph_date" value="{{ old('disph_date', date('Y-m-d')) }}" required>
                    @error('disph_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
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
                <div class="col-md-3">
                    <label for="disph_sources" class="form-label">Transaction Source <span class="text-danger">*</span></label>
                    <select class="form-select @error('disph_sources') is-invalid @enderror" id="disph_sources" name="disph_sources" required>
                        <option value="0" {{ old('disph_sources', '0') === '0' ? 'selected' : '' }}>Broken</option>
                        <option value="1" {{ old('disph_sources') === '1' ? 'selected' : '' }}>Change Description</option>
                    </select>
                    @error('disph_sources') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3" id="brokh_id_wrapper">
                    <label for="brokh_id" class="form-label">Ref Broken No</label>
                    <select class="form-select @error('brokh_id') is-invalid @enderror" id="brokh_id" name="brokh_id">
                        <option value="">Pilih Broken</option>
                        @foreach($brokenHeaders as $bh)
                            <option value="{{ $bh->brokh_id }}" {{ old('brokh_id') == $bh->brokh_id ? 'selected' : '' }}>
                                {{ $bh->brokh_code }}
                            </option>
                        @endforeach
                    </select>
                    @error('brokh_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label for="cust_id" class="form-label">Customer <span class="text-danger">*</span></label>
                    <select class="form-select @error('cust_id') is-invalid @enderror" id="cust_id" name="cust_id" required>
                        <option value="">Pilih Customer</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->cust_id }}" {{ old('cust_id') == $customer->cust_id ? 'selected' : '' }}>
                                {{ $customer->cust_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('cust_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label for="emp_id_dispossed_by" class="form-label">Disposed By</label>
                    <select class="form-select @error('emp_id_dispossed_by') is-invalid @enderror" id="emp_id_dispossed_by" name="emp_id_dispossed_by">
                        <option value="">Pilih Employee</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->emp_id }}" {{ old('emp_id_dispossed_by') == $emp->emp_id ? 'selected' : '' }}>
                                {{ $emp->emp_code }} - {{ $emp->emp_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('emp_id_dispossed_by') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label for="disph_reason" class="form-label">Reason</label>
                    <input type="text" class="form-control @error('disph_reason') is-invalid @enderror" id="disph_reason" name="disph_reason" value="{{ old('disph_reason') }}">
                    @error('disph_reason') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-12">
                    <label for="disph_notes" class="form-label">Notes</label>
                    <input type="text" class="form-control @error('disph_notes') is-invalid @enderror" id="disph_notes" name="disph_notes" value="{{ old('disph_notes') }}">
                    @error('disph_notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
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
                    </tbody>
                </table>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Save</button>
                <a href="{{ route('transactions.disposal.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
