@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Categories</h1>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">Add Category</button>
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
                <th>Type</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="categories-table-body">
            @foreach ($categories as $category)
                <tr id="category-row-{{ $category->id }}">
                    <td>{{ $category->name }}</td>
                    <td>{{ $category->type }}</td>
                    <td>
                        <button class="btn btn-warning btn-sm edit-category-btn" data-id="{{ $category->id }}" data-name="{{ $category->name }}" data-type="{{ $category->type }}" data-bs-toggle="modal" data-bs-target="#editCategoryModal">Edit</button>
                        <button class="btn btn-danger btn-sm delete-category-btn" data-id="{{ $category->id }}">Delete</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Add Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="add-category-form" method="POST" action="{{ route('categories.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCategoryModalLabel">Add Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="name">Name</label>
                        <input type="text" name="name" id="name" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="type">Type</label>
                        <select name="type" id="type" class="form-control" required>
                            <option value="Asset">Asset</option>
                            <option value="Liability">Liability</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add Category</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Edit Category Modal -->
<div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="edit-category-form" method="POST">
            @csrf
            @method('PATCH')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCategoryModalLabel">Edit Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit-category-id">
                    <div class="form-group mb-3">
                        <label for="edit-name">Name</label>
                        <input type="text" name="name" id="edit-name" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="edit-type">Type</label>
                        <select name="type" id="edit-type" class="form-control" required>
                            <option value="Asset">Asset</option>
                            <option value="Liability">Liability</option>
                        </select>
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

<script>
    // Handle Add Category Form Submission
    $('#add-category-form').on('submit', function(e) {
        e.preventDefault();
        const formData = $(this).serialize();
        $.ajax({
            url: '{{ route('categories.store') }}',
            method: 'POST',
            data: formData,
            success: function(response) {
                alert(response.message);
                location.reload(); // Reload categories
            },
            error: function(xhr) {
                alert('Error: ' + xhr.responseJSON.message);
            }
        });
    });

    // Handle Edit Button Click
    $('.edit-category-btn').on('click', function() {
        const id = $(this).data('id');
        const name = $(this).data('name');
        const type = $(this).data('type');

        $('#edit-category-id').val(id);
        $('#edit-name').val(name);
        $('#edit-type').val(type);

        $('#edit-category-form').attr('action', `/categories/${id}`);
    });

    // Handle Edit Category Form Submission
    $('#edit-category-form').on('submit', function(e) {
        e.preventDefault();
        const formData = $(this).serialize();
        const actionUrl = $(this).attr('action');
        $.ajax({
            url: actionUrl,
            method: 'POST',
            data: formData,
            success: function(response) {
                alert(response.message);
                location.reload(); // Reload categories
            },
            error: function(xhr) {
                alert('Error: ' + xhr.responseJSON.message);
            }
        });
    });

    // Handle Delete Category
    $('.delete-category-btn').on('click', function() {
        const id = $(this).data('id');
        if (confirm('Are you sure you want to delete this category?')) {
            $.ajax({
                url: `/categories/${id}`,
                method: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function(response) {
                    alert(response.message);
                    $(`#category-row-${id}`).remove(); // Remove row from table
                },
                error: function(xhr) {
                    alert('Error: ' + xhr.responseJSON.message);
                }
            });
        }
    });
</script>
@endsection
