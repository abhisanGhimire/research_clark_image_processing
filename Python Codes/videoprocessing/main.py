# all required library imported

import cv2
import numpy as np
import youtube_dl
import sys
import math
import os
import re
from random import randint

if __name__ == '__main__':
    # video_id = "To7jJ_d_kbM"
    video_id = sys.argv[1]
    # video_url = "To7jJ_d_kbM"
    video_url = sys.argv[1]
    url = "https://www.youtube.com/watch?v=" + video_id
    directory = r'C:/xampp/htdocs/processed_image_python'
    ydl_opts = {}

    # create youtube-dl object
    ydl = youtube_dl.YoutubeDL(ydl_opts)
    # set video url, extract video information
    try:
        info_dict = ydl.extract_info(video_url, download=False)
    except:
        print("Failed")
        sys.exit()
    # get video formats available
    formats = info_dict.get('formats', None)
    length = math.floor(info_dict['duration'])
    title = info_dict["title"]
    regex = re.compile('[^a-zA-Z0-9()]')
    title = regex.sub('_', title)
    # print(formats)

    for f in formats:
        # print(f.get('format_note', None))
        if f.get('format_note', None) == '144p':
            url = f.get('url', None)
            break
        if f.get('format_note', None) == '360p':
            url = f.get('url', None)
            break
        if f.get('format_note', None) == '240p':
            url = f.get('url', None)
            break
    cap = cv2.VideoCapture(url)

    # check if url was opened
    if not cap.isOpened():
        print('video not opened')
        exit(-1)

    frame_rate = math.floor(cap.get(5))
    # print(length, frame_rate)

    os.chdir(directory)
    if not (os.path.isdir(directory + "/" + title + "-" + video_id)):
        os.mkdir(title + "-" + video_id)
    os.chdir(directory + "/" + title + "-" + video_id)

    flag = 0
    if length > 28740:
        length = 28740
    gap = math.floor(length / 8)
    # print(gap)
    while True:
        # read frame
        ret, frame = cap.read()
        if flag == 0 or 1 or 2 or 3 or 4 or 5 or 6 or 7:
            point = ((flag * gap) + gap)
            cap.set(1, point * frame_rate)
        if flag == 8:
            break
        # check if frame is empty
        if ret:
            # cv2.imshow('frame', frame)
            minute = point // 60
            print(minute, length, point)
            if length < 60:
                if flag == 0:
                    file_name = "-" + str(point) + "s.png"
                else:
                    print(file_name)
                    cv2.imwrite(f"clip" + file_name, frame)
                    file_name = "-" + str(point) + "s.png"
                if cv2.waitKey(30) & 0xFF == ord('q'):
                    break
            if length > 60 and 60 > minute:
                minute = point // 60
                print(flag)
                second = point - (minute * 60)
                if flag == 0:
                    file_name = "-" + str(minute) + "m-" + str(second) + "s.png"
                else:
                    print(file_name)
                    cv2.imwrite(f"clip" + file_name, frame)
                    file_name = "-" + str(minute) + "m-" + str(second) + "s.png"
                if cv2.waitKey(30) & 0xFF == ord('q'):
                    break
            if minute >= 60:
                # print(file_name)
                hr = minute // 60
                minute = (point - (hr * 60 * 60)) // 60
                second = point - ((minute * 60) + (hr * 60 * 60))
                cv2.imwrite(f"clip" + file_name, frame)
                file_name = "-" + str(hr) + "h-" + str(minute) + "m-" + str(second) + "s.png"
                if cv2.waitKey(30) & 0xFF == ord('q'):
                    break
        else:
            break
        flag += 1
    cap.release()
    print("Success")
cv2.destroyAllWindows()
