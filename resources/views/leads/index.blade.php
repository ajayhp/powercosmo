@extends('auth.layouts')

@section('extra_css')
<style>
    .btn-group button {
    margin-right: 5px; /* Adds space between buttons */
    }

    .updateLeadBtn {
        margin: 10px auto;
        display: block;
    }

    .modal-footer .btn {
        opacity: 1 !important;
        visibility: visible !important;
    }

    #updatesList {
        max-height: 300px;
        overflow-y: scroll;
        overflow-x: hidden;
        padding-right: 10px;
        margin-top: 10px;
    }

    #updatesList::-webkit-scrollbar {
        width: 0px;
    }

    #updatesList .row {
        padding-bottom: 10px;
    }

    #updatesList .col-6 {
        padding-left: 10px;
        padding-right: 10px;
        word-wrap: break-word;
        overflow-wrap: break-word;
    }

    #updatesList .col-6 p {
        margin: 20px;
        padding: 0;
    }

    #updatesList .col-3 p {
        margin: 20px;
        padding: 0;
    }

    #updatesList .col-6 p small {
        font-size: 0.8rem;
        color: #6c757d;
    }

    b, strong {
        font-weight: bolder;
        margin: 17px;
    }

</style>
@endsection
@section('content')

<div class="row justify-content-center mt-5">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">Leads</div>
            <div class="card-body">
            <!-- DataTable -->
            <table id="leads-table" class="display">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Mobile</th>
                        <th>Created At</th>
                        <th>Description</th>
                        <th>Source </th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Table rows will be filled by DataTables -->
                </tbody>
            </table>
            </div>
        </div>
    </div>
</div>
<!-- Edit Lead Modal -->
<div class="modal fade" id="editLeadModal" tabindex="-1" role="dialog" aria-labelledby="editLeadModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editLeadModalLabel">Edit Lead</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    {{-- <span aria-hidden="true">&times;</span> --}}
                </button>
            </div>
            <div class="modal-body">
                <form id="editLeadForm">
                    <input type="hidden" id="lead_id">
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" class="form-control" id="lead_name">
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="text" disabled class="form-control" id="lead_email" required>
                    </div>
                    <div class="form-group">
                        <label>Mobile</label>
                        <input type="text" disabled class="form-control" id="lead_mobile" required>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea class="form-control" id="lead_description"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Source</label>
                        <input type="text" class="form-control" id="lead_source" required>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select class="form-control" id="lead_status" required>
                            <option value="new">New</option>
                            <option value="accepted">Accepted</option>
                            <option value="completed">Completed</option>
                            <option value="rejected">Rejected</option>
                            <option value="invalid">Invalid</option>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary updateLeadBtn">Update Lead</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal to Post Update -->
<div class="modal fade" id="postUpdateModal" tabindex="-1" aria-labelledby="postUpdateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="postUpdateModalLabel">Post Update</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="postUpdateForm">
                    <div class="mb-3">
                        <label for="leadMessage" class="form-label">Lead Update Message</label>
                        <textarea class="form-control" id="leadMessage" rows="3" required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveUpdateBtn">Save Update</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Structure -->
<div class="modal fade" id="viewUpdatesModal" tabindex="-1" aria-labelledby="viewUpdatesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewUpdatesModalLabel">Lead Updates</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-6"><strong>Message</strong></div>
                    <div class="col-3"><strong>Name</strong></div>
                    <div class="col-3"><strong>Time</strong></div>
                </div>
                <div id="updatesList" style="max-height: 300px; overflow-y: auto;">
                    <!-- Lead updates will be injected here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


@endsection
@section('extra_scripts')

