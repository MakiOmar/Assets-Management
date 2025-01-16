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
// Handle Add Liability Form Submission
document.getElementById('add-liability-form').addEventListener('submit', function (e) {
    e.preventDefault();
    const formData = new FormData(this);

    axios.post('{{ route('liabilities.store') }}', formData)
        .then(response => {
            Swal.fire({
                title: 'Success',
                text: response.data.message,
                icon: 'success',
                confirmButtonText: 'OK'
            }).then(() => location.reload()); // Reload liabilities
        })
        .catch(error => {
            Swal.fire({
                title: 'Error',
                text: error.response.data.message,
                icon: 'error',
                confirmButtonText: 'OK'
            });
        });
});

// Handle Edit Button Click
document.querySelectorAll('.edit-liability-btn').forEach(button => {
    button.addEventListener('click', function () {
        const id = button.dataset.id;
        const name = button.dataset.name;
        const amount = button.dataset.amount;
        const dueDate = button.dataset.dueDate;
        const type = button.dataset.type;

        document.getElementById('edit-liability-id').value = id;
        document.getElementById('edit-name').value = name;
        document.getElementById('edit-amount').value = amount;
        document.getElementById('edit-due-date').value = dueDate;
        document.getElementById('edit-type').value = type;

        document.getElementById('edit-liability-form').setAttribute('action', `/liabilities/${id}`);
    });
});

// Handle Edit Liability Form Submission
document.getElementById('edit-liability-form').addEventListener('submit', function (e) {
    e.preventDefault();
    const formData = new FormData(this);
    const actionUrl = this.getAttribute('action');

    axios.post(actionUrl, formData)
        .then(response => {
            Swal.fire({
                title: 'Success',
                text: response.data.message,
                icon: 'success',
                confirmButtonText: 'OK'
            }).then(() => location.reload()); // Reload liabilities
        })
        .catch(error => {
            Swal.fire({
                title: 'Error',
                text: error.response.data.message,
                icon: 'error',
                confirmButtonText: 'OK'
            });
        });
});

// Handle Delete Liability
document.querySelectorAll('.delete-liability-btn').forEach(button => {
    button.addEventListener('click', function () {
        const id = button.dataset.id;
        Swal.fire({
            title: 'Are you sure?',
            text: 'You won’t be able to revert this!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then(result => {
            if (result.isConfirmed) {
                axios.delete(`/liabilities/${id}`, {
                    data: { _token: '{{ csrf_token() }}' }
                })
                    .then(response => {
                        Swal.fire({
                            title: 'Deleted!',
                            text: response.data.message,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            document.getElementById(`liability-row-${id}`).remove(); // Remove row from table
                        });
                    })
                    .catch(error => {
                        Swal.fire({
                            title: 'Error',
                            text: error.response.data.message,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    });
            }
        });
    });
});

</script>
@endpush
