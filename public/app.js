// URL base para los endpoints RESTful
const API_BASE = '/users';

document.addEventListener('DOMContentLoaded', loadUsers);

function loadUsers() {
    fetch(API_BASE)
        .then(res => res.json())
        .then(users => {
            const tbody = document.querySelector('#users-table tbody');
            tbody.innerHTML = '';
            users.forEach(user => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${user.id}</td>
                    <td>${user.name}</td>
                    <td>${user.email}</td>
                    <td>
                        <button class="action-btn edit-btn" onclick="editUser('${user.id}')">Editar</button>
                        <button class="action-btn delete-btn" onclick="deleteUser('${user.id}')">Eliminar</button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        });
}

function createUser() {
    const name = document.getElementById('name').value;
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    fetch(API_BASE, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ name, email, password })
    })
    .then(res => {
        if (!res.ok) throw new Error('Error al crear usuario');
        return res.json();
    })
    .then(() => {
        loadUsers();
        document.getElementById('name').value = '';
        document.getElementById('email').value = '';
        document.getElementById('password').value = '';
    })
    .catch(err => alert(err.message));
}

function deleteUser(id) {
    if (!confirm('¿Seguro que deseas eliminar este usuario?')) return;
    fetch(`${API_BASE}/${id}`, { method: 'DELETE' })
        .then(res => {
            if (!res.ok && res.status !== 204) throw new Error('Error al eliminar usuario');
            loadUsers();
        })
        .catch(err => alert(err.message));
}

function editUser(id) {
    // Puedes expandir esta función para mostrar un formulario de edición
    alert('Funcionalidad de edición no implementada aún.');
}
