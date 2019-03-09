# ==============================================================================
# Project:      Object Detection Application
# File name:    server2.py
# Author:       JGinn DAlexander
# Year:         2019
# ==============================================================================

# ==============================================================================
# Imports
# ==============================================================================
import socket
import sys
import traceback
import csv
import datetime
from threading import Thread
from colorama import Fore, Back, Style
import mysql.connector
from pythonping import ping

# Output to file
outputToFileName = 'server.csv'
serverName = 'Server1'

# Modules
mod_OutputFile = 1 # Output to a file.

# ==============================================================================
# Send to database
# ==============================================================================
def sendToDatabase(ipPort, client_input):
    currentDT = getCurrentTime()

    mydb = mysql.connector.connect(
      host="localhost",
      user="root",
      passwd="",
      database="objectTracker"
    )

    mycursor = mydb.cursor()

    sql = "INSERT INTO counter (counter_date, counter_time, counter_IP, counter_count) VALUES (%s, %s, %s, %s)"
    val = str(currentDT.strftime("%Y-%m-%d")), str(currentDT.strftime("%X")), str(ipPort), str(client_input)

    mycursor.execute(sql, val)

    mydb.commit()

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
def outputToFile(ipPort, client_input):
    if mod_OutputFile == 1:
        # Map data to columns.
        currentDT = getCurrentTime()
        row = [str(currentDT.strftime("%x")), str(currentDT.strftime("%X")), str(ipPort), str(client_input)]

        # Amend CSV.
        with open(outputToFileName, 'a') as file:
            writer = csv.writer(file)
            writer.writerow(row)

    return file

# ==============================================================================
# Main
# ==============================================================================
def main():
    # Print Intro Messages
    print(Fore.WHITE + '# ============================= #')
    print(Fore.WHITE + '# Object Detection App          #')
    print(Fore.WHITE + '# server2.py v0.1               #')
    print(Fore.WHITE + '# 2019                          #')
    print(Fore.WHITE + '# ============================= #')

    # Start the server
    start_server()

# ==============================================================================
# Server Loop
# ==============================================================================
def start_server():
    try:
        host = "127.0.0.1"
        port = 8888         # arbitrary non-privileged port

        soc = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
        # SO_REUSEADDR flag tells the kernel to reuse a local socket in TIME_WAIT state, without waiting for its natural timeout to expire
        soc.setsockopt(socket.SOL_SOCKET, socket.SO_REUSEADDR, 1)
        print(Fore.GREEN + "[OK] Socket created")

        try:
            soc.bind((host, port))
        except:
            print(Fore.RED + "[ERROR] Bind failed. Error : " + str(sys.exc_info()))
            sys.exit()

        soc.listen(5)       # queue up to 5 requests
        print(Fore.GREEN + "[INFO] Socket now listening")

        # infinite loop- do not reset for every requests
        while True:
            connection, address = soc.accept()
            ip, port = str(address[0]), str(address[1])
            print(Fore.WHITE + "Connected with " + ip + ":" + port)

            try:
                Thread(target=client_thread, args=(connection, ip, port)).start()
            except:
                print(Fore.RED + "[DANGER] Thread did not start.")
                traceback.print_exc()

        soc.close()

        # Close the write to file if Output mode is on.
        if mod_OutputFile == 1:
            file.close()
            print(Fore.GREEN + 'Output file closed.')

    except (KeyboardInterrupt, SystemExit):
        print(Fore.RED + '[DANGER] Program has finished.')
        exit()

# ==============================================================================
# Client Thread
# ==============================================================================
def client_thread(connection, ip, port, max_buffer_size = 5120):
    is_active = True

    while is_active:
        client_input = receive_input(connection, max_buffer_size)
        ipPort = ip + ":" + port

        if "--QUIT--" in client_input:
            print(Fore.YELLOW + "[INFO] Client is requesting to quit")
            connection.close()
            print(Fore.WHITE + "[OK] Connection " + ip + ":" + port + " closed")
            is_active = False
        else:
            print(ipPort + ": " + client_input)

            try:
                sendToDatabase(ipPort, client_input)
            except Exception as e:
                print(Fore.RED + '[DANGER] Cannot write to database.')

            # Output to file
            try:
                file = outputToFile(ipPort, client_input)
            except Exception as e:
                print(Fore.RED + '[DANGER] Cannot Output to file.')

            connection.sendall("-".encode("utf8"))

# ==============================================================================
# Recieve Input
# ==============================================================================
def receive_input(connection, max_buffer_size):
    client_input = connection.recv(max_buffer_size)
    client_input_size = sys.getsizeof(client_input)

    if client_input_size > max_buffer_size:
        print(Fore.YELLOW + "[INFO] The input size is greater than expected {}".format(client_input_size))

    decoded_input = client_input.decode("utf8").rstrip()  # decode and strip end of line
    result = process_input(decoded_input)

    return result

# ==============================================================================
# Process
# ==============================================================================
def process_input(input_str):
    # print("Processing the input received from client")

    output = str(input_str).upper()
    return output

if __name__ == "__main__":
    main()
