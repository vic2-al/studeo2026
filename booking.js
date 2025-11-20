// Sistema de Agendamento
class BookingManager {
    constructor() {
        this.services = [
            {
                id: "1",
                name: "Manicure Simples",
                description: "Corte, lixa e esmaltação das unhas das mãos",
                price: 25.00,
                duration: 30
            },
            {
                id: "2",
                name: "Pedicure Completa",
                description: "Tratamento completo para os pés",
                price: 35.00,
                duration: 45
            },
            {
                id: "3",
                name: "Alongamento de Unhas",
                description: "Alongamento com gel ou acrílico",
                price: 80.00,
                duration: 90
            },
            {
                id: "4",
                name: "Design Especial",
                description: "Arte personalizada nas unhas",
                price: 50.00,
                duration: 60
            }
        ];

        this.availableTimeSlots = [
            '09:00', '10:00', '11:00', '14:00', '15:00', '16:00', '17:00', '18:00'
        ];

        this.init();
    }

    init() {
        console.log('BookingManager iniciado');
        this.loadServices();
        this.populateServiceSelect();
        this.generateTimeSlots();
        this.setupEventListeners();
    }

    setupEventListeners() {
        const dateInput = document.getElementById('date');
        const serviceSelect = document.getElementById('service');

        if (dateInput) {
            dateInput.addEventListener('change', () => {
                this.updateAvailableTimeSlots();
            });
        }

        if (serviceSelect) {
            serviceSelect.addEventListener('change', (e) => {
                this.updateSelectedService(e.target.value);
            });
        }
    }

    loadServices() {
        const servicesGrid = document.getElementById('servicesGrid');
        if (!servicesGrid) return;

        servicesGrid.innerHTML = '';

        this.services.forEach(service => {
            const serviceCard = document.createElement('div');
            serviceCard.className = 'service-card';
            serviceCard.dataset.serviceId = service.id;
            serviceCard.innerHTML = `
                <h3>${service.name}</h3>
                <p>${service.description}</p>
                <div class="price">R$ ${service.price.toFixed(2)}</div>
            `;
            serviceCard.addEventListener('click', () => {
                this.selectService(service.id);
            });
            servicesGrid.appendChild(serviceCard);
        });
    }

    populateServiceSelect() {
        const serviceSelect = document.getElementById('service');
        if (!serviceSelect) return;

        serviceSelect.innerHTML = '<option value="">Selecione um serviço</option>';

        this.services.forEach(service => {
            const option = document.createElement('option');
            option.value = service.id;
            option.textContent = `${service.name} - R$ ${service.price.toFixed(2)}`;
            serviceSelect.appendChild(option);
        });
    }

    selectService(serviceId) {
        document.querySelectorAll('.service-card').forEach(card => {
            card.classList.remove('selected');
        });

        const selectedCard = document.querySelector(`[data-service-id="${serviceId}"]`);
        if (selectedCard) {
            selectedCard.classList.add('selected');
        }

        document.getElementById('service').value = serviceId;
    }

    updateSelectedService(serviceId) {
        document.querySelectorAll('.service-card').forEach(card => {
            card.classList.remove('selected');
        });

        if (serviceId) {
            const selectedCard = document.querySelector(`[data-service-id="${serviceId}"]`);
            if (selectedCard) {
                selectedCard.classList.add('selected');
            }
        }
    }

    generateTimeSlots() {
        const timeSlotsContainer = document.getElementById('timeSlots');
        if (!timeSlotsContainer) return;

        timeSlotsContainer.innerHTML = '';

        this.availableTimeSlots.forEach(time => {
            const timeSlot = document.createElement('div');
            timeSlot.className = 'time-slot';
            timeSlot.textContent = time;
            timeSlot.dataset.time = time;
            timeSlot.addEventListener('click', () => {
                this.selectTimeSlot(timeSlot);
            });
            timeSlotsContainer.appendChild(timeSlot);
        });
    }

    selectTimeSlot(selectedSlot) {
        document.querySelectorAll('.time-slot').forEach(slot => {
            slot.classList.remove('selected');
        });

        selectedSlot.classList.add('selected');
    }

    updateAvailableTimeSlots() {
        const selectedDate = document.getElementById('date').value;
        const today = new Date().toISOString().split('T')[0];

        document.querySelectorAll('.time-slot').forEach(slot => {
            slot.classList.remove('unavailable');

            if (selectedDate === today) {
                const time = slot.dataset.time;
                const busySlots = ['10:00', '14:00', '16:00'];
                if (busySlots.includes(time)) {
                    slot.classList.add('unavailable');
                }
            }
        });
    }
}

// Inicializar BookingManager
console.log('🔧 Carregando BookingManager...');
const bookingManager = new BookingManager();