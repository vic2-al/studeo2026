// Sistema de Gerenciamento de Usuários e Agendamentos
class NailStudioSystem {
    constructor() {
        this.users = this.loadFromStorage('users') || [];
        this.appointments = this.loadFromStorage('appointments') || [];
        this.currentUser = this.loadFromStorage('currentUser') || null;
        this.isAdmin = false;

        this.initializeSystem();
    }

    initializeSystem() {
        // Criar usuário admin padrão se não existir
        if (!this.users.find(user => user.role === 'admin')) {
            this.users.push({
                id: this.generateId(),
                name: 'Administrador',
                email: 'admin@nailstudio.com',
                password: 'admin123',
                phone: '(11) 99999-9999',
                role: 'admin',
                createdAt: new Date().toISOString()
            });
            this.saveToStorage('users', this.users);
        }

        // Verificar se há usuário logado
        if (this.currentUser) {
            this.showMainSystem();
        } else {
            this.showAuthSection('login-section');
        }

        this.setupEventListeners();
        this.setupNavigation();
    }

    // Gerenciamento de Autenticação
    login(email, password) {
        const user = this.users.find(u => u.email === email && u.password === password);
        if (user) {
            this.currentUser = user;
            this.isAdmin = user.role === 'admin';
            this.saveToStorage('currentUser', user);
            this.showMainSystem();
            return true;
        }
        return false;
    }

    logout() {
        this.currentUser = null;
        this.isAdmin = false;
        this.saveToStorage('currentUser', null);
        this.showAuthSection('login-section');
    }

    register(userData) {
        if (this.users.find(u => u.email === userData.email)) {
            throw new Error('Email já cadastrado');
        }

        if (userData.password !== userData.confirmPassword) {
            throw new Error('Senhas não coincidem');
        }

        const newUser = {
            id: this.generateId(),
            name: userData.name,
            email: userData.email,
            phone: userData.phone,
            password: userData.password,
            role: 'client',
            createdAt: new Date().toISOString()
        };

        this.users.push(newUser);
        this.saveToStorage('users', this.users);
        return newUser;
    }

    // Gerenciamento de Agendamentos
    createAppointment(appointmentData) {
        const appointment = {
            id: this.generateId(),
            serviceName: appointmentData.serviceName,
            price: appointmentData.price,
            technician: appointmentData.technician,
            date: appointmentData.date,
            time: appointmentData.time,
            observations: appointmentData.observations,
            userId: this.currentUser.id,
            userName: this.currentUser.name,
            userEmail: this.currentUser.email,
            status: 'pendente',
            createdAt: new Date().toISOString()
        };

        this.appointments.push(appointment);
        this.saveToStorage('appointments', this.appointments);
        return appointment;
    }

    cancelAppointment(appointmentId) {
        const appointment = this.appointments.find(a => a.id === appointmentId);
        if (appointment) {
            appointment.status = 'cancelado';
            this.saveToStorage('appointments', this.appointments);
        }
    }

    confirmAppointment(appointmentId) {
        const appointment = this.appointments.find(a => a.id === appointmentId);
        if (appointment) {
            appointment.status = 'confirmado';
            this.saveToStorage('appointments', this.appointments);
        }
    }

    getUserAppointments() {
        return this.appointments.filter(a => a.userId === this.currentUser.id);
    }

    getAllAppointments() {
        return this.appointments.sort((a, b) => new Date(b.createdAt) - new Date(a.createdAt));
    }

    // Utilitários
    generateId() {
        return Date.now().toString(36) + Math.random().toString(36).substr(2);
    }

    saveToStorage(key, data) {
        localStorage.setItem(key, JSON.stringify(data));
    }

    loadFromStorage(key) {
        const data = localStorage.getItem(key);
        return data ? JSON.parse(data) : null;
    }

    // Interface
    showMainSystem() {
        this.hideAllSections();
        document.getElementById('main-system').style.display = 'block';
        this.updateUserInterface();

        if (this.isAdmin) {
            document.body.classList.add('user-admin');
            document.querySelectorAll('.admin-only').forEach(el => {
                el.style.display = 'block';
            });
            this.loadAdminPanel();
        } else {
            this.loadUserAppointments();
        }
    }

    showAuthSection(sectionId) {
        this.hideAllSections();
        document.getElementById(sectionId).classList.add('active-section');
    }

    hideAllSections() {
        // Esconder todas as seções de autenticação
        document.querySelectorAll('.auth-section').forEach(section => {
            section.classList.remove('active-section');
        });
        // Esconder sistema principal
        document.getElementById('main-system').style.display = 'none';
    }

