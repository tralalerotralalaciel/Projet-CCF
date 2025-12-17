#define BOUTON 14

int etatBouton = 0;

void setup() {
  Serial.begin(115200);
  pinMode(BOUTON, INPUT);
}

void loop() {
  etatBouton = digitalRead(BOUTON);

  if(etatBouton == HIGH){ // Si le bouton est appuyé
    Serial.println("Bouton appuye");
  }

  delay(100);
}