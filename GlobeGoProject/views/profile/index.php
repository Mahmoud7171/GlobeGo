<?php
// Profile view.
// Expects: $user (User instance), $error_message, $success_message
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <h1 class="mb-4">My Profile</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <!-- Profile Card -->
            <div class="card mb-4">
                <div class="card-body text-center">
                    <!-- Default avatar for light mode -->
                    <div class="default-avatar-light mb-3" id="default-avatar-light" style="<?php echo $user->profile_image ? 'display: none;' : ''; ?>">
                        <svg width="150" height="150" viewBox="0 0 150 150" xmlns="http://www.w3.org/2000/svg">
                            <rect width="150" height="150" fill="#e0e0e0"/>
                            <circle cx="75" cy="50" r="25" fill="#ffffff"/>
                            <path d="M 30 100 Q 30 80 75 80 Q 120 80 120 100 L 120 150 L 30 150 Z" fill="#ffffff"/>
                        </svg>
                    </div>
                    <!-- Default avatar for dark mode -->
                    <div class="default-avatar-dark mb-3" id="default-avatar-dark" style="display: none;">
                        <svg width="150" height="150" viewBox="0 0 150 150" xmlns="http://www.w3.org/2000/svg">
                            <rect width="150" height="150" fill="#2d2d2d"/>
                            <circle cx="75" cy="50" r="25" fill="#000000"/>
                            <path d="M 30 100 Q 30 80 75 80 Q 120 80 120 100 L 120 150 L 30 150 Z" fill="#000000"/>
                        </svg>
                    </div>
                    <img src="<?php echo $user->profile_image ?: ''; ?>" 
                         class="profile-image mb-3" alt="Profile Photo" id="profile-preview"
                         style="<?php echo $user->profile_image ? '' : 'display: none;'; ?>">
                    <h4><?php echo $user->first_name . ' ' . $user->last_name; ?></h4>
                    <p class="text-muted"><?php echo ucfirst($user->role); ?></p>
                    
                    <?php if ($user->verified): ?>
                        <p class="text-success"><i class="fas fa-check-circle"></i> Verified Account</p>
                    <?php endif; ?>
                    
                    <div class="mt-3">
                        <span class="badge bg-<?php echo $user->status === 'active' ? 'success' : 'warning'; ?>">
                            <?php echo ucfirst($user->status); ?>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Account Information -->
            <div class="card">
                <div class="card-header">
                    <h5>Account Information</h5>
                </div>
                <div class="card-body">
                    <p><strong>Email:</strong> <?php echo $user->email; ?></p>
                    <p><strong>Member Since:</strong> <?php echo date('F Y', strtotime($user->created_at ?? 'now')); ?></p>
                    <p><strong>Role:</strong> <?php echo ucfirst($user->role); ?></p>
                    
                    <?php if ($user->phone): ?>
                        <p><strong>Phone:</strong> <?php echo $user->phone; ?></p>
                    <?php endif; ?>
                    
                    <?php if ($user->languages): ?>
                        <p><strong>Languages:</strong> <?php echo $user->languages; ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5>Edit Profile</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($error_message)): ?>
                        <div class="alert alert-danger"><?php echo $error_message; ?></div>
                    <?php endif; ?>
                    
                    <?php if (!empty($success_message)): ?>
                        <div class="alert alert-success"><?php echo $success_message; ?></div>
                    <?php endif; ?>
                    
                    <form method="POST" action="">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="first_name" class="form-label">First Name *</label>
                                    <input type="text" class="form-control" id="first_name" name="first_name" 
                                           value="<?php echo $user->first_name; ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="last_name" class="form-label">Last Name *</label>
                                    <input type="text" class="form-control" id="last_name" name="last_name" 
                                           value="<?php echo $user->last_name; ?>" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" id="phone" name="phone" 
                                   value="<?php echo $user->phone; ?>">
                        </div>

                        <div class="form-group mb-3">
                            <label for="profile_image" class="form-label">Profile Image URL</label>
                            <input type="url" class="form-control" id="profile_image" name="profile_image" 
                                   value="<?php echo $user->profile_image; ?>">
                        </div>

                        <?php if (isGuide()): ?>
                        <div class="form-group mb-3">
                            <label for="bio" class="form-label">Bio</label>
                            <textarea class="form-control" id="bio" name="bio" rows="4" 
                                      placeholder="Tell travelers about yourself, your experience, and what makes your tours special..."><?php echo $user->bio; ?></textarea>
                        </div>

                        <div class="form-group mb-3">
                            <label for="languages" class="form-label">Languages Spoken</label>
                            <input type="text" class="form-control" id="languages" name="languages" 
                                   value="<?php echo $user->languages; ?>" 
                                   placeholder="e.g., English, Spanish, French">
                            <small class="form-text text-muted">Separate languages with commas</small>
                        </div>
                        <?php endif; ?>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Update Profile</button>
                            <a href="dashboard.php" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Change Password Section -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5>Change Password</h5>
                </div>
                <div class="card-body">
                    <form id="password-form">
                        <div class="form-group mb-3">
                            <label for="current_password" class="form-label">Current Password</label>
                            <input type="password" class="form-control" id="current_password" name="current_password" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="new_password" class="form-label">New Password</label>
                            <input type="password" class="form-control" id="new_password" name="new_password" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="confirm_new_password" class="form-label">Confirm New Password</label>
                            <input type="password" class="form-control" id="confirm_new_password" name="confirm_new_password" required>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-warning">Change Password</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.default-avatar-light,
