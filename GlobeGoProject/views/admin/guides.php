<?php
// Admin guides management view
// Expects: $guides_map (array of guides with their tours)
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
            <h1 class="mb-4">Manage Tour Guides</h1>
            <p class="text-muted">Edit guide descriptions and languages for each trip they offer.</p>
        </div>
    </div>

    <?php if (empty($guides_map)): ?>
        <div class="alert alert-info">
            <h5>No Guides Found</h5>
            <p>There are no guides with tours in the system yet.</p>
        </div>
    <?php else: ?>
        <?php foreach ($guides_map as $guide): ?>
            <div class="card mb-4">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0">
                                <?php echo htmlspecialchars($guide['first_name'] . ' ' . $guide['last_name']); ?>
                                <?php if ($guide['verified']): ?>
                                    <span class="badge bg-success ms-2">Verified</span>
                                <?php endif; ?>
                            </h5>
                            <small class="text-muted"><?php echo htmlspecialchars($guide['email']); ?></small>
                        </div>
                        <button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="collapse" 
                                data-bs-target="#guide-<?php echo $guide['guide_id']; ?>" 
                                aria-expanded="false" aria-controls="guide-<?php echo $guide['guide_id']; ?>">
                            <i class="fas fa-edit"></i> Edit Guide Info
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Guide's Tours -->
                    <div class="mb-3">
                        <h6>Tours Offered:</h6>
                        <div class="row">
                            <?php foreach ($guide['tours'] as $tour): ?>
                                <div class="col-md-6 mb-2">
                                    <div class="border rounded p-2">
                                        <strong><?php echo htmlspecialchars($tour['title']); ?></strong><br>
                                        <small class="text-muted">
                                            <i class="fas fa-tag"></i> <?php echo htmlspecialchars($tour['category']); ?>
                                            <?php if ($tour['attraction_name']): ?>
                                                | <i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($tour['attraction_name']); ?>
                                            <?php endif; ?>
                                        </small>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Current Guide Info -->
                    <div class="mb-3">
                        <h6>Current Information:</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Bio:</strong></p>
                                <p class="text-muted"><?php echo $guide['bio'] ? nl2br(htmlspecialchars($guide['bio'])) : '<em>No bio set</em>'; ?></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Languages:</strong></p>
                                <p class="text-muted"><?php echo $guide['languages'] ? htmlspecialchars($guide['languages']) : '<em>No languages specified</em>'; ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Edit Form (Collapsible) -->
                    <div class="collapse" id="guide-<?php echo $guide['guide_id']; ?>">
                        <hr>
                        <form method="POST" action="">
                            <input type="hidden" name="action" value="update_guide">
                            <input type="hidden" name="guide_id" value="<?php echo $guide['guide_id']; ?>">
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="bio-<?php echo $guide['guide_id']; ?>" class="form-label">Guide Description/Bio</label>
                                    <textarea class="form-control" id="bio-<?php echo $guide['guide_id']; ?>" 
                                              name="bio" rows="5" 
                                              placeholder="Enter guide's bio or description..."><?php echo htmlspecialchars($guide['bio'] ?? ''); ?></textarea>
                                    <small class="form-text text-muted">This description will be shown on all tours offered by this guide.</small>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="languages-<?php echo $guide['guide_id']; ?>" class="form-label">Languages Spoken</label>
                                    <input type="text" class="form-control" id="languages-<?php echo $guide['guide_id']; ?>" 
                                           name="languages" 
                                           value="<?php echo htmlspecialchars($guide['languages'] ?? ''); ?>"
                                           placeholder="e.g., English, Spanish, French">
                                    <small class="form-text text-muted">Separate multiple languages with commas.</small>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Update Guide Information
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<style>
    .card {
        transition: box-shadow 0.3s ease;
    }
    .card:hover {
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
</style>



