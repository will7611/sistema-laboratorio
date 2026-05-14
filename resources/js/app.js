import './bootstrap';
import './echo'; // Asegúrate de que esto cargue primero

setTimeout(() => {
    if (typeof window.Echo !== 'undefined') {
        console.log("Suscribiendo al canal-prueba...");
        
        // El punto (.) antes del nombre de la clase es VITAL
        // Le dice a Laravel Echo "no intentes adivinar el namespace, usa esto exacto"
        window.Echo.channel('canal-prueba')
            .listen('.App\\Events\\TestReverb', (e) => {
                console.log('¡ÉXITO! Mensaje recibido:', e.mensaje);
                alert('Reverb funciona: ' + e.mensaje);
            });
            
    } else {
        console.error("Window.Echo no está definido");
    }
}, 1000);
