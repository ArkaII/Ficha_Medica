function updateTime() {
    // Obtiene la hora actual
    const now = new Date();
    
    // Formatea la hora
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    const seconds = String(now.getSeconds()).padStart(2, '0');
    
    // Construye la cadena de tiempo
    const timeString = `${hours}:${minutes}:${seconds}`;
    
    // Actualiza el contenido del reloj en el elemento con el id "clock"
    document.getElementById('clock').textContent = timeString;
}

// Llama a la función updateTime() cada segundo para actualizar el reloj
setInterval(updateTime, 1000);

// Seleccionar todos los botones "Agendar"
const agendarButtons = document.querySelectorAll('.agendar-btn');



