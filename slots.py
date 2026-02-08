# -*- coding: utf-8 -*-
"""
Created on Sun Apr 28 09:48:20 2024

@author: plats
"""

# ημερομηνία έναρξης και λήξης
start_date = '2024-10-01'
end_date = '2024-10-31'
# ώρα έναρξης και λήξης
start_time = '09:00'
end_time = '14:30'

times = []
current_date = start_date
while current_date <end_date:
    current_time = start_time
    while current_time < end_time:
        times.append(current_date+','+current_time)
        hours, minutes = map(int, current_time.split(':'))
        minutes += 30
        if minutes >= 60:
            minutes -= 60
            hours += 1
        current_time = f"{hours:02d}:{minutes:02d}"
        
    year,month,day =  map(int, current_date.split('-'))
    day+=1
    current_date = f"{year:04d}-{month:02d}-{day:02d}"
# Αποθηκεύουμε τις ώρες στο αρχείο CSV
with open('available_slots.csv', 'w') as csvfile:
    for time in times:
        csvfile.write(time+"\n")
