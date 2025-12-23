<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../classes/User.php';

// Initialize database connection
$database = new Database();

// Check if user is logged in and is admin
if (!isLoggedIn() || !isAdmin()) {
    redirect(SITE_URL . '/auth/login.php');
}

$page_title = "Manage Users";

$user = new User($database->getConnection());

// Handle user actions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action']) && isset($_POST['user_id'])) {
        $user_id = (int)$_POST['user_id'];
        $action = sanitize($_POST['action']);
        $success = false;
        $message = '';
        
        switch ($action) {
            case 'approve':
                $success = $user->updateUserStatus($user_id, 'active');
                $message = $success ? "User approved successfully!" : "Failed to approve user.";
                break;
            case 'suspend':
                $duration_days = isset($_POST['duration_days']) ? (int)$_POST['duration_days'] : 0;
                if ($duration_days <= 0) {
                    $message = "Please specify a valid suspension duration.";
                } else {
                    $success = $user->suspendUser($user_id, $duration_days);
                    $message = $success ? "User suspended for {$duration_days} day(s) successfully!" : "Failed to suspend user.";
                }
                break;
            case 'unsuspend':
                $success = $user->unsuspendUser($user_id);
                $message = $success ? "User unsuspended successfully!" : "Failed to unsuspend user.";
                break;
            case 'delete':
                $success = $user->deleteUser($user_id);
                if (!$success) {
                    $message = "Cannot delete admin users or user not found.";
                } else {
                    $message = "User deleted successfully!";
                }
                break;
            case 'verify_guide':
                $success = $user->verifyGuide($user_id);
                $message = $success ? "Guide verified successfully!" : "Failed to verify guide.";
                break;
        }
        
        if ($success || $message) {
            $_SESSION['admin_message'] = $message;
            $_SESSION['admin_message_type'] = $success ? 'success' : 'danger';
            $redirect_url = SITE_URL . '/admin/users.php';
            if (isset($_GET['page']) && is_numeric($_GET['page'])) {
                $redirect_url .= '?page=' . (int)$_GET['page'];
            }
            redirect($redirect_url);
        }
    }
}

$all_users = $user->getAllUsers();

// Filter users
$status_filter = $_GET['status'] ?? 'all';
$role_filter = $_GET['role'] ?? 'all';
$verified_filter = $_GET['verified'] ?? 'all';

$filtered_users = $all_users;

if ($status_filter !== 'all') {
    $filtered_users = array_filter($filtered_users, function($u) use ($status_filter) {
        return $u['status'] === $status_filter;
    });
}

if ($role_filter !== 'all') {
    $filtered_users = array_filter($filtered_users, function($u) use ($role_filter) {
        return $u['role'] === $role_filter;
    });
}

if ($verified_filter !== 'all') {
    $verified_value = $verified_filter === '1';
    $filtered_users = array_filter($filtered_users, function($u) use ($verified_value) {
        return (isset($u['verified']) ? $u['verified'] : 0) == $verified_value;
    });
}

// Reset array keys after filtering
$filtered_users = array_values($filtered_users);

// Pagination settings
$items_per_page = 5;
$total_items = count($filtered_users);
$total_pages = $total_items > 0 ? ceil($total_items / $items_per_page) : 1;
$current_page = isset($_GET['page']) ? max(1, min((int)$_GET['page'], $total_pages)) : 1;

// Calculate offset
$offset = ($current_page - 1) * $items_per_page;

// Get paginated users
$paginated_users = array_slice($filtered_users, $offset, $items_per_page);

include __DIR__ . '/../includes/header.php';
?>