    updateUserInterface() {
        document.getElementById('user-display-name').textContent = this.currentUser.name;
        document.getElementById('user-avatar').textContent = this.currentUser.name.charAt(0).toUpperCase();
        document.getElementById('user-role-display').textContent = this.isAdmin ? 'Administrador' : 'Cliente';
    }

    loadUserAppointments() {
        const appointments = this.getUserAppointments();
        const container = document.getElementById('user-appointments-list');

        if (appointments.length === 0) {
            container.innerHTML = `
                <div class="empty-state">
                    <p>Nenhum agendamento encontrado</p>
                    <p><small>Faça seu primeiro agendamento!</small></p>
                </div>
            `;
            return;
        }

        container.innerHTML = appointments.map(appointment => `
            <div class="appointment-item">
                <div class="appointment-info">
                    <div class="appointment-header">
                        <strong>${appointment.serviceName}</strong>
                        <span class="appointment-price">R$ ${appointment.price}</span>
                    </div>
                    <div class="appointment-details">
                        <div class="detail-item date">${this.formatDate(appointment.date)}</div>
                        <div class="detail-item time">${appointment.time}</div>
                        <div class="detail-item nail-tech">${appointment.technician}</div>
                    </div>
                    ${appointment.observations ? `
                    <div class="appointment-observations">
                        <strong>Observações:</strong> ${appointment.observations}
                    </div>
                    ` : ''}
                </div>
                <div class="status-container">
                    <span class="status ${appointment.status}">${this.getStatusText(appointment.status)}</span>
                    ${appointment.status === 'pendente' ?
                `<button class="cancel-btn" onclick="system.cancelAppointment('${appointment.id}'); system.loadUserAppointments()">Cancelar</button>` :
                ''}
                </div>
            </div>
        `).join('');
    }

    loadAdminPanel() {
        const appointments = this.getAllAppointments();
        const container = document.getElementById('admin-appointments-list');

        // Atualizar estatísticas
        const today = new Date().toDateString();
        const todayAppointments = appointments.filter(a => new Date(a.date).toDateString() === today);

        document.getElementById('total-agendamentos').textContent = appointments.length;
        document.getElementById('agendamentos-hoje').textContent = todayAppointments.length;
        document.getElementById('agendamentos-pendentes').textContent = appointments.filter(a => a.status === 'pendente').length;

        if (appointments.length === 0) {
            container.innerHTML = `
                <div class="empty-state">
                    <p>Nenhum agendamento encontrado</p>
                </div>
            `;
        } else {
            container.innerHTML = appointments.map(appointment => `
                <div class="appointment-item">
                    <div class="appointment-info">
                        <div class="appointment-header">
                            <strong>${appointment.serviceName}</strong>
                            <span class="appointment-price">R$ ${appointment.price}</span>
                        </div>
                        <div class="appointment-details">
                            <div class="detail-item date">${this.formatDate(appointment.date)}</div>
                            <div class="detail-item time">${appointment.time}</div>
                            <div class="detail-item nail-tech">${appointment.technician}</div>
                        </div>
                        <div class="appointment-client">
                            <strong>Cliente:</strong> ${appointment.userName} (${appointment.userEmail})
                        </div>
                        ${appointment.observations ? `
                        <div class="appointment-observations">
                            <strong>Observações:</strong> ${appointment.observations}
                        </div>
                        ` : ''}
                    </div>
                    <div class="status-container">
                        <span class="status ${appointment.status}">${this.getStatusText(appointment.status)}</span>
                        ${appointment.status === 'pendente' ?
                    `<button class="confirm-btn" onclick="system.confirmAppointment('${appointment.id}'); system.loadAdminPanel()">Confirmar</button>` :
                    ''}
                        <button class="cancel-btn" onclick="system.cancelAppointment('${appointment.id}'); system.loadAdminPanel()">Cancelar</button>
                    </div>
                </div>
            `).join('');
        }
    }

    // Utilitários de Interface
    showContentSection(sectionId) {
        document.querySelectorAll('.content-section').forEach(section => {
            section.classList.remove('active-section');
        });
        document.getElementById(sectionId).classList.add('active-section');

        // Atualizar navegação
        document.querySelectorAll('.nav-link').forEach(link => {
            link.classList.remove('active');
        });
        const correspondingNav = document.querySelector(`[data-section="${sectionId}"]`);
        if (correspondingNav) {
            correspondingNav.classList.add('active');
        }
    }

    formatDate(dateString) {
        return new Date(dateString).toLocaleDateString('pt-BR');
    }

    getStatusText(status) {
        const statusMap = {
            'pendente': 'Pendente',
            'confirmado': 'Confirmado',
            'cancelado': 'Cancelado'
        };
        return statusMap[status] || status;
    }

