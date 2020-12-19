#define B 3600 // B-коэффициент
#define SERIAL_R 2940 // сопротивление последовательного резистора, 102 кОм
#define THERMISTOR_R 10860 // номинальное сопротивления термистора, 100 кОм
#define NOMINAL_T 21 // номинальная температура (при которой TR = 100 кОм)
 
// const byte tempPin = A5;
const byte relePin = 2;
bool releMod = false;
void setup() {
    Serial.begin( 9600 );
    pinMode( A5, INPUT );
    pinMode( relePin, OUTPUT);
}
 
void loop() {
    int t = analogRead( A5 );
    float tr = 1023.0 / t - 1;
    tr = SERIAL_R / tr;
    Serial.print("R=");
    Serial.print(tr);
    Serial.print(", t=");
 
    float steinhart;
    steinhart = tr / THERMISTOR_R; // (R/Ro)
    steinhart = log(steinhart); // ln(R/Ro)
    steinhart /= B; // 1/B * ln(R/Ro)
    steinhart += 1.0 / (NOMINAL_T + 273.15); // + (1/To)
    steinhart = 1.0 / steinhart; // Invert
    steinhart -= 273.15; 
    
    if (steinhart >95) { // 94
      releMod = true;
    }
    if (steinhart < 90) { //88
      releMod = false;
    }
    digitalWrite(relePin, releMod);  
    Serial.print(steinhart);  
   Serial.print(", pinMod=");
   Serial.println(releMod);
    delay(100);
}