<div class="container mt-4">
    <?php if (isset($_SESSION['admin_message'])): ?>
        <div class="alert alert-<?php echo $_SESSION['admin_message_type']; ?> alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['admin_message']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php 
        unset($_SESSION['admin_message']);
        unset($_SESSION['admin_message_type']);
        ?>
    <?php endif; ?>
    
    <div class="row">
        <div class="col-12">
            <h1 class="mb-4">Manage Users</h1>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="all" <?php echo $status_filter === 'all' ? 'selected' : ''; ?>>All Status</option>
                                    <option value="active" <?php echo $status_filter === 'active' ? 'selected' : ''; ?>>Active</option>
                                    <option value="pending" <?php echo $status_filter === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                    <option value="suspended" <?php echo $status_filter === 'suspended' ? 'selected' : ''; ?>>Suspended</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="role" class="form-label">Role</label>
                                <select class="form-select" id="role" name="role">
                                    <option value="all" <?php echo $role_filter === 'all' ? 'selected' : ''; ?>>All Roles</option>
                                    <option value="tourist" <?php echo $role_filter === 'tourist' ? 'selected' : ''; ?>>Tourist</option>
                                    <option value="guide" <?php echo $role_filter === 'guide' ? 'selected' : ''; ?>>Guide</option>
                                    <option value="admin" <?php echo $role_filter === 'admin' ? 'selected' : ''; ?>>Admin</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="verified" class="form-label">Verification</label>
                                <select class="form-select" id="verified" name="verified">
                                    <option value="all" <?php echo $verified_filter === 'all' ? 'selected' : ''; ?>>All</option>
                                    <option value="1" <?php echo $verified_filter === '1' ? 'selected' : ''; ?>>Verified</option>
                                    <option value="0" <?php echo $verified_filter === '0' ? 'selected' : ''; ?>>Not Verified</option>
                                </select>
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary me-2">Filter</button>
                                <a href="users.php" class="btn btn-outline-secondary">Clear</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="card">
        <div class="card-header">
            <h5>Users (<?php echo count($filtered_users); ?>)</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Verified</th>
                            <th>Joined</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($paginated_users as $user_item): ?>
                        <tr>
                            <td><?php echo $user_item['id']; ?></td>
                            <td><?php echo $user_item['first_name'] . ' ' . $user_item['last_name']; ?></td>
                            <td><?php echo $user_item['email']; ?></td>
                            <td>
                                <span class="badge bg-info"><?php echo ucfirst($user_item['role']); ?></span>
                            </td>
                            <td>
                                <span class="badge bg-<?php 
                                    echo match($user_item['status']) {
                                        'active' => 'success',
                                        'pending' => 'warning',
                                        'suspended' => 'danger',
                                        default => 'secondary'
                                    };
                                ?>">
                                    <?php echo ucfirst($user_item['status']); ?>
                                </span>
                                <?php if ($user_item['status'] === 'suspended' && isset($user_item['suspend_until']) && $user_item['suspend_until']): ?>
                                    <br><small class="text-muted">Until: <?php echo formatDateTime($user_item['suspend_until']); ?></small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($user_item['role'] === 'guide'): ?>
                                    <?php if (isset($user_item['verified']) && $user_item['verified']): ?>
                                        <span class="badge bg-success">Verified</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning">Unverified</span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo formatDate($user_item['created_at']); ?></td>
                            <td>
                                <div class="btn-group" role="group">
                                    <?php if ($user_item['status'] === 'pending'): ?>
                                        <form method="POST" style="display: inline;">
                                            <input type="hidden" name="action" value="approve">
                                            <input type="hidden" name="user_id" value="<?php echo $user_item['id']; ?>">
                                            <button type="submit" class="btn btn-sm btn-success" 
                                                    onclick="return confirm('Are you sure you want to approve this user?');">
                                                <i class="fas fa-check me-1"></i>Approve
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                    
                                    <?php if ($user_item['status'] === 'active'): ?>
                                        <div id="suspendBtn<?php echo $user_item['id']; ?>">
                                            <button type="button" class="btn btn-sm btn-warning" 
                                                    onclick="showSuspendForm(<?php echo $user_item['id']; ?>)">
                                                <i class="fas fa-ban me-1"></i>Suspend
                                            </button>
                                        </div>
                                        <div id="suspendForm<?php echo $user_item['id']; ?>" style="display: none;">
                                            <form method="POST" class="d-inline-flex align-items-center gap-2">
                                                <input type="hidden" name="action" value="suspend">
                                                <input type="hidden" name="user_id" value="<?php echo $user_item['id']; ?>">
                                                <div class="input-group input-group-sm" style="width: 140px;">
                                                    <input type="number" name="duration_days" min="1" max="365" value="7" 
                                                           class="form-control text-center" required>
                                                    <span class="input-group-text bg-light">days</span>
                                                </div>
                                                <button type="submit" class="btn btn-sm btn-warning">
                                                    <i class="fas fa-check me-1"></i>Confirm
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-secondary" 
                                                        onclick="hideSuspendForm(<?php echo $user_item['id']; ?>)">
                                                    <i class="fas fa-times me-1"></i>Cancel
                                                </button>
                                            </form>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if ($user_item['status'] === 'suspended'): ?>
                                        <form method="POST" style="display: inline;">
                                            <input type="hidden" name="action" value="unsuspend">
                                            <input type="hidden" name="user_id" value="<?php echo $user_item['id']; ?>">
                                            <button type="submit" class="btn btn-sm btn-success" 
                                                    onclick="return confirm('Are you sure you want to unsuspend this user?');">
                                                <i class="fas fa-unlock me-1"></i>Unsuspend
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                    
                                    <?php if ($user_item['role'] === 'guide' && (!isset($user_item['verified']) || !$user_item['verified'])): ?>
                                        <form method="POST" style="display: inline;">
                                            <input type="hidden" name="action" value="verify_guide">
                                            <input type="hidden" name="user_id" value="<?php echo $user_item['id']; ?>">
                                            <button type="submit" class="btn btn-sm btn-info" 
                                                    onclick="return confirm('Are you sure you want to verify this guide?');">
                                                <i class="fas fa-check-circle me-1"></i>Verify Guide
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                    
                                    <?php if ($user_item['role'] !== 'admin'): ?>
                                        <form method="POST" style="display: inline;">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="user_id" value="<?php echo $user_item['id']; ?>">
                                            <button type="submit" class="btn btn-sm btn-danger" 
                                                    onclick="return confirm('⚠️ WARNING: This will permanently delete this user and all their data. This action cannot be undone!\n\nAre you absolutely sure you want to delete <?php echo htmlspecialchars($user_item['first_name'] . ' ' . $user_item['last_name']); ?>?');">
                                                <i class="fas fa-trash me-1"></i>Delete
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
            <div class="pagination-wrapper mt-4" data-total-pages="<?php echo $total_pages; ?>" data-current-page="<?php echo $current_page; ?>">
                <div class="d-flex flex-column align-items-center gap-3">
                    <!-- Page Info - Centered -->
                    <div class="pagination-info text-center">
                        <small class="text-muted">
                            <?php 
                                $display_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                                $display_page = max(1, min($display_page, $total_pages));
                                $per_page = (int)$items_per_page;
                                $total = (int)$total_items;
                                
                                if ($total > 0 && $display_page >= 1) {
                                    $start = (($display_page - 1) * $per_page) + 1;
                                    $end = min($display_page * $per_page, $total);
                                } else {
                                    $start = 0;
                                    $end = 0;
                                }
                            ?>
                            Showing <strong><?php echo $start; ?></strong> 
                            to <strong><?php echo $end; ?></strong> 
                            of <strong><?php echo $total; ?></strong> users
                        </small>
                    </div>
                    
                    <!-- Pagination Buttons - Centered -->
                    <nav>
                        <ul class="pagination-custom mb-0">
                            <!-- Previous Button -->
                            <?php 
                                $page_from_get = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                                $current_page_for_prev = max(1, $page_from_get);
                            ?>
                            <?php if ($current_page_for_prev > 1): 
                                $prev_params = [];
                                if ($status_filter !== 'all') {
                                    $prev_params['status'] = $status_filter;
                                }
                                if ($role_filter !== 'all') {
                                    $prev_params['role'] = $role_filter;
                                }
                                if ($verified_filter !== 'all') {
                                    $prev_params['verified'] = $verified_filter;
                                }
                                $prev_page = max(1, $current_page_for_prev - 1);
                                $prev_params['page'] = $prev_page;
                                $prev_url = '?' . http_build_query($prev_params);
                            ?>
                            <li class="pagination-item">
                                <a class="pagination-link pagination-nav" href="users.php<?php echo htmlspecialchars($prev_url); ?>">
                                    <i class="fas fa-chevron-left me-1"></i> Previous
                                </a>
                            </li>
                            <?php else: ?>
                            <li class="pagination-item disabled">
                                <span class="pagination-link pagination-nav disabled">
                                    <i class="fas fa-chevron-left me-1"></i> Previous
                                </span>
                            </li>
                            <?php endif; ?>
                            
                            <!-- Page Numbers -->
                            <?php
                                $current_page_int = (int)$current_page;
                                $total_pages_int = (int)$total_pages;
                                $start_page = max(1, $current_page_int - 2);
                                $end_page = min($total_pages_int, $current_page_int + 2);
                                
                                // Show first page if not in range
                                if ($start_page > 1): 
                                    $first_params = [];
                                    if ($status_filter !== 'all') {
                                        $first_params['status'] = $status_filter;
                                    }
                                    if ($role_filter !== 'all') {
                                        $first_params['role'] = $role_filter;
                                    }
                                    if ($verified_filter !== 'all') {
                                        $first_params['verified'] = $verified_filter;
                                    }
                                    $first_url = !empty($first_params) ? '?' . http_build_query($first_params) : '';
                            ?>
                                <li class="pagination-item">
                                    <a class="pagination-link" href="users.php<?php echo htmlspecialchars($first_url); ?>">1</a>
                                </li>
                                <?php if ($start_page > 2): ?>
                                    <li class="pagination-item">
                                        <span class="pagination-link pagination-ellipsis" data-expand-start="2" data-expand-end="<?php echo $start_page - 1; ?>" title="Click to show pages 2-<?php echo $start_page - 1; ?>">...</span>
                                    </li>
                                <?php endif; ?>
                            <?php endif; ?>
                            
                            <?php 
                            // Display page numbers in the calculated range
                            for ($i = $start_page; $i <= $end_page; $i++): 
                                $page_params = [];
                                if ($status_filter !== 'all') {
                                    $page_params['status'] = $status_filter;
                                }
                                if ($role_filter !== 'all') {
                                    $page_params['role'] = $role_filter;
                                }
                                if ($verified_filter !== 'all') {
                                    $page_params['verified'] = $verified_filter;
                                }
                                if ($i > 1) {
                                    $page_params['page'] = $i;
                                }
                                $page_url = !empty($page_params) ? '?' . http_build_query($page_params) : '';
                            ?>
                                <li class="pagination-item <?php echo $i === $current_page_int ? 'active' : ''; ?>">
                                    <a class="pagination-link <?php echo $i === $current_page_int ? 'active' : ''; ?>" href="users.php<?php echo htmlspecialchars($page_url); ?>">
                                        <?php echo $i; ?>
                                    </a>
                                </li>
                            <?php endfor; ?>
                            
                            <?php 
                            // Show last page if not in range
                            if ($end_page < $total_pages_int): 
                                if ($end_page < $total_pages_int - 1):
                            ?>
                                <li class="pagination-item">
                                    <span class="pagination-link pagination-ellipsis" data-expand-start="<?php echo $end_page + 1; ?>" data-expand-end="<?php echo $total_pages_int - 1; ?>" title="Click to show pages <?php echo $end_page + 1; ?>-<?php echo $total_pages_int - 1; ?>">...</span>
                                </li>
                            <?php 
                                endif;
                                $last_params = [];
                                if ($status_filter !== 'all') {
                                    $last_params['status'] = $status_filter;
                                }
                                if ($role_filter !== 'all') {
                                    $last_params['role'] = $role_filter;
                                }
                                if ($verified_filter !== 'all') {
                                    $last_params['verified'] = $verified_filter;
                                }
                                $last_params['page'] = $total_pages_int;
                                $last_url = '?' . http_build_query($last_params);
                            ?>
                                <li class="pagination-item">
                                    <a class="pagination-link" href="users.php<?php echo htmlspecialchars($last_url); ?>">
                                        <?php echo $total_pages_int; ?>
                                    </a>
                                </li>
                            <?php endif; ?>
                            
                            <!-- Next Button -->
                            <?php 
                                $page_from_get_next = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                                $current_page_for_next = max(1, $page_from_get_next);
                                $is_last_page = $current_page_for_next >= $total_pages_int;
                            ?>
                            <?php if (!$is_last_page): 
                                $next_params = [];
                                if ($status_filter !== 'all') {
                                    $next_params['status'] = $status_filter;
                                }
                                if ($role_filter !== 'all') {
                                    $next_params['role'] = $role_filter;
                                }
                                if ($verified_filter !== 'all') {
                                    $next_params['verified'] = $verified_filter;
                                }
                                $next_page = $current_page_for_next + 1;
                                $next_params['page'] = $next_page;
                                $next_url = '?' . http_build_query($next_params);
                            ?>
                            <li class="pagination-item">
                                <a class="pagination-link pagination-nav" href="users.php<?php echo htmlspecialchars($next_url); ?>">
                                    Next <i class="fas fa-chevron-right ms-1"></i>
                                </a>
                            </li>
                            <?php else: ?>
                            <li class="pagination-item disabled">
                                <span class="pagination-link pagination-nav disabled">
                                    Next <i class="fas fa-chevron-right ms-1"></i>
                                </span>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function showSuspendForm(userId) {
    // Hide the suspend button
    document.getElementById('suspendBtn' + userId).style.display = 'none';
    // Show the form
    document.getElementById('suspendForm' + userId).style.display = 'block';
    // Hide all other suspend forms and show their buttons
    var allForms = document.querySelectorAll('[id^="suspendForm"]');
    var allButtons = document.querySelectorAll('[id^="suspendBtn"]');
    allForms.forEach(function(form) {
        if (form.id !== 'suspendForm' + userId) {
            form.style.display = 'none';
        }
    });
    allButtons.forEach(function(btn) {
        var btnUserId = btn.id.replace('suspendBtn', '');
        if (btnUserId != userId) {
            btn.style.display = 'block';
        }
    });
}

function hideSuspendForm(userId) {
    // Hide the form
    document.getElementById('suspendForm' + userId).style.display = 'none';
    // Show the suspend button
    document.getElementById('suspendBtn' + userId).style.display = 'block';
}
</script>

<script>
// Ellipsis expansion functionality for users.php
document.addEventListener('DOMContentLoaded', function() {
    // Set up ellipsis click handlers after a delay to ensure DOM is ready
    setTimeout(() => {
        const ellipsisElements = document.querySelectorAll('.pagination-ellipsis');
        console.log('[Users Pagination] Found', ellipsisElements.length, 'ellipsis elements');
        
        ellipsisElements.forEach((ellipsis, index) => {
            // Force styles to make it clickable
            ellipsis.style.setProperty('cursor', 'pointer', 'important');
            ellipsis.style.setProperty('pointer-events', 'auto', 'important');
            ellipsis.style.setProperty('opacity', '1', 'important');
            ellipsis.style.border = '2px solid #e9ecef';
            ellipsis.style.background = '#fff';
            ellipsis.style.color = '#495057';
            ellipsis.style.borderRadius = '25px';
            ellipsis.style.padding = '0.5rem 0.75rem';
            ellipsis.style.minWidth = '40px';
            ellipsis.style.height = '40px';
            
            // Remove disabled from parent
            const parent = ellipsis.closest('.pagination-item, .page-item');
            if (parent && parent.classList.contains('disabled')) {
                parent.classList.remove('disabled');
                parent.style.setProperty('pointer-events', 'auto', 'important');
            }
            
            // Remove any existing listeners
            const newEllipsis = ellipsis.cloneNode(true);
            ellipsis.parentNode.replaceChild(newEllipsis, ellipsis);
            
            // Re-apply styles
            newEllipsis.style.setProperty('cursor', 'pointer', 'important');
            newEllipsis.style.setProperty('pointer-events', 'auto', 'important');
            newEllipsis.style.setProperty('opacity', '1', 'important');
            newEllipsis.style.border = '2px solid #e9ecef';
            newEllipsis.style.background = '#fff';
            newEllipsis.style.color = '#495057';
            newEllipsis.style.borderRadius = '25px';
            newEllipsis.style.padding = '0.5rem 0.75rem';
            newEllipsis.style.minWidth = '40px';
            newEllipsis.style.height = '40px';
            
            // Add click listener with inline expand function
            newEllipsis.addEventListener('click', function(e) {
                console.log('[Users Pagination] ELLIPSIS CLICKED!', this, e);
                e.preventDefault();
                e.stopPropagation();
                e.stopImmediatePropagation();
                
                const ellipsisElement = this;
                const paginationWrapper = ellipsisElement.closest('.pagination-wrapper');
                const paginationList = ellipsisElement.closest('.pagination-custom') || ellipsisElement.closest('.pagination');
                
                if (!paginationList || !paginationWrapper) {
                    console.error('[Users Pagination] Missing pagination elements');
                    return;
                }
                
                const totalPages = parseInt(paginationWrapper.getAttribute('data-total-pages')) || 0;
                const currentPage = parseInt(paginationWrapper.getAttribute('data-current-page')) || 1;
                const expandStart = parseInt(ellipsisElement.getAttribute('data-expand-start')) || 0;
                const expandEnd = parseInt(ellipsisElement.getAttribute('data-expand-end')) || 0;
                
                console.log('[Users Pagination] Expand params:', { totalPages, currentPage, expandStart, expandEnd });
                
                if (totalPages === 0 || expandStart === 0 || expandEnd === 0 || expandStart > expandEnd) {
                    console.error('[Users Pagination] Invalid expand parameters');
                    return;
                }
                
                // Get base URL from existing link
                const existingLink = paginationList.querySelector('a.pagination-link:not(.pagination-nav)') || paginationList.querySelector('a.page-link:not(.disabled)');
                if (!existingLink || !existingLink.href) {
                    console.error('[Users Pagination] No existing link found');
                    return;
                }
                
                try {
                    const url = new URL(existingLink.href, window.location.origin);
                    const basePath = url.pathname;
                    const searchParams = new URLSearchParams(url.search);
                    searchParams.delete('page');
                    
                    const filterParams = {};
                    for (const [key, value] of searchParams.entries()) {
                        filterParams[key] = value;
                    }
                    
                    console.log('[Users Pagination] Creating page links from', expandStart, 'to', expandEnd);
                    
                    const fragment = document.createDocumentFragment();
                    const ellipsisParent = ellipsisElement.parentElement;
                    const isBootstrapPagination = paginationList.classList.contains('pagination');
                    
                    for (let i = expandStart; i <= expandEnd; i++) {
                        const li = document.createElement('li');
                        li.className = (isBootstrapPagination ? 'page-item' : 'pagination-item') + (i === currentPage ? ' active' : '');
                        
                        const a = document.createElement('a');
                        a.className = (isBootstrapPagination ? 'page-link' : 'pagination-link') + (i === currentPage ? ' active' : '');
                        
                        const pageParams = { ...filterParams };
                        if (i > 1) {
                            pageParams.page = i;
                        }
                        const queryString = Object.keys(pageParams).length > 0 ? '?' + new URLSearchParams(pageParams).toString() : '';
                        a.href = basePath + queryString;
                        a.textContent = i;
                        
                        li.appendChild(a);
                        fragment.appendChild(li);
                    }
                    
                    console.log('[Users Pagination] Replacing ellipsis with', expandEnd - expandStart + 1, 'page links');
                    ellipsisParent.replaceWith(...Array.from(fragment.children));
                    console.log('[Users Pagination] Ellipsis expanded successfully!');
                } catch (error) {
                    console.error('[Users Pagination] Error expanding ellipsis:', error);
                }
            }, true);
            
            console.log(`[Users Pagination] Listener added to ellipsis ${index}`);
        });
    }, 500);
});
</script>

