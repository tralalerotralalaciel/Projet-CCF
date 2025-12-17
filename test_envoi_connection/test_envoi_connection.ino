#include <WiFi.h>
#include <HTTPClient.h>

const char* ssid = "WIFI_FOR_ESP32";
const char* password = "WIFI_FOR_ESP32";

const char* serverName = "http://172.30.102.6:5000/etat_salle";

unsigned long lastTime = 0;
unsigned long timerDelay = 10000;

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
  if((millis() - lastTime) > timerDelay){
    if(WiFi.status() == WL_CONNECTED){
      WiFiClient client;
      HTTPClient http;

      http.begin(client, serverName);

      http.addHeader("Content-Type", "application/json");
      String httpRequestData = "{\"etat\": false}";

      int httpResponseCode = http.POST(httpRequestData);

      Serial.print("HTTP Code réponse : ");
      Serial.println(httpResponseCode);

      http.end();
    }
    else{ // Si le WiFi est déconnecté
      Serial.println("WiFi Déconnecté");
    }
    lastTime = millis();
  }
}
