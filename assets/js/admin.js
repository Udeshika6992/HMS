/**
 * Admin Panel JavaScript
 */

// =====================================================
// User Management
// =====================================================
function confirmDelete(userId, userName) {
    if (confirm('Are you sure you want to delete user: ' + userName + '?')) {
        var form = document.createElement('form');
        form.method = 'POST';
        form.action = baseUrl + 'admin/users/delete/' + userId;
       