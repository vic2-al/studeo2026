// authManager.js
class AuthManager {
    constructor() {
        this.currentUser = null;
        this.init();
    }

    init() {
        this.loadCurrentUser();
    }

    loadCurrentUser() {
        const loggedUser = localStorage.getItem('loggedUser');
        if (loggedUser) {
            this.currentUser = JSON.parse(loggedUser);
        }
    }

    getCurrentUser() {
        return this.currentUser;
    }

    addAppointment(appointment) {
        if (!this.currentUser) return false;

        if (!this.currentUser.appointments) {
            this.currentUser.appointments = [];
        }

        this.currentUser.appointments.push(appointment);
        this.saveUserData();
        return true;
    }

    cancelAppointment(appointmentId) {
        if (!this.currentUser || !this.currentUser.appointments) return false;

        this.currentUser.appointments = this.currentUser.appointments.filter(
            app => app.id !== appointmentId
        );
        this.saveUserData();
        return true;
    }

    saveUserData() {
        // Atualiza o usuário logado
        localStorage.setItem('loggedUser', JSON.stringify(this.currentUser));

        // Atualiza na lista de usuários
        const users = JSON.parse(localStorage.getItem('users')) || [];
        const userIndex = users.findIndex(u => u.email === this.currentUser.email);

        if (userIndex !== -1) {
            users[userIndex] = this.currentUser;
            localStorage.setItem('users', JSON.stringify(users));
        }
    }
}

// Inicializar AuthManager
const authManager = new AuthManager();