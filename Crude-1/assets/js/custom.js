/**
 * Custom JavaScript bindings for ProfileHub
 */

$(document).ready(function () {
    // Delete profile confirmation using SweetAlert2
    $(document).on('click', '.btn-delete-user', function (e) {
        e.preventDefault();
        const deleteUrl = $(this).attr('href');
        const userName = $(this).data('name') || 'this user';
        
        Swal.fire({
            title: 'Are you sure?',
            text: `This action will permanently delete ${userName}'s profile and their image!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fa-solid fa-trash-can me-1"></i> Yes, delete it!',
            cancelButtonText: 'Cancel',
            customClass: {
                confirmButton: 'btn btn-danger px-4 mx-2',
                cancelButton: 'btn btn-secondary px-4 mx-2'
            },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading state while redirecting
                Swal.fire({
                    title: 'Deleting...',
                    text: 'Please wait while the profile is being removed.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                // Redirect to delete.php?id={id}
                window.location.href = deleteUrl;
            }
        });
    });

    // Image preview helper for edit/create profiles
    $('#profile_image').on('change', function () {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                $('#image-preview-placeholder').attr('src', e.target.result).removeClass('d-none');
            }
            reader.readAsDataURL(file);
        }
    });
});
