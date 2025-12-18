#include <WiFi.h>
#include <HTTPClient.h>

#define BOUTON 14
#define LED 2

const char* ssid = "WIFI_FOR_ESP32";
const char* password = "WIFI_FOR_ESP32";

const char* serverName = "http://172.30.103.68/web/etat_salle.php";

const char* nomSalle = "C206";

int etatBouton = 0;
bool etatSalle = false; // True veut dire que la salle est occupée, false veut dire qu'elle est libre

void setup() {
  Serial.begin(115200);
  
  WiFi.begin(ssid, password);
  Serial.println("Connecting");

  pinMode(BOUTON, INPUT);
  pinMode(LED, OUTPUT);

  // Pendant la connexion WiFi
  while(WiFi.status() != WL_CONNECTED){
    delay(500);
    Serial.print(".");
  }

  Serial.print("Connected to WiFi network with IP Address.");
  Serial.println(WiFi.localIP());
}

void loop() {
  etatBouton = digitalRead(BOUTON);

  if(etatBouton == HIGH){
    if(WiFi.status() == WL_CONNECTED){
      WiFiClient client;
      HTTPClient http;

      http.begin(client, serverName);

      http.addHeader("Content-Type", "application/json");

      String httpRequestData;
      if(etatSalle == false){ // Salle libre
        digitalWrite(LED, LOW);
        httpRequestData = "{\"etat\": 0, \"nom\": \"" + String(nomSalle) + "\"}";
      }else{ // Salle occupée
        digitalWrite(LED, HIGH);
        httpRequestData = "{\"etat\": 1, \"nom\": \"" + String(nomSalle) + "\"}";
      }
      etatSalle = !etatSalle;

      int httpResponseCode = http.POST(httpRequestData);

      Serial.print("HTTP Code réponse : ");
      Serial.println(httpResponseCode);

      http.end();
    }
    else{ // Si le WiFi est déconnecté
      Serial.println("WiFi Déconnecté");
    }
    delay(5000); // C'est normalement mauvais de mettre un délai comme ça, même en faisant pas attention à quand le bouton est maintenu.
    // Mais ici on peut, car on peut se dire que le bouton va être appuyé toutes les 30 minutes, donc ça pose pas vraiment problème.
  }
}
