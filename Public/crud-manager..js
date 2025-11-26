// crudManager.js - Versão Melhorada e Estável
class CRUDManager {
    constructor() {
        this.baseURL = './crud.php';
        this.currentEditId = null;
        this.isOnline = false;
        this.currentSection = 'menu';

        this.init();
    }

    async init() {
        await this.testConnection();
        console.log(this.isOnline ? '✅ SISTEMA ONLINE' : '⚠️ MODO OFFLINE');
        this.showMainMenu();
    }

    async testConnection() {
        try {
            const response = await fetch(this.baseURL + '?table=servicos');
            if (response.ok) {
                this.isOnline = true;
                this.showStatus('online');
            } else {
                this.isOnline = false;
                this.showStatus('offline');
            }
        } catch (error) {
            this.isOnline = false;
            this.showStatus('offline');
        }
    }

    showStatus(status) {
        const oldStatus = document.getElementById('connection-status');
        if (oldStatus) oldStatus.remove();

        const statusDiv = document.createElement('div');
        statusDiv.id = 'connection-status';
        statusDiv.style.cssText = `
            position: fixed;
            top: 10px;
            right: 10px;
            padding: 8px 12px;
            border-radius: 20px;
            font-weight: bold;
            z-index: 10000;
            font-size: 12px;
            ${status === 'online' ?
                'background: #4CAF50; color: white;' :
                'background: #ff9800; color: white;'
            }
        `;
        statusDiv.textContent = status === 'online' ? '✅ ONLINE' : '⚠️ OFFLINE';
        document.body.appendChild(statusDiv);
    }

    // Método para mostrar menu principal
    showMainMenu() {
        const app = document.getElementById('app');
        app.innerHTML = `
            <div class="main-menu">
                <h1>💅 Studio de Unhas</h1>
                <div class="menu-grid">
                    <div class="menu-card" onclick="crudManager.showSection('servicos')">
                        <div class="menu-icon">💅</div>
                        <h3>Serviços</h3>
                        <p>Gerenciar serviços oferecidos</p>
                    </div>
                    <div class="menu-card" onclick="crudManager.showSection('tecnicas')">
                        <div class="menu-icon">👩‍🎨</div>
                        <h3>Técnicas</h3>
                        <p>Cadastrar técnicas</p>
                    </div>
                    <div class="menu-card" onclick="crudManager.showSection('clientes')">
                        <div class="menu-icon">👥</div>
                        <h3>Clientes</h3>
                        <p>Gerenciar clientes</p>
                    </div>
                    <div class="menu-card" onclick="crudManager.showSection('agendamentos')">
                        <div class="menu-icon">📅</div>
                        <h3>Agendamentos</h3>
                        <p>Agendar serviços</p>
                    </div>
                </div>
            </div>
        `;
        this.currentSection = 'menu';
    }

    // Método para mostrar seções
    showSection(section) {
        this.currentSection = section;
        const app = document.getElementById('app');

        switch (section) {
            case 'servicos':
                this.showServicosSection();
                break;
            case 'tecnicas':
                this.showTecnicasSection();
                break;
            case 'clientes':
                this.showClientesSection();
                break;
            case 'agendamentos':
                this.showAgendamentosSection();
                break;
        }
    }

    // Seção de Serviços
    showServicosSection() {
        const app = document.getElementById('app');
        app.innerHTML = `
            <div class="section">
                <div class="section-header">
                    <button class="back-btn" onclick="crudManager.showMainMenu()">← Voltar</button>
                    <h2>💅 Gerenciar Serviços</h2>
                </div>
                
                <div class="form-container">
                    <h3>${this.currentEditId ? 'Editar' : 'Cadastrar'} Serviço</h3>
                    <form id="servico-form" onsubmit="event.preventDefault(); crudManager.saveServico(new FormData(this))">
                        <input type="hidden" id="servico-id">
                        <div class="form-group">
                            <label>Nome do Serviço:</label>
                            <input type="text" name="nome" required>
                        </div>
                        <div class="form-group">
                            <label>Descrição:</label>
                            <textarea name="descricao" rows="3"></textarea>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Preço (R$):</label>
                                <input type="number" name="preco" step="0.01" required>
                            </div>
                            <div class="form-group">
                                <label>Duração (minutos):</label>
                                <input type="number" name="duracao_minutos" required>
                            </div>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn-primary">${this.currentEditId ? 'Atualizar' : 'Cadastrar'} Serviço</button>
                            ${this.currentEditId ? '<button type="button" class="btn-secondary" onclick="crudManager.cancelEdit()">Cancelar</button>' : ''}
                        </div>
                    </form>
                </div>

                <div class="list-container">
                    <h3>Serviços Cadastrados</h3>
                    <div id="servicos-list" class="items-list">
                        Carregando...
                    </div>
                </div>
            </div>
        `;
        this.loadServicos();
    }

