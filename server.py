# ==============================================================================
# Project:      Object Detection Application
# File name:    server.py
# Author:       JGinn DAlexander
# Year:         2019
# ==============================================================================

# ==============================================================================
# Imports
# ==============================================================================
import socket
import sys
import datetime                         # Include date and time
from colorama import Fore, Back, Style  # Include terminal colours

# Output to file
outputToFileName = 'server.txt'

# Modules
mod_OutputFile = 1          # Output to a file.

# ==============================================================================
# Get curren time
# ==============================================================================
def getCurrentTime():
    # Get current date and time.
    currentDT = datetime.datetime.now()

    return currentDT

# ==============================================================================
# Output to file
# ==============================================================================
def outputToFile():
    if mod_OutputFile == 1:
        # Get current date and time
        currentDT = getCurrentTime()

        # Write to the file.
        with open(outputToFileName, 'a') as file:
            print(client_address,'received {!r}'.format(data))
            outputString = str(currentDT) + ',' + str(client_address) + ',' + str(data) + '\n'
            # outputString = str(currentDT) + ',' + str(feedName) + ',' + str(outputTargetCount) + '\n'
            file.write(str(outputString))

# ==============================================================================
# Socket Setup
# ==============================================================================
# Create a TCP/IP socket
sock = socket.socket(socket.AF_INET, socket.SOCK_STREAM)

# Bind the socket to the port
server_address = ('192.168.1.123', 5500)
print('starting up on {} port {}'.format(*server_address))
sock.bind(server_address)

# Listen for incoming connections
sock.listen(5)

# ==============================================================================
# Main Sequence
# ==============================================================================
# Print Intro Messages
print(Fore.WHITE + '# ============================= #')
print(Fore.WHITE + '# Object Detection App          #')
print(Fore.WHITE + '# server.py v0.1                #')
print(Fore.WHITE + '# 2019                          #')
print(Fore.WHITE + '# ============================= #')

# Wait for key press
input(Fore.WHITE + 'Press enter to continue... ')

# ==============================================================================
# Main Loop
# ==============================================================================
while True:
    # Wait for a connection
    print(Fore.YELLOW + '[INFO] Waiting for a connection')
    connection, client_address = sock.accept()
    try:
        print(Fore.GREEN + '[OK] connection from', client_address)

        # On Connection write to file
        if mod_OutputFile == 1:
            # Write to the file.
            with open(outputToFileName, 'a') as file:
                currentDT = getCurrentTime()
                outputString = str(currentDT) + ',' + 'Connection from' + str(client_address) + '\n'
                file.write(str(outputString))

        # Receive the data in small chunks and retransmit it
        while True:
            data = connection.recv(16)
            print(client_address,'received {!r}'.format(data))

            # Output to file
            outputToFile()

            if data:
                # print('sending data back to the client')
                connection.sendall(data)
            else:
                print(Fore.YELLOW + '[INFO] No data from', client_address)
                break

    finally:
        # Clean up the connection
        connection.close()

        # Close the write to file if Output mode is on.
        if mod_OutputFile == 1:
            file.close()
            print(Fore.GREEN + 'Output file closed.')
