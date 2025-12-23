// GlobeGo JavaScript Functions

document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Initialize popovers
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });

    // Add fade-in animation to cards
    const cards = document.querySelectorAll('.card');
    cards.forEach((card, index) => {
        card.style.animationDelay = `${index * 0.1}s`;
        card.classList.add('fade-in');
    });

    // Form validation
    initFormValidation();
    
    // Image lazy loading
    initLazyLoading();
    
    // Search functionality
    initSearch();
    
    // Booking functionality
    initBooking();
    // Theme toggle with persistence
    initThemeToggle();
    // Hero slideshow
    initHeroSlideshow();
    // Fake reviews for tour details page
    initFakeReviews();
    
    // Pagination ellipsis expansion - run immediately and after a delay
    initPaginationEllipsis();
    
    // Re-initialize after a short delay to catch dynamically loaded content
    setTimeout(() => {
        initPaginationEllipsis();
    }, 500);
    
    // Initialize idle screen - works on all pages
    initIdleScreen();
    
    // Initialize chatbot
    initChatbot();
});

// Also initialize idle screen if DOM is already loaded (for pages that load scripts after DOM)
if (document.readyState === 'loading') {
    // DOM is still loading, wait for DOMContentLoaded (handled above)
} else {
    // DOM is already loaded, initialize after a short delay to ensure elements exist
    setTimeout(() => {
        if (!window.idleScreenInitialized) {
            initIdleScreen();
        }
    }, 100);
}

// Fallback: Try to initialize when window loads (for edge cases)
window.addEventListener('load', function() {
    if (!window.idleScreenInitialized) {
        setTimeout(() => {
            initIdleScreen();
        }, 200);
    }
    
    // Initialize chatbot if not already initialized
    if (!window.chatbotInitialized) {
        initChatbot();
    }
});

