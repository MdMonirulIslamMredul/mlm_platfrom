/* Admin Panel JavaScript */

document.addEventListener('DOMContentLoaded', function() {
    initAdminPanel();
});

function initAdminPanel() {
    initSidebarToggle();
    initAlertDismiss();
}

/**
 * Initialize sidebar toggle for mobile
 */
function initSidebarToggle() {
    const toggleBtn = document.getElementById('sidebarToggle');
    const sidebar = document.querySelector('.admin-sidebar');

    if (toggleBtn) {
        toggleBtn.addEventListener('click', function() {
            sidebar.classList.toggle('show');
        });
    }

    // Close sidebar when clicking outside
    document.addEventListener('click', function(event) {
        if (sidebar && toggleBtn && !sidebar.contains(event.target) && !toggleBtn.contains(event.target)) {
            sidebar.classList.remove('show');
        }
    });
}

/**
 * Initialize alert dismissal
 */
function initAlertDismiss() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });
}

/**
 * Confirm delete action
 */
function confirmDelete(message = 'Are you sure you want to delete this item?') {
    return confirm(message);
}

/**
 * Format file size
 */
function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
}

/**
 * Export table to CSV
 */
function exportTableToCSV(filename = 'export.csv') {
    const table = document.querySelector('.records-table-wrapper table');
    if (!table) return;

    let csv = [];
    const rows = table.querySelectorAll('tr');

    for (let i = 0; i < rows.length; i++) {
        const row = [];
        const cols = rows[i].querySelectorAll('td, th');

        for (let j = 0; j < cols.length; j++) {
            row.push('"' + cols[j].innerText + '"');
        }

        csv.push(row.join(','));
    }

    downloadCSV(csv.join('\n'), filename);
}

/**
 * Download CSV file
 */
function downloadCSV(csv, filename) {
    const csvFile = new Blob([csv], { type: 'text/csv' });
    const downloadLink = document.createElement('a');
    downloadLink.href = window.URL.createObjectURL(csvFile);
    downloadLink.setAttribute('download', filename);
    document.body.appendChild(downloadLink);
    downloadLink.click();
    document.body.removeChild(downloadLink);
}

/**
 * Show loading state on button
 */
function showLoadingButton(buttonId) {
    const button = document.getElementById(buttonId);
    if (button) {
        button.disabled = true;
        button.classList.add('btn-loading');
        const originalText = button.innerHTML;
        button.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Loading...';
        return originalText;
    }
}

/**
 * Reset loading state on button
 */
function resetLoadingButton(buttonId, originalText) {
    const button = document.getElementById(buttonId);
    if (button) {
        button.disabled = false;
        button.classList.remove('btn-loading');
        button.innerHTML = originalText;
    }
}

/**
 * Validate form inputs
 */
function validateForm(formId) {
    const form = document.getElementById(formId);
    if (!form) return false;

    const requiredFields = form.querySelectorAll('[required]');
    let isValid = true;

    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            field.classList.add('is-invalid');
            isValid = false;
        } else {
            field.classList.remove('is-invalid');
        }
    });

    return isValid;
}

/**
 * Handle form submission with validation
 */
function handleFormSubmit(formId) {
    const form = document.getElementById(formId);
    if (!form) return;

    form.addEventListener('submit', function(e) {
        if (!validateForm(formId)) {
            e.preventDefault();
            showAlert('Please fill all required fields', 'danger');
        }
    });
}

/**
 * Show toast notification
 */
function showAlert(message, type = 'info') {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    alertDiv.style.cssText = `
        top: 20px;
        right: 20px;
        z-index: 9999;
        min-width: 300px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    `;
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

    document.body.appendChild(alertDiv);

    setTimeout(() => {
        const bsAlert = new bootstrap.Alert(alertDiv);
        bsAlert.close();
    }, 5000);
}

/**
 * Handle image preview
 */
function handleImagePreview(inputId, previewId) {
    const input = document.getElementById(inputId);
    const preview = document.getElementById(previewId);

    if (input) {
        input.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file && preview) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = `<img src="${e.target.result}" alt="Preview" class="img-thumbnail">`;
                };
                reader.readAsDataURL(file);
            }
        });
    }
}

/**
 * Handle multiple file selection display
 */
function handleMultipleFiles(inputId, listId) {
    const input = document.getElementById(inputId);
    const list = document.getElementById(listId);

    if (input) {
        input.addEventListener('change', function(e) {
            if (list) {
                list.innerHTML = '';
                for (let file of this.files) {
                    const item = document.createElement('div');
                    item.className = 'document-item';
                    item.innerHTML = `
                        <i class="bi bi-file"></i>
                        <span>${file.name}</span>
                        <small>${formatFileSize(file.size)}</small>
                    `;
                    list.appendChild(item);
                }
            }
        });
    }
}

/**
 * Initialize table search/filter
 */
function initTableSearch(inputId, tableId) {
    const searchInput = document.getElementById(inputId);
    const table = document.getElementById(tableId) || document.querySelector('.records-table-wrapper table');

    if (searchInput && table) {
        searchInput.addEventListener('keyup', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const rows = table.querySelectorAll('tbody tr');

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });
    }
}

/**
 * Initialize table sorting
 */
function initTableSort(tableId) {
    const table = document.getElementById(tableId) || document.querySelector('.records-table-wrapper table');
    if (!table) return;

    const headers = table.querySelectorAll('thead th');
    headers.forEach((header, index) => {
        header.style.cursor = 'pointer';
        header.addEventListener('click', function() {
            sortTable(table, index);
        });
    });
}

/**
 * Sort table by column
 */
function sortTable(table, columnIndex) {
    const rows = Array.from(table.querySelectorAll('tbody tr'));
    const isAsc = !table.dataset.sortAsc;
    table.dataset.sortAsc = isAsc;

    rows.sort((a, b) => {
        const aVal = a.children[columnIndex].textContent.trim();
        const bVal = b.children[columnIndex].textContent.trim();

        if (!isNaN(aVal) && !isNaN(bVal)) {
            return isAsc ? aVal - bVal : bVal - aVal;
        } else {
            return isAsc ? aVal.localeCompare(bVal) : bVal.localeCompare(aVal);
        }
    });

    rows.forEach(row => table.querySelector('tbody').appendChild(row));
}

// Initialize common functions
document.addEventListener('DOMContentLoaded', function() {
    // Auto-initialize image preview if needed
    if (document.getElementById('user_image')) {
        handleImagePreview('user_image', 'imagePreview');
    }

    // Auto-initialize multiple files if needed
    if (document.getElementById('documents')) {
        handleMultipleFiles('documents', 'documentsList');
    }

    // Initialize table sort
    initTableSort();
});
