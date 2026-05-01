# Recording Milage

When a user records a journey this is the information they supply:
- Date
- Journey Description
- Vehicle Used
- miles/km travelled
- hours driven

## Journey Description examples:
home to London to home
Round trip to London
Londong - Luton

'Vehicle Used' will be pulled from a lookup table specific to the user.

miles/km travelled values are stored internally as km.

## The Vehicle Used Lookup table

The user defines the vehicles they use in a look up table with the following columns
- Vehicle Type
- Name / registration information

Vehicle Type can be one of
- Company car with fuel card
- Company car
- Private car
- Rental car

The user can also specify their default car. This is the vehicle that the 'Vehicle Used' field is set to when they add a new journey.