// Chatbot Functionality
function initChatbot() {
    if (window.chatbotInitialized) {
        return;
    }
    window.chatbotInitialized = true;
    
    const chatbotToggle = document.getElementById('chatbot-toggle');
    const chatbotWindow = document.getElementById('chatbot-window');
    const chatbotClose = document.getElementById('chatbot-close');
    const chatbotInput = document.getElementById('chatbot-input');
    const chatbotSend = document.getElementById('chatbot-send');
    const chatbotMessages = document.getElementById('chatbot-messages');
    const chatbotBadge = document.querySelector('.chatbot-badge');
    
    if (!chatbotToggle || !chatbotWindow || !chatbotInput || !chatbotSend || !chatbotMessages) {
        console.warn('Chatbot elements not found');
        return;
    }
    
    // Comprehensive Knowledge Base - Website-specific information only
    const knowledgeBase = {
        refund: [
            {
                keywords: ['refund', 'money back', 'get money', 'cancel refund', 'refund policy', 'refund processing', 'refund time', 'when refund',
                    'استرداد', 'استرجاع', 'استرداد المال', 'سياسة الاسترداد', 'متى الاسترداد',
                    'remboursement', 'rembourser', 'politique de remboursement',
                    'reembolso', 'devolución', 'política de reembolso'],
                answer: 'Our refund policy is detailed and fair:\n\n📋 Tourist Cancellations:\n• More than 48 hours before tour: Full refund minus 5% processing fee\n• 24-48 hours before tour: 50% refund\n• Less than 24 hours before tour: No refund\n• No-show: No refund\n\n📋 Guide Cancellations:\n• If a guide cancels: You receive a full refund\n• We help find alternative tours or guides\n\n📋 Weather & Force Majeure:\n• Severe weather or natural disasters: Full refunds provided\n• Alternative dates may be offered when possible\n\n⏱️ Refund Processing:\n• Refunds processed within 5-10 business days\n• Refunds issued to original payment method\n• Processing fees are non-refundable'
            }
        ],
        cancellation: [
            {
                keywords: ['cancel', 'cancellation', 'cancel booking', 'cancel tour', 'modify booking', 'change booking', 'cancellation fee', 'cancellation policy',
                    'إلغاء', 'إلغاء الحجز', 'إلغاء الجولة', 'رسوم الإلغاء', 'سياسة الإلغاء',
                    'annuler', 'annulation', 'annuler réservation', 'annuler visite',
                    'cancelar', 'cancelación', 'cancelar reserva', 'cancelar tour'],
                answer: 'Cancellation policies on GlobeGo:\n\n✅ You can cancel anytime from your dashboard:\n• Go to "Your Booked Tours" section\n• Click "View" on the booking\n• Follow cancellation options\n\n💰 Refund amounts:\n• More than 48 hours: Full refund (minus 5% processing fee)\n• 24-48 hours: 50% refund\n• Less than 24 hours: No refund\n• No-show: No refund\n\n⚠️ Cancellation Fee:\n• 25% cancellation fee applies for cancellations\n• This fee is deducted from refunds\n\n🔄 If guide cancels:\n• You get full refund automatically\n• We help find alternative tours'
            }
        ],
        offers: [
            {
                keywords: ['offer', 'discount', 'special offer', 'deal', 'promotion', 'save', 'special offers page', 'offers section',
                    'عرض', 'خصم', 'عروض خاصة', 'صفقة', 'توفير', 'صفحة العروض',
                    'offre', 'réduction', 'offre spéciale', 'promotion', 'économiser',
                    'oferta', 'descuento', 'oferta especial', 'promoción', 'ahorrar'],
                answer: 'GlobeGo offers amazing deals! 🎁\n\n🌟 Special Offers Section:\n• Egypt tours - Pyramids, Egyptian Museum\n• India tours - Taj Mahal in Agra\n• Japan tours - Shibuya, Tokyo\n• These tours have exclusive discounted pricing\n• Visit the "Special offers" tab in navigation\n\n💰 Multi-Ticket Discounts (for non-offer tours):\n• Book 2 tickets = 5% OFF\n• Book 3 tickets = 10% OFF\n• Book 4+ tickets = 15% OFF\n• Automatically applied when booking 2+ tickets\n• Only applies to tours NOT in Special Offers section\n\n💡 How to claim:\n• Multi-ticket discount appears automatically when selecting 2+ participants\n• Special offers are visible on the Special Offers page\n• Discounts cannot be combined'
            }
        ],
        destinations: [
            {
                keywords: ['destination', 'where', 'places', 'location', 'tours available', 'what destinations', 'countries', 'cities', 'all destinations', 'everywhere',
                    'وجهة', 'أين', 'أماكن', 'موقع', 'الجولات المتاحة', 'ما الوجهات', 'بلدان', 'مدن', 'جميع الوجهات',
                    'destination', 'où', 'lieux', 'emplacement', 'visites disponibles', 'quelles destinations',
                    'destino', 'dónde', 'lugares', 'ubicación', 'tours disponibles', 'qué destinos'],
                answer: 'GlobeGo offers tours to amazing destinations worldwide! 🌍\n\n🌎 ALL Available Destinations:\n\n🇪🇬 EGYPT:\n• Pyramids of Giza - Ancient wonders\n• Grand Egyptian Museum - World\'s largest collection of Egyptian artifacts\n• Cairo city tours - Local culture and history\n\n🇮🇳 INDIA:\n• Taj Mahal - Agra, symbol of eternal love\n• Cultural and historical tours\n• Local guide experiences\n\n🇯🇵 JAPAN:\n• Shibuya Crossway - World\'s busiest pedestrian crossing, Tokyo\n• Tokyo city tours - Modern and traditional experiences\n• Shibuya district exploration\n\n🇫🇷 FRANCE:\n• Eiffel Tower - Paris, iconic iron tower\n• Paris Evening Walk - City of Light tours\n• Paris city tours - Iconic landmarks\n\n🇺🇸 USA:\n• Times Square - New York City, "Crossroads of the World"\n• NYC Food Tour - Diverse culinary scene\n• New York City tours - Iconic landmarks\n\n🇮🇹 ITALY:\n• Colosseum - Rome, ancient Roman amphitheater\n• Ancient Rome Discovery - Roman ruins and history\n• Rome tours - Eternal City experiences\n\n🇬🇧 UNITED KINGDOM:\n• Tower Bridge - London, Victorian suspension bridge\n• London Tower Bridge Experience - High-level walkways\n• London tours - Historical landmarks\n\n🇪🇸 SPAIN:\n• Sagrada Familia - Barcelona, Gaudí\'s unfinished masterpiece\n• Sagrada Familia & Gaudí\'s Barcelona - Architectural tours\n• Barcelona tours - Catalan culture\n\n🇵🇪 PERU:\n• Machu Picchu - Cusco, ancient Incan citadel\n• Machu Picchu Adventure - New Seven Wonders\n\n🇨🇳 CHINA:\n• Great Wall of China - Beijing, 2000+ years of history\n• Great Wall tours - Architectural achievements\n\n🇧🇷 BRAZIL:\n• Christ the Redeemer - Rio de Janeiro, iconic statue\n• Rio tours - Panoramic mountain views\n\n🇦🇺 AUSTRALIA:\n• Sydney Opera House - Architectural masterpiece\n• Sydney tours - UNESCO World Heritage site\n\n🇰🇭 CAMBODIA:\n• Angkor Wat - Siem Reap, largest religious monument\n• Angkor Wat Temple Complex - Khmer Empire history\n\n🇯🇴 JORDAN:\n• Petra - The Rose City, carved into sandstone cliffs\n• Petra tours - Nabataean civilization\n\n🇬🇧 UNITED KINGDOM (Additional):\n• Stonehenge - Wiltshire, mysterious prehistoric monument\n• Stonehenge Mystery Tour - Ancient theories\n\n🔍 How to explore:\n• Visit "Destinations" page to see all tours\n• Use search bar to filter by location\n• Filter by category, price, or date\n• Click on any tour for detailed information\n\n📍 Each destination offers:\n• Multiple tour options\n• Different categories (Historical, Food, Walking, Adventure, Cultural, Nature)\n• Various price ranges ($45-$120)\n• Flexible scheduling with multiple dates'
            }
        ],
        egypt: [
            {
                keywords: ['egypt', 'pyramid', 'cairo', 'egyptian', 'pyramids of giza', 'egyptian museum', 'grand egyptian museum', 'giza'],
                answer: 'Egypt tours are incredible! 🇪🇬\n\n🏛️ Available Attractions & Tours:\n\n1. Pyramids of Giza:\n• Explore ancient wonders and mysteries\n• Historical tours of the Great Pyramid\n• Learn about pharaohs and ancient Egypt\n• Categories: Historical, Cultural\n\n2. Grand Egyptian Museum:\n• World\'s largest collection of ancient Egyptian artifacts\n• Discover treasures of Tutankhamun\n• Experience rich history of ancient Egypt\n• Tour: "The Grand Egyptian Museum"\n• Price: $85 | Duration: 4 hours | Max: 20 participants\n• Category: Museum Tour\n• Meeting Point: Grand Egyptian Museum Main Entrance\n\n3. Cairo City Tours:\n• Experience local culture and traditions\n• Walking tours through historic districts\n• Cultural immersion experiences\n\n💰 Special Pricing:\n• Egypt tours featured in Special Offers section\n• Exclusive discounted pricing available\n• Visit "Special offers" tab to see deals\n\n📅 Tour Details:\n• Various tour schedules available\n• Led by verified local guides\n• Multiple categories: Historical, Cultural, Walking, Museum Tour\n\n🔍 Find Egypt tours:\n• Go to Destinations page\n• Search for "Egypt" or "Cairo"\n• Or visit Special Offers page'
            }
        ],
        india: [
            {
                keywords: ['india', 'taj mahal', 'agra', 'indian', 'taj'],
                answer: 'India tours are amazing! 🇮🇳\n\n🏛️ Available Attractions & Tours:\n\n1. Taj Mahal - Agra:\n• Symbol of eternal love\n• One of the most beautiful buildings in the world\n• UNESCO World Heritage site\n• Mughal architecture masterpiece\n• Tour: "Taj Mahal Experience"\n• Price: $75 | Duration: 3 hours | Max: 20 participants\n• Category: Cultural Tour\n• Meeting Point: Taj Mahal East Gate\n\n2. Cultural & Historical Tours:\n• Local guide experiences with authentic insights\n• Traditional Indian culture immersion\n• Historical sites and monuments\n\n💰 Special Pricing:\n• India tours featured in Special Offers section\n• Exclusive discounted pricing\n• Visit "Special offers" tab for deals\n\n📅 Tour Features:\n• Multiple tour schedules available\n• Verified local Indian guides\n• Historical and Cultural categories\n• Flexible booking options\n\n🔍 Find India tours:\n• Search "India" or "Taj Mahal" on Destinations page\n• Check Special Offers page for exclusive deals\n• Filter by Cultural Tour category'
            }
        ],
        japan: [
            {
                keywords: ['japan', 'tokyo', 'shibuya', 'japanese', 'shibuya crossway', 'hachiko'],
                answer: 'Japan tours are fantastic! 🇯🇵\n\n🏙️ Available Attractions & Tours:\n\n1. Shibuya Crossway - Tokyo:\n• World\'s busiest pedestrian crossing\n• Watch thousands cross simultaneously\n• Vibrant Shibuya district exploration\n• Shopping, dining, and entertainment\n• Tour: "Shibuya Crossway Experience"\n• Price: $55 | Duration: 2 hours | Max: 15 participants\n• Category: City Tour\n• Meeting Point: Hachiko Statue, Shibuya Station\n\n2. Tokyo City Tours:\n• Modern and traditional experiences combined\n• Explore vibrant Japanese culture\n• Iconic landmarks and neighborhoods\n• Cultural immersion\n\n💰 Special Pricing:\n• Japan tours in Special Offers section\n• Exclusive discounted pricing\n• Visit "Special offers" tab\n\n📅 Tour Features:\n• Various schedules available\n• Verified local Japanese guides\n• Cultural, Walking, City Tour, and Adventure categories\n• Flexible timing options\n\n🔍 Find Japan tours:\n• Search "Japan" or "Tokyo" or "Shibuya" on Destinations page\n• Check Special Offers for exclusive deals\n• Filter by City Tour category'
            }
        ],
        paris: [
            {
                keywords: ['paris', 'france', 'city of light', 'eiffel tower', 'trocadero', 'seine'],
                answer: 'Paris tours are magical! 🇫🇷\n\n🗼 Available Attractions & Tours:\n\n1. Eiffel Tower:\n• Iconic iron tower and symbol of Paris\n• 324 meters tall, built for 1889 World\'s Fair\n• Panoramic city views from observation decks\n• One of the most recognizable structures worldwide\n• Rating: 4.8/5 (1,250+ reviews)\n\n2. Paris Evening Walk:\n• Experience magic of Paris at night\n• Guided walk through City of Light\n• Visit Eiffel Tower, stroll along Seine\n• Discover hidden gems of 7th arrondissement\n• Perfect for first-time visitors and couples\n• Tour: "Paris Evening Walk"\n• Price: $45 | Duration: 2 hours | Max: 12 participants\n• Category: Walking Tour\n• Meeting Point: Trocadéro Metro Station, Exit 6\n\n3. Paris City Tours:\n• Iconic landmarks exploration\n• Historical and cultural experiences\n• Art, food, and romantic views\n\n📅 Tour Features:\n• Multiple tour schedules (evening times)\n• Verified local French guides\n• Walking Tour and Cultural categories\n• Price range: $45+\n\n🔍 Find Paris tours:\n• Search "Paris" or "Eiffel Tower" on Destinations page\n• Filter by Walking Tour category\n• Browse available evening dates'
            }
        ],
        newyork: [
            {
                keywords: ['new york', 'nyc', 'new york city', 'usa', 'america', 'times square', 'union square', 'manhattan'],
                answer: 'New York City tours are exciting! 🇺🇸\n\n🗽 Available Attractions & Tours:\n\n1. Times Square:\n• Major commercial intersection in Manhattan\n• "The Crossroads of the World"\n• Massive digital billboards\n• Heart of NYC entertainment district\n• Rating: 4.6/5 (980+ reviews)\n\n2. NYC Food Tour:\n• Taste best of New York City through diverse culinary scene\n• Visit authentic pizzerias, delis, and food markets\n• Sample bagels, pizza, hot dogs, international cuisine\n• Learn about NYC food culture\n• Tour: "NYC Food Adventure"\n• Price: $85 | Duration: 4 hours | Max: 10 participants\n• Category: Food Tour\n• Meeting Point: Union Square Park, North End\n\n3. NYC City Tours:\n• Iconic landmarks and neighborhoods\n• Cultural and food tour experiences\n• Manhattan exploration\n\n📅 Tour Features:\n• Multiple schedules available (morning and afternoon)\n• Verified local NYC guides\n• Food Tour, Walking Tour, and Cultural categories\n• Price range: $85+\n\n🔍 Find NYC tours:\n• Search "New York" or "NYC" or "Times Square" on Destinations page\n• Filter by Food Tour category for culinary experiences\n• Check for morning and afternoon time slots'
            }
        ],
        rome: [
            {
                keywords: ['rome', 'italy', 'eternal city', 'roman', 'colosseum', 'roman empire', 'gladiator'],
                answer: 'Rome tours are historical! 🇮🇹\n\n🏛️ Available Attractions & Tours:\n\n1. Colosseum:\n• Ancient Roman amphitheater\n• One of the most famous landmarks worldwide\n• Built in 70-80 AD, held up to 80,000 spectators\n• Hosted gladiatorial contests and public spectacles\n• Rating: 4.9/5 (2,100+ reviews)\n\n2. Ancient Rome Discovery:\n• Explore Colosseum and surrounding ancient Roman ruins\n• Learn about gladiators, emperors, daily life of ancient Romans\n• Includes skip-the-line access\n• Detailed historical commentary\n• Tour: "Ancient Rome Discovery"\n• Price: $65 | Duration: 3 hours | Max: 15 participants\n• Category: Historical Tour\n• Meeting Point: Colosseum Main Entrance, Via dei Fori Imperiali\n\n3. Rome Cultural Tours:\n• Roman Empire secrets and history\n• Cultural experiences in Eternal City\n• Walking tours through historic districts\n\n📅 Tour Features:\n• Multiple tour schedules (morning 10:00 and afternoon 14:00)\n• Verified local Italian guides\n• Historical, Cultural, and Walking Tour categories\n• Duration: 3 hours | Price: $65\n\n🔍 Find Rome tours:\n• Search "Rome" or "Colosseum" on Destinations page\n• Filter by Historical Tour category\n• Browse morning and afternoon dates'
            }
        ],
        categories: [
            {
                keywords: ['category', 'categories', 'tour type', 'what types', 'historical', 'food tour', 'walking tour', 'adventure', 'cultural', 'nature'],
                answer: 'GlobeGo offers tours in multiple categories! 📂\n\n🎯 Available Categories:\n• Historical - Ancient sites, monuments, museums\n• Food Tour - Local cuisine, restaurants, markets\n• Walking Tour - City walks, neighborhood exploration\n• Adventure - Active experiences, outdoor activities\n• Cultural - Local traditions, customs, arts\n• Nature - Natural attractions, parks, landscapes\n\n🔍 How to filter:\n• Use category filter on Destinations page\n• Select category from dropdown\n• Browse tours by your interest\n\n💡 Each category offers:\n• Different tour experiences\n• Various price ranges\n• Multiple destinations\n• Flexible scheduling'
            }
        ],
        guides: [
            {
                keywords: ['guide', 'tour guide', 'who is guide', 'guide background', 'guide info', 'guide verified', 'guide profile', 'become guide', 'guide verification', 'guide bio', 'guide languages', 'all guides', 'every guide', 'guide names', 'who are guides'],
                answer: 'All GlobeGo tour guides are verified professionals! 👤\n\n👥 ALL VERIFIED GUIDES ON GLOBEGO:\n\n1. SARAH JOHNSON 🇫🇷\n• Location: Paris, France\n• Specialization: Paris city tours, evening walks\n• Languages: English, French, Spanish\n• Experience: 10+ years showing visitors the best of Paris\n• Tours: Paris Evening Walk\n• Bio: "Experienced tour guide with 10+ years showing visitors the best of Paris. Fluent in English, French, and Spanish."\n• Verified: ✓ Yes\n\n2. MARCO ROSSI 🇮🇹\n• Location: Rome, Italy\n• Specialization: Ancient Rome, historical tours\n• Languages: English, Italian, German\n• Experience: Passionate about Roman history and architecture\n• Tours: Ancient Rome Discovery\n• Bio: "Passionate about Roman history and architecture. Specializes in historical tours of ancient Rome."\n• Verified: ✓ Yes\n\n3. EMMA WILLIAMS 🇺🇸\n• Location: New York City, USA\n• Specialization: NYC food tours, culinary experiences\n• Languages: English, Spanish\n• Experience: NYC native foodie expert\n• Tours: NYC Food Adventure\n• Bio: "NYC native foodie expert. Knows all the hidden gems and best spots for authentic New York cuisine."\n• Verified: ✓ Yes\n\n4. JAMES ANDERSON 🇬🇧\n• Location: London, UK\n• Specialization: London history, Tower Bridge\n• Languages: English, French\n• Experience: London historian and architecture enthusiast\n• Tours: London Tower Bridge Experience\n• Bio: "London historian and architecture enthusiast. Expert in Victorian and modern London landmarks."\n• Verified: ✓ Yes\n\n5. ISABELLA GARCIA 🇪🇸\n• Location: Barcelona, Spain\n• Specialization: Gaudí architecture, Catalan culture\n• Languages: English, Spanish, Catalan, French\n• Experience: Barcelona native specializing in Gaudí\n• Tours: Sagrada Familia & Gaudí\'s Barcelona\n• Bio: "Barcelona native specializing in Gaudí architecture and Catalan culture. Fluent in multiple languages."\n• Verified: ✓ Yes\n\n✅ Guide Verification Process:\n• Identity verification required\n• Background checks conducted\n• Interview process with our team\n• Documentation review (national ID, address)\n• Only verified guides can create tours\n\n📋 Guide Profiles Include:\n• Bio - Personal background and experience\n• Languages spoken - Communication abilities\n• Profile image - See your guide\n• Verification badge - Confirmed professional\n• Tour history - Experience level\n\n🌟 Guide Qualities:\n• All guides are verified by GlobeGo team\n• Local experts who know hidden gems\n• Passionate about their destinations\n• Professional and knowledgeable\n• Committed to providing great experiences\n\n👀 View Guide Info:\n• See guide details on tour details page\n• View guide profile and bio\n• Check languages and experience\n• See which tours each guide leads\n\n📝 Become a Guide:\n• Click "Become a Guide" in navigation\n• Complete registration form\n• Provide national ID and address\n• Answer verification questions\n• Wait for approval and interview'
            }
        ],
        booking: [
            {
                keywords: ['book', 'booking', 'how to book', 'reserve', 'booking process', 'booking steps', 'how do i book', 'make booking'],
                answer: 'Booking a tour on GlobeGo is simple! 📅\n\n📋 Step-by-Step Process:\n1. Browse tours - Visit "Destinations" page\n2. Select tour - Click on a tour you like\n3. View details - See tour description, price, schedule\n4. Choose date - Select from available tour schedules\n5. Select participants - Choose number of people (1-20)\n6. Claim discount - If booking 2+ tickets, claim multi-ticket offer\n7. Payment - Choose payment method and complete\n8. Confirmation - Receive instant booking confirmation\n\n💳 Payment Options:\n• Credit Card (Visa)\n• PayPal\n• Bank Transfer\n• Cash (for some tours)\n\n💰 Save Money:\n• Book 2+ tickets for multi-ticket discounts\n• Check Special Offers page for deals\n• Discounts automatically applied\n\n📧 After Booking:\n• Receive confirmation email\n• View booking in your Dashboard\n• Get booking reference number\n• Access tour details and guide info'
            }
        ],
        payment: [
            {
                keywords: ['payment', 'payment method', 'how to pay', 'credit card', 'paypal', 'visa', 'bank transfer', 'cash', 'payment options', 'payment processing'],
                answer: 'GlobeGo accepts multiple secure payment methods! 💳\n\n💳 Accepted Payment Methods:\n• Credit Card (Visa) - Most popular option\n• PayPal - Secure online payments\n• Bank Transfer - Direct bank payments\n• Cash - Available for some tours (pending status)\n\n🔒 Payment Security:\n• All payments processed securely\n• Encrypted payment gateways\n• Your financial information is protected\n• PCI compliant processing\n\n⏱️ Payment Processing:\n• Credit Card/PayPal: Instant confirmation\n• Bank Transfer: May take 1-2 business days\n• Cash: Payment pending until tour date\n\n📧 Payment Confirmation:\n• Receive email receipt after payment\n• View payment details in Dashboard\n• Payment reference provided\n• Download receipt anytime\n\n💰 Payment Terms:\n• Full payment required at booking\n• Prices displayed in specified currency\n• All payments processed at time of booking\n• Refunds follow cancellation policy'
            }
        ],
        account: [
            {
                keywords: ['account', 'sign up', 'register', 'create account', 'account type', 'tourist', 'guide account', 'admin', 'user account'],
                answer: 'GlobeGo offers different account types! 👥\n\n👤 Account Types:\n• Tourist - Book and participate in tours\n• Guide - Create and lead tours (requires verification)\n• Admin - Platform administrators\n\n📝 Creating an Account:\n• Click "Sign-up" in navigation\n• Fill in details: name, email, password\n• Choose account type (Tourist or Guide)\n• Complete registration\n• Verify email if required\n\n✅ Account Features:\n• Dashboard - View bookings and tours\n• Profile management\n• Booking history\n• Tour management (for guides)\n• Settings and preferences\n\n🔐 Account Security:\n• Secure password required\n• Email verification\n• Password reset available\n• Account protection measures\n\n📧 Support:\n• Forgot password? Use "Forgot password" link\n• Account suspended? Contact support\n• Need help? Email support@globego.com'
            }
        ],
        dashboard: [
            {
                keywords: ['dashboard', 'my bookings', 'booked tours', 'my tours', 'booking history', 'view booking'],
                answer: 'Your GlobeGo Dashboard is your control center! 📊\n\n📋 Dashboard Features:\n• Your Booked Tours - See all your bookings\n• Booking status (Pending, Confirmed, Cancelled)\n• View booking details\n• Cancel or modify bookings\n• Download receipts\n• View tour schedules\n\n👀 For Tourists:\n• See upcoming tours\n• View past bookings\n• Access booking references\n• Cancel bookings (subject to policy)\n• View guide information\n\n👨‍🏫 For Guides:\n• Manage your tours\n• View tour bookings\n• Update tour schedules\n• See participant information\n\n📱 Access Dashboard:\n• Click "Dashboard" in navigation\n• Must be logged in\n• View all your activity\n• Manage bookings easily'
            }
        ],
        search: [
            {
                keywords: ['search', 'find tour', 'filter', 'how to search', 'search tours', 'filter tours'],
                answer: 'Searching for tours on GlobeGo is easy! 🔍\n\n🔍 Search Options:\n• Search bar - Type destination or tour name\n• Filter by location - Select specific city/country\n• Filter by category - Historical, Food, Walking, Adventure, Cultural\n• Filter by price - Set maximum price range\n• Filter by date - Choose preferred tour date\n\n📍 Search Features:\n• Real-time search results\n• Multiple filter combinations\n• Clear filters option\n• Sort by relevance or price\n\n💡 Search Tips:\n• Use destination names (e.g., "Paris", "Egypt")\n• Try category names (e.g., "Food Tour")\n• Combine filters for precise results\n• Check Special Offers for discounted tours\n\n🌐 Where to Search:\n• Homepage search bar\n• Destinations page filters\n• Special Offers page\n• Tour details page'
            }
        ],
        support: [
            {
                keywords: ['support', 'help', 'contact', 'email', 'phone', 'customer service', 'help center', 'contact us'],
                answer: 'GlobeGo support is here to help! 📞\n\n📧 Contact Methods:\n• Email: support@globego.com\n• Phone: +1 (555) 123-4567\n• Help Center: Visit help.php page\n• Contact Form: Available on contact page\n\n⏰ Support Hours:\n• Email: 24/7 (response within 24 hours)\n• Phone: Monday-Friday, 9 AM - 6 PM\n\n📋 Support Can Help With:\n• Booking questions\n• Cancellation requests\n• Payment issues\n• Account problems\n• Technical support\n• General inquiries\n\n🔗 Quick Links:\n• Help Center - FAQ and guides\n• Contact Us - Direct contact form\n• Terms of Service - Policies and terms\n\n💬 Need Immediate Help?\n• Check Help Center for common questions\n• Use chatbot for quick answers\n• Email for detailed assistance'
            }
        ],
        terms: [
            {
                keywords: ['terms', 'terms of service', 'policy', 'policies', 'user agreement', 'terms and conditions'],
                answer: 'GlobeGo Terms of Service outline important policies! 📜\n\n📋 Key Policies:\n• User Accounts - Registration and responsibilities\n• Booking and Payment - Terms for bookings\n• Cancellation and Refund - Detailed refund policy\n• Tour Guide Responsibilities - Guide requirements\n• Tourist Responsibilities - Participant rules\n• User Conduct - Prohibited activities\n• Account Suspension - Suspension and termination\n• Privacy Policy - Data protection\n\n⏰ Important Details:\n• Terms updated regularly\n• Users notified of changes\n• Continued use = acceptance\n• Dispute resolution process\n\n📖 Full Terms:\n• Visit Terms of Service page\n• Read complete policy document\n• Contact support for questions\n\n🔒 Your Rights:\n• Right to cancel (subject to policy)\n• Right to refund (as per policy)\n• Right to account deletion\n• Right to dispute resolution'
            }
        ],
        fines: [
            {
                keywords: ['fine', 'fines', 'cancellation fee', 'penalty', 'late fee'],
                answer: 'Cancellation fees on GlobeGo: 💰\n\n⚠️ Cancellation Fee:\n• 25% of ticket price applies when you cancel\n• Deducted from refund amount\n• Processing fees are separate (5%)\n\n📋 Fee Details:\n• Applied to all cancellations\n• Non-refundable portion\n• Shown in cancellation confirmation\n• Part of total refund calculation\n\n💳 Paying Fines:\n• Fines appear in your Dashboard\n• Can be paid via Visa or PayPal\n• Payment required for account access\n• View fine details in Fines section\n\n📧 Fine Notifications:\n• Email notification sent\n• Visible in Dashboard\n• Payment options provided\n• Clear fee breakdown shown'
            }
        ],
        schedule: [
            {
                keywords: ['schedule', 'tour schedule', 'date', 'time', 'available dates', 'tour times', 'when', 'tour date'],
                answer: 'Tour schedules are flexible! 📅\n\n📅 Schedule Features:\n• Multiple dates available per tour\n• Various time slots\n• Check availability in real-time\n• Available spots shown\n\n🔍 Viewing Schedules:\n• See schedules on tour details page\n• Select from available dates\n• Check participant capacity\n• Choose preferred time\n\n⏰ Schedule Information:\n• Meeting point specified\n• Duration shown (hours)\n• Maximum participants listed\n• Available spots displayed\n\n📝 Booking Schedule:\n• Select date from dropdown\n• Choose number of participants\n• System checks availability\n• Confirms if spots available\n\n🔄 Schedule Updates:\n• Guides can update schedules\n• Availability updates in real-time\n• Book early for popular dates'
            }
        ],
        participants: [
            {
                keywords: ['participants', 'people', 'how many', 'group size', 'max participants', 'number of people', 'tickets'],
                answer: 'Participant information for tours! 👥\n\n👥 Participant Limits:\n• Minimum: 1 participant per booking\n• Maximum: 20 participants per booking\n• Tour-specific maximums may vary\n\n💰 Multi-Ticket Discounts:\n• 2 tickets = 5% discount\n• 3 tickets = 10% discount\n• 4+ tickets = 15% discount\n• Only for non-offer tours\n• Automatically applied\n\n📋 Booking Participants:\n• Select number during booking\n• Price calculated per person\n• Discounts applied automatically\n• Total price shown before payment\n\n🎫 Ticket Information:\n• Each participant needs a ticket\n• Price is per person\n• Group discounts available\n• Special offers may have different pricing'
            }
        ],
        london: [
            {
                keywords: ['london', 'uk', 'united kingdom', 'tower bridge', 'thames', 'victorian'],
                answer: 'London tours are fascinating! 🇬🇧\n\n🌉 Available Attractions & Tours:\n\n1. Tower Bridge:\n• Victorian suspension bridge over River Thames\n• Completed in 1894\n• Two Gothic-style towers\n• Bascule bridge that can be raised for ships\n• Rating: 4.7/5 (1,450+ reviews)\n\n2. London Tower Bridge Experience:\n• Discover iconic Tower Bridge with guided tour\n• High-level walkways and Victorian engine rooms\n• Learn about bridge\'s history\n• Enjoy stunning views of Thames\n• See glass floor walkway\n• Tour: "London Tower Bridge Experience"\n• Price: $55 | Duration: 2 hours | Max: 20 participants\n• Category: Historical Tour\n• Meeting Point: Tower Bridge Exhibition Entrance\n• Guide: James Anderson (London historian)\n\n📅 Tour Features:\n• Multiple schedules (morning 10:00 and afternoon 14:00)\n• Verified local London guide\n• Historical Tour category\n• Flexible booking options\n\n🔍 Find London tours:\n• Search "London" or "Tower Bridge" on Destinations page\n• Filter by Historical Tour category'
            }
        ],
        barcelona: [
            {
                keywords: ['barcelona', 'spain', 'gaudi', 'sagrada familia', 'catalan', 'catalonia'],
                answer: 'Barcelona tours are architectural wonders! 🇪🇸\n\n⛪ Available Attractions & Tours:\n\n1. Sagrada Familia:\n• Unfinished basilica designed by Antoni Gaudí\n• Construction began in 1882, still ongoing\n• Masterpiece of Catalan Modernism\n• UNESCO World Heritage Site\n• Rating: 4.9/5 (1,800+ reviews)\n\n2. Sagrada Familia & Gaudí\'s Barcelona:\n• Explore Antoni Gaudí\'s masterpiece\n• Discover architectural genius behind unfinished basilica\n• Includes skip-the-line access\n• Expert commentary\n• Walk through Eixample district\n• Tour: "Sagrada Familia & Gaudí\'s Barcelona"\n• Price: $75 | Duration: 3 hours | Max: 15 participants\n• Category: Cultural Tour\n• Meeting Point: Sagrada Familia Main Entrance, Carrer de la Marina\n• Guide: Isabella Garcia (Barcelona native, Gaudí expert)\n\n📅 Tour Features:\n• Multiple schedules (morning 10:00 and afternoon 14:00)\n• Verified local Barcelona guide\n• Cultural Tour category\n• Catalan culture immersion\n\n🔍 Find Barcelona tours:\n• Search "Barcelona" or "Sagrada Familia" on Destinations page\n• Filter by Cultural Tour category'
            }
        ],
        peru: [
            {
                keywords: ['peru', 'machu picchu', 'cusco', 'inca', 'incan', 'aguas calientes'],
                answer: 'Peru tours are adventurous! 🇵🇪\n\n🏔️ Available Attractions & Tours:\n\n1. Machu Picchu - Cusco:\n• Ancient Incan citadel\n• One of the New Seven Wonders of the World\n• Mysterious ruins in mountain setting\n• Incan culture and history\n\n2. Machu Picchu Adventure:\n• Journey to ancient Incan citadel\n• Explore mysterious ruins\n• Learn about Incan culture\n• Enjoy breathtaking mountain views\n• Tour: "Machu Picchu Adventure"\n• Price: $120 | Duration: 8 hours | Max: 15 participants\n• Category: Historical Tour\n• Meeting Point: Aguas Calientes Train Station\n\n📅 Tour Features:\n• Full-day experience (8 hours)\n• Verified local Peruvian guides\n• Historical Tour category\n• Mountain adventure experience\n\n🔍 Find Peru tours:\n• Search "Peru" or "Machu Picchu" on Destinations page\n• Filter by Historical Tour category'
            }
        ],
        china: [
            {
                keywords: ['china', 'beijing', 'great wall', 'great wall of china', 'badaling'],
                answer: 'China tours are magnificent! 🇨🇳\n\n🏯 Available Attractions & Tours:\n\n1. Great Wall of China - Beijing:\n• One of greatest architectural achievements in human history\n• Over 2,000 years of fascinating history\n• Breathtaking views and ancient structure\n\n2. Great Wall of China Tour:\n• Walk along magnificent Great Wall\n• Experience breathtaking views\n• Learn about wall\'s fascinating 2000+ year history\n• Tour: "Great Wall of China"\n• Price: $95 | Duration: 5 hours | Max: 18 participants\n• Category: Historical Tour\n• Meeting Point: Badaling Great Wall Visitor Center\n\n📅 Tour Features:\n• Half-day experience (5 hours)\n• Verified local Chinese guides\n• Historical Tour category\n• Architectural wonder exploration\n\n🔍 Find China tours:\n• Search "China" or "Great Wall" or "Beijing" on Destinations page\n• Filter by Historical Tour category'
            }
        ],
        brazil: [
            {
                keywords: ['brazil', 'rio de janeiro', 'rio', 'christ the redeemer', 'corcovado', 'christ statue'],
                answer: 'Brazil tours are iconic! 🇧🇷\n\n🗿 Available Attractions & Tours:\n\n1. Christ the Redeemer - Rio de Janeiro:\n• Iconic statue, one of New Seven Wonders of the World\n• Symbol of Brazilian culture\n• Panoramic views of Rio from Corcovado Mountain\n\n2. Christ the Redeemer Tour:\n• Visit iconic Christ the Redeemer statue\n• Enjoy panoramic views of Rio de Janeiro\n• From top of Corcovado Mountain\n• Learn about symbol of Brazilian culture\n• Tour: "Christ the Redeemer"\n• Price: $70 | Duration: 3 hours | Max: 20 participants\n• Category: Cultural Tour\n• Meeting Point: Corcovado Train Station\n\n📅 Tour Features:\n• Half-day experience (3 hours)\n• Verified local Brazilian guides\n• Cultural Tour category\n• Mountain views and cultural immersion\n\n🔍 Find Brazil tours:\n• Search "Brazil" or "Rio" or "Christ the Redeemer" on Destinations page\n• Filter by Cultural Tour category'
            }
        ],
        australia: [
            {
                keywords: ['australia', 'sydney', 'opera house', 'sydney opera house'],
                answer: 'Australia tours are world-class! 🇦🇺\n\n🎭 Available Attractions & Tours:\n\n1. Sydney Opera House:\n• World-famous architectural masterpiece\n• UNESCO World Heritage site\n• Iconic building design and cultural significance\n\n2. Sydney Opera House Tour:\n• Explore world-famous Sydney Opera House\n• Architectural masterpiece tour\n• Learn about design, history, and cultural significance\n• Tour: "Sydney Opera House"\n• Price: $80 | Duration: 2 hours | Max: 25 participants\n• Category: Cultural Tour\n• Meeting Point: Sydney Opera House Main Entrance\n\n📅 Tour Features:\n• Short experience (2 hours)\n• Verified local Australian guides\n• Cultural Tour category\n• Architectural and cultural exploration\n\n🔍 Find Australia tours:\n• Search "Australia" or "Sydney" or "Opera House" on Destinations page\n• Filter by Cultural Tour category'
            }
        ],
        cambodia: [
            {
                keywords: ['cambodia', 'siem reap', 'angkor wat', 'khmer', 'temple'],
                answer: 'Cambodia tours are ancient! 🇰🇭\n\n🏛️ Available Attractions & Tours:\n\n1. Angkor Wat - Siem Reap:\n• Largest religious monument in the world\n• Ancient Khmer temples\n• Stunning sunrise views\n• Rich history of Khmer Empire\n\n2. Angkor Wat Temple Complex:\n• Discover magnificent Angkor Wat\n• Explore ancient Khmer temples\n• Witness stunning sunrise views\n• Learn about rich history of Khmer Empire\n• Tour: "Angkor Wat Temple Complex"\n• Price: $90 | Duration: 6 hours | Max: 15 participants\n• Category: Historical Tour\n• Meeting Point: Angkor Wat Main Entrance\n\n📅 Tour Features:\n• Full-day experience (6 hours)\n• Verified local Cambodian guides\n• Historical Tour category\n• Temple complex exploration\n\n🔍 Find Cambodia tours:\n• Search "Cambodia" or "Angkor Wat" or "Siem Reap" on Destinations page\n• Filter by Historical Tour category'
            }
        ],
        jordan: [
            {
                keywords: ['jordan', 'petra', 'rose city', 'nabataean', 'siq', 'treasury'],
                answer: 'Jordan tours are mysterious! 🇯🇴\n\n🏜️ Available Attractions & Tours:\n\n1. Petra - The Rose City:\n• Ancient city carved into rose-red sandstone cliffs\n• Walk through the Siq\n• Discover the Treasury\n• Nabataean civilization architectural wonder\n\n2. Petra - The Rose City Tour:\n• Explore ancient city of Petra\n• Carved into rose-red sandstone cliffs\n• Walk through the Siq\n• Discover the Treasury\n• Learn about Nabataean civilization\n• Tour: "Petra - The Rose City"\n• Price: $100 | Duration: 5 hours | Max: 18 participants\n• Category: Historical Tour\n• Meeting Point: Petra Visitor Center\n\n📅 Tour Features:\n• Half-day experience (5 hours)\n• Verified local Jordanian guides\n• Historical Tour category\n• Ancient city exploration\n\n🔍 Find Jordan tours:\n• Search "Jordan" or "Petra" on Destinations page\n• Filter by Historical Tour category'
            }
        ],
        stonehenge: [
            {
                keywords: ['stonehenge', 'wiltshire', 'uk', 'prehistoric', 'mystery', 'ancient'],
                answer: 'Stonehenge tours are mysterious! 🗿\n\n⛰️ Available Attractions & Tours:\n\n1. Stonehenge - Wiltshire, UK:\n• One of world\'s most famous prehistoric monuments\n• Mysterious ancient site\n• Theories about construction and purpose\n• Mystical atmosphere\n\n2. Stonehenge Mystery Tour:\n• Visit mysterious Stonehenge\n• Prehistoric monument exploration\n• Learn about theories surrounding construction and purpose\n• Experience mystical atmosphere of ancient site\n• Tour: "Stonehenge Mystery Tour"\n• Price: $65 | Duration: 3 hours | Max: 20 participants\n• Category: Historical Tour\n• Meeting Point: Stonehenge Visitor Center\n\n📅 Tour Features:\n• Half-day experience (3 hours)\n• Verified local UK guides\n• Historical Tour category\n• Ancient mystery exploration\n\n🔍 Find Stonehenge tours:\n• Search "Stonehenge" or "Wiltshire" on Destinations page\n• Filter by Historical Tour category'
            }
        ],
        tours: [
            {
                keywords: ['tour', 'tours', 'all tours', 'every tour', 'tour list', 'what tours', 'available tours', 'tour titles'],
                answer: 'GlobeGo offers amazing tours worldwide! 🎫\n\n📋 ALL AVAILABLE TOURS:\n\n1. Paris Evening Walk - $45 | 2 hours | Walking Tour\n   • Guide: Sarah Johnson | Meeting: Trocadéro Metro Station\n\n2. Ancient Rome Discovery - $65 | 3 hours | Historical Tour\n   • Guide: Marco Rossi | Meeting: Colosseum Main Entrance\n\n3. NYC Food Adventure - $85 | 4 hours | Food Tour\n   • Guide: Emma Williams | Meeting: Union Square Park\n\n4. London Tower Bridge Experience - $55 | 2 hours | Historical Tour\n   • Guide: James Anderson | Meeting: Tower Bridge Exhibition Entrance\n\n5. Sagrada Familia & Gaudí\'s Barcelona - $75 | 3 hours | Cultural Tour\n   • Guide: Isabella Garcia | Meeting: Sagrada Familia Main Entrance\n\n6. The Grand Egyptian Museum - $85 | 4 hours | Museum Tour\n   • Meeting: Grand Egyptian Museum Main Entrance\n\n7. Taj Mahal Experience - $75 | 3 hours | Cultural Tour\n   • Meeting: Taj Mahal East Gate\n\n8. Shibuya Crossway Experience - $55 | 2 hours | City Tour\n   • Meeting: Hachiko Statue, Shibuya Station\n\n9. Machu Picchu Adventure - $120 | 8 hours | Historical Tour\n   • Meeting: Aguas Calientes Train Station\n\n10. Great Wall of China - $95 | 5 hours | Historical Tour\n    • Meeting: Badaling Great Wall Visitor Center\n\n11. Christ the Redeemer - $70 | 3 hours | Cultural Tour\n    • Meeting: Corcovado Train Station\n\n12. Sydney Opera House - $80 | 2 hours | Cultural Tour\n    • Meeting: Sydney Opera House Main Entrance\n\n13. Angkor Wat Temple Complex - $90 | 6 hours | Historical Tour\n    • Meeting: Angkor Wat Main Entrance\n\n14. Petra - The Rose City - $100 | 5 hours | Historical Tour\n    • Meeting: Petra Visitor Center\n\n15. Stonehenge Mystery Tour - $65 | 3 hours | Historical Tour\n    • Meeting: Stonehenge Visitor Center\n\n💰 Price Range: $45 - $400\n⏱️ Duration: 2 - 8 hours\n👥 Max Participants: 10 - 25 per tour\n\n🔍 Find Tours:\n• Visit Destinations page\n• Use search and filters\n• Check Special Offers for discounts'
            }
        ],
        prices: [
            {
                keywords: ['cheapest', 'cheap', 'lowest price', 'most affordable', 'budget', 'inexpensive', 'low price', 'cheapest tour', 'cheapest tours', 'affordable tours', 'budget tours',
                    'أرخص', 'رخيص', 'أقل سعر', 'ميزانية', 'رخيصة', 'أرخص جولة', 'جولات رخيصة',
                    'moins cher', 'pas cher', 'prix le plus bas', 'budget', 'visites pas chères',
                    'más barato', 'barato', 'precio más bajo', 'presupuesto', 'tours baratos'],
                answer: 'Looking for the most affordable tours? 💰\n\n🏆 CHEAPEST TOURS ON GLOBEGO:\n\n🥇 CHEAPEST: London Tower Bridge Experience - $55\n   • 2 hours | Historical Tour\n   • Guide: James Anderson\n   • Affordable London experience!\n\n🥇 CHEAPEST: Shibuya Crossway Experience - $55\n   • 2 hours | City Tour\n   • Tokyo, Japan\n   • Great value for money!\n\n🥉 THIRD CHEAPEST: Stonehenge Mystery Tour - $65\n   • 3 hours | Historical Tour\n   • Wiltshire, UK\n   • Ancient mystery exploration\n\n🥉 THIRD CHEAPEST: Ancient Rome Discovery - $65\n   • 3 hours | Historical Tour\n   • Guide: Marco Rossi\n   • Rome, Italy\n\n💰 OTHER AFFORDABLE OPTIONS:\n• Christ the Redeemer - $70\n• Sagrada Familia & Gaudí\'s Barcelona - $75\n• Sydney Opera House - $80\n• NYC Food Adventure - $85\n• Angkor Wat Temple Complex - $90\n• Great Wall of China - $95\n• Petra - The Rose City - $100\n• Machu Picchu Adventure - $120\n\n💡 SAVE MORE:\n• Book 2+ tickets for multi-ticket discounts (5-15% off)\n• Check Special Offers page for exclusive deals\n• Egypt, India, and Japan tours have special pricing\n\n🔍 Find Budget Tours:\n• Visit Destinations page\n• Filter by price range\n• Sort by price (lowest first)'
            },
            {
                keywords: ['most expensive', 'expensive', 'highest price', 'premium', 'luxury', 'costly', 'pricey', 'expensive tour', 'expensive tours', 'premium tours', 'luxury tours',
                    'أغلى', 'غالي', 'أعلى سعر', 'فاخر', 'باهظ', 'جولات فاخرة',
                    'plus cher', 'cher', 'prix le plus élevé', 'premium', 'luxe', 'visites de luxe',
                    'más caro', 'caro', 'precio más alto', 'premium', 'lujo', 'tours de lujo'],
                answer: 'Looking for premium experiences? 💎\n\n🏆 MOST EXPENSIVE TOURS ON GLOBEGO:\n\n🥇 MOST EXPENSIVE: Paris Evening Walk - $400\n   • 2 hours | Walking Tour\n   • Guide: Sarah Johnson\n   • Ultimate luxury Paris experience!\n\n🥈 SECOND MOST EXPENSIVE: The Grand Egyptian Museum - $299\n   • 4 hours | Museum Tour\n   • World\'s largest collection of ancient Egyptian artifacts\n   • Premium museum experience\n\n🥉 THIRD MOST EXPENSIVE: Shibuya Crossway Experience - $279\n   • 2 hours | City Tour\n   • Tokyo, Japan\n   • Premium Tokyo experience\n\n💰 OTHER PREMIUM OPTIONS:\n• Taj Mahal Experience - $249\n• Machu Picchu Adventure - $120\n• Petra - The Rose City - $100\n• Great Wall of China - $95\n• Angkor Wat Temple Complex - $90\n• NYC Food Adventure - $85\n• Sydney Opera House - $80\n• Sagrada Familia & Gaudí\'s Barcelona - $75\n• Christ the Redeemer - $70\n• Stonehenge Mystery Tour - $65\n• Ancient Rome Discovery - $65\n• London Tower Bridge Experience - $55\n\n💡 WHY PREMIUM PRICING?\n• Longer duration tours (5-8 hours)\n• Full-day experiences\n• World-famous attractions\n• Comprehensive guided experiences\n• Unique and exclusive destinations\n• VIP access and luxury amenities\n\n🔍 Find Premium Tours:\n• Visit Destinations page\n• Filter by price range\n• Sort by price (highest first)\n• Look for longer duration tours'
            },
            {
                keywords: ['price', 'prices', 'cost', 'costs', 'how much', 'price range', 'tour prices', 'tour cost', 'pricing', 'what does it cost', 'tour pricing',
                    'سعر', 'أسعار', 'تكلفة', 'تكاليف', 'كم', 'نطاق السعر', 'أسعار الجولات', 'تكلفة الجولة', 'التسعير', 'اسعار الرحلات', 'أسعار', 'الأسعار', 'رحلات', 'الرحلات', 'جولات', 'الجولات',
                    'prix', 'coût', 'combien', 'gamme de prix', 'prix des visites', 'coût de la visite',
                    'precio', 'precios', 'costo', 'costos', 'cuánto', 'rango de precios', 'precios de tours', 'costo del tour'],
                answer: 'Tour prices on GlobeGo vary by destination and experience! 💰\n\n📊 PRICE RANGE:\n• Lowest: $55 (London Tower Bridge Experience, Shibuya Crossway Experience)\n• Highest: $400 (Paris Evening Walk)\n• Average: $70-120\n\n💰 PRICE BREAKDOWN BY TOUR (CORRECT RANKING):\n\n💵 BUDGET ($55-$70):\n• London Tower Bridge Experience - $55 (CHEAPEST)\n• Shibuya Crossway Experience - $55 (CHEAPEST)\n• Stonehenge Mystery Tour - $65\n• Ancient Rome Discovery - $65\n• Christ the Redeemer - $70\n\n💵 MID-RANGE ($75-$100):\n• Sagrada Familia & Gaudí\'s Barcelona - $75\n• Sydney Opera House - $80\n• NYC Food Adventure - $85\n• Angkor Wat Temple Complex - $90\n• Great Wall of China - $95\n• Petra - The Rose City - $100\n\n💵 PREMIUM ($120-$300):\n• Machu Picchu Adventure - $120\n• Taj Mahal Experience - $249\n• Shibuya Crossway Experience - $279\n• The Grand Egyptian Museum - $299\n\n💎 LUXURY ($400+):\n• Paris Evening Walk - $400 (MOST EXPENSIVE)\n   • Ultimate luxury Paris experience\n   • Premium guided tour\n\n💡 PRICING FACTORS:\n• Tour duration (2-8 hours)\n• Destination popularity\n• Experience type\n• Guide expertise\n• Special features included\n• VIP access and luxury amenities\n\n💰 SAVE MONEY:\n• Multi-ticket discounts: 5% (2 tickets), 10% (3 tickets), 15% (4+ tickets)\n• Special Offers: Egypt, India, Japan tours have exclusive pricing\n• Book early for better availability\n\n🔍 Find Tours by Price:\n• Visit Destinations page\n• Use price filter (set maximum price)\n• Sort by price (lowest or highest)\n• Check Special Offers for deals'
            }
        ],
        default: [
            {
                keywords: [],
                answer: 'I\'m Globoba, your GlobeGo assistant! I can help with:\n\n🌍 DESTINATIONS:\n• Egypt (Pyramids, Egyptian Museum, Cairo)\n• India (Taj Mahal, Agra)\n• Japan (Shibuya, Tokyo)\n• France (Paris, Eiffel Tower)\n• USA (New York City, Times Square)\n• Italy (Rome, Colosseum)\n• UK (London, Tower Bridge; Stonehenge)\n• Spain (Barcelona, Sagrada Familia)\n• Peru (Machu Picchu)\n• China (Great Wall, Beijing)\n• Brazil (Rio, Christ the Redeemer)\n• Australia (Sydney Opera House)\n• Cambodia (Angkor Wat)\n• Jordan (Petra)\n\n👥 GUIDES:\n• Sarah Johnson (Paris)\n• Marco Rossi (Rome)\n• Emma Williams (NYC)\n• James Anderson (London)\n• Isabella Garcia (Barcelona)\n\n💰 PRICING:\n• Cheapest tours\n• Most expensive tours\n• Price ranges and costs\n• Budget-friendly options\n• Premium experiences\n\n📋 OTHER TOPICS:\n• Refund and cancellation policies\n• Special offers and discounts\n• Tour guide information and verification\n• Booking process and steps\n• Payment methods (Visa, PayPal, Bank Transfer)\n• Account types and registration\n• Dashboard features\n• Search and filtering\n• Support contact information\n• Terms of service\n• Tour categories (Historical, Food, Walking, Adventure, Cultural, Nature)\n• Tour schedules and dates\n• Participant limits and discounts\n• All 15+ available tours\n\nWhat would you like to know?'
            }
        ]
    };
    
    // Out-of-scope keywords that indicate questions not about the website
    const outOfScopeKeywords = [
        'weather', 'temperature', 'climate', 'rain', 'snow', 'sunny',
        'recipe', 'cooking', 'how to cook', 'food recipe',
        'news', 'current events', 'politics', 'sports', 'entertainment',
        'stock', 'investment', 'crypto', 'bitcoin', 'trading',
        'medical', 'doctor', 'health', 'medicine', 'symptoms',
        'movie', 'film', 'actor', 'celebrity',
        'game', 'video game', 'playstation', 'xbox',
        'unrelated', 'random', 'joke', 'funny',
        'calculate', 'math', 'equation', 'solve',
        'translate', 'language', 'meaning of',
        'other website', 'competitor', 'similar site'
    ];
    
    // Toggle chatbot window
    chatbotToggle.addEventListener('click', () => {
        chatbotWindow.classList.toggle('active');
        if (chatbotWindow.classList.contains('active')) {
            chatbotInput.focus();
            if (chatbotBadge) {
                chatbotBadge.style.display = 'none';
            }
        }
    });
    
    chatbotClose.addEventListener('click', () => {
        chatbotWindow.classList.remove('active');
    });
    
    // Send message function
    function sendMessage(text, isUser = true) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `chatbot-message ${isUser ? 'user-message' : 'bot-message'}`;
        
        const contentDiv = document.createElement('div');
        contentDiv.className = 'message-content';
        
        if (isUser) {
            contentDiv.textContent = text;
        } else {
            contentDiv.innerHTML = `<i class="fas fa-robot"></i><div>${text.replace(/\n/g, '<br>')}</div>`;
        }
        
        messageDiv.appendChild(contentDiv);
        chatbotMessages.appendChild(messageDiv);
        
        // Scroll to bottom
        chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
    }
    
    // Check if question is out of scope
    function isOutOfScope(question) {
        const lowerQuestion = question.toLowerCase();
        
        // Price-related keywords that should always be in scope
        const priceKeywords = ['price', 'prices', 'cost', 'costs', 'cheapest', 'expensive', 'most expensive', 'affordable', 'budget', 'premium', 'luxury', 'how much'];
        const hasPriceKeywords = priceKeywords.some(kw => lowerQuestion.includes(kw));
        
        // If question is about prices, it's always in scope
        if (hasPriceKeywords) {
            return false;
        }
        
        // Check for out-of-scope keywords
        for (const keyword of outOfScopeKeywords) {
            if (lowerQuestion.includes(keyword.toLowerCase())) {
                // Check if it's actually about the website (e.g., "weather cancellation" is in scope)
                const websiteContextKeywords = ['tour', 'booking', 'cancel', 'refund', 'guide', 'globe', 'website', 'site', 'platform', 'price', 'cost', 'cheapest', 'expensive'];
                const hasWebsiteContext = websiteContextKeywords.some(ctx => lowerQuestion.includes(ctx));
                
                // If no website context, it's out of scope
                if (!hasWebsiteContext) {
                    return true;
                }
            }
        }
        
        // Check if question is too generic or unrelated
        const genericQuestions = ['what is', 'who is', 'when did', 'tell me about', 'explain'];
        const hasGenericStart = genericQuestions.some(gq => lowerQuestion.startsWith(gq));
        const hasWebsiteKeywords = ['tour', 'booking', 'globe', 'guide', 'offer', 'destination', 'refund', 'cancel', 'price', 'cost', 'cheapest', 'expensive', 'most expensive', 'affordable', 'budget'].some(kw => lowerQuestion.includes(kw));
        
        // If starts with generic question but has no website keywords, likely out of scope
        if (hasGenericStart && !hasWebsiteKeywords && lowerQuestion.length > 20) {
            return true;
        }
        
        return false;
    }
    
    // Detect language from user input (improved detection)
    function detectLanguage(text) {
        const lowerText = text.toLowerCase();
        
        // Arabic detection - Arabic characters (most reliable)
        const arabicPattern = /[\u0600-\u06FF\u0750-\u077F\u08A0-\u08FF\uFB50-\uFDFF\uFE70-\uFEFF]/;
        if (arabicPattern.test(text)) {
            return 'ar';
        }
        
        // French detection - common French words and patterns
        const frenchWords = [
            'bonjour', 'salut', 'merci', 'comment', 'pourquoi', 'où', 'quand', 'combien',
            'visite', 'réservation', 'annulation', 'remboursement', 'guide', 'tour',
            'paris', 'france', 'français', 'comment ça', 'qu\'est-ce', 'est-ce que',
            'beaucoup', 'très', 'avec', 'sans', 'pour', 'dans', 'sur', 'sous'
        ];
        const frenchPatterns = [
            /\b(le|la|les|un|une|des|de|du|au|aux)\b/gi,
            /\b(est|sont|être|avoir|faire|aller)\b/gi,
            /\b(comment|pourquoi|où|quand|combien)\b/gi
        ];
        let frenchScore = 0;
        frenchWords.forEach(word => {
            if (lowerText.includes(word)) frenchScore++;
        });
        frenchPatterns.forEach(pattern => {
            if (pattern.test(text)) frenchScore += 0.5;
        });
        if (frenchScore >= 2) {
            return 'fr';
        }
        
        // Spanish detection - common Spanish words and patterns
        const spanishWords = [
            'hola', 'gracias', 'cómo', 'por qué', 'dónde', 'cuándo', 'cuánto',
            'visita', 'reserva', 'cancelación', 'reembolso', 'guía', 'tour',
            'españa', 'barcelona', 'madrid', 'español', 'qué', 'cuál', 'quién',
            'muy', 'mucho', 'con', 'sin', 'para', 'por', 'en', 'sobre'
        ];
        const spanishPatterns = [
            /\b(el|la|los|las|un|una|unos|unas|de|del|al|a|en|con|sin)\b/gi,
            /\b(es|son|ser|estar|tener|hacer|ir)\b/gi,
            /\b(cómo|por qué|dónde|cuándo|cuánto|qué|cuál)\b/gi
        ];
        let spanishScore = 0;
        spanishWords.forEach(word => {
            if (lowerText.includes(word)) spanishScore++;
        });
        spanishPatterns.forEach(pattern => {
            if (pattern.test(text)) spanishScore += 0.5;
        });
        if (spanishScore >= 2) {
            return 'es';
        }
        
        // Default to English
        return 'en';
    }
    
    // Translate answer to detected language using AJAX
    async function translateAnswer(answer, lang) {
        if (lang === 'en') {
            return answer; // Already in English
        }
        
        try {
            // Get base URL from current page
            const baseUrl = window.location.origin + window.location.pathname.substring(0, window.location.pathname.lastIndexOf('/') + 1);
            const translateUrl = baseUrl + 'chatbot-translate.php';
            
            const response = await fetch(translateUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    text: answer,
                    lang: lang
                })
            });
            
            if (!response.ok) {
                throw new Error('Translation request failed');
            }
            
            const data = await response.json();
            if (data.error) {
                console.error('Translation error:', data.error);
                return answer;
            }
            return data.translated || answer;
        } catch (error) {
            console.error('Translation error:', error);
            return answer; // Return original if translation fails
        }
    }
    
    // Find answer based on question (returns English answer, translation happens separately)
    function findAnswer(question) {
        // Normalize question for matching (handle Arabic, French, Spanish)
        // For Arabic, we need to check the original text, not lowercased
        const normalizedQuestion = question.toLowerCase().trim();
        const originalQuestion = question.trim();
        
        // First check if out of scope
        if (isOutOfScope(question)) {
            return "I can't answer this question. I can only help with GlobeGo website information including refund policies, offers, destinations, tour guides, bookings, payments, and account-related questions. How else can I help you?";
        }
        
        // Score-based matching for better accuracy
        let bestMatch = null;
        let bestScore = 0;
        
        // Check each category
        for (const [category, responses] of Object.entries(knowledgeBase)) {
            for (const response of responses) {
                let score = 0;
                let matchedKeywords = 0;
                
                for (const keyword of response.keywords) {
                    const keywordLower = keyword.toLowerCase();
                    // Check if keyword appears in question
                    // For non-English keywords (Arabic, etc.), check original text
                    // For English keywords, check normalized (lowercase)
                    if (normalizedQuestion.includes(keywordLower) || 
                        originalQuestion.includes(keyword) ||
                        originalQuestion.toLowerCase().includes(keywordLower)) {
                        score += keyword.length; // Use original length for better scoring
                        matchedKeywords++;
                    }
                }
                
                // Boost score if multiple keywords match
                if (matchedKeywords > 1) {
                    score *= 1.5;
                }
                
                if (score > bestScore) {
                    bestScore = score;
                    bestMatch = response.answer;
                }
            }
        }
        
        // Return best match or default (in English)
        return bestMatch || knowledgeBase.default[0].answer;
    }
    
    // Handle send button click
    chatbotSend.addEventListener('click', async () => {
        const question = chatbotInput.value.trim();
        if (question) {
            sendMessage(question, true);
            chatbotInput.value = '';
            
            // Show typing indicator
            const typingIndicator = document.createElement('div');
            typingIndicator.className = 'chatbot-message bot-message';
            typingIndicator.innerHTML = '<div class="message-content"><i class="fas fa-robot"></i><div>...</div></div>';
            chatbotMessages.appendChild(typingIndicator);
            chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
            
            try {
                // Detect language and get answer
                const detectedLang = detectLanguage(question);
                console.log('Detected language:', detectedLang, 'for question:', question);
                
                const answer = findAnswer(question);
                console.log('Found answer (English):', answer.substring(0, 100) + '...');
                
                // Translate answer to detected language
                const translatedAnswer = await translateAnswer(answer, detectedLang);
                console.log('Translated answer:', translatedAnswer.substring(0, 100) + '...');
                
                // Remove typing indicator
                typingIndicator.remove();
                
                // Simulate thinking delay
                setTimeout(() => {
                    sendMessage(translatedAnswer, false);
                }, 500);
            } catch (error) {
                console.error('Chatbot error:', error);
                typingIndicator.remove();
                sendMessage("Sorry, I encountered an error. Please try again.", false);
            }
        }
    });
    
    // Handle Enter key
    chatbotInput.addEventListener('keypress', async (e) => {
        if (e.key === 'Enter') {
            const question = chatbotInput.value.trim();
            if (question) {
                sendMessage(question, true);
                chatbotInput.value = '';
                
                // Show typing indicator
                const typingIndicator = document.createElement('div');
                typingIndicator.className = 'chatbot-message bot-message';
                typingIndicator.innerHTML = '<div class="message-content"><i class="fas fa-robot"></i><div>...</div></div>';
                chatbotMessages.appendChild(typingIndicator);
                chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
                
                try {
                    // Detect language and get answer
                    const detectedLang = detectLanguage(question);
                    const answer = findAnswer(question);
                    
                    // Translate answer to detected language
                    const translatedAnswer = await translateAnswer(answer, detectedLang);
                    
                    // Remove typing indicator
                    typingIndicator.remove();
                    
                    // Simulate thinking delay
                    setTimeout(() => {
                        sendMessage(translatedAnswer, false);
                    }, 500);
                } catch (error) {
                    console.error('Chatbot error:', error);
                    typingIndicator.remove();
                    sendMessage("Sorry, I encountered an error. Please try again.", false);
                }
            }
        }
    });
}

