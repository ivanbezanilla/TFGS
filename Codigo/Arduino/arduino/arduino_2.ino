#include <ESP8266WiFi.h>
#include <MySQL_Connection.h>
#include <MySQL_Cursor.h>
#include <SoftwareSerial.h>
#include <WebSocketsClient.h>
 
#define DHTPIN 2
#define DHTTYPE DHT11
#define SOIL_MOISTURE_PIN A0
#define RELAY_PIN 4 // Pin donde está conectado el relé
#define ESP8266_RX_PIN 10
#define ESP8266_TX_PIN 11
 
const char* ssid = "TU_SSID";
const char* password = "TU_CLAVE_WIFI";
const char* dbServer = "IP_DEL_SERVIDOR_MYSQL";
const char* dbUser = "USUARIO_MYSQL";
const char* dbPassword = "CONTRASEÑA_MYSQL";
const char* dbName = "sistema_riego";
const char* webSocketServerAddress = "IP_DEL_SERVIDOR_WEBSOCKET";
const uint16_t webSocketServerPort = 8080; // El puerto del servidor WebSocket
 
WiFiClient client;
MySQL_Connection conn((Client *)&client);
DHT dht(DHTPIN, DHTTYPE);
SoftwareSerial espSerial(ESP8266_RX_PIN, ESP8266_TX_PIN);
WebSocketsClient webSocket;
 
void setup() {
    Serial.begin(115200);
    pinMode(RELAY_PIN, OUTPUT); // Configurar el pin del relé como salida
 
    espSerial.begin(115200);
    dht.begin();
 
    WiFi.begin(ssid, password);
    while (WiFi.status() != WL_CONNECTED) {
        delay(1000);
        Serial.println("Conectando a la red WiFi...");
    }
    Serial.println("Conexión WiFi establecida.");
 
    if (conn.connect(dbServer, 3306, dbUser, dbPassword, dbName)) {
        Serial.println("Conexión a la base de datos establecida.");
    } else {
        Serial.println("Error al conectar a la base de datos.");
    }

    webSocket.begin(webSocketServerAddress, webSocketServerPort);
    webSocket.onEvent(webSocketEvent);
}
 
void loop() {
    float humedad = dht.readHumidity();
    float temperatura = dht.readTemperature();
    int humedadSuelo = analogRead(SOIL_MOISTURE_PIN);
 
    // Guardar los datos en la base de datos
    if (conn.connected()) {
        char query[128];
        sprintf(query, "INSERT INTO datos_sensores (humedad, temperatura, humedad_suelo) VALUES (%f, %f, %d)", humedad, temperatura, humedadSuelo);
        MySQL_Cursor *cursor = new MySQL_Cursor(&conn);
        cursor->execute(query);
        delete cursor;
    }
    
    webSocket.loop();
    // Esperar un mensaje del servidor WebSocket para activar el relé
    while (espSerial.available()) {
        String mensaje = espSerial.readStringUntil('\n');
        if (mensaje.startsWith("activar_rele")) {
            int tiempoRiego = mensaje.substring(13).toInt();
            activarRiego(tiempoRiego);
        }
    }
 
    delay(5000);
}
 
// Función para activar el relé durante un tiempo especificado
void activarRiego(int tiempoRiego) {
    Serial.println("Activando el relé...");
    digitalWrite(RELAY_PIN, HIGH); // Activar el relé
    delay(tiempoRiego * 1000); // Convertir segundos a milisegundos
    digitalWrite(RELAY_PIN, LOW); // Desactivar el relé
    Serial.println("Riego completado.");
}

// Función para manejar eventos WebSocket
void webSocketEvent(WStype_t type, uint8_t * payload, size_t length) {
    switch(type) {
        case WStype_CONNECTED:
            Serial.println("Conectado al servidor WebSocket.");
            break;
        case WStype_TEXT:
            Serial.println("Mensaje recibido del servidor WebSocket:");
            Serial.println((char *)payload);
            // Aquí puedes agregar la lógica para manejar el mensaje recibido
            break;
        case WStype_DISCONNECTED:
            Serial.println("Desconectado del servidor WebSocket.");
            break;
    }
}