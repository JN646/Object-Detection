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
import csv
import datetime                         # Include date and time
from colorama import Fore, Back, Style  # Include terminal colours

# Output to file
outputToFileName = 'server.csv'

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

        # Map data to columns.
        row = [str(currentDT.strftime("%x")), str(currentDT.strftime("%X")), str(client_address), str(data)]

        # Amend CSV.
        with open(outputToFileName, 'a') as file:
            writer = csv.writer(file)
            writer.writerow(row)

        # Write to the file.
        # try:
        #     with open(outputToFileName, 'a') as file:
        #         print(client_address,'received {!r}'.format(data))
        #         outputString = str(currentDT) + ',' + str(client_address) + ',' + str(data) + '\n'
        #         file.write(str(outputString))
        # except:
        #     print(Fore.RED + '[DANGER] Could not open output file.')

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
