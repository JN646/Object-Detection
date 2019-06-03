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
import numpy as np
import cv2
import datetime
import time
import os
import geocoder
from colorama import Fore, Back, Style
from matplotlib import pyplot as plt
from mysql.connector import errorcode
from psutil import virtual_memory

# ==============================================================================
# System Management
# ==============================================================================
class SystemManagement:
    """
    Provides system and hardware related function.
    """

    # Init
    def __init__(self, systemName, systemID):
        self.systemName = systemName
        self.systemID = systemID

    # Get IP
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

    # Is device connected to the internet?
    def is_connected(self):
        try:
            # connect to the host
            s = socket.create_connection(("www.google.com", 80))
            return True
        except OSError:
            pass
        finally:
            s.close()
        return False

    # Get total RAM
    def getTotalRAM(self):
        mem = virtual_memory()
        GB = (1024**3)
        memRAM = mem.total/GB
        return memRAM  # total physical memory available

    # Get available RAM
    def getAvailableRAM(self):
        mem = virtual_memory()
        GB = (1024**3)
        memRAM = round(mem.available/GB, 2)
        return memRAM  # total physical memory available

    # Get Software Information
    def softwareInformation(self):
        print("OBJECT TRACKING SYSTEM - v0.1")
        print("System Name:",self.systemName)
        print("System IP:",self.getIP())
        print("System Connection:",self.is_connected())
        print("RAM:",self.getAvailableRAM(),"/",self.getTotalRAM())

    # Write detected object to database.
    def writeIPToDatabase(self):
        newConnection = ServerConnection("localhost","root","","objectTracker2")
        newConnection.databaseConnect()
        # newConnection.databaseInfo()

        sql = "UPDATE devices SET device_ip = %s WHERE device_id = %s"
        val = (str(SystemManagement.getIP(self)),self.systemID)

        try:
            newConnection.mycursor.execute(sql, val)
            newConnection.mydb.commit()
            newConnection.mydb.close()
        except mysql.connector.Error as err:
            print("[DANGER] Something went wrong: {}".format(err))
            sys.exit()

# ==============================================================================
# Server Connection
# ==============================================================================
class ServerConnection:
    """
    Provides SQL server related functions.
    """

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

    # Get parameters from device table
    def getDeviceParameters(self):
        newConnection = ServerConnection("localhost","root","","objectTracker2")
        newConnection.databaseConnect()

        sql = "SELECT * FROM devices WHERE device_id = 1"

        try:
            newConnection.mycursor.execute(sql)
            myresult = newConnection.mycursor.fetchall()
            print("[OK] Found Paramters")

            for x in myresult:
                return x

        except mysql.connector.Error as err:
            print("[DANGER] Something went wrong: {}".format(err))
            sys.exit(1)
        finally:
            newConnection.mycursor.close()

    # Count total number of rows in database
    def countRows(self):
        newConnection = ServerConnection("localhost","root","","objectTracker2")
        newConnection.databaseConnect()

        sql = "select * from counter"

        try:
            newConnection.mycursor.execute(sql)
            records = newConnection.mycursor.fetchall()
            rowCount = newConnection.mycursor.rowcount;
        except mysql.connector.Error as err:
            print("[DANGER] Something went wrong: {}".format(err))
            sys.exit(1)
        finally:
            newConnection.mycursor.close()

        return rowCount

    # Count total number of rows in database
    def tableTruncate(self):
        newConnection = ServerConnection("localhost","root","","objectTracker2")
        newConnection.databaseConnect()

        sql = "TRUNCATE counter"

        try:
            newConnection.mycursor.execute(sql)
            print("[OK] Truncated table")
        except mysql.connector.Error as err:
            print("[DANGER] Something went wrong: {}".format(err))
            sys.exit(1)
        finally:
            newConnection.mycursor.close()

    # Get database information
    def databaseInfo(self):
        print("DATABASE INFORMATION")
        print("Host: ",self.host)
        print("User: ",self.user)
        print("Password: ",self.passwd)
        print("Database: ",self.database)

