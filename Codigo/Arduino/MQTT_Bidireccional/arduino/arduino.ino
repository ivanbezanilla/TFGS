#include <DHT.h>  // Biblioteca para el sensor DHT11
#include <WiFiEsp.h>
#include <WiFiEspClient.h>
#include <PubSubClient.h>
#include "SoftwareSerial.h"

#define DHTPIN 7     // Pin al que está conectado el sensor DHT11
#define DHTTYPE DHT11   // Tipo de sensor DHT que estás utilizando

#define SOIL_MOISTURE_PIN A0  // Pin al que está conectado el sensor de humedad del suelo
#define RELAY_PIN 9          // Pin al que está conectado el relé

DHT dht(DHTPIN, DHTTYPE);

float temperature = 0;
float humidity = 0;
int sensorValue = 0;
int soilMoisture = 0; // Variable para almacenar la humedad del suelo

#define WIFI_AP "TP-Link_7F20"
#define WIFI_PASSWORD "79949910"
char server[50] = "54.163.155.226";

WiFiEspClient espClient;
PubSubClient client(espClient);
SoftwareSerial soft(3, 2);
unsigned long lastSend;
int status = WL_IDLE_STATUS;

// Definir los límites mínimo y máximo de lectura del sensor
int sensorMin = 0;  // Valor mínimo leído por el sensor
int sensorMax = 1023;  // Valor máximo leído por el sensor

// Definir los límites mínimo y máximo de humedad del suelo
int humedadMin = 0;  // Porcentaje mínimo de humedad del suelo
int humedadMax = 100;  // Porcentaje máximo de humedad del suelo

void setup() {
    Serial.begin(9600);
    pinMode(RELAY_PIN, OUTPUT); // Configurar el pin del relé como salida
    InitWiFi();
    client.setServer(server, 1883);
    client.setCallback(callback); // Establecer la función de devolución de llamada para los mensajes entrantes
    lastSend = 0;
    dht.begin();
    pinMode(SOIL_MOISTURE_PIN, INPUT);
}

void loop() {
    status = WiFi.status();
    if(status != WL_CONNECTED) {
        reconnectWifi();
    }

    if(!client.connected()) {
        reconnectClient();
    }

    temperature = dht.readTemperature();
    humidity = dht.readHumidity();
    sensorValue = analogRead(SOIL_MOISTURE_PIN);  // Leer valor del sensor de humedad del suelo
    soilMoisture = map(sensorValue, sensorMin, sensorMax, humedadMin, humedadMax);

    if(millis() - lastSend > 10000 ) {
        sendDataTopic();
        lastSend = millis();
    }

    client.loop();
}

void callback(char* topic, byte* payload, unsigned int length) {
    if (!client.connected()) {
        Serial.println("Conexión perdida. Intentando reconectar...");
        reconnectClient();
    }
    
    // Si se recibe un mensaje en el topic "riego", se activa el relé durante los segundos indicados en el mensaje
    if (strcmp(topic, "riego") == 0) {
        Serial.print("Mensaje recibido en el tema 'riego': ");
        payload[length] = '\0';  // Asegurar que el payload es una cadena null-terminated
        Serial.println((char*)payload);  // Imprimir el mensaje en el monitor serie

        int seconds = atoi((char*)payload); // Convertir el payload a entero (segundos)
        activatePump(seconds);
    }
}

void sendDataTopic() {
    String payload = "Temperatura: " + String(temperature) + " C, Humedad: " + String(humidity) + " %, Humedad del suelo: " + String(soilMoisture) + ".00 %, id_ard: 1";
    char attributes[100];
    payload.toCharArray(attributes, 100);
    client.publish("sensores", attributes);
    Serial.println(attributes);
}

void activatePump(int seconds) {
    digitalWrite(RELAY_PIN, HIGH); // Activar el relé (encender la bomba de agua)
    delay(seconds * 1000); // Esperar el tiempo indicado en segundos
    digitalWrite(RELAY_PIN, LOW); // Desactivar el relé (apagar la bomba de agua)
}

void InitWiFi() {
    soft.begin(9600);
    WiFi.init(&soft);
    if (WiFi.status() == WL_NO_SHIELD) {
        Serial.println("El modulo WiFi no esta presente");
        while (true);
    }
    reconnectWifi();
}

void reconnectWifi() {
    Serial.println("Iniciar conección a la red WIFI");
    while(status != WL_CONNECTED) {
        Serial.print("Intentando conectarse a WPA SSID: ");
        Serial.println(WIFI_AP);
        status = WiFi.begin(WIFI_AP, WIFI_PASSWORD);
        delay(500);
    }
    Serial.println("Conectado a la red WIFI");
}

void reconnectClient() {
    while(!client.connected()) {
        Serial.print("Conectando a: ");
        Serial.println(server);
        String clientId = "ESP8266Client-" + String(random(0xffff), HEX);
        if(client.connect(clientId.c_str())) {
            client.subscribe("riego"); // Suscribirse al topic "riego"
            Serial.println("[DONE]");
        } else {
            Serial.print( "[FAILED] [ rc = " );
            Serial.print( client.state() );
            Serial.println( " : retrying in 5 seconds]" );
            delay( 5000 );
        }
    }
}