    setupEventListeners() {
        // Login
        const loginForm = document.getElementById('login-form');
        if (loginForm) {
            loginForm.addEventListener('submit', (e) => {
                e.preventDefault();
                const email = document.getElementById('login-email').value;
                const password = document.getElementById('login-password').value;

                if (this.login(email, password)) {
                    alert('Login realizado com sucesso!');
                } else {
                    alert('Email ou senha incorretos!');
                }
            });
        }

        // Cadastro
        const registerForm = document.getElementById('register-form');
        if (registerForm) {
            registerForm.addEventListener('submit', (e) => {
                e.preventDefault();
                const formData = {
                    name: document.getElementById('register-name').value,
                    phone: document.getElementById('register-phone').value,
                    email: document.getElementById('register-email').value,
                    password: document.getElementById('register-password').value,
                    confirmPassword: document.getElementById('register-confirm-password').value
                };

                try {
                    this.register(formData);
                    alert('Cadastro realizado com sucesso! Faça login para continuar.');
                    this.showAuthSection('login-section');
                } catch (error) {
                    alert(error.message);
                }
            });
        }

        // Agendamento
        const bookingForm = document.getElementById('booking-form');
        if (bookingForm) {
            bookingForm.addEventListener('submit', (e) => {
                e.preventDefault();

                const serviceSelect = document.getElementById('servico');
                const selectedOption = serviceSelect.options[serviceSelect.selectedIndex];
                const price = selectedOption.getAttribute('data-price');

                const appointmentData = {
                    serviceName: selectedOption.text.split(' - ')[0],
                    price: price,
                    technician: document.getElementById('tecnica').value,
                    date: document.getElementById('data').value,
                    time: document.getElementById('horario').value,
                    observations: document.getElementById('observacoes').value
                };

                // Validações
                if (!appointmentData.serviceName || !appointmentData.technician ||
                    !appointmentData.date || !appointmentData.time) {
                    alert('Por favor, preencha todos os campos obrigatórios!');
                    return;
                }

                this.createAppointment(appointmentData);
                alert('Agendamento realizado com sucesso!');
                this.showContentSection('agendamentos-section');
                this.loadUserAppointments();
                bookingForm.reset();
                updateSummary();
            });
        }

        // Configurar data mínima no formulário
        const dataInput = document.getElementById('data');
        if (dataInput) {
            const today = new Date().toISOString().split('T')[0];
            dataInput.min = today;
        }

        // Atualizar resumo em tempo real
        document.querySelectorAll('#servico, #tecnica, #data, #horario').forEach(input => {
            if (input) {
                input.addEventListener('change', updateSummary);
            }
        });
    }

    setupNavigation() {
        // Navegação principal
        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const sectionId = link.getAttribute('data-section');
                this.showContentSection(sectionId);
            });
        });

        // Links de autenticação
        const authLinks = document.querySelectorAll('a[onclick^="showSection"]');
        authLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const onclickContent = link.getAttribute('onclick');
                const sectionId = onclickContent.match(/'([^']+)'/)[1];
                this.showAuthSection(sectionId);
            });
        });
    }
}

// Função global para atualizar resumo
function updateSummary() {
    const servicoSelect = document.getElementById('servico');
    const tecnicaSelect = document.getElementById('tecnica');
    const dataInput = document.getElementById('data');
    const horarioSelect = document.getElementById('horario');

    if (servicoSelect && tecnicaSelect && dataInput && horarioSelect) {
        document.getElementById('servico-selecionado').textContent =
            servicoSelect.value ? servicoSelect.options[servicoSelect.selectedIndex].text.split(' - ')[0] : '-';

        document.getElementById('tecnica-selecionada').textContent =
            tecnicaSelect.value ? tecnicaSelect.options[tecnicaSelect.selectedIndex].text : '-';

        const dataHora = dataInput.value && horarioSelect.value ?
            `${new Date(dataInput.value).toLocaleDateString('pt-BR')} às ${horarioSelect.value}` : '-';
        document.getElementById('datahora-selecionada').textContent = dataHora;

        // Atualizar total
        if (servicoSelect.value) {
            const price = servicoSelect.options[servicoSelect.selectedIndex].getAttribute('data-price');
            document.getElementById('total-selecionado').textContent = `R$ ${parseFloat(price).toFixed(2)}`;
        } else {
            document.getElementById('total-selecionado').textContent = 'R$ 0,00';
        }
    }
}

// Funções globais para uso no HTML
function showSection(sectionId) {
    system.showAuthSection(sectionId);
}

function showContentSection(sectionId) {
    system.showContentSection(sectionId);
}

function logout() {
    system.logout();
}

// Inicializar sistema quando o DOM estiver carregado
document.addEventListener('DOMContentLoaded', function () {
    window.system = new NailStudioSystem();
});