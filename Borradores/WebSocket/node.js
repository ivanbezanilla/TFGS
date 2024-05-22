const http = require('http');
const express = require('express');
const socketIo = require('socket.io');
const mysql = require('mysql');
 
const app = express();
const server = http.createServer(app);
const io = socketIo(server);
 
const dbConfig = {
    host: 'IP_DEL_SERVIDOR_MYSQL',
    user: 'USUARIO_MYSQL',
    password: 'CONTRASEÑA_MYSQL',
    database: 'sistema_riego'
};
 
const dbConnection = mysql.createConnection(dbConfig);
 
dbConnection.connect((err) => {
    if (err) {
        console.error('Error de conexión a la base de datos: ' + err.stack);
        return;
    }
    console.log('Conexión a la base de datos establecida.');
});
 
io.on('connection', (socket) => {
    console.log('Cliente WebSocket conectado.');
 
    // Consulta periódica de los últimos valores de sensores en la base de datos y envío al cliente WebSocket
    setInterval(() => {
        dbConnection.query('SELECT * FROM datos_sensores ORDER BY id DESC LIMIT 1', (error, results) => {
            if (error) throw error;
            const sensorData = {
                humedad: results[0].humedad,
                temperatura: results[0].temperatura,
                humedadSuelo: results[0].humedad_suelo
            };
            socket.emit('sensores', sensorData);
        });
    }, 5000);
 
    // Manejo de la activación del riego desde la página web
    socket.on('activar_riego', (tiempoRiego) => {
        // Aquí puedes activar el riego durante la cantidad de segundos especificada
        console.log('Activando riego durante ' + tiempoRiego + ' segundos.');
 
        // Enviar mensaje al Arduino para activar el relé
        socket.emit('activar_rele', tiempoRiego);
    });
 
    socket.on('disconnect', () => {
        console.log('Cliente WebSocket desconectado.');
    });
});
 
// Iniciar el servidor en el puerto 3000
server.listen(3000, () => {
    console.log('Servidor WebSocket iniciado en el puerto 3000');
});