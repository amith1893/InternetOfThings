import paho.mqtt.client as mqtt
#import broker
import time
import RPi.GPIO as GPIO
import time

def message_handler(client, userdata, message):
	print("Message received ", str(message.payload.decode("utf-8")))
	print("Message topic ", message.topic)
	print("Message QOS ", message.qos)
	print("Message retain flag ", message.retain)
	
	command = str(message.payload.decode("utf-8"))
	if command == "Turn on":
		lightsOn()
	elif command == "Turn off":
		lightsOff()

def lightsOn():
	GPIO.setmode(GPIO.BCM)
	GPIO.setwarnings(False)
	GPIO.setup(18,GPIO.OUT)
	print("LED on")
	GPIO.output(18,GPIO.HIGH)

def lightsOff():
	GPIO.setmode(GPIO.BCM)
	GPIO.setwarnings(False)
	GPIO.setup(18,GPIO.OUT)
	print("LED off")
	GPIO.output(18,GPIO.LOW)



subs = mqtt.Client("light-subscriber")
print("Connecting to broker")
#subs.connect("127.0.0.1:1883")
subs.connect("127.0.0.1")
print("Subscribing to lights topic")
subs.on_message = message_handler
subs.loop_start()
subs.subscribe("house/light")
time.sleep(1000)
