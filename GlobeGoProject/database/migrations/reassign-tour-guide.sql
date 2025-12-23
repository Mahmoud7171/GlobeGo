-- Reassign tour ID 24 from Mina Hassan to Megan Fox
-- This script will:
-- 1. Find or create Megan Fox as a guide
-- 2. Update tour ID 24 to be assigned to Megan Fox

-- Step 1: Check if Megan Fox exists, if not create her
INSERT INTO users (email, password, first_name, last_name, role, verified, status, bio, languages)
SELECT 
    'megan.fox@globego.com' as email,
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi' as password, -- password: password
    'Megan' as first_name,
    'Fox' as last_name,
    'guide' as role,
    1 as verified,
    'active' as status,
    'Experienced tour guide specializing in historical and cultural tours. Passionate about sharing the mysteries and stories of ancient sites.' as bio,
    'English, Spanish' as languages
WHERE NOT EXISTS (
    SELECT 1 FROM users WHERE first_name = 'Megan' AND last_name = 'Fox' AND role = 'guide'
);

-- Step 2: Get Megan Fox's ID
SET @megan_fox_id = (SELECT id FROM users WHERE first_name = 'Megan' AND last_name = 'Fox' AND role = 'guide' LIMIT 1);

-- Step 3: Update tour ID 24 to be assigned to Megan Fox
UPDATE tours 
SET guide_id = @megan_fox_id 
WHERE id = 24;

-- Step 4: Verify the change
SELECT 
    t.id as tour_id,
    t.title as tour_title,
    u.id as guide_id,
    u.first_name,
    u.last_name,
    u.email
FROM tours t
LEFT JOIN users u ON t.guide_id = u.id
WHERE t.id = 24;



