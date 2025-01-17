$(document).ready(function () {
    const userTable = $('#userTable').DataTable({
        ajax: {
            url: 'read.php',
            type: 'GET',
            dataSrc: 'data',
        },
        columns: [
            { data: 'id' },
            { data: 'name' },
            { data: 'gender' },
            { data: 'mobile' },
            { data: 'email' },
            {
                data: 'image',
                render: function (data) {
                    return `<img src="uploads/${data}" class="img-thumbnail" style="width: 50px; height: 50px;">`;
                },
            },
            {
                data: 'id',
                render: function (data) {
                    return `
                        <button class="btn btn-info btn-sm view-btn" data-id="${data}">View</button>
                        <button class="btn btn-warning btn-sm update-btn" data-id="${data}">Update</button>
                        <button class="btn btn-danger btn-sm delete-btn" data-id="${data}">Delete</button>
                    `;
                },
            },
        ],
    });

    // Add User
    $('#userForm').on('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);

        $.ajax({
            url: 'create.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                const res = JSON.parse(response);
                if (res.success) {
                    alert('User saved successfully!');
                    $('#userModal').modal('hide');
                    userTable.ajax.reload();
                } else {
                    alert('Error: ' + res.message);
                }
            },
        });
    });

    // Update User
    // $('#userTable').on('click', '.update-btn', function () {
    //     const id = $(this).data('id');
    //     $.ajax({
    //         url: 'view.php',
    //         type: 'POST',
    //         data: { id },
    //         success: function (response) {
    //             const res = JSON.parse(response);
    //             if (res.success) {
    //                 $('#userId').val(res.data.id);
    //                 $('#name').val(res.data.name);
    //                 $('#gender').val(res.data.gender);
    //                 $('#mobile').val(res.data.mobile);
    //                 $('#email').val(res.data.email);
    //                 $('#modalTitle').text('Update User');
    //                 $('#userModal').modal('show');
    //             } else {
    //                 alert('Error: ' + res.message);
    //             }
    //         },
    //     });
    // });

    //
    
    //Update User
    $('#userTable').on('click', '.update-btn', function () {
        const userId = $(this).data('id');
        window.location.href = `update.php?id=${userId}`; // Redirect to update page with user ID
    });

    // Delete User
    $('#userTable').on('click', '.delete-btn', function () {
        const id = $(this).data('id');
        if (confirm('Are you sure you want to delete this user?')) {
            $.ajax({
                url: 'delete.php',
                type: 'POST',
                data: { id },
                success: function (response) {
                    const res = JSON.parse(response);
                    if (res.success) {
                        alert('User deleted successfully!');
                        userTable.ajax.reload();
                    } else {
                        alert('Error: ' + res.message);
                    }
                },
            });
        }
    });
});

 