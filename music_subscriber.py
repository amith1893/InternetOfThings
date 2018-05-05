import paho.mqtt.client as mqtt
#import broker
import time
import pygame

class Sound:
    def __init__(self):
        pygame.mixer.init()

    def playSound(self):
        print("Alive")
        pygame.mixer.music.load("play.wav")
        pygame.mixer.music.play()
        while pygame.mixer.music.get_busy() == True:
            continue

    def stopSound():
        pygame.mixer.music.stop()


def message_handler(client, userdata, message):
    print("Message received ", str(message.payload.decode("utf-8")))
    print("Message topic ", message.topic)
    print("Message QOS ", message.qos)
    print("Message retain flag ", message.retain)
    sound = Sound()
    print(message)
    command = str(message.payload.decode("utf-8"))
    if command == "Play":
        print("---------------------------")
        sound.playSound()
    elif message == "Stop":
        sound.stopSound()
       


subs = mqtt.Client("subscriber-3")
print("Connecting to broker")
#subs.connect("127.0.0.1:1883")
subs.connect("127.0.0.1")
print("Subscribing to music topic")
subs.on_message = message_handler
subs.loop_start()
subs.subscribe("house/music")
time.sleep(1000)
