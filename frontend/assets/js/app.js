

$(document).ready(function() {
    'use strict';

   
    $.spapp.init({
        defaultView: 'home',
        anchors: {
            main: '#app-content'
        },
        routes: {
            'home': {
                view: 'home',
                load: 'pages/home.html',
                onCreate: function() {
                    updateNavigation();
                }
            },
            'browse': {
                view: 'browse',
                load: 'pages/browse.html',
                onCreate: function() {
                    updateNavigation();
                },
                onReady: function() {
                    initBrowsePage();
                }
            },
            'pet-detail': {
                view: 'pet-detail',
                load: 'pages/pet-detail.html',
                onReady: function() {
                    updateNavigation();
                },
                onReady: function() {
                    initPetDetailPage();
                }
            },
            'shelters': {
                view: 'shelters',
                load: 'pages/shelters.html',
                onCreate: function() {
                    updateNavigation();
                }
            },
            'reviews': {
                view: 'reviews',
                load: 'pages/reviews.html',
                onCreate: function() {
                    updateNavigation();
                },
                onReady: function() {
                    initReviewsPage();
                }
            },
            'login': {
                view: 'login',
                load: 'pages/login.html',
                onCreate: function() {
                    updateNavigation();
                },
                onReady: function() {
                    initLoginPage();
                }
            },
            'register': {
                view: 'register',
                load: 'pages/register.html',
                onCreate: function() {
                    updateNavigation();
                },
                onReady: function() {
                    initRegisterPage();
                }
            },
            'profile': {
                view: 'profile',
                load: 'pages/profile.html',
                onCreate: function() {
                    if (!AuthService.requireLogin()) return;
                    updateNavigation();
                },
                onReady: function() {
                    initProfilePage();
                }
            },
            'requests': {
                view: 'requests',
                load: 'pages/requests.html',
                onCreate: function() {
                    if (!AuthService.requireLogin()) return;
                    updateNavigation();
                },
                onReady: function() {
                    initRequestsPage();
                }
            },
            'admin': {
                view: 'admin',
                load: 'pages/admin.html',
                onCreate: function() {
                    if (!AuthService.requireAdmin()) return;
                    updateNavigation();
                },
                onReady: function() {
                    initAdminPage();
                }
            }
        }
    });

    
    function updateNavigation() {
        const currentUser = AuthService.getCurrentUser();

        if (currentUser) {
            $('#nav-login').hide();
            $('#nav-logout').show();
            $('#nav-profile').show();
            $('#nav-requests').show();

            if (currentUser.role === 'admin') {
                $('#nav-admin').show();
            } else {
                $('#nav-admin').hide();
            }
        } else {
            $('#nav-login').show();
            $('#nav-logout').hide();
            $('#nav-profile').hide();
            $('#nav-requests').hide();
            $('#nav-admin').hide();
        }

       
        const currentController = $.spapp.getCurrentController();
        $('.nav-link').removeClass('active');
        $('a[href="#' + currentController + '"]').addClass('active');
    }

   
    $('#logout-btn').on('click', function(e) {
        e.preventDefault();
        AuthService.logout();
        alert('Logged out successfully');
        window.location.hash = '#home';
    });

    function initLoginPage() {
        $('#login-form').on('submit', function(e) {
            e.preventDefault();

            const email = $('#email').val();
            const password = $('#password').val();

            const result = AuthService.login(email, password);

            if (result.success) {
                alert('Login successful! Welcome, ' + result.user.name);
                window.location.hash = '#home';
            } else {
                alert(result.message);
            }
        });
    }

   
    function initRegisterPage() {
        $('#register-form').on('submit', function(e) {
            e.preventDefault();

            const name = $('#name').val();
            const email = $('#email').val();
            const password = $('#password').val();
            const confirmPassword = $('#confirm-password').val();

            if (password !== confirmPassword) {
                alert('Passwords do not match');
                return;
            }

            const result = AuthService.register(name, email, password);

            if (result.success) {
                alert('Registration successful! Welcome, ' + result.user.name);
                window.location.hash = '#home';
            } else {
                alert(result.message);
            }
        });
    }

   
    function initBrowsePage() {
      
        $(document).on('click', '.pet-card', function(e) {
            if (!$(e.target).closest('.adopt-btn').length) {
                const petId = $(this).data('pet-id');
                sessionStorage.setItem('currentPetId', petId);
                window.location.hash = '#pet-detail';
            }
        });

       
        $(document).on('click', '.adopt-btn', function(e) {
            e.stopPropagation();
            const petId = $(this).data('pet-id');
            requestAdoption(petId);
        });

       
        $('.filter-input').on('change', function() {
            filterPets();
        });

        $('#search').on('input', function() {
            filterPets();
        });
    }

   
    function initPetDetailPage() {
        const petId = sessionStorage.getItem('currentPetId');

        if (!petId) {
            alert('No pet selected');
            window.location.hash = '#browse';
            return;
        }

        const pet = PetService.getPetById(petId);

        if (!pet) {
            alert('Pet not found');
            window.location.hash = '#browse';
            return;
        }

       
        updatePetDetailPage(pet);

       
        $('#adopt-btn').on('click', function() {
            requestAdoption(pet.id);
        });
    }

   
    function updatePetDetailPage(pet) {
        const container = $('#app-content');

       
        container.find('.breadcrumb-item.active').text(pet.name);

        
        container.find('.card-img-top').first().attr('src', pet.image).attr('alt', pet.name);

        
        container.find('.display-5').text(pet.name);
        container.find('.badge.bg-primary').text(pet.type);
        container.find('.badge.bg-success, .badge.bg-warning').removeClass('bg-success bg-warning')
            .addClass(pet.status === 'Available' ? 'bg-success' : 'bg-warning')
            .text(pet.status);

       
        const stats = container.find('.stat-icon').parent().parent();
        stats.eq(0).find('strong').text(pet.age + ' years');
        stats.eq(1).find('strong').text(pet.size);
        stats.eq(2).find('strong').text(pet.gender);
        stats.eq(3).find('strong').text(pet.shelter);

        
        container.find('h4:contains("About")').next('p').text(pet.fullDescription.split('.')[0] + '.');
        container.find('h4:contains("About")').next('p').next('p').text(pet.fullDescription.split('.').slice(1).join('.'));

        
        const shelterInfo = PetService.getShelterInfo(pet.shelter);
        if (shelterInfo) {
            const shelterSection = container.find('h4:contains("Shelter Information")');
            shelterSection.next('h5').text(shelterInfo.name);
            const shelterList = shelterSection.parent().find('ul li');
            shelterList.eq(0).html('<i class="bi bi-geo-alt-fill text-primary me-2"></i>' + shelterInfo.address);
            shelterList.eq(1).html('<i class="bi bi-telephone-fill text-primary me-2"></i>' + shelterInfo.phone);
            shelterList.eq(2).html('<i class="bi bi-envelope-fill text-primary me-2"></i>' + shelterInfo.email);
            shelterList.eq(3).html('<i class="bi bi-clock-fill text-primary me-2"></i>' + shelterInfo.hours);
        }

        
        if (pet.medicalHistory) {
            const medicalList = container.find('h4:contains("Medical History")').parent().find('ul li');
            medicalList.eq(0).find('strong').next().text(pet.medicalHistory.vaccinations);
            medicalList.eq(1).find('strong').next().text(pet.medicalHistory.spayedNeutered ? 'Yes' : 'No');
            medicalList.eq(2).find('strong').next().text(pet.medicalHistory.microchipped ? 'Yes' : 'No');
            medicalList.eq(3).find('strong').next().text(pet.medicalHistory.healthStatus);
            medicalList.eq(4).find('strong').next().text(pet.medicalHistory.specialNeeds);
        }
    }

    
    function filterPets() {
        const filters = {
            type: $('#pet-type').val(),
            age: $('#age').val(),
            size: $('#size').val(),
            shelter: $('#shelter').val(),
            search: $('#search').val()
        };

        const filteredPets = PetService.filterPets(filters);

       
        $('.text-muted strong').first().text(filteredPets.length);

       
        $('.pet-card').each(function() {
            const petId = $(this).data('pet-id');
            const pet = PetService.getPetById(petId);

            if (filteredPets.includes(pet)) {
                $(this).parent().show();
            } else {
                $(this).parent().hide();
            }
        });
    }

    
    function requestAdoption(petId) {
        if (!AuthService.isLoggedIn()) {
            alert('Please log in to request adoption');
            window.location.hash = '#login';
            return;
        }

        const user = AuthService.getCurrentUser();
        const result = RequestService.createRequest(user.id, petId);

        if (result.success) {
            const pet = PetService.getPetById(petId);
            alert('Adoption request submitted for ' + pet.name + '!\nThe shelter will contact you soon.');
            window.location.hash = '#requests';
        } else {
            alert('Failed to submit adoption request: ' + result.message);
        }
    }

    
    function initProfilePage() {
        const user = AuthService.getCurrentUser();

        if (user) {
            $('#profile-name').val(user.name);
            $('#profile-email').val(user.email);
        }

        $('#profile-form').on('submit', function(e) {
            e.preventDefault();

            const updates = {
                name: $('#profile-name').val(),
                email: $('#profile-email').val()
            };

            const result = AuthService.updateProfile(updates);

            if (result.success) {
                alert('Profile updated successfully!');
            } else {
                alert(result.message);
            }
        });
    }

   
    function initReviewsPage() {
        const stars = $('.star-rating i');

        stars.on('click', function() {
            const rating = $(this).data('rating');
            setRating(rating);
        });

        $('#review-form').on('submit', function(e) {
            e.preventDefault();

            if (!AuthService.isLoggedIn()) {
                alert('Please log in to submit a review');
                window.location.hash = '#login';
                return;
            }

            alert('Review submitted successfully!');
            $(this)[0].reset();
            setRating(0);
        });
    }

    function setRating(rating) {
        $('.star-rating i').each(function(index) {
            if (index < rating) {
                $(this).removeClass('bi-star').addClass('bi-star-fill');
            } else {
                $(this).removeClass('bi-star-fill').addClass('bi-star');
            }
        });
        $('#rating-value').val(rating);
    }

    
    function initAdminPage() {
       
        loadAdminRequests();

      
        $(document).on('click', '.approve-btn', function() {
            const requestId = $(this).data('request-id');
            if (confirm('Approve this adoption request?')) {
                const result = RequestService.updateRequestStatus(requestId, 'approved', 'Approved by admin');

                if (result.success) {
                    alert('Request approved successfully!');
                    loadAdminRequests(); 
                } else {
                    alert('Failed to approve request: ' + result.message);
                }
            }
        });

        $(document).on('click', '.reject-btn', function() {
            const requestId = $(this).data('request-id');
            if (confirm('Reject this adoption request?')) {
                const result = RequestService.updateRequestStatus(requestId, 'rejected', 'Rejected by admin');

                if (result.success) {
                    alert('Request rejected');
                    loadAdminRequests(); 
                } else {
                    alert('Failed to reject request: ' + result.message);
                }
            }
        });
    }

    
    function loadAdminRequests() {
        const requests = RequestService.getAllRequests();
        const stats = RequestService.getStatistics();

       
        $('#admin-pending-count').text(stats.pending);
        $('#admin-total-pets').text(PetService.getAllPets().length);

        
        const tbody = $('#admin-requests-table tbody');
        tbody.empty();

        requests.forEach(function(request) {
            const statusBadge = request.status === 'pending' ? 'bg-warning' :
                              request.status === 'approved' ? 'bg-success' : 'bg-danger';
            const statusText = request.status.charAt(0).toUpperCase() + request.status.slice(1);

            const actionButtons = request.status === 'pending' ?
                `<button class="btn btn-success btn-sm approve-btn" data-request-id="${request.id}">
                    <i class="bi bi-check-lg"></i>
                </button>
                <button class="btn btn-danger btn-sm reject-btn" data-request-id="${request.id}">
                    <i class="bi bi-x-lg"></i>
                </button>` :
                `<button class="btn btn-info btn-sm">
                    <i class="bi bi-eye"></i>
                </button>`;

            const row = `
                <tr>
                    <td>${request.requestId}</td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2" style="width: 30px; height: 30px; min-width: 30px;">
                                <small class="fw-bold">${request.user.initials}</small>
                            </div>
                            <div>
                                <small class="fw-bold">${request.user.name}</small>
                                <br>
                                <small class="text-muted">${request.user.email}</small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <strong>${request.pet.name}</strong>
                        <br>
                        <small class="text-muted">${request.pet.breed}, ${request.pet.age} years</small>
                    </td>
                    <td>${request.shelter}</td>
                    <td>${request.dateApplied}</td>
                    <td><span class="badge ${statusBadge}">${statusText}</span></td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            ${actionButtons}
                        </div>
                    </td>
                </tr>
            `;

            tbody.append(row);
        });
    }

    
    function initRequestsPage() {
        const user = AuthService.getCurrentUser();
        if (!user) return;

       
        loadUserRequests(user.id);

        
        $(document).on('click', '.cancel-request-btn', function() {
            const requestId = $(this).data('request-id');
            if (confirm('Are you sure you want to cancel this adoption request?')) {
                const result = RequestService.cancelRequest(requestId);

                if (result.success) {
                    alert('Request cancelled successfully');
                    loadUserRequests(user.id);
                } else {
                    alert('Failed to cancel request: ' + result.message);
                }
            }
        });
    }

   
    function loadUserRequests(userId) {
        const requests = RequestService.getRequestsByUser(userId);

        
        const userStats = {
            total: requests.length,
            pending: requests.filter(r => r.status === 'pending').length,
            approved: requests.filter(r => r.status === 'approved').length,
            rejected: requests.filter(r => r.status === 'rejected').length
        };

       
        $('.stat-card h3').eq(0).text(userStats.total);
        $('.stat-card h3').eq(1).text(userStats.pending);
        $('.stat-card h3').eq(2).text(userStats.approved);
        $('.stat-card h3').eq(3).text(userStats.rejected);

        
        if (requests.length === 0) {
            $('#empty-state').removeClass('d-none');
            $('.tab-content .card').remove();
        }
    }

    
    updateNavigation();
});

