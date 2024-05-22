#include <DHT.h>  // Biblioteca para el sensor DHT11
#include <WiFiEsp.h>
#include <WiFiEspClient.h>
#include <PubSubClient.h>
#include "SoftwareSerial.h"

#define DHTPIN 7     // Pin al que está conectado el sensor DHT11
#define DHTTYPE DHT11   // Tipo de sensor DHT que estás utilizando

#define SOIL_MOISTURE_PIN A0  // Pin al que está conectado el sensor de humedad del suelo

DHT dht(DHTPIN, DHTTYPE);

float temperature = 0;
float humidity = 0;
int soilMoisture = 0;

#define WIFI_AP "TP-Link_7F20"
#define WIFI_PASSWORD "79949910"
char server[50] = "54.163.155.226";

WiFiEspClient espClient;
PubSubClient client(espClient);
SoftwareSerial soft(3, 2);
unsigned long lastSend;
int status = WL_IDLE_STATUS;

void setup() {
    Serial.begin(9600);
    InitWiFi();
    client.setServer(server, 1883);
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
    soilMoisture = analogRead(SOIL_MOISTURE_PIN);  // Leer valor del sensor de humedad del suelo

    if(millis() - lastSend > 2000 ) {
        sendDataTopic();
        lastSend = millis();
    }

    client.loop();
}

void sendDataTopic() {
    String payload = "Temperatura: " + String(temperature) + "C, Humedad: " + String(humidity) + "%, Humedad del suelo: " + String(soilMoisture);
    char attributes[100];
    payload.toCharArray(attributes, 100);
    client.publish("sensores", attributes);
    Serial.println(attributes);
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
            Serial.println("[DONE]");
        } else {
            Serial.print( "[FAILED] [ rc = " );
            Serial.print( client.state() );
            Serial.println( " : retrying in 5 seconds]" );
            delay( 5000 );
        }
    }
}
