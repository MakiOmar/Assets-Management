@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Assets</h1>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAssetModal">Add Asset</button>
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
                <th>Value</th>
                <th>Type</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="assets-table-body">
            @foreach ($assets as $asset)
                <tr id="asset-row-{{ $asset->id }}">
                    <td>{{ $asset->name }}</td>
                    <td>{{ $asset->value }}</td>
                    <td>{{ $asset->type }}</td>
                    <td>
                        <button class="btn btn-warning btn-sm edit-asset-btn" data-id="{{ $asset->id }}" data-name="{{ $asset->name }}" data-value="{{ $asset->value }}" data-type="{{ $asset->type }}" data-bs-toggle="modal" data-bs-target="#editAssetModal">Edit</button>
                        <button class="btn btn-danger btn-sm delete-asset-btn" data-id="{{ $asset->id }}">Delete</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Add Asset Modal -->
<div class="modal fade" id="addAssetModal" tabindex="-1" aria-labelledby="addAssetModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="add-asset-form" method="POST" action="{{ route('admin-assets.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addAssetModalLabel">Add Asset</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="name">Name</label>
                        <input type="text" name="name" id="name" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="value">Value</label>
                        <input type="number" name="value" id="value" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="type">Type</label>
                        <input type="text" name="type" id="type" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add Asset</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Edit Asset Modal -->
<div class="modal fade" id="editAssetModal" tabindex="-1" aria-labelledby="editAssetModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="edit-asset-form" method="POST">
            @csrf
            @method('PATCH')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editAssetModalLabel">Edit Asset</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit-asset-id">
                    <div class="form-group mb-3">
                        <label for="edit-name">Name</label>
                        <input type="text" name="name" id="edit-name" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="edit-value">Value</label>
                        <input type="number" name="value" id="edit-value" class="form-control" required>
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
        // Handle Add Asset Form Submission
        $('#add-asset-form').on('submit', function(e) {
            e.preventDefault();
            const formData = $(this).serialize();
            $.ajax({
                url: '{{ route('admin-assets.store') }}',
                method: 'POST',
                data: formData,
                success: function(response) {
                    alert(response.message);
                    location.reload(); // Reload assets
                },
                error: function(xhr) {
                    alert('Error: ' + xhr.responseJSON.message);
                }
            });
        });

        // Handle Edit Button Click
        $('.edit-asset-btn').on('click', function() {
            const id = $(this).data('id');
            const name = $(this).data('name');
            const value = $(this).data('value');
            const type = $(this).data('type');

            $('#edit-asset-id').val(id);
            $('#edit-name').val(name);
            $('#edit-value').val(value);
            $('#edit-type').val(type);

            $('#edit-asset-form').attr('action', `/manage/assets/${id}`);
        });

        // Handle Edit Asset Form Submission
        $('#edit-asset-form').on('submit', function(e) {
            e.preventDefault();
            const formData = $(this).serialize();
            const actionUrl = $(this).attr('action');
            $.ajax({
                url: actionUrl,
                method: 'POST',
                data: formData,
                success: function(response) {
                    alert(response.message);
                    location.reload(); // Reload assets
                },
                error: function(xhr) {
                    alert('Error: ' + xhr.responseJSON.message);
                }
            });
        });

        // Handle Delete Asset
        $('.delete-asset-btn').on('click', function() {
            const id = $(this).data('id');
            if (confirm('Are you sure you want to delete this asset?')) {
                $.ajax({
                    url: `/manage/assets/${id}`,
                    method: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(response) {
                        alert(response.message);
                        $(`#asset-row-${id}`).remove(); // Remove row from table
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
