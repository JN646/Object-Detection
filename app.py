# ==============================================================================
# Project:      Object Detection Application
# File name:    app.py
# Author:       JGinn DAlexander
# Year:         2019
# ==============================================================================

# ==============================================================================
# Imports
# ==============================================================================
from flask_socketio import SocketIO, emit
from flask import Flask, render_template, url_for, copy_current_request_context
from random import random
from time import sleep
from threading import Thread, Event
import socket
import sys

# Create a TCP/IP socket
sock = socket.socket(socket.AF_INET, socket.SOCK_STREAM)

# Bind the socket to the port
server_address = ('192.168.1.123', 5500)
print('starting up on {} port {}'.format(*server_address))
sock.bind(server_address)

# Listen for incoming connections
sock.listen(5)

# ==============================================================================
# Define Flask App
# ==============================================================================
app = Flask(__name__)
app.config['SECRET_KEY'] = 'secret!'
app.config['DEBUG'] = True

#turn the flask app into a socketio app
socketio = SocketIO(app)

#random number Generator Thread
thread = Thread()
thread_stop_event = Event()

class RandomThread(Thread):
    def __init__(self):
        self.delay = 1
        super(RandomThread, self).__init__()

    def randomNumberGenerator(self):
        while not thread_stop_event.isSet():
            # Wait for a connection
            print('waiting for a connection')
            connection, client_address = sock.accept()
            try:
                print('connection from', client_address)

                # Receive the data in small chunks and retransmit it
                while True:
                    data = connection.recv(16)
                    print(client_address,'received {!r}'.format(data))
                    if data:
                        # print('sending data back to the client')
                        connection.sendall(data)
                    else:
                        print('no data from', client_address)
                        break
            finally:
                # Clean up the connection
                connection.close()

            number = input('Manual enter number...')
            # number = round(random()*10, 3)
            print(number)
            socketio.emit('newnumber', {'number': data}, namespace='/test')
            sleep(self.delay)

    def run(self):
        self.randomNumberGenerator()

# ==============================================================================
# Routing
# ==============================================================================
@app.route('/')
def index():
    #only by sending this page first will the client be connected to the socketio instance
    return render_template('index.html')

# ==============================================================================
# Socket Connect
# ==============================================================================
@socketio.on('connect', namespace='/test')
def test_connect():
    # need visibility of the global thread object
    global thread
    print('Client connected')

    #Start the random number generator thread only if the thread has not been started before.
    if not thread.isAlive():
        print("Starting Thread")
        thread = RandomThread()
        thread.start()

@socketio.on('disconnect', namespace='/test')

# ==============================================================================
# Socket Disconnect
# ==============================================================================
def test_disconnect():
    print('Client disconnected')

# ==============================================================================
# Main Routine
# ==============================================================================
if __name__ == '__main__':
    socketio.run(app)
