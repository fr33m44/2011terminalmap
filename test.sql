#select * from kiosk_information group by kiosk_location_2

SELECT kiosk_asset_id,formatted_address,latlng,kiosk_address,kiosk_location,kiosk_location_2,KIOSK_FLOOR_ID
FROM kiosk_information  
ORDER BY kiosk_location_2,kiosk_address

SELECT kiosk_asset_id,formatted_address,latlng,kiosk_address,kiosk_location,kiosk_location_2,KIOSK_FLOOR_ID
FROM kiosk_information 
WHERE kiosk_location_2 LIKE '%环卫%'
ORDER BY kiosk_location_2,kiosk_address

SELECT kiosk_asset_id,formatted_address,latlng,kiosk_address,kiosk_location,kiosk_location_2,KIOSK_FLOOR_ID
FROM kiosk_information 
WHERE latlng LIKE '%undefined%'
ORDER BY kiosk_location_2,kiosk_address



SELECT kiosk_asset_id,formatted_address,latlng,kiosk_address,kiosk_location,kiosk_location_2,KIOSK_FLOOR_ID
FROM kiosk_information 
WHERE latlng LIKE '%undefined%'
ORDER BY kiosk_location_2

SELECT @@VERSION;

SELECT * FROM kiosk_information

UPDATE kiosk_information SET kiosk_address= REPLACE(kiosk_address ,"\n\r","");
UPDATE kiosk_information SET kiosk_address= REPLACE(kiosk_address ,"\n","");
UPDATE kiosk_information SET kiosk_address= REPLACE(kiosk_address ,"\r","");


SELECT * FROM kiosk_information WHERE formatted_address=''

SELECT KIOSK_ASSET_ID,kiosk_location_2 FROM kiosk_information WHERE kiosk_location_2 ='兰溪谷地';