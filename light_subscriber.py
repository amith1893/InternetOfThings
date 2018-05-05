import paho.mqtt.client as mqtt
#import broker
import time

def message_handler(client, userdata, message):
    print("Message received ", str(message.payload.decode("utf-8")))
    print("Message topic ", message.topic)
    print("Message QOS ", message.qos)
    print("Message retain flag ", message.retain)


subs = mqtt.Client("subscriber-2")
print("Connecting to broker")
#subs.connect("127.0.0.1:1883")
subs.connect("127.0.0.1")
print("Subscribing to lights topic")
subs.on_message = message_handler
subs.loop_start()
subs.subscribe("house/light")
time.sleep(1000)
