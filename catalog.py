from recombee_api_client.api_client import *
from recombee_api_client.api_requests import *
import json
import csv
import time

def send_with_delay(client, request):
    print(f"Sending request: {request.__class__.__name__}")
    response = client.send(request)
    time.sleep(3)
    return response

client = RecombeeClient('flaviu-dev', 'yO02H30kHYTnkNFgXNslwpANvptPXWPp8oUo6XDKeQGAaEMQk7utEdMnx84wCnyJ', region=Region.EU_WEST)

# send_with_delay(client, ResetDatabase())

# send_with_delay(client, AddItemProperty('Brand', 'string'))
# send_with_delay(client, AddItemProperty('Model_Name', 'string'))
# send_with_delay(client, AddItemProperty('Processor', 'string'))
# send_with_delay(client, AddItemProperty('Operating_System', 'string'))
# send_with_delay(client, AddItemProperty('Storage', 'string'))
# send_with_delay(client, AddItemProperty('RAM', 'string'))
# send_with_delay(client, AddItemProperty('Screen_Size', 'string'))
# send_with_delay(client, AddItemProperty('Touch_Screen', 'boolean'))
# send_with_delay(client, AddItemProperty('Price', 'double'))

requests = []
with open('Laptops.csv', encoding='utf-8') as f:
    reader = csv.DictReader(f)
    for i, row in enumerate(reader):
        values = {
            'Brand': row['Brand'],
            'Model_Name': row['Model Name'],
            'Processor': row['Processor'],
            'Operating_System': row['Operating System'],
            'Storage': row['Storage'],
            'RAM': row['RAM'],
            'Screen_Size': row['Screen Size'],
            'Touch_Screen': True if row['Touch_Screen'] == 'Yes' else False,
            'Price': float(row['Price'].replace('₹', '').replace(',', '')),
        }
        r = SetItemValues(i, values, cascade_create=True)
        requests.append(r)

res = send_with_delay(client, Batch(requests))
print(res)