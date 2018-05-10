import paho.mqtt.client as mqtt
#import broker

def publish_music(message):
    publ = mqtt.Client("publisher")
    publ.connect("127.0.0.1")
    publ.publish("house/music", message)



def publish_light(message):
    publ = mqtt.Client("publisher")
    publ.connect("127.0.0.1")
    publ.publish("house/light", message)

def publish_sms(message):
    publ = mqtt.Client("publisher")
    publ.connect("127.0.0.1")
    publ.publish("house/sms", message)