# ==============================================================================
# Object Model
# ==============================================================================
class ObjectDetection:
    """
    Basic Object Detection Class.

    Attributes:
        objectClass: The type of object detected.
        objectTime: The time object detected.
        objectClassLabel: The text label for the object detected.
        objectConfidence: The confidence lebel of the detected object.
    """
    # Object Init.
    def __init__(self, objectDeviceID, objectClass, objectConfidence, objectLatLong, objectLocLeft, objectLocTop, objectLocRight, objectLocBottom):
        self.deviceID = objectDeviceID
        self.objectClass = objectClass
        self.objectTime = datetime.datetime.now().strftime('%Y-%m-%d %H:%M:%S.%f')[:-3]
        self.objectClassLabel = "Blank"
        self.objectConfidence = objectConfidence
        self.objectLatLong = objectLatLong
        self.objectLocLeft = objectLocLeft
        self.objectLocTop = objectLocTop
        self.objectLocRight = objectLocRight
        self.objectLocBottom = objectLocBottom

        # Load names of classes
        classesFile = "network/coco.names";
        classes = None
        with open(classesFile, 'rt') as f:
            classes = f.read().rstrip('\n').split('\n')

        self.objectClassLabel = classes[self.objectClass]

    # Get the class label from the class list.
    def getClassLabel(self):
        # Get the label from class id.
        self.objectClassLabel = classes[self.objectClass]

    # Write to file
    def writeToFile(self):
        # Map data to columns.
        row = [self.deviceID, self.objectTime, self.objectClass, str(self.objectConfidence)+"%", self.objectLatLong[0], self.objectLatLong[1], self.objectLocLeft, self.objectLocTop, self.objectLocRight, self.objectLocBottom]

        # Amend CSV.
        with open("file.csv", 'a') as file:
            writer = csv.writer(file)
            writer.writerow(row)

    # Write detected object to database.
    def writeToDatabase(self):
        newConnection = ServerConnection("localhost","root","","objectTracker2")
        newConnection.databaseConnect()
        # newConnection.databaseInfo()

        sql = "INSERT INTO counter (count_deviceID, count_class, count_time, count_confidence, count_lat, count_long, count_left, count_top, count_right, count_bottom) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)"
        val = str(self.deviceID), str(self.objectClass), str(self.objectTime), str(self.objectConfidence), self.objectLatLong[0], self.objectLatLong[1], self.objectLocLeft, self.objectLocTop, self.objectLocRight, self.objectLocBottom

        try:
            newConnection.mycursor.execute(sql, val)
            newConnection.mydb.commit()
            newConnection.mydb.close()
        except mysql.connector.Error as err:
            print("[DANGER] Something went wrong: {}".format(err))
            sys.exit()

# ==============================================================================
# Initialise the parameters
# ==============================================================================
confThreshold = 0.5                     # Confidence threshold.
nmsThreshold = 0.4                      # Non-maximum suppression threshold.
inpSize = [416,416]                     # Width and Height of network's input image.
ProcessVideo = True
frameCount = 0
frameExtractRate = 24
deviceID = 3
videoInput = 0

# Network Config
modelName = 'YOLOv3'
modelConfiguration = 'network/yolov3.cfg'
modelWeights = 'network/yolov3.weights'
classesFile = "network/coco.names";

# Modules
mod_writeToFile = 0
mod_writeToDatabase = 1

# ==============================================================================
# Get Output Names
# ==============================================================================
def getOutputsNames(net):
    layersNames = net.getLayerNames()
    return [layersNames[i[0] - 1] for i in net.getUnconnectedOutLayers()]

# ==============================================================================
# Main Sequence
# ==============================================================================
try:
    # Clear the Screen
    os.system('clear')
    print('Object Detection Application')
    print('Running...')

    # ==============================================================================
    # Main Script
    # ==============================================================================

    # OBJECT CLASS
    newConnection = ServerConnection("localhost","root","","objectTracker2")
    # newConnection.tableTruncate()
    print("Total Rows:",newConnection.countRows())
    deviceParameters = newConnection.getDeviceParameters()
    # confThreshold = float(deviceParameters[4])
    print("Device Name:",deviceParameters[1])
    print("Threshold:",deviceParameters[4])

    # SYSTEM MANAGEMENT CLASS
    newSystem = SystemManagement(deviceParameters[1],deviceParameters[0])
    newSystem.writeIPToDatabase()

    print("Device ID:",deviceID)
    # newSystem.softwareInformation()

    # Get Camera Footage
    cap = cv2.VideoCapture(videoInput)

    if not cap.isOpened():
        print('[DANGER] Cannot open camera feed.')
        sys.exit()

    # Load names of classes
    classes = None

    with open(classesFile, 'rt') as f:
        classes = f.read().rstrip('\n').split('\n')

    # Configure the network
    net = cv2.dnn.readNetFromDarknet(modelConfiguration, modelWeights)
    net.setPreferableBackend(cv2.dnn.DNN_BACKEND_OPENCV)
    net.setPreferableTarget(cv2.dnn.DNN_TARGET_CPU)

