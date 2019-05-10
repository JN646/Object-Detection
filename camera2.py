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
import mysql.connector

# Storing Objects.
class ObjectDetection:
    """Basic Object Detection Class.

    Attributes:
        objectClass: The type of object detected.
        objectTime: The time object detected.
        objectClassLabel: The text label for the object detected.
        objectConfidence: The confidence lebel of the detected object.
    """
    # Object Init.
    def __init__(self, objectClass, objectConfidence):
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
        row = [self.objectTime, self.objectClassLabel, str(self.objectConfidence)+"%"]

        # Amend CSV.
        with open("file.csv", 'a') as file:
            writer = csv.writer(file)
            writer.writerow(row)

    def writeToDatabase(self):
        mydb = mysql.connector.connect(
          host="localhost",
          user="root",
          passwd="",
          database="objectTracker2"
        )

        mycursor = mydb.cursor()

        sql = "INSERT INTO counter (count_class, count_time, count_confidence) VALUES (%s, %s, %s)"
        val = str(self.objectClassLabel), str(self.objectTime), str(self.objectConfidence)

        try:
            mycursor.execute(sql, val)
        except mysql.connector.Error as err:
            print("[DANGER] Something went wrong: {}".format(err))

        mydb.commit()

# Giving it data.
newImage = ObjectDetection(2,20);

print(newImage.objectClass," - ",newImage.objectTime);
newImage.writeToFile();
newImage.writeToDatabase();