// Form Validation
function initFormValidation() {
    const forms = document.querySelectorAll('.needs-validation');
    
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        });
    });

    // Real-time password confirmation validation
    const confirmPassword = document.getElementById('confirm_password');
    const password = document.getElementById('password');
    
    if (confirmPassword && password) {
        confirmPassword.addEventListener('input', function() {
            if (this.value !== password.value) {
                this.setCustomValidity('Passwords do not match');
            } else {
                this.setCustomValidity('');
            }
        });
    }

    // Email validation
    const emailInputs = document.querySelectorAll('input[type="email"]');
    emailInputs.forEach(input => {
        input.addEventListener('blur', function() {
            if (this.value && !isValidEmail(this.value)) {
                this.setCustomValidity('Please enter a valid email address');
            } else {
                this.setCustomValidity('');
            }
        });
    });
}

// Email validation helper
function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

// Lazy Loading for Images
function initLazyLoading() {
    const images = document.querySelectorAll('img[data-src]');
    
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.classList.remove('lazy');
                imageObserver.unobserve(img);
            }
        });
    });

    images.forEach(img => imageObserver.observe(img));
}

// Search Functionality
function initSearch() {
    const searchInput = document.getElementById('search-input');
    if (searchInput) {
        let searchTimeout;
        
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                performSearch(this.value);
            }, 300);
        });
    }
}

