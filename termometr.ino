#define tmax 96 // температура максимум
#define tmin 86 // температура минимум
#define timeout 10 // период снятия показаний в секундах

#define B 3600 // B-коэффициент
#define SERIAL_R 2940 // сопротивление последовательного резистора, 102 кОм
#define THERMISTOR_R 10860 // номинальное сопротивления термистора, 100 кОм
#define NOMINAL_T 21 // номинальная температура (при которой TR = 100 кОм)
 
const byte tempPin = A5;
const byte relePin = 2;
bool releMod = false;
void setup() {
    Serial.begin( 9600 );
    pinMode( tempPin, INPUT );
    pinMode( relePin, OUTPUT);
}
 
void loop() {
    int t = analogRead( tempPin );
    float tr = 1023.0 / t - 1;
    tr = SERIAL_R / tr;
    Serial.print("R=");
    Serial.print(tr);
    Serial.print(", t=");
 
    float steinhart;
    steinhart = tr / THERMISTOR_R;
    steinhart = log(steinhart);
    steinhart /= B;
    steinhart += 1.0 / (NOMINAL_T + 273.15);
    steinhart = 1.0 / steinhart;
    steinhart -= 273.15; 
    
    if (steinhart >tmax) {
      releMod = true;
    }
    if (steinhart < tmin) {
      releMod = false;
    }
    digitalWrite(relePin, releMod);  
    Serial.print(steinhart);  
    Serial.print(", pinMod=");
    Serial.println(releMod);
    delay(timeout * 1000);
}
