// app.js - Configurações para InfinityFree
console.log('🚀 NailStudio System - InfinityFree Edition');

// Sistema de fallback para quando a API não estiver disponível
class FallbackSystem {
    constructor() {
        this.isOnline = true;
        this.checkConnection();
    }

    async checkConnection() {
        try {
            const response = await fetch('api/crud.php?table=servicos');
            this.isOnline = response.ok;

            if (this.isOnline) {
                console.log('✅ Conectado ao servidor');
                this.showOnlineStatus();
            } else {
                console.warn('⚠️ Servidor offline - usando modo local');
                this.showOfflineStatus();
            }
        } catch (error) {
            this.isOnline = false;
            console.warn('⚠️ Erro de conexão - usando modo local');
            this.showOfflineStatus();
        }
    }

    showOnlineStatus() {
        // Adiciona indicador visual de online
        if (!document.getElementById('online-status')) {
            const status = document.createElement('div');
            status.id = 'online-status';
            status.style.cssText = `
                position: fixed;
                top: 10px;
                right: 10px;
                background: #80ed99;
                color: #1a1a2e;
                padding: 5px 10px;
                border-radius: 15px;
                font-size: 12px;
                z-index: 1000;
                font-weight: bold;
            `;
            status.textContent = '✅ Online';
            document.body.appendChild(status);
        }
    }

    showOfflineStatus() {
        // Adiciona indicador visual de offline
        if (!document.getElementById('online-status')) {
            const status = document.createElement('div');
            status.id = 'online-status';
            status.style.cssText = `
                position: fixed;
                top: 10px;
                right: 10px;
                background: #ff6b6b;
                color: white;
                padding: 5px 10px;
                border-radius: 15px;
                font-size: 12px;
                z-index: 1000;
                font-weight: bold;
            `;
            status.textContent = '⚠️ Offline';
            document.body.appendChild(status);
        }
    }
}

// Inicializar quando a página carregar
document.addEventListener('DOMContentLoaded', function () {
    // Aguardar um pouco para o sistema principal carregar
    setTimeout(() => {
        const fallbackSystem = new FallbackSystem();

        // Adicionar mensagem de ajuda para administradores
        if (document.body.classList.contains('user-admin')) {
            console.log('👨‍💼 Modo administrador ativo');

            // Adicionar dica visual para administradores
            const adminTip = document.createElement('div');
            adminTip.style.cssText = `
                background: rgba(212, 170, 252, 0.1);
                border-left: 4px solid #d4aafc;
                padding: 10px 15px;
                margin: 10px 0;
                border-radius: 6px;
                font-size: 14px;
            `;
            adminTip.innerHTML = `
                <strong>💡 Dica do Administrador:</strong> 
                Use as seções de CRUD para gerenciar serviços, técnicas, clientes e agendamentos.
            `;

            const adminSection = document.getElementById('admin-section');
            if (adminSection) {
                const firstChild = adminSection.querySelector('.admin-controls');
                if (firstChild) {
                    firstChild.parentNode.insertBefore(adminTip, firstChild);
                }
            }
        }
    }, 2000);
});

// Função global para verificar status
function checkSystemStatus() {
    return {
        online: navigator.onLine,
        timestamp: new Date().toLocaleString('pt-BR')
    };
}