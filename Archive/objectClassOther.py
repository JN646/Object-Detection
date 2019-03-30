# Setting multiple objects into an array.
my_objects = []
createObjects(my_objects)

# Printing each object
for obj in my_objects:
    obj.getClassLabel()
    obj.writeToFile()
    print(obj.objectTime, obj.objectClassLabel, str(obj.objectConfidence)+"%")

# Generate Objects
def createObjects(my_objects):
    for i in range(100):
        my_objects.append(ObjectDetection(random.randint(0,79), random.randint(1,100)))

# Load names of classes
def getLabelContent():
    classes = None
    with open("network/coco.names", 'rt') as f:
        classes = f.read().rstrip('\n').split('\n')
    return classes

# Get LabelClasses
classes = getLabelContent()