function performSearch(query) {
    if (query.length < 2) return;
    
    // Show loading state
    showLoadingState();
    
    // Simulate API call (replace with actual AJAX call)
    fetch(`/api/search.php?q=${encodeURIComponent(query)}`)
        .then(response => response.json())
        .then(data => {
            displaySearchResults(data);
        })
        .catch(error => {
            console.error('Search error:', error);
            hideLoadingState();
        });
}

function displaySearchResults(results) {
    hideLoadingState();
    // Implement search results display
}

function showLoadingState() {
    const loadingElement = document.getElementById('loading-indicator');
    if (loadingElement) {
        loadingElement.style.display = 'block';
    }
}

function hideLoadingState() {
    const loadingElement = document.getElementById('loading-indicator');
    if (loadingElement) {
        loadingElement.style.display = 'none';
    }
}

// Booking Functionality
function initBooking() {
    const bookButtons = document.querySelectorAll('.book-tour-btn');
    
    bookButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const tourId = this.dataset.tourId;
            openBookingModal(tourId);
        });
    });
}

function openBookingModal(tourId) {
    // Create and show booking modal
    const modal = createBookingModal(tourId);
    document.body.appendChild(modal);
    
    const bootstrapModal = new bootstrap.Modal(modal);
    bootstrapModal.show();
    
    // Clean up when modal is hidden
    modal.addEventListener('hidden.bs.modal', function() {
        document.body.removeChild(modal);
    });
}

