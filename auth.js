// === auth.js ===
// Gerencia login e cadastro

document.addEventListener("DOMContentLoaded", () => {
    const loginForm = document.getElementById("loginForm");
    const registerForm = document.getElementById("registerForm");
    const loginScreen = document.getElementById("loginScreen");
    const registerScreen = document.getElementById("registerScreen");
    const mainScreen = document.getElementById("mainScreen");
    const userName = document.getElementById("userName");

    // Usuário padrão
    const defaultUser = {
        email: "admin@nailstudio.com",
        password: "admin123",
        name: "admin"
    };

    // ✅ LOGIN
    if (loginForm) {
        loginForm.addEventListener("submit", (e) => {
            e.preventDefault();

            const email = document.getElementById("email").value.trim();
            const password = document.getElementById("password").value.trim();

            // Busca usuários salvos
            const users = JSON.parse(localStorage.getItem("users")) || [];

            // Verifica se o usuário é o padrão
            if (email === defaultUser.email && password === defaultUser.password) {
                localStorage.setItem("loggedUser", JSON.stringify(defaultUser));
                showMainScreen(defaultUser.name);
                return;
            }

            // Verifica usuários cadastrados
            const user = users.find(u => u.email === email && u.password === password);

            if (user) {
                localStorage.setItem("loggedUser", JSON.stringify(user));
                showMainScreen(user.name);
            } else {
                alert("❌ E-mail ou senha incorretos!");
            }
        });
    }

    // ✅ CADASTRO
    if (registerForm) {
        registerForm.addEventListener("submit", (e) => {
            e.preventDefault();

            const name = document.getElementById("regName").value.trim();
            const email = document.getElementById("regEmail").value.trim();
            const phone = document.getElementById("regPhone").value.trim();
            const password = document.getElementById("regPassword").value.trim();
            const confirmPassword = document.getElementById("regConfirmPassword").value.trim();

            if (password !== confirmPassword) {
                alert("⚠️ As senhas não coincidem!");
                return;
            }

            const users = JSON.parse(localStorage.getItem("users")) || [];

            // Evita duplicidade
            if (users.some(u => u.email === email)) {
                alert("⚠️ Este e-mail já está cadastrado!");
                return;
            }

            const newUser = { name, email, phone, password };
            users.push(newUser);
            localStorage.setItem("users", JSON.stringify(users));

            alert("✅ Conta criada com sucesso!");
            showLogin();
        });
    }

    // ✅ Alternar entre telas
    const registerLink = document.getElementById("registerLink");
    const loginLink = document.getElementById("loginLink");
    const logoutBtn = document.getElementById("logoutBtn");

    if (registerLink) registerLink.addEventListener("click", showRegister);
    if (loginLink) loginLink.addEventListener("click", showLogin);
    if (logoutBtn) logoutBtn.addEventListener("click", logout);

    // === Funções ===
    function showMainScreen(name) {
        loginScreen.style.display = "none";
        registerScreen.style.display = "none";
        mainScreen.style.display = "block";
        userName.textContent = name;
    }

    function showRegister() {
        loginScreen.style.display = "none";
        registerScreen.style.display = "block";
    }

    function showLogin() {
        registerScreen.style.display = "none";
        loginScreen.style.display = "block";
    }

    function logout() {
        localStorage.removeItem("loggedUser");
        showLogin();
    }

    // ✅ Se já estiver logado, pula direto para tela principal
    const loggedUser = JSON.parse(localStorage.getItem("loggedUser"));
    if (loggedUser) {
        showMainScreen(loggedUser.name);
    }
});
