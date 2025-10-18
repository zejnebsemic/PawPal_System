
const RequestService = (function() {
    'use strict';

   
    let adoptionRequests = [
        {
            id: 1,
            requestId: '#1247',
            user: {
                id: 2,
                name: 'Regular User',
                email: 'user@example.com',
                initials: 'RU'
            },
            pet: {
                id: 1,
                name: 'Max',
                breed: 'Golden Retriever',
                age: 3,
                type: 'Dog',
                image: '/fronte'
            },
            shelter: 'City Shelter',
            dateApplied: 'Jan 15, 2024',
            status: 'pending',
            notes: ''
        },
        {
            id: 2,
            requestId: '#1246',
            user: {
                id: 3,
                name: 'Sarah Miller',
                email: 'sarah@email.com',
                initials: 'SM'
            },
            pet: {
                id: 2,
                name: 'Luna',
                breed: 'Persian Cat',
                age: 2,
                type: 'Cat',
                image: 'https://images.unsplash.com/photo-1514888286974-6c03e2ca1dba?w=200&h=150&fit=crop'
            },
            shelter: 'Paws & Claws',
            dateApplied: 'Jan 18, 2024',
            status: 'pending',
            notes: ''
        },
        {
            id: 3,
            requestId: '#1245',
            user: {
                id: 4,
                name: 'Mike Wilson',
                email: 'mike@email.com',
                initials: 'MW'
            },
            pet: {
                id: 3,
                name: 'Buddy',
                breed: 'Beagle',
                age: 4,
                type: 'Dog',
                image: 'https://images.unsplash.com/photo-1587300003388-59208cc962cb?w=200&h=150&fit=crop'
            },
            shelter: 'Happy Tails',
            dateApplied: 'Jan 16, 2024',
            status: 'approved',
            notes: 'Approved - excellent match'
        },
        {
            id: 4,
            requestId: '#1244',
            user: {
                id: 5,
                name: 'Emily Johnson',
                email: 'emily@email.com',
                initials: 'EJ'
            },
            pet: {
                id: 4,
                name: 'Mittens',
                breed: 'Tabby Cat',
                age: 1,
                type: 'Cat',
                image: './frontend/assets/images/mittens.jpeg'
            },
            shelter: 'Rescue Haven',
            dateApplied: 'Jan 14, 2024',
            status: 'rejected',
            notes: 'Applicant does not have suitable living space'
        },
        {
            id: 5,
            requestId: '#1243',
            user: {
                id: 6,
                name: 'David Brown',
                email: 'david@email.com',
                initials: 'DB'
            },
            pet: {
                id: 5,
                name: 'Charlie',
                breed: 'Mixed Breed',
                age: 5,
                type: 'Dog',
                image: 'https://images.unsplash.com/photo-1552053831-71594a27632d?w=200&h=150&fit=crop'
            },
            shelter: 'City Shelter',
            dateApplied: 'Jan 12, 2024',
            status: 'approved',
            notes: 'Great match - experienced dog owner'
        },
        {
            id: 6,
            requestId: '#1242',
            user: {
                id: 2,
                name: 'Regular User',
                email: 'user@example.com',
                initials: 'RU'
            },
            pet: {
                id: 7,
                name: 'Rocky',
                breed: 'German Shepherd',
                age: 2,
                type: 'Dog',
                image: 'https://images.unsplash.com/photo-1561037404-61cd46aa615b?w=200&h=150&fit=crop'
            },
            shelter: 'Happy Tails',
            dateApplied: 'Jan 10, 2024',
            status: 'pending',
            notes: ''
        }
    ];

    return {
       
        getAllRequests: function() {
            return adoptionRequests;
        },

       
        getRequestsByUser: function(userId) {
            return adoptionRequests.filter(req => req.user.id === userId);
        },

        
        getRequestsByStatus: function(status) {
            return adoptionRequests.filter(req => req.status === status);
        },

        
        getRequestById: function(id) {
            return adoptionRequests.find(req => req.id === parseInt(id));
        },

        
        createRequest: function(userId, petId) {
            const user = AuthService.getCurrentUser();
            const pet = PetService.getPetById(petId);

            if (!user || !pet) {
                return { success: false, message: 'Invalid user or pet' };
            }

            const newRequest = {
                id: adoptionRequests.length + 1,
                requestId: '#' + (1247 + adoptionRequests.length),
                user: {
                    id: user.id,
                    name: user.name,
                    email: user.email,
                    initials: user.name.split(' ').map(n => n[0]).join('')
                },
                pet: {
                    id: pet.id,
                    name: pet.name,
                    breed: pet.breed,
                    age: pet.age,
                    type: pet.type,
                    image: pet.thumbnail
                },
                shelter: pet.shelter,
                dateApplied: new Date().toLocaleDateString('en-US', {
                    month: 'short',
                    day: 'numeric',
                    year: 'numeric'
                }),
                status: 'pending',
                notes: ''
            };

            adoptionRequests.push(newRequest);
            return { success: true, request: newRequest };
        },

       
        updateRequestStatus: function(requestId, status, notes) {
            const request = this.getRequestById(requestId);

            if (!request) {
                return { success: false, message: 'Request not found' };
            }

            request.status = status;
            if (notes) {
                request.notes = notes;
            }

            return { success: true, request: request };
        },

      
        cancelRequest: function(requestId) {
            const index = adoptionRequests.findIndex(req => req.id === parseInt(requestId));

            if (index === -1) {
                return { success: false, message: 'Request not found' };
            }

            adoptionRequests.splice(index, 1);
            return { success: true };
        },

       
        getStatistics: function() {
            return {
                total: adoptionRequests.length,
                pending: adoptionRequests.filter(req => req.status === 'pending').length,
                approved: adoptionRequests.filter(req => req.status === 'approved').length,
                rejected: adoptionRequests.filter(req => req.status === 'rejected').length
            };
        }
    };
})();