# Catch keyboard interrupts.
except (KeyboardInterrupt, SystemExit):
    print('[DANGER] Program has finished.')
    sys.exit()

# ==============================================================================
# Main Loop
# ==============================================================================
while(True):
    try:
        # Get current date and time.
        currentDT = datetime.datetime.now()

        # Read the camera feed.
        ret, frame = cap.read()

        if ProcessVideo == True:
            if frameCount % frameExtractRate == 0:
                # Create a 4D blob from a frame.
                blob = cv2.dnn.blobFromImage(frame, 1/255, (inpSize[0], inpSize[1]), [0,0,0], 1, crop=True)

                # Sets the input to the network
                net.setInput(blob)

                # Runs the forward pass to get output of the output layers
                outs = net.forward(getOutputsNames(net))

                # Remove the bounding boxes with low confidence
                # Define variables.
                frameHeight = frame.shape[0]
                frameWidth = frame.shape[1]

                # Scan through all the bounding boxes output from the network and keep only the ones with high confidence scores.
                # Assign the box's class label as the class with the highest score.
                classIds = []
                confidences = []
                boxes = []
                objectLocTop = 0
                targetCount = 0
                objectCount = 0

                for out in outs:
                    for detection in out:
                        scores = detection[5:]
                        classId = np.argmax(scores)
                        confidence = scores[classId]
                        if confidence > 0:
                            center_x = int(detection[0] * frameWidth)
                            center_y = int(detection[1] * frameHeight)
                            width = int(detection[2] * frameWidth)
                            height = int(detection[3] * frameHeight)
                            left = int(center_x - width / 2)
                            top = int(center_y - height / 2)
                            classIds.append(classId)
                            confidences.append(float(confidence))
                            boxes.append([left, top, width, height])

                    # Perform non maximum suppression to eliminate redundant overlapping boxes with lower confidences.
                    indices = cv2.dnn.NMSBoxes(boxes, confidences, confThreshold, nmsThreshold)
                    for i in indices:
                        i = i[0]
                        box = boxes[i]
                        left = box[0]
                        top = box[1]
                        right = left + width
                        bottom = top + height
                        width = box[2]
                        height = box[3]
                        conf = confidences[i]

                        objectLocLeft = left
                        objectLocTop = top
                        objectLocRight = right
                        objectLocBottom = bottom

                        # Get the label for the class name and its confidence
                        if classes:
                            assert(classId < len(classes))
                            # label = '%s %s%%' % (classes[classId], label)
                            label = str(classes[classId])

                # Collect the data for each item in the array.
                for i in range(len(classIds)):
                    roundConf = '%.8f' % confidences[i]
                    if conf > confThreshold:
                        g = geocoder.ip('me')
                        newImage = ObjectDetection(deviceID,classIds[i],roundConf,g.latlng,objectLocLeft,objectLocTop,objectLocRight,objectLocBottom)
                        print(frameCount," - ",
                        newImage.objectTime," - ",
                        newImage.objectClass," - ",
                        newImage.objectConfidence,
                        newImage.objectLatLong[0],
                        newImage.objectLatLong[1],
                        newImage.objectLocLeft,
                        newImage.objectLocTop,
                        newImage.objectLocRight,
                        newImage.objectLocBottom)

                        if mod_writeToFile == 1:
                            newImage.writeToDatabase()

                        if mod_writeToDatabase == 1:
                            newImage.writeToDatabase()

        frameCount += 1

    except (KeyboardInterrupt, SystemExit):
        print('[DANGER] Program has finished.')
        break

# ==============================================================================
# Closing Remarks
# ==============================================================================
# Release the camera feed.
print('Exiting camera feed.')
cap.release()
