#include <DHT.h>
#include <SoftwareSerial.h>
#include <MySQL_Connection.h>
#include <MySQL_Cursor.h>

#define DHTPIN 12
#define DHTTYPE DHT11
#define SOIL_MOISTURE_PIN A0
#define RELAY_PIN 4 // Pin donde está conectado el relé
#define ESP8266_RX_PIN 2
#define ESP8266_TX_PIN 3

const char* ssid = "Redmi Note 10S";
const char* password = "jolopo09";
const char* dbServer = "91.126.107.133";
const char* dbUser = "asir205";
const char* dbPassword = "Alisal2023";
const char* dbName = "sistema_riego";

DHT dht(DHTPIN, DHTTYPE);
SoftwareSerial espSerial(ESP8266_TX_PIN, ESP8266_RX_PIN);
MySQL_Connection conn((Client *)&espSerial);

void setup() {
    Serial.begin(115200);
    pinMode(RELAY_PIN, OUTPUT); // Configurar el pin del relé como salida

    espSerial.begin(115200);
    dht.begin();

    // Iniciar comunicación con el módulo ESP8266
    //sendCommand("AT+RST\r\n", 2000); // Reiniciar el módulo ESP8266
    Serial.println("Reiniciar modulo");
    delay(5000);
    //sendCommand("AT+CWMODE=1\r\n", 1000); // Configurar el modo de Wi-Fi en modo estación
    Serial.println("Modo estacion");
    delay(5000);
    //sendCommand("AT+CWJAP=\"" + String(ssid) + "\",\"" + String(password) + "\"\r\n", 5000); // Conectar al Wi-Fi
    Serial.println("Conexión al wifi");
    delay(5000);

    // Convertir la dirección IP del servidor MySQL a IPAddress
    IPAddress serverIP;
    serverIP.fromString(dbServer);

    // Conectar al servidor MySQL
    if (conn.connect(serverIP, 3306, dbUser, dbPassword, dbName)) {
        Serial.println("Conexión a la base de datos establecida.");
    } else {
        Serial.println("Error al conectar a la base de datos.");
    }
}

void loop() {
    float humedad = dht.readHumidity();
    float temperatura = dht.readTemperature();
    int humedadSuelo = analogRead(SOIL_MOISTURE_PIN);

    // Guardar los datos en la base de datos
    if (conn.connected()) {
        char query[128];
        sprintf(query, "INSERT INTO datos_sensores (humedad, temperatura, humedad_suelo) VALUES (%f, %f, %d)", humedad, temperatura, humedadSuelo);
        MySQL_Cursor *cur_mem = new MySQL_Cursor(&conn);
        cur_mem->execute(query);
        delete cur_mem;
    }

    // Activar el relé durante 10 segundos
    activarRiego(10);

    // Esperar un tiempo antes de volver a leer los sensores
    delay(5000);
}

// Función para enviar comandos AT al módulo ESP8266
String sendCommand(String command, const int timeout) {
    String response = "";
    espSerial.print(command);
    long int time = millis();
    while ((time + timeout) > millis()) {
        while (espSerial.available()) {
            char c = espSerial.read();
            response += c;
        }
    }
    return response;
}

// Función para activar el relé durante un tiempo especificado
void activarRiego(int tiempoRiego) {
    Serial.println("Activando el relé...");
    digitalWrite(RELAY_PIN, HIGH); // Activar el relé
    delay(tiempoRiego * 1000); // Convertir segundos a milisegundos
    digitalWrite(RELAY_PIN, LOW); // Desactivar el relé
    Serial.println("Riego completado.");
}
