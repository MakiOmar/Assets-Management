<form id="create-category-form">
    @csrf
    <div class="form-group">
        <label for="name">Name</label>
        <input type="text" name="name" id="name" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="type">Type</label>
        <select name="type" id="type" class="form-control" required>
            <option value="Asset">Asset</option>
            <option value="Liability">Liability</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Save</button>
</form>