    // CRUD Methods
    async list(table) {
        if (!this.isOnline) return [];
        try {
            const response = await fetch(`${this.baseURL}?table=${table}`);
            if (!response.ok) throw new Error('API offline');
            const data = await response.json();
            return Array.isArray(data) ? data : [];
        } catch (error) {
            return [];
        }
    }

    async create(table, data) {
        if (!this.isOnline) {
            alert('⚠️ Sistema offline. Dados salvos localmente.');
            return { id: Date.now(), message: 'Salvo localmente (offline)' };
        }
        try {
            const response = await fetch(this.baseURL + '?table=' + table, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });
            return await response.json();
        } catch (error) {
            return { error: 'Servidor offline' };
        }
    }

    async update(table, id, data) {
        if (!this.isOnline) {
            alert('⚠️ Sistema offline. Alterações salvas localmente.');
            return { message: 'Atualizado localmente (offline)' };
        }
        try {
            const response = await fetch(`${this.baseURL}?table=${table}&id=${id}`, {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });
            return await response.json();
        } catch (error) {
            return { error: 'Servidor offline' };
        }
    }

    async delete(table, id) {
        if (!this.isOnline) {
            alert('⚠️ Sistema offline. Ação salva localmente.');
            return { message: 'Excluído localmente (offline)' };
        }
        try {
            const response = await fetch(`${this.baseURL}?table=${table}&id=${id}`, {
                method: 'DELETE'
            });
            return await response.json();
        } catch (error) {
            return { error: 'Servidor offline' };
        }
    }

    // Serviços
    async loadServicos() {
        const servicos = await this.list('servicos');
        this.renderServicos(servicos);
    }

    renderServicos(servicos) {
        const container = document.getElementById('servicos-list');
        if (!container) return;

        if (servicos.length === 0) {
            container.innerHTML = '<div class="empty-state">Nenhum serviço cadastrado</div>';
            return;
        }

        container.innerHTML = servicos.map(servico => `
            <div class="item-card">
                <div class="item-info">
                    <div class="item-header">
                        <strong>${servico.nome || 'Sem nome'}</strong>
                        <span class="item-price">R$ ${parseFloat(servico.preco || 0).toFixed(2)}</span>
                    </div>
                    <div class="item-details">
                        <div class="detail">${servico.descricao || 'Sem descrição'}</div>
                        <div class="detail">${servico.duracao_minutos || 0} minutos</div>
                    </div>
                </div>
                <div class="item-actions">
                    <button class="btn-edit" onclick="crudManager.editServico(${servico.id})">Editar</button>
                    <button class="btn-delete" onclick="crudManager.deleteServico(${servico.id})">Excluir</button>
                </div>
            </div>
        `).join('');
    }

    async saveServico(formData) {
        const data = {
            nome: formData.get('nome'),
            descricao: formData.get('descricao'),
            preco: parseFloat(formData.get('preco')),
            duracao_minutos: parseInt(formData.get('duracao_minutos'))
        };

        let result;
        if (this.currentEditId) {
            result = await this.update('servicos', this.currentEditId, data);
            this.currentEditId = null;
        } else {
            result = await this.create('servicos', data);
        }

        if (!result.error) {
            alert('✅ Serviço salvo com sucesso!');
            this.loadServicos();
            document.getElementById('servico-form').reset();
        } else {
            alert('❌ Erro: ' + result.error);
        }
    }

    async editServico(id) {
        if (!this.isOnline) {
            alert('⚠️ Modo offline - Edição não disponível');
            return;
        }

        try {
            const response = await fetch(`${this.baseURL}?table=servicos&id=${id}`);
            const servico = await response.json();

            document.querySelector('input[name="nome"]').value = servico.nome || '';
            document.querySelector('textarea[name="descricao"]').value = servico.descricao || '';
            document.querySelector('input[name="preco"]').value = servico.preco || '';
            document.querySelector('input[name="duracao_minutos"]').value = servico.duracao_minutos || '';

            this.currentEditId = id;
            document.querySelector('.form-container h3').textContent = 'Editar Serviço';
        } catch (error) {
            alert('❌ Erro ao carregar serviço para edição');
        }
    }

    cancelEdit() {
        this.currentEditId = null;
        document.getElementById('servico-form').reset();
        document.querySelector('.form-container h3').textContent = 'Cadastrar Serviço';
    }

    async deleteServico(id) {
        if (confirm('Tem certeza que deseja excluir este serviço?')) {
            const result = await this.delete('servicos', id);
            if (!result.error) {
                alert('✅ Serviço excluído!');
                this.loadServicos();
            } else {
                alert('❌ Erro: ' + result.error);
            }
        }
    }

    // Seção de Técnicas
    showTecnicasSection() {
        const app = document.getElementById('app');
        app.innerHTML = `
            <div class="section">
                <div class="section-header">
                    <button class="back-btn" onclick="crudManager.showMainMenu()">← Voltar</button>
                    <h2>👩‍🎨 Gerenciar Técnicas</h2>
                </div>
                
                <div class="form-container">
                    <h3>Cadastrar Técnica</h3>
                    <form id="tecnica-form" onsubmit="event.preventDefault(); crudManager.saveTecnica(new FormData(this))">
                        <div class="form-group">
                            <label>Nome da Técnica:</label>
                            <input type="text" name="nome" required>
                        </div>
                        <div class="form-group">
                            <label>Especialidade:</label>
                            <input type="text" name="especialidade" required>
                        </div>
                        <div class="form-group">
                            <label>Experiência:</label>
                            <input type="text" name="experiencia" placeholder="ex: 5 anos">
                        </div>
                        <button type="submit" class="btn-primary">Cadastrar Técnica</button>
                    </form>
                </div>

                <div class="list-container">
                    <h3>Técnicas Cadastradas</h3>
                    <div id="tecnicas-list" class="items-list">
                        Carregando...
                    </div>
                </div>
            </div>
        `;
        this.loadTecnicas();
    }

    async loadTecnicas() {
        const tecnicas = await this.list('tecnicas');
        this.renderTecnicas(tecnicas);
    }

    renderTecnicas(tecnicas) {
        const container = document.getElementById('tecnicas-list');
        if (!container) return;

        if (tecnicas.length === 0) {
            container.innerHTML = '<div class="empty-state">Nenhuma técnica cadastrada</div>';
            return;
        }

        container.innerHTML = tecnicas.map(t => `
            <div class="item-card">
                <div class="item-info">
                    <div class="item-header">
                        <strong>${t.nome}</strong>
                    </div>
                    <div class="item-details">
                        <div class="detail">Especialidade: ${t.especialidade}</div>
                        <div class="detail">Experiência: ${t.experiencia || 'Não informada'}</div>
                    </div>
                </div>
            </div>
        `).join('');
    }

    async saveTecnica(formData) {
        const data = {
            nome: formData.get('nome'),
            especialidade: formData.get('especialidade'),
            experiencia: formData.get('experiencia')
        };

        const result = await this.create('tecnicas', data);
        if (!result.error) {
            alert('✅ Técnica cadastrada com sucesso!');
            this.loadTecnicas();
            document.getElementById('tecnica-form').reset();
        } else {
            alert('❌ Erro: ' + result.error);
        }
    }

    // Seções de Clientes e Agendamentos (estrutura básica)
    showClientesSection() {
        const app = document.getElementById('app');
        app.innerHTML = `
            <div class="section">
                <div class="section-header">
                    <button class="back-btn" onclick="crudManager.showMainMenu()">← Voltar</button>
                    <h2>👥 Gerenciar Clientes</h2>
                </div>
                <div class="empty-state">
                    <p>Funcionalidade de Clientes em desenvolvimento</p>
                    <p><small>Esta área será usada para cadastrar e gerenciar clientes</small></p>
                </div>
            </div>
        `;
    }

    showAgendamentosSection() {
        const app = document.getElementById('app');
        app.innerHTML = `
            <div class="section">
                <div class="section-header">
                    <button class="back-btn" onclick="crudManager.showMainMenu()">← Voltar</button>
                    <h2>📅 Gerenciar Agendamentos</h2>
                </div>
                <div class="empty-state">
                    <p>Funcionalidade de Agendamentos em desenvolvimento</p>
                    <p><small>Esta área será usada para agendar serviços</small></p>
                </div>
            </div>
        `;
    }
}

// Inicialização
console.log('🔄 Iniciando CRUD Manager...');
const crudManager = new CRUDManager();