<?php
// Admin reports view - Contact reports submitted by users
// Expects: $reports (array), $stats (array), $status_filter (string)
?>
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <h1 class="mb-4"><i class="fas fa-envelope me-2"></i>Contact Reports</h1>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title text-primary">Total Reports</h5>
                    <h3 class="mb-0"><?php echo $stats['total'] ?? 0; ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title text-warning">New</h5>
                    <h3 class="mb-0"><?php echo $stats['new_count'] ?? 0; ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title text-info">Read</h5>
                    <h3 class="mb-0"><?php echo $stats['read_count'] ?? 0; ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title text-success">Replied</h5>
                    <h3 class="mb-0"><?php echo $stats['replied_count'] ?? 0; ?></h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card filter-card">
                <div class="card-body">
                    <form method="GET" action="">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-3">
                                <label for="status" class="form-label filter-label">
                                    <i class="fas fa-filter me-2"></i>Filter by Status
                                </label>
                                <select class="form-control-custom filter-select" id="status" name="status">
                                    <option value="all" <?php echo $status_filter === 'all' ? 'selected' : ''; ?>>All Status</option>
                                    <option value="new" <?php echo $status_filter === 'new' ? 'selected' : ''; ?>>New</option>
                                    <option value="read" <?php echo $status_filter === 'read' ? 'selected' : ''; ?>>Read</option>
                                    <option value="replied" <?php echo $status_filter === 'replied' ? 'selected' : ''; ?>>Replied</option>
                                    <option value="archived" <?php echo $status_filter === 'archived' ? 'selected' : ''; ?>>Archived</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn-filter">
                                    <i class="fas fa-search me-2"></i>FILTER
                                </button>
                                <a href="reports.php" class="btn-clear">
                                    <i class="fas fa-times me-2"></i>CLEAR
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Reports Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 reports-header-title" style="font-weight: 600;">Contact Reports (<?php echo count($reports ?? []); ?>)</h5>
        </div>
        <div class="card-body">
            <?php if (empty($reports)): ?>
                <div class="alert alert-info text-center">
                    <i class="fas fa-inbox fa-3x mb-3"></i>
                    <h5>No reports found</h5>
                    <p class="mb-0">
                        <?php if ($status_filter !== 'all'): ?>
                            No reports with status "<?php echo htmlspecialchars($status_filter); ?>".
                        <?php else: ?>
                            No contact reports have been submitted yet.
                        <?php endif; ?>
                    </p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Subject</th>
                                <th>Message Preview</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($reports as $report): ?>
                                <tr data-report-id="<?php echo $report['id']; ?>"
                                    data-bs-target="#collapseReport<?php echo $report['id']; ?>" 
                                    aria-expanded="false" aria-controls="collapseReport<?php echo $report['id']; ?>"
                                    style="cursor: pointer;" class="hover-row report-row">
                                    <td><?php echo $report['id']; ?></td>
                                    <td><strong><?php echo htmlspecialchars($report['name']); ?></strong></td>
                                    <td>
                                        <a href="mailto:<?php echo htmlspecialchars($report['email']); ?>" onclick="event.stopPropagation();">
                                            <?php echo htmlspecialchars($report['email']); ?>
                                        </a>
                                    </td>
                                    <td><?php echo htmlspecialchars($report['subject']); ?></td>
                                    <td>
                                        <div class="message-preview-text" style="max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" 
                                             title="<?php echo htmlspecialchars($report['message']); ?>">
                                            <?php echo htmlspecialchars(substr($report['message'], 0, 50)) . (strlen($report['message']) > 50 ? '...' : ''); ?>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?php 
                                            echo match($report['status'] ?? 'new') {
                                                'new' => 'warning',
                                                'read' => 'info',
                                                'replied' => 'success',
                                                'archived' => 'secondary',
                                                default => 'secondary'
                                            };
                                        ?>">
                                            <?php echo ucfirst($report['status'] ?? 'new'); ?>
                                        </span>
                                    </td>
                                    <td><?php echo isset($report['created_at']) ? date('M j, Y H:i', strtotime($report['created_at'])) : 'N/A'; ?></td>
                                    <td class="actions-cell" onclick="event.stopPropagation();">
                                        <div class="d-flex align-items-center gap-2" onclick="event.stopPropagation();">
                                            <!-- Modern Dropdown Menu -->
                                            <div class="dropdown" onclick="event.stopPropagation();">
                                                <button class="btn btn-sm btn-outline-primary dropdown-toggle modern-dropdown" 
                                                        type="button" 
                                                        id="actionsDropdown<?php echo $report['id']; ?>" 
                                                        data-bs-toggle="dropdown" 
                                                        aria-expanded="false"
                                                        onclick="event.stopPropagation();">
                                                    <i class="fas fa-cog me-1"></i>Actions
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end modern-dropdown-menu" 
                                                    aria-labelledby="actionsDropdown<?php echo $report['id']; ?>"
                                                    onclick="event.stopPropagation();">
                                                    <li>
                                                        <h6 class="dropdown-header">
                                                            <i class="fas fa-tag me-2"></i>Update Status
                                                        </h6>
                                                    </li>
                                                    <li>
                                                        <form method="POST" action="" class="dropdown-item-form">
                                                            <input type="hidden" name="report_id" value="<?php echo $report['id']; ?>">
                                                            <input type="hidden" name="update_status" value="1">
                                                            <input type="hidden" name="status" value="new">
                                                            <button type="submit" class="dropdown-item <?php echo ($report['status'] ?? '') === 'new' ? 'active' : ''; ?>">
                                                                <i class="fas fa-circle text-warning me-2"></i>New
                                                            </button>
                                                        </form>
                                                    </li>
                                                    <li>
                                                        <form method="POST" action="" class="dropdown-item-form">
                                                            <input type="hidden" name="report_id" value="<?php echo $report['id']; ?>">
                                                            <input type="hidden" name="update_status" value="1">
                                                            <input type="hidden" name="status" value="read">
                                                            <button type="submit" class="dropdown-item <?php echo ($report['status'] ?? '') === 'read' ? 'active' : ''; ?>">
                                                                <i class="fas fa-circle text-info me-2"></i>Read
                                                            </button>
                                                        </form>
                                                    </li>
                                                    <li>
                                                        <form method="POST" action="" class="dropdown-item-form">
                                                            <input type="hidden" name="report_id" value="<?php echo $report['id']; ?>">
                                                            <input type="hidden" name="update_status" value="1">
                                                            <input type="hidden" name="status" value="replied">
                                                            <button type="submit" class="dropdown-item <?php echo ($report['status'] ?? '') === 'replied' ? 'active' : ''; ?>">
                                                                <i class="fas fa-circle text-success me-2"></i>Replied
                                                            </button>
                                                        </form>
                                                    </li>
                                                    <li>
                                                        <form method="POST" action="" class="dropdown-item-form">
                                                            <input type="hidden" name="report_id" value="<?php echo $report['id']; ?>">
                                                            <input type="hidden" name="update_status" value="1">
                                                            <input type="hidden" name="status" value="archived">
                                                            <button type="submit" class="dropdown-item <?php echo ($report['status'] ?? '') === 'archived' ? 'active' : ''; ?>">
                                                                <i class="fas fa-circle text-secondary me-2"></i>Archived
                                                            </button>
                                                        </form>
                                                    </li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <a class="dropdown-item text-danger" 
                                                           href="?delete=<?php echo $report['id']; ?>" 
                                                           onclick="return confirm('Are you sure you want to delete this report?');">
                                                            <i class="fas fa-trash-alt me-2"></i>Delete Report
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                
                                <!-- Collapsible row for full report details -->
                                <tr class="collapse" id="collapseReport<?php echo $report['id']; ?>" data-bs-parent="tbody">
                                    <td colspan="8">
                                        <div class="report-details-container">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <h6 class="report-details-title mb-3"><i class="fas fa-info-circle me-2"></i>Full Report Details</h6>
                                                    <table class="table table-borderless table-sm report-details-table">
                                                        <tr>
                                                            <th width="120">Report ID:</th>
                                                            <td><?php echo $report['id']; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th>Name:</th>
                                                            <td><strong><?php echo htmlspecialchars($report['name']); ?></strong></td>
                                                        </tr>
                                                        <tr>
                                                            <th>Email:</th>
                                                            <td>
                                                                <a href="mailto:<?php echo htmlspecialchars($report['email']); ?>" class="report-email-link">
                                                                    <?php echo htmlspecialchars($report['email']); ?>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Subject:</th>
                                                            <td><strong><?php echo htmlspecialchars($report['subject']); ?></strong></td>
                                                        </tr>
                                                        <tr>
                                                            <th>Status:</th>
                                                            <td>
                                                                <span class="badge bg-<?php 
                                                                    echo match($report['status'] ?? 'new') {
                                                                        'new' => 'warning',
                                                                        'read' => 'info',
                                                                        'replied' => 'success',
                                                                        'archived' => 'secondary',
                                                                        default => 'secondary'
                                                                    };
                                                                ?>">
                                                                    <?php echo ucfirst($report['status'] ?? 'new'); ?>
                                                                </span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Date Submitted:</th>
                                                            <td><?php echo isset($report['created_at']) ? date('F j, Y \a\t g:i A', strtotime($report['created_at'])) : 'N/A'; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th>Full Message:</th>
                                                            <td>
                                                                <div class="report-message-box">
                                                                    <?php echo nl2br(htmlspecialchars($report['message'])); ?>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
    /* Page Title */
    h1 {
        color: #ffffff !important;
        font-weight: 700;
    }
    
    h1 i {
        color: #5dade2;
    }
    
    /* Light mode page title */
    body:not(.dark) h1 {
        color: #212529 !important;
    }
    
    body:not(.dark) h1 i {
        color: #007bff;
    }
    
    /* Filter Card - Dark Style with Smooth Corners */
    .filter-card {
        background: #2b3040 !important;
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 15px !important;
        overflow: hidden;
    }
    
    .filter-label {
        color: #ffffff !important;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }
    
    .filter-label i {
        color: #5dade2;
    }
    
    .filter-select {
        width: 100%;
        padding: 0.875rem 1rem;
        font-size: 1rem;
        border: 2px solid #3b3f45;
        border-radius: 25px !important;
        background-color: #383e4a;
        color: #e3e6ea;
        transition: all 0.3s ease;
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23e3e6ea' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 1rem center;
        background-size: 12px;
        padding-right: 2.5rem;
    }
    
    .filter-select:focus {
        outline: none;
        border-color: #5dade2;
        box-shadow: 0 0 0 0.2rem rgba(93, 173, 226, 0.2);
        background-color: #383e4a;
    }
    
    .filter-select:focus {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%235dade2' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
    }
    
    /* Filter Buttons - Smooth Rounded Corners */
    .btn-filter {
        background: linear-gradient(135deg, #007bff 0%, #0056b3 50%, #004085 100%);
        border: none;
        color: #fff;
        padding: 0.75rem 1.5rem;
        font-size: 0.95rem;
        font-weight: 700;
        border-radius: 25px !important;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0, 123, 255, 0.4);
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
    }
    
    .btn-filter:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 123, 255, 0.6);
        background: linear-gradient(135deg, #0056b3 0%, #004085 50%, #003366 100%);
        color: #fff;
        text-decoration: none;
    }
    
    .btn-clear {
        background: linear-gradient(135deg, #6c757d 0%, #5a6268 50%, #495057 100%);
        border: none;
        color: #fff;
        padding: 0.75rem 1.5rem;
        font-size: 0.95rem;
        font-weight: 700;
        border-radius: 25px !important;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(108, 117, 125, 0.4);
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
        margin-left: 0.5rem;
    }
    
    .btn-clear:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(108, 117, 125, 0.6);
        background: linear-gradient(135deg, #5a6268 0%, #495057 50%, #343a40 100%);
        color: #fff;
        text-decoration: none;
    }
    
    /* Report Details Container - Dark Style with Smooth Corners */
    .report-details-container {
        background: rgba(43, 48, 64, 0.95);
        border-radius: 20px !important;
        padding: 2rem;
        margin: 1rem 0;
        border: 1px solid rgba(255, 255, 255, 0.1);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        overflow: hidden;
    }
    
    .report-details-title {
        color: #ffffff !important;
        font-size: 1.25rem;
        font-weight: 700;
        padding-bottom: 1rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .report-details-title i {
        color: #5dade2;
    }
    
    .report-details-table {
        color: #e3e6ea;
    }
    
    .report-details-table th {
        color: #ffffff !important;
        font-weight: 600;
        padding: 0.75rem 0.5rem;
    }
    
    .report-details-table td {
        color: #e3e6ea !important;
        padding: 0.75rem 0.5rem;
    }
    
    .report-details-table strong {
        color: #ffffff !important;
    }
    
    .report-email-link {
        color: #5dade2 !important;
        text-decoration: none;
    }
    
    .report-email-link:hover {
        color: #4a9fd1 !important;
        text-decoration: underline;
    }
    
    .report-message-box {
        background: #383e4a;
        border: 2px solid #3b3f45;
        border-radius: 15px !important;
        padding: 1.5rem;
        color: #e3e6ea !important;
        min-height: 100px;
    }
    
    /* Statistics Cards - Smooth Rounded Corners */
    .card.text-center {
        border-radius: 15px !important;
        overflow: hidden;
    }
    
    /* Table Responsive Container - Smooth Corners */
    .table-responsive {
        border-radius: 15px;
        overflow: hidden;
    }
    
    /* Alert Boxes - Smooth Corners */
    .alert {
        border-radius: 15px !important;
        overflow: hidden;
    }
    
    /* Cards - Solid Dark Background with Smooth Corners */
    .card {
        background: #2b3040 !important;
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 15px !important;
        overflow: hidden;
    }
    
    /* Card Headers - White Text */
    .card-header {
        background: #2b3040 !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .card-header h5,
    .reports-header-title {
        color: #ffffff !important;
        font-weight: 600;
    }
    
    /* Light mode card header */
    body:not(.dark) .card-header {
        background: #f8f9fa !important;
        border-bottom: 1px solid #e9ecef;
    }
    
    body:not(.dark) .card-header h5,
    body:not(.dark) .reports-header-title,
    body:not(.dark) .card .card-header h5,
    body:not(.dark) .card .card-header .reports-header-title {
        color: #212529 !important;
        font-weight: 600 !important;
    }
    
    /* Ensure Contact Reports heading is visible in light mode */
    body:not(.dark) h5.reports-header-title,
    body:not(.dark) .card-header .reports-header-title {
        color: #212529 !important;
        font-weight: 600 !important;
    }
    
    /* Card Body - Dark Background */
    .card-body {
        background: #2b3040 !important;
        color: #e3e6ea;
    }
    
    /* Table Styling - Dark Theme with Smooth Corners */
    .table {
        color: #e3e6ea;
        border-radius: 12px;
        overflow: hidden;
    }
    
    .table thead th {
        color: #ffffff !important;
        border-bottom: 2px solid rgba(255, 255, 255, 0.1);
        background: #2b3040;
        border-radius: 0;
    }
    
    .table thead th:first-child {
        border-top-left-radius: 12px;
    }
    
    .table thead th:last-child {
        border-top-right-radius: 12px;
    }
    
    .table tbody td {
        color: #e3e6ea !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    }
    
    /* Message Preview Text - Ensure visibility */
    .message-preview-text {
        color: #e3e6ea !important;
        font-weight: 400;
    }
    
    body.dark .message-preview-text {
        color: #e3e6ea !important;
    }
    
    /* Light mode - Make message preview clearly visible */
    body:not(.dark) .message-preview-text {
        color: #212529 !important;
        font-weight: 400;
    }
    
    /* Ensure message preview is visible in table cells */
    body:not(.dark) .table tbody td .message-preview-text {
        color: #212529 !important;
    }
    
    body.dark .table tbody td .message-preview-text {
        color: #e3e6ea !important;
    }
    
    .table tbody tr:last-child td:first-child {
        border-bottom-left-radius: 12px;
    }
    
    .table tbody tr:last-child td:last-child {
        border-bottom-right-radius: 12px;
    }
    
    .table tbody tr:hover {
        background-color: rgba(255, 255, 255, 0.05) !important;
    }
    
    .table tbody td strong {
        color: #ffffff !important;
    }
    
    body:not(.dark) .table tbody td strong {
        color: #212529 !important;
    }
    
    .table tbody td a {
        color: #5dade2 !important;
    }
    
    body:not(.dark) .table tbody td a {
        color: #007bff !important;
    }
    
    .table tbody td a:hover {
        color: #4a9fd1 !important;
    }
    
    body:not(.dark) .table tbody td a:hover {
        color: #0056b3 !important;
    }
    
    /* Badges - Smooth Rounded Corners */
    .badge {
        border-radius: 20px !important;
        padding: 0.5rem 0.75rem;
        font-weight: 600;
    }
    
    .hover-row:hover {
        background-color: rgba(255, 255, 255, 0.05) !important;
    }
    .collapse-icon {
        transition: transform 0.3s ease;
        color: #e3e6ea;
    }
    .collapse-icon.rotated {
        transform: rotate(180deg);
    }
    
    /* Modern Dropdown Styling - Smooth Corners */
    .modern-dropdown {
        border-radius: 25px !important;
        padding: 6px 16px;
        font-weight: 500;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .modern-dropdown:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }
    
    .modern-dropdown-menu {
        border-radius: 15px !important;
        box-shadow: 0 8px 24px rgba(0,0,0,0.15);
        border: 1px solid rgba(0,0,0,0.1);
        padding: 8px;
        margin-top: 8px;
        min-width: 220px;
        animation: slideDown 0.2s ease;
        background: #fff !important;
        overflow: hidden;
    }
    
    /* Dark mode support for dropdown */
    body.dark .modern-dropdown-menu {
        background: #2d2d2d !important;
        border-color: rgba(255,255,255,0.1) !important;
        box-shadow: 0 8px 24px rgba(0,0,0,0.5) !important;
    }
    
    body.dark .modern-dropdown-menu .dropdown-item {
        color: #e0e0e0 !important;
    }
    
    body.dark .modern-dropdown-menu .dropdown-item:hover {
        color: white !important;
    }
    
    body.dark .modern-dropdown-menu .dropdown-header {
        color: #9e9e9e !important;
    }
    
    body.dark .modern-dropdown-menu .dropdown-divider {
        border-color: rgba(255,255,255,0.1) !important;
    }
    
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .modern-dropdown-menu .dropdown-header {
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #6c757d;
        padding: 8px 12px;
        margin-bottom: 4px;
    }
    
    .modern-dropdown-menu .dropdown-item {
        border-radius: 10px !important;
        padding: 10px 16px;
        margin: 2px 0;
        transition: all 0.2s ease;
        font-weight: 500;
        display: flex;
        align-items: center;
    }
    
    .modern-dropdown-menu .dropdown-item:hover {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        transform: translateX(4px);
    }
    
    .modern-dropdown-menu .dropdown-item.active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        font-weight: 600;
    }
    
    .modern-dropdown-menu .dropdown-item.text-danger:hover {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
    }
    
    .modern-dropdown-menu .dropdown-item i {
        width: 20px;
        text-align: center;
    }
    
    .modern-dropdown-menu .dropdown-divider {
        margin: 8px 0;
        opacity: 0.2;
    }
    
    .dropdown-item-form {
        margin: 0;
        padding: 0;
    }
    
    .dropdown-item-form button {
        width: 100%;
        text-align: left;
        border: none;
        background: none;
        padding: 0;
    }
    
    /* ============================================
       LIGHT MODE STYLES - Match Dark Mode Quality
       ============================================ */
    
    /* Light mode cards */
    body:not(.dark) .card {
        background: #ffffff !important;
        border: 1px solid #e9ecef !important;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1) !important;
    }
    
    body:not(.dark) .card-body {
        background: #ffffff !important;
        color: #212529 !important;
    }
    
    /* Light mode filter card */
    body:not(.dark) .filter-card {
        background: #ffffff !important;
        border: 1px solid #e9ecef !important;
    }
    
    body:not(.dark) .filter-label {
        color: #212529 !important;
    }
    
    body:not(.dark) .filter-label i {
        color: #007bff;
    }
    
    body:not(.dark) .filter-select {
        background-color: #ffffff !important;
        border: 2px solid #ced4da !important;
        color: #212529 !important;
    }
    
    body:not(.dark) .filter-select:focus {
        border-color: #007bff !important;
        background-color: #ffffff !important;
    }
    
    /* Light mode tables */
    body:not(.dark) .table {
        color: #212529 !important;
        background: #ffffff !important;
    }
    
    body:not(.dark) .table thead th {
        color: #212529 !important;
        background: #f8f9fa !important;
        border-bottom: 2px solid #dee2e6 !important;
    }
    
    body:not(.dark) .table tbody td {
        color: #212529 !important;
        border-bottom: 1px solid #e9ecef !important;
    }
    
    /* Force message preview visibility in light mode */
    body:not(.dark) .table tbody td .message-preview-text,
    body:not(.dark) table tbody td .message-preview-text,
    body:not(.dark) .table-responsive .table tbody td .message-preview-text {
        color: #212529 !important;
        font-weight: 400 !important;
    }
    
    body:not(.dark) .table tbody tr:hover {
        background-color: #f8f9fa !important;
    }
    
    body:not(.dark) .table tbody tr:hover td .message-preview-text {
        color: #212529 !important;
    }
    
    body:not(.dark) .table tbody tr:nth-child(even) {
        background-color: #f8f9fa;
    }
    
    body:not(.dark) .table tbody tr:nth-child(even):hover {
        background-color: #e9ecef !important;
    }
    
    body:not(.dark) .table tbody tr:nth-child(even) td .message-preview-text {
        color: #212529 !important;
    }
    
    /* Light mode statistics cards */
    body:not(.dark) .card.text-center {
        background: #ffffff !important;
        border: 1px solid #e9ecef !important;
    }
    
    body:not(.dark) .card.text-center .card-body {
        background: #ffffff !important;
        color: #212529 !important;
    }
    
    body:not(.dark) .card.text-center h3 {
        color: #212529 !important;
    }
    
    body:not(.dark) .card.text-center .card-title {
        color: #212529 !important;
    }
    
    /* Light mode report details */
    body:not(.dark) .report-details-container {
        background: #f8f9fa !important;
        border: 1px solid #e9ecef !important;
    }
    
    body:not(.dark) .report-details-title {
        color: #212529 !important;
    }
    
    body:not(.dark) .report-details-title i {
        color: #007bff;
    }
    
    body:not(.dark) .report-details-table {
        color: #212529 !important;
    }
    
    body:not(.dark) .report-details-table th {
        color: #212529 !important;
    }
    
    body:not(.dark) .report-details-table td {
        color: #212529 !important;
    }
    
    body:not(.dark) .report-details-table strong {
        color: #212529 !important;
    }
    
    body:not(.dark) .report-email-link {
        color: #007bff !important;
    }
    
    body:not(.dark) .report-email-link:hover {
        color: #0056b3 !important;
    }
    
    body:not(.dark) .report-message-box {
        background: #ffffff !important;
        border: 2px solid #e9ecef !important;
        color: #212529 !important;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Prevent row collapse when clicking in Actions column
    document.querySelectorAll('.actions-cell').forEach(function(cell) {
        cell.addEventListener('click', function(e) {
            e.stopPropagation();
        }, true); // Use capture phase
    });
    
    // Handle collapse functionality
    document.querySelectorAll('.report-row').forEach(function(row) {
        const targetId = row.getAttribute('data-bs-target');
        const collapseTarget = document.querySelector(targetId);
        
        if (collapseTarget) {
            collapseTarget.addEventListener('show.bs.collapse', function() {
                // Collapse shown
            });
            
            collapseTarget.addEventListener('hide.bs.collapse', function() {
                // Collapse hidden
            });
        }
    });
    
    // Handle row click to toggle collapse (excluding Actions column)
    document.querySelectorAll('.report-row').forEach(function(row) {
        row.addEventListener('click', function(e) {
            // Don't collapse if clicking in Actions column
            if (e.target.closest('.actions-cell')) {
                return;
            }
            
            // Don't collapse if clicking on email link
            if (e.target.closest('a[href^="mailto:"]')) {
                return;
            }
            
            // Toggle collapse manually
            const targetId = row.getAttribute('data-bs-target');
            const collapseTarget = document.querySelector(targetId);
            
            if (collapseTarget) {
                const bsCollapse = new bootstrap.Collapse(collapseTarget, {
                    toggle: true
                });
            }
        });
    });
});
</script>
