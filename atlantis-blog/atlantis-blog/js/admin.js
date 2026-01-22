$(document).ready(function() {
    // Edit Interaction
    $('.btn-edit').on('click', function() {
        const btn = $(this);
        $('#edit_comment_id').val(btn.data('comment-id'));
        $('#edit_name').val(btn.data('name'));
        $('#edit_email').val(btn.data('email'));
        $('#edit_comment').val(btn.data('comment'));
        
        new bootstrap.Modal('#editModal').show();
    });
    
    // Handle Edit Submit
    $('#editCommentForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = {
            id: $('#edit_comment_id').val(),
            name: $('#edit_name').val(),
            email: $('#edit_email').val(),
            comment: $('#edit_comment').val(),
            csrf_token: $('#edit_csrf_token').val()
        };
        
        $.ajax({
            url: 'api/edit_comment.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    showAlert('success', 'Comment updated successfully');
                    
                    // Update DOM row - Fix column indices: 0=Post Title, 1=Author, 2=Email, 3=Comment, 4=Actions
                    const row = $(`tr[data-comment-id="${formData.id}"]`);
                    row.find('td:eq(1)').text(formData.name);  // Author column
                    row.find('td:eq(2)').text(formData.email);  // Email column
                    row.find('.comment-preview').text(formData.comment);  // Comment column
                    
                    // Update Button Data
                    row.find('.btn-edit')
                        .data('name', formData.name)
                        .data('email', formData.email)
                        .data('comment', formData.comment);
                        
                    bootstrap.Modal.getInstance(document.getElementById('editModal')).hide();
                } else {
                    showAlert('danger', response.message);
                }
            },
            error: function(xhr) {
                const message = xhr.responseJSON?.message || 'An error occurred. Please try again.';
                showAlert('danger', message);
            }
        });
    });
    
    // Delete Interaction
    $('.btn-delete').on('click', function() {
        $('#delete_comment_id').val($(this).data('comment-id'));
        new bootstrap.Modal('#deleteModal').show();
    });
    
    // Confirm Delete
    $('#confirmDelete').on('click', function() {
        const id = $('#delete_comment_id').val();
        const csrf_token = $('#delete_csrf_token').val();
        
        $.ajax({
            url: 'api/delete_comment.php',
            type: 'POST',
            data: { id: id, csrf_token: csrf_token },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    showAlert('success', 'Comment deleted');
                    $(`tr[data-comment-id="${id}"]`).fadeOut(function() { $(this).remove(); });
                    
                    // Update counter - ensure it doesn't go negative
                    const countEl = $('#totalComments');
                    const newCount = Math.max(0, parseInt(countEl.text()) - 1);
                    countEl.text(newCount);
                    
                    bootstrap.Modal.getInstance(document.getElementById('deleteModal')).hide();
                } else {
                    showAlert('danger', response.message);
                }
            },
            error: function(xhr) {
                const message = xhr.responseJSON?.message || 'An error occurred. Please try again.';
                showAlert('danger', message);
            }
        });
    });

    function showAlert(type, message) {
        const alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        $('#alertContainer').html(alertHtml);
        setTimeout(() => $('.alert').alert('close'), 4000);
    }
});