.default-avatar-dark {
    display: inline-block;
    margin: 0 auto;
}

.default-avatar-light svg,
.default-avatar-dark svg {
    border-radius: 50%;
    width: 150px;
    height: 150px;
}

body.dark .default-avatar-light {
    display: none !important;
}

body.dark .default-avatar-dark {
    display: inline-block !important;
}

body:not(.dark) .default-avatar-light {
    display: inline-block !important;
}

body:not(.dark) .default-avatar-dark {
    display: none !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Update default avatar based on dark mode
    function updateDefaultAvatar() {
        const isDark = document.body.classList.contains('dark');
        const lightAvatar = document.getElementById('default-avatar-light');
        const darkAvatar = document.getElementById('default-avatar-dark');
        const profilePreview = document.getElementById('profile-preview');
        
        if (lightAvatar && darkAvatar && !profilePreview.src) {
            if (isDark) {
                lightAvatar.style.display = 'none';
                darkAvatar.style.display = 'inline-block';
            } else {
                lightAvatar.style.display = 'inline-block';
                darkAvatar.style.display = 'none';
            }
        }
    }
    
    // Check on load
    updateDefaultAvatar();
    
    // Watch for dark mode changes
    const observer = new MutationObserver(updateDefaultAvatar);
    observer.observe(document.body, {
        attributes: true,
        attributeFilter: ['class']
    });
    
    // Profile image preview
    const profileImageInput = document.getElementById('profile_image');
    const profilePreview = document.getElementById('profile-preview');
    const lightAvatar = document.getElementById('default-avatar-light');
    const darkAvatar = document.getElementById('default-avatar-dark');

    if (profileImageInput && profilePreview) {
        profileImageInput.addEventListener('input', function() {
            if (this.value && this.value.trim() !== '') {
                profilePreview.src = this.value;
                profilePreview.style.display = 'inline-block';
                if (lightAvatar) lightAvatar.style.display = 'none';
                if (darkAvatar) darkAvatar.style.display = 'none';
                profilePreview.onerror = function() {
                    this.style.display = 'none';
                    updateDefaultAvatar();
                };
            } else {
                profilePreview.src = '';
                profilePreview.style.display = 'none';
                updateDefaultAvatar();
            }
        });
    }

    // Password form validation
    const passwordForm = document.getElementById('password-form');
    passwordForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const newPassword = document.getElementById('new_password').value;
        const confirmPassword = document.getElementById('confirm_new_password').value;
        
        if (newPassword !== confirmPassword) {
            GlobeGo.showAlert('New passwords do not match.', 'danger');
            return;
        }
        
        if (newPassword.length < 6) {
            GlobeGo.showAlert('Password must be at least 6 characters long.', 'danger');
            return;
        }
        
        // Simulate password change
        GlobeGo.showAlert('Password changed successfully!', 'success');
        passwordForm.reset();
    });
});
</script>


