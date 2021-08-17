# A programming guide for myself by myself

## An oversimplified resume

## VARIABLES

###### A way to store data

Examples:
```
age = 24;
name = "Celio";
firstLetterName = 'C';
height = 1.70;
weight = 63;
hasPets = true;
cats = ["Nina", "Ghost", "Sani"];
dogs = [];

```

## OPERATORS

###### A way to do an action or consideration
### Math
```
Add +
Sub -
Multiplication *
Division /
```

### Logical
```
Assign =
Equal ==
Less <
More >
Less or Equal <=
More or Equal >=
Different != <>
```

### Boolean
```
AND and &&
OR or ||
NOT not !
```

## CONDITIONALS

###### A way to check and compare

```
if (age < 18){
	print("Can not get driver license");
}

if (height > 1.50)
print("Can enter in Roller Coaster")

if (hasPets){
	if (cats.count > 0){
		print(cats)
	}

	if (dogs.count > 0){
		print(dogs)
	}
}

if (name == "Celio"){
	print("You are God of this dirty document")
}

if (age > 800 && height < 1.0){
	print("Are you a Jedi Master?")
}
```

## LOOPS

###### A way of using GOTO, using other special words. And say that GOTO is a bad practice, and blá blá blá

```
i = 0;
for ( i = 0; i < 10; i++){
	print(i);
}
```

```
i = 10;
while ( i > 0 ){
	print(i);
	i = i - 1;
}
```

```
i = 10;
do{
	print(i);
	i = i - 1;
}
while ( i > 0 );
```

## FUNCTIONS

###### A way to simplify and reduce code repetition

```
function bool canEnterRollerCoaster(float height){
	if (height > 1.50){
		return true;
	}
	return false;
}

function bool canEnterRollerCoaster(float height){
	return height > 1.50
}

function float calculateIMC(float height, int weight){
	return weight / (height * height)
}
```

## STRUCT

###### A way to create your own type of data

```
typedef struct{
	int age
	string name
	float height
	float weight
} Person

Person person
person.age = age
person.name = name
person.height = height
person.weight =weight
```
```
typedef struct{
	int data
	Node *next
}Node
```

## OBJECT ORIENTED

###### A way to simulate real world in computer as using concept of Objects, that has attributes and methods
### Class

###### A way abstract way to define attributes and methods
```
class Person{
	int age;
	string name;
	float height;
	float weight;

	function contruct(string name, int age, float height, float weight){
		this.age = age;
		this.name = name;
		this.height = height;
		this.weight = weight;
	}

	function bool canGetDriverLicense(){
		return this.age > 18
	}
}
```
#### Inheritance

###### An concept of parent and child
```
class Student extends Person{
	int grade;
	bool completed;
}
```
#### Encapsulation

###### A concept of private and public attributes and methods
```
class Car{
	private int year;
	private string model;
	public string color;

	public function changeColor(string newColor){
		this.color = newColor;
	}
}
```

#### Abstraction

###### Abstract actions, characteristics

- Person

	- Has name, age, height, weight

	- Can think, breathe, eat

- Car

	- Has size, color, height, weight

	- Can turn on, turn off, accelerate, brake

#### Polymorphism

###### A method can be different between objects
```
Intern extends Employee{
	public function float calcPayment(){
		return 1000.00;
	}
}

Junior extends Employee{
	public function float calcPayment(float hoursWorked){
		return hoursWorked * this.perHour;
	}
}
```

### Object

###### Instance of class

```
person = new Person(name, age, height, weight)

if (person.canGetDriverLicense()){
	print("Can get driver license")
}
```
