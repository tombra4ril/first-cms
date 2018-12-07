<!DOCTYPE html>
	<head>
		<title>Widget Corp</title>
		<link rel="stylesheet" href="stylesheets/public.css" />
	</head>

	<body>
		<header>
			<h1>WELCOME!!!</h1>
			<div id="analog">
				<canvas style="border: 0px #eee solid; background-color: black; display: inline-block; position: fixed; right: 0px; top: 0px" id="canvas" width="100px" height="100px">Sorry your Browser does not support the CANVAS TAG</canvas>
				<script>
					//Get the canvas element then the context
					const canvas = document.getElementById("canvas");
					const canvasContext = canvas.getContext("2d");

					//Get the center of the context, create radius of the circle and set it to a new location
					const center = canvas.width / 2;
					const radius = 0.9 * center;

					//Start the analog clock
					setInterval(startClock, 1000);
					
					//Start the clock
					function startClock(){
						//Draws the face of the clock
						drawFace();
						//Draw the small black circle in the center
						drawBlackCircle();
						//Draw the numbers 1 to 12
						drawNumbers();
						//Draw the second's hand
						drawSecond();
						//Draw the minute's hand
						drawMinute();
						//Draw the hour's hand
						drawHour();
					}

					//function for the face of the clock
					function drawFace(){
						canvasContext.translate(center, center);

						//Draw the circle
						 //Don't call this method because it will translate the coordinates of the canvas to (0, 0)
						canvasContext.beginPath();
						canvasContext.arc(0, 0, radius, 0, 2 * Math.PI);
						canvasContext.fillStyle = "white";
						canvasContext.fill();
					}

					//Function to draw a small black circle
					function drawBlackCircle(){
						//Reset the origin
						canvasContext.beginPath();

						canvasContext.arc(0, 0, 5, 0, 2 * Math.PI);
						canvasContext.fillStyle = "black";
						canvasContext.fill();
					}

					//Functio to draw the numbers 1 to 12
					function drawNumbers(){
						//In degress from 12 O'clock to 1 O'clock
						const oneSegmentDegree = toDegree(12);;
						//Conver oneNumberDegree to radian
						const toRadian = oneSegmentDegree * (Math.PI / 180);

						canvasContext.font = "10px ariel";
						canvasContext.textBaseline = "middle";
		      			canvasContext.textAlign = "center";

						for(let item = 1; item <= 12; item++){
							//Rotate the context at the center
							let angle = item * toRadian;//Angle to rotate
							canvasContext.rotate(angle);
							//Translate to the new location before drawing the text
							let y = -1 * (radius - (0.1 * radius));
							canvasContext.translate(0, y);
							//Rotate the context at the new location
							canvasContext.rotate(-angle);
							//Draw text now
							canvasContext.fillText(item.toString(), 0, 0);

							//Translate the context to the center of the circle 
							//Rotate the context at the new location
							canvasContext.rotate(angle);
							//Translate back to the center of the circle
							canvasContext.translate(0, -1 * y);
							//Rotate the context at the new location
							canvasContext.rotate(-angle);
						}

						canvasContext.translate(-center, -center);
					}

					//Convers to Degrees depending on the segments
					function toDegree(degree = 60){
						return (360 / degree);
					}

					//Draw the seconds hand
					function drawSecond(){
						//Create the current date
						let date = new Date();
						//Set the segment
						const segment = 60;
						 //Get the current seconds
						let second = date.getSeconds() % segment;
						

						//Calculate the seconds in times of radian
						const segmentDegree = second * toDegree(segment);//Find the segments in degrees
						const toRadian = segmentDegree * (Math.PI / 180);

						//Translate the coordinate first
						canvasContext.translate(center, center);

						//Draw the seconds line
						canvasContext.lineWidth = "1";
						canvasContext.lineCap = "round";
						canvasContext.strokeStyle = "black";
						canvasContext.beginPath();
						canvasContext.moveTo(0, 0);
						canvasContext.rotate(toRadian);
						canvasContext.lineTo(0, (-0.8 * radius));
						canvasContext.stroke();
						canvasContext.rotate(-1 * toRadian);

						//Translate the coordinate back
						canvasContext.translate(-center, -center);
					}

					//Draw the minutes hand
					function drawMinute(){
						//Create the current date
						let date = new Date();
						//Get the current seconds
						let minute = date.getMinutes();
						//Set the segment
						const segment = 60;

						//Calculate the seconds in times of radian
						const segmentDegree = minute * toDegree(segment);//Find the segments in degrees
						const toRadian = segmentDegree * (Math.PI / 180);

						//Translate the coordinate first
						canvasContext.translate(center, center);

						//Draw the seconds line
						canvasContext.lineWidth = "2";
						canvasContext.lineCap = "round";
						canvasContext.strokeStyle = "green";
						canvasContext.beginPath();
						canvasContext.moveTo(0, 0);
						canvasContext.rotate(toRadian);
						canvasContext.lineTo(0, (-0.7 * radius));
						canvasContext.stroke();
						canvasContext.rotate(-1 * toRadian);

						//Translate the coordinate back
						canvasContext.translate(-center, -center);
					}

					//Draw the hours hand
					function drawHour(){
						//Create the current date
						let date = new Date();
						//Get the current seconds
						let hour = date.getHours() % 12;
						//Set the segment
						const segment = 12;

						//Calculate the seconds in times of radian
						const segmentDegree = hour * toDegree(segment);//Find the segments in degrees
						const toRadian = segmentDegree * (Math.PI / 180);

						//Translate the coordinate first
						canvasContext.translate(center, center);

						//Draw the seconds line
						canvasContext.lineWidth = "4";
						canvasContext.lineCap = "round";
						canvasContext.strokeStyle = "red"
						canvasContext.beginPath();
						canvasContext.moveTo(0, 0);
						canvasContext.rotate(toRadian);
						canvasContext.lineTo(0, (-0.5 * radius));
						canvasContext.stroke();
						canvasContext.rotate(-1 * toRadian);

						//Translate the coordinate back
						canvasContext.translate(-center, -center);
					}
				</script>
			</div>
		</header>