function createBookingModal(tourId) {
    const modal = document.createElement('div');
    modal.className = 'modal fade';
    modal.innerHTML = `
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Book Tour</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="booking-form">
                        <div class="mb-3">
                            <label for="num-participants" class="form-label">Number of Participants</label>
                            <input type="number" class="form-control" id="num-participants" min="1" max="10" required>
                        </div>
                        <div class="mb-3">
                            <label for="tour-date" class="form-label">Select Date</label>
                            <input type="date" class="form-control" id="tour-date" required>
                        </div>
                        <div class="mb-3">
                            <label for="booking-notes" class="form-label">Special Requirements (Optional)</label>
                            <textarea class="form-control" id="booking-notes" rows="3"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="processBooking(${tourId})">Book Now</button>
                </div>
            </div>
        </div>
    `;
    return modal;
}

function processBooking(tourId) {
    const form = document.getElementById('booking-form');
    const formData = new FormData(form);
    
    // Validate form
    if (!form.checkValidity()) {
        form.classList.add('was-validated');
        return;
    }
    
    // Show loading state
    const submitBtn = event.target;
    const originalText = submitBtn.textContent;
    submitBtn.textContent = 'Processing...';
    submitBtn.disabled = true;
    
    // Simulate booking process
    setTimeout(() => {
        // Reset button
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
        
        // Close modal
        const modal = bootstrap.Modal.getInstance(document.querySelector('.modal'));
        modal.hide();
        
        // Show success message
        showAlert('Booking successful! Check your email for confirmation.', 'success');
    }, 2000);
}