<style>
/* Pagination Styles - Same as Admin Bookings */
.pagination-wrapper {
    padding: 1.5rem 0;
    display: flex;
    justify-content: center;
}

.pagination-info {
    font-size: 0.95rem;
    text-align: center;
    width: 100%;
}

.pagination-info strong {
    color: var(--primary-color);
    font-weight: 600;
}

.pagination-custom {
    display: flex;
    list-style: none;
    padding: 0;
    margin: 0;
    gap: 0.5rem;
    align-items: center;
    flex-wrap: wrap;
    justify-content: center;
}

.pagination-item {
    margin: 0;
}

.pagination-link {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 40px;
    height: 40px;
    padding: 0.5rem 1rem;
    background: #fff;
    border: 2px solid #e9ecef;
    border-radius: 25px;
    color: #495057;
    text-decoration: none;
    font-weight: 500;
    font-size: 0.9rem;
    transition: all 0.3s ease;
    cursor: pointer;
}

.pagination-link:hover:not(.disabled):not(.active) {
    background: linear-gradient(45deg, #007bff, #0056b3);
    border-color: #007bff;
    color: #fff;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
}

.pagination-link.active {
    background: linear-gradient(45deg, #007bff, #0056b3);
    border-color: #007bff;
    color: #fff;
    font-weight: 600;
    box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
}

.pagination-item.disabled .pagination-link.disabled,
.pagination-item.disabled span.pagination-link.disabled:not(.pagination-ellipsis) {
    opacity: 0.5;
    cursor: not-allowed;
    pointer-events: none !important;
}

/* Ensure ellipsis is always clickable */
.pagination-item:has(.pagination-ellipsis),
.pagination-item.disabled:has(.pagination-ellipsis) {
    pointer-events: auto !important;
}

.pagination-item .pagination-ellipsis {
    pointer-events: auto !important;
    cursor: pointer !important;
}

.pagination-item:not(.disabled) a.pagination-link {
    pointer-events: auto !important;
    cursor: pointer !important;
    text-decoration: none !important;
    display: inline-block !important;
}

.pagination-nav {
    padding: 0.5rem 1.25rem;
    font-weight: 600;
}

.pagination-ellipsis {
    border: 2px solid #e9ecef !important;
    background: #fff !important;
    cursor: pointer !important;
    pointer-events: auto !important;
    color: #495057 !important;
    min-width: 40px !important;
    height: 40px !important;
    padding: 0.5rem 0.75rem !important;
    border-radius: 25px !important;
    transition: all 0.3s ease !important;
}

.pagination-ellipsis:hover {
    background: rgba(0, 123, 255, 0.1) !important;
    border-color: #007bff !important;
    color: #007bff !important;
    transform: translateY(-2px) !important;
    box-shadow: 0 4px 12px rgba(0, 123, 255, 0.2) !important;
}

/* Dark Mode Support */
body.dark .pagination-link {
    background: #1b1e22;
    border-color: #3b3f45;
    color: #e3e6ea;
}

body.dark .pagination-link:hover:not(.disabled):not(.active) {
    background: linear-gradient(45deg, #0056b3, #004085);
    border-color: #5dade2;
    color: #fff;
}

body.dark .pagination-link.active {
    background: linear-gradient(45deg, #5dade2, #007bff);
    border-color: #5dade2;
    color: #fff;
}

body.dark .pagination-info strong {
    color: #5dade2;
}

body.dark .pagination-ellipsis {
    color: #9ca3af;
    background: transparent;
}

@media (max-width: 576px) {
    .pagination-wrapper {
        flex-direction: column;
        align-items: center;
    }
    
    .pagination-info {
        text-align: center;
        margin-bottom: 1rem;
    }
    
    .pagination-custom {
        justify-content: center;
    }
    
    .pagination-link {
        min-width: 36px;
        height: 36px;
        padding: 0.4rem 0.8rem;
        font-size: 0.85rem;
    }
    
    .pagination-nav {
        padding: 0.4rem 1rem;
    }
}
</style>

<?php include __DIR__ . '/../includes/footer.php'; ?>
