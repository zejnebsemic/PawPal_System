// Authentication Service - Manages user authentication and authorization
const AuthService = (function() {
    'use strict';

    // Demo users (in a real app, this would be handled by backend API)
    const users = [
        {
            id: 1,
            name: 'Admin User',
            email: 'admin@example.com',
            password: 'admin123',
            role: 'admin'
        },
        {
            id: 2,
            name: 'Regular User',
            email: 'user@example.com',
            password: 'user123',
            role: 'user'
        }
    ];

    // Current user session
    let currentUser = null;

    // Initialize - check for existing session
    function init() {
        const savedUser = localStorage.getItem('currentUser');
        if (savedUser) {
            try {
                currentUser = JSON.parse(savedUser);
            } catch (e) {
                console.error('Failed to parse saved user session');
                localStorage.removeItem('currentUser');
            }
        }
    }

    return {
        // Initialize authentication service
        init: init,

        // Login user
        login: function(email, password) {
            const user = users.find(u => u.email === email && u.password === password);

            if (user) {
                // Don't store password in session
                currentUser = {
                    id: user.id,
                    name: user.name,
                    email: user.email,
                    role: user.role
                };

                localStorage.setItem('currentUser', JSON.stringify(currentUser));
                return { success: true, user: currentUser };
            }

            return { success: false, message: 'Invalid email or password' };
        },

        // Register new user
        register: function(name, email, password) {
            // Check if user already exists
            const existingUser = users.find(u => u.email === email);

            if (existingUser) {
                return { success: false, message: 'Email already registered' };
            }

            // Create new user (in real app, this would save to database)
            const newUser = {
                id: users.length + 1,
                name: name,
                email: email,
                password: password,
                role: 'user'
            };

            users.push(newUser);

            // Log in the new user
            currentUser = {
                id: newUser.id,
                name: newUser.name,
                email: newUser.email,
                role: newUser.role
            };

            localStorage.setItem('currentUser', JSON.stringify(currentUser));
            return { success: true, user: currentUser };
        },

        // Logout user
        logout: function() {
            currentUser = null;
            localStorage.removeItem('currentUser');
        },

        // Get current user
        getCurrentUser: function() {
            return currentUser;
        },

        // Check if user is logged in
        isLoggedIn: function() {
            return currentUser !== null;
        },

        // Check if current user is admin
        isAdmin: function() {
            return currentUser && currentUser.role === 'admin';
        },

        // Check if user has required role
        hasRole: function(role) {
            return currentUser && currentUser.role === role;
        },

        // Require login (redirect if not logged in)
        requireLogin: function() {
            if (!this.isLoggedIn()) {
                alert('Please log in to access this page');
                window.location.hash = '#login';
                return false;
            }
            return true;
        },

        // Require admin (redirect if not admin)
        requireAdmin: function() {
            if (!this.isLoggedIn()) {
                alert('Please log in to access this page');
                window.location.hash = '#login';
                return false;
            }

            if (!this.isAdmin()) {
                alert('Access denied. Admin privileges required.');
                window.location.hash = '#home';
                return false;
            }

            return true;
        },

        // Update user profile
        updateProfile: function(updates) {
            if (!currentUser) {
                return { success: false, message: 'No user logged in' };
            }

            // Update current user
            Object.assign(currentUser, updates);

            // Save to localStorage
            localStorage.setItem('currentUser', JSON.stringify(currentUser));

            return { success: true, user: currentUser };
        }
    };
})();

// Initialize auth service
AuthService.init();