// Utility Functions
function showAlert(message, type = 'info') {
    const alertContainer = document.getElementById('alert-container') || createAlertContainer();
    
    const alert = document.createElement('div');
    alert.className = `alert alert-${type} alert-dismissible fade show`;
    alert.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    alertContainer.appendChild(alert);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (alert.parentNode) {
            alert.remove();
        }
    }, 5000);
}

function createAlertContainer() {
    const container = document.createElement('div');
    container.id = 'alert-container';
    container.className = 'position-fixed top-0 end-0 p-3';
    container.style.zIndex = '9999';
    document.body.appendChild(container);
    return container;
}

// Image Upload Preview
function previewImage(input, previewId) {
    const preview = document.getElementById(previewId);
    const file = input.files[0];
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
}

// Price Calculator
function calculateTotalPrice(basePrice, participants) {
    return basePrice * participants;
}

// Date Validation
function validateDate(dateInput) {
    const selectedDate = new Date(dateInput.value);
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    
    if (selectedDate < today) {
        dateInput.setCustomValidity('Please select a future date');
        return false;
    } else {
        dateInput.setCustomValidity('');
        return true;
    }
}

// Smooth Scrolling
function smoothScrollTo(elementId) {
    const element = document.getElementById(elementId);
    if (element) {
        element.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    }
}

// Copy to Clipboard
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        showAlert('Copied to clipboard!', 'success');
    }).catch(() => {
        showAlert('Failed to copy to clipboard', 'danger');
    });
}

// Format Currency
function formatCurrency(amount) {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD'
    }).format(amount);
}

// Debounce Function
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Throttle Function
function throttle(func, limit) {
    let inThrottle;
    return function() {
        const args = arguments;
        const context = this;
        if (!inThrottle) {
            func.apply(context, args);
            inThrottle = true;
            setTimeout(() => inThrottle = false, limit);
        }
    };
}

// Export functions for global use
window.GlobeGo = {
    showAlert,
    previewImage,
    calculateTotalPrice,
    validateDate,
    smoothScrollTo,
    copyToClipboard,
    formatCurrency
};

// Theme Toggle
function initThemeToggle() {
    const toggleBtn = document.getElementById('themeToggle');
    const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
    const stored = localStorage.getItem('theme');
    const initialDark = stored ? stored === 'dark' : prefersDark;
    setTheme(initialDark ? 'dark' : 'light');

    if (toggleBtn) {
        toggleBtn.addEventListener('click', function() {
            const willBeDark = !document.body.classList.contains('dark');
            setTheme(willBeDark ? 'dark' : 'light');
            localStorage.setItem('theme', willBeDark ? 'dark' : 'light');
            updateToggleIcon(toggleBtn, willBeDark);
        });
        updateToggleIcon(toggleBtn, document.body.classList.contains('dark'));
    }
}

function setTheme(mode) {
    if (mode === 'dark') {
        document.body.classList.add('dark');
    } else {
        document.body.classList.remove('dark');
    }
}

function updateToggleIcon(btn, isDark) {
    if (!btn) return;
    btn.innerHTML = isDark ? '<i class="fas fa-sun"></i>' : '<i class="fas fa-moon"></i>';
}
// Hero Slideshow
function initHeroSlideshow() {
    const slides = document.querySelectorAll('.hero-slide');
    const locationText = document.querySelector('.hero-location-text');
    const titleText = document.querySelector('.hero-title-text');
    const descriptionText = document.querySelector('.hero-description-text');
    const exploreBtn = document.querySelector('.hero-explore-btn');
    
    if (slides.length <= 1) return; // No slideshow needed if only one slide
    
    let currentSlide = 0;
    const totalSlides = slides.length;
    
    function showNextSlide() {
        // Remove active class from current slide
        slides[currentSlide].classList.remove('active');
        
        // Move to next slide
        currentSlide = (currentSlide + 1) % totalSlides;
        
        // Add active class to new slide
        slides[currentSlide].classList.add('active');
        
        // Update content
        updateSlideContent(slides[currentSlide]);
    }
    
    function updateSlideContent(slide) {
        const city = slide.getAttribute('data-city') || '';
        const location = slide.getAttribute('data-location') || '';
        const title = slide.getAttribute('data-title') || '';
        const description = slide.getAttribute('data-description') || '';
        
        // Update location
        if (locationText) {
            locationText.textContent = location;
        }
        
        // Update title
        if (titleText) {
            titleText.innerHTML = title.replace(' ', '<br>');
        }
        
        // Update description
        if (descriptionText) {
            descriptionText.textContent = description;
        }
        
        // Update explore button link
        if (exploreBtn) {
            exploreBtn.href = `tours.php?location=${encodeURIComponent(city)}`;
        }
    }
    
    // Auto-advance slideshow every 5 seconds
    setInterval(showNextSlide, 5000);
    
    // Initialize content for first slide
    if (slides.length > 0) {
        updateSlideContent(slides[0]);
    }
}

