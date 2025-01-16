<form id="edit-category-form">
    @csrf
    @method('PATCH')
    <div class="form-group">
        <label for="name">Name</label>
        <input type="text" name="name" id="name" value="{{ $category->name }}" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="type">Type</label>
        <select name="type" id="type" class="form-control" required>
            <option value="Asset" {{ $category->type == 'Asset' ? 'selected' : '' }}>Asset</option>
            <option value="Liability" {{ $category->type == 'Liability' ? 'selected' : '' }}>Liability</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Update</button>
</form>
