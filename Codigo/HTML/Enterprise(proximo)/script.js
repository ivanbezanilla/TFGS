const socket = io();
 
socket.on('sensores', (data) => {
    document.getElementById('humedad').textContent = `Humedad: ${data.humedad}%`;
    document.getElementById('temperatura').textContent = `Temperatura: ${data.temperatura}°C`;
    document.getElementById('humedadSuelo').textContent = `Humedad del Suelo: ${data.humedadSuelo}`;
});
 
function activarRiego() {
    const tiempoRiego = document.getElementById('tiempoRiego').value;
    socket.emit('activar_rele', tiempoRiego);
    alert(`Se ha activado el riego durante ${tiempoRiego} segundos.`);
}