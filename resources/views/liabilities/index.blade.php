@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Liabilities</h1>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addLiabilityModal">Add Liability</button>
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Amount</th>
                <th>Due Date</th>
                <th>Type</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="liabilities-table-body">
            @foreach ($liabilities as $liability)
                <tr id="liability-row-{{ $liability->id }}">
                    <td>{{ $liability->name }}</td>
                    <td>{{ $liability->amount }}</td>
                    <td>{{ $liability->due_date }}</td>
                    <td>{{ $liability->type }}</td>
                    <td>
                        <button class="btn btn-warning btn-sm edit-liability-btn" data-id="{{ $liability->id }}" data-name="{{ $liability->name }}" data-amount="{{ $liability->amount }}" data-due-date="{{ $liability->due_date }}" data-type="{{ $liability->type }}" data-bs-toggle="modal" data-bs-target="#editLiabilityModal">Edit</button>
                        <button class="btn btn-danger btn-sm delete-liability-btn" data-id="{{ $liability->id }}">Delete</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Add Liability Modal -->
<div class="modal fade" id="addLiabilityModal" tabindex="-1" aria-labelledby="addLiabilityModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="add-liability-form" method="POST" action="{{ route('liabilities.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addLiabilityModalLabel">Add Liability</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="name">Name</label>
                        <input type="text" name="name" id="name" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="amount">Amount</label>
                        <input type="number" name="amount" id="amount" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="due_date">Due Date</label>
                        <input type="date" name="due_date" id="due_date" class="form-control">
                    </div>
                    <div class="form-group mb-3">
                        <label for="type">Type</label>
                        <input type="text" name="type" id="type" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add Liability</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Edit Liability Modal -->
<div class="modal fade" id="editLiabilityModal" tabindex="-1" aria-labelledby="editLiabilityModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="edit-liability-form" method="POST">
            @csrf
            @method('PATCH')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editLiabilityModalLabel">Edit Liability</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit-liability-id">
                    <div class="form-group mb-3">
                        <label for="edit-name">Name</label>
                        <input type="text" name="name" id="edit-name" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="edit-amount">Amount</label>
                        <input type="number" name="amount" id="edit-amount" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="edit-due-date">Due Date</label>
                        <input type="date" name="due_date" id="edit-due-date" class="form-control">
                    </div>
                    <div class="form-group mb-3">
                        <label for="edit-type">Type</label>
                        <input type="text" name="type" id="edit-type" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('js')
<script>
    jQuery(document).ready(function ($) {
        // Handle Add Liability Form Submission
        $('#add-liability-form').on('submit', function(e) {
            e.preventDefault();
            const formData = $(this).serialize();
            $.ajax({
                url: '{{ route('liabilities.store') }}',
                method: 'POST',
                data: formData,
                success: function(response) {
                    alert(response.message);
                    location.reload(); // Reload liabilities
                },
                error: function(xhr) {
                    alert('Error: ' + xhr.responseJSON.message);
                }
            });
        });

        // Handle Edit Button Click
        $('.edit-liability-btn').on('click', function() {
            const id = $(this).data('id');
            const name = $(this).data('name');
            const amount = $(this).data('amount');
            const dueDate = $(this).data('due-date');
            const type = $(this).data('type');

            $('#edit-liability-id').val(id);
            $('#edit-name').val(name);
            $('#edit-amount').val(amount);
            $('#edit-due-date').val(dueDate);
            $('#edit-type').val(type);

            $('#edit-liability-form').attr('action', `/liabilities/${id}`);
        });

        // Handle Edit Liability Form Submission
        $('#edit-liability-form').on('submit', function(e) {
            e.preventDefault();
            const formData = $(this).serialize();
            const actionUrl = $(this).attr('action');
            $.ajax({
                url: actionUrl,
                method: 'POST',
                data: formData,
                success: function(response) {
                    alert(response.message);
                    location.reload(); // Reload liabilities
                },
                error: function(xhr) {
                    alert('Error: ' + xhr.responseJSON.message);
                }
            });
        });

        // Handle Delete Liability
        $('.delete-liability-btn').on('click', function() {
            const id = $(this).data('id');
            if (confirm('Are you sure you want to delete this liability?')) {
                $.ajax({
                    url: `/liabilities/${id}`,
                    method: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(response) {
                        alert(response.message);
                        $(`#liability-row-${id}`).remove(); // Remove row from table
                    },
                    error: function(xhr) {
                        alert('Error: ' + xhr.responseJSON.message);
                    }
                });
            }
        });
    });
</script>
@endpush
