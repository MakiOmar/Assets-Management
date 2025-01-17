@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Assets</h1>
        @can('create', App\Models\Asset::class)
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAssetModal">Add Asset</button>
        @endcan
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
                <tr id="asset-row-{{ $asset->id }}" hx-confirm>
                    <td>{{ $asset->name }}</td>
                    <td>{{ $asset->value }}</td>
                    <td>{{ $asset->type }}</td>
                    <td>
                        @can('update', $asset)
                        <button class="btn btn-warning btn-sm edit-asset-btn" data-id="{{ $asset->id }}" data-name="{{ $asset->name }}" data-value="{{ $asset->value }}" data-type="{{ $asset->type }}" data-bs-toggle="modal" data-bs-target="#editAssetModal">Edit</button>
                        @endcan
                        @can('delete', $asset)
                        <button class="btn btn-danger btn-sm delete-asset-btn"
                            hx-delete="/manage/assets/{{ $asset->id }}"
                            hx-headers='{"X-CSRF-TOKEN": "{{ csrf_token() }}"}'
                            hx-confirm="You won’t be able to revert this!"
                            hx-indicator="#maglev-loading-indicator"
                            hx-target="closest tr"
                            hx-swap="outerHTML"
                        >
                        Delete
                        </button>
                        @endcan
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Add Asset Modal -->
<div class="modal fade" id="addAssetModal" tabindex="-1" aria-labelledby="addAssetModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form 
        id="add-asset-form" 
        method="POST" 
        action="{{ route('manage-assets.store') }}" 
        hx-post="{{ route('manage-assets.store') }}" 
        hx-swap="none"  
        hx-indicator="#maglev-loading-indicator"
        hx-no-swal = 'true'
        >
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
        <form id="edit-asset-form" 
        method="POST" 
        hx-patch="" 
        hx-debug="true" 
        hx-swap="none" 
        hx-indicator="#maglev-loading-indicator"
        hx-no-swal = 'true'
        >
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
    document.addEventListener('DOMContentLoaded', function () {
        document.addEventListener('htmx:afterRequest', (event) => {
            var form = event.detail.requestConfig.triggeringEvent.target;
            if (form && form.hasAttribute('hx-post') && form.getAttribute('id') === 'add-asset-form' ) {
                if (event.detail.successful) {
                    Swal.fire({
                        title: 'Success',
                        text: 'Asset added successfully!',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        location.reload(); // Reload assets
                    });
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: 'There was an issue adding the asset.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            }
            if (form && form.hasAttribute('hx-post') && form.getAttribute('id') === 'edit-asset-form' ) {
                
                if (event.detail.successful) {
                    Swal.fire({
                        title: 'Success',
                        text: 'Asset Edited successfully!',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        location.reload(); // Reload assets
                    });
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: 'There was an issue Editing the asset.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            }
        });


    // Handle Edit Button Click
    const editAssetButtons = document.querySelectorAll('.edit-asset-btn');
    editAssetButtons.forEach(button => {
        button.addEventListener('click', function () {
            const id = button.dataset.id;
            const name = button.dataset.name;
            const value = button.dataset.value;
            const type = button.dataset.type;

            document.getElementById('edit-asset-id').value = id;
            document.getElementById('edit-name').value = name;
            document.getElementById('edit-value').value = value;
            document.getElementById('edit-type').value = type;

            document.getElementById('edit-asset-form').setAttribute('action', `/manage/assets/${id}`);
            document.getElementById('edit-asset-form').setAttribute('hx-patch', `/manage/assets/${id}`);
            htmx.process(document.body);
        });
    });
});

</script>

@endpush
