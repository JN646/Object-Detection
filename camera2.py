# ==============================================================================
# Project:      Object Detection Application
# File name:    object_class.py
# Author:       JGinn DAlexander
# Year:         2019
# ==============================================================================

# ==============================================================================
# Imports
# ==============================================================================
import sys
import datetime
import csv
import random
import socket
import mysql.connector
from mysql.connector import errorcode
from psutil import virtual_memory

# ==============================================================================
# System Management
# ==============================================================================
class SystemManagement:
    """docstring for SystemManagement."""

    def __init__(self, systemName):
        self.systemName = systemName

    def getIP(self):
        s = socket.socket(socket.AF_INET, socket.SOCK_DGRAM)
        try:
            # doesn't even have to be reachable
            s.connect(('10.255.255.255', 1))
            IP = s.getsockname()[0]
        except:
            IP = '127.0.0.1'
        finally:
            s.close()
        return IP

    def is_connected(self):
        try:
            # connect to the host
            socket.create_connection(("www.google.com", 80))
            return True
        except OSError:
            pass
        return False

    def getTotalRAM(self):
        mem = virtual_memory()
        GB = (1024*1024*1024)
        memRAM = mem.total/GB
        return memRAM  # total physical memory available

    def getAvailableRAM(self):
        mem = virtual_memory()
        GB = (1024*1024*1024)
        memRAM = round(mem.available/GB, 2)
        return memRAM  # total physical memory available

# ==============================================================================
# Server Connection
# ==============================================================================
class ServerConnection:
    """docstring for ServerConnection."""

    # Init
    def __init__(self, host, user, passwd, database):
        self.host = host
        self.user = user
        self.passwd = passwd
        self.database = database

    # Connect to database
    def databaseConnect(self):
        try:
            self.mydb = mysql.connector.connect(
              host=self.host,
              user=self.user,
              passwd=self.passwd,
              database=self.database
            )

            self.mycursor = self.mydb.cursor()

        except mysql.connector.Error as err:
            print("[DANGER] Something went wrong: {}".format(err))
            sys.exit(1)

    # Get database information
    def databaseInfo(self):
        print("Database Information")
        print("Host: ",self.host)
        print("User: ",self.user)
        print("Password: ",self.passwd)
        print("Database: ",self.database)

# ==============================================================================
# Object Model
# ==============================================================================
class ObjectDetection:
    """Basic Object Detection Class.

    Attributes:
        objectClass: The type of object detected.
        objectTime: The time object detected.
        objectClassLabel: The text label for the object detected.
        objectConfidence: The confidence lebel of the detected object.
    """
    # Object Init.
    def __init__(self, objectDeviceID, objectClass, objectConfidence):
        self.deviceID = objectDeviceID
        self.objectClass = objectClass
        self.objectTime = datetime.datetime.now()
        self.objectClassLabel = "Blank"
        self.objectConfidence = objectConfidence

    # Get the class label from the class list.
    def getClassLabel(self):
        # Get the label from class id.
        self.objectClassLabel = classes[self.objectClass]

    def writeToFile(self):
        # Map data to columns.
        row = [self.deviceID, self.objectTime, self.objectClassLabel, str(self.objectConfidence)+"%"]

        # Amend CSV.
        with open("file.csv", 'a') as file:
            writer = csv.writer(file)
            writer.writerow(row)

    # Write detected object to database.
    def writeToDatabase(self):
        newConnection = ServerConnection("localhost","root","","objectTracker2")
        newConnection.databaseConnect()
        newConnection.databaseInfo()

        sql = "INSERT INTO counter (count_deviceID, count_class, count_time, count_confidence) VALUES (%s, %s, %s, %s)"
        val = str(self.deviceID), str(self.objectClassLabel), str(self.objectTime), str(self.objectConfidence)

        try:
            newConnection.mycursor.execute(sql, val)
            newConnection.mydb.commit()
        except mysql.connector.Error as err:
            print("[DANGER] Something went wrong: {}".format(err))
            sys.exit()

# ==============================================================================
# Main Script
# ==============================================================================
newImage = ObjectDetection(1,2,20)

newSystem = SystemManagement("Client1")
print("System Name:",newSystem.systemName)
print("System IP:",newSystem.getIP())
print("System Connection:",newSystem.is_connected())
print("Total RAM:",newSystem.getTotalRAM(),"GB")
print("Available RAM:",newSystem.getAvailableRAM(),"GB")

# print(newImage.objectClass," - ",newImage.objectTime);
# newImage.writeToFile();
# newImage.writeToDatabase();
