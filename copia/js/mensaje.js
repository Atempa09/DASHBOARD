const chatContainer = document.getElementById('chat-container');
const openChatBtn = document.getElementById('open-chat-btn');
const closeBtn = document.getElementById('close-btn');
const chatMensajes = document.getElementById('chat-mensajes');
const chatForm = document.getElementById('chat-form');
const mensajeInput = document.getElementById('mensaje');
const notificacionesCount = document.getElementById('notificaciones-count');

let receptorId = '';
let intervalo; // Para cargar mensajes
let intervaloConexion; // Para actualizar estado de conexión

// Mostrar el chat
openChatBtn.addEventListener('click', () => {
    chatContainer.style.display = 'flex';
    openChatBtn.style.display = 'none';

    // Iniciar estado de conexión
    actualizarEstadoConexion(); // llamada inmediata
    intervaloConexion = setInterval(actualizarEstadoConexion, 2000);
});

// Cerrar el chat
closeBtn.addEventListener('click', () => {
    chatContainer.style.display = 'none';
    openChatBtn.style.display = 'block';

    // Detener intervalos
    clearInterval(intervalo); // mensajes
    clearInterval(intervaloConexion); // conexión
});

// Seleccionar usuario y cargar mensajes
function abrirChat(id) {
    receptorId = parseInt(id);
    if (!receptorId) {
        console.warn('ID del receptor no válido:', id);
        return;
    }

    cargarMensajes();

    clearInterval(intervalo);
    intervalo = setInterval(cargarMensajes, 2000);

    // Reiniciar estado de conexión
    clearInterval(intervaloConexion);
    actualizarEstadoConexion(); // llamada inmediata
    intervaloConexion = setInterval(actualizarEstadoConexion, 2000);
}

// Cargar mensajes del chat con el usuario seleccionado
function cargarMensajes() {
    if (!receptorId) return;

    fetch(`cargar_mensajes.php?receptor_id=${receptorId}&mi_id=${mi_id}`)
        .then(res => res.json())
        .then(data => {
            chatMensajes.innerHTML = '';
            data.mensajes.forEach(msg => {
                const div = document.createElement('div');
                div.classList.add('mensaje');
                div.classList.add(msg.emisor_id == mi_id ? 'enviado' : 'recibido');
                div.innerHTML = `<p>${msg.mensaje}</p><div style="font-size:10px; color:#888;">${msg.fecha}</div>`;
                chatMensajes.appendChild(div);
            });
            chatMensajes.scrollTop = chatMensajes.scrollHeight;

            // Actualizar contador de notificaciones
            fetch(`notificaciones.php?mi_id=${mi_id}`)
                .then(res => res.json())
                .then(data => {
                    notificacionesCount.textContent = data.no_leidos > 0 ? data.no_leidos : '';
                });

            // Marcar como leídos
            fetch(`marcar_como_leido.php?emisor_id=${receptorId}`);
        })
        .catch(err => console.error("Error al cargar mensajes:", err));
}

// Enviar mensaje
chatForm.addEventListener('submit', e => {
    e.preventDefault();
    if (!mensajeInput.value.trim() || !receptorId) {
        console.warn("No hay mensaje o receptor no seleccionado");
        return;
    }

    fetch('enviar_mensaje.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `mensaje=${encodeURIComponent(mensajeInput.value)}&receptor_id=${receptorId}`
    }).then(() => {
        mensajeInput.value = '';
        cargarMensajes();
    }).catch(err => console.error("Error al enviar mensaje:", err));
});

// Función para actualizar el estado de conexión
function actualizarEstadoConexion() {
    fetch('actualizar_estado.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('Estado de conexión actualizado.');
            }
        })
        .catch(error => console.error('Error al actualizar estado:', error));
}