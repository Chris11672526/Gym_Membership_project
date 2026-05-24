@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>Members Management</h1>
        </div>
        <div class="col-md-4 text-end">
            <button class="btn btn-primary" id="addMemberBtn" data-bs-toggle="modal" data-bs-target="#memberModal">
                Add Member
            </button>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <table id="membersTable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal for Add/Edit -->
<div class="modal fade" id="memberModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Member</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="memberForm">
                @csrf
                <input type="hidden" id="memberId">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">First Name *</label>
                        <input type="text" class="form-control" name="first_name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Last Name *</label>
                        <input type="text" class="form-control" name="last_name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Gender *</label>
                        <select class="form-control" name="gender" required>
                            <option>Male</option>
                            <option>Female</option>
                            <option>Other</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Birthdate *</label>
                        <input type="date" class="form-control" name="birthdate" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone *</label>
                        <input type="tel" class="form-control" name="phone" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Branch *</label>
                        <select class="form-control" name="branch_id" required>
                            @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status *</label>
                        <select class="form-control" name="status" required>
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                            <option value="Suspended">Suspended</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
$(function() {
    let table = $('#membersTable').DataTable({
        processing: true,
        ajax: { url: '{{ route("admin.members") }}', data: { ajax: true } },
        columns: [
            { data: 'id' },
            { data: 'first_name', render: (d, t, r) => d + ' ' + r.last_name },
            { data: 'email' },
            { data: 'phone' },
            { data: 'status', render: (d) => `<span class="badge bg-${d==='Active'?'success':'danger'}">${d}</span>` },
            { data: null, orderable: false, render: (d, t, r) => `
                <button class="btn btn-sm btn-warning editBtn" data-id="${r.id}">Edit</button>
                <button class="btn btn-sm btn-danger deleteBtn" data-id="${r.id}">Delete</button>
            `}
        ]
    });

    $('#memberForm').on('submit', function(e) {
        e.preventDefault();
        let mid = $('#memberId').val();
        let url = mid ? `/admin/members/${mid}/update` : '/admin/members/store';

        $.ajax({
            url: url, method: 'POST', data: $(this).serialize(),
            success: (r) => {
                alert(r.message);
                bootstrap.Modal.getInstance(document.getElementById('memberModal')).hide();
                table.ajax.reload();
            },
            error: (e) => alert('Error: ' + e.responseJSON?.message)
        });
    });

    $(document).on('click', '.deleteBtn', function() {
        if(confirm('Delete?')) {
            $.post(`/admin/members/${$(this).data('id')}/delete`, { _token: $('[name="_token"]').val() },
                (r) => { alert(r.message); table.ajax.reload(); }
            );
        }
    });
});
</script>
@endpush