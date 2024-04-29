#include <SoftwareSerial.h>
#include <DHT.h>

#define DHTPIN 12       // Pin donde está conectado el sensor DHT11
#define SOILPIN A0      // Pin analógico donde está conectado el sensor de humedad del suelo
#define DHTTYPE DHT11  // Tipo de sensor DHT (DHT11 en este caso)

DHT dht(DHTPIN, DHTTYPE);

SoftwareSerial espSerial(3, 2); // RX, TX del ESP-01

void setup() {
  Serial.begin(9600);
  espSerial.begin(9600); // Inicializar la comunicación con el ESP-01
  dht.begin();
}

void loop() {
  // Leer la temperatura y humedad del sensor DHT11
  float temperature = dht.readTemperature();
  float humidity = dht.readHumidity();

  // Leer la humedad del suelo
  int soilHumidity = analogRead(SOILPIN);

  // Verificar si se leyeron correctamente los valores del sensor DHT11
  if (!isnan(temperature) && !isnan(humidity)) {
    Serial.println("Lecturas de sensores exitosas");

    // Construir la cadena de datos a enviar al servidor
    String data = "temperature=" + String(temperature) + "&humidity=" + String(humidity) + "&soilHumidity=" + String(soilHumidity);
    int dataLength = data.length(); // Obtener la longitud de la cadena de datos

    Serial.println("URL construida: " + data);
delay(1000);
    // Establecer conexión TCP con el servidor
    espSerial.println("AT+CIPSTART=\"TCP\",\"91.126.107.133\",80");
    if (espSerial.find("OK")) {
      Serial.println("Conexión exitosa al servidor");
delay(1000);
      // Enviar solicitud HTTP POST
      espSerial.print("AT+CIPSEND=");
      espSerial.println(dataLength + 4);
      if (espSerial.find(">")) {
        Serial.println("Enviando datos al servidor");
        espSerial.print("POST /insert_data.php HTTP/1.1\r\n");
        espSerial.print("Host: 91.126.107.133\r\n");
        espSerial.print("Content-Type: application/x-www-form-urlencoded\r\n");
        espSerial.print("Content-Length: ");
        espSerial.print(dataLength);
        espSerial.print("\r\n\r\n");
        espSerial.println(data);
        delay(2000); // Aumentar el tiempo de espera después de enviar los datos
        espSerial.println();
        if (espSerial.find("SEND OK")) {
          Serial.println("Datos enviados correctamente");
        } else {
          Serial.println("Error al enviar datos");
        }
      } else {
        Serial.println("No se encontró el prompt '>' para enviar datos");
      }
    } else {
      Serial.println("Error al establecer conexión con el servidor");
    }

    // Cerrar conexión TCP
    espSerial.println("AT+CIPCLOSE");
    delay(1000); // Esperar para cerrar la conexión
  } else {
    Serial.println("Error al leer los sensores");
  }

  delay(5000); // Esperar 5 segundos antes de tomar otra lectura
}
