#include <WiFi.h>
#include <HTTPClient.h>

const char* ssid = "WIFI_FOR_ESP32";
const char* password = "WIFI_FOR_ESP32";

// const char serverName = ""

void setup() {
  Serial.begin(115200);
  
  WiFi.begin(ssid, password);
  Serial.println("Connecting");

  // Pendant la connexion WiFi
  while(WiFi.status() != WL_CONNECTED){
    delay(500);
    Serial.print(".");
  }

  Serial.print("Connected to WiFi network with IP Address.");
  Serial.println(WiFi.localIP());
}

void loop() {

}
