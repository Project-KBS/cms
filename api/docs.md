# API Documentatie

## Inhoud
- [Categorieeen](#categorieeen)
    - [Verkrijg alle categorieeen](#verkrijg-alle-categorieeen)
- [Producten](#producten)
    - [Verkrijg alle producten](#verkrijg-alle-producten)
    - [Zoek op producten](#zoek-op-producten)
- [API Info](#api-info)

## Categorieeen

#### Verkrijg alle categorieeen

Voorbeeld request:

`GET /api/v1/categorie/get`

Voorbeeld response:

```json
{ 
    "return": "array",
    "array":{ 
        "record_name": "categorie",
        "records":[ 
            { 
                "id": "1",
                "name": "Novelty Items"
            },
            { 
                "id": "2",
                "name": "Clothing"
            },
            { 
                "id": "10",
                "name": "Packaging Materials"
            }
        ]
    }
}
```

_Zie de comments in [categorie.php](/app/model/categorie.php) voor meer informatie over de attributen._

## Producten

#### Verkrijg alle producten

Voorbeeld requests:

`GET /api/v1/product/get`

`GET /api/v1/product/get?limit=50`

Query Parameters:

| Parameter | Required? | Description |
| --------- | --------- | ----------- |
| `limit` | optional, default 20 | Maximale hoeveelheid producten er gereturned moeten worden.


Voorbeeld response:

```json
{
    "return":"array",
    "array":{
        "record_name":"product",
        "records":[
            {
                "id":"75",
                "name":"Ride on big wheel monster truck",
                "supplier":"Northwind Electric Cars",
                "color":"Black",
                "package_unit":"Each",
                "package_outer":"Each",
                "qty_per_outer":"1",
                "brand":"Northwind",
                "size":"1\/12 scale",
                "lead_time":"14",
                "is_chill":"0",
                "barcode":"",
                "tax":"15.000",
                "price_unit":"345.00",
                "price_recommended":"515.78",
                "weight":"21.000",
                "comments_marketing":"Suits child to 20 kg",
                "photo":"",
                "custom_fields":"{ \"CountryOfManufacture\": \"China\", \"Tags\": [\"So Realistic\"] }",
                "tags":"[\"So Realistic\"]"
            },
            {
                "id":"73",
                "name":"Ride on vintage American toy coupe",
                "supplier":"Northwind Electric Cars",
                "color":"Red",
                "package_unit":"Each",
                "package_outer":"Each",
                "qty_per_outer":"1",
                "brand":"Northwind",
                "size":"1\/12 scale",
                "lead_time":"14",
                "is_chill":"0",
                "barcode":"",
                "tax":"15.000",
                "price_unit":"285.00",
                "price_recommended":"426.08",
                "weight":"18.000",
                "comments_marketing":"Suits child to 20 kg",
                "photo":"",
                "custom_fields":"{ \"CountryOfManufacture\": \"China\", \"Tags\": [\"Vintage\",\"So Realistic\"] }",
                "tags":"[\"Vintage\",\"So Realistic\"]"
            }
        ]
    }
}
```

_Zie de comments in [product.php](/app/model/product.php) voor meer informatie over de attributen._

#### Zoek op producten

_Geeft producten terug waarvan de naam deels overeenkomt met de zoekterm._

Voorbeeld requests:

`GET /api/v1/product/search?zoekterm=USB`

`GET /api/v1/product/search?zoekterm=USB&limit=50`

Query Parameters:

| Parameter | Required? | Description |
| --------- | --------- | ----------- |
| `zoekterm` | required | Op welk keyword te zoeken in de productnaam. HTML encoded.
| `categorie` | optional, default ignored | Categorie-specifiek zoeken. Laat weg om op het hele assortiment te zoeken.
| `order` | optional, default ignored | _(Nog niet geimplementeerd)_
| `limit` | optional, default 20 | Maximale hoeveelheid producten er gereturned moeten worden.

Voorbeeld response:

```json
{
    "return":"array",
    "array":{
        "record_name":"product",
        "records":[
            {
                "id":"75",
                "name":"Ride on big wheel monster truck",
                "supplier":"Northwind Electric Cars",
                "color":"Black",
                "package_unit":"Each",
                "package_outer":"Each",
                "qty_per_outer":"1",
                "brand":"Northwind",
                "size":"1\/12 scale",
                "lead_time":"14",
                "is_chill":"0",
                "barcode":"",
                "tax":"15.000",
                "price_unit":"345.00",
                "price_recommended":"515.78",
                "weight":"21.000",
                "comments_marketing":"Suits child to 20 kg",
                "photo":"",
                "custom_fields":"{ \"CountryOfManufacture\": \"China\", \"Tags\": [\"So Realistic\"] }",
                "tags":"[\"So Realistic\"]"
            },
            {
                "id":"73",
                "name":"Ride on vintage American toy coupe",
                "supplier":"Northwind Electric Cars",
                "color":"Red",
                "package_unit":"Each",
                "package_outer":"Each",
                "qty_per_outer":"1",
                "brand":"Northwind",
                "size":"1\/12 scale",
                "lead_time":"14",
                "is_chill":"0",
                "barcode":"",
                "tax":"15.000",
                "price_unit":"285.00",
                "price_recommended":"426.08",
                "weight":"18.000",
                "comments_marketing":"Suits child to 20 kg",
                "photo":"",
                "custom_fields":"{ \"CountryOfManufacture\": \"China\", \"Tags\": [\"Vintage\",\"So Realistic\"] }",
                "tags":"[\"Vintage\",\"So Realistic\"]"
            }
        ]
    }
}
```

_Zie de comments in [product.php](/app/model/product.php) voor meer informatie over de attributen._


## API Info

_Geeft actuele informatie over de API._

Voorbeeld request:

`GET /api/v1/api`

Voorbeeld response:

```json
{
    "return": "info",
    "info": {
        "version": "v1.0.0.0",
        "impl": "standard",
        "client": {
            "name": "Wide World Importers",
            "logo": "img/logo/small-250x90.png"
        }
    }
}
```
