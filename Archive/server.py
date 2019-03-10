# ==============================================================================
# Project:      Object Detection Application
# File name:    server.py
# Author:       JGinn DAlexander
# Year:         2019
# ==============================================================================

# ==============================================================================
# Imports
# ==============================================================================
import socket                               # Include Socket.IO.
import sys                                  # Include System Code.
import csv                                  # Include CSV.
import datetime                             # Include date and time.
from colorama import Fore, Back, Style      # Include terminal colours.

# Output to file
outputToFileName = 'server.csv'

# TCP Socket Connections
serverName = 'Server1'                      # Server Name.
socketHost = '192.168.1.123'                # Server Address.
socketPort = 5500                           # Server Port.

# Modules
mod_OutputFile = 1                          # Output to a file.

# ==============================================================================
# Get current time
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

        # Map data to columns.
        row = [str(currentDT.strftime("%x")), str(currentDT.strftime("%X")), str(client_address), str(data)]

        # Amend CSV.
        with open(outputToFileName, 'a') as file:
            writer = csv.writer(file)
            writer.writerow(row)

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
# Socket Setup
# ==============================================================================
# Create a TCP/IP socket
sock = socket.socket(socket.AF_INET, socket.SOCK_STREAM)

# Bind the socket to the port
server_address = (socketHost, socketPort)
print(Fore.WHITE + 'Starting up on {} port {}'.format(*server_address))

try:
    sock.bind(server_address)
except Exception as e:
    print(Fore.RED + '[DANGER] Could not bind to port.')
    exit()

# Listen for incoming connections
sock.listen(5)

# ==============================================================================
# Main Loop
# ==============================================================================
while True:
    try:
        # Wait for a connection
        print(Fore.YELLOW + '[INFO] Waiting for a connection')
        connection, client_address = sock.accept()
        try:
            print(Fore.GREEN + '[OK] connection from', client_address)

            # Receive the data in small chunks and retransmit it
            while True:
                data = connection.recv(16)
                print(client_address,'received {!r}'.format(data))

                # Output to file
                try:
                    file = outputToFile()
                except Exception as e:
                    print(Fore.RED + '[DANGER] Cannot Output to file.')

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

    except (KeyboardInterrupt, SystemExit):
        print(Fore.RED + '[DANGER] Program has finished.')
        break
