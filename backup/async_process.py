import os
import time
import pygame
import psutil

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


def play(filename):
    sound = Sound()
    sound.playSound()

def play_music(filename):
    playf = 0
    while True:
        control = input()
        if control == 'p':
            if playf == 0:
                pid = os.fork()
                playf = 1
                if pid == 0:
                    play(filename)
            else:
                print("Already playing")
        elif control == 's':
            if playf == 1:
                print("Need to stop")
                print("Stop the existing child who is playing the music")
                playf = 0
                curr_id = psutil.Process(os.getpid())
                for child in curr_id.children(recursive=True):
                    child.kill()
            else:
                print("No need to stop")

        time.sleep(5)

play_music("play.wav")

