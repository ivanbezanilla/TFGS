#include <Arduino.h>
#include <SoftwareSerial.h>
#include <WiFiEsp.h>

// Configuración de la red WiFi
char ssid[] = "TP-Link_7F20";          // Nombre de la red WiFi
char pass[] = "79949910";    // Contraseña de la red WiFi

// Configuración del servidor
char server[] = "jarduino.ddns.net";    // Dirección IP o nombre de dominio del servidor
int port = 80;                    // Puerto del servidor

// Inicializar el cliente WiFi y el cliente HTTP
WiFiEspClient client;

void setup() {
  Serial.begin(9600);   // Inicializar el puerto serie
  // Inicializar el módulo ESP8266
  WiFi.init(&Serial1);
  // Conectar a la red WiFi
  WiFi.begin(ssid, pass);
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("Conectado a la red WiFi");
}

void loop() {
  if (client.connect(server, port)) {
    Serial.println("Conectado al servidor");
    // Definir el cuerpo de la solicitud POST
    String postData = "temperature=25.5&humidity=60&soilHumidity=500";
    // Enviar la solicitud POST al servidor
    client.println("POST /insert_data.php HTTP/1.1");
    client.println("Host: " + String(server));
    client.println("Content-Type: application/x-www-form-urlencoded");
    client.println("Content-Length: " + String(postData.length()));
    client.println();
    client.println(postData);
    delay(100);
    // Leer y mostrar la respuesta del servidor
    while (client.available()) {
      char c = client.read();
      Serial.print(c);
    }
    Serial.println();
    client.stop(); // Cerrar la conexión
  } else {
    Serial.println("Error al conectar al servidor");
  }
  delay(5000);  // Esperar antes de enviar la siguiente solicitud
}
