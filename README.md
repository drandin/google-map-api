# API

Table of contents

1. [API methods](#authorization-in-the-system)
    - [map/markers](#mapmarkers)    
    - [map/people](#mappeople)

## Base information
 
Our system uses JSON format for conveying information.

## API methods

Below describes all methods of system. 

### map/markers

This method allows you retrieve all coordinates of people.

#### Response

```json
[
  {
      "id":1,
      "person_id":1,
      "latitude":55.716698,
      "longitude":37.751972,
      "fio":"Иванов Иван Иванович",
      "distance":9.290107802553953
  },
  {
      "id":2,
      "person_id":2,
      "latitude":55.723782,
      "longitude":37.16246,
      "fio":"Серафим Пётр Николаевич",
      "distance":28.65814966266335
  }
]
```

### map/people

This method allows you retrieve name of people and count repetitions. Sort by name (А-Я).

#### Response

```json
[
  {
      "name":"Иван",
      "repetitions":2,
      "ids":"15,35"
  },
  {
      "name":"Агата",
      "repetitions":1,
      "ids":"32"
  }
]
```
