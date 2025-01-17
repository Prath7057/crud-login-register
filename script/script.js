$(document).ready(function () {
    //
    $('#userTable').DataTable();
    //
    // Show the modal for adding a new user
    $('#addUserBtn').on('click', function () {
        $('#userForm')[0].reset(); // Clear the form
        $('#userId').val(''); // Clear the hidden ID field
        $('#modalTitle').text('Add User'); // Set modal title
        $('#userModal').modal('show');
    });

    // Handle the Update button click
    $('#userTable').on('click', '.update-btn', function () {
        const userId = $(this).data('id');
        $('#userModal').modal('show'); // Show the modal
        $('#modalTitle').text('Update User'); // Set modal title
        $('#SubmitBtn').text('Update'); // Set modal title

        // Fetch user details using AJAX
        $.ajax({
            url: `update.php?id=${userId}`,
            type: 'GET',
            success: function (response) {
                const user = JSON.parse(response);

                if (user) {
                    // Populate the form with user data
                    $('#userId').val(user.id);
                    $('#name').val(user.name);
                    $('#gender').val(user.gender);
                    $('#mobile').val(user.mobile);
                    $('#email').val(user.email);
                    $('#image').val(''); // Clear the image field
                } else {
                    alert('User not found.');
                }
            },
            error: function () {
                alert('Error fetching user details.');
            },
        });
    });

    $('#userForm').on('submit', function (e) {
        e.preventDefault();
    
        let isValid = true;
    
        const nameField = $('#name');
        if (!nameField.val().trim()) {
            isValid = false;
            alert('Please enter your name.');
        }
    
        const genderField = $('#gender');
        if (!genderField.val()) {
            isValid = false;
            alert('Please select your gender.');
        }
    
        const mobileField = $('#mobile');
        if (!mobileField.val().match(/^[0-9]{10}$/)) {
            isValid = false;
            alert('Please enter a valid 10-digit mobile number.');
        }
    
        const emailField = $('#email');
        if (!emailField.val().match(/^[^@]+@[^@]+\.[a-zA-Z]{2,}$/)) {
            isValid = false;
            alert('Please enter a valid email address.');
        }
    
        if (!isValid) {
            return; 
        }
    
        const formData = new FormData(this);
        const url = $('#userId').val() ? 'update_action.php' : 'create.php';
    
        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                console.log(response);
                const res = JSON.parse(response);
    
                if (res.success) {
                    $('#userForm')[0].reset();
                    $('#userId').val(''); 
                    $('#modalTitle').text('Add User');
                    $('#userModal').modal('hide');
                    datatableReload();
                } else {
                    alert('Error: ' + res.message);
                }
            },
            error: function () {
                alert('An error occurred while processing the request.');
            },
        });
    });
    

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
                        datatableReload();
                    } else {
                        alert('Error: ' + res.message);
                    }
                },
            });
        }
    });
});
function datatableReload() {
    $.ajax({
        url: 'read.php',
        type: 'POST',
        success: function (response) {
            const res = response; 
            if (res) {
                $('#datatableBody').html('');
                $('#datatableBody').html(res);
            } else {
                alert('Error: No data found.');
            }
        },
    });
}
