#include <SoftwareSerial.h>
#include <DHT.h>
#include "PubSubClientWithSoftwareSerial.h"

#define RX_PIN 3
#define TX_PIN 2
#define DHTPIN 12
#define SOILPIN A0
#define DHTTYPE DHT11
#define RELAY_PIN 4

SoftwareSerial espSerial(RX_PIN, TX_PIN);
PubSubClientWithSoftwareSerial client(espSerial);
DHT dht(DHTPIN, DHTTYPE);

void setup() {
    Serial.begin(9600);
    espSerial.begin(9600);
    //setupEsp01();
    pinMode(RELAY_PIN, OUTPUT);
    dht.begin();
    reconnect(); // Conectar al inicio
}

void loop() {
    if (!client.connected()) {
        reconnect(); // Reconectar si se pierde la conexión
    }
    client.loop();
    float temperatura = dht.readTemperature();
    float humedad = dht.readHumidity();
    int soilHumidity = analogRead(SOILPIN);
    enviarDatosSensores(temperatura, humedad, soilHumidity);
    delay(5000);
}

void setupEsp01() {
    sendATCommand("AT", 10000);
    sendATCommand("AT+CWMODE=1", 10000);
    sendATCommand("AT+CWJAP=\"TP-Link_7F20\",\"79949910\"", 15000);
    sendATCommand("AT+CIPMUX=0", 10000);
}

void sendATCommand(String command, int timeout) {
    espSerial.println(command);
    delay(timeout);
    while (espSerial.available() > 0) {
        Serial.write(espSerial.read());
    }
}

void enviarDatosSensores(float temperatura, float humedad, int soilHumidity) {
    String payload = "Temperatura: " + String(temperatura) + ", Humedad: " + String(humedad) + ", HumedadSuelo: " + String(soilHumidity);
    bool success = client.publish("sensores", payload.c_str());
    if (success) {
        Serial.println("Datos de sensores enviados correctamente.");
    } else {
        Serial.println("Error al enviar datos de sensores.");
    }
}

void callback(char* topic, byte* payload, unsigned int length) {
    Serial.print("Mensaje recibido en el tema: ");
    Serial.println(topic);
    String message;
    for (int i = 0; i < length; i++) {
        message += (char)payload[i];
    }
    Serial.print("Contenido del mensaje: ");
    Serial.println(message);
    if (strcmp(topic, "riego/control") == 0) {
        int riegoTiempo = atoi(message.c_str());
        digitalWrite(RELAY_PIN, HIGH);
        delay(riegoTiempo * 1000);
        digitalWrite(RELAY_PIN, LOW);
    }
}

void reconnect() {
    while (!client.connected()) {
        Serial.println("Intentando conexión MQTT...");
        if (client.connect("arduino_client")) {
            Serial.println("Conexión MQTT exitosa.");
            client.subscribe("riego/control");
        } else {
            Serial.print("Error al conectar al servidor MQTT, rc=");
            Serial.println(client.state());
            Serial.println("Intentando nuevamente en 5 segundos...");
            delay(5000);
        }
    }
}
