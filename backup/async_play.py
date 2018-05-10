import os

def play_file(filename):
    os.exec("aplay play.wav") 

def play_music(filename):
    new_pid = os.fork()
    while True:
        get_control = input("Enter command")
        if get_control == 'p':
            new_pid = os.fork()
            if new_pid == 0:
                play_file(filename)
            else:
                os.
    if new_pid == 0:
        play_file(filename)    
