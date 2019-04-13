# Imports
import cv2
import os

def extractFrames(pathIn, pathOut):
    os.mkdir(pathOut)

    cap = cv2.VideoCapture(pathIn)
    count = 0
    frameExtractRate = 24

    while (cap.isOpened()):
        ret, frame = cap.read()

        # While there are frames.
        if ret == True:
            if count % frameExtractRate == 0:
                print('Read %d frame: ' % count, ret)
                cv2.imwrite(os.path.join(pathOut, "frame{:d}.jpg".format(count)), frame)  # save frame as JPEG file
            count += 1
        else:
            break

    # When everything done, release the capture
    cap.release()
    cv2.destroyAllWindows()

def main():
    # Extract Frames
    extractFrames('footage/walk.mp4', 'data')

if __name__=="__main__":
    main()