// Fake Reviews with Shuffle and Fade Animation
function initFakeReviews() {
    const reviewsContainer = document.getElementById('reviews-container');
    const reviewCountBadge = document.getElementById('review-count');
    
    if (!reviewsContainer) return; // Only run on tour details page
    
    // Fake reviews data with usernames - includes reviews about tour and tour guide
    const fakeReviews = [
        { username: 'sarah_travels', rating: 5, tourReview: 'Absolutely amazing experience! The museum tour was incredible and well-organized.', guideReview: 'Our guide was knowledgeable and made the tour unforgettable. Highly recommend!', date: '2 days ago', avatar: '👩' },
        { username: 'mike_explorer', rating: 5, tourReview: 'Best tour I\'ve ever been on! The itinerary was perfect and covered all the highlights.', guideReview: 'The guide was friendly and showed us hidden gems we would have never found on our own.', date: '5 days ago', avatar: '👨' },
        { username: 'emily_wanderer', rating: 5, tourReview: 'Incredible tour! Worth every penny. The experience was beyond expectations.', guideReview: 'The guide was professional, patient, and answered all our questions with great detail.', date: '1 week ago', avatar: '👩' },
        { username: 'david_adventures', rating: 4, tourReview: 'Great tour with lots of interesting information about the history and artifacts.', guideReview: 'The guide was engaging and made the experience educational and fun.', date: '2 weeks ago', avatar: '👨' },
        { username: 'jessica_globetrotter', rating: 5, tourReview: 'Perfect tour! We learned so much about ancient Egypt and the artifacts.', guideReview: 'The guide was amazing, passionate about the subject, and made everything come alive!', date: '3 weeks ago', avatar: '👩' },
        { username: 'james_world', rating: 4, tourReview: 'Really enjoyed this tour. Good value for money and well-paced.', guideReview: 'The guide was very knowledgeable about the area and shared interesting stories.', date: '1 month ago', avatar: '👨' },
        { username: 'amanda_journeys', rating: 5, tourReview: 'Outstanding experience! The tour was comprehensive and covered everything we wanted to see.', guideReview: 'The guide made us feel welcome and shared incredible stories that made history come alive.', date: '1 month ago', avatar: '👩' },
        { username: 'robert_travels', rating: 5, tourReview: 'Fantastic tour! We saw everything we wanted and more.', guideReview: 'The guide was excellent, professional, and had a great sense of humor. Made great memories!', date: '2 months ago', avatar: '👨' },
        { username: 'lisa_explorer', rating: 4, tourReview: 'Very informative and enjoyable tour. Learned a lot about the museum and its collections.', guideReview: 'The guide was friendly, approachable, and made the experience special for everyone.', date: '2 months ago', avatar: '👩' },
        { username: 'chris_wanderlust', rating: 5, tourReview: 'Amazing tour! The museum is breathtaking and the tour route was well-planned.', guideReview: 'The guide was passionate about Egyptian history and made the whole experience memorable. Worth every cent!', date: '3 months ago', avatar: '👨' },
        { username: 'sophia_tours', rating: 5, tourReview: 'Exceptional tour experience! The museum is stunning and the tour was perfectly organized.', guideReview: 'Our guide was outstanding - knowledgeable, personable, and went above and beyond to ensure we had a great time.', date: '1 week ago', avatar: '👩' },
        { username: 'alex_discoveries', rating: 4, tourReview: 'Great tour with excellent pacing. We never felt rushed and had time to appreciate everything.', guideReview: 'The guide was professional and had great communication skills. Very informative!', date: '3 weeks ago', avatar: '👨' },
        { username: 'maria_visits', rating: 5, tourReview: 'Wonderful tour! The museum is a must-see and this tour made it even better.', guideReview: 'The guide was fantastic - enthusiastic, knowledgeable, and made everyone feel included.', date: '1 month ago', avatar: '👩' },
        { username: 'john_adventures', rating: 5, tourReview: 'Perfect tour for history lovers! Comprehensive and well-structured.', guideReview: 'The guide was a true expert and storyteller. Made ancient history fascinating and accessible.', date: '2 months ago', avatar: '👨' },
        { username: 'linda_explorer', rating: 4, tourReview: 'Enjoyable tour with great insights into Egyptian culture and history.', guideReview: 'The guide was friendly and patient, especially with our many questions. Great experience!', date: '3 months ago', avatar: '👩' }
    ];
    
    // Shuffle array function
    function shuffleArray(array) {
        const shuffled = [...array];
        for (let i = shuffled.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [shuffled[i], shuffled[j]] = [shuffled[j], shuffled[i]];
        }
        return shuffled;
    }
    
    // Shuffle reviews
    const shuffledReviews = shuffleArray(fakeReviews);
    
    // Display one review at a time
    let currentIndex = 0;
    
    function renderStars(rating) {
        let stars = '';
        for (let i = 1; i <= 5; i++) {
            if (i <= rating) {
                stars += '<i class="fas fa-star text-warning"></i>';
            } else {
                stars += '<i class="far fa-star text-muted"></i>';
            }
        }
        return stars;
    }
    
    function showReview() {
        const review = shuffledReviews[currentIndex];
        
        // Fade out
        reviewsContainer.style.opacity = '0';
        reviewsContainer.style.transition = 'opacity 0.6s ease-in-out';
        
        setTimeout(() => {
            // Update content with tour and guide reviews
            reviewsContainer.innerHTML = `
                <div class="review-item p-4 border rounded" style="background: rgba(0,0,0,0.02);">
                    <div class="d-flex align-items-start mb-3">
                        <div class="review-avatar me-3" style="font-size: 2.5rem;">${review.avatar}</div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <h6 class="mb-0">@${review.username}</h6>
                                    <small class="text-muted">${review.date}</small>
                                </div>
                                <div class="rating">
                                    ${renderStars(review.rating)}
                                </div>
                            </div>
                            <div class="mb-2">
                                <strong class="text-primary">About the Tour:</strong>
                                <p class="mb-2">${review.tourReview}</p>
                            </div>
                            <div>
                                <strong class="text-success">About the Guide:</strong>
                                <p class="mb-0">${review.guideReview}</p>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            // Update review count
            if (reviewCountBadge) {
                reviewCountBadge.textContent = shuffledReviews.length;
            }
            
            // Fade in
            setTimeout(() => {
                reviewsContainer.style.opacity = '1';
            }, 50);
        }, 600);
        
        // Move to next review
        currentIndex = (currentIndex + 1) % shuffledReviews.length;
    }
    
    // Show initial review
    showReview();
    
    // Show new review every 5 seconds
    setInterval(() => {
        // Occasionally reshuffle when we've gone through all reviews
        if (currentIndex === 0) {
            const newShuffled = shuffleArray(fakeReviews);
            shuffledReviews.splice(0, shuffledReviews.length, ...newShuffled);
        }
        showReview();
    }, 5000);
}

// Idle Screen with Tips - Global function available on all pages
function initIdleScreen() {
    // Prevent multiple initializations
    if (window.idleScreenInitialized) {
        return;
    }
    window.idleScreenInitialized = true;
    
    const IDLE_TIMEOUT = 20000; // 20 seconds
    const TIP_ROTATION_INTERVAL = 5000; // 5 seconds
    
    // Website-specific tips about destinations, offers, and features
    const tips = [
        "Book 2 or more tickets and save up to 15% with our special multi-ticket discounts!",
        "Explore ancient Egypt with our exclusive tours to the Pyramids and Egyptian Museum.",
        "Discover the iconic Taj Mahal in India with our specially curated cultural tours.",
        "Experience the vibrant energy of Tokyo's Shibuya district with our Japan tours.",
        "Visit our Special Offers section for exclusive deals on Egypt, India, and Japan tours.",
        "Search tours by destination, category, or price to find your perfect adventure.",
        "Book tours in categories like Historical, Food Tour, Walking Tour, Adventure, and Cultural.",
        "Explore Paris, the City of Light, with our guided tours of iconic landmarks.",
        "Discover New York City's famous attractions with our expert local guides.",
        "Experience the Eternal City of Rome with our historical and cultural tours.",
        "Get 5% off when booking 2 tickets, 10% off for 3 tickets, and 15% off for 4+ tickets!",
        "All our tours are led by verified local guides who know the best hidden gems.",
        "Filter tours by Historical, Food Tour, Walking Tour, Adventure, or Cultural categories.",
        "Check out our Special Offers page for discounted tours to Egypt, India, and Japan.",
        "Book your dream tour and explore destinations like Paris, New York, Rome, and more.",
        "Use our search feature to find tours by destination, category, or your preferred date.",
        "Save money with our multi-ticket discounts - the more tickets you book, the more you save!",
        "Explore ancient wonders in Egypt, marvel at the Taj Mahal in India, or experience modern Tokyo.",
        "Our Special Offers section features exclusive deals that you won't find elsewhere.",
        "Book with confidence - all our guides are verified and passionate about their destinations."
    ];
    
    let idleTimer = null;
    let tipRotationTimer = null;
    let currentTipIndex = 0;
    let isIdle = false;
    
    const overlay = document.getElementById('idle-overlay');
    const audio = document.getElementById('idle-audio');
    const tipContainer = document.querySelector('.idle-tips-container');
    
    if (!overlay || !audio || !tipContainer) {
        console.warn('Idle screen elements not found - may not be loaded yet');
        // Reset flag so it can try again
        window.idleScreenInitialized = false;
        return;
    }
    
    // Unlock audio for playback (required by modern browsers)
    // Modern browsers require user interaction before playing audio
    let audioUnlocked = false;
    
    function unlockAudio() {
        if (audioUnlocked || !audio) return;
        
        // Try to unlock audio by playing and immediately pausing
        const playPromise = audio.play().catch(err => {
            // Audio will be unlocked after first user interaction
            console.log('Audio unlock attempt (will work after user interaction):', err.message);
        });
        
        if (playPromise !== undefined) {
            playPromise.then(() => {
                // Audio unlocked successfully
                audioUnlocked = true;
                audio.pause();
                audio.currentTime = 0;
                console.log('Audio unlocked successfully');
            }).catch(() => {
                // Will be unlocked on next user interaction
            });
        }
    }
    
    // Unlock audio on first user interaction (anywhere on the page)
    const unlockEvents = ['click', 'keydown', 'touchstart', 'mousedown', 'scroll'];
    const unlockAudioOnce = () => {
        if (!audioUnlocked) {
            unlockAudio();
        }
    };
    
    // Add listeners to unlock audio on first interaction
    unlockEvents.forEach(event => {
        document.addEventListener(event, unlockAudioOnce, { once: true, passive: true });
    });
    
    // Also try to unlock immediately if page is already interactive
    if (document.readyState === 'complete') {
        setTimeout(unlockAudio, 100);
    }
    
    // Create tip elements
    tips.forEach((tip, index) => {
        const tipElement = document.createElement('div');
        tipElement.className = 'idle-tip';
        tipElement.setAttribute('data-tip', index);
        tipElement.innerHTML = `
            <i class="fas fa-quote-left"></i><span class="tip-text">${tip}</span><i class="fas fa-quote-right"></i>
        `;
        tipContainer.appendChild(tipElement);
    });
    
    // Show a tip
    function showTip(index) {
        const allTips = tipContainer.querySelectorAll('.idle-tip');
        allTips.forEach((tip, i) => {
            tip.classList.remove('active');
        });
        
        if (allTips[index]) {
            allTips[index].classList.add('active');
        }
    }
    
    // Start idle screen
    function startIdleScreen() {
        if (isIdle) return;
        
        isIdle = true;
        overlay.classList.add('active');
        
        // Play audio with proper error handling
        audio.currentTime = 0;
        audio.volume = 1.0; // Ensure volume is at maximum
        
        // Function to attempt audio playback
        const attemptPlayAudio = () => {
            // Try to unlock audio if not already unlocked
            if (!audioUnlocked) {
                unlockAudio();
                // Wait a bit for unlock to complete, then try playing
                setTimeout(() => {
                    playAudio();
                }, 100);
                return;
            }
            
            playAudio();
        };
        
        const playAudio = () => {
            // Try to play audio
            const playPromise = audio.play();
            
            if (playPromise !== undefined) {
                playPromise.then(() => {
                    // Audio playing successfully
                    audioUnlocked = true;
                    console.log('Idle audio playing successfully');
                }).catch(err => {
                    // Audio play was prevented - this usually means:
                    // 1. Browser autoplay policy blocked it (needs user interaction first)
                    // 2. Audio file not found or corrupted
                    console.warn('Could not play idle audio:', err.message || err);
                    console.warn('Audio will play after next user interaction');
                    
                    // Try to unlock on next user interaction
                    const retryOnInteraction = () => {
                        unlockAudio();
                        setTimeout(() => {
                            audio.play().catch(() => {
                                // Still blocked, will work after user interaction
                            });
                        }, 50);
                        document.removeEventListener('click', retryOnInteraction);
                        document.removeEventListener('keydown', retryOnInteraction);
                    };
                    document.addEventListener('click', retryOnInteraction, { once: true });
                    document.addEventListener('keydown', retryOnInteraction, { once: true });
                });
            } else {
                // Fallback for older browsers
                try {
                    audio.play();
                    audioUnlocked = true;
                } catch (err) {
                    console.warn('Audio play failed:', err);
                }
            }
        };
        
        // Attempt to play audio
        attemptPlayAudio();
        
        // Show first tip
        currentTipIndex = 0;
        showTip(currentTipIndex);
        
        // Rotate tips
        tipRotationTimer = setInterval(() => {
            currentTipIndex = (currentTipIndex + 1) % tips.length;
            showTip(currentTipIndex);
        }, TIP_ROTATION_INTERVAL);
    }
    
    // Stop idle screen
    function stopIdleScreen() {
        if (!isIdle) return;
        
        isIdle = false;
        overlay.classList.remove('active');
        
        // Stop audio
        audio.pause();
        audio.currentTime = 0;
        
        // Stop tip rotation
        if (tipRotationTimer) {
            clearInterval(tipRotationTimer);
            tipRotationTimer = null;
        }
    }
    
    // Reset idle timer
    function resetIdleTimer() {
        if (idleTimer) {
            clearTimeout(idleTimer);
        }
        
        stopIdleScreen();
        
        idleTimer = setTimeout(() => {
            startIdleScreen();
        }, IDLE_TIMEOUT);
    }
    
    // User activity events
    const activityEvents = [
        'mousedown',
        'mousemove',
        'keypress',
        'scroll',
        'touchstart',
        'click',
        'keydown'
    ];
    
    activityEvents.forEach(event => {
        document.addEventListener(event, resetIdleTimer, { passive: true });
    });
    
    // Start the idle timer
    resetIdleTimer();
    
    // Handle visibility change (tab switching)
    document.addEventListener('visibilitychange', () => {
        if (document.hidden) {
            if (idleTimer) clearTimeout(idleTimer);
            stopIdleScreen();
        } else {
            resetIdleTimer();
        }
    });
}

// Pagination Ellipsis Expansion - Using Event Delegation
let paginationEllipsisInitialized = false;

function initPaginationEllipsis() {
    console.log('[Pagination] Initializing ellipsis expansion...');
    
    // Use event delegation on document to handle dynamically added ellipsis (only once)
    if (!paginationEllipsisInitialized) {
        console.log('[Pagination] Setting up document-level click listener');
        document.addEventListener('click', function(e) {
            const ellipsis = e.target.closest('.pagination-ellipsis');
            if (ellipsis) {
                console.log('[Pagination] Ellipsis clicked via delegation!', ellipsis);
                e.preventDefault();
                e.stopPropagation();
                expandEllipsis(ellipsis);
            }
        }, true); // Use capture phase to ensure it fires
        paginationEllipsisInitialized = true;
    }
    
    // Also attach directly to existing ellipsis elements and ensure they're clickable
    const ellipsisElements = document.querySelectorAll('.pagination-ellipsis');
    console.log('[Pagination] Found', ellipsisElements.length, 'ellipsis elements');
    
    ellipsisElements.forEach((ellipsis, index) => {
        console.log(`[Pagination] Processing ellipsis ${index + 1}:`, ellipsis);
        
        // Check data attributes
        const expandStart = ellipsis.getAttribute('data-expand-start');
        const expandEnd = ellipsis.getAttribute('data-expand-end');
        console.log(`[Pagination] Ellipsis ${index + 1} data:`, { expandStart, expandEnd });
        
        // Ensure it's clickable via inline styles (overrides any CSS)
        ellipsis.style.cursor = 'pointer';
        ellipsis.style.pointerEvents = 'auto';
        ellipsis.style.userSelect = 'none';
        ellipsis.style.border = '2px solid #e9ecef';
        ellipsis.style.background = '#fff';
        ellipsis.style.color = '#495057';
        ellipsis.style.borderRadius = '25px';
        ellipsis.style.padding = '0.5rem 0.75rem';
        ellipsis.style.minWidth = '40px';
        ellipsis.style.height = '40px';
        ellipsis.style.transition = 'all 0.3s ease';
        
        console.log(`[Pagination] Applied styles to ellipsis ${index + 1}`);
        
        // Remove parent disabled class if present and ensure ellipsis is clickable
        const parent = ellipsis.closest('.pagination-item, .page-item');
        if (parent) {
            console.log(`[Pagination] Parent element:`, parent, 'Classes:', parent.className);
            if (parent.classList.contains('disabled')) {
                console.log(`[Pagination] Removing disabled class from parent:`, parent);
                parent.classList.remove('disabled');
            }
            parent.style.pointerEvents = 'auto';
            
            // Also remove disabled class from ellipsis itself if present
            if (ellipsis.classList.contains('disabled')) {
                console.log(`[Pagination] Removing disabled class from ellipsis`);
                ellipsis.classList.remove('disabled');
            }
        }
        
        // Check computed styles
        const computedStyle = window.getComputedStyle(ellipsis);
        console.log(`[Pagination] Ellipsis ${index + 1} computed styles:`, {
            cursor: computedStyle.cursor,
            pointerEvents: computedStyle.pointerEvents,
            display: computedStyle.display,
            visibility: computedStyle.visibility,
            opacity: computedStyle.opacity
        });
        
        // Remove any existing click listeners to avoid duplicates
        const newEllipsis = ellipsis.cloneNode(true);
        ellipsis.parentNode.replaceChild(newEllipsis, ellipsis);
        
        // Re-apply styles with !important via setProperty
        newEllipsis.style.setProperty('cursor', 'pointer', 'important');
        newEllipsis.style.setProperty('pointer-events', 'auto', 'important');
        newEllipsis.style.setProperty('user-select', 'none', 'important');
        newEllipsis.style.setProperty('opacity', '1', 'important');
        newEllipsis.style.border = '2px solid #e9ecef';
        newEllipsis.style.background = '#fff';
        newEllipsis.style.color = '#495057';
        newEllipsis.style.borderRadius = '25px';
        newEllipsis.style.padding = '0.5rem 0.75rem';
        newEllipsis.style.minWidth = '40px';
        newEllipsis.style.height = '40px';
        newEllipsis.style.transition = 'all 0.3s ease';
        
        // Remove disabled class if present
        newEllipsis.classList.remove('disabled');
        
        // Ensure parent is not disabled
        const newParent = newEllipsis.closest('.pagination-item, .page-item');
        if (newParent && newParent.classList.contains('disabled')) {
            console.log(`[Pagination] Removing disabled from new parent`);
            newParent.classList.remove('disabled');
            newParent.style.setProperty('pointer-events', 'auto', 'important');
        }
        
        // Add direct click listener
        newEllipsis.addEventListener('click', function(e) {
            console.log('[Pagination] Direct click on ellipsis!', this, e);
            e.preventDefault();
            e.stopPropagation();
            e.stopImmediatePropagation();
            expandEllipsis(this);
        }, true);
        
        // Also add mousedown to catch events earlier
        newEllipsis.addEventListener('mousedown', function(e) {
            console.log('[Pagination] Mousedown on ellipsis!', this, e);
            e.stopPropagation();
        }, true);
        
        console.log(`[Pagination] Added click listener to ellipsis ${index + 1}`);
    });
    
    console.log('[Pagination] Initialization complete');
}

function expandEllipsis(ellipsisElement) {
    console.log('[Pagination] expandEllipsis called with:', ellipsisElement);
    
    const paginationWrapper = ellipsisElement.closest('.pagination-wrapper');
    const paginationList = ellipsisElement.closest('.pagination-custom') || ellipsisElement.closest('.pagination');
    
    console.log('[Pagination] Found elements:', {
        paginationWrapper: !!paginationWrapper,
        paginationList: !!paginationList,
        wrapper: paginationWrapper,
        list: paginationList
    });
    
    if (!paginationList || !paginationWrapper) {
        console.error('[Pagination] Missing required elements!', {
            hasWrapper: !!paginationWrapper,
            hasList: !!paginationList
        });
        return;
    }
    
    // Get total pages and current page from data attributes
    const totalPages = parseInt(paginationWrapper.getAttribute('data-total-pages')) || 0;
    const currentPage = parseInt(paginationWrapper.getAttribute('data-current-page')) || 1;
    
    console.log('[Pagination] Pagination data:', { totalPages, currentPage });
    
    if (totalPages === 0) {
        console.error('[Pagination] Total pages is 0!');
        return;
    }
    
    // Get expand range from ellipsis data attributes
    const expandStart = parseInt(ellipsisElement.getAttribute('data-expand-start')) || 0;
    const expandEnd = parseInt(ellipsisElement.getAttribute('data-expand-end')) || 0;
    
    console.log('[Pagination] Expand range:', { expandStart, expandEnd });
    
    if (expandStart === 0 || expandEnd === 0 || expandStart > expandEnd) {
        console.error('[Pagination] Invalid expand range!', { expandStart, expandEnd });
        return;
    }
    
    // Get base URL and search params from existing pagination links
    const existingLink = paginationList.querySelector('a.pagination-link:not(.pagination-nav)') || paginationList.querySelector('a.page-link:not(.disabled)');
    
    console.log('[Pagination] Existing link:', existingLink);
    
    if (!existingLink || !existingLink.href) {
        console.error('[Pagination] No existing link found!');
        return;
    }
    
    try {
        const url = new URL(existingLink.href, window.location.origin);
        const basePath = url.pathname;
        const searchParams = new URLSearchParams(url.search);
        
        console.log('[Pagination] URL info:', { basePath, searchParams: Object.fromEntries(searchParams) });
        
        // Remove page parameter to rebuild it
        searchParams.delete('page');
        
        // Get all filter parameters
        const filterParams = {};
        for (const [key, value] of searchParams.entries()) {
            filterParams[key] = value;
        }
        
        console.log('[Pagination] Filter params:', filterParams);
        
        // Create page number links for the expanded range
        const fragment = document.createDocumentFragment();
        const ellipsisParent = ellipsisElement.parentElement;
        
        console.log('[Pagination] Ellipsis parent:', ellipsisParent);
        
        for (let i = expandStart; i <= expandEnd; i++) {
            const li = document.createElement('li');
            // Check if using Bootstrap pagination or custom pagination
            const isBootstrapPagination = paginationList.classList.contains('pagination');
            if (isBootstrapPagination) {
                li.className = 'page-item' + (i === currentPage ? ' active' : '');
            } else {
                li.className = 'pagination-item' + (i === currentPage ? ' active' : '');
            }
            
            const a = document.createElement('a');
            if (isBootstrapPagination) {
                a.className = 'page-link' + (i === currentPage ? ' active' : '');
            } else {
                a.className = 'pagination-link' + (i === currentPage ? ' active' : '');
            }
            
            // Build URL with filters and page number
            const pageParams = { ...filterParams };
            if (i > 1) {
                pageParams.page = i;
            }
            const queryString = Object.keys(pageParams).length > 0 ? '?' + new URLSearchParams(pageParams).toString() : '';
            a.href = basePath + queryString;
            a.textContent = i;
            
            console.log(`[Pagination] Created page link ${i}:`, a.href);
            
            li.appendChild(a);
            fragment.appendChild(li);
        }
        
        console.log('[Pagination] Replacing ellipsis with', expandEnd - expandStart + 1, 'page links');
        
        // Replace ellipsis with the new page links
        ellipsisParent.replaceWith(...Array.from(fragment.children));
        
        console.log('[Pagination] Ellipsis expanded successfully!');
        
        // Re-initialize ellipsis handlers for any remaining ellipsis
        setTimeout(() => {
            initPaginationEllipsis();
        }, 100);
    } catch (error) {
        console.error('[Pagination] Error expanding ellipsis:', error);
        console.error('[Pagination] Stack trace:', error.stack);
    }
}

