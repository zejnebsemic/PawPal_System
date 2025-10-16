// Pet Service - Manages pet data for the application
const PetService = (function() {
    'use strict';

    // Pet database
    const pets = [
        {
            id: 1,
            name: 'Max',
            type: 'Dog',
            breed: 'Golden Retriever',
            age: 3,
            size: 'Large',
            gender: 'Male',
            shelter: 'City Shelter',
            status: 'Available',
            image: 'https://images.unsplash.com/photo-1543466835-00a7907e9de1?w=800&h=600&fit=crop',
            thumbnail: 'https://images.unsplash.com/photo-1543466835-00a7907e9de1?w=400&h=300&fit=crop',
            description: 'Friendly golden retriever, great with kids and other pets.',
            fullDescription: 'Max is a friendly and energetic Golden Retriever who loves to play and go on long walks. He\'s great with children and other pets, making him the perfect family companion. Max is house-trained, knows basic commands, and is up to date on all vaccinations. He enjoys outdoor activities, playing fetch, and swimming. Max would thrive in a home with a yard where he can run and play.',
            characteristics: ['House Trained', 'Good with Kids', 'Good with Pets', 'Vaccinated', 'Spayed/Neutered'],
            medicalHistory: {
                vaccinations: 'All up to date',
                spayedNeutered: true,
                microchipped: true,
                healthStatus: 'Excellent',
                specialNeeds: 'None'
            }
        },
        {
            id: 2,
            name: 'Luna',
            type: 'Cat',
            breed: 'Persian',
            age: 2,
            size: 'Medium',
            gender: 'Female',
            shelter: 'Paws & Claws',
            status: 'Available',
            image: 'https://images.unsplash.com/photo-1514888286974-6c03e2ca1dba?w=800&h=600&fit=crop',
            thumbnail: 'https://images.unsplash.com/photo-1514888286974-6c03e2ca1dba?w=400&h=300&fit=crop',
            description: 'Gentle Persian cat, loves to cuddle and very affectionate.',
            fullDescription: 'Luna is a beautiful Persian cat with a gentle and affectionate nature. She loves to cuddle on the couch and will purr contentedly for hours. Luna is perfect for a calm household and enjoys a quiet environment. She\'s litter trained and up to date on all vaccinations.',
            characteristics: ['Litter Trained', 'Indoor Cat', 'Affectionate', 'Vaccinated', 'Spayed/Neutered'],
            medicalHistory: {
                vaccinations: 'All up to date',
                spayedNeutered: true,
                microchipped: true,
                healthStatus: 'Excellent',
                specialNeeds: 'None'
            }
        },
        {
            id: 3,
            name: 'Buddy',
            type: 'Dog',
            breed: 'Beagle',
            age: 4,
            size: 'Medium',
            gender: 'Male',
            shelter: 'Happy Tails',
            status: 'Available',
            image: 'https://images.unsplash.com/photo-1587300003388-59208cc962cb?w=800&h=600&fit=crop',
            thumbnail: 'https://images.unsplash.com/photo-1587300003388-59208cc962cb?w=400&h=300&fit=crop',
            description: 'Energetic beagle, loves outdoor activities and walks.',
            fullDescription: 'Buddy is an energetic Beagle who loves outdoor adventures and long walks. He\'s curious, friendly, and gets along well with other dogs. Buddy is house-trained and knows basic commands. He would be perfect for an active family who enjoys the outdoors.',
            characteristics: ['House Trained', 'Good with Dogs', 'Energetic', 'Vaccinated', 'Neutered'],
            medicalHistory: {
                vaccinations: 'All up to date',
                spayedNeutered: true,
                microchipped: true,
                healthStatus: 'Excellent',
                specialNeeds: 'None'
            }
        },
        {
            id: 4,
            name: 'Mittens',
            type: 'Cat',
            breed: 'Tabby',
            age: 1,
            size: 'Small',
            gender: 'Female',
            shelter: 'Rescue Haven',
            status: 'Available',
            image: 'https://images.unsplash.com/photo-1573865526739-10c1d3a1f0e3?w=800&h=600&fit=crop',
            thumbnail: 'https://images.unsplash.com/photo-1573865526739-10c1d3a1f0e3?w=400&h=300&fit=crop',
            description: 'Playful tabby cat, good with other pets and children.',
            fullDescription: 'Mittens is a playful young tabby cat who loves to chase toys and explore. She\'s friendly with children and other pets, making her a great addition to any family. Mittens is litter trained and very social.',
            characteristics: ['Litter Trained', 'Playful', 'Good with Kids', 'Vaccinated', 'Spayed'],
            medicalHistory: {
                vaccinations: 'All up to date',
                spayedNeutered: true,
                microchipped: true,
                healthStatus: 'Excellent',
                specialNeeds: 'None'
            }
        },
        {
            id: 5,
            name: 'Charlie',
            type: 'Dog',
            breed: 'Mixed Breed',
            age: 5,
            size: 'Medium',
            gender: 'Male',
            shelter: 'City Shelter',
            status: 'Available',
            image: 'https://images.unsplash.com/photo-1552053831-71594a27632d?w=800&h=600&fit=crop',
            thumbnail: 'https://images.unsplash.com/photo-1552053831-71594a27632d?w=400&h=300&fit=crop',
            description: 'Loyal companion, well-trained and house-broken.',
            fullDescription: 'Charlie is a loyal and well-trained companion. He\'s calm, obedient, and perfect for someone looking for a mature dog. Charlie is house-broken and knows many commands. He loves gentle walks and cuddling on the couch.',
            characteristics: ['House Trained', 'Calm', 'Well-Trained', 'Vaccinated', 'Neutered'],
            medicalHistory: {
                vaccinations: 'All up to date',
                spayedNeutered: true,
                microchipped: true,
                healthStatus: 'Excellent',
                specialNeeds: 'None'
            }
        },
        {
            id: 6,
            name: 'Whiskers',
            type: 'Cat',
            breed: 'Senior Cat',
            age: 6,
            size: 'Medium',
            gender: 'Male',
            shelter: 'Paws & Claws',
            status: 'Pending',
            image: 'https://images.unsplash.com/photo-1518791841217-8f162f1e1131?w=800&h=600&fit=crop',
            thumbnail: 'https://images.unsplash.com/photo-1518791841217-8f162f1e1131?w=400&h=300&fit=crop',
            description: 'Independent senior cat, low maintenance and calm.',
            fullDescription: 'Whiskers is an independent senior cat who enjoys a quiet life. He\'s low maintenance, calm, and perfect for someone looking for a relaxed companion. Whiskers is litter trained and enjoys lounging in sunny spots.',
            characteristics: ['Litter Trained', 'Independent', 'Calm', 'Vaccinated', 'Neutered'],
            medicalHistory: {
                vaccinations: 'All up to date',
                spayedNeutered: true,
                microchipped: true,
                healthStatus: 'Good',
                specialNeeds: 'Senior diet recommended'
            }
        },
        {
            id: 7,
            name: 'Rocky',
            type: 'Dog',
            breed: 'German Shepherd',
            age: 2,
            size: 'Large',
            gender: 'Male',
            shelter: 'Happy Tails',
            status: 'Available',
            image: 'https://images.unsplash.com/photo-1561037404-61cd46aa615b?w=800&h=600&fit=crop',
            thumbnail: 'https://images.unsplash.com/photo-1561037404-61cd46aa615b?w=400&h=300&fit=crop',
            description: 'Protective and loyal German Shepherd, needs active owner.',
            fullDescription: 'Rocky is a protective and loyal German Shepherd who needs an active and experienced owner. He\'s intelligent, trainable, and would make an excellent guard dog. Rocky needs regular exercise and mental stimulation.',
            characteristics: ['House Trained', 'Protective', 'Intelligent', 'Vaccinated', 'Neutered'],
            medicalHistory: {
                vaccinations: 'All up to date',
                spayedNeutered: true,
                microchipped: true,
                healthStatus: 'Excellent',
                specialNeeds: 'Needs experienced owner'
            }
        },
        {
            id: 8,
            name: 'Bella',
            type: 'Cat',
            breed: 'Siamese',
            age: 3,
            size: 'Small',
            gender: 'Female',
            shelter: 'Rescue Haven',
            status: 'Available',
            image: 'https://images.unsplash.com/photo-1571566882372-1598d88abd90?w=800&h=600&fit=crop',
            thumbnail: 'https://images.unsplash.com/photo-1571566882372-1598d88abd90?w=400&h=300&fit=crop',
            description: 'Sweet Siamese cat, talkative and loves attention.',
            fullDescription: 'Bella is a sweet and talkative Siamese cat who loves attention. She\'ll follow you around the house and chat with you throughout the day. Bella is perfect for someone who wants an interactive and affectionate companion.',
            characteristics: ['Litter Trained', 'Talkative', 'Affectionate', 'Vaccinated', 'Spayed'],
            medicalHistory: {
                vaccinations: 'All up to date',
                spayedNeutered: true,
                microchipped: true,
                healthStatus: 'Excellent',
                specialNeeds: 'None'
            }
        }
    ];

    // Shelter information
    const shelters = {
        'City Shelter': {
            id: 1,
            name: 'City Shelter',
            address: '123 Main Street, New York, NY 10001',
            phone: '(555) 123-4567',
            email: 'info@cityshelter.com',
            hours: 'Mon-Sat: 9AM-6PM, Sun: 10AM-4PM'
        },
        'Paws & Claws': {
            id: 2,
            name: 'Paws & Claws',
            address: '456 Oak Avenue, Brooklyn, NY 11201',
            phone: '(555) 234-5678',
            email: 'contact@pawsandclaws.com',
            hours: 'Mon-Sun: 8AM-7PM'
        },
        'Happy Tails': {
            id: 3,
            name: 'Happy Tails',
            address: '789 Pine Road, Queens, NY 11354',
            phone: '(555) 345-6789',
            email: 'info@happytails.com',
            hours: 'Tue-Sat: 10AM-5PM, Closed Sun-Mon'
        },
        'Rescue Haven': {
            id: 4,
            name: 'Rescue Haven',
            address: '321 Elm Street, Manhattan, NY 10002',
            phone: '(555) 456-7890',
            email: 'support@rescuehaven.com',
            hours: 'Mon-Sun: 9AM-8PM'
        }
    };

    return {
        // Get all pets
        getAllPets: function() {
            return pets;
        },

        // Get pet by ID
        getPetById: function(id) {
            return pets.find(pet => pet.id === parseInt(id));
        },

        // Get shelter information
        getShelterInfo: function(shelterName) {
            return shelters[shelterName];
        },

        // Get available pets only
        getAvailablePets: function() {
            return pets.filter(pet => pet.status === 'Available');
        },

        // Filter pets
        filterPets: function(filters) {
            return pets.filter(pet => {
                if (filters.type && pet.type.toLowerCase() !== filters.type.toLowerCase()) return false;
                if (filters.shelter && pet.shelter !== filters.shelter) return false;
                if (filters.size && pet.size.toLowerCase() !== filters.size.toLowerCase()) return false;
                if (filters.search) {
                    const search = filters.search.toLowerCase();
                    return pet.name.toLowerCase().includes(search) ||
                           pet.description.toLowerCase().includes(search) ||
                           pet.breed.toLowerCase().includes(search);
                }
                return true;
            });
        }
    };
})();