<script>
    $(document).ready(function() {
        var table;
        var isAdmin = @json(Auth::user()->isAdmin());
        initDataTable();
    function showSuccessNotification(title,text) {
        Swal.fire({
            icon: 'success',
            title: title,
            text: text,
            showConfirmButton: false,
            timer: 2000
        });
    }

        function initDataTable() {

     table = $('#leads-table').DataTable({
        processing: true,
        serverSide: false,
        ajax: {
            url: '{{ route("home") }}',
            type: 'GET',
            data: function(d) {
                d.email = $('#email-filter').val();
            }
        },
        columns: [
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'mobile', name: 'mobile' },
            { data: 'created_at', name: 'created_at',searchable: false },
            {
                data: 'description',
                name: 'description',
                render: function(data, type, row) {
                    // Truncate description to 20 characters and show full description on hover
                    var truncatedDescription = data.length > 20 ? data.substring(0, 20) + '...' : data;
                    return `<span title="${data}">${truncatedDescription}</span>`;
                },
                searchable: false
            },
            { data: 'source', name: 'source',searchable: false },
            { data: 'status', name: 'status',searchable: false },
            {
                data: null,
                render: function(data, type, row) {
                    return `
                         <div class="btn-group" role="group">
                         ${isAdmin ? `   <button class="btn btn-primary btn-sm edit-lead" data-id="${row.id}">Edit</button>` : ''}
                            <button class="btn btn-info btn-sm post-update" data-id="${row.id}">Post Update</button>
                            <button class="btn btn-success btn-sm view-updates" data-id="${row.id}">View Updates</button>
                        </div>
                    `;
                },
                orderable: false,
                searchable: false
            }
        ],
        columnDefs: [
        { targets: [3], visible: false }
    ],
    order: [[3, 'desc']]
    });

}

$('#leads-table').on('click', '.edit-lead', function() {
        var leadId = $(this).data('id');

        // Fetch Lead Data from API
        $.ajax({
            url: `/leads/${leadId}/edit`,
            type: 'GET',
            success: function(response) {
                $('#lead_id').val(response.id);
                $('#lead_email').val(response.email);
                $('#lead_mobile').val(response.mobile);
                $('#lead_name').val(response.name);
                $('#lead_description').val(response.description);
                $('#lead_source').val(response.source);
                $('#lead_status').val(response.status);

                // Show the Modal
                $('#editLeadModal').modal('show');
            }
        });
    });

    $('#editLeadForm').on('submit', function(event) {
    event.preventDefault();

    // Clear any previous error messages
    $('.error-message').remove();

    // Get the values of the fields
    var leadId = $('#lead_id').val();
    var leadName = $('#lead_name').val().trim();
    var leadDescription = $('#lead_description').val().trim();
    var leadSource = $('#lead_source').val().trim();
    var leadStatus = $('#lead_status').val().trim();

    var isValid = true;

    // Basic Validation to check if fields are not empty
    if (!leadName) {
        isValid = false;
        $('#lead_name').after('<span class="error-message text-danger">This field is required.</span>');
    } else if (leadName.length > 20) {
        isValid = false;
        $('#lead_name').after('<span class="error-message text-danger">Name cannot be more than 20 characters.</span>');
    }

    if (!leadDescription) {
        isValid = false;
        $('#lead_description').after('<span class="error-message text-danger">This field is required.</span>');
    }
    else if (leadDescription.length < 50) {
        isValid = false;
        $('#lead_description').after('<span class="error-message text-danger">Description cannot be less than 50 characters.</span>');
    }

    if (!leadSource) {
        isValid = false;
        $('#lead_source').after('<span class="error-message text-danger">This field is required.</span>');
    }

    if (!leadStatus) {
        isValid = false;
        $('#lead_status').after('<span class="error-message text-danger">This field is required.</span>');
    }

    // If any field is empty, prevent form submission
    if (!isValid) {
        return;
    }

    var formData = {
        name: leadName,
        description: leadDescription,
        source: leadSource,
        status: leadStatus,
        _token: '{{ csrf_token() }}'
    };

        // Proceed with the AJAX request if validation passes
        $.ajax({
            url: `/leads/${leadId}`,
            type: 'PUT',
            data: formData,
            success: function(response) {
                $('#editLeadModal').modal('hide');
                showSuccessNotification('Lead Edited','The lead information has been updated successfully.');
                table.ajax.reload(null, false);
            },
            error: function(error) {
                Swal.fire('Error!', 'Something went wrong.', 'error');
            }
        });
    });


    $(document).on('click', '.post-update', function() {
    const leadId = $(this).data('id');

    $('#leadMessage').val('');
    $('#postUpdateModal').modal('show');
    $('#postUpdateForm').data('lead-id', leadId);
});

$('#saveUpdateBtn').on('click', function() {
    const leadId = $('#postUpdateForm').data('lead-id');
    const leadMessage = $('#leadMessage').val();
    $('.error-message').remove();
    var isMessageValid = true;
    if (!leadMessage) {
        isMessageValid = false;
        $('#leadMessage').after('<span class="error-message text-danger">This field is required.</span>');
    }
    else if (leadMessage.length < 50) {
        isMessageValid = false;
        $('#leadMessage').after('<span class="error-message text-danger">Message cannot be less than 20 characters.</span>');
    }
    if (!isMessageValid) {
        return;
    }
    $.ajax({
        url: '{{ route("leads.update.store") }}',
        method: 'POST',
        data: {
            lead_id: leadId,
            lead_message: leadMessage,
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            showSuccessNotification('Lead Update','The lead message has been saved successfully.');

            $('#postUpdateModal').modal('hide');
        },
        error: function(error) {
            Swal.fire('Error!', 'Something went wrong.', 'error');
        }
    });
});
$(document).on('click', '.view-updates', function() {
    const leadId = $(this).data('id');

    $.ajax({
        url: '{{route("leads.update")}}',
        method: 'GET',
        data: { lead_id: leadId },
        success: function(response) {
            if (Array.isArray(response)) {
                let updates = '';
                response.forEach(function(update) {
                    const formattedTimestamp = new Date(update.created_at).toLocaleString();

                    updates += `
                        <div class="row mb-2">
                            <div class="col-6">
                                <p>${update.lead_message}</p>
                            </div>
                            <div class="col-3">
                                <p>${update.user.name}</p>
                            </div>
                            <div class="col-3">
                                <p>${formattedTimestamp}</p>
                            </div>
                        </div>`;
                });

                $('#updatesList').html(updates);

                const updatesContainer = $('#updatesList')[0];
                updatesContainer.scrollTop = updatesContainer.scrollHeight;

                $('#viewUpdatesModal').modal('show');
            } else {
                alert('Updates not found or invalid data structure');
            }
        },
        error: function(error) {
            alert('Error fetching lead updates:', error);
        }
    });
});

});

</script>
@endsection
