import paho.mqtt.client as mqtt
#import broker
import time
import pygame
import os
import psutil

playf = 0

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


def play():
	sound = Sound()
	sound.playSound()

def message_handler(client, userdata, message):
	print("Message received ", str(message.payload.decode("utf-8")))
	print("Message topic ", message.topic)
	print("Message QOS ", message.qos)
	print("Message retain flag ", message.retain)
	print(message)
	command = str(message.payload.decode("utf-8"))
    
	global playf
	if command == "Play":
		print("---------------------------------------")
		if playf == 0:
			playf = 1
			pid = os.fork()
			if pid == 0:
				play()
		else:
			print("Already playing")

	elif command == "Stop":
		print("PLAYF value ", playf)
		if playf == 1:
			print("Need to Stop")
			print("Stop the existing child who is playing music")
			playf = 0
			curr_id = psutil.Process(os.getpid())
			for child in curr_id.children(recursive=True):
				child.kill()
		else:
			print("Not running any file. No need to stop")
       

subs = mqtt.Client("subscriber-3")
print("Connecting to broker")
subs.connect("127.0.0.1")
print("Subscribing to music topic")
subs.on_message = message_handler
subs.loop_start()
subs.subscribe("house/music")
time.sleep(1000)
