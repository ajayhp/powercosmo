@extends('auth.layouts')

@section('extra_css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<style>
    .btn-group button {
        margin-right: 5px;
    }

    .updateLeadBtn {
        margin: 10px auto;
        display: block;
    }

    .modal-footer .btn {
        opacity: 1 !important;
        visibility: visible !important;
    }
</style>
@endsection

@section('content')
<div class="row justify-content-center mt-5">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Employee</span>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEmployeeModal">Add Employee</button>
            </div>
            <div class="card-body">
                <table id="leads-table" class="display">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Employee Modal -->
<div class="modal fade" id="addEmployeeModal" tabindex="-1" aria-labelledby="addEmployeeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addEmployeeModalLabel">Add Employee</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addEmployeeForm">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                    </div>
                    <button type="submit" class="btn btn-success updateLeadBtn">Add Employee</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('extra_scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        var table = $('#leads-table').DataTable({
            processing: true,
            serverSide: false,
            ajax: {
                url: '{{ route("employee.index") }}',
                type: 'GET',
            },
            columns: [
                { data: 'name', name: 'name' },
                { data: 'email', name: 'email' },
                { data: 'created_at', name: 'created_at', searchable: false }
            ],
        columnDefs: [
        { targets: [2], visible: false }
        ],
            order: [[2, 'desc']]
        });

        $('#addEmployeeForm').submit(function(e) {
            e.preventDefault();
            const name = $('#name').val();
            const email = $('#email').val();
            const password = $('#password').val();
            const password_confirmation = $('#password_confirmation').val();
            $.ajax({
                url: '{{ route("employee.store") }}',
                type: 'POST',
                data: {
                email: email,
                name: name,
                password: password,
                password_confirmation: password_confirmation,
                _token: '{{ csrf_token() }}'
            },
                success: function(response) {
                    $('#addEmployeeModal').modal('hide');
                    $('#name').val('');
                    $('#email').val('');
                    $('#password').val('');
                    $('#password_confirmation').val('');
                    table.ajax.reload();
                    showSuccessNotification('Employee ','The Employee added successfully.');
                },
                error: function(response) {
                    try {
                    Swal.fire('Error!', response.responseJSON.message, 'error');
                    } catch (error) {
                    alert('Error adding employee');
                    }

                }
            });
        });

        function showSuccessNotification(title,text) {
        Swal.fire({
            icon: 'success',
            title: title,
            text: text,
            showConfirmButton: false,
            timer: 2000 // Auto close after 1.5 seconds
        });
    }
    });
</script>
@endsection
