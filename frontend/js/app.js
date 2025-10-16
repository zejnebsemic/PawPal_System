// SPA Router and Application Logic

class App {
    constructor() {
        this.currentPage = 'home';
        this.currentUser = null;
        this.init();
    }

    init() {
        // Check for existing session
        this.checkSession();

        // Set up navigation
        this.setupNavigation();

        // Load initial page
        this.loadPage('home');

        // Set up logout button
        document.getElementById('logout-btn')?.addEventListener('click', (e) => {
            e.preventDefault();
            this.logout();
        });
    }

    checkSession() {
        // Check if user is logged in (using localStorage for demo)
        const user = localStorage.getItem('currentUser');
        if (user) {
            this.currentUser = JSON.parse(user);
            this.updateNavForLoggedInUser();
        }
    }

    setupNavigation() {
        // Add click handlers to all navigation links
        document.querySelectorAll('[data-page]').forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const page = e.currentTarget.getAttribute('data-page');
                this.loadPage(page);

                // Update active state
                document.querySelectorAll('.nav-link').forEach(l => l.classList.remove('active'));
                e.currentTarget.classList.add('active');

                // Close mobile menu if open
                const navbarCollapse = document.querySelector('.navbar-collapse');
                if (navbarCollapse.classList.contains('show')) {
                    navbarCollapse.classList.remove('show');
                }
            });
        });
    }

    async loadPage(page) {
        this.currentPage = page;
        const contentDiv = document.getElementById('app-content');

        try {
            // Show loading state
            contentDiv.innerHTML = '<div class="text-center py-5"><div class="spinner-border text-primary" role="status"></div></div>';

            // Fetch the page HTML
            const response = await fetch(`frontend/views/${page}.html`);
            if (!response.ok) throw new Error('Page not found');

            const html = await response.text();
            contentDiv.innerHTML = html;

            // Add fade-in animation
            contentDiv.classList.add('page-transition');

            // Initialize page-specific functionality
            this.initPageFunctionality(page);

            // Scroll to top
            window.scrollTo({ top: 0, behavior: 'smooth' });

        } catch (error) {
            console.error('Error loading page:', error);
            contentDiv.innerHTML = `
                <div class="container py-5">
                    <div class="alert alert-danger">
                        <h4>Page Not Found</h4>
                        <p>The page you're looking for doesn't exist.</p>
                        <button class="btn btn-primary" onclick="app.loadPage('home')">Go Home</button>
                    </div>
                </div>
            `;
        }
    }

    initPageFunctionality(page) {
        // Initialize page-specific JavaScript
        switch(page) {
            case 'login':
                this.initLoginPage();
                break;
            case 'register':
                this.initRegisterPage();
                break;
            case 'browse':
                this.initBrowsePage();
                break;
            case 'pet-detail':
                this.initPetDetailPage();
                break;
            case 'requests':
                this.initRequestsPage();
                break;
            case 'admin':
                this.initAdminPage();
                break;
            case 'profile':
                this.initProfilePage();
                break;
            case 'shelters':
                this.initSheltersPage();
                break;
            case 'reviews':
                this.initReviewsPage();
                break;
        }
    }

    // Login Page
    initLoginPage() {
        const loginForm = document.getElementById('login-form');
        if (loginForm) {
            loginForm.addEventListener('submit', (e) => {
                e.preventDefault();
                const email = document.getElementById('email').value;
                const password = document.getElementById('password').value;

                // Simulate login (replace with actual API call)
                this.login({ email, name: email.split('@')[0], role: 'user' });
            });
        }
    }

    // Register Page
    initRegisterPage() {
        const registerForm = document.getElementById('register-form');
        if (registerForm) {
            registerForm.addEventListener('submit', (e) => {
                e.preventDefault();
                const name = document.getElementById('name').value;
                const email = document.getElementById('email').value;

                // Simulate registration (replace with actual API call)
                this.login({ email, name, role: 'user' });
            });
        }
    }

    // Browse Page
    initBrowsePage() {
        // Add filter handlers
        const filterInputs = document.querySelectorAll('.filter-input');
        filterInputs.forEach(input => {
            input.addEventListener('change', () => this.filterPets());
        });

        // Add pet card click handlers
        document.querySelectorAll('.pet-card').forEach(card => {
            card.addEventListener('click', (e) => {
                if (!e.target.closest('.btn')) {
                    const petId = card.getAttribute('data-pet-id');
                    this.viewPetDetail(petId);
                }
            });
        });

        // Add adoption button handlers
        document.querySelectorAll('.adopt-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.stopPropagation();
                const petId = btn.getAttribute('data-pet-id');
                this.requestAdoption(petId);
            });
        });
    }

    // Pet Detail Page
    initPetDetailPage() {
        const adoptBtn = document.getElementById('adopt-btn');
        if (adoptBtn) {
            adoptBtn.addEventListener('click', (e) => {
                const petId = adoptBtn.getAttribute('data-pet-id');
                this.requestAdoption(petId);
            });
        }
    }

    // Requests Page
    initRequestsPage() {
        // Add cancel request handlers
        document.querySelectorAll('.cancel-request-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const requestId = btn.getAttribute('data-request-id');
                this.cancelRequest(requestId);
            });
        });
    }

    // Admin Page
    initAdminPage() {
        // Add approve/reject handlers
        document.querySelectorAll('.approve-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const requestId = btn.getAttribute('data-request-id');
                this.approveRequest(requestId);
            });
        });

        document.querySelectorAll('.reject-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const requestId = btn.getAttribute('data-request-id');
                this.rejectRequest(requestId);
            });
        });
    }

    // Profile Page
    initProfilePage() {
        const profileForm = document.getElementById('profile-form');
        if (profileForm) {
            profileForm.addEventListener('submit', (e) => {
                e.preventDefault();
                this.updateProfile();
            });
        }
    }

    // Shelters Page
    initSheltersPage() {
        // Add view details handlers
        document.querySelectorAll('.view-shelter-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const shelterId = btn.getAttribute('data-shelter-id');
                this.viewShelterDetails(shelterId);
            });
        });
    }

    // Reviews Page
    initReviewsPage() {
        const reviewForm = document.getElementById('review-form');
        if (reviewForm) {
            reviewForm.addEventListener('submit', (e) => {
                e.preventDefault();
                this.submitReview();
            });
        }

        // Star rating handlers
        document.querySelectorAll('.star-rating i').forEach((star, index) => {
            star.addEventListener('click', () => {
                this.setRating(index + 1);
            });
        });
    }

    // Helper Functions
    filterPets() {
        // Implement pet filtering logic
        console.log('Filtering pets...');
    }

    viewPetDetail(petId) {
        // Store pet ID for detail page
        sessionStorage.setItem('currentPetId', petId);
        this.loadPage('pet-detail');
    }

    requestAdoption(petId) {
        if (!this.currentUser) {
            alert('Please log in to request adoption');
            this.loadPage('login');
            return;
        }

        // Simulate adoption request
        alert('Adoption request submitted successfully!');
        this.loadPage('requests');
    }

    cancelRequest(requestId) {
        if (confirm('Are you sure you want to cancel this request?')) {
            alert('Request cancelled successfully');
            this.loadPage('requests');
        }
    }

    approveRequest(requestId) {
        if (confirm('Approve this adoption request?')) {
            alert('Request approved successfully');
            this.loadPage('admin');
        }
    }

    rejectRequest(requestId) {
        if (confirm('Reject this adoption request?')) {
            alert('Request rejected');
            this.loadPage('admin');
        }
    }

    updateProfile() {
        alert('Profile updated successfully');
    }

    viewShelterDetails(shelterId) {
        alert('Viewing shelter details: ' + shelterId);
    }

    submitReview() {
        alert('Review submitted successfully');
        this.loadPage('reviews');
    }

    setRating(rating) {
        const stars = document.querySelectorAll('.star-rating i');
        stars.forEach((star, index) => {
            if (index < rating) {
                star.classList.remove('bi-star');
                star.classList.add('bi-star-fill');
            } else {
                star.classList.remove('bi-star-fill');
                star.classList.add('bi-star');
            }
        });
        document.getElementById('rating-value').value = rating;
    }

    // Auth Functions
    login(user) {
        this.currentUser = user;
        localStorage.setItem('currentUser', JSON.stringify(user));
        this.updateNavForLoggedInUser();
        alert('Login successful!');
        this.loadPage('home');
    }

    logout() {
        this.currentUser = null;
        localStorage.removeItem('currentUser');
        this.updateNavForLoggedOut();
        alert('Logged out successfully');
        this.loadPage('home');
    }

    updateNavForLoggedInUser() {
        document.getElementById('nav-login').style.display = 'none';
        document.getElementById('nav-logout').style.display = 'block';
        document.getElementById('nav-profile').style.display = 'block';
        document.getElementById('nav-requests').style.display = 'block';

        if (this.currentUser.role === 'admin') {
            document.getElementById('nav-admin').style.display = 'block';
        }
    }

    updateNavForLoggedOut() {
        document.getElementById('nav-login').style.display = 'block';
        document.getElementById('nav-logout').style.display = 'none';
        document.getElementById('nav-profile').style.display = 'none';
        document.getElementById('nav-requests').style.display = 'none';
        document.getElementById('nav-admin').style.display = 'none';
    }
}

// Initialize the app
const app = new App();
