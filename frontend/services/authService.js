
const AuthService = (function() {
    'use strict';

    
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

   
    let currentUser = null;

   
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
       
        init: init,

      
        login: function(email, password) {
            const user = users.find(u => u.email === email && u.password === password);

            if (user) {
               
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

       
        register: function(name, email, password) {
            
            const existingUser = users.find(u => u.email === email);

            if (existingUser) {
                return { success: false, message: 'Email already registered' };
            }

            
            const newUser = {
                id: users.length + 1,
                name: name,
                email: email,
                password: password,
                role: 'user'
            };

            users.push(newUser);

            
            currentUser = {
                id: newUser.id,
                name: newUser.name,
                email: newUser.email,
                role: newUser.role
            };

            localStorage.setItem('currentUser', JSON.stringify(currentUser));
            return { success: true, user: currentUser };
        },

       
        logout: function() {
            currentUser = null;
            localStorage.removeItem('currentUser');
        },

        
        getCurrentUser: function() {
            return currentUser;
        },

       
        isLoggedIn: function() {
            return currentUser !== null;
        },

       
        isAdmin: function() {
            return currentUser && currentUser.role === 'admin';
        },

       
        hasRole: function(role) {
            return currentUser && currentUser.role === role;
        },

       
        requireLogin: function() {
            if (!this.isLoggedIn()) {
                alert('Please log in to access this page');
                window.location.hash = '#login';
                return false;
            }
            return true;
        },

        
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

       
        updateProfile: function(updates) {
            if (!currentUser) {
                return { success: false, message: 'No user logged in' };
            }

            
            Object.assign(currentUser, updates);

           
            localStorage.setItem('currentUser', JSON.stringify(currentUser));

            return { success: true, user: currentUser };
        }
    };
})();


AuthService.init();
