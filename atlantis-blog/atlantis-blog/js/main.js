$(document).ready(function() {
    // Handle comment form submission
    $('.comment-form').on('submit', function(e) {
        e.preventDefault();
        
        const form = $(this);
        const postId = form.data('post-id');
        const submitBtn = form.find('button[type="submit"]');
        const originalBtnText = submitBtn.html();
        
        // Get form data
        const formData = {
            post_id: postId,
            name: form.find('input[name="name"]').val(),
            email: form.find('input[name="email"]').val(),
            comment: form.find('textarea[name="comment"]').val(),
            csrf_token: form.find('input[name="csrf_token"]').val(),
            honeypot: form.find('input[name="honeypot"]').val()
        };
        
        // Disable all form inputs during submit
        form.find('input, textarea, button').prop('disabled', true);
        submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Posting...');
        
        // Send AJAX request
        $.ajax({
            url: 'api/add_comment.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    showAlert('success', response.message);
                    
                    // Reset form
                    form[0].reset();
                    
                    // Add new comment to the list
                    const commentHtml = `
                        <div class="comment-item" style="display: none;">
                            <div class="comment-header">
                                <div class="comment-author">
                                    ${escapeHtml(response.comment.name)}
                                </div>
                                <div class="comment-date">Just now</div>
                            </div>
                            <div class="comment-text">
                                ${escapeHtml(response.comment.comment).replace(/\n/g, '<br>')}
                            </div>
                        </div>
                    `;
                    
                    const commentsList = $('#comments-list-' + postId);
                    
                    // Remove "no comments" message
                    commentsList.find('.no-comments').remove();
                    
                    // Prepend new comment and fade in
                    commentsList.prepend(commentHtml);
                    commentsList.find('.comment-item:first').fadeIn();
                    
                    // Update comment count - scope to the specific post card
                    const postCard = form.closest('.post-card');
                    const commentMetaEl = postCard.find('.meta-comments');
                    const currentCount = parseInt(commentMetaEl.text().match(/\d+/)[0]) + 1;
                    commentMetaEl.html(`<i class="far fa-comments"></i> ${currentCount} Comments`);
                    
                    // Update discussion header count
                    const discussionTitle = postCard.find('.comments-title');
                    discussionTitle.text(`Discussion (${currentCount})`);
                    
                } else {
                    showAlert('danger', response.message || 'Failed to add comment');
                }
            },
            error: function(xhr, status, error) {
                const message = xhr.responseJSON?.message || 'An error occurred. Please try again.';
                showAlert('danger', message);
                console.error(error);
            },
            complete: function() {
                form.find('input, textarea, button').prop('disabled', false);
                submitBtn.html(originalBtnText);
            }
        });
    });
    
    // Alert Helper
    function showAlert(type, message) {
        const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        
        const alertHtml = `
            <div class="alert ${alertClass} alert-dismissible fade show shadow-sm" role="alert">
                <i class="fas ${icon} me-2"></i> ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        
        $('#alertContainer').html(alertHtml);
        
        // Auto dismiss
        setTimeout(function() {
            $('.alert').fadeOut('slow', function() { $(this).remove(); });
        }, 4000);
    }
    
    // Utility: Smooth Scroll
    $('a[href^="#"]').on('click', function(e) {
        e.preventDefault();
        const target = $(this.getAttribute('href'));
        if (target.length) {
            $('html, body').animate({ scrollTop: target.offset().top - 20 }, 800);
        }
    });

    // Utility: Escape HTML
    function escapeHtml(text) {
        return text
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }
});